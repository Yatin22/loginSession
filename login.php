<?php

include("config.php");

if(isset($_SESSION['user_id'])){
  header("location:userPage.php");
  exit();
}


if($_SERVER['REQUEST_METHOD']=="POST"){
  $username=$_POST['username'];
  $password=$_POST['password'];
  $sql="Select * from `loginSession` where username='$username'";
  $passwordcheck=mysqli_query($conn,$sql);
  if(mysqli_num_rows($passwordcheck)==1){
    $row=mysqli_fetch_array($passwordcheck);
    if($row['status']==1){
      echo "<script>alert(`User not found`);</script>";
    }
    else{
      if (password_verify($password,$row['Password'])){
        $_SESSION['user_id']=$row['userid'];
        $_SESSION['user_name']=$row['username'];
        header("location:userPage.php");
        exit();
       }else{
        echo "<script>alert(`Check your password`);</script>";
      
       }
    }
   
  }else{
    echo "<script>alert(`User not found`);</script>";

  }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Page</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f2f2f2;
    }
    
    .login-container {
      max-width: 400px;
      margin: 0 auto;
      padding: 20px;
      border: 1px solid #ccc;
      background-color: #fff;
      border-radius: 5px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    
    .login-container h1 {
      text-align: center;
      margin-bottom: 30px;
    }
    
    .form-group {
      margin-bottom: 20px;
    }
    
    .form-group label {
      display: block;
      font-weight: bold;
      margin-bottom: 5px;
    }
    
    .form-group input[type="text"],
    .form-group input[type="password"] {
      width: 100%;
      padding: 10px;
      font-size: 16px;
      border: 1px solid #ccc;
      border-radius: 3px;
      box-sizing: border-box; /* Include padding and border in the width */
    }
    
    .form-group button {
      width: 100%;
      padding: 10px;
      font-size: 16px;
      background-color: #4CAF50;
      color: #fff;
      border: none;
      border-radius: 3px;
      cursor: pointer;
    }
    
    .form-group button:hover {
      background-color: #45a049;
    }
    
    .form-group .register-link {
      text-align: center;
    }
    
    .form-group .register-link a {
      color: #007bff;
      text-decoration: none;
    }
    
    .form-group .register-link a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <h1>Login</h1>
    <form method="post">
      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" placeholder="Enter your username" name="username" required>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" placeholder="Enter your password" name="password" required>
      </div>
      <div class="form-group">
        <button type="submit">Login</button>
      </div>
    </form>
    <div class="form-group register-link">
      <hr>
      <p>New User? <a href="Register.php">Register</a></p>
    </div>
  </div>
</body>
</html>
