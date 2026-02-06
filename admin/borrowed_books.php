<?php
require_once '../db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>รายการหนังสือที่ถูกยืม</title>

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

/* Table */
table{
    width:100%;
    border-collapse:collapse;
    background:#fff;
    border-radius:14px;
    overflow:hidden;
    box-shadow:0 6px 18px rgba(31,58,95,.12);
}
th, td{
    padding:14px;
    text-align:left;
}
th{
    background:#1f3a5f;
    color:#fff;
}
tr:nth-child(even){
    background:#f4f7fb;
}
.cover{
    width:60px;
}
.cover img{
    width:100%;
    border-radius:6px;
}
.status{
    color:#e67e22;
    font-weight:500;
}
</style>
</head>
<body>

<?php include '../navbar.php'; ?>

<div class="container">
<h2>📖 หนังสือที่กำลังถูกยืม</h2>

<table>
<thead>
<tr>
    <th>ปก</th>
    <th>ชื่อหนังสือ</th>
    <th>ผู้แต่ง</th>
    <th>ผู้ยืม</th>
    <th>วันที่ยืม</th>
    <th>กำหนดคืน</th>
    <th>สถานะ</th>
</tr>
</thead>
<tbody>

<?php
$sql = "
    SELECT 
        borrows.borrow_date,
        borrows.due_date,
        users.full_name AS user_name,
        books.title,
        books.author,
        books.cover_image
    FROM borrows
    JOIN users ON borrows.user_id = users.user_id
    JOIN books ON borrows.book_id = books.book_id
    WHERE borrows.status = 'borrowing'
    ORDER BY borrows.borrow_date DESC
";

$result = $conn->query($sql);

if ($result->num_rows == 0):
?>
<tr>
    <td colspan="7">ไม่มีรายการยืมหนังสือ</td>
</tr>
<?php
endif;

while($row = $result->fetch_assoc()):
?>
<tr>
    <td class="cover">
        <?php if ($row['cover_image']): ?>
            <img src="../uploads/books/<?= $row['cover_image'] ?>">
        <?php else: ?>
            <img src="../uploads/no-book.png">
        <?php endif; ?>
    </td>
    <td><?= htmlspecialchars($row['title']) ?></td>
    <td><?= htmlspecialchars($row['author']) ?></td>
    <td><?= htmlspecialchars($row['user_name']) ?></td>
    <td><?= $row['borrow_date'] ?></td>
    <td><?= $row['due_date'] ?></td>
    <td class="status">กำลังยืม</td>
</tr>
<?php endwhile; ?>

</tbody>
</table>
</div>

</body>
</html>
