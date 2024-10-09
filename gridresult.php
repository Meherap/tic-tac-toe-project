<!DOCTYPE html>
<html>
<head>
    <title>Dynamic HTML Table</title>
    <style>
        table {
            border-collapse: collapse;
            width: 50%;
            margin: 20px auto;
        }
        table, th, td {
            border: 1px solid black;
        }
        td {
            text-align: center;
            padding: 10px;
        }
    </style>
</head>
<body>

<form method="POST" action="">
    <label for="rows">Enter number of rows:</label>
    <input type="number" id="rows" name="rows" min="1" required>
    <br><br>
    
    <label for="cols">Enter number of columns:</label>
    <input type="number" id="cols" name="cols" min="1" required>
    <br><br>
    
    <label for="char">Enter character to fill table:</label>
    <input type="text" id="char" name="char" maxlength="1" required>
    <br><br>
    
    <input type="submit" value="Generate Table">
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rows = intval($_POST["rows"]);
    $cols = intval($_POST["cols"]);
    $char = $_POST["char"];
    
    if ($rows > 0 && $cols > 0 && strlen($char) == 1) {
        echo "<table>";
        for ($i = 0; $i < $rows; $i++) {
            echo "<tr>";
            for ($j = 0; $j < $cols; $j++) {
                echo "<td>$char</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Please enter valid inputs.</p>";
    }
}
?>

</body>
</html>
