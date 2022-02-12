<?php
session_start();
include("../sys_db_connect.php");
if (isset($_POST['addClassSubmit'])) {
    $sql = "INSERT INTO tb_class(ClassTitle,ClassDetail,OwnerID,Day,ClassBegin)VALUES(?,?,?,?,?)";
    $stmt = $mysqli->prepare($sql);
    $classBegin = "{$_POST["Hours"]}:{$_POST["Minutes"]}";
    $stmt->bind_param("ssiss", $_POST["ClassTitle"], $_POST["ClassDetail"], $_SESSION["UserID"], $_POST["Day"], $classBegin);
    if (!$stmt->execute()) exit("ผิดพลาดในการเขียนข้อมูล");
    $ClassID = $stmt->insert_id;
    $stmt->close();
    header("location:./index.php?m=4&g=$ClassID");
}
if (empty($_SESSION['UserID'])) header("location:../login.php");
if (isset($_SESSION['Status']) and $_SESSION['Status'] === "ADMIN") include("./as_admin.php");
if (isset($_SESSION['Status']) and $_SESSION['Status'] === "USER") include("./as_user.php");
