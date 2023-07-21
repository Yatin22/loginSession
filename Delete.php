<?php
include "config.php";
$userid=$_SESSION['user_id'];
$sql="Update `loginSession` set status=1 where userid=$userid";
$result=mysqli_query($conn,$sql);
header("location:logout.php");
?>