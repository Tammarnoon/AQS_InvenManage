<?php
// query ข้อมูลมาแสดง
if (isset($_GET['id']) && $_GET['act'] == 'password') {

  $sqlQueryeditUser = $condb->prepare("SELECT* FROM tbl_user WHERE id=?");

  //Single row query แสดง 1 รายการ FETCH_ASSOC
  $sqlQueryeditUser->execute([$_GET['id']]);
  $row = $sqlQueryeditUser->fetch(PDO::FETCH_ASSOC);

  //ถ้าคิวรี่ผิดพลาดให้หยุดการทำงาน
  if ($sqlQueryeditUser->rowCount() != 1) {
    exit();
  }
} //isset
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>แก้ไข Password</h1>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="card card-outline card-info">
          <div class="card-body">
            <div class="card card-primary">
              <!-- form start -->
              <form action="" method="post" enctype="multipart/form-data">
                <div class="card-body">

                  <div class="form-group row">
                    <label class="col-sm-2">Username</label>
                    <div class="col-sm-4">
                      <input type="text" name="username" class="form-control" value="<?php echo $row['username']; ?>" disabled>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-2">New password</label>
                    <div class="col-sm-4">
                      <input type="password" name="newpassword" class="form-control" required placeholder="รหัสผ่านใหม่">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-2">Comfirm password</label>
                    <div class="col-sm-4">
                      <input type="password" name="confirmpassword" class="form-control" required placeholder="ยืนยันรหัสผ่าน">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-2"></label>
                    <div class="col-sm-4">
                      <input type="hidden" name="id" value="<?php echo $row['id'] ?>">
                      <button type="submit" class="btn btn-primary">ตกลง</button>
                      <a href="admin_user.php" class="btn btn-danger">ยกเลิก</a>
                    </div>
                  </div>

                </div>

              </form>
              <!-- เช็คข้อมูล -->
              <?php
              // echo '<pre>';
              // print_r($_POST);
              ?>


            </div> <!-- /.card-body -->

          </div>
        </div>
      </div>
      <!-- /.col-->
    </div>
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- PHP CONFIRM PASSWORD -->
<?php

if (isset($_POST['id']) && isset($_POST['newpassword']) && isset($_POST['confirmpassword'])) {
  try {
    //ประกาศตัวแปรที่รับมาจาก formm
    $id = $_POST['id'];
    $newpassword = $_POST['newpassword'];
    $confirmpassword = $_POST['confirmpassword'];

    //สร้างเงื่อนไขตรวจสอบ password ว่าครงกันไหม
    if ($newpassword != $confirmpassword) {
      // echo 'password ไม่ตรงกัน';
      echo '<script>
                       setTimeout(function() {
                        swal({
                            title: "Password ไม่ตรงกัน",
                            text: "ตรวจสอบ Password อีกครั้ง",
                            type: "error"
                        }, function() {
                            window.location = "admin_user.php?id=' . $id . '&act=password"; //หน้าที่ต้องการให้กระโดดไป
                        });
                      }, 1);
                  </script>';
    } else {
      // echo 'password ตรงกัน';

      $password = sha1($_POST['newpassword']);

      //sql update
      $sqleditPassword = $condb->prepare("UPDATE  tbl_user SET password='$password' WHERE id=:id");

      //blindParam
      $sqleditPassword->bindParam(':id', $id, PDO::PARAM_INT);

      $result = $sqleditPassword->execute();

      $condb = null; //close connect db

      if ($result) {
        echo '<script>
                       setTimeout(function() {
                        swal({
                            title: "แก้ไขรหัสผ่านสำเร็จ",
                            type: "success"
                        }, function() {
                            window.location = "admin_user.php"; //หน้าที่ต้องการให้กระโดดไป
                        });
                      }, 1);
                  </script>';
      }
    } //else 'password ตรงกัน';
    
  } catch (PDOException $e) {
    // error show
    // echo 'Message: '. $e->getMessage();

    // handle PDOException errors
    echo '<script>
                       setTimeout(function() {
                        swal({
                            title: "เกิดข้อผิดพลาด",
                            type: "error"
                        }, function() {
                            window.location = "admin_user.php"; //หน้าที่ต้องการให้กระโดดไป
                        });
                      }, 1);
                  </script>';
  }
} //isset

?>
<!--  END PHP CONFIRM PASSWORD -->