<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<?php
echo "<script>
        $(document).ready(function() {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'ส่งสลิปสำเร็จ',
                                    text: 'สามารถตรวจสอบการโอนเงินได้',
                                    showConfirmButton: false,
                                    timer: 5000
                                  })     
        });             
        </script>";
header("refresh:2; url=status.php");
?>
 <!DOCTYPE html>
<html>
<head>
    <title>Redirecting...</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
</head>
<body>

</body>
</html>           