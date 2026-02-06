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

/* ===== บันทึกการยืม ===== */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['borrow'])) {

    $book_id  = intval($_POST['book_id']);
    $due_date = $_POST['due_date'];

    $insert = $conn->query("
        INSERT INTO borrows (user_id, book_id, borrow_date, due_date, status)
        VALUES ($user_id, $book_id, CURDATE(), '$due_date', 'borrowing')
    ");

    if ($insert) {
        $conn->query("
            UPDATE books 
            SET status = 'unavailable'
            WHERE book_id = $book_id
        ");
        $success = "✅ ยืมหนังสือสำเร็จ";
    } else {
        $error = "❌ ไม่สามารถยืมหนังสือได้";
    }
}

/* ===== ดึงหนังสือ ===== */
$books = $conn->query("SELECT * FROM books ORDER BY book_id DESC");
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>ยืมหนังสือ</title>

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
    margin-bottom:20px;
    color:#1f3a5f;
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
    margin-bottom:10px;
}

/* Status */
.book-status{
    display:inline-block;
    padding:6px 14px;
    border-radius:20px;
    font-size:13px;
    margin-bottom:12px;
}
.available{
    background:#eafaf1;
    color:#27ae60;
}
.borrowed{
    background:#fdecec;
    color:#c0392b;
}

/* Form */
.borrow-form label{
    font-size:13px;
    color:#555;
    display:block;
    margin-bottom:6px;
}
.borrow-form input{
    width:100%;
    padding:8px;
    margin-bottom:10px;
    border-radius:8px;
    border:1px solid #ccc;
}

/* Button */
.btn{
    display:block;
    width:100%;
    padding:10px;
    border-radius:8px;
    font-size:14px;
    border:none;
}
.btn-borrow{
    background:#1f3a5f;
    color:#fff;
    cursor:pointer;
}
.btn-disabled{
    background:#ccc;
    color:#666;
    cursor:not-allowed;
}
</style>
</head>
<body>

<?php include '../navbar.php'; ?>

<div class="container">

<h2>📚 ยืมหนังสือ</h2>

<?php if ($success): ?><div class="alert-success"><?= $success ?></div><?php endif; ?>
<?php if ($error): ?><div class="alert-error"><?= $error ?></div><?php endif; ?>

<div class="book-grid">

<?php while($b = $books->fetch_assoc()): ?>
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

        <?php if ($b['status'] === 'available'): ?>
            <div class="book-status available">พร้อมให้ยืม</div>

            <form method="post" class="borrow-form">
                <input type="hidden" name="book_id" value="<?= $b['book_id'] ?>">

                <label>📅 กรุณากรอกวันคืนหนังสือ</label>
                <input type="date" name="due_date" required>

                <button class="btn btn-borrow" name="borrow">
                    ยืมหนังสือ
                </button>
            </form>

        <?php else: ?>
            <div class="book-status borrowed">ถูกยืมแล้ว</div>
            <div class="btn btn-disabled">ไม่สามารถยืมได้</div>
        <?php endif; ?>
    </div>

</div>
<?php endwhile; ?>

</div>
</div>

</body>
</html>
