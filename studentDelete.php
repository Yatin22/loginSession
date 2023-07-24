<?php
include "config.php";
    if(!(isset($_SESSION['user_id']))){
        header("location:login.php");
        exit();
      }
      if($_SERVER['REQUEST_METHOD']=='GET'){
          $id=$_GET['id'];
          $delete=mysqli_query($conn,"DELETE FROM `student` WHERE student_id=$id and status=1");
          echo "<script>alert('student with studentid=$id is deleted');</script>";
          header("location:studentTable.php");
      }



?>