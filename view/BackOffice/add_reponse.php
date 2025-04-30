<?php
// Include the necessary files
require_once __DIR__ . '/../../model/reponse.php';
require_once __DIR__ . '/../../controller/ReponseController.php';
require_once __DIR__ . '/../../controller/ReclamationController.php';

$reclamation_id = isset($_GET['reclamation_id']) ? $_GET['reclamation_id'] : null;
$admin_id = null;

// Fetch the reclamation if required
$controller = new ReclamationController();
$reclamation = null;
if ($reclamation_id) {
    $reclamation = $controller->getReclamationById($reclamation_id);
}

// Form submission handling with validation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Initialize validation flags
    $isValid = true;
    $validationErrors = [];
    
    // Validate content
    if (empty($_POST['contenu'])) {
        $isValid = false;
        $validationErrors[] = "Le contenu de la réponse est obligatoire.";
    } elseif (strlen($_POST['contenu']) < 10) {
        $isValid = false;
        $validationErrors[] = "La réponse doit contenir au moins 10 caractères.";
    }
    
    // Validate that either file or camera image is provided
    if (empty($_POST['camera_image']) && (!isset($_FILES['piece_jointe']) || $_FILES['piece_jointe']['error'] !== UPLOAD_ERR_OK)) {
        $isValid = false;
        $validationErrors[] = "Veuillez fournir une pièce jointe ou prendre une photo.";
    }
    
    // Validate file if uploaded
    if (isset($_FILES['piece_jointe']) && $_FILES['piece_jointe']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
        $maxSize = 2 * 1024 * 1024; // 2MB
        
        if ($_FILES['piece_jointe']['size'] > $maxSize) {
            $isValid = false;
            $validationErrors[] = "Le fichier est trop volumineux (max 2MB).";
        }
        
        if (!in_array($_FILES['piece_jointe']['type'], $allowedTypes)) {
            $isValid = false;
            $validationErrors[] = "Type de fichier non autorisé (seuls JPEG, PNG, GIF et PDF sont acceptés).";
        }
    }
    
    // If validation passes, process the form
    if ($isValid) {
        try {
            $reponseController = new ReponseController();
            $imagePath = null;

            // Handle camera image
            if (!empty($_POST['camera_image'])) {
                $imageData = $_POST['camera_image'];
                $filename = 'camera_' . time() . '.jpg';
                $imagePath = 'uploads/' . $filename;
                
                // Remove the "data:image/jpeg;base64," part
                $imageData = str_replace('data:image/jpeg;base64,', '', $imageData);
                $imageData = str_replace(' ', '+', $imageData);
                $imageBinary = base64_decode($imageData);
                
                // Ensure uploads directory exists
                if (!file_exists('uploads')) {
                    mkdir('uploads', 0777, true);
                }
                
                file_put_contents($imagePath, $imageBinary);
            }
            // Handle file upload
            elseif (isset($_FILES['piece_jointe']) && $_FILES['piece_jointe']['error'] === UPLOAD_ERR_OK) {
                $filename = basename($_FILES['piece_jointe']['name']);
                $imagePath = 'uploads/' . $filename;
                
                // Ensure uploads directory exists
                if (!file_exists('uploads')) {
                    mkdir('uploads', 0777, true);
                }
                
                move_uploaded_file($_FILES['piece_jointe']['tmp_name'], $imagePath);
            }
            
            $reponse = new Reponse(
                $reclamation_id,
                $admin_id,
                $_POST['contenu'],
                $imagePath
            );
            $reponse->setDateCreation(date('Y-m-d H:i:s'));
            
            $reponseController->addReponse($reponse);
            
            // Redirect after successful submission
            header("Location: view_reponse.php");
            exit();
        } catch (Exception $e) {
            // Handle any errors that occur during processing
            die("Erreur: " . $e->getMessage());
        }
    } else {
        // Display validation errors (will be shown before the form)
        foreach ($validationErrors as $error) {
            echo "<div style='color: red; margin-bottom: 15px;'>$error</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Réponse</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f9;
            color: #333;
            padding: 20px;
        }

        .form-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.47);
        }

        h3 {
            text-align: center;
            margin-bottom: 20px;
            color:rgb(0, 0, 0);
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: 500;
            margin-bottom: 5px;
            color: #444;
        }

        input[type="text"],
        textarea,
        input[type="file"] {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 16px;
            width: 100%;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:disabled,
        textarea:disabled {
            background-color: #f1f1f1;
        }

        input[type="text"]:focus,
        textarea:focus {
            outline: none;
            border-color:rgb(170, 8, 8);
        }

        button {
            padding: 12px 20px;
            background-color:rgb(170, 8, 8);
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color:rgb(170, 8, 8);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .disabled-input {
            background-color: #e9ecef;
        }

        .error-message {
            color: #d9534f;
            background-color: #f2dede;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ebccd1;
            border-radius: 4px;
        }

        .error-message ul {
            margin: 5px 0 5px 20px;
        }

        .error-field {
            border-color: #d9534f !important;
        }

        .validation-error {
            color: #d9534f;
            font-size: 14px;
            margin-top: -10px;
            margin-bottom: 10px;
            display: none;
        }

        .file-options {
            display: flex;
            margin-bottom: 10px;
            gap: 10px;
        }

        .option-btn {
            padding: 8px 15px;
            background-color: #e0e0e0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .option-btn.active {
            background-color:rgb(170, 8, 8);
            color: white;
        }

        .capture-btn {
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            margin-top: 10px;
            cursor: pointer;
        }

        #photoPreview img {
            max-width: 100%;
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        #video {
            background-color: #ddd;
            border-radius: 4px;
            width: 100%;
            max-height: 300px;
        }

        #cameraSection {
            display: none;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h3>Répondre à la Réclamation</h3>

    <?php if (!empty($validationErrors)): ?>
        <div class="error-message">
            <ul>
                <?php foreach ($validationErrors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form id="responseForm" action="" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="reclamation_id" value="<?php echo htmlspecialchars($reclamation_id); ?>">
        <input type="hidden" name="admin_id" value="<?php echo htmlspecialchars($admin_id); ?>">

        <div class="form-group">
            <label>Réclamation ID:</label>
            <input type="text" value="<?php echo htmlspecialchars($reclamation['id'] ?? ''); ?>" class="disabled-input" disabled>
        </div>

        <div class="form-group">
            <label for="contenu">Votre Réponse:</label>
            <textarea name="contenu" id="contenu" rows="5" required><?php echo htmlspecialchars($_POST['contenu'] ?? ''); ?></textarea>
            <div id="contenuError" class="validation-error">La réponse doit contenir au moins 10 caractères.</div>
        </div>

        <div class="form-group">
            <label>Date de la Réponse:</label>
            <input type="text" value="<?php echo date('Y-m-d H:i:s'); ?>" class="disabled-input" disabled>
        </div>

        <div class="form-group">
            <label>Attach File:</label>
            <div class="file-options">
                <button type="button" id="uploadBtn" class="option-btn active">Upload File</button>
                <button type="button" id="cameraBtn" class="option-btn">Use Camera</button>
            </div>
            
            <div id="uploadSection">
                <input type="file" name="piece_jointe" id="piece_jointe" accept="image/jpeg,image/png,image/gif,application/pdf">
                <div id="fileError" class="validation-error"></div>
            </div>

            <div id="cameraSection">
                <video id="video" width="100%" autoplay></video>
                <canvas id="canvas" style="display:none;"></canvas>
                <button type="button" id="captureBtn" class="capture-btn">Take Photo</button>
                <input type="hidden" name="camera_image" id="camera_image">
                <div id="photoPreview"></div>
            </div>
        </div>

        <button type="submit" class="submit-btn">Envoyer</button>
    </form>
</div>

<script>
    // DOM Elements
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const captureBtn = document.getElementById('captureBtn');
    const uploadBtn = document.getElementById('uploadBtn');
    const cameraBtn = document.getElementById('cameraBtn');
    const uploadSection = document.getElementById('uploadSection');
    const cameraSection = document.getElementById('cameraSection');
    const photoPreview = document.getElementById('photoPreview');
    const cameraImage = document.getElementById('camera_image');
    const contenu = document.getElementById('contenu');
    const contenuError = document.getElementById('contenuError');
    const fileInput = document.getElementById('piece_jointe');
    const fileError = document.getElementById('fileError');
    const form = document.getElementById('responseForm');

    let stream = null;

    // Toggle between upload and camera
    uploadBtn.addEventListener('click', () => {
        uploadBtn.classList.add('active');
        cameraBtn.classList.remove('active');
        uploadSection.style.display = 'block';
        cameraSection.style.display = 'none';
        stopCamera();
    });

    cameraBtn.addEventListener('click', () => {
        cameraBtn.classList.add('active');
        uploadBtn.classList.remove('active');
        uploadSection.style.display = 'none';
        cameraSection.style.display = 'block';
        startCamera();
    });

    // Start camera
    function startCamera() {
        stopCamera(); // Stop any existing stream
        
        navigator.mediaDevices.getUserMedia({ 
            video: { 
                width: { ideal: 1280 },
                height: { ideal: 720 },
                facingMode: 'environment' 
            } 
        })
        .then(function(s) {
            stream = s;
            video.srcObject = stream;
        })
        .catch(function(err) {
            console.error("Camera error: ", err);
            alert("Could not access the camera. Please check permissions.");
            uploadBtn.click(); // Fall back to upload
        });
    }

    // Stop camera
    function stopCamera() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            video.srcObject = null;
            stream = null;
        }
    }

    // Capture photo
    captureBtn.addEventListener('click', function() {
        const context = canvas.getContext('2d');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        
        // Convert to data URL
        const imageDataUrl = canvas.toDataURL('image/jpeg', 0.8); // 0.8 quality
        cameraImage.value = imageDataUrl;
        
        // Show preview
        photoPreview.innerHTML = '<img src="' + imageDataUrl + '" alt="Captured Photo">';
        
        // Stop camera after capture
        stopCamera();
        
        // Validate the captured image
        validateFile();
    });

    // Form validation
    function validateContenu() {
        if (contenu.value.trim() === '') {
            contenu.classList.add('error-field');
            contenuError.textContent = "Le contenu de la réponse est obligatoire.";
            contenuError.style.display = 'block';
            return false;
        } else if (contenu.value.length < 10) {
            contenu.classList.add('error-field');
            contenuError.textContent = "La réponse doit contenir au moins 10 caractères.";
            contenuError.style.display = 'block';
            return false;
        } else {
            contenu.classList.remove('error-field');
            contenuError.style.display = 'none';
            return true;
        }
    }

    function validateFile() {
        // Check if using camera and has image
        if (cameraSection.style.display !== 'none' && cameraImage.value) {
            fileError.style.display = 'none';
            fileInput.classList.remove('error-field');
            return true;
        }
        
        // Check if file is uploaded
        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
            const maxSize = 2 * 1024 * 1024; // 2MB
            
            if (file.size > maxSize) {
                fileInput.classList.add('error-field');
                fileError.textContent = "Le fichier est trop volumineux (max 2MB).";
                fileError.style.display = 'block';
                return false;
            }
            
            if (!allowedTypes.includes(file.type)) {
                fileInput.classList.add('error-field');
                fileError.textContent = "Type de fichier non autorisé (seuls JPEG, PNG, GIF et PDF sont acceptés).";
                fileError.style.display = 'block';
                return false;
            }
            
            fileInput.classList.remove('error-field');
            fileError.style.display = 'none';
            return true;
        }
        
        // If neither camera image nor file is provided
        fileInput.classList.add('error-field');
        fileError.textContent = "Veuillez fournir une pièce jointe ou prendre une photo.";
        fileError.style.display = 'block';
        return false;
    }

    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const isContenuValid = validateContenu();
        const isFileValid = validateFile();
        
        if (isContenuValid && isFileValid) {
            this.submit();
        } else {
            // Scroll to the first error
            if (!isContenuValid) {
                contenu.scrollIntoView({ behavior: 'smooth', block: 'center' });
            } else if (!isFileValid) {
                (cameraSection.style.display !== 'none' ? captureBtn : fileInput).scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'center' 
                });
            }
        }
    });

    // Real-time validation
    contenu.addEventListener('input', validateContenu);
    fileInput.addEventListener('change', validateFile);

    // Clean up camera when leaving page
    window.addEventListener('beforeunload', stopCamera);
    window.addEventListener('pagehide', stopCamera);

    // Initialize to upload mode
    uploadBtn.click();
</script>

</body>
</html>