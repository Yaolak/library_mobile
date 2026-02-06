<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>



<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">

<!-- Google Font : Sarabun -->
<link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
*{
    box-sizing:border-box;
    font-family:'Sarabun', sans-serif;
}

/* Navbar layout */
.navbar{
    background:linear-gradient(135deg,#FF0033,#b48a94);
    padding:14px 30px;
    display:grid;
    grid-template-columns: 1fr auto 1fr;
    align-items:center;
    box-shadow:0 6px 18px rgba(0,0,0,.2);
}

/* Left */
.nav-left a{
    color:#fff;
    text-decoration:none;
    font-size:18px;
    font-weight:600;
    letter-spacing:0.3px;
}

/* Center menu */
.nav-center{
    display:flex;
    justify-content:center;
    gap:26px;
}
.nav-center a{
    color:#fff7f9;
    text-decoration:none;
    font-size:15px;
    font-weight:500;
    letter-spacing:0.2px;
    padding-bottom:4px;
    opacity:.9;
}
.nav-center a:hover{
    opacity:1;
    border-bottom:2px solid #f4ff20;
}

/* Right */
.nav-right{
    text-align:right;
    color:#f3e6e9;
    font-size:14px;
    font-weight:500;
}
.nav-right a{
    color:#f3e6e9;
    text-decoration:none;
    margin-left:8px;
}
.nav-right a:hover{
    text-decoration:underline;
}
</style>

</head>
<body>

<div class="navbar">

    <!-- ซ้าย -->
    <div class="nav-left">
        <?php if ($_SESSION['role'] === 'member') { ?>
            <a href="../user/borrow_book.php">ระบบยืมคืนหนังสือ</a>
        <?php } else { ?>
            <a href="../admin/index.php">ระบบยืมคืนหนังสือ</a>
        <?php } ?>
    </div>

    <!-- กลาง -->
    <div class="nav-center">
        <?php if ($_SESSION['role'] === 'member') { ?>
           
           
            <a href="../user/borrow_book.php">ยืมหนังสือ</a>
            <a href="../user/return_book.php">คืนหนังสือ</a>
            <a href="../user/history.php">ประวัติการคืนและการยืม</a>
        <?php } else { ?>
            <a href="../admin/index.php">หน้าแรก</a>
            <a href="../admin/members.php">สมาชิก</a>
            <a href="../admin/add_book.php">เพิ่มหนังสือ</a>
      
            <a href="../admin/borrowed_books.php">หนังสือถูกยืม</a>
        <?php } ?>
    </div>

    <!-- ขวา -->
    <div class="nav-right">
        <?= $_SESSION['full_name'] ?> |
        <a href="../logout.php">ออกจากระบบ</a>
    </div>

</div>

</body>
</html>
