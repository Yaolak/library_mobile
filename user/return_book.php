<?php
require_once '../db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'member') {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$success = "";
$error   = "";

/* ===== คืนหนังสือ ===== */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['return'])) {

    $borrow_id = intval($_POST['borrow_id']);
    $book_id   = intval($_POST['book_id']);

    $update = $conn->query("
        UPDATE borrows
        SET return_date = NOW(), status = 'returned'
        WHERE borrow_id = $borrow_id AND user_id = $user_id
    ");

    if ($update) {
        $conn->query("
            UPDATE books
            SET status = 'available'
            WHERE book_id = $book_id
        ");
        $success = "✅ คืนหนังสือเรียบร้อยแล้ว";
    } else {
        $error = "❌ ไม่สามารถคืนหนังสือได้";
    }
}

/* ===== ดึงหนังสือที่กำลังยืม ===== */
$borrows = $conn->query("
    SELECT 
        bo.borrow_id,
        bo.book_id,
        bo.due_date,
        b.title,
        b.author,
        b.cover_image
    FROM borrows bo
    JOIN books b ON bo.book_id = b.book_id
    WHERE bo.user_id = $user_id
      AND bo.status = 'borrowing'
    ORDER BY bo.borrow_date DESC
");
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>คืนหนังสือ</title>

<link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
*{
    box-sizing:border-box;
    font-family:'Sarabun', sans-serif;
}
body{
    margin:0;
    background:#f4f7fb;
}
.container{
    padding:40px;
}
h2{
    color:#1f3a5f;
    margin-bottom:20px;
}

/* Alert */
.alert-success{
    background:#eafaf1;
    color:#27ae60;
    padding:12px;
    border-radius:10px;
    margin-bottom:15px;
}
.alert-error{
    background:#fdecec;
    color:#c0392b;
    padding:12px;
    border-radius:10px;
    margin-bottom:15px;
}

/* Grid */
.book-grid{
    display:grid;
    grid-template-columns:repeat(auto-fill, minmax(220px,1fr));
    gap:24px;
}

/* Card */
.book-card{
    background:#fff;
    border-radius:16px;
    overflow:hidden;
    box-shadow:0 6px 18px rgba(31,58,95,.12);
    transition:.25s;
}
.book-card:hover{
    transform:translateY(-6px);
}

/* Cover */
.book-cover{
    height:260px;
    background:#e3ebf3;
}
.book-cover img{
    width:100%;
    height:100%;
    object-fit:cover;
}

/* Info */
.book-info{
    padding:18px;
}
.book-title{
    font-size:16px;
    font-weight:600;
    color:#1f3a5f;
}
.book-author{
    font-size:14px;
    color:#666;
    margin-bottom:8px;
}
.book-due{
    font-size:13px;
    color:#e67e22;
    margin-bottom:12px;
}

/* Button */
.btn{
    width:100%;
    padding:10px;
    border-radius:8px;
    font-size:14px;
    border:none;
    cursor:pointer;
}
.btn-return{
    background:#27ae60;
    color:#fff;
}
</style>
</head>
<body>

<?php include '../navbar.php'; ?>

<div class="container">

<h2>📦 คืนหนังสือ</h2>

<?php if ($success): ?><div class="alert-success"><?= $success ?></div><?php endif; ?>
<?php if ($error): ?><div class="alert-error"><?= $error ?></div><?php endif; ?>

<div class="book-grid">

<?php if ($borrows->num_rows == 0): ?>
    <p>ไม่มีหนังสือที่กำลังยืมอยู่</p>
<?php endif; ?>

<?php while($b = $borrows->fetch_assoc()): ?>
<div class="book-card">

    <div class="book-cover">
        <?php if ($b['cover_image']): ?>
            <img src="../uploads/books/<?= $b['cover_image'] ?>">
        <?php else: ?>
            <img src="../uploads/no-book.png">
        <?php endif; ?>
    </div>

    <div class="book-info">
        <div class="book-title"><?= htmlspecialchars($b['title']) ?></div>
        <div class="book-author">ผู้แต่ง: <?= htmlspecialchars($b['author']) ?></div>
        <div class="book-due">กำหนดคืน: <?= $b['due_date'] ?></div>

        <form method="post">
            <input type="hidden" name="borrow_id" value="<?= $b['borrow_id'] ?>">
            <input type="hidden" name="book_id" value="<?= $b['book_id'] ?>">
            <button class="btn btn-return" name="return">
                คืนหนังสือ
            </button>
        </form>
    </div>

</div>
<?php endwhile; ?>

</div>
</div>

</body>
</html>
