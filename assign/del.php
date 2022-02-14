<?php
session_start();
if (empty($_SESSION['UserID'])) header('Location:../login.php');
if (empty($_GET['classid'])) header("Location:./");
include("../sys_db_connect.php");

$sql = "DELETE FROM tb_exercisesend WHERE ExerciseID IN (SELECT ExerciseID FROM tb_exercise WHERE ClassID=?)";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $_GET['classid']);
$stmt->execute();
$stmt->close();

$sql = "DELETE FROM tb_exercise WHERE ClassID=?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $_GET['classid']);
$stmt->execute();
$stmt->close();

$sql = "DELETE FROM tb_enroll WHERE ClassID=?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $_GET['classid']);
$stmt->execute();
$stmt->close();

$sql = "DELETE FROM tb_class WHERE ClassID=?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $_GET['classid']);
$stmt->execute();
$stmt->close();

echo "ลบสำเร็จ <a href='./'>กลับ</a>";
