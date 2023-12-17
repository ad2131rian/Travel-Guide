<?php
session_start();
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['id'])) {
        $id = $_POST['id'];
        $userId = $_SESSION['userId'];

        // Check if the user has already liked the attraction
        $stmt = $pdo->prepare("SELECT * FROM likes WHERE attraction_id = ? AND user_id = ?");
        $stmt->execute([$id, $userId]);
        $like = $stmt->fetch();

        if ($like) {
            // User has already liked the attraction, so remove the like
            $stmt = $pdo->prepare("DELETE FROM likes WHERE attraction_id = ? AND user_id = ?");
            $stmt->execute([$id, $userId]);

            // Decrease the like count in the attractions table
            $stmt = $pdo->prepare("UPDATE attractions SET likes = likes - 1 WHERE id = ?");
            $stmt->execute([$id]);
        } else {
            // User has not liked the attraction, so add the like
            $stmt = $pdo->prepare("INSERT INTO likes (attraction_id, user_id) VALUES (?, ?)");
            $stmt->execute([$id, $userId]);

            // Increase the like count in the attractions table
            $stmt = $pdo->prepare("UPDATE attractions SET likes = likes + 1 WHERE id = ?");
            $stmt->execute([$id]);
        }

        // Get the new like count
        $stmt = $pdo->prepare("SELECT likes FROM attractions WHERE id = ?");
        $stmt->execute([$id]);
        $newLikeCount = $stmt->fetchColumn();

        // Return a JSON response with the new like count
        echo json_encode(['success' => true, 'newLikeCount' => $newLikeCount]);
    } else {
        // Return an error message in the JSON response
        echo json_encode(['error' => 'ID not received']);
    }
    exit();
}
?>
}
?>
?>