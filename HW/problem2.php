<?php
session_start();

if (isset($_POST['submitNumber'])) {
    $numQuestions = intval($_POST['num_questions']);
    $surveyName = htmlspecialchars($_POST['survey_name']);
   
    if ($numQuestions > 0 && !empty($surveyName)) {
        $_SESSION['num_questions'] = $numQuestions;  
        $_SESSION['survey_name'] = $surveyName;  
        header('Location: ' . $_SERVER['PHP_SELF'] . '?step=questions'); 
        exit;
    } else {
        $error = "Please enter a valid survey name and a positive number of questions.";
    }
}

if (isset($_POST['submitSurvey'])) {
    $questions = [];
    
    for ($i = 1; $i <= $_SESSION['num_questions']; $i++) {
        if (!empty($_POST['question' . $i])) {
            $questions[] = htmlspecialchars($_POST['question' . $i]);
        }
    }

    $_SESSION['questions'] = $questions;

    header('Location: ' . $_SERVER['PHP_SELF'] . '?step=answer');
    exit;
}

if (isset($_POST['submitAnswers'])) {
    $answers = [];
    $questions = $_SESSION['questions'];

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
    <title>Dynamic Survey with Survey Name</title>
</head>
<body>

<?php if (!isset($_GET['step'])): ?>
    
    <h2>Create a Survey</h2>
    <form method="POST">
        <label for="survey_name">Survey Name:</label>
        <input type="text" id="survey_name" name="survey_name" required><br><br>
        
        <label for="num_questions">How many questions would you like to ask?</label>
        <input type="number" id="num_questions" name="num_questions" min="1" required>
        <br><br>
        <input type="submit" name="submitNumber" value="Next">
    </form>
    <?php if (isset($error)): ?>
        <p style="color:red;"><?= $error ?></p>
    <?php endif; ?>

<?php elseif ($_GET['step'] == 'questions'): ?>
    
    <h2>Enter Questions for Survey: <?= htmlspecialchars($_SESSION['survey_name']) ?></h2>
    <form method="POST">
        <?php for ($i = 1; $i <= $_SESSION['num_questions']; $i++): ?>
            <label for="question<?= $i ?>">Question <?= $i ?>:</label>
            <input type="text" id="question<?= $i ?>" name="question<?= $i ?>"><br><br>
        <?php endfor; ?>
        <input type="submit" name="submitSurvey" value="Create Survey">
    </form>

<?php elseif ($_GET['step'] == 'answer'): ?>
    
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
