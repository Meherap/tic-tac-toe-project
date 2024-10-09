<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Examples of the basics of PHP</title>
</head>
<body>

<?php
$allDays = "Monday Tuesday Wednesday Thursday Friday Saturday Sunday";

// This will split up the string into an array of strings containing the days of the week
$daysOfTheWeek = explode(' ', $allDays);

// Lets print them out, 1 per line
print "<p>Days of the week";
foreach ($daysOfTheWeek as $day) {
	print "<br>$day\n";
}
print "</p>";

// Now lets replace every instance of "day" with 'dîâ' to show that we can use other characters in our strings
$convertFrom = 'day';
$convertTo = 'dîâ';
$allDaysReplaced = str_ireplace($convertFrom, $convertTo, $allDays);

// Once again split the string up into an array
$daysOfTheWeekReplaced = explode(' ' , $allDaysReplaced);

// Print out those days, one per line. Note the use of the . operator to concatenate strings and the single quotes that do not evaluate variables
print "<p>Days of the week where $convertFrom is replaced with " . $convertTo . ' using the variable name $convertTo';
foreach ($daysOfTheWeekReplaced as $day) {
	print "<br>$day\n";
}
print "</p>";

// Create a new associative array (an array with a string as an index) where the key is the day of the week and the value is a Yes or No whether that day of the week is a weekday.
// Note that we set the key of the array using the value of the $daysOfTheWeek array at the given index
for ($i = 0; $i < count($daysOfTheWeek); $i++) {
    $day = $daysOfTheWeek[$i];
    if ($day == 'Saturday' || $day == 'Sunday') {
        $isWeekday[$day] = 'No';
    } else {
        $isWeekday[$day] = 'Yes';
    }
}
// When this is done, we have an associative array that looks like this:
// $$isWeekday = [
//	'Monday' => 'Yes',
//	'Tuesday' => 'Yes',
//	'Wednesday' => 'Yes',
//	'Thursday' => 'Yes',
//	'Friday' => 'Yes',
//	'Saturday' => 'No',
//	'Sunday' => 'No'
//];

// Lets print them out and while we are here lets count the number of days that are weekdays
$weekdayCount = 0;
print "<p>Weekdays are:";

// Note that to use the foreach with an associative array you have to use 2 variables to hold the key and the value.
foreach ($isWeekday as $day => $weekday) {
    if ($weekday == 'Yes') {
        print "<br>$day";
        $weekdayCount++;
    }
}

// Now print out the weekends, which are the days of the week that have a "No" set in the associative array
print "<p>Weekends are:";
foreach ($isWeekday as $day => $weekday) {
    if ($weekday == 'No') {
        print "<br>$day";
    }
}
print "</p>";

// Now do some math, figuring out what percentage of a week are weekend days
$totalWeekdaysInAYear = $weekdayCount * 52;
$percentageOfAWeekAreWeekends = (count($daysOfTheWeek) - $weekdayCount) / count($daysOfTheWeek);
print("The percentage of a week are weekdays is " . number_format($percentageOfAWeekAreWeekends, 3));
?>

</body>
</html>