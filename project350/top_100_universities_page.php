
<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Default password for XAMPP is empty
$dbname = "edu4you";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from the universities table
$sql = "SELECT name, gpa_requirement, ranking, tuition, location FROM universities ORDER BY name ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top 100 Universities</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        header {
            background-color: #0044cc;
            color: white;
            padding: 1rem;
            text-align: center;
        }
        table {
            width: 90%;
            margin: 2rem auto;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 1rem;
            text-align: left;
        }
        th {
            background-color: #0044cc;
            color: white;
        }
    </style>
</head>
<body>
    <header>
        <h1>Top 100 U.S. Universities</h1>
    </header>
    
    <table>
        <thead>
            <tr>
                <th>University Name</th>
                <th>GPA Requirement</th>
                <th>Ranking</th>
                <th>Tuition</th>
                <th>Location</th>
            </tr>
        </thead>
    
        <thead>
            <tr>
                <th>University Name</th>
                <th>Minimum GPA Requirement</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Display data in table rows
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>" . $row["name"] . "</td><td>" . $row["gpa_requirement"] . "</td></tr>";
                }
            } else {
                echo "<tr><td colspan='2'>No data available</td></tr>";
            }
            $conn->close();
            ?>
        </tbody>
    </table>
</body>
</html>
