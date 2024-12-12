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

// Get the university name from the query parameter
$university_name = isset($_GET['name']) ? urldecode($_GET['name']) : null;

if ($university_name) {
    // Fetch university details
    $stmt = $conn->prepare("SELECT name, gpa_requirement, ranking, tuition, location FROM universities WHERE name = ?");
    $stmt->bind_param("s", $university_name);
    $stmt->execute();
    $result = $stmt->get_result();
    $university = $result->fetch_assoc();
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Details</title>
    <style>
        /* Global Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #FFFFFF;
        }

        /* Top Bar */
        .top-bar {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem 2.5rem;
            background-color: #FFFFFF;
            border-bottom: 1px solid #e0e0e0;
            position: relative;
        }
        .top-bar .logo {
            position: absolute;
            left: 2rem;
        }
        .top-bar .logo img {
            height: 50px;
            width: auto;
        }
        .top-bar .menu {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
        }
        .top-bar .menu a {
            color: #000000;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        .top-bar .menu a:hover {
            color: #05350e;
        }

        /* Content Section */
        .content {
            max-width: 800px;
            margin: 2rem auto;
            background-color: #FFFFFF;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 2rem;
        }
        .content h2 {
            color: #05350e;
            margin-bottom: 1rem;
        }
        .content p {
            margin: 0.5rem 0;
        }

        /* Back Link */
        .back-link {
            display: inline-block;
            margin-top: 1.5rem;
            text-decoration: none;
            color: #05350e;
            font-weight: bold;
        }
        .back-link:hover {
            text-decoration: underline;
        }

        /* Footer */
        footer {
            background-color: #FFFFFF;
            color: #000000;
            text-align: center;
            padding: 1rem;
            margin-top: 2rem;
        }
        footer a {
            color: #05350e;
            text-decoration: none;
        }
        footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <!-- Top Bar -->
    <div class="top-bar">
        <a href="#" class="logo">
            <img src="Logoedu4you.jpeg" alt="EDU4YOU Logo">
        </a>
        <div class="menu">
            <a href="EDU4YOU_Main_Prototype_Finalized.php">Home</a>
            <a href="updated_navigation_search.html">Search Universities</a>
            <a href="updated_navigation_compare.html">Compare Colleges</a>
            <a href="updated_navigation_majors.html">Explore Majors</a>
        </div>
    </div>

    <!-- Content Section -->
    <div class="content">
        <?php if ($university): ?>
            <h2><?= htmlspecialchars($university['name']) ?></h2>
            <p><strong>Minimum GPA Requirement:</strong> <?= htmlspecialchars($university['gpa_requirement']) ?></p>
            <p><strong>Ranking:</strong> <?= htmlspecialchars($university['ranking']) ?></p>
            <p><strong>Tuition:</strong> $<?= htmlspecialchars(number_format($university['tuition'], 2)) ?></p>
            <p><strong>Location:</strong> <?= htmlspecialchars($university['location']) ?></p>
        <?php else: ?>
            <p>University not found.</p>
        <?php endif; ?>
        <a href="EDU4YOU_Main_Prototype_Finalized.php" class="back-link">Back to List</a>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 EDU4YOU. All rights reserved. <a href="privacy.html">Privacy Policy</a></p>
    </footer>
</body>
</html>
