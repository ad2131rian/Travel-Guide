<?php

$user = "root";
$pass = "";
// Databse Connection
try {
    $pdo = new PDO('mysql:host=localhost;dbname=travel_guide', $user, $pass);
    
} catch (PDOException $e) {
    echo "Connection Error: " . $e->getMessage();
}
?>

