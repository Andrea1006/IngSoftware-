<?php
$host = "localhost";
$db_username = "root";
$db_password = "";
$database = "login_system";

try {
    $conn = new PDO("mysql:host=$host;dbname=$database", $db_username, $db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}
?>
