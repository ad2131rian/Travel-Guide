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



// Variable Initialization
$username = $email = $password = $confirmPassword = $firstName = $lastName = $country = '';
$agreeToTerms = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    {

        $username = isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '';
        $email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
        $password = $_POST['password']; // Password will be hashed, so no need for htmlspecialchars here
        $confirmPassword = $_POST['confirmPassword'];
        $firstName = isset($_POST['firstName']) ? htmlspecialchars($_POST['firstName']) : '';
        $lastName = isset($_POST['lastName']) ? htmlspecialchars($_POST['lastName']) : '';
        $country = isset($_POST['country']) ? htmlspecialchars($_POST['country']) : '';
        $agreeToTerms = isset($_POST['agreeToTerms']);

 }

    // Validate inputs
    if (!$username || strlen($username) < 3 || strlen($username) > 20) {
    $errors[] = 'Username must be between 3 and 20 characters.';
    }
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
    $errors[] = 'Username must contain only letters, numbers, and underscores.';
    }

    if (!preg_match('/^[a-zA-Z]+$/', $firstName)) {
        $errors[] = 'First name must contain only letters.';
     }
     if (!preg_match('/^[a-zA-Z]+$/', $lastName)) {
        $errors[] = 'Last name must contain only letters.';
     }
     


    if (!$password || strlen($password) < 8 || strlen($password) > 50) {
        $errors[] = 'Password must be between 8 and 50 characters.';
    }
    if (!preg_match('/[a-z]/', $password) ||
        !preg_match('/[A-Z]/', $password) ||
        !preg_match('/[0-9]/', $password) ||
        !preg_match('/[^\w]/', $password)) {
        $errors[] = 'Password must include upper and lower case letters, a number, and a special character.';
    }

    if ($password !== $confirmPassword) {
        $errors[] = 'Passwords do not match.';
    }
    

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format.';
    }
    // Check if email already exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        $errors[] = 'Email already in use.';
    }

    if (!$firstName) {
        $errors[] = 'First name is required.';
    }
    if (!$lastName) {
        $errors[] = 'Last name is required.';
    }

    

    if (!$agreeToTerms) {
        $errors[] = 'You must agree to the terms of service.';
    }

    // Proceed if no errors
    if (empty($errors)) {
        $hashedPassword = md5($password);
        $stmt = $pdo->prepare('INSERT INTO users (username, email, password, firstName, lastName, country) VALUES (?, ?, ?, ?, ?, ?)');
        $success = $stmt->execute([$username, $email, $hashedPassword, $firstName, $lastName, $country]);
        // Hash the password
        
       

        if ($success) {
            // Session Management
            $_SESSION['userId'] = $pdo->lastInsertId();
            $_SESSION['username'] = $username;
            
            // Redirect to account page
            header('Location: account.php');
            exit();
        } else {
            $errors[] = 'Registration failed. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta tags, title, and CSS links (similar to index.php) -->
    <link href="style.css" rel="stylesheet" type="text/css">
    <script src="main.js"></script>
    


</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <!-- Navbar content (similar to index.php) -->
    </nav>
    
    <div id="errorsContainer" style="display: none;"></div>


    <div class="container py-5">
       <div class="row justify-content-center">
           <div class="col-md-8">
               <h2 class="text-center">Register</h2>
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!$success): ?>
            <!-- Registration Form -->
        <?php else: ?>
            <!-- Success Message -->
            <div id="successMessage">
                <h2>Registration Successful!</h2>
                <p>Redirecting to account page...</p>
                <div id="loadingBarContainer">
                    <div id="loadingBar">
                    <div id="loadingBar" style="width: 100%; height: 30px; background-color: green;"></div>
                    </div>
                </div>
            </div>
            <script>
                setTimeout(function() {
                    window.location.href = 'account.php';
                }, 3000); // Redirect after 3 seconds
            </script>
        <?php endif; ?>
    </div>

        <form method="post" action="register.php">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" id="password" name="password" required onfocus="showPasswordHelp()" onblur="hidePasswordHelp()">
                    </div>

                    <div id="passwordHelp" class="form-text text-muted" style="display: none;">
                        <ul>
                            <li>Password must be between 8 and 50 characters.</li>
                            <li>Password must include upper and lower case letters, a number, and a special character.</li>
                        </ul>
                    </div>

            <div class="form-group">
                <label for="confirmPassword">Confirm Password:</label>
                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
            </div>
            <div class="form-group">
                <label for="firstName">First Name:</label>
                <input type="text" class="form-control" id="firstName" name="firstName" required>
            </div>
            <div class="form-group">
                <label for="lastName">Last Name:</label>
                <input type="text" class="form-control" id="lastName" name="lastName" required>
            </div>
            <div class="form-group">
    <label for="country">Country:</label>
    <select class="form-control" id="country" name="country">
        <option value="USA">United States</option>
        <option value="Canada">Canada</option>
        <option value="UK">United Kingdom</option>
        <option value="Australia">Australia</option>
        <option value="Germany">Germany</option>
        <option value="France">France</option>
        <option value="India">India</option>
        <option value="China">China</option>
        <option value="Japan">Japan</option>
        <option value="Brazil">Brazil</option>
        <!-- Add more countries as needed -->
    </select>
</div>

            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="agreeToTerms" name="agreeToTerms" required>
                <label class="form-check-label" for="agreeToTerms">I agree to the terms of service.</label>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
    </div>
        <!-- Registration success and progress bar -->!
    <div id="successMessage" style="display: <?php echo $success ? 'block' : 'none'; ?>;">
            <h2>Registration Successful!</h2>
            <p>Redirecting to account page...</p>
            <div id="loadingBarContainer" style="width: 100%; background-color: grey;">
                <div id="loadingBar" style="width: 0%; height: 30px; background-color: green;"></div>
            </div>
        </div>
    </div>

    <!-- Footer and scripts (similar to index.php) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <script>
      function showPasswordHelp() {
          $('#passwordHelp').show();
      }

      function hidePasswordHelp() {
          $('#passwordHelp').hide();
      }

      <?php if ($success): ?>
      var loadingWidth = 0;
      var interval = setInterval(function() {
          loadingWidth += 10;
          document.getElementById('loadingBar').style.width = loadingWidth + '%';
          if (loadingWidth >= 100) {
              clearInterval(interval);
              window.location.href = 'account.php'; // Redirect to account page
          }
      }, 300); // Adjust the speed of the loading bar
      <?php endif; ?>
  </script>
</body>
</html>

