<?php
    session_start();
    require_once 'db_connection.php';

    $destinationId = $_GET['id'];
    $userId = $_SESSION['userId'];

    $stmt = $pdo->prepare("DELETE FROM destinations WHERE id = ? AND user_id = ?");
    $stmt->execute([$destinationId, $userId]);

    header('Location: account.php');
    exit();
?>