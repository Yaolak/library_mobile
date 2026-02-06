<?php
require_once '../db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ป้องกันคนไม่ใช่ admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// ดึงข้อมูลสมาชิก
$members = $conn->query("
    SELECT user_id, full_name, username, email, phone, created_at 
    FROM users 
    WHERE role = 'member'
    ORDER BY user_id DESC
");
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>จัดการสมาชิก | ผู้ดูแลระบบ</title>

<link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
*{
    box-sizing:border-box;
    font-family:'Sarabun', sans-serif;
}
body{
    margin:0;
    background:#f4f6f8;
}
.container{
    padding:40px;
}
h2{
    color:#2f80ed;
    margin-bottom:20px;
}
.table-box{
    background:#fff;
    padding:25px;
    border-radius:12px;
    box-shadow:0 6px 18px rgba(0,0,0,.08);
}
table{
    width:100%;
    border-collapse:collapse;
}
th, td{
    padding:12px;
    border-bottom:1px solid #eee;
    font-size:14px;
    text-align:center;
}
th{
    background:#f4f7fb;
    color:#333;
}
tr:hover{
    background:#fafafa;
}
.empty{
    text-align:center;
    padding:20px;
    color:#777;
}
</style>
</head>
<body>

<!-- Navbar -->
<?php include '../navbar.php'; ?>

<div class="container">

    <h2>👤 รายชื่อสมาชิก</h2>

    <div class="table-box">
        <table>
            <tr>
                <th>รหัส</th>
                <th>ชื่อ-นามสกุล</th>
                <th>ชื่อผู้ใช้</th>
                <th>อีเมล</th>
                <th>เบอร์โทร</th>
                <th>วันที่สมัคร</th>
            </tr>

            <?php if ($members->num_rows > 0): ?>
                <?php while($m = $members->fetch_assoc()): ?>
                <tr>
                    <td><?= $m['user_id'] ?></td>
                    <td><?= $m['full_name'] ?></td>
                    <td><?= $m['username'] ?></td>
                    <td><?= $m['email'] ?></td>
                    <td><?= $m['phone'] ?></td>
                    <td><?= date('d/m/Y', strtotime($m['created_at'])) ?></td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="empty">ไม่มีข้อมูลสมาชิก</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>

</div>

</body>
</html>
