<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if (isset($_SESSION["UserID"])) header("location:./dashboard.php");
if (isset($_REQUEST['registerSubmit'])) {

    if ($_REQUEST['Password'] != $_REQUEST['PasswordChk']) exit("รหัสผ่านไม่ตรงกัน [ <a href='./register.php'>กลับ</a> ]");

    include("./sys_db_connect.php");

    $sql = "SELECT Username FROM tb_user WHERE Username=?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $_REQUEST['Username']);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if ($user) exit("ชื่อผู้ใช้ถูกใช้แล้ว [ <a href='./register.php'>กลับ</a> ]");

    $sql = "INSERT INTO tb_user(Username,Password,Name,Status)VALUES(?,?,?,?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ssss", $_REQUEST['Username'], $_REQUEST['Password'], $_REQUEST['Name'], $_REQUEST['Status']);
    if (!$stmt->execute()) exit("ผิดพลาดระหว่างบันทึกข้อมูล [ <a href='./register.php'>กลับ</a> ]");

    header("location:./login.php");
}
?>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.0-beta1/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" />

    <title>Register</title>
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
            <h2 class="col-12 text-center">สมัครสมาชิก</h2>
            <div class="col-5 bg-white p-3 mx-auto mt-3">
                <form class="needs-validation" method="post" novalidate>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="Username" placeholder="ชื่อผู้ใช้" required>
                        <label for="Username">ชื่อผู้ใช้</label>
                        <div class="invalid-feedback">
                            กรุณากรอกชื่อผู้ใช้!
                        </div>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" name="Password" placeholder="รหัสผ่าน" required>
                        <label for="Password">รหัสผ่าน</label>
                        <div class="invalid-feedback">
                            กรุณากรอกรหัสผ่าน!
                        </div>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" name="PasswordChk" placeholder="ยืนยันรหัสผ่าน" required>
                        <label for="PasswordChk">ยืนยันรหัสผ่าน</label>
                        <div class="invalid-feedback">
                            กรุณากรอกยืนยันรหัสผ่าน!
                        </div>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="Name" placeholder="ชื่อ-นามสกุล" required>
                        <label for="Name">ชื่อ-นามสกุล</label>
                        <div class="invalid-feedback">
                            กรุณากรอกชื่อ-นามสกุล!
                        </div>
                    </div>
                    <div class="mb-3">
                        <select class="form-select form-select" name="Status">
                            <option value="USER">ผู้ใช้งาน</option>
                        </select>
                    </div>
                    <div class="mb-3 text-center">
                        <button name="registerSubmit" class="btn btn-primary">
                            <i class="fa fa-user-plus me-1" aria-hidden="true"></i> สมัครสมาชิก
                        </button>
                    </div>
                    <div class="mb-3 text-center">
                        หรือ <a href="./login.php">เข้าสู่ระบบ</a>
                    </div>
                </form>
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