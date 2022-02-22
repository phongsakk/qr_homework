<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if (empty($_SESSION['UserID'])) header('Location:../login.php');
if (empty($_GET['classid'])) header("Location:./");
if (empty($_GET['userid'])) header("Location:./");

include("../sys_db_connect.php");

$cid = $_GET['classid'];
$uid = $_GET['userid'];

$sql = "SELECT * FROM tb_user WHERE UserID=?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $uid);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

$sql = "SELECT * FROM tb_exercise WHERE classid=? ORDER BY ExerciseStart,ExerciseEnd";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $cid);
$stmt->execute();
$exercises = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.0-beta1/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" />
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    <title>ระบบส่งงาน(ออนไลน์)</title>

    <style>
        * {
            font-family: 'Sarabun', sans-serif;
        }

        .form {
            overflow: hidden;
            height: 0;
            transition: height 0.25s;
        }

        .form.show {
            height: 3.375rem;
        }

        .ql-editor {
            /* padding: 12px 15px; */
            white-space: nowrap;
        }

        .ex-group {
            margin-bottom: 3rem;
            animation: fade-in .75s forwards;
        }

        .ex-group.hide {
            display: none;
            animation: fade-out .1s forwards;
        }

        @keyframes fade-in {
            0% {
                display: none;
                opacity: 0;
            }

            1% {
                display: block;
                opacity: 0
            }

            100% {
                display: block;
                opacity: 1;
            }
        }

        @keyframes fade-out {
            0% {
                display: block;
                opacity: 1
            }

            99% {
                display: block;
                opacity: 0;
            }

            100% {
                display: none;
                opacity: 0;
            }
        }
    </style>
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
                <!--  -->
                <div class="card border-info">
                    <div class="card-header bg-info text-white">
                        ประวัติการส่งงานของ <?= $user['Name'] ?>
                    </div>
                    <div class="card-body">
                        <?php
                        foreach ($exercises as $x) {
                            $sql = "SELECT * FROM tb_exercisesend WHERE UserID=? AND ExerciseID=?";
                            $stmt = $mysqli->prepare($sql);
                            $stmt->bind_param("ii", $uid, $x['ExerciseID']);
                            $stmt->execute();
                            $file = $stmt->get_result()->fetch_assoc();
                            $stmt->close();
                        ?>
                            <div class="card mb-3">
                                <div class="card-body p-0">
                                    <div class="ql-editor bg-white">
                                        <?= $x['ExerciseTitle'] ?>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-auto me-auto">
                                            <?= date('d M Y', strtotime($x['ExerciseStart'])) ?> - <?= date('d M Y', strtotime($x['ExerciseEnd'])) ?>
                                        </div>
                                        <div class="col-auto ms-auto">
                                            <?php
                                            if ($file) {
                                            ?>
                                                <a href="../upload/<?= $file['FileName'] ?>">ดูไฟล์</a>
                                            <?php
                                            } else {
                                            ?>
                                                <span class="text-muted">ไม่ได้ส่ง</span>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                        ?>

                    </div>
                </div>
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