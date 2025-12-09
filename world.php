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

<ul>
<?php foreach ($results as $row): ?>
  <li><?= $row['name'] . ' is ruled by ' . $row['head_of_state']; ?></li>
<?php endforeach; ?>
</ul>