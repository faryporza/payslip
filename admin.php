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

// Check if user is admin
if ($status !== 'admin') {
    // Redirect to upload.php or any other page you want to redirect non-admin users to
    header('location: upload.php');
    exit(); // Stop further execution
}


// Logout
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    header('location: index.php');
}

// Update user status
if (isset($_POST['update_status'])) {
    $userId = $_POST['user_id'];
    $status = $_POST['status'];
    $query = "UPDATE users SET status='$status' WHERE id='$userId'";
    mysqli_query($db, $query);
}

// Edit username and password
if (isset($_POST['edit_user'])) {
    $userId = $_POST['user_id'];
    $newUsername = $_POST['new_username'];
    $newPassword = $_POST['new_password'];

    // Update username and password in the database
    $query = "UPDATE users SET username='$newUsername', password='$newPassword' WHERE id='$userId'";
    mysqli_query($db, $query);
}

// Delete user
if (isset($_POST['delete_user'])) {
    $userId = $_POST['user_id'];

    // Delete user images from the folder
    $deleteImagesQuery = "SELECT image FROM images WHERE id='$userId'";
    $deleteImagesResult = mysqli_query($db, $deleteImagesQuery);
    while ($imageRow = mysqli_fetch_assoc($deleteImagesResult)) {
        $image = $imageRow['image'];
        unlink("images/" . $image);
    }

    // Delete user images from the database
    $deleteImagesQuery = "DELETE FROM images WHERE id='$userId'";
    mysqli_query($db, $deleteImagesQuery);

    // Delete user record from the database
    $deleteUserQuery = "DELETE FROM users WHERE id='$userId'";
    mysqli_query($db, $deleteUserQuery);
    $successMsg = "User deleted successfully.";
}

// Delete user image
if (isset($_GET['delete_image'])) {
    $imageId = $_GET['delete_image'];
    $query = "SELECT image FROM images WHERE id='$imageId'";
    $result = mysqli_query($db, $query);
    $image = mysqli_fetch_assoc($result)['image'];

    // Delete image file from folder
    if (unlink("images/" . $image)) {
        $deleteMsg = "Image deleted successfully.";
    } else {
        $deleteMsg = "Failed to delete image.";
    }

    // Delete image record from database
    $deleteQuery = "DELETE FROM images WHERE id='$imageId'";
    mysqli_query($db, $deleteQuery);
}

// Fetch all users
$query = "SELECT * FROM users";
$result = mysqli_query($db, $query);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin System</title>
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
        .container a {
            display: inline-block;
            padding: 8px 16px;
            /*background-color: #4CAF50;*/
            color: #fff;
            text-decoration: none;
            border-radius: 3px;
            margin-bottom: 10px;
        }
        .container a:hover {
            background-color: #45a049;
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
        .container form .form-group select {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 3px;
            margin-bottom: 10px;
        }
        .container form button {
            padding: 8px 16px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
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
        .container .delete-msg {
            color: #f00;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>สาหวัดดี, <?php echo $username; ?>!</h2>
        <a href="admin.php?logout='1'" class="btn btn-danger">Logout</a>
        <a href="upload.php" class="btn btn-warning">กลับสู่หน้าหลัก</a>
        <hr>
        <h3>User Status</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Status</th>
                    <th>Action</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['username']; ?><br><?php echo $row['password']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                        <td>
                            <form method="post" action="admin.php">
                                <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                <div class="form-group">
                                    <select name="status">
                                        <option value="ยังไม่ได้จ่าย ❌" <?php if ($row['status'] == 'ยังไม่ได้จ่าย ❌') echo 'selected'; ?>>ยังไม่ได้จ่าย ❌</option>
                                        <option value="จ่ายแล้ว ✅" <?php if ($row['status'] == 'จ่ายแล้ว ✅') echo 'selected'; ?>>จ่ายแล้ว ✅</option>
                                        <option value="ส่งสลิปใหม่อีกครั้ง ❗" <?php if ($row['status'] == 'ส่งสลิปใหม่อีกครั้ง ❗') echo 'selected'; ?>>ส่งสลิปใหม่อีกครั้ง ❗</option>
                                        <!-- Add more status options as needed -->
                                    </select>
                                </div>
                                <button type="submit" name="update_status" class="btn btn-primary">Update</button>
                            </form>
                        </td>
                        <td>
                            <form method="post" action="admin.php">
                                <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                <div class="form-group">
                                    <input type="text" name="new_username" placeholder="New Username" required>
                                </div>
                                <div class="form-group">
                                    <input type="password" name="new_password" placeholder="New Password" required>
                                </div>
                                <button type="submit" name="edit_user" class="btn btn-success"  onclick="return confirm('Are you sure?');" >Edit</button>
                            </form>
                        </td>
                        <td>
                            <form method="post" action="admin.php">
                                <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="delete_user" class="btn btn-danger"  onclick="return confirm('Are you sure?');" >Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <h3>Images</h3>
        <?php if (isset($deleteMsg)) { ?>
            <div class="delete-msg alert alert-danger"><?php echo $deleteMsg; ?></div>
        <?php } ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Image</th>
                    <th>Upload Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $imageQuery = "SELECT * FROM images";
                $imageResult = mysqli_query($db, $imageQuery);

                while ($imageRow = mysqli_fetch_assoc($imageResult)) {
                    $imageId = $imageRow['id'];
                    $username = $imageRow['username'];
                    $image = $imageRow['image'];
                    $uploadDate = $imageRow['upload_date'];
                ?>
                    <tr>
                        <td><?php echo $username; ?></td>
                        <td><a href ="images/<?php echo $image; ?>"><img src="images/<?php echo $image; ?>" width="100"></a></td>
                        <td><?php echo $uploadDate; ?></td>
                        <td><a href="admin.php?delete_image=<?php echo $imageId; ?>" onclick="return confirm('Are you sure?');">Delete</a></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
