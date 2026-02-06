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

// ดึงข้อมูลสรุป
$totalBooks   = $conn->query("SELECT COUNT(*) AS c FROM books")->fetch_assoc()['c'];
$totalMembers = $conn->query("SELECT COUNT(*) AS c FROM users WHERE role='member'")->fetch_assoc()['c'];
$totalBorrows = $conn->query("SELECT COUNT(*) AS c FROM borrows")->fetch_assoc()['c'];

// ดึงรายการหนังสือ
$books = $conn->query("SELECT * FROM books ORDER BY book_id DESC");
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>แอดมิน | ระบบยืมคืนหนังสือ</title>

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
}
.subtitle{
    color:#555;
    margin-bottom:30px;
}

/* dashboard */
.dashboard{
    display:grid;
    grid-template-columns:repeat(auto-fit, minmax(220px,1fr));
    gap:20px;
    margin-bottom:40px;
}
.card{
    background:#fff;
    padding:25px;
    border-radius:12px;
    box-shadow:0 6px 18px rgba(0,0,0,.08);
}
.card h3{
    margin:0;
    font-size:18px;
}
.card .number{
    font-size:32px;
    font-weight:600;
    margin-top:10px;
}
.card.books .number{ color:#2f80ed; }
.card.members .number{ color:#8e44ad; }
.card.borrows .number{ color:#e67e22; }

/* table */
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
}
th{
    background:#f2f4f8;
}
td img{
    width:60px;
    border-radius:6px;
}
.status-available{
    color:#27ae60;
    font-weight:600;
}
.status-borrowed{
    color:#e74c3c;
    font-weight:600;
}
</style>
</head>
<body>

<?php include '../navbar.php'; ?>

<div class="container">

    <h2>แดชบอร์ดผู้ดูแลระบบ</h2>
    <div class="subtitle">
        ยินดีต้อนรับ <?= htmlspecialchars($_SESSION['full_name']) ?> 👋
    </div>

    <!-- Dashboard -->
    <div class="dashboard">
        <div class="card books">
            <h3>📚 หนังสือทั้งหมด</h3>
            <div class="number"><?= $totalBooks ?></div>
        </div>

        <div class="card members">
            <h3>👤 สมาชิกทั้งหมด</h3>
            <div class="number"><?= $totalMembers ?></div>
        </div>

        <div class="card borrows">
            <h3>🔄 การยืมทั้งหมด</h3>
            <div class="number"><?= $totalBorrows ?></div>
        </div>
    </div>

    <!-- Book list -->
    <div class="table-box">
        <h3>📖 รายการหนังสือที่เพิ่ม</h3>

        <table>
            <thead>
                <tr>
                    <th>ปก</th>
                    <th>ชื่อหนังสือ</th>
                    <th>ผู้แต่ง</th>
                    <th>สถานะ</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($books->num_rows > 0) { ?>
                <?php while($row = $books->fetch_assoc()) { ?>
                <tr>
                    <td>
                        <?php if ($row['cover_image']) { ?>
                            <img src="../uploads/books/<?= $row['cover_image'] ?>">
                        <?php } else { ?>
                            ไม่มีรูป
                        <?php } ?>
                    </td>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= htmlspecialchars($row['author']) ?></td>
                    <td class="status-<?= $row['status'] ?>">
                        <?= $row['status'] === 'available' ? 'ว่าง' : 'ถูกยืม' ?>
                    </td>
                </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="4" style="text-align:center;color:#888;">
                        ยังไม่มีหนังสือ
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>
