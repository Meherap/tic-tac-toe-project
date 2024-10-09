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
    $types = [];
    $additionalInfo = [];

    for ($i = 1; $i <= $_SESSION['num_questions']; $i++) {
        $questionText = htmlspecialchars($_POST['question' . $i]);
        $questionType = $_POST['type' . $i];
        
        if (!empty($questionText)) {
            $questions[] = $questionText;
            $types[] = $questionType;

            if ($questionType == 'text') {
                $charLimit = intval($_POST['char_limit' . $i]);
                $additionalInfo[] = $charLimit > 0 ? $charLimit : 200; 
            } elseif ($questionType == 'multiple') {
                $options = array_map('trim', explode(',', $_POST['options' . $i]));
                $validOptions = array_filter($options);  
                if (count($validOptions) > 0) {
                    $additionalInfo[] = $validOptions;
                } else {
                    $error = "Multiple-choice question $i needs at least one valid option.";
                }
            }
        }
    }

    if (!isset($error)) {
        $_SESSION['questions'] = $questions;
        $_SESSION['types'] = $types;
        $_SESSION['additionalInfo'] = $additionalInfo;
        header('Location: ' . $_SERVER['PHP_SELF'] . '?step=answer');
        exit;
    }
}

if (isset($_POST['submitAnswers'])) {
    $questions = $_SESSION['questions'];
    $types = $_SESSION['types'];
    $additionalInfo = $_SESSION['additionalInfo'];
    $answers = [];

    for ($i = 0; $i < count($questions); $i++) {
        $answer = htmlspecialchars($_POST['answer' . $i]);
        $answers[] = $answer;
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
    <title>Advanced Survey</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; }
        form { margin-bottom: 20px; }
        label { font-weight: bold; }
        input, textarea { margin-bottom: 10px; padding: 8px; width: 100%; }
        .error { color: red; }
    </style>
</head>
<body>

<?php if (!isset($_GET['step'])): ?>
   
    <h2>Create Your Survey</h2>
    <form method="POST">
        <label for="survey_name">Survey Name:</label>
        <input type="text" id="survey_name" name="survey_name" required><br><br>

        <label for="num_questions">Number of Questions:</label>
        <input type="number" id="num_questions" name="num_questions" min="1" required><br><br>
        <input type="submit" name="submitNumber" value="Next">
    </form>
    <?php if (isset($error)): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

<?php elseif ($_GET['step'] == 'questions'): ?>
    
    <h2>Enter Your Questions for: <?= htmlspecialchars($_SESSION['survey_name']) ?></h2>
    <form method="POST">
        <?php for ($i = 1; $i <= $_SESSION['num_questions']; $i++): ?>
            <label for="question<?= $i ?>">Question <?= $i ?>:</label>
            <input type="text" id="question<?= $i ?>" name="question<?= $i ?>"><br><br>

            <label>Type:</label>
            <select name="type<?= $i ?>">
                <option value="text">Text</option>
                <option value="multiple">Multiple Choice</option>
            </select><br><br>

           
            <div id="extraInfo<?= $i ?>">
                <label for="char_limit<?= $i ?>">Character Limit (for Text questions):</label>
                <input type="number" id="char_limit<?= $i ?>" name="char_limit<?= $i ?>" min="1" value="200"><br><br>

                <label for="options<?= $i ?>">Multiple Choice Options (comma-separated):</label>
                <input type="text" id="options<?= $i ?>" name="options<?= $i ?>"><br><br>
            </div>
        <?php endfor; ?>
        <input type="submit" name="submitSurvey" value="Create Survey">
    </form>

<?php elseif ($_GET['step'] == 'answer'): ?>
    
    <h2>Answer the Survey: <?= htmlspecialchars($_SESSION['survey_name']) ?></h2>
    <form method="POST">
        <?php for ($i = 0; $i < count($_SESSION['questions']); $i++): ?>
            <label><?= $_SESSION['questions'][$i] ?>:</label><br>
            
            <?php if ($_SESSION['types'][$i] == 'text'): ?>
                <input type="text" name="answer<?= $i ?>" maxlength="<?= $_SESSION['additionalInfo'][$i] ?>" required><br><br>
            
            <?php elseif ($_SESSION['types'][$i] == 'multiple'): ?>
                <?php foreach ($_SESSION['additionalInfo'][$i] as $option): ?>
                    <input type="radio" name="answer<?= $i ?>" value="<?= $option ?>" required> <?= $option ?><br>
                <?php endforeach; ?><br>
            <?php endif; ?>
        <?php endfor; ?>
        <input type="submit" name="submitAnswers" value="Submit Survey">
    </form>
<?php endif; ?>

</body>
</html>
