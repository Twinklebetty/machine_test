<?php
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: SAMEORIGIN");
header("X-XSS-Protection: 1; mode=block");
require_once('dbconnection.php');
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $error = array();
    $username = mysqli_real_escape_string($connect, $_POST['username']);
    $password = mysqli_real_escape_string($connect, $_POST['password']);
    if(empty($username)) {
        
            $error['username'] = "*Username should be filled.";
        }
    if(empty($password)) {
            $error['password'] = "*Password should be filled.";
        }
    //$username = $_POST['username'];
    $sql = "SELECT * FROM users WHERE username= ?";
    $statement = $connect->prepare($sql);
    $statement->bind_param('s', $username);
    $statement->execute();
    $result = $statement->get_result();
    while ($row = $result->fetch_assoc()) {
        if (! empty($row)) {
            $hashedPassword = $row["password"];
            if (password_verify($_POST["password"], $hashedPassword)) {
                $_SESSION["logged_in"] = true;
                $_SESSION["user_id"] = $row["user_id"];
                 header("location: fileupload.php");
            }
            else
            {
                $error['failed'] = "Invalid Username or Password!";
            }
        }
        else
            {
                $error['failed'] = "Invalid Username or Password!";
            }
    }
}


?>
<!DOCTYPE html>
<html>
<head>
    <title>User Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class ="container">

<h1> User login </h1>

<form action="" method="post">   
<div class="mb-3">
  <label for="" class="form-label">Username</label>
  <input type="text" class="form-control" name="username" placeholder="" value="">
  <?php if(isset($error['username'])) { echo '<span class="text-danger">' . $error['username'] . '</span>'; } ?>
</div>
<div class="mb-3">
  <label for="" class="form-label">Password</label>
  <input type="password" name="password" placeholder="enter password" class="form-control" value="">
   <?php if(isset($error['password'])) { echo '<span class="text-danger">' . $error['password'] . '</span>'; } ?>
</div>
<button type="submit" class="btn btn-primary">Login</button>
<a href="register.php">Create user </a>
</form>
  <?php if(isset($error['failed'])) { echo '<p class="text-danger">' . $error['failed'] . '</p>'; } ?>  
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
