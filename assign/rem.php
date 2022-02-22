<?php
include("../sys_db_connect.php");

$uid = $_GET['userid'];
$cid = $_GET['classid'];

// remove all file that sent by the user to the class:
$sql = "SELECT * FROM tb_exercisesend WHERE UserID=? AND ExerciseID IN (SELECT ExerciseID FROM tb_exercise WHERE ClassID=?)";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ii", $uid, $cid);
$stmt->execute();
$arr = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
foreach (array_column($arr, 'FileName') as $file) {
    if (file_exists("../upload/{$file}")) unlink("../upload/{$file}");
}

// drop rows:
$sql = "DELETE FROM tb_exercisesend WHERE UserID=? AND ExerciseID IN (SELECT ExerciseID FROM tb_exercise WHERE ClassID=?)";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ii", $uid, $cid);
$stmt->execute();

$sql = "DELETE FROM tb_enroll WHERE UserID=? AND ClassId=?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ii", $_GET['userid'], $_GET['classid']);
$stmt->execute();

$stmt->close();

header("Location:./man.php?classid={$_GET['classid']}");
