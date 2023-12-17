<?php
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['search'])) {
        $search = $_POST['search'];
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username LIKE ? OR email LIKE ?");
        $stmt->execute(["%$search%", "%$search%"]);
        $results = $stmt->fetchAll();

        foreach ($results as $result) {
            echo $result['username'] . ", " . $result['email'] . "<br>";
        }
    }
}
?>
