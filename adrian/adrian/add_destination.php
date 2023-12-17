<?php
    session_start();
    require_once 'db_connection.php';

    $userId = $_SESSION['userId'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $stmt = $pdo->prepare("INSERT INTO destinations (name, description, user_id) VALUES (?, ?, ?)");
        $stmt->execute([$name, $description, $_SESSION['userId']]);
        $success = true;
        $_SESSION['success'] = 'New destination added successfully.';
        header('Location: account.php');
        exit();
    } else {
        echo "This page cannot be accessed directly.";
    }
?>