<?php
require '../config.php';
if (!is_logged_in()) { header('Location: login.php'); exit; }

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id) {
    $stmt = $pdo->prepare("DELETE FROM questions WHERE id = :id");
    $stmt->execute([':id' => $id]);
}
header('Location: dashboard.php');
exit;
?>
