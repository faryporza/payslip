<?php
session_start();
include 'db.php';

// Logout
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    header('location: index.php');
}

// Fetch all users
$query = "SELECT * FROM users";
$result = mysqli_query($db, $query);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Status</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai&display=swap');

        body {
            font-family: 'IBM Plex Sans Thai', sans-serif;
            background-color: #f1f1f1;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .container h2 {
            margin-top: 0;
        }
        .container table {
            width: 100%;
            border-collapse: collapse;
        }
        .container table th,
        .container table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        .container table th {
            background-color: #f2f2f2;
            text-align: left;
        }
        .container .success {
            color: #0a0;
            margin-bottom: 10px;
        }
        .container .error {
            color: #f00;
            margin-bottom: 10px;
        }
        .container .delete-msg {
            color: #f00;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if (isset($_SESSION['username'])) { ?>
            <h2>สาหวัดดี, <?php echo $_SESSION['username']; ?>!</h2>
            <a href="upload.php" class="btn btn-warning">กลับสู่หน้าหลัก</a>
            <a href="logout.php" class="btn btn-danger"
            >ออกจากระบบ</a><br><br>
            <div class="alert alert-info alert-dismissible">
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    <strong>หมายเหตุ!</strong> เช็คสถานะการโอนเงินของตนเอง ถ้าขึ้น"รอการตรวจสอบ ⭕"สามารถออกจากระบบได้เลย.
  </div>
        <?php } else { ?>
            <h2>สวัสดี, ผู้ใช้ที่ไม่ระบุชื่อ!</h2>
            <a href="index.php" class="btn btn-warning">กลับสู่หน้าล็อกอิน</a>
        <?php } ?>
        
        <hr>
        <h3>สถานะการจ่ายเงิน</h3>
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>สถานะ</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

    
    </div>
</body>
</html>
