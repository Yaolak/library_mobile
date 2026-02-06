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


$history = $conn->query("
    SELECT 
        bo.borrow_date,
        bo.due_date,
        bo.return_date,
        bo.status,
        b.title,
        b.author,
        b.cover_image
    FROM borrows bo
    JOIN books b ON bo.book_id = b.book_id
    WHERE bo.user_id = $user_id
    ORDER BY bo.borrow_date DESC
");
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>ประวัติการยืม-คืน</title>

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


.book-grid{
    display:grid;
    grid-template-columns:repeat(auto-fill, minmax(240px,1fr));
    gap:24px;
}


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


.book-cover{
    height:260px;
    background:#e3ebf3;
}
.book-cover img{
    width:100%;
    height:100%;
    object-fit:cover;
}


.book-info{
    padding:18px;
}
.book-title{
    font-size:16px;
    font-weight:600;
    color:#1f3a5f;
    margin-bottom:4px;
}
.book-author{
    font-size:14px;
    color:#666;
    margin-bottom:8px;
}
.book-date{
    font-size:13px;
    color:#555;
    margin-bottom:4px;
}


.status{
    display:inline-block;
    margin-top:10px;
    padding:6px 14px;
    border-radius:20px;
    font-size:13px;
}
.borrowing{
    background:#fdecec;
    color:#c0392b;
}
.returned{
    background:#eafaf1;
    color:#27ae60;
}
</style>
</head>
<body>

<?php include '../navbar.php'; ?>

<div class="container">

<h2>ประวัติการยืมและคืนหนังสือ</h2>

<?php if ($history->num_rows == 0): ?>
    <p>ยังไม่มีประวัติการยืมหนังสือ</p>
<?php endif; ?>

<div class="book-grid">

<?php while($h = $history->fetch_assoc()): ?>
<div class="book-card">

    <div class="book-cover">
        <?php if ($h['cover_image']): ?>
            <img src="../uploads/books/<?= $h['cover_image'] ?>">
        <?php else: ?>
            <img src="../uploads/no-book.png">
        <?php endif; ?>
    </div>

    <div class="book-info">
        <div class="book-title"><?= htmlspecialchars($h['title']) ?></div>
        <div class="book-author">ผู้แต่ง: <?= htmlspecialchars($h['author']) ?></div>

        <div class="book-date"> วันที่ยืม: <?= $h['borrow_date'] ?></div>
        <div class="book-date"> กำหนดคืน: <?= $h['due_date'] ?></div>

        <?php if ($h['status'] === 'returned'): ?>
            <div class="book-date"> วันที่คืน: <?= $h['return_date'] ?></div>
            <span class="status returned">คืนแล้ว</span>
        <?php else: ?>
            <span class="status borrowing">กำลังยืม</span>
        <?php endif; ?>
    </div>

</div>
<?php endwhile; ?>

</div>
</div>

</body>
</html>
