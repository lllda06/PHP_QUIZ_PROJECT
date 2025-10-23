<?php
// config.php - połączenie z bd,
// W innych plikach zamiast pisać połączenie od nowa — wystarczy require 'config.php';.
session_start();

$DB_HOST = '127.0.0.1';
$DB_NAME = 'quizdb';
$DB_USER = 'root';
$DB_PASS = '';
$DSN = "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4";

try {
    $pdo = new PDO($DSN, $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die("DB connection failed: " . $e->getMessage());
}

// helpery
function is_logged_in() {
    return !empty($_SESSION['admin_id']);
}
?>
