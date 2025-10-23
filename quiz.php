<?php
require 'config.php';

$n = isset($_GET['n']) ? (int)$_GET['n'] : 10;
if (!in_array($n, [10,20,30,40])) $n = 10;

// Pobierz losowe pytania
$stmt = $pdo->prepare("
  SELECT q.id, q.question_text, q.image_path
  FROM questions q
  ORDER BY RAND()
  LIMIT :n
");
$stmt->bindValue(':n', $n, PDO::PARAM_INT);
$stmt->execute();
$questions = $stmt->fetchAll();

$ids = array_column($questions, 'id');
$answers = [];
if ($ids) {
    $in = implode(',', array_map('intval', $ids));
    $stmt2 = $pdo->query("SELECT * FROM answers WHERE question_id IN ($in) ORDER BY RAND()");
    $allA = $stmt2->fetchAll();
    foreach ($allA as $a) {
        $answers[$a['question_id']][] = $a;
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Test</title>
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

.timer {
  font-weight: 700;
  font-size: 1.3rem;
  background: rgba(255, 179, 71, 0.85);
  padding: 10px 15px;
  border-radius: 20px;
  width: fit-content;
  margin: 0 auto 30px auto;
  color: #333;
  box-shadow: 0 5px 15px rgba(255,179,71,0.7);
}

.question {
  background: rgba(255, 255, 255, 0.12);
  padding: 20px 25px;
  margin-bottom: 30px;
  border-radius: 12px;
  box-shadow: 0 6px 18px rgba(0,0,0,0.2);
  transition: background-color 0.3s ease;
}

.question.answered {
  background-color: rgba(255, 179, 71, 0.4);
}

.question h3 {
  margin-top: 0;
  font-weight: 700;
  font-size: 1.25rem;
  margin-bottom: 15px;
  color: #ffb347;
  text-shadow: 0 1px 3px rgba(0,0,0,0.3);
}

.question p {
  font-size: 1rem;
  line-height: 1.5;
  margin-bottom: 15px;
  white-space: pre-wrap;
}

.q-image {
  text-align: center;
  margin-bottom: 15px;
}

.q-image img {
  max-width: 100%;
  border-radius: 10px;
  box-shadow: 0 5px 15px rgba(0,0,0,0.3);
}

.answers {
  list-style: none;
  padding-left: 0;
  margin: 0;
}

.answers li {
  margin-bottom: 12px;
}

.answers label {
  cursor: pointer;
  font-size: 1.05rem;
  user-select: none;
  display: flex;
  align-items: center;
  padding: 10px 15px;
  border-radius: 8px;
  background: rgba(255,255,255,0.15);
  transition: background-color 0.3s ease;
  box-shadow: 0 2px 6px rgba(0,0,0,0.15);
}

.answers label:hover {
  background: rgba(255, 179, 71, 0.3);
}

.answers input[type="radio"] {
  margin-right: 15px;
  cursor: pointer;
  accent-color: #ffb347;
  transform: scale(1.2);
  flex-shrink: 0;
}

button[type="submit"] {
  background: #ffb347;
  border: none;
  border-radius: 30px;
  width: 100%;
  padding: 16px 0;
  font-size: 1.3rem;
  font-weight: 700;
  color: #333;
  cursor: pointer;
  box-shadow: 0 6px 20px rgba(255,179,71,0.75);
  transition: background-color 0.3s ease, box-shadow 0.3s ease;
  margin-top: 15px;
}

button[type="submit"]:hover {
  background: #ffa726;
  box-shadow: 0 8px 25px rgba(255,167,38,0.9);
}

/* Brak odpowiedzi (błąd danych) */
em {
  color: #ffc57d;
  font-style: normal;
  font-weight: 600;
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

/* Responsywność */
@media (max-width: 600px) {
  .container {
    padding: 20px 25px;
    max-width: 100%;
  }

  h1 {
    font-size: 1.6rem;
  }

  .question h3 {
    font-size: 1.1rem;
  }

  button[type="submit"] {
    font-size: 1.1rem;
    padding: 14px 0;
  }

}
  </style>
</head>
<body>
  <main class="container">
    <h1>Test — <?php echo htmlspecialchars($n); ?> pytań</h1>

    <?php if ($n === 40): ?>
      <div id="timer" class="timer">Pozostały czas: <span id="time">60:00</span></div>
      <script>
        let remaining = 3600;
        const display = document.getElementById('time');
        const interval = setInterval(function(){
          let m = Math.floor(remaining/60);
          let s = remaining % 60;
          display.textContent = String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0');
          if (remaining-- <= 0) {
            clearInterval(interval);
            alert('Koniec czasu — test zostanie automatycznie zapisany i sprawdzony.');
            document.getElementById('quizForm').submit();
          }
        }, 1000);
      </script>
    <?php endif; ?>

    <form id="quizForm" action="check.php" method="post">
      <input type="hidden" name="n" value="<?php echo $n; ?>">
      <?php foreach ($questions as $i => $q): ?>
        <?php $qid = (int)$q['id']; ?>
        <div class="question" id="q-<?php echo $qid; ?>">
          <h3>Pytanie <?php echo $i+1; ?>:</h3>
          <p><?php echo nl2br(htmlspecialchars($q['question_text'])); ?></p>
          <?php if (!empty($q['image_path'])): ?>
            <div class="q-image"><img src="<?php echo 'uploads/' . htmlspecialchars($q['image_path']); ?>" alt="obrazek pytania" style="max-width:300px;"></div>
          <?php endif; ?>

          <?php if (!empty($answers[$qid])): ?>
            <ul class="answers">
              <?php foreach ($answers[$qid] as $a): ?>
                <li>
                  <label>
                    <input type="radio" name="answer[<?php echo $qid; ?>]" value="<?php echo $a['id']; ?>" onchange="markAnswered(<?php echo $qid; ?>)">
                    <?php echo htmlspecialchars($a['answer_text']); ?>
                  </label>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php else: ?>
            <p><em>Brak odpowiedzi dla tego pytania (błąd danych).</em></p>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>

      <button type="submit">Sprawdź</button>
      <p><a href="index.php">Wróć do strony głównej</a></p>
    </form>
  </main>

  <script>
    function markAnswered(qid) {
      const el = document.getElementById('q-' + qid);
      if (el) {
        el.classList.add('answered');
      }
    }
  </script>
</body>
</html>
