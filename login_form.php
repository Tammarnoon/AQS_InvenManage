<?php
//start session
session_start();

//เรียกใช้ file condb
require_once 'config/condb.php';

// echo '<pre>';
// print_r($_POST);

//เงื่อนไข check input จาก form
if (isset($_POST['username']) && isset($_POST['password']) && $_POST['action'] == 'login') {
    //ประกาศตัวแปรรับค่าจากฟอร์ม
    $username = $_POST['username'];
    $password = sha1($_POST['password']); //เก็บรหัสผ่านในรูปแบบ sha1 

    //check username  & password
    $stmtLogin = $condb->prepare("SELECT * FROM tbl_user WHERE username = :username AND password = :password");
    //blindParam
    $stmtLogin->bindParam(':username', $username, PDO::PARAM_STR);
    $stmtLogin->bindParam(':password', $password, PDO::PARAM_STR);
    $stmtLogin->execute();

    //นับจำนวนที่ query ถ้า 0 = ไม่เจอ
    // echo $stmtLogin->rowCount();
    // exit;

    if ($stmtLogin->rowCount() == 1) {
        //fetch เพื่อเรียกคอลัมภ์ที่ต้องการไปสร้างตัวแปร session
        $row = $stmtLogin->fetch(PDO::FETCH_ASSOC);
        //สร้างตัวแปร session
        $_SESSION['id'] = $row['id'];
        $_SESSION['user_level'] = $row['user_level'];
        $_SESSION['title_name'] = $row['title_name']; 
        $_SESSION['name'] = $row['name']; 
        $_SESSION['surname'] = $row['surname']; 
        $_SESSION['username'] = $row['username'];
        $_SESSION['user_img'] = $row['user_img'];

        //เช็คว่ามีตัวแปร session อะไรบ้าง
        // print_r($_SESSION);
        // exit();

        $condb = null; //close connect db

        //check user level
        if (isset($_SESSION['user_level']) && $_SESSION['user_level'] == 'admin') { // ตรวจสอบระดับผู้ใช้ admin
            header('Location: admin/');
            exit;
        }

        if (isset($_SESSION['user_level']) && $_SESSION['user_level'] == 'staff') { // ตรวจสอบระดับผู้ใช้ staff
            header('Location: staff/');
            exit;
        }

    } else { //ถ้า username or password ไม่ถูกต้อง

        echo '<script>
                          setTimeout(function() {
                           swal({
                               title: "เกิดข้อผิดพลาด",
                                text: "Username หรือ Password ไม่ถูกต้อง ลองใหม่อีกครั้ง",
                               type: "warning"
                           }, function() {
                               window.location = "login_form.php"; //หน้าที่ต้องการให้กระโดดไป
                           });
                         }, 1);
                     </script>';
        $condb = null; //close connect db

    } //else

} //isset

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AquatechLogin</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="assets/dist/css/adminlte.min.css?v=3.2.0">

    <!-- Switch alert -->
    <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href=""><b>AquaTech |</b>| Login</a>
        </div>

        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Login เพื่อทำการเข้าสู่ระบบ</p>

                <form action="" method="post">
                    <div class="input-group mb-3">
                        <input type="text" name="username" class="form-control" placeholder="username" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8">
                        </div>

                        <div class="col-12">
                            <button type="submit" name="action" value="login" class="btn btn-primary btn-block">เข้าสู่ระบบ</button>
                        </div>

                    </div>
                </form>
            </div>

        </div>
    </div>

</body>

</html>