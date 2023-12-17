<?php
include 'db/dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name']; // Remember to sanitize and validate input
    $description = $_POST['description'];

    try {
        $stmt = $pdo->prepare("INSERT INTO attractions (name, description) VALUES (?, ?)");
        $stmt->execute([$name, $description]);
        echo "Attraction added successfully";
    } catch (\PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
