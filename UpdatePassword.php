<?php
include "config.php";
if(!(isset($_SESSION['user_id']))){
    header("location:login.php");
    exit();
  }
  
  if($_SERVER['REQUEST_METHOD']=="POST"){
    $oldPassword=$_POST['oldPassword'];
    $NewPassword=$_POST['NewPassword'];
    $CoPassword=$_POST['CoPassword'];

    $userid=$_SESSION['user_id'];
    $username=$_SESSION['user_name'];
    $sql="Select * from `loginSession` where userid=$userid and status=0";
    $result=mysqli_query($conn,$sql);
    if(mysqli_num_rows($result)==1){
        $result=mysqli_fetch_array($result);
        echo $result;
        $passCheck = password_verify($oldPassword,$result['Password']);
        if($passCheck){
            if($NewPassword==$CoPassword){
                $NewPassword=password_hash($NewPassword,PASSWORD_BCRYPT);
                $done=mysqli_query($conn,"Update `loginSession` set Password='$NewPassword',updatedDate= NOW() where userid=$userid"); 
                echo "<script>alert('Your Password is updated');</script>";
                header("location:userPage.php");
            }else{
                echo "<script>alert('Password and confirm password don't match');</script>";
            }
        }

    }
}

?>


<!DOCTYPE html>
<html>
<head>
  <title>Change Password</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f0f0f0;
    }
    .container {
      max-width: 400px;
      margin: 0 auto;
      padding: 20px;
      border: 1px solid #ccc;
      border-radius: 5px;
      background-color: #fff;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    h2 {
      text-align: center;
    }
    .form-group {
      margin-bottom: 20px;
    }
    .form-group label {
      display: block;
      font-weight: bold;
      margin-bottom: 5px;
    }
    .form-group input {
      width: 100%;
      padding: 10px;
      font-size: 16px;
      border: 1px solid #ccc;
      border-radius: 5px;
      box-sizing: border-box;
    }
    .form-group button {
      width: 100%;
      padding: 10px;
      font-size: 16px;
      background-color: #4CAF50;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    .form-group button:hover {
      background-color: #45a049;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Change Password</h2>
    <form method="POST">
      <div class="form-group">
        <label for="old-password">Old Password:</label>
        <input type="password" id="old-password" name="oldPassword" required>
      </div>
      <div class="form-group">
        <label for="new-password">New Password:</label>
        <input type="password" id="new-password" name="NewPassword" required>
      </div>
      <div class="form-group">
        <label for="confirm-password">Confirm New Password:</label>
        <input type="password" id="confirm-password" name="CoPassword" required>
      </div>
      <div class="form-group">
        <button type="submit" >Update Password</button>
      </div>
    </form>
  </div>

  
</body>
</html>
