<?php
include 'db_connection.php';
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

$order = isset($_POST['order']) ? $_POST['order'] : 'ASC';
$category = isset($_POST['category']) ? $_POST['category'] : '';

$search="";

if(isset($_POST['submit'])){
   $search=$_POST['search'];
}
$sort = isset($_POST['sort']) ? $_POST['sort'] : 'name';
$category = isset($_POST['category']) ? $_POST['category'] : '';

// Prepare a SQL statement
$sql = "SELECT * FROM attractions WHERE 1=1" . 
    (!empty($category) ? " AND category = :category" : "") . 
    " ORDER BY " . ($sort == 'rating' ? 'likes' : 'name');

$stmt = $pdo->prepare($sql);

// Bind parameters
if (!empty($category)) {
    $stmt->bindParam(':category', $category);
}

// Execute the statement
if ($stmt->execute()) {
    $attractions = $stmt->fetchAll();
} else {
    echo "Error: " . implode(", ", $stmt->errorInfo());
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
        
        <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCGIVjWKaYXPyPlTJCcy0blJlYyOkHxPn8&libraries=places&callback=initMap"></script>
        
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
            <li class="nav-item active">
                </li>
                <?php if (isset($_SESSION['userId'])): ?>
                    <a class="nav-link" href="account.php">Account <span class="sr-only">(current)</span></a>
                <li class="nav-item">
                    <a href="logout.php" class="btn btn-danger ml-2">Logout</a>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="register.php">Register</a>
                </li>
            <?php endif; ?>
            <!-- Additional navigation items here -->
        </ul>
    </div>
</nav>
    
    
    <div class="container mt-4">
        <h1>Welcome to the Local Travel Guide</h1>
        <p>Explore and contribute to local attractions.</p>
        
        <!-- Search Form -->
        <form method='post'>        
            <div class="row mb-4">
                <div class="col">
                    <input type="text" name='search' value="<?php echo $search;?>" class="form-control" placeholder="Search destinations...">
                </div>
                <div class="col-auto">
                    <input value="Search" name='submit' type='submit' class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                </div>
            </div>
        </form>
        <form method="POST" action="">
            <label for="sort">Sort By:</label>
            <select name="sort" id="sort">
                <option value="name" <?php echo $sort == 'name' ? 'selected' : ''; ?>>Name</option>
                <option value="rating" <?php echo $sort == 'rating' ? 'selected' : ''; ?>>Rating</option>
            </select>

            <label for="order">Order:</label>
            <select name="order" id="order">
                <option value="ASC" <?php echo $order == 'ASC' ? 'selected' : ''; ?>>Ascending</option>
                <option value="DESC" <?php echo $order == 'DESC' ? 'selected' : ''; ?>>Descending</option>
            </select>
            <label for="category">Filter By Category:</label>
            <select name="category" id="category">
                <option value="">All Categories</option>
                <option value="history" <?php echo $category == 'history' ? 'selected' : ''; ?>>History</option>
                <option value="nature" <?php echo $category == 'nature' ? 'selected' : ''; ?>>Nature</option>
                <option value="art" <?php echo $category == 'art' ? 'selected' : ''; ?>>Art</option>
                <option value="city" <?php echo $category == 'city' ? 'selected' : ''; ?>>City</option>
            </select>
            
            <button type="submit" class="btn btn-primary">
                Apply
            </button>
        </form>
            <?php

            $sql = "SELECT * FROM attractions WHERE (name LIKE :search OR description LIKE :search)" . 
            (!empty($category) ? " AND category = :category" : "") . 
            " ORDER BY " . ($sort == 'rating' ? 'likes' : 'name') . " " . $order;
            
            $stmt = $pdo->prepare($sql);
            
            $params = [
    ':search' => $search.'%',
];


            if (!empty($category)) {
                $params[':category'] = $category;
            }
            
            $stmt->execute($params);
            
            while ($row = $stmt->fetch()) {
                // Display the attractions here
            
                    echo "<div class='card mb-3'>";
                    echo "<div class='card-body'>";
                    echo "<h5 class='card-title'>" . htmlspecialchars($row['name']) . "</h5>";
                    echo "<p class='card-text'>" . htmlspecialchars($row['description']) . "</p>";
                    echo "<img class='card-image' width='200px' src='assets/images/" . htmlspecialchars($row['image_path']) . "'></img>";
                    if (isset($_SESSION['userId'])) {
                        echo "<form method='POST' action='like.php'>";
                    }
                    else{
                        echo "<form action='login.php'>";
                    }
                    echo '<input type="hidden" name="id" value="'.$row['id'].'">';
                    // Check if the user has liked the attraction
                    $stmta = $pdo->prepare("SELECT * FROM likes WHERE attraction_id = ? AND user_id = ?");
                    $stmta->execute([$row['id'], isset($_SESSION['userId']) ? $_SESSION['userId'] : 0]);
                    $like = $stmta->fetch();

                    // If the user has liked the attraction, add the 'heart-active' class
                    $likeClass = $like ? 'heart-active' : '';

                    echo "<div class='heart-btn'>";
                    echo "<div class='content $likeClass'>";
                    echo "<span class='heart $likeClass'></span>";
                    echo "<span class='text $likeClass'>Like</span>";
                    echo "<span class='numb $likeClass'>" . $row['likes'] . "</span>";
                    echo "</div>";
                    echo "</div>";
                    echo "</form>";
                    echo "</div>";
                    echo "<button class='btn btn-primary more-info-btn' data-toggle='modal' data-target='#infoModal' data-id='" . $row['id'] . "' data-name='" . htmlspecialchars($row['name']) . "' data-description='" . htmlspecialchars($row['description']) . "'>More Info</button>";                    
                    echo "</div>";
                }
                
            
            ?>
            <script>
            $(document).ready(function(){
                $('.content').click(function(e){
                    e.preventDefault(); // Prevent the form from being submitted normally

                    $(this).toggleClass("heart-active");
                    $(this).find('.text').toggleClass("heart-active");
                    $(this).find('.numb').toggleClass("heart-active");
                    $(this).find('.heart').toggleClass("heart-active");

                    var form = $(this).closest('form');
                    var formData = form.serialize(); // Get the data from the form

                    // Store the 'this' context in a variable
                    var that = $(this);

                    $.ajax({
                        type: 'POST',
                        url: form.attr('action'),
                        data: formData,
                        success: function(response) {
                            // Update the like count
                            var likeCountElement = that.find('.numb');
                            likeCountElement.text(response.newLikeCount);
                        }
                    });
                });
            });

            </script>

            <input id="pac-input" class="controls" type="text" placeholder="Search Box">
            <input id="user-location" class="controls" type="text" placeholder="Your Location">

            <div id="map"></div>
            <script src="main.js"></script>
             <!-- Info Modal -->
            <div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="infoModalLabel">Attraction Info</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="infoModalBody">
                    <!-- Info from Wikipedia will be inserted here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
                </div>
            </div>
            </div>           
            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel"><?php echo isset($destinations['name']) ? $destinations['name'] : ''; ?></h5>
                            
                            <?php
                    echo isset($destinations['description']) ? $destinations['description'] : '';
                    echo isset($destinations['image_path']) ? '<img src="assets/images/'.$destinations['image_path'].'" alt="'.$destinations['name'].'">' : '';
                    ?>
                    
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
</body>
</html>





