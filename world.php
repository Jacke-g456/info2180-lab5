<?php
$host = 'localhost';
$username = 'lab5_user';
$password = 'password123';
$dbname = 'world';

$conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);

// Check if country parameter is provided
if (isset($_GET['country'])) {
    $country = $_GET['country'];
    
    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM countries WHERE name LIKE :country");
    $countryParam = "%" . $country . "%";
    $stmt->bindParam(':country', $countryParam, PDO::PARAM_STR);
    $stmt->execute();
    
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // If no country specified, get all countries
    $stmt = $conn->query("SELECT * FROM countries");
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>


<?php if (!empty($results)): ?>
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
<?php endif; ?>