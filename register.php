<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  
    $full_name = $_POST['fullname'];
    $username  = $_POST['username'];
    $password  = $_POST['password'];
    $email     = $_POST['email'];
    $phone     = $_POST['phone'];

   
    $sql = "
        INSERT INTO users
        (full_name, username, password, role, created_at, email, phone)
        VALUES
        ('$full_name', '$username', '$password', 'member', NOW(), '$email', '$phone')
    ";

    $conn->query($sql);

    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>สมัครสมาชิก</title>
<style>
*{
    box-sizing:border-box;
    font-family:"Segoe UI", Arial, sans-serif;
}
body{
    margin:0;
    height:100vh;
    background:#eef3f7;
    display:flex;
    justify-content:center;
    align-items:center;
}
.form{
    width:380px;
    background:#ffffff;
    padding:30px;
    border-radius:12px;
    box-shadow:0 8px 20px rgba(0,0,0,.08);
}
.form h3{
    text-align:center;
    margin-bottom:25px;
    color:#27ae60;
    font-weight:600;
}
.input{
    width:100%;
    padding:12px;
    margin-bottom:16px;
    border:1px solid #ccd6dd;
    border-radius:8px;
    font-size:14px;
}
.input:focus{
    outline:none;
    border-color:#27ae60;
}
.btn{
    width:100%;
    padding:12px;
    background:linear-gradient(135deg,#27ae60,#2f80ed);
    color:#fff;
    border:none;
    border-radius:8px;
    font-size:15px;
    cursor:pointer;
}
.btn:hover{
    opacity:.9;
}
.link{
    margin-top:18px;
    text-align:center;
    font-size:14px;
}
.link a{
    color:#2f80ed;
    text-decoration:none;
}
.link a:hover{
    text-decoration:underline;
}
</style>
</head>
<body>

<div class="form">
    <h3>สมัครสมาชิก</h3>
    <form method="post">
        <input class="input" name="fullname" placeholder="ชื่อ - นามสกุล" required>
        <input class="input" type="email" name="email" placeholder="อีเมล" required>
        <input class="input" name="phone" placeholder="เบอร์โทรศัพท์" required>
        <input class="input" name="username" placeholder="ชื่อผู้ใช้" required>
        <input class="input" type="password" name="password" placeholder="รหัสผ่าน" required>

        <button class="btn" type="submit">สมัครสมาชิก</button>
    </form>

    <div class="link">
        มีบัญชีแล้ว? <a href="login.php">เข้าสู่ระบบ</a>
    </div>
</div>

</body>
</html>
