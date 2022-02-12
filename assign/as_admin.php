<!DOCTYPE html>
<html lang="en">
<?php
$sql = "SELECT * FROM tb_class WHERE OwnerID=?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $_SESSION['UserID']);
$stmt->execute();
$classes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
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
            <div class="col-12 bg-white p-3 mt-3 shadow-sm mx-auto" style="max-width:750px">
                <table class="table align-middle table-hover">
                    <thead>
                        <tr>
                            <th colspan="5">
                                <h3 class="h3 col-sm-12 text-center">รายวิชาที่รับผิดชอบ</h3>
                            </th>
                        </tr>
                        <tr>
                            <th>ID</th>
                            <th>ชื่อวิชา</th>
                            <th>กลุ่มเรียน</th>
                            <th>วัน-เวลา</th>
                            <th>เมนู</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include("../sys_db_connect.php");
                        $stmt = $mysqli->prepare("SELECT *, (SELECT COUNT(*) FROM tb_enroll WHERE tb_enroll.ClassID=tb_class.ClassID)AS Registered FROM tb_class WHERE OwnerID=? ORDER BY Day,ClassBegin,Registered DESC");
                        $stmt->bind_param("i", $_SESSION["UserID"]);
                        $stmt->execute();
                        $classes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                        foreach ($classes as $class) {
                            $begin = date("H:i", strtotime($class['ClassBegin']));
                            $end = date("H:i", strtotime($class['ClassEnd']));
                        ?>
                            <tr>
                                <td><?= str_pad($class['ClassID'], 3, "0", false) ?></td>
                                <th><?= $class['ClassTitle'] ?></th>
                                <td><?= $class['ClassDetail'] . " (" . $class['Registered'] . ")" ?></td>
                                <td><?= $class['Day'] . "(" . $begin . "-" . $end . ")" ?></td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-warning dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa fa-clipboard-check me-1" aria-hidden="true"></i> ตัวเลือก
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark">
                                            <li>
                                                <h3 class="dropdown-header">ชั้นเรียน</h3>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="./asg.php?classid=<?= $class['ClassID'] ?>">
                                                    <i class="fa fa-plus me-1" aria-hidden="true"></i> การบ้านใหม่
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="./chk.php?classid=<?= $class['ClassID'] ?>">
                                                    <i class="fa fa-tasks me-1" aria-hidden="true"></i> ตรวจการบ้าน
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="./man.php?classid=<?= $class['ClassID'] ?>">
                                                    <i class="fa fa-users me-1" aria-hidden="true"></i> จัดการผู้เรียน
                                                </a>
                                            </li>
                                            <li>
                                                <hr class="dropdown-division">
                                            </li>
                                            <li>
                                                <h3 class="dropdown-header">การตั้งค่า</h3>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="./edi.php?classid=<?= $class['ClassID'] ?>">
                                                    <i class="fa fa-edit me-1" aria-hidden="true"></i> แก้ไขข้อมูล
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="./del.php?classid=<?= $class['ClassID'] ?>" onclick="return confirm('ต้องการลบวิชาหรือไม่?')">
                                                    <i class="fa fa-trash me-1" aria-hidden="true"></i> ลบรายวิชา
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5">
                                <div class="row">
                                    <div class="col-sm-12 text-center">
                                        <a href="./new.php" class="btn btn-info">
                                            <i class="fa fa-plus-circle me-1" aria-hidden="true"></i>เพิ่มชั้นเรียน
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="col-12 mt-3 mx-auto text-end" style="max-width:750px">
                <a href="../dashboard.php" class="btn btn-secondary">
                    <i class="fa fa-tachometer-alt me-1" aria-hidden="true"></i> ไปที่แผงควบคุม
                </a>
            </div>
        </div>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.0-beta1/js/bootstrap.bundle.min.js"></script>
</body>

</html>