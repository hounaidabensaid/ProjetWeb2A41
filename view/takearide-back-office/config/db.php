<?php
class Database {
    public static function getConnection() {
        return new mysqli('localhost', 'root', '', '123'); // <- le nom de ta base de données ici
    }
}
