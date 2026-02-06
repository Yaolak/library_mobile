<?php
require_once '../db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$success = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title  = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');

    /* ตรวจช่องว่างก่อน */
    if ($title === '' || $author === '') {
        $error = "กรุณากรอกชื่อหนังสือและผู้แต่ง";
    }

    /* ---------- รูป ---------- */
    $imageName = NULL;

    if (!$error && !empty($_FILES['cover_image']['name'])) {

        $ext = strtolower(pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, ['jpg','jpeg','png'])) {
            $error = "รองรับเฉพาะไฟล์ JPG / PNG เท่านั้น";
        } else {
            $imageName = time().'_'.$ext;
            move_uploaded_file(
                $_FILES['cover_image']['tmp_name'],
                "../uploads/books/".$imageName
            );
        }
    }

    /* ---------- บันทึก ---------- */
    if (!$error) {

        $stmt = $conn->prepare("
            INSERT INTO books (title, author, cover_image, status)
            VALUES (?, ?, ?, 'available')
        ");
        $stmt->bind_param("sss", $title, $author, $imageName);

        if ($stmt->execute()) {
            $success = "เพิ่มหนังสือเรียบร้อยแล้ว";
        } else {
            $error = "บันทึกข้อมูลไม่สำเร็จ";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>เพิ่มหนังสือ</title>

<link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
*{ box-sizing:border-box; font-family:'Sarabun',sans-serif; }
body{ margin:0; background:#f4f6f8; }
.container{ max-width:720px; margin:40px auto; }
.card{
    background:#fff;
    padding:30px;
    border-radius:14px;
    box-shadow:0 6px 18px rgba(0,0,0,.08);
}
h2{ color:#2f80ed; margin-top:0; }
.form-group{ margin-bottom:18px; }
label{ font-weight:500; display:block; margin-bottom:6px; }
input{
    width:100%;
    padding:12px;
    border-radius:8px;
    border:1px solid #ccd6dd;
}
.btn{
    width:100%;
    padding:12px;
    background:linear-gradient(135deg,#2f80ed,#27ae60);
    color:#fff;
    border:none;
    border-radius:10px;
    font-size:15px;
    cursor:pointer;
}
.alert{ padding:12px; border-radius:8px; margin-bottom:15px; }
.success{ background:#eafaf1; color:#27ae60; }
.error{ background:#fdecec; color:#c0392b; }
</style>
</head>
<body>

<?php include '../navbar.php'; ?>

<div class="container">
<div class="card">

<h2>📘 เพิ่มหนังสือใหม่</h2>

<?php if ($success): ?><div class="alert success"><?= $success ?></div><?php endif; ?>
<?php if ($error): ?><div class="alert error"><?= $error ?></div><?php endif; ?>

<form method="post" enctype="multipart/form-data">

    <div class="form-group">
        <label>ชื่อหนังสือ</label>
        <input type="text" name="title" required>
    </div>

    <div class="form-group">
        <label>ผู้แต่ง</label>
        <input type="text" name="author" required>
    </div>

    <div class="form-group">
        <label>รูปปกหนังสือ</label>
        <input type="file" name="cover_image" accept="image/*">
    </div>

    <button class="btn">บันทึกหนังสือ</button>

</form>

</div>
</div>

</body>
</html>
