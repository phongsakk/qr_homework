<?php
session_start();
if (empty($_SESSION['UserID'])) header('Location:../login.php');
if (empty($_GET['classid'])) header("Location:./");

echo ("Delete");
