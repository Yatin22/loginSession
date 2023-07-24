<?php 
include "config.php";
$userid = $_SESSION['user_id'];
$username = $_SESSION['user_name'];
if (!isset($_SESSION['user_id'])) {
    header("location:login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>User Page</title>
</head>
<body>
<div class="container">
    <h1>Welcome to Your User Page!</h1>
    <div class="user-info">
        <p><strong>User ID:</strong> <span id="user-id"><?php echo $userid; ?></span></p>
        <p><strong>Username:</strong> <span id="username"><?php echo $username; ?></span></p>
    </div>
    <div class="actions">
        <button onclick="logout()">Logout</button>
        <button onclick="deleteUser()">Delete User</button>
        <a href="UpdatePassword.php">
            <button>Update Password</button>
        </a>
        <a href="studentTable.php">
            <button>Go to Student Table</button>
        </a>
    </div>
</div>
<script>
    function logout() {
        var response = confirm("You really want to logout");
        if (response) {
            window.location.replace("logout.php");
        } else {
            window.location.reload();
        }
    }
    function deleteUser() {
        var response = confirm("You really want to Delete");
        if (response) {
            window.location.replace("Delete.php");
        } else {
            window.location.reload();
        }
    }
</script>
</body>
</html>