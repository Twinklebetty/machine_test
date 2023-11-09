<?php

include('dbconnection.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
$name = $_POST['name'];
$username = $_POST['username'];
$password = $_POST['password'];
$hpassword = password_hash($password,  
          PASSWORD_DEFAULT);
$sql = "INSERT INTO users (name,username,password) VALUES (?, ?, ?)";
$stmt = $connect->prepare($sql);
$stmt->bind_param('sss', $name, $username, $hpassword);
$stmt->execute();
$result = $stmt->store_result();
$stmt->close();
if($result)
{
  echo "<script>alert('users created successfully');window.location.href='index.php';</script>";
}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>User Registration</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class ="container">
<h1> User Registration </h1>
<form action="" method="post">	
<div class="mb-3">
  <label for="" class="form-label">Name</label>
  <input type="text" class="form-control" name="name" placeholder="" value="" required>
</div>
<div class="mb-3">
  <label for="" class="form-label">username</label>
  <input type="text" name="username" placeholder="enter username" class="form-control" value="" required>
</div>
<div class="mb-3">
  <label for="" class="form-label">Password</label>
  <input type="password" name="password" placeholder="enter password" class="form-control" value="" required>
</div>
<button type="submit" class="btn btn-primary">Register</button>
</form>	
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

