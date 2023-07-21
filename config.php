<?php 
session_start();
$conn= mysqli_connect("localhost","phpmyadmin","12345","myDB");
if($conn){
    // echo "Connection succesful";
}else{
    echo "error";
}
?>