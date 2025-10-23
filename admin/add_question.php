<?php
require '../config.php';
if (!is_logged_in()) { header('Location: login.php'); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $qtext = trim($_POST['question_text'] ?? '');
    $answers = [$_POST['a1'] ?? '', $_POST['a2'] ?? '', $_POST['a3'] ?? '', $_POST['a4'] ?? ''];
    $correct_idx = isset($_POST['correct']) ? (int)$_POST['correct'] : null;

    if ($qtext === '' || in_array('', $answers, true) || $correct_idx === null) {
        $error = 'Wypełnij wszystkie pola i zaznacz prawidłową odpowiedź.';
    } else {
        $image_name = null;
        if (!empty($_FILES['image']['name'])) {
            $f = $_FILES['image'];
            $allowed = ['image/png','image/jpeg','image/gif'];
            if ($f['error'] === UPLOAD_ERR_OK && in_array(mime_content_type($f['tmp_name']), $allowed)) {
                $ext = pathinfo($f['name'], PATHINFO_EXTENSION);
                $image_name = uniqid('img_') . '.' . $ext;
                move_uploaded_file($f['tmp_name'], __DIR__ . '/../uploads/' . $image_name);
            } else {
                $error = 'Błąd przy uploadzie obrazka (zły typ lub błąd przesyłu).';
            }
        }

        if (!$error) {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare("INSERT INTO questions (question_text, image_path) VALUES (:t, :img)");
            $stmt->execute([':t' => $qtext, ':img' => $image_name]);
            $qid = $pdo->lastInsertId();

            $stmtA = $pdo->prepare("INSERT INTO answers (question_id, answer_text, is_correct) VALUES (:qid, :txt, :is)");
            foreach ($answers as $i => $ans) {
                $is = ($i === $correct_idx) ? 1 : 0;
                $stmtA->execute([':qid'=>$qid, ':txt'=>$ans, ':is'=>$is]);
            }
            $pdo->commit();
            header('Location: dashboard.php');
            exit;
        }
    }
}
?>
<!doctype html><html><head><meta charset="utf-8">
<title>Dodaj pytanie</title>
<style>
 /* ------------------------
   Globalne style i tło
------------------------ */
body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
  color: #fff;
  margin: 0;
  padding: 50px 20px;
  align-items: flex-start;
  min-height: 100vh;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}

.container {
  background: rgba(24, 8, 48, 0.85);
  padding: 40px 50px;
  border-radius: 20px;
  box-shadow:
    0 10px 20px rgba(106, 17, 203, 0.4),
    0 6px 6px rgba(37, 117, 252, 0.4);
  max-width: 720px;
  width: 100%;
  box-sizing: border-box;
  transition: background-color 0.3s ease;
}

.container:hover {
  background: rgba(32, 12, 64, 0.95);
}

/* ------------------------
   Nagłówki
------------------------ */
h1 {
  font-size: 2.6rem;
  font-weight: 700;
  text-align: center;
  margin-bottom: 35px;
  text-shadow: 0 3px 10px rgba(106, 17, 203, 0.7);
}

/* ------------------------
   Formularz i pola
------------------------ */
form {
  margin-top: 15px;
}

form p,
form label,
form ol li {
  font-size: 1.1rem;
  line-height: 1.4;
  margin-bottom: 18px;
  color: #ddd;
}

textarea,
input[type="text"],
input[type="file"],
select {
  width: 100%;
  padding: 14px 18px;
  border-radius: 12px;
  border: 2px solid transparent;
  font-size: 1.05rem;
  font-weight: 500;
  box-sizing: border-box;
  background-color: #24104a;
  color: #eaeaff;
  box-shadow:
    inset 0 2px 4px rgba(255,255,255,0.1),
    0 0 6px rgba(106, 17, 203, 0.4);
  transition:
    border-color 0.3s ease,
    box-shadow 0.3s ease,
    background-color 0.3s ease;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  resize: vertical;
}

textarea {
  min-height: 100px;
  max-height: 180px;
}

textarea:focus,
input[type="text"]:focus,
input[type="file"]:focus,
select:focus {
  outline: none;
  border-color: #8a5cf6;
  box-shadow:
    0 0 10px 2px rgba(138, 92, 246, 0.7),
    inset 0 2px 4px rgba(255,255,255,0.15);
  background-color: #2e1c68;
}

/* ------------------------
   Lista odpowiedzi (ol)
------------------------ */
ol {
  padding-left: 24px;
  color: #ddd;
}

ol li {
  margin-bottom: 14px;
}

ol li input {
  width: 100%;
  background: transparent;
  border: none;
  border-bottom: 2px solid #555;
  color: #ccc;
  padding: 8px 6px;
  font-size: 1rem;
  border-radius: 6px;
  transition: border-color 0.25s ease;
}

ol li input:focus {
  border-bottom-color: #ab7fff;
  color: #fff;
  outline: none;
}

/* ------------------------
   Przycisk
------------------------ */
button[type="submit"] {
  margin-top: 30px;
  display: block;
  width: 100%;
  background: linear-gradient(45deg, #7b2ff7, #f107a3);
  color: #fff;
  font-weight: 700;
  font-size: 1.3rem;
  padding: 16px 0;
  border: none;
  border-radius: 35px;
  cursor: pointer;
  box-shadow:
    0 0 12px #f107a3,
    0 4px 14px rgba(123, 47, 247, 0.7);
  transition: background 0.3s ease, box-shadow 0.3s ease;
  letter-spacing: 0.06em;
  user-select: none;
}

button[type="submit"]:hover {
  background: linear-gradient(45deg, #f107a3, #7b2ff7);
  box-shadow:
    0 0 18px #f107a3,
    0 6px 20px rgba(123, 47, 247, 0.9);
}

/* ------------------------
   Link Powrót
------------------------ */
p a {
  display: inline-block;
  margin-top: 25px;
  text-align: center;
  color: #d9b3ff;
  font-weight: 600;
  text-decoration: none;
  font-size: 1.05rem;
  transition: color 0.25s ease;
}

p a:hover {
  color: #fff;
  text-shadow: 0 0 8px #f107a3;
}

/* ------------------------
   Błędy
------------------------ */
p.error {
  background: #ff5c5c;
  color: #fff;
  padding: 10px 14px;
  border-radius: 12px;
  font-weight: 700;
  text-align: center;
  box-shadow: 0 0 12px rgba(255, 92, 92, 0.9);
  margin-bottom: 20px;
}

</style>
</head>
<body>
  <h1>Dodaj pytanie</h1>
  <?php if ($error) echo "<p style='color:red;'>".htmlspecialchars($error)."</p>"; ?>
  <form method="post" enctype="multipart/form-data">
    <p>Treść pytania:<br><textarea name="question_text" rows="4" cols="60"></textarea></p>
    <p>Obraz (opcjonalnie): <input type="file" name="image" accept="image/*"></p>
    <p>Odpowiedzi (dokładnie 4):</p>
    <ol>
      <li><input name="a1"></li>
      <li><input name="a2"></li>
      <li><input name="a3"></li>
      <li><input name="a4"></li>
    </ol>
    <p>Która odpowiedź jest poprawna? 
      <select name="correct">
        <option value="0">1</option>
        <option value="1">2</option>
        <option value="2">3</option>
        <option value="3">4</option>
      </select>
    </p>
    <button type="submit">Dodaj</button>
  </form>
  <p><a href="dashboard.php">Powrót</a></p>
</body></html>
