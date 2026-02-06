<?php
session_start();

$conn = new mysqli("localhost", "root", "", "library_mobile");
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("เชื่อมต่อฐานข้อมูลไม่สำเร็จ");
}
?>
