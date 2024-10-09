
<?php
session_start();
include 'tic-tac-toe-functions.php';

if (!isset($_SESSION['board'])) {
    $_SESSION['board'] = [
        ["", "", ""],
        ["", "", ""],
        ["", "", ""]
    ];
    $_SESSION['current_player'] = 'X';
    $_SESSION['winner'] = null;
    $_SESSION['turns'] = 0;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($_POST as $key => $value) {
        list($row, $col) = explode('-', $key);
        $row = (int)$row - 1;
        $col = (int)$col - 1;

        if ($_SESSION['board'][$row][$col] === "" && !$_SESSION['winner']) {

            $_SESSION['board'][$row][$col] = $_SESSION['current_player'];
            $_SESSION['turns']++;

            if (checkWinner($_SESSION['board'], $_SESSION['current_player'])) {
                $_SESSION['winner'] = $_SESSION['current_player'];
            } elseif ($_SESSION['turns'] === 9) {
                $_SESSION['winner'] = 'Draw';
            } else {

                $_SESSION['current_player'] = $_SESSION['current_player'] === 'X' ? 'O' : 'X';
            }
        }
    }
}

// Reset game
if (isset($_POST['reset'])) {
    unset($_SESSION['board'], $_SESSION['current_player'], $_SESSION['winner'], $_SESSION['turns']);
    header("Location: tic-tac-toe.php");
    exit;
}

function displayBoard($board)
{
    foreach ($board as $row => $cells) {
        echo "<tr>";
        foreach ($cells as $col => $cell) {
            $cell_name = ($row + 1) . '-' . ($col + 1);
            if ($cell === 'X') {
                echo "<td style='background-color: green;'><p>X</p></td>";
            } elseif ($cell === 'O') {
                echo "<td style='background-color: red;'><p>O</p></td>";
            } else {
                echo "<td><button type='submit' name='$cell_name' value='{$_SESSION['current_player']}'></button></td>";
            }
        }
        echo "</tr>";
    }
}

function checkWinner($board, $player)
{
    // Horizontal, vertical, and diagonal checks
    for ($i = 0; $i < 3; $i++) {
        if ($board[$i][0] === $player && $board[$i][1] === $player && $board[$i][2] === $player) return true;
        if ($board[0][$i] === $player && $board[1][$i] === $player && $board[2][$i] === $player) return true;
    }
    if ($board[0][0] === $player && $board[1][1] === $player && $board[2][2] === $player) return true;
    if ($board[0][2] === $player && $board[1][1] === $player && $board[2][0] === $player) return true;

    return false;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Tic Tac Toe</title>
    <style>
        /* Button background is blue with a black border */
        button {
            background-color: #3498db;
            height: 100%;
            width: 100%;
            text-align: center;
            font-size: 20px;
            color: white;
            vertical-align: middle;
            border: 0px;
        }

        /* Styles the table cells to look like a tic-tac-toe grid */
        table td {
            text-align: center;
            vertical-align: middle;
            padding: 0px;
            margin: 0px;
            width: 75px;
            height: 75px;
            font-size: 20px;
            border: 3px solid #040404;
            color: white;
        }

        /* This shows a darker blue background when the mouse hovers over the buttons */
        button:hover,
        input[type="submit"]:hover,
        button:focus,
        input[type="submit"]:focus {
            background-color: #04469d;
            text-decoration: none;
            outline: none;
        }
    </style>
</head>
<body>
<h1>Tic Tac Toe</h1>
<form method="post">
    <table>
        <?php displayBoard($_SESSION['board']); ?>
    </table>
    <?php if ($_SESSION['winner']): ?>
        <p>
            <?php
            if ($_SESSION['winner'] === 'Draw') {
                echo "It’s a draw!";
            } else {
                echo "The winner is {$_SESSION['winner']}!";
            }
            ?>
        </p>
        <button type="submit" name="reset">Play Again</button>
    <?php endif; ?>
</form>
</body>
</html>
