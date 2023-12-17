<?php
// Include your database connection script here
include 'db_connection.php';

// Get the user's input from the AJAX request
$input = isset($_POST['query']) ? $_POST['query'] : '';



// Validate the input
if (!empty($input) && is_string($input)) {
    // Sanitize the input to prevent SQL injection
    $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');

        

    // Prepare an SQL statement for fetching matching values
    $stmt = $pdo->prepare('SELECT `name` FROM `clienti` WHERE `nominativo` LIKE :term OR `cell` LIKE :term');

    // Execute the statement with the user's input as a parameter
    $stmt->execute(['term' => "%" . $input . "%"]);

    // Fetch all matching rows
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Encode the rows as JSON and output the response
    echo json_encode($rows);
} else {
    // If the input is not valid, return an error message
    echo json_encode(['error' => 'Invalid input']);
}

?>

