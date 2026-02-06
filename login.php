<?php
require_once 'db.php'; 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // ยังไม่ hash รหัสผ่าน
        if ($password === $user['password']) {

            $_SESSION['user_id']   = $user['user_id'];
            $_SESSION['username']  = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['role']      = $user['role'];

            if ($user['role'] === 'admin') {
                header("Location: admin/index.php");
            } else {
                header("Location: user/borrow_book.php");
            }
            exit;

        } else {
            $error = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
        }
    } else {
        $error = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>เข้าสู่ระบบ</title>

<!-- Google Font : Sarabun -->
<link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
*{
    box-sizing:border-box;
    font-family:'Sarabun', sans-serif;
}

body{
    margin:0;
    height:100vh;
    background:#eef3f7;
    display:flex;
    align-items:center;
    justify-content:center;
}

.box{
    width:360px;
    background:#fff;
    padding:30px;
    border-radius:12px;
    box-shadow:0 8px 20px rgba(0,0,0,.08);
}

.box h3{
    text-align:center;
    margin-bottom:25px;
    color:#2f80ed;
    font-weight:600;
}

.input{
    width:100%;
    padding:12px;
    border:1px solid #ccd6dd;
    border-radius:8px;
    margin-bottom:18px;
    font-size:14px;
}

.input:focus{
    outline:none;
    border-color:#2f80ed;
}

.btn{
    width:100%;
    padding:12px;
    background:linear-gradient(135deg,#2f80ed,#27ae60);
    color:#fff;
    border:none;
    border-radius:8px;
    font-size:15px;
    font-weight:500;
    cursor:pointer;
}

.btn:hover{
    opacity:.9;
}

.error{
    background:#fdecec;
    color:#c0392b;
    padding:10px;
    border-radius:8px;
    margin-bottom:18px;
    text-align:center;
    font-size:14px;
}

.link{
    text-align:center;
    margin-top:18px;
    font-size:14px;
}

.link a{
    color:#27ae60;
    text-decoration:none;
    font-weight:500;
}

.link a:hover{
    text-decoration:underline;
}
</style>
</head>

<body>

<div class="box">
    <h3>เข้าสู่ระบบ</h3>

    <?php if ($error): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <form method="post">
        <input class="input" name="username" placeholder="ชื่อผู้ใช้" required>
        <input class="input" type="password" name="password" placeholder="รหัสผ่าน" required>
        <button class="btn">เข้าสู่ระบบ</button>
    </form>

    <div class="link">
        ยังไม่มีบัญชี? <a href="register.php">สมัครสมาชิก</a>
    </div>
</div>

</body>
</html>
