<?php
require 'config.php';
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Test - wybierz ilość pytań</title>
  <link rel="stylesheet" href="assets/style.css">
  <style>
        body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background: linear-gradient(135deg, #667eea, #764ba2);
  color: #fff;
  margin: 0;
  padding: 0;
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
}

.container {
  background: rgba(255, 255, 255, 0.1);
  padding: 30px 40px;
  border-radius: 12px;
  box-shadow: 0 8px 24px rgba(0,0,0,0.2);
  max-width: 400px;
  width: 100%;
  text-align: center;
}

h1 {
  margin-bottom: 25px;
  font-weight: 700;
  font-size: 1.8rem;
  letter-spacing: 1.2px;
  text-shadow: 0 2px 5px rgba(0,0,0,0.3);
}

form label {
  display: block;
  background: rgba(255, 255, 255, 0.15);
  margin: 10px 0;
  padding: 12px 15px;
  border-radius: 8px;
  cursor: pointer;
  font-size: 1.1rem;
  transition: background 0.3s ease;
  user-select: none;
  box-shadow: 0 2px 6px rgba(0,0,0,0.15);
}

form label:hover {
  background: rgba(255, 255, 255, 0.3);
}

input[type="radio"] {
  margin-right: 12px;
  accent-color: #ffb347;
  cursor: pointer;
  transform: scale(1.2);
  vertical-align: middle;
}

button {
  background: #ffb347;
  border: none;
  padding: 14px 40px;
  border-radius: 30px;
  font-weight: 700;
  color: #333;
  font-size: 1.2rem;
  cursor: pointer;
  box-shadow: 0 5px 15px rgba(255,179,71,0.6);
  transition: background 0.3s ease, box-shadow 0.3s ease;
  margin-top: 25px;
  width: 100%;
}

button:hover {
  background: #ffa726;
  box-shadow: 0 8px 20px rgba(255,167,38,0.8);
}

hr {
  border: none;
  height: 1px;
  background: rgba(255, 255, 255, 0.3);
  margin: 35px 0 20px;
}

a {
  color: #ffb347;
  text-decoration: none;
  font-weight: 600;
  transition: color 0.3s ease;
}

a:hover {
  color: #ffa726;
  text-decoration: underline;
}

/* Responsywność */
@media (max-width: 480px) {
  .container {
    padding: 25px 20px;
    max-width: 320px;
  }
  
  button {
    padding: 12px 25px;
    font-size: 1rem;
  }
}
  </style>
</head>
<body>
  <main class="container">
    <h1>Wybierz ilość pytań</h1>
    <form action="quiz.php" method="get">
      <label>
        <input type="radio" name="n" value="10" checked> 10
      </label>
      <label><input type="radio" name="n" value="20"> 20</label>
      <label><input type="radio" name="n" value="30"> 30</label>
      <label><input type="radio" name="n" value="40"> 40</label>
      <br><br>
      <button type="submit">Rozpocznij test</button>
    </form>

    <hr>
    <p><a href="admin/login.php">Panel administracyjny</a></p>
  </main>
</body>
</html>
