<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        table {
            border-collapse: collapse;
            font-family: Courier, monospace;
            font-size: 18px;
        }
        td {
            width: 20px;
            height: 20px;
            text-align: center;
        }
        .outer {
            color: red; /* Color for the outer edge */
        }
        .crosshairs {
            color: blue; /* Color for the inside crosshairs */
        }
    </style>
    <title>ASCII Art with HTML Table</title>
</head>
<body>

<?php

$size = 11;

if ($size % 2 == 0 || $size < 7) {
    echo "Size must be an odd number greater than or equal to 7.";
    exit;
}

$mid = intval($size / 2);

echo "<table>";

for ($i = 0; $i < $size; $i++) {
    echo "<tr>";
    for ($j = 0; $j < $size; $j++) {
        $class = "";
        $char = " ";

        if ($i == 0 && $j == 0) {
            $char = "/";
            $class = "outer";
        }
        elseif ($i == 0 && $j == $size - 1) {
            $char = "\\";
            $class = "outer";
        }
        elseif ($i == $size - 1 && $j == 0) {
            $char = "\\";
            $class = "outer";
        }
        elseif ($i == $size - 1 && $j == $size - 1) {
            $char = "/";
            $class = "outer";
        }
        elseif ($i == 0) {
            $char = "-";
            $class = "outer";
        }
        elseif ($i == $size - 1) {
            $char = "_";
            $class = "outer";
        }
        elseif ($j == 0 || $j == $size - 1) {
            $char = "|";
            $class = "outer";
        }
        elseif ($i == $mid && $j == $mid) {
            $char = "+";
            $class = "crosshairs";
        }
        elseif ($i == $mid) {
            $char = "-";
            $class = "crosshairs";
        }
        elseif ($j == $mid) {
            $char = "|";
            $class = "crosshairs";
        }
        elseif (($i == 1 || $i == $size - 2) && ($j == 1 || $j == $size - 2)) {
            $char = "+";
            $class = "outer";
        }

        echo "<td class='$class'>$char</td>";
    }
    echo "</tr>";
}

echo "</table>";

?>

</body>
</html>
