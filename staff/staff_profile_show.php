<?php

// echo '<pre>';
// print_r($_SESSION);
// echo '</pre>';

?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Profile ส่วนตัว</h1>
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
                                        <label class="col-sm-2">ระดับ user</label>
                                        <div class="col-sm-2">
                                            <select name="user_level" class="form-control" disabled >
                                                <option value="" disabled>-- เลือกข้อมูล --</option>
                                                <option value="admin" <?= ($_SESSION['user_level'] == 'admin') ? 'selected' : ''; ?>>-- ADMIN --</option>
                                                <option value="staff" <?= ($_SESSION['user_level'] == 'staff') ? 'selected' : ''; ?>>-- STAFF --</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2">Username</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="username" class="form-control"
                                                required value="<?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : ''; ?>" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2">คำนำหน้า</label>
                                        <div class="col-sm-2">
                                            <select name="title_name" class="form-control" required>
                                                <option value="" disabled>-- เลือกข้อมูล --</option>
                                                <option value="นาย" <?= ($_SESSION['title_name'] == 'นาย') ? 'selected' : ''; ?>>-- นาย --</option>
                                                <option value="นาง" <?= ($_SESSION['title_name'] == 'นาง') ? 'selected' : ''; ?>>-- นาง --</option>
                                                <option value="นางสาว" <?= ($_SESSION['title_name'] == 'นางสาว') ? 'selected' : ''; ?>>-- นางสาว --</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2">ชื่อ</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="name" class="form-control"
                                                required value="<?= isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : ''; ?>">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2">นามสกุล</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="surname" class="form-control"
                                                required value="<?= isset($_SESSION['surname']) ? htmlspecialchars($_SESSION['surname']) : ''; ?>">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2">รูปภาพ</label>
                                        <div class="col-sm-4">
                                            รูปภาพเดิม
                                            <br>
                                            <?php if (isset($_SESSION['user_img']) && !empty($_SESSION['user_img'])): ?>
                                                <img src="../assets/user_img/<?= htmlspecialchars($_SESSION['user_img']) ?>" width="200px" alt="Current User Image">
                                            <?php else: ?>
                                                <img src="../assets/user_img/default.jpg" width="200px" alt="No Image Available">
                                            <?php endif; ?>
                                            <br>
                                            <br>
                                            เลือกรูปภาพใหม่
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" name="user_img" class="custom-file-input" id="exampleInputFile">
                                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2"></label>
                                        <div class="col-sm-4">
                                            <input type="hidden" name="id" value="<?php echo $_SESSION['id'] ?>">
                                            <input type="hidden" name="oldImg" value="<?php echo $_SESSION['user_img']; ?>">
                                            <button type="submit" class="btn btn-primary">ตกลง</button>
                                            <button type="reset" class="btn btn-danger">ยกเลิก</button>
                                        </div>
                                    </div>

                                </div>

                                <?php
                                //เช็คข้อมูล
                                // echo '<pre>';
                                // print_r($_POST);
                                // echo '<hr>';
                                // print_r($_FILES);
                                // exit;
                                ?>
                            </form>

                        </div>
                        <!-- /.card-body -->


                    </div>
                </div>
            </div>
            <!-- /.col-->
        </div>
    </section>

<!-- PHP EDIT -->
<?php

