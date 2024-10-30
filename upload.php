<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<?php
session_start();
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('location: index.php');
}

$username = $_SESSION['username'];

// Fetch user status
$query = "SELECT status FROM users WHERE username='$username'";
$result = mysqli_query($db, $query);
$status = mysqli_fetch_assoc($result)['status'];

// Handle image upload
if (isset($_POST['upload'])) {
    $image = $_FILES['image']['name'];
    $target = "images/" . basename($image);
    $uploadDate = date('Y-m-d'); // Get current date

    $query = "INSERT INTO images (username, image, upload_date) VALUES ('$username', '$image', '$uploadDate')";
    mysqli_query($db, $query);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $success = "<script>
        $(document).ready(function() {
                    let timerInterval
                    Swal.fire({
                    title: 'Auto close alert!',
                    html: 'I will close in <b></b> milliseconds.',
                    timerProgressBar: true,
                    }).then((result) => {
                    /* Read more about handling dismissals below */
                    if (result.dismiss === Swal.DismissReason.timer) {
                        console.log('I was closed by the timer')
                    }
                    })   
        });             
        </script>";

        // Update user status to "pending review"
        $updateStatusQuery = "UPDATE users SET status='รอการตรวจสอบ ⭕' WHERE username='$username'";
        mysqli_query($db, $updateStatusQuery);
           // Redirect to status.php after successful upload and update
           header('Location: alert.php');
           exit(); // Make sure to exit after the redirect to prevent further execution
    } else {
        $error = "<script>
            $(document).ready(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'มีบางอย่างผิดพลาด โปรดส่งสลิปใหม่อีกครั้ง!',
                })     
            });            

            </script> ";
    }
}

// Check if upload date is empty
 $isEmptyDate = empty($uploadDate);

// Fetch all users
$query = "SELECT * FROM users";
$result = mysqli_query($db, $query);


// Check if upload date is empty
 $isEmptyDate = empty($uploadDate);

// Fetch all users
$query = "SELECT * FROM users";
$result = mysqli_query($db, $query);

//deta Status
$imageQuery = "SELECT * FROM images";
$imageResult = mysqli_query($db, $imageQuery);

    while ($imageRow = mysqli_fetch_assoc($imageResult)) {
        $uploadDate = $imageRow['upload_date'];
    }

?>

<!DOCTYPE html>
<html>
<head>
    <title>User</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script
      src="https://code.jquery.com/jquery-3.4.1.min.js"
      integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
      crossorigin="anonymous"
    ></script>
    <link
      href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"
      rel="stylesheet"
      type="text/css"
    />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai&display=swap');

        body {
            font-family: 'IBM Plex Sans Thai', sans-serif;
            background-color: #f1f1f1;
            padding: 20px;
        }
        .container {
            max-width: 613px;
            margin: 0 auto;
            background-color: #fff;
            padding: 75px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .container h2 {
            margin-top: 0;
        }
        .container h3 {
            margin-top: 20px;
        }
        .container form button:hover {
            background-color: #45a049;
        }
        .container .success {
            color: #0a0;
            margin-bottom: 10px;
        }
        .container .error {
            color: #f00;
            margin-bottom: 10px;
        }
        .button:disabled {
            opacity: 0.5;
        }
        .hide {
            display: none;
        }
        .loading-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.7);
        z-index: 9999;
        justify-content: center;
        align-items: center;
    }

    .loading-spinner {
        border: 8px solid #f3f3f3;
        border-top: 8px solid #3498db;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }
    /* Styling for the popup */
    .popup {
      display: none;
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background: #fff;
      padding: 20px;
      border: 2px solid #ccc;
      z-index: 9999;
    }
    
    /* Styling for the overlay */
    .overlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.7);
      z-index: 9998;
    }
    
    /* Styling for the close button */
    .close {
      position: absolute;
      top: 10px;
      right: 10px;
      cursor: pointer;
    }
    </style>
</head>
<body>
    <div class="container">
        <?php
            // Check if the user is an admin
            if ($status === 'admin') {
                echo '<a href="admin.php" class="btn btn-primary">เช็คสลิป</a> ';
                echo '<a href="logouttorg.php" class="btn btn-warning">สมัครสมาชิคให้ผู้ใช้</a> <hr>';
                
            }
        ?>
        <h2>สาหวัดดี, <?php echo $username; ?>!</h2>
        <h3>สถานะ: <?php echo $status; ?></h3>
        <p>สถานะจะเปลี่ยนเมื่อตรวจสอบแล้ว</p>
        <br><a href="status.php">เช็คสถานะการโอนเงินทั้งหมด</a>
        <hr>
        <h3><b>อัปโหลดสลิป</b></h3>
        <p>กดส่งแล้วกรุณารออ</p>
        <?php if (isset($success)) { ?>
            <div class="success"><?php echo $success; ?></div>
        <?php } ?>
        <?php if (isset($error)) { ?>
            <div class="error"><?php echo $error; ?></div>
        <?php } ?>
        <form id="myForm" method="post" action="upload.php" enctype="multipart/form-data">
            <div class="form-group">
                <input type="file" name="image" class="form-control" required>
            </div><br>
          <button type="submit" name="upload" class="btn btn-success" >
          <i class="loading-icon fa fa-spinner fa-spin hide"></i>
            <span class="btn-txt">ส่งสลิปการโอนเงิน</span></button>


    <div class="result"></div>
    <div class="loading-overlay" id="loadingOverlay">
    <div class="loading-spinner"></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelector('#myForm').addEventListener('submit', function (event) {
                document.querySelector('#loadingOverlay').style.display = 'flex';
            });
        });
    </script>

        
    
         <br><br>
         <a href="bank.html">ช่องทางการโอนเงิน</a>
         <p></p>
         <a href="pass.php">รหัสเน็ตฟิค</a>
            
        </form>
        
        <hr>
        <a href="logout.php" class="btn btn-danger"
            >ออกจากระบบ</a>
    </div>
</body>
</html>
