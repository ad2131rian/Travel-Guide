<?php
require_once 'db_connection.php'; 
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['destinationName']);
    $description = htmlspecialchars($_POST['description']);
    $userId = $_SESSION['userId']; // Assuming you store the user's ID in session

    $stmt = $pdo->prepare("INSERT INTO destinations (name, description, user_id) VALUES (?, ?, ?)");
    $stmt->execute([$name, $description, $userId]);

    // Redirect or inform the user of success
}


header("Location: account.php");


?>