if (isset($_POST['id']) && isset($_POST['name']) && isset($_POST['surname']) && isset($_POST['title_name'])) {
  try {
    // ประกาศตัวแปรที่รับมาจาก form
    $id = $_POST['id'];
    $title_name = $_POST['title_name'];
    $name = $_POST['name'];
    $surname = $_POST['surname'];

    //เงื่อนไขการตรวจสอบการอัพโหลดไฟล์
    if ($_FILES['user_img']['name'] == '') {
      // echo'ไม่มีการอัพไฟล์';

      //sql edit
      $sqleditUser = $condb->prepare("UPDATE tbl_user SET title_name=:title_name, name=:name, surname=:surname WHERE id=:id");

      //blindParam
      $sqleditUser->bindParam(':id', $id, PDO::PARAM_INT);
      $sqleditUser->bindParam(':title_name', $title_name, PDO::PARAM_STR);
      $sqleditUser->bindParam(':name', $name, PDO::PARAM_STR);
      $sqleditUser->bindParam(':surname', $surname, PDO::PARAM_STR);

      $result = $sqleditUser->execute();

      // อัปเดตเซสชันเฉพาะเมื่อมีการเปลี่ยนแปลงข้อมูลของผู้ใช้ที่ล็อกอินอยู่
      if ($result) {
        // ตรวจสอบว่าผู้ใช้ที่ล็อกอินอยู่คือคนที่ถูกแก้ไขหรือไม่
        if ($_SESSION['id'] == $id) {
          // ตรวจสอบว่ามีการอัปโหลดรูปภาพใหม่หรือไม่
          if (!empty($user_img)) {
            $_SESSION['user_img'] = htmlspecialchars($user_img);
          }

          // อัปเดตตัวแปรเซสชันของผู้ใช้ที่ล็อกอินอยู่
          $_SESSION['title_name'] = htmlspecialchars($title_name);
          $_SESSION['name'] = htmlspecialchars($name);
          $_SESSION['surname'] = htmlspecialchars($surname);
        }


        echo '<script>
              setTimeout(function() {
                  swal({
                      title: "แก้ไขข้อมูลสำเร็จ",
                      type: "success"
                  }, function() {
                      window.location = "staff_profile.php"; //หน้าที่ต้องการให้กระโดดไป
                  });
              }, 1);
          </script>';
      }
    } else {
      // echo'มีการอัพไฟล์';

      //สร้างตัวแปรวันที่เพื่อเอาไปตั้งชื่อไฟล์ใหม่
      $date1 = date("Ymd_His");

      //สร้างตัวแปรสุ่มตัวเลขเพื่อเอาไปตั้งชื่อไฟล์ที่อัพโหลดไม่ให้ชื่อไฟล์ซ้ำกัน
      $numrand = (mt_rand());
      $user_img = (isset($_POST['user_img']) ? $_POST['user_img'] : '');

      //ตัดขื่อเอาเฉพาะนามสกุล
      $typefile = strrchr($_FILES['user_img']['name'], ".");

      // echo $typefile;
      // exit;

      //สร้างเงื่อนไขตรวจสอบนามสกุลของไฟล์ที่อัพโหลดเข้ามา
      if ($typefile != '.jpg' && $typefile != '.jpeg' && $typefile != '.png') {
        // echo 'อัพโหลดไฟล์ไม่ถูก';
        // exit;

        echo '<script>
                      setTimeout(function() {
                        swal({
                            title: "อัพโหลดไฟล์ไม่ถูกต้อง",
                            text: ".jpg, .jpeg, .png เท่านั้น",
                            type: "error"
                        }, function() {
                            window.location = "staff_profile.php?id=' . $id . '&act=edit"; //หน้าที่ต้องการให้กระโดดไป
                        });
                      }, 1);
                  </script>';
        exit;
      } else {
        // echo 'อัพโหลดไฟล์ถูก';

        //ลบภาพเก่า
        unlink('../assets/user_img/' . $_POST['oldImg']);

        //โฟลเดอร์ที่เก็บไฟล์
        $path = "../assets/user_img/";

        //ตั้งชื่อไฟล์ใหม่เป็น สุ่มตัวเลข+วันที่
        $newname = $numrand . $date1 . $typefile;
        $path_copy = $path . $newname;

        //คัดลอกไฟล์ไปยังโฟลเดอร์
        move_uploaded_file($_FILES['user_img']['tmp_name'], $path_copy);

        //sql edit
        $sqleditUser = $condb->prepare("UPDATE tbl_user SET title_name=:title_name, name=:name, 
        surname=:surname, user_img='$newname' WHERE id=:id");

        //blindParam
        $sqleditUser->bindParam(':id', $id, PDO::PARAM_INT);
        $sqleditUser->bindParam(':title_name', $title_name, PDO::PARAM_STR);
        $sqleditUser->bindParam(':name', $name, PDO::PARAM_STR);
        $sqleditUser->bindParam(':surname', $surname, PDO::PARAM_STR);

        $result = $sqleditUser->execute();

        //เงื่อนไขตรวจสอบการเพิ่มข้อมูล
        if ($result) {
          // ตรวจสอบว่าผู้ใช้ที่ล็อกอินอยู่คือคนที่ถูกแก้ไขหรือไม่
          if ($_SESSION['id'] == $id) {
            // อัปเดตตัวแปรเซสชันของผู้ใช้ที่ล็อกอินอยู่
            $_SESSION['user_img'] = htmlspecialchars($newname); 
            $_SESSION['name'] = htmlspecialchars($name);
            $_SESSION['surname'] = htmlspecialchars($surname);
            $_SESSION['title_name'] = htmlspecialchars($title_name);
          }


          echo '<script>
                                  setTimeout(function() {
                                    swal({
                                        title: "แก้ข้อมูลสำเร็จ",
                                        type: "success"
                                    }, function() {
                                        window.location = "staff_profile.php"; //หน้าที่ต้องการให้กระโดดไป
                                    });
                                  }, 1);
                              </script>';
          exit;
        }
      } // check file type

    } // echo'มีการอัพไฟล์';

  } catch (PDOException $e) {
    // handle PDOException errors
    echo '<script>
          setTimeout(function() {
              swal({
                  title: "เกิดข้อผิดพลาด",
                  type: "error"
              }, function() {
                  window.location = "staff_profile.php"; //หน้าที่ต้องการให้กระโดดไป
              });
          }, 1);
      </script>';
  }
} //isset


?>
<!-- END PHP EDIT -->