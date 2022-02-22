<!DOCTYPE html>
<html lang="en">
<?php

session_start();
if (empty($_SESSION['UserID'])) header('Location:../login.php');
if (empty($_GET['classid'])) header("Location:./");

include("../sys_db_connect.php");
if (isset($_POST['Submit'])) {
    var_export($_POST);
    $stmt = $mysqli->prepare("UPDATE tb_class SET ClassTitle=?,ClassDetail=?,Day=?,ClassBegin=?,ClassEnd=? WHERE ClassID=?");
    $stmt->bind_param("sssssi", $_POST['ClassName'], $_POST['ClassDetail'], $_POST['ClassDay'], $_POST['ClassBegin'], $_POST['ClassEnd'], $_GET['classid']);
    if (!$stmt->execute()) exit("cannot save!");
    header("Location:./");
}

$stmt = $mysqli->prepare("SELECT *,(SELECT COUNT(*) FROM tb_exercise WHERE ClassID = tb_class.ClassID) AS TotalWork FROM tb_class WHERE ClassID=?");
$stmt->bind_param("i", $_GET['classid']);
$stmt->execute();
$class = $stmt->get_result()->fetch_assoc();
$stmt->close();

$begin = date("H:i", strtotime($class['ClassBegin']));
$end = date("H:i", strtotime($class['ClassEnd']));

$sql = "SELECT *,
        (SELECT COUNT(*) 
            FROM tb_exercisesend 
            WHERE ExerciseID 
            IN(
                SELECT ExerciseID FROM tb_exercise WHERE ClassID=e.ClassID
            )
            AND UserID=u.UserID
        )AS WorkSent 
        FROM tb_enroll e 
        INNER JOIN tb_user u ON e.UserID=u.UserID WHERE ClassID=?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $_GET['classid']);
$stmt->execute();
$users = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.0-beta1/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" />

    <title>ระบบส่งงาน(ออนไลน์)</title>
</head>

<body>
    <header class="bg-light">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light ">
                <div class="container-fluid">
                    <a class="navbar-brand" href="/Anuchit/index.php">SiteLogo</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-link" aria-current="page" href="/Anuchit/index.php">หน้าหลัก</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/Anuchit/dashboard.php">แผงควบคุม</a>
                            </li>
                        </ul>
                        <div class="d-flex ms-3">
                            <a href="./login.php" class="btn btn-outline-success">เข้าสู่ระบบ</a>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </header>

    <main>
        <div class="container mt-5 bg-light p-3 rounded">
            <h2 class="col-12 text-center">ระบบส่งงาน(ออนไลน์)</h2>
            <div class="col-12 bg-white p-3 mt-3 shadow-sm mx-auto" style="max-width:550px">
                <table class="table align-middle table-hover">
                    <thead class="table-active table-secondary">
                        <tr>
                            <th>ชื่อวิชา</td>
                            <td colspan=3><?= $class['ClassTitle'] . " [" . str_pad($class['ClassID'], 3, "0", false) . "]" ?></td>
                        </tr>
                        <tr>
                            <th>กลุ่มเรียน</td>
                            <td colspan=3><?= $class['ClassDetail'] ?></td>
                        </tr>
                        <tr>
                            <th>วัน-เวลา</td>
                            <td colspan=2><?= $class['Day'] . "(" . $begin . "-" . $end . ")" ?></td>
                            <td class="py-0">
                                <a href="./edi.php?classid=<?= $_GET['classid'] ?>" class="btn btn-primary">
                                    <i class="fa fa-edit me-1" aria-hidden="true"></i>แก้ไข
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td colspan=4></td>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <td>#</td>
                            <td>ชื่อ-นามสกุล</td>
                            <td>จำนวนงาน</td>
                            <td>เมนู</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($users as $user) {
                        ?>
                            <tr>
                                <td><?= str_pad($user['UserID'], 3, "0", STR_PAD_LEFT) ?></td>
                                <td><?= $user['Name'] ?></td>
                                <td><?= $user['WorkSent'] . "/" . $class['TotalWork'] ?></td>
                                <td>
                                    <a href="./rem.php?classid=<?= $_GET['classid'] ?>&userid=<?= $user['UserID'] ?>" class="btn btn-danger" onclick="return confirm('คุณต้องการลบผู้เรียนดังกล่าวหรือไม่?')">
                                        <i class="fa fa-trash me-1" aria-hidden="true"></i>ลบออก
                                    </a>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>

                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan=4>
                                <div class="col-sm-12 text-center">
                                    <a href="./add.php?classid=<?= $_GET['classid'] ?>" class="btn btn-primary">
                                        <i class="fa fa-plus me-1" aria-hidden="true"></i>เพิ่มผู้เรียน
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="col-12 mt-3 mx-auto text-end" style="max-width:550px">
                <a href="./" class="btn btn-secondary me-1">
                    <i class="fa fa-tasks me-1" aria-hidden="true"></i> ระบบส่งงาน
                </a>
                <a href="../dashboard.php" class="btn btn-secondary">
                    <i class="fa fa-tachometer-alt me-1" aria-hidden="true"></i> ไปที่แผงควบคุม
                </a>
            </div>
        </div>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.0-beta1/js/bootstrap.bundle.min.js"></script>
</body>

</html>