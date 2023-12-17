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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '';
    $password = $_POST['password'];
    $password = md5($password);
    // Validate inputs
    if (!$username || !$password) {
        $errors[] = 'Username and password are required.';
    } else {
        // Check if user exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        echo $user['password'];

        if ($user && ($password== $user['password'])) {

            // Session Management
            $_SESSION['userId'] = $user['id'];
            $_SESSION['username'] = $username;

            // User has been authenticated
            $_SESSION['login_time'] = time();

            // Redirect to account page
            header('Location: account.php');
            exit();
        } else {
            $errors[] = 'Invalid username or password.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="style.css" rel="stylesheet" type="text/css">
    <script src="main.js"></script>
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
                <a class="nav-link" href="index.php">Home <span class="sr-only"></span></a>
            </li>
            <!-- Additional navigation items here -->
        </ul>
    </div>
</nav>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2 class="text-center">Login</h2>
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $error): ?>
                            <p><?php echo htmlspecialchars($error); ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form method="post" action="login.php">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Login</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
