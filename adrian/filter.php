<?php
include 'db_connection.php';

$category = isset($_POST['category']) ? $_POST['category'] : '';
$rating = isset($_POST['rating']) ? $_POST['rating'] : '';

// Prepare a SQL statement
$sql = "SELECT * FROM attractions WHERE 1=1" .
    (!empty($category) ? " AND category = :category" : "") .
    (!empty($rating) ? " AND likes >= :rating" : "");

$stmt = $pdo->prepare($sql);

// Bind parameters
if (!empty($category)) {
    $stmt->bindParam(':category', $category);
}

if (!empty($rating)) {
    $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
}

// Execute the statement
if ($stmt->execute()) {
    $result = $stmt->fetchAll();

    if (count($result) > 0) {
        foreach ($result as $row) {
            echo "id: " . $row["id"]. " - Name: " . $row["name"]. " - Description: " . $row["description"]. "<br>";
        }
    } else {
        echo "0 results";
    }
} else {
    echo "Error: " . implode(", ", $stmt->errorInfo());
}


?>



