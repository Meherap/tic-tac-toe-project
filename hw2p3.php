<?php

$size = 11;

if ($size % 2 == 0 || $size < 7) {
    echo "Size must be an odd number greater than or equal to 7.";
    exit;
}

echo "<pre style='font-family: monospace; line-height: 1;'>";

$mid = intval($size / 2);

for ($i = 0; $i < $size; $i++) {
    for ($j = 0; $j < $size; $j++) {
        if ($i == 0 && $j == 0) echo "/";
        elseif ($i == 0 && $j == $size - 1) echo "\\";
        elseif ($i == $size - 1 && $j == 0) echo "\\";
        elseif ($i == $size - 1 && $j == $size - 1) echo "/";
        elseif ($i == 0) echo "-";
        elseif ($i == $size - 1) echo "_";
        elseif ($j == 0 || $j == $size - 1) echo "|";
        elseif ($i == $mid && $j == $mid) echo "+";
        elseif ($i == $mid) echo "-";
        elseif ($j == $mid) echo "|";
        elseif (($i == 1 || $i == $size - 2) && ($j == 1 || $j == $size - 2)) echo "+";
        else echo " ";
    }
    echo "\n";
}

echo "</pre>";
?>

