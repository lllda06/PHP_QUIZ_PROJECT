<?php
require '../config.php';
if (!is_logged_in()) {
    header('Location: login.php');
    exit;
}

$stmt = $pdo->query("SELECT q.id, q.question_text, q.image_path, (SELECT COUNT(*) FROM answers a WHERE a.question_id = q.id) as cnt FROM questions q ORDER BY q.created_at DESC");
$questions = $stmt->fetchAll();
?>
<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Panel admin</title>
    <style>
      body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background: linear-gradient(135deg, #667eea, #764ba2);
  color: #fff;
  margin: 0;
  padding: 40px 20px;
  justify-content: center;
  align-items: flex-start;
  min-height: 100vh;
}

h1 {
  font-size: 2rem;
  text-align: center;
  margin-bottom: 25px;
  text-shadow: 0 2px 5px rgba(0,0,0,0.3);
}

.container {
  background: rgba(255, 255, 255, 0.1);
  padding: 30px 40px;
  border-radius: 12px;
  box-shadow: 0 8px 24px rgba(0,0,0,0.25);
  max-width: 900px;
  width: 100%;
  box-sizing: border-box;
}

p {
  text-align: center;
  margin-bottom: 20px;
}

a {
  color: #ffcc80;
  text-decoration: none;
  font-weight: 600;
  transition: color 0.2s ease;
}

a:hover {
  color: #ffe0b2;
}

a.button {
  background: #ffb347;
  color: #333;
  padding: 10px 20px;
  border-radius: 30px;
  display: inline-block;
  font-weight: bold;
  box-shadow: 0 5px 15px rgba(255,179,71,0.7);
  transition: background 0.3s ease, box-shadow 0.3s ease;
  margin-bottom: 20px;
}

a.button:hover {
  background: #ffa726;
  box-shadow: 0 8px 20px rgba(255,167,38,0.9);
}

table {
  width: 100%;
  border-collapse: collapse;
  background: rgba(255, 255, 255, 0.12);
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 6px 18px rgba(0,0,0,0.2);
}

table th,
table td {
  padding: 12px 16px;
  text-align: left;
  border-bottom: 1px solid rgba(255,255,255,0.2);
}

table th {
  background: rgba(0,0,0,0.2);
  font-weight: bold;
}

table td {
  font-size: 0.95rem;
}

table tr:hover {
  background: rgba(255,255,255,0.1);
}

.actions a {
  margin-right: 10px;
  color: #ffcc80;
}

.actions a:hover {
  color: #fff;
}
    </style>
  </head>
  <body>
  <h1>Panel administracyjny</h1>
  <p>Zalogowany jako: <?php echo htmlspecialchars($_SESSION['admin_user']); ?> | <a href="logout.php">Wyloguj</a> | <a href="../index.php">Wróć do strony głównej</a></p>

  <p><a href="add_question.php">Dodaj pytanie</a></p>

  <table border="1" cellpadding="6">
    <tr><th>ID</th><th>Treść</th><th>Obraz</th><th>Ilość odpowiedzi</th><th>Akcje</th></tr>
    <?php foreach ($questions as $q): ?>
      <tr>
        <td><?php echo $q['id']; ?></td>
        <td><?php echo htmlspecialchars(mb_strimwidth($q['question_text'], 0, 80, '...')); ?></td>
        <td><?php echo $q['image_path'] ? 'tak' : '—'; ?></td>
        <td><?php echo $q['cnt']; ?></td>
        <td>
          <a href="edit_question.php?id=<?php echo $q['id']; ?>">Edytuj</a> |
          <a href="delete_question.php?id=<?php echo $q['id']; ?>" onclick="return confirm('Usuń?')">Usuń</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</body></html>
