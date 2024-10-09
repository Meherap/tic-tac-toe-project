<?php
if (isset($_POST['submitSurvey'])) {
    $surveyName = htmlspecialchars($_POST['survey_name']);
    $questions = [];
    
    for ($i = 1; $i <= 5; $i++) {
        if (!empty($_POST['question' . $i])) {
            $questions[] = htmlspecialchars($_POST['question' . $i]);
        }
    }
    
    session_start();
    $_SESSION['survey_name'] = $surveyName;
    $_SESSION['questions'] = $questions;

    header('Location: ' . $_SERVER['PHP_SELF'] . '?step=answer');
    exit;
}

if (isset($_POST['submitAnswers'])) {
    session_start();
    $questions = $_SESSION['questions'];
    $answers = [];

    for ($i = 0; $i < count($questions); $i++) {
        $answers[] = htmlspecialchars($_POST['answer' . $i]);
    }

    echo "<h2>Survey Results: " . htmlspecialchars($_SESSION['survey_name']) . "</h2>";
    echo "<ul>";
    for ($i = 0; $i < count($questions); $i++) {
        echo "<li><strong>Question:</strong> " . $questions[$i] . "<br>";
        echo "<strong>Answer:</strong> " . $answers[$i] . "</li><br>";
    }
    echo "</ul>";
    session_destroy();  
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Survey Form</title>
</head>
<body>
<?php if (!isset($_GET['step'])): ?>
   
    <h2>Create a Survey</h2>
    <form method="POST">
        <label for="survey_name">Survey Name:</label>
        <input type="text" id="survey_name" name="survey_name" required><br><br>
        
        <?php for ($i = 1; $i <= 5; $i++): ?>
            <label for="question<?= $i ?>">Question <?= $i ?>:</label>
            <input type="text" id="question<?= $i ?>" name="question<?= $i ?>"><br><br>
        <?php endfor; ?>
        
        <input type="submit" name="submitSurvey" value="Create Survey">
    </form>

<?php elseif ($_GET['step'] == 'answer'): ?>
   
    <?php session_start(); ?>
    <h2>Answer the Survey: <?= htmlspecialchars($_SESSION['survey_name']) ?></h2>
    <form method="POST">
        <?php foreach ($_SESSION['questions'] as $index => $question): ?>
            <label for="answer<?= $index ?>"><?= $question ?>:</label>
            <input type="text" id="answer<?= $index ?>" name="answer<?= $index ?>" required><br><br>
        <?php endforeach; ?>
        <input type="submit" name="submitAnswers" value="Submit Answers">
    </form>
<?php endif; ?>
</body>
</html>
