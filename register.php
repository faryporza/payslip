<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<?php
session_start();
include 'db.php';

// Register
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if username already exists
    $query = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($db, $query);
    if (mysqli_num_rows($result) > 0) {
        echo "<script>
        $(document).ready(function() {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'เกิดข้อผิดพลาด Username อาจซ้ำกับคนอื่นคุบบ!',
            })     
        });            

        </script>"; 
    } else {
        $query = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
        mysqli_query($db, $query);
        $_SESSION['username'] = $username;
        echo "<script>
        $(document).ready(function() {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'สมัครสมาชิกสำเร็จ',
                                    showConfirmButton: false,
                                    timer: 2000
                                  })     
        });             
        </script>";
        header('refresh:1; url=upload.php');
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Login/Register</title>
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
        .container form .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .container form .form-group input[type="text"],
        .container form .form-group input[type="password"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 3px;
            margin-bottom: 10px;
        }
        .container .error {
            color: #f00;
            margin-bottom: 30px;
        }
        body {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
        padding: 0;
        }
        .Button {
        font-family: "Segoe UI", sans-serif;
        font-weight: 600;
        line-height: 130%;
        letter-spacing: -0.02em;
        color: #444638;
        font-size: 18px;
        position: relative;
        border-radius: 80em;
        background-color: #11190c;
        border: 0.125rem solid #11190c;
        text-align: center;
        text-decoration: none;
        transition: background-color 0.2s ease, border-color 0.2s ease,
            color 0.2s ease, fill 0.2s ease, transform 0.2s ease-in-out;
        cursor: pointer;
        overflow: hidden;
        will-change: transform;
        padding: 5px 11px;
        display: inline-block;
        margin-top: clamp(1px, 5.3333333333vw, 1.96px);
        }

        .Button:hover {
        background-color: #e6ff00;
        border-color: #e6ff00;
        transition: background-color 0.2s ease, border-color 0.2s ease,
            color 0.2s ease, fill 0.2s ease, transform 0.2s ease-in-out;
        transform: scale(1.07);
        will-change: transform;
        color: #11190c;
        }

        .Button:after {
        content: "";
        display: block;
        height: 100%;
        width: 100%;
        position: absolute;
        left: 0;
        top: 0;
        transform: translate(-100%) rotate(10deg);
        transform-origin: top left;
        transition: background-color 0.2s ease, border-color 0.2s ease,
            color 0.2s ease, fill 0.2s ease, transform 0.2s ease-in-out;
        will-change: transform;
        z-index: -1;
        border-radius: 80em;
        background-color: #e6ff00;
        }

        .Button:hover:after {
        transform: translate(0);
        z-index: 0;
        }

        .Button > * {
        position: relative;
        z-index: 1;
        }

        .Button__inner {
        color: #e6ff00;
        }

        .Button:hover .Button__inner {
        color: #11190c;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Register</h2>
            <form method="post" action="register.php">
                <div class="form-group">
                    <label>Username:</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Password:</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" name="register" class="Button">
                    <span class="Button__inner"> สมัครสมาชิก </span>
                </button>

            </form>
        <p>ถ้ามี Username <a href="index.php">เข้าสู่ระบบ กดตรงนี้</a> 
        <hr><a href="status.php">เช็คสถานะทั้งหมด</a>
    </div>
</body>
</html>