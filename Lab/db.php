<?php
$host = '127.0.0.1';
$port = 3307;
$user = 'root';
$pass = '';
$dbname = 'threadwear_coursework_v4';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conn = new mysqli($host, $user, $pass, $dbname, $port);
$conn->set_charset('utf8mb4');
?>
