<!DOCTYPE html>
<html lang="en">
<?php
$sql = "SELECT *,
            (
                (
                    SELECT COUNT(*) 
                    FROM tb_exercise 
                    WHERE ClassID = e.ClassID
                    AND ExerciseStart<? 
                    AND ExerciseEnd>?
                ) - (
                    SELECT COUNT(*) 
                    FROM tb_exercisesend 
                    INNER JOIN tb_exercise
                    ON tb_exercisesend.ExerciseID=tb_exercise.ExerciseID
                    WHERE tb_exercisesend.ExerciseID 
                    IN(
                        SELECT tb_exercisesend.ExerciseID FROM tb_exercise WHERE ClassID=e.ClassID
                    ) 
                    AND UserID=e.UserID
                    AND ExerciseStart<? 
                    AND ExerciseEnd>?
                )
            ) AS Stack
        FROM tb_enroll e 
        INNER JOIN tb_class c 
        ON e.ClassID=c.ClassID 
        WHERE e.UserID=? 
        ORDER BY Stack DESC,Day,ClassBegin";
$stmt = $mysqli->prepare($sql);
$today = date("Y-m-d H:i:s");
$stmt->bind_param("ssssi", $today, $today, $today, $today, $_SESSION['UserID']);
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
            <div class="col-12 bg-white p-3 mt-3 shadow-sm mx-auto" style="max-width:550px">
                <h4 class="col-sm-12 border-bottom border-2 pb-2 mb-2">เลือกวิชา</h4>
                <table class="table table-hover align-middle table-borderless">
                    <thead>
                        <tr>
                            <th>รหัส</th>
                            <th>ชื่อวิชา</th>
                            <th>วัน-เวลา</th>
                            <th>เมนู</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($classes as $c) :
                            $begin = date("H:i", strtotime($c['ClassBegin']));
                            $end = date("H:i", strtotime($c['ClassEnd']));
                        ?>
                            <tr>
                                <td><?= str_pad($c['ClassID'], 3, "0", STR_PAD_LEFT) ?></td>
                                <td><?= $c['ClassTitle'] ?></td>
                                <td><?= $c['Day'] . "(" . $begin . "-" . $end . ")" ?>
                                <td>
                                    <a href="./all.php?classid=<?= $c['ClassID'] ?>" class="btn btn-warning position-relative">
                                        <i class="fa fa-eye me-2" aria-hidden="true"></i>ดู
                                        <?php
                                        if ($c['Stack']) :
                                        ?>
                                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                                <?= $c['Stack'] ?>
                                                <span class="visually-hidden">การบ้านทั้งหมด</span>
                                            </span>
                                        <?php
                                        endif;
                                        ?>
                                    </a>
                                </td>
                            </tr>
                        <?php
                        endforeach;
                        ?>
                    </tbody>
                </table>
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
</body>

</html>