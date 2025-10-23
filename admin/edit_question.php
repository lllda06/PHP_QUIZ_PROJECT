<?php
require '../config.php';
if (!is_logged_in()) { header('Location: login.php'); exit; }

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) { header('Location: dashboard.php'); exit; }

$stmt = $pdo->prepare("SELECT * FROM questions WHERE id = :id");
$stmt->execute([':id' => $id]);
$q = $stmt->fetch();
if (!$q) { header('Location: dashboard.php'); exit; }

$stmtA = $pdo->prepare("SELECT * FROM answers WHERE question_id = :id ORDER BY id ASC");
$stmtA->execute([':id' => $id]);
$answers = $stmtA->fetchAll();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $qtext = trim($_POST['question_text'] ?? '');
    $answers_in = [$_POST['a1'] ?? '', $_POST['a2'] ?? '', $_POST['a3'] ?? '', $_POST['a4'] ?? ''];
    $correct_idx = isset($_POST['correct']) ? (int)$_POST['correct'] : null;

    if ($qtext === '' || in_array('', $answers_in, true) || $correct_idx === null) {
        $error = 'Wypełnij wszystkie pola i zaznacz prawidłową odpowiedź.';
    } else {
        $image_name = $q['image_path'];
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
            $stmt = $pdo->prepare("UPDATE questions SET question_text = :t, image_path = :img WHERE id = :id");
            $stmt->execute([':t' => $qtext, ':img' => $image_name, ':id' => $id]);

            // update answers (assumes 4)
            foreach ($answers as $i => $row) {
                $is = ($i === $correct_idx) ? 1 : 0;
                $stmtU = $pdo->prepare("UPDATE answers SET answer_text = :txt, is_correct = :is WHERE id = :aid");
                $stmtU->execute([':txt' => $answers_in[$i], ':is' => $is, ':aid' => $row['id']]);
            }
            $pdo->commit();
            header('Location: dashboard.php');
            exit;
        }
    }
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Edytuj pytanie</title></head><body>
  <h1>Edytuj pytanie</h1>
  <?php if ($error) echo "<p style='color:red;'>".htmlspecialchars($error)."</p>"; ?>
  <form method="post" enctype="multipart/form-data">
    <p>Treść pytania:<br><textarea name="question_text" rows="4" cols="60"><?php echo htmlspecialchars($q['question_text']); ?></textarea></p>
    <p>Obecny obraz: <?php echo $q['image_path'] ? htmlspecialchars($q['image_path']) : 'brak'; ?></p>
    <p>Zamień obraz (opcjonalnie): <input type="file" name="image" accept="image/*"></p>
    <p>Odpowiedzi (dokładnie 4):</p>
    <ol>
      <?php for ($i=0;$i<4;$i++): ?>
        <li><input name="a<?php echo $i+1; ?>" value="<?php echo isset($answers[$i])?htmlspecialchars($answers[$i]['answer_text']):''; ?>"></li>
      <?php endfor; ?>
    </ol>
    <p>Która odpowiedź jest poprawna? 
      <select name="correct">
        <?php 
        $correctIndex = 0;
        foreach ($answers as $i => $a) { if ($a['is_correct']) $correctIndex = $i; }
        for ($i=0;$i<4;$i++): ?>
        <option value="<?php echo $i; ?>" <?php echo ($i===$correctIndex)?'selected':''; ?>><?php echo $i+1; ?></option>
      <?php endfor; ?>
      </select>
    </p>
    <button type="submit">Zapisz</button>
  </form>
  <p><a href="dashboard.php">Powrót</a></p>
</body></html>
