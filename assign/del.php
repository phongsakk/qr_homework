<?php
session_start();
if (empty($_SESSION['UserID'])) header('Location:../login.php');
if (empty($_GET['classid'])) header("Location:./");
// include("../sys_db_connect.php");
// $stmt = $mysqli->prepare("");
echo ("Delete");
