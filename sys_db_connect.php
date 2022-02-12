<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
date_default_timezone_set("Asia/Bangkok");
try {
    $mysqli = new mysqli("localhost", "root", "", "db_homework");
    $mysqli->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    error_log($e->getMessage());
    exit();
}
