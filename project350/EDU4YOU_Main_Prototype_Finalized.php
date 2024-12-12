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
$sql = "SELECT name, gpa_requirement, ranking, tuition, location FROM universities";
$result = $conn->query($sql);
$universities = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $universities[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EDU4YOU - Find Your University</title>
    <style>
        /* Global Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #FFFFFF;
        }

        /* Top Bar (Logo and Navigation) */
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

        /* Filter Section */
        .filter-section {
            background-color: #FFFFFF;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin: 2rem;
            display: flex;
            gap: 10px;
        }
        .filter-section input, .filter-section select, .filter-section button {
            padding: 0.8rem;
            border: 1px solid #ccc;
            border-radius: 4px;
            flex: 1;
        }
        .filter-section button {
            background-color: #05350e;
            color: white;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }
        .filter-section button:hover {
            background-color: #000000;
        }

        /* University List */
        .university-list {
            padding: 2rem;
        }
        .university-item {
            background-color: #FFFFFF;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .university-item h3 {
            color: #05350e;
        }
        .university-item button {
            margin-top: 1rem;
            padding: 0.8rem;
            background-color: #05350e;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }
        .university-item button:hover {
            background-color: #000000;
        }

        /* Pagination */
        .pagination {
            text-align: center;
            margin: 20px 0;
        }
        .pagination button {
            padding: 10px 20px;
            border: none;
            background-color: #05350e;
            color: white;
            cursor: pointer;
            margin: 0 5px;
            border-radius: 4px;
        }
        .pagination button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
        .pagination button:hover:not(:disabled) {
            background-color: #000000;
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

    <!-- Filter Section -->
    <div class="filter-section">
        <input type="text" id="search-bar" placeholder="Search by university name..." onkeyup="filterUniversities()">
        <select id="gpa-filter" onchange="filterUniversities()">
            <option value="all">All GPAs</option>
            <option value="3.0">3.0+</option>
            <option value="3.5">3.5+</option>
            <option value="4.0">4.0</option>
        </select>
        <button onclick="resetFilters()">Reset Filters</button>
    </div>

    <!-- University List -->
    <div class="university-list" id="university-list">
        <!-- Universities will be inserted dynamically -->
    </div>
    <div class="pagination" id="pagination">
        <!-- Pagination buttons will be added dynamically -->
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 EDU4YOU. All rights reserved. <a href="privacy.html">Privacy Policy</a></p>
    </footer>

    <script>
        const universities = <?php echo json_encode($universities); ?>;
        const universitiesPerPage = 50;
        let currentPage = 1;
        let filteredUniversities = universities;

        function displayUniversities(filteredUniversities, page = 1) {
            const list = document.getElementById('university-list');
            list.innerHTML = '';
            const start = (page - 1) * universitiesPerPage;
            const end = start + universitiesPerPage;
            const universitiesToShow = filteredUniversities.slice(start, end);

            universitiesToShow.forEach(university => {
                const item = document.createElement('div');
                item.className = 'university-item';
                item.innerHTML = `
                    <h3>${university.name}</h3>
                    <p>Minimum GPA: ${university.gpa_requirement}</p>
                    <button onclick="viewDetails('${university.name}')">More Details</button>
                `;
                list.appendChild(item);
            });

            updatePagination(filteredUniversities.length, page);
        }

        function filterUniversities() {
            const searchBar = document.getElementById('search-bar').value.toLowerCase();
            const gpaFilter = document.getElementById('gpa-filter').value;

            filteredUniversities = universities.filter(university => {
                const matchesName = university.name.toLowerCase().includes(searchBar);
                const matchesGpa = gpaFilter === 'all' || parseFloat(university.gpa_requirement) >= parseFloat(gpaFilter);
                return matchesName && matchesGpa;
            });

            currentPage = 1; // Reset to first page
            displayUniversities(filteredUniversities, currentPage);
        }

        function updatePagination(totalUniversities, page) {
            const pagination = document.getElementById('pagination');
            pagination.innerHTML = '';

            const totalPages = Math.ceil(totalUniversities / universitiesPerPage);

            const prevButton = document.createElement('button');
            prevButton.textContent = 'Previous';
            prevButton.disabled = page === 1;
            prevButton.onclick = () => {
                currentPage--;
                displayUniversities(filteredUniversities, currentPage);
            };
            pagination.appendChild(prevButton);

            const nextButton = document.createElement('button');
            nextButton.textContent = 'Next';
            nextButton.disabled = page === totalPages;
            nextButton.onclick = () => {
                currentPage++;
                displayUniversities(filteredUniversities, currentPage);
            };
            pagination.appendChild(nextButton);
        }

        function resetFilters() {
            document.getElementById('search-bar').value = '';
            document.getElementById('gpa-filter').value = 'all';
            currentPage = 1;
            filteredUniversities = universities;
            displayUniversities(filteredUniversities, currentPage);
        }

        function viewDetails(name) {
            window.location.href = `details.php?name=${encodeURIComponent(name)}`;
        }

        // Initial display of all universities
        displayUniversities(universities, currentPage);
    </script>
</body>
</html>
