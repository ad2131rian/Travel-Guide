<?php
    session_start();
    require_once 'db_connection.php';

    $userId = $_SESSION['userId'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $destinationId = $_POST['id']; // Retrieve the destination ID from POST parameters
        $name = $_POST['name'];
        $description = $_POST['description'];

        $stmt = $pdo->prepare("UPDATE destinations SET name = ?, description = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([$name, $description, $destinationId, $userId]);
        if ($stmt->rowCount() > 0) {
            $_SESSION['success'] = 'Destination updated successfully.'; 
        }
        header('Location: account.php');
        exit();
    } else {
        // Display an error message or redirect to another page
        echo "This page cannot be accessed directly.";
    }
?>