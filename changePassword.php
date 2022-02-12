<!DOCTYPE html>
<html lang="en">
<?php
session_start();
include("./sys_db_connect.php");

$sql = "SELECT * FROM tb_user WHERE UserID=?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $_SESSION['UserID']);
$stmt->execute();
$u = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (isset($_REQUEST['profileSubmit'])) {
    if ($_REQUEST['PasswordNew'] != $_REQUEST['PasswordChk']) exit("รหัสผ่านใหม่และรหัสผ่านยืนยันไม่ตรงกัน [ <a href='./changePassword.php'>กลับ</a> ]");

    if ($u['Password'] != $_REQUEST['Password']) exit("รหัสผ่านเก่าไม่ถูกต้อง [ <a href='./changePassword.php'>กลับ</a> ]");

    $sql = "UPDATE tb_user SET Password=? WHERE UserID=?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("si", $_REQUEST['PasswordNew'], $_SESSION['UserID']);
    $stmt->execute();
    $stmt->close();

    header("location:./dashboard.php");
}
?>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.0-beta1/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" />

    <title>Change Password</title>
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
            <h2 class="col-12 text-center">เปลี่ยนรหัสผ่าน</h2>
            <div class="col-5 bg-white p-3 mx-auto mt-3">
                <div id="UserStatus">
                    <div class="row mb-1">
                        <div class="col-2 fw-bold">
                            UID
                        </div>
                        <div class="col">
                            <?= str_pad($u['UserID'], 3, "0", STR_PAD_LEFT) ?>
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-2 fw-bold">
                            ชื่อ
                        </div>
                        <div class="col">
                            <?= $u['Name'] ?>
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-2 fw-bold">
                            Status
                        </div>
                        <div class="col">
                            <?= $u['Status'] ?>
                        </div>
                    </div>
                    <hr>
                    <form class="needs-validation" method="post" novalidate>
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" name="PasswordNew" placeholder="รหัสผ่านใหม่" required>
                            <label for="PasswordNew">รหัสผ่านใหม่</label>
                            <div class="invalid-feedback">
                                กรุณากรอกรหัสผ่านใหม่!
                            </div>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" name="PasswordChk" placeholder="ยืนยันรหัสผ่านใหม่" required>
                            <label for="PasswordChk">ยืนยันรหัสผ่านใหม่</label>
                            <div class="invalid-feedback">
                                กรุณากรอกยืนยันรหัสผ่านใหม่!
                            </div>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" name="Password" placeholder="รหัสผ่านเก่า" required>
                            <label for="Password">รหัสผ่านเก่า</label>
                            <div class="invalid-feedback">
                                กรุณากรอกรหัสผ่านเก่า!
                            </div>
                        </div>
                        <div class="mb-3 text-center">
                            <a href="./dashboard.php" class="btn btn-warning m-1"><i class="fa fa-backward" aria-hidden="true"></i> กลับ</a>
                            <button name="profileSubmit" class="btn btn-primary m-1"><i class="fa fa-save" aria-hidden="true"></i> บันทึกข้อมูล</button>
                        </div>
                        <div class="mb-3 text-center">
                            หรือ <a href="./profile.php">เปลี่ยนชื่อ</a>
                        </div>
                    </form>
                </div>

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