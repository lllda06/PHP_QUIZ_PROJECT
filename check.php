<?php
require 'config.php';

$selected = $_POST['answer'] ?? [];
$n = isset($_POST['n']) ? (int)$_POST['n'] : 0;

$qids = array_keys($selected);
if (!$qids) {
    $qids = [];
}

$results = [];
if ($qids) {
    $in = implode(',', array_map('intval', $qids));
    $stmt = $pdo->query("SELECT id, question_id, is_correct FROM answers WHERE id IN ($in) OR question_id IN ($in)");
    $rows = $stmt->fetchAll();

    $isCorrectByAnswerId = [];
    foreach ($rows as $r) {
        $isCorrectByAnswerId[$r['id']] = (int)$r['is_correct'];
    }

    $score = 0;
    foreach ($selected as $question_id => $answer_id) {
        $aid = (int)$answer_id;
        if (!empty($isCorrectByAnswerId[$aid])) {
            $score++;
            $results[$question_id] = ['chosen' => $aid, 'correct' => true];
        } else {
            $results[$question_id] = ['chosen' => $aid, 'correct' => false];
        }
    }
} else {
    $score = 0;
}

$detail = [];
if ($qids) {
    $in = implode(',', array_map('intval', $qids));
    $stmtQ = $pdo->query("SELECT id, question_text FROM questions WHERE id IN ($in)");
    $qrows = $stmtQ->fetchAll();
    foreach ($qrows as $q) $detail[$q['id']] = ['text' => $q['question_text']];

    $stmtA = $pdo->query("SELECT id, question_id, answer_text, is_correct FROM answers WHERE question_id IN ($in)");
    while ($a = $stmtA->fetch()) {
        $detail[$a['question_id']]['answers'][] = $a;
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Wynik testu</title>
  <link rel="stylesheet" href="assets/style.css">
  <style>
    /* assets/style.css */

body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background: linear-gradient(135deg, #667eea, #764ba2);
  color: #fff;
  margin: 0;
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: flex-start;
  padding: 40px 20px;
}

.container {
  background: rgba(255, 255, 255, 0.1);
  padding: 30px 40px;
  border-radius: 12px;
  box-shadow: 0 8px 24px rgba(0,0,0,0.25);
  max-width: 700px;
  width: 100%;
  box-sizing: border-box;
}

h1 {
  font-weight: 700;
  font-size: 2rem;
  margin-bottom: 25px;
  text-align: center;
  text-shadow: 0 2px 5px rgba(0,0,0,0.3);
}

p {
  font-size: 1.1rem;
  margin-bottom: 20px;
  text-align: center;
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

a:hover {
  background: #ffa726;
  box-shadow: 0 8px 25px rgba(255,167,38,0.9);
}

.question.result {
  background: rgba(255, 255, 255, 0.12);
  padding: 20px 25px;
  margin-bottom: 30px;
  border-radius: 12px;
  box-shadow: 0 6px 18px rgba(0,0,0,0.2);
}

.question.result p {
  font-weight: 700;
  font-size: 1.2rem;
  margin-bottom: 15px;
  color: #ffb347;
  text-shadow: 0 1px 3px rgba(0,0,0,0.3);
}

.answers {
  list-style: none;
  padding-left: 0;
  margin: 0;
}

.answers li {
  margin-bottom: 12px;
  font-size: 1.05rem;
  padding: 10px 15px;
  border-radius: 8px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.15);
  background: rgba(255, 255, 255, 0.15);
  transition: background-color 0.3s ease, color 0.3s ease;
}

.answers li.chosen {
  background: #e57373; 
  color: #fff;
  font-weight: 700;
  box-shadow: 0 4px 15px rgba(229, 115, 115, 0.7);
}

.answers li.correct {
  background: #81c784; 
  color: #fff;
  font-weight: 700;
  box-shadow: 0 4px 15px rgba(129, 199, 132, 0.7);
}

.answers li.chosen.correct {
  background: #4caf50;
  box-shadow: 0 6px 20px rgba(76, 175, 80, 0.85);
}

.answers li::before {
  font-weight: 700;
  margin-right: 8px;
}



  </style>
</head>
<body>
  <main class="container">
    <h1>Wynik</h1>
    <p>Twoje punkty: <?php echo $score; ?> / <?php echo count($selected); ?></p>

    <?php foreach ($detail as $qid => $q): ?>
      <div class="question result">
        <p><strong><?php echo nl2br(htmlspecialchars($q['text'])); ?></strong></p>
        <ul class="answers">
          <?php foreach ($q['answers'] as $a):
            $chosen = (isset($selected[$qid]) && (int)$selected[$qid] === (int)$a['id']);
            $correct = (int)$a['is_correct'] === 1;
            $cls = '';
            if ($chosen) $cls = 'chosen';
            if ($correct) $cls = 'correct';
          ?>
            <li class="<?php echo $cls; ?>">
              <?php
                if ($chosen && !$correct) echo 'Twoja odpowiedź: ';
                if ($correct) echo 'Poprawna odpowiedź: ';
              ?>
              <?php echo htmlspecialchars($a['answer_text']); ?>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endforeach; ?>

    <p><a href="index.php">Wróć do strony głównej</a></p>
  </main>
</body>
</html>
