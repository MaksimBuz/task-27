<?php 
include './connect.php';
$password=md5($_POST["pass"]);
$password=md5($password."MEGASECRET");
$login=$_POST["login"];
$query="INSERT INTO `users` (`USER_ID`, `LOGIN`, `PASSWORD`,`User_role`) VALUES (NULL, '$login', '$password','1')";
$success = $mysqli->query($query);
header('location:./index.php');
?>