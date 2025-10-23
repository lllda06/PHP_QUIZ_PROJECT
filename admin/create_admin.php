<?php
require '../config.php';

$username = 'admin';
$password = 'qwerty';

$password_hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO users (username, password_hash) VALUES (:u, :p)");
try {
    $stmt->execute([':u' => $username, ':p' => $password_hash]);
    echo "Admin utworzony.";
} catch (PDOException $e) {
    echo "Błąd: " . $e->getMessage();
}
?>
