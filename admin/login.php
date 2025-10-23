<?php
require '../config.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :u LIMIT 1");
    $stmt->execute([':u' => $username]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['admin_user'] = $user['username'];
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Nieprawidłowe dane logowania.';
    }
}
?>

<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Login admin</title>
    <style>
      /* assets/style.css */

body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background: linear-gradient(135deg, #667eea, #764ba2);
  color: #fff;
  margin: 0;
  height: 100vh;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}

h1 {
  text-align: center;
  font-weight: 700;
  margin-bottom: 25px;
  font-size: 2rem;
  text-shadow: 0 2px 5px rgba(0,0,0,0.3);
}

form {
  background: rgba(255, 255, 255, 0.12);
  padding: 30px 40px;
  border-radius: 12px;
  box-shadow: 0 8px 24px rgba(0,0,0,0.25);
  width: 320px;
  box-sizing: border-box;
}

label {
  display: block;
  margin-bottom: 18px;
  font-size: 1.1rem;
  cursor: pointer;
}

input[type="text"],
input[type="password"] {
  width: 100%;
  padding: 10px 12px;
  margin-top: 6px;
  border-radius: 8px;
  border: none;
  font-size: 1rem;
  box-sizing: border-box;
  outline: none;
  transition: box-shadow 0.3s ease;
}

input[type="text"]:focus,
input[type="password"]:focus {
  box-shadow: 0 0 8px 2px #ffb347;
  background: rgba(255,255,255,0.2);
}

button {
  width: 100%;
  padding: 14px 0;
  background: #ffb347;
  border: none;
  border-radius: 30px;
  font-size: 1.2rem;
  font-weight: 700;
  color: #333;
  cursor: pointer;
  box-shadow: 0 5px 15px rgba(255,179,71,0.7);
  transition: background 0.3s ease, box-shadow 0.3s ease;
}

button:hover {
  background: #ffa726;
  box-shadow: 0 8px 20px rgba(255,167,38,0.9);
}

p.error {
  background: rgba(255, 0, 0, 0.8);
  color: white;
  padding: 10px 15px;
  border-radius: 8px;
  margin-bottom: 20px;
  text-align: center;
  font-weight: 600;
  box-shadow: 0 2px 8px rgba(255, 0, 0, 0.6);
}

a {
  display: inline-block;
  margin-top: 30px;
  text-decoration: none;
  background: #ffb347;
  color: #333;
  padding: 12px 24px;
  border-radius: 30px;
  font-weight: 700;
  box-shadow: 0 6px 20px rgba(255,179,71,0.75);
  transition: background-color 0.3s ease, box-shadow 0.3s ease;
}
p {
  text-align: center;
}

    </style>
  </head>
  <body>
  <h1>Logowanie (admin)</h1>
  <?php if ($error) echo "<p class='error'>".htmlspecialchars($error)."</p>"; ?>
  <form method="post">
    <label>Użytkownik: <input type="text" name="username"></label><br>
    <label>Hasło: <input type="password" name="password"></label><br>
    <button type="submit">Zaloguj</button>
    <p><a href="index.php">Wróć do strony głównej</a></p>
  </form>
</body>
</html>
