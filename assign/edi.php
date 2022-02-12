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
$stmt = $mysqli->prepare("SELECT * FROM tb_class WHERE ClassID=?");
$stmt->bind_param("i", $_GET['classid']);
$stmt->execute();
$class = $stmt->get_result()->fetch_assoc();
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
                <form class="needs-validation" method="post" novalidate>
                    <div class="row mb-3">
                        <label for="ClassName" class="col-sm-3 col-form-label">ชื่อวิชา</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="ClassName" placeholder="ชื่อวิชา" required value="<?= $class['ClassTitle'] ?>">
                            <div class="invalid-feedback">
                                กรุณากรอกชื่อวิชา!
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="ClassDetail" class="col-sm-3 col-form-label">ชื่อกลุ่มเรียน</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="ClassDetail" placeholder="ชื่อกลุ่มเรียน" required value="<?= $class['ClassDetail'] ?>">
                            <div class="invalid-feedback">
                                กรุณากรอกชื่อกลุ่มเรียน!
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="event_day_start_end" class="col-sm-3 col-form-label">วัน-เวลา</label>
                        <div class="col-sm-9">
                            <div class="input-group mb-2">
                                <span class="input-group-text col-2">วัน</span>
                                <select title="select day" name="ClassDay" class="form-select">
                                    <?php
                                    $days = ["จันทร์", "อังคาร", "พุธ", "พฤหัสบดี", "ศุกร์", "เสาร์", "อาทิตย์"];
                                    foreach ($days as $day) {
                                    ?>
                                        <option value="<?= $day ?>" <?= ($day === $class['Day']) ? " selected" : "" ?>>วัน<?= $day ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="input-group mb-2">
                                <span class="input-group-text col-2">เริ่ม</span>
                                <input type="time" name="ClassBegin" class="form-control" step="900" value="<?= date("H:i", strtotime($class['ClassBegin'])) ?>">
                            </div>
                            <div class="input-group mb-2">
                                <span class=" input-group-text col-2">จบ</span>
                                <input type="time" name="ClassEnd" class="form-control" step="900" value="<?= date("H:i", strtotime($class['ClassEnd'])) ?>">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 text-center">
                        <button name="Submit" class="btn btn-primary">
                            <i class="fa fa-save me-1" aria-hidden="true"></i> บันทึกข้อมูล
                        </button>
                    </div>
                </form>
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
    <script type="text/javascript">
        (function() {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms)
                .forEach(function(form) {
                    form.addEventListener('submit', function(event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        form.classList.add('was-validated')
                    }, false)
                })
        })()
    </script>
</body>

</html>