<?php
session_start();
// Session to track user login time
if (isset($_SESSION['login_time']) && time() - $_SESSION['login_time'] > 3600) {
    // Session timed out
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
} else if (isset($_SESSION['login_time'])) {
    // Session is not timed out yet, update the login time
    $_SESSION['login_time'] = time();
}
require_once 'db_connection.php'; // Database connection


$errors = [];
$success = false;

// User Authentication
if (!isset($_SESSION['userId'])) {
    header('Location: login.php');
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
     {
        // Input Validation
        $name = filter_input(INPUT_POST, 'destinationName', FILTER_SANITIZE_STRING);
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);

        if (empty($name) || empty($description)) {
            $errors[] = 'All fields are required.';
        } else {
            // SQL Injection Protection & Error Handling
            try {
                $stmt = $pdo->prepare("INSERT INTO destinations (name, description, user_id) VALUES (?, ?, ?)");
                $stmt->execute([$name, $description, $_SESSION['userId']]);
                $success = true;
            } catch (PDOException $e) {
                $errors[] = 'Database error: ' . $e->getMessage(); // Handle or log the error
            }
        }
    }
}




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Local Travel Guide</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet" type="text/css">
    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="index.php">Local Travel Guide</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a href="logout.php" class="btn btn-danger ml-2">Logout</a>
                </li>
                <!-- Additional navigation items here -->
            </ul>
        </div>
    </nav>
    
    <div class="container">
        <div class="container">
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <script>
            setTimeout(function() {
                $("#success-alert").alert('close');
            }, 5000); // The alert will close after 5 seconds
        </script>
    <?php endif; ?>
    <div class="alert alert-primary" role="alert">
        Welcome, <?php echo $_SESSION['username']; ?>
    </div>

    <h3>Add New Destination</h3>
    <form method="post" action="add_destination.php">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea class="form-control" id="description" name="description" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Add Destination</button>
    </form><br>

    <h3>Your Destinations</h3>
    <?php
        $userId = $_SESSION['userId'];
        $stmt = $pdo->prepare("SELECT * FROM destinations WHERE user_id = ?");
        $stmt->execute([$userId]);
        $destinations = $stmt->fetchAll();

        foreach ($destinations as $destination) {
            echo "<div class='card mb-3'>";
            echo "<div class='card-body'>";
            echo "<h5 class='card-title'>" . htmlspecialchars($destination['name']) . "</h5>";
            echo "<p class='card-text'>" . htmlspecialchars($destination['description']) . "</p>";
            echo "<button class='btn btn-primary' data-toggle='modal' data-target='#editModal' data-id='" . $destination['id'] . "' data-name='" . htmlspecialchars($destination['name']) . "' data-description='" . htmlspecialchars($destination['description']) . "'>Edit</button>";
            echo "<a href='delete.php?id=" . $destination['id'] . "' class='btn btn-danger ml-2'>Delete</a>";
            echo "</div>";
            echo "</div>";
        }
    ?>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edit Destination</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" action="edit.php">
        <div class="modal-body">
          <input type="hidden" id="editId" name="id">
          <div class="form-group">
            <label for="editName">Name:</label>
            <input type="text" class="form-control" id="editName" name="name" required>
          </div>
          <div class="form-group">
            <label for="editDescription">Description:</label>
            <textarea class="form-control" id="editDescription" name="description" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
  $('#editModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var id = button.data('id');
    var name = button.data('name');
    var description = button.data('description');

    var modal = $(this);
    modal.find('#editId').val(id);
    modal.find('#editName').val(name);
    modal.find('#editDescription').val(description);
  });
});
</script>