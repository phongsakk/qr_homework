<!DOCTYPE html>
<html lang="en">
<?php

session_start();
if (empty($_SESSION['UserID'])) header('Location:../login.php');
if (empty($_GET['classid'])) header("Location:./");

include("../sys_db_connect.php");
if (isset($_POST['submit'])) {
    $stmt = $mysqli->prepare("INSERT INTO tb_exercise(ExerciseTitle,ClassID,ExerciseStart,ExerciseEnd)VALUES(?,?,?,?)");
    $stmt->bind_param('siss', $_POST['title'], $_GET['classid'], $_POST['start'], $_POST['end']);
    if ($stmt->execute()) header("Location:./man.php?classid={$GET['classid']}");
    exit("Error: cannot save!");
}
$stmt = $mysqli->prepare("SELECT *,('3') AS TotalWork FROM tb_class WHERE ClassID=?");
$stmt->bind_param("i", $_GET['classid']);
$stmt->execute();
$class = $stmt->get_result()->fetch_assoc();
$stmt->close();
$begin = date("H:i", strtotime($class['ClassBegin']));
$end = date("H:i", strtotime($class['ClassEnd']));
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
        .quill-editor {
            min-height: calc(5.75rem + 0.9px);
            border-bottom-left-radius: .25rem;
            border-bottom-right-radius: .25rem;
        }

        .ql-editor {
            font-family: "Sarabun";
            font-size: 16px;
            min-height: calc(5.75rem + 0.9px);
        }

        .ql-toolbar.ql-snow {
            border-top-left-radius: .25rem;
            border-top-right-radius: .25rem;
        }

        .ql-toolbar.ql-snow .ql-formats {
            margin-inline: 0;
            padding-inline: .25rem;
            border-left: 2px solid rgba(0, 0, 0, .1);
        }

        .ql-toolbar.ql-snow .ql-formats:first-of-type {
            border-left: 0;
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
            <h2 class="col-12 text-center">การบ้านใหม่</h2>
            <div class="col-12 bg-white p-3 mt-3 shadow-sm mx-auto" style="max-width:800px">
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
                </table>
                <form id="richTextForm" method="post">
                    <div class="row mb-2">
                        <label for="start" class="col-form-label col-sm-3">เวลาเริ่ม</label>
                        <div class="col-sm-9">
                            <input type="datetime-local" name="start" class="form-control" value="<?= date("Y-m-d", time()) . "T" . date("H:i", time()) ?>">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="end" class="col-form-label col-sm-3">เวลาปิดรับ</label>
                        <div class="col-sm-9">
                            <input type="datetime-local" name="end" class="form-control" value="<?= date("Y-m-d", time()) . "T" . date("H:i", time()) ?>">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="rich" class="col-form-label col-sm-3">คำอธิบาย</label>
                        <div class="col-sm-9">
                            <div>
                                <div name="rich" class="quill-editor">
                                </div>
                            </div>
                            <input id="richHidden" type="hidden" name="title">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-12 text-end">
                            <button type="submit" class="btn btn-primary" name="submit">
                                <i class="fa fa-laptop-house me-1"></i>สร้าง
                            </button>
                        </div>
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
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

    <script>
        var toolbarOptions = [
            [{
                'header': [1, 2, 3, false]
            }],
            ['bold', 'italic', 'underline', 'strike', {
                'color': []
            }],
            [{
                'list': 'ordered'
            }, {
                'list': 'bullet'
            }],
            [{
                'script': 'sub'
            }, {
                'script': 'super'
            }],
            [{
                align: ''
            }, {
                align: 'center'
            }, {
                align: 'right'
            }, {
                align: 'justify'
            }, ],
            ['image']
        ];

        var quill = new Quill('.quill-editor', {
            theme: 'snow',
            modules: {
                toolbar: toolbarOptions
            },
            placeholder: 'เขียนคำอธิบาย...',
        });

        quill.on('text-change', () => {
            document.getElementById('richHidden').value = quill.root.innerHTML
        })
    </script>
</body>

</html>