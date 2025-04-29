<?php
class config
{
    private static $pdo = null;

    public static function getConnexion()
    {
        if (!isset(self::$pdo)) {
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "123";
            try {
                self::$pdo = new PDO(
                    "mysql:host=$servername;dbname=$dbname",
                    $username,
                    $password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ]
                );
                // ✅ Ne rien afficher ici
            } catch (Exception $e) {
                // ✅ Retourner l'erreur sous forme JSON uniquement si tu fais un script API
                http_response_code(500);
                echo json_encode(['error' => 'Erreur de connexion à la base de données.']);
                exit;
            }
        }
        return self::$pdo;
    }
}
?>
