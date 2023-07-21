<?php 
include "config.php";
$name = $username = $password = $confirmPassword = $emailid ='';

if(isset($_SESSION['user_id'])){
  header("location:userPage.php");
  exit();
}


if($_SERVER["REQUEST_METHOD"] == "POST"){
 $name=$_POST['name'];
 $username=$_POST['username'];
 $emailid=$_POST['emailid'];
 $password=$_POST['password'];
 $confirmPassword=$_POST['confirmPassword'];
 $duplicate="Select * from `loginSession` where username='$username' or emailid='$emailid' and status=0";
 $returnDuplicate=mysqli_query($conn,$duplicate);
 if(isset($_SESSION['user_id'])){
  header("location:userPage.php");
  exit();
}
//  echo mysqli_num_rows($returnDuplicate);
 if(mysqli_num_rows($returnDuplicate)>1){
    echo "<script>alert(`Duplicate record occurs`);</script>";
    header("location:register.php");
 }else{
    if($password == $confirmPassword){
       $password2=password_hash($_POST['password'],PASSWORD_BCRYPT);
        $insertQuery="INSERT INTO `loginSession`(username, name, Password, emailid, CreatedDate,status) VALUES ('$username','$name','$password2','$emailid',NOW(),0)";
        $insertRun=mysqli_query($conn,$insertQuery);
        header("location:login.php");
    }else{
        echo "<script>alert(`Passwords don't match`);</script>";

    }
 }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Registration Form</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f0f0f0;
    }
    form {
      max-width: 400px;
      margin: 0 auto;
      padding: 20px;
      border: 1px solid #ccc;
      border-radius: 5px;
      background-color: #fff;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      display: flex;
        flex-direction: column;
        align-items: center;
    }
    h2 {
      text-align: center;
    }
    label,
    input {
      display: block;
      width: 100%;
      margin-bottom: 10px;
    }
    input[type="text"],
    input[type="email"],
    input[type="password"] {
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      width: 87%;
    }
    input[type="submit"] {
      background-color: #4CAF50;
      color: white;
      cursor: pointer;
      padding: 10px 15px;
      border: none;
      border-radius: 5px;
    }
    input[type="submit"]:hover {
      background-color: #45a049;
    }
  </style>
</head>
<body>
  <form method="post">
    <h2>Registration Form</h2>

    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="emailid" required>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <label for="confirm-password">Confirm Password:</label>
    <input type="password" id="confirm-password" name="confirmPassword" required>

    <input type="submit" value="Register" name="submit">
    <div class="form-group register-link">
      <hr>
      <p>New User? <a href="login.php">Login</a></p>
    </div>
  </form>
  
</body>
</html>
