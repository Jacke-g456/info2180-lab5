<?php
$host = 'localhost';
$username = 'lab5_user';
$password = 'password123';
$dbname = 'world';

$conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);

$lookup_type = 'countries'; // Default value
if (isset($_GET['lookup'])) {
    $lookup_type = $_GET['lookup']; 
}

// Check if country parameter is provided and not empty
if (isset($_GET['country']) && !empty(trim($_GET['country']))) {
    $country = trim($_GET['country']);
    
    if ($lookup_type === 'cities') {
        // Query for cities in the specified country
        $stmt = $conn->prepare("
            SELECT cities.name AS city_name, 
                   cities.district, 
                   cities.population,
                   countries.name AS country_name
            FROM cities
            JOIN countries ON cities.country_code = countries.code
            WHERE countries.name LIKE :country
            ORDER BY cities.name
        ");
        $countryParam = "%" . $country . "%";
        $stmt->bindParam(':country', $countryParam, PDO::PARAM_STR);
        $stmt->execute();
        
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        // Original query for countries
        $stmt = $conn->prepare("SELECT * FROM countries WHERE name LIKE :country");
        $countryParam = "%" . $country . "%";
        $stmt->bindParam(':country', $countryParam, PDO::PARAM_STR);
        $stmt->execute();
        
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} else {
    // If no country specified or empty, get ALL cities or ALL countries based on lookup type
    if ($lookup_type === 'cities') {
        // Get ALL cities from all countries
        $stmt = $conn->query("
            SELECT cities.name AS city_name, 
                   cities.district, 
                   cities.population,
                   countries.name AS country_name
            FROM cities
            JOIN countries ON cities.country_code = countries.code
            ORDER BY countries.name, cities.name
        ");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        // Get ALL countries
        $stmt = $conn->query("SELECT * FROM countries");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Display results based on lookup type
if ($lookup_type === 'cities') {
    displayCitiesTable($results);
} else {
    displayCountriesTable($results);
}

// Function to display countries table
function displayCountriesTable($results) {
    if (!empty($results)): ?>
    <table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse; width: 100%;">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th>Country Name</th>
                <th>Continent</th>
                <th>Independence Year</th>
                <th>Head of State</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($results as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['name']); ?></td>
                <td><?= htmlspecialchars($row['continent']); ?></td>
                <td>
                    <?php 
                    if (!empty($row['independence_year']) && $row['independence_year'] != 0) {
                        echo htmlspecialchars($row['independence_year']);
                    } else {
                        echo 'N/A';
                    }
                    ?>
                </td>
                <td>
                  <?php 
                    if (!empty($row['head_of_state']) && $row['head_of_state'] != 0) {
                        echo htmlspecialchars($row['head_of_state']);
                    } else {
                        echo 'N/A';
                    }
                    ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <p>Found <?= count($results); ?> country(ies).</p>
    <?php else: ?>
    <p>No countries found matching your search.</p>
    <?php endif;
}

// Function to display cities table
function displayCitiesTable($results) {
    if (!empty($results)): ?>
    <table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse; width: 100%;">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th>City Name</th>
                <th>Country</th>
                <th>District</th>
                <th>Population</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($results as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['city_name']); ?></td>
                <td><?= htmlspecialchars($row['country_name']); ?></td>
                <td><?= htmlspecialchars($row['district']); ?></td>
                <td><?= number_format(htmlspecialchars($row['population'])); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <p>Found <?= count($results); ?> cit(y/ies).</p>
    <?php else: ?>
    <p>No cities found.</p>
    <?php endif;
}
?>