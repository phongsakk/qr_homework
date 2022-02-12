<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if (isset($_SESSION["UserID"])) header("location:./dashboard.php");
if (isset($_REQUEST['loginSubmit'])) {
    include("./sys_db_connect.php");

    $Username = $_REQUEST['Username'];
    $Password = $_REQUEST['Password'];

    $sql = "SELECT * FROM tb_user WHERE Username=? AND Password=?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ss", $Username, $Password);
    $stmt->execute();
    $session = $stmt->get_result()->fetch_assoc();
    $mysqli->close();

    if (!$session) exit('ไม่พบผู้ใช้งาน หรือ รหัสผ่านไม่ถูกต้อง[<a href="./login.php">กลับ</a>]');

    session_start();
    $_SESSION['UserID'] = $session['UserID'];
    $_SESSION['Status'] = $session['Status'];
    session_write_close();

    header("location:./dashboard.php");
}
?>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.0-beta1/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" />

    <title>Login</title>
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
            <h2 class="col-12 text-center">เข้าสู่ระบบ</h2>
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
                    <div class="mb-3 text-center">
                        <button name="loginSubmit" class="btn btn-primary">
                            <i class="fa fa-sign-in-alt me-1" aria-hidden="true"></i> เข้าสู่ระบบ
                        </button>
                    </div>
                    <div class="mb-3 text-center">
                        หรือ <a href="./register.php">สมัครสมาชิก</a>
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