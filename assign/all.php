<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if (empty($_SESSION['UserID'])) header('Location:../login.php');
if (empty($_GET['classid'])) header("Location:./");

include("../sys_db_connect.php");
if (isset($_POST['submit'])) {

    // Rename:
    $target_dir = "../upload/";
    $file_type = strtolower(pathinfo($_FILES['fm-file']['name'], PATHINFO_EXTENSION));
    $file_newname =
        str_pad($_SESSION['UserID'], 3, "0", STR_PAD_LEFT) .
        str_pad($_POST['fm-cl-id'], 3, "0", STR_PAD_LEFT) .
        str_pad($_POST['fm-hw-id'], 3, "0", STR_PAD_LEFT) .
        "." .
        $file_type;
    $file_upload = $target_dir . $file_newname;

    // Upload:
    if (move_uploaded_file($_FILES["fm-file"]["tmp_name"], $file_upload)) {
        echo "The file " . htmlspecialchars(basename($_FILES["fm-file"]["name"])) . " has been uploaded as " . $file_newname . " <br>";
    } else {
        echo "Sorry, there was an error uploading your file.<br>";
    }

    // Collect to Database:
    $sql = "INSERT INTO tb_exercisesend(ExerciseID,UserID,FileName)VALUES(?,?,?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("iis", $_POST['fm-hw-id'], $_SESSION['UserID'], $file_newname);
    $stmt->execute();
    $stmt->close();

    // Report:
    echo "<script>alert('บันทึกไฟล์แล้ว');window.location='./all.php?classid={$_POST['fm-cl-id']}';</script>";
    exit();
}

$sql = "SELECT *,
            (
                SELECT COUNT(*)
                FROM tb_exercisesend es
                
            )AS Status,
            (
                SELECT COUNT(*) 
                FROM tb_exercisesend es 
                WHERE es.ExerciseID=e.ExerciseID 
                AND es.UserID=?
            )AS Send 
        FROM tb_exercise e 
        WHERE ClassID=?
        ORDER BY e.ExerciseEnd";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ii", $_SESSION['UserID'], $_GET['classid']);
$stmt->execute();
$exercies = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.0-beta1/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" />
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Sarabun" rel="stylesheet">

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
            <div class="col-12 bg-white p-3 mt-3 shadow-sm mx-auto" style="max-width:800px">
                <h4 class="col-sm-12 border-bottom border-2 pb-2 mb-2">การบ้านทั้งหมด</h4>
                <div class="row g-0 mb-3">
                    <div class="col-sm-12">
                        ตัวกรอง
                    </div>
                    <div class="col-auto m-1 alert alert-light p-1 border" role="button" onclick="filter(3)">
                        <i class="fa fa-question-circle me-1" aria-hidden="true"></i> ทั้งหมด
                    </div>
                    <div class="col-auto m-1 alert alert-primary p-1" role="button" onclick="filter(-1)">
                        <i class="fa fa-question-circle me-1" aria-hidden="true"></i> ยังไม่ถึงกำหนดส่ง
                    </div>
                    <div class="col-auto m-1 alert alert-warning p-1" role="button" onclick="filter(0)">
                        <i class="fa fa-exclamation-circle me-1" aria-hidden="true"></i> อยู่ในช่วงส่งงาน
                    </div>
                    <div class="col-auto m-1 alert alert-success p-1" role="button" onclick="filter(1)">
                        <i class="fa fa-info-circle me-1" aria-hidden="true"></i> ส่งงานแล้ว
                    </div>
                    <div class="col-auto m-1 alert alert-danger p-1" role="button" onclick="filter(2)">
                        <i class="fa fa-exclamation-triangle me-1" aria-hidden="true"></i> เลยกำหนดส่ง
                    </div>
                </div>
                <div class="p-3">
                    <?php
                    foreach ($exercies as $i => $x) :
                        $today = strtotime(date('Y-m-d H:i:s'));
                        $classBegin = strtotime($x['ExerciseStart']);
                        $classEnd = strtotime($x['ExerciseEnd']);
                    ?>
                        <div class="ex-group col-sm-12 shadow-sm position-relative border rounded">
                            <div class="px-4 py-3 bg-light rounded-top border-bottom">
                                <div class="ql-editor bg-white">
                                    <?= $x['ExerciseTitle'] ?>
                                </div>
                            </div>
                            <div class="px-3 py-2 bg-light rounded-bottom">
                                <div class="row mb-2">
                                    <div class="col-sm-6">เริ่ม: <?= date('F dS, Y @H:iน', $classBegin) ?></div>
                                    <div class="col-sm-6">สิ้นสุด: <?= date('F dS, Y @H:iน', $classEnd) ?></div>
                                </div>
                                <?php
                                if ($today < $classBegin) :
                                ?>
                                    <div class="alert alert-primary p-2 mb-0" role="alert">
                                        <i class="fa fa-question-circle me-1" aria-hidden="true"></i> ยังไม่ถึงกำหนดส่ง
                                    </div>
                                <?php
                                elseif ($x['Send'] == 1) :
                                    // Alert Sent:
                                    $stmt = $mysqli->prepare("SELECT * FROM tb_exercisesend WHERE UserID = ? AND ExerciseID=?");
                                    $stmt->bind_param("ii", $_SESSION['UserID'], $x['ExerciseID']);
                                    $stmt->execute();
                                    $arr = $stmt->get_result()->fetch_assoc();
                                    $stmt->close();
                                ?>
                                    <div class="alert alert-success p-2 mb-0">
                                        <div>
                                            <div class="row g-0">
                                                <div class="col-auto">
                                                    <i class="fa fa-info-circle me-1" aria-hidden="true"></i> ส่งงานแล้ว
                                                </div>
                                                <a class="col-auto btn btn-primary ms-auto me-0" href="<?= "../upload/" . $arr['FileName'] ?>">
                                                    <i class="fa fa-download me-1" aria-hidden="true"></i> ดาวน์โหลด
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                elseif ($today > $classEnd) :
                                    // Alert Expired:
                                ?>
                                    <div class="alert alert-danger p-2 mb-0" role="alert">
                                        <i class="fa fa-exclamation-triangle me-1" aria-hidden="true"></i> เลยกำหนดส่ง
                                    </div>
                                <?php
                                else :
                                    // 
                                ?>
                                    <div class="">
                                        <div class="alert alert-warning p-2 mb-0" role="alert">
                                            <div>
                                                <div class="row g-0">
                                                    <div class="col-auto">
                                                        <i class="fa fa-exclamation-circle me-1" aria-hidden="true"></i> อยู่ในช่วงส่งงาน
                                                    </div>
                                                    <button class="col-auto btn btn-primary ms-auto me-0" onclick="showForm(this)">
                                                        <i class="fa fa-file-alt me-1" aria-hidden="true"></i> ส่งงาน
                                                    </button>
                                                </div>
                                            </div>
                                            <form class="form" method="post" enctype="multipart/form-data">
                                                <div class="row">
                                                    <div class="col-sm-9 mx-auto py-2">
                                                        <div class="col-sm-12 input-group">
                                                            <input type="file" name="fm-file" title="อัพโหลดไฟล์" class="form-control" required>
                                                            <input type="hidden" name="fm-cl-id" value="<?= $x['ClassID'] ?>">
                                                            <input type="hidden" name="fm-hw-id" value="<?= $x['ExerciseID'] ?>">
                                                            <button class="btn btn-secondary" name="submit" value="<?= $x['ExerciseID'] ?>">
                                                                <i class="fa fa-upload me-1" aria-hidden="true"></i> อัพโหลด
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                <?php
                                endif;
                                if ($x['Send'] == 0) :
                                ?>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        ยังไม่ส่ง
                                        <span class="visually-hidden">Status</span>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php
                    endforeach;
                    ?>
                </div>
            </div>
            <div class="col-7 mx-auto mt-4">
                <div class="col-12 p-3 mt-3 text-end">
                    <a href="../dashboard.php" class="btn btn-secondary">
                        <i class="fa fa-tachometer-alt me-1" aria-hidden="true"></i> ไปที่แผงควบคุม
                    </a>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.0-beta1/js/bootstrap.bundle.min.js"></script>
    <script>
        function showForm(btn) {
            btn.parentNode.parentNode.nextElementSibling.classList.toggle("show")
        }
        var groups = document.getElementsByClassName('ex-group');
        // console.log(groups);
        for (let i = 0; i < groups.length; i++) {
            let alert = groups[i].querySelector('.alert');
            if (alert.classList.contains("alert-primary")) {
                groups[i].setAttribute("data-hw", -1);
            }
            if (alert.classList.contains("alert-warning")) {
                groups[i].setAttribute("data-hw", 0);
            }
            if (alert.classList.contains("alert-success")) {
                groups[i].setAttribute("data-hw", 1);
            }
            if (alert.classList.contains("alert-danger")) {
                groups[i].setAttribute("data-hw", 2);
            }
        }

        async function filter(param) {
            for (let i = 0; i < groups.length; i++) {
                groups[i].classList.add("hide");
            }
            await wait(100);
            if (param < 3) {
                for (let i = 0; i < groups.length; i++) {
                    if (groups[i].getAttribute("data-hw") == param) {
                        groups[i].classList.remove("hide");
                    } else {
                        groups[i].classList.add("hide");
                    }
                }
            } else {
                for (let i = 0; i < groups.length; i++) {
                    groups[i].classList.remove("hide");
                }
            }
        }

        function wait(time) {
            return new Promise(resolve => {
                setTimeout(() => {
                    resolve();
                }, time);
            });
        }
    </script>
</body>

</html>