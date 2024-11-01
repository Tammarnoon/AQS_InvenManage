  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>เพิ่มข้อมูล User</h1>
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
                        <input type="text" name="username" class="form-control" required placeholder="username">
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-sm-2">Password</label>
                      <div class="col-sm-4">
                        <input type="password" name="password" class="form-control" required placeholder="password">
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-sm-2">ระดับ user</label>
                      <div class="col-sm-2">
                        <select name="user_level" class="form-control" required>
                          <option value="">-- เลือกข้อมูล --</option>
                          <option value="admin">-- ADMIN --</option>
                          <option value="staff">-- STAFF --</option>
                        </select>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-sm-2">คำนำหน้า</label>
                      <div class="col-sm-2">
                        <select name="title_name" class="form-control" required>
                          <option value="">-- เลือกข้อมูล --</option>
                          <option value="นาย">-- นาย --</option>
                          <option value="นาง">-- นาง --</option>
                          <option value="นางสาว">-- นางสาว --</option>
                        </select>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-sm-2">ชื่อ</label>
                      <div class="col-sm-4">
                        <input type="text" name="name" class="form-control" required placeholder="ชื่อ">
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-sm-2">นามสกุล</label>
                      <div class="col-sm-4">
                        <input type="text" name="surname" class="form-control" required placeholder="นามสกุล">
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-sm-2">รูปภาพ</label>
                      <div class="col-sm-4">
                        <div class="input-group">
                          <div class="custom-file">
                            <input type="file" name="user_img" class="custom-file-input" required id="exampleInputFile">
                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-sm-2"></label>
                      <div class="col-sm-4">
                        <button type="submit" class="btn btn-primary">ตกลง</button>
                        <a href="admin_user.php" class="btn btn-danger">ยกเลิก</a>
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
                // ?>
                </form>

              </div>
              <!-- /.card-body -->


            </div>
          </div>
        </div>
        <!-- /.col-->
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- PHP INSERT -->
  <?php

  //เช็ค input ที่มาจาก form
  if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['name']) && isset($_POST['surname'])) {
    try {
      // ประกาศตัวแปรรับค่าจากฟอร์ม
      $username = $_POST['username'];
      $password = sha1($_POST['password']);
      $title_name = $_POST['title_name'];
      $name = $_POST['name'];
      $surname = $_POST['surname'];
      $user_level = $_POST['user_level'];

      //เช็ค username ซ้ำ
      $sqlcheckUsernameDUP = $condb->prepare("SELECT username FROM tbl_user WHERE username= :username");

      //bindParam
      $sqlcheckUsernameDUP->bindParam(':username', $username, PDO::PARAM_STR);
      $sqlcheckUsernameDUP->execute();
      $row = $sqlcheckUsernameDUP->fetch(PDO::FETCH_ASSOC);

      //นับจำนวนการ query ถ้า 1 คือ username ซ้ำ
      if ($sqlcheckUsernameDUP->rowCount() == 1) {
        echo '<script>
                  setTimeout(function() {
                      swal({
                          title: "มี Username นี้อยู่เเล้ว",
                          text: "กรุณาใช้ Username อื่น",
                          type: "error"
                      }, function() {
                          window.location = "admin_user.php?act=insert"; //หน้าที่ต้องการให้กระโดดไป
                      });
                  }, 1);  
              </script>';
      } else {

        //สร้างตัวแปรวันที่เพื่อเอาไปตั้งชื่อไฟล์ใหม่
        $date1 = date("Ymd_His");

        //สร้างตัวแปรสุ่มตัวเลขเพื่อเอาไปตั้งชื่อไฟล์ที่อัพโหลดไม่ให้ชื่อไฟล์ซ้ำกัน
        $numrand = (mt_rand());
        $user_img = (isset($_POST['user_img']) ? $_POST['user_img'] : '');
        $upload = $_FILES['user_img']['name'];

        //มีการอัพโหลดไฟล์
        if ($upload != '') {
          //ตัดขื่อเอาเฉพาะนามสกุล
          $typefile = strrchr($_FILES['user_img']['name'], ".");

          //สร้างเงื่อนไขตรวจสอบนามสกุลของไฟล์ที่อัพโหลดเข้ามา
          if ($typefile == '.jpg' || $typefile  == '.jpeg' || $typefile  == '.png') {

            //โฟลเดอร์ที่เก็บไฟล์
            $path = "../assets/user_img/"; //ยังไม่ได้

            //ตั้งชื่อไฟล์ใหม่เป็น สุ่มตัวเลข+วันที่
            $newname = $numrand . $date1 . $typefile;
            $path_copy = $path . $newname;

            //คัดลอกไฟล์ไปยังโฟลเดอร์
            move_uploaded_file($_FILES['user_img']['tmp_name'], $path_copy);

            //sql insert
            $sqlinsertUser = $condb->prepare("INSERT INTO tbl_user (username, password, title_name, name, surname, user_level, user_img)
            VALUES (:username, '$password', :title_name, :name, :surname, :user_level, '$newname')");

            //bindParam
            $sqlinsertUser->bindParam(':username', $username, PDO::PARAM_STR);
            $sqlinsertUser->bindParam(':title_name', $title_name, PDO::PARAM_STR);
            $sqlinsertUser->bindParam(':name', $name, PDO::PARAM_STR);
            $sqlinsertUser->bindParam(':surname', $surname, PDO::PARAM_STR);
            $sqlinsertUser->bindParam(':user_level', $user_level, PDO::PARAM_STR);

            $resultUser = $sqlinsertUser->execute();

            $condb = null; //close connect db

            if ($resultUser) {
              echo '<script>
                    setTimeout(function() {
                        swal({
                            title: "เพิ่มข้อมูลสำเร็จ",
                            type: "success"
                        }, function() {
                            window.location = "admin_user.php"; //หน้าที่ต้องการให้กระโดดไป
                        });
                    }, 1);
                </script>';
            }
          } else { //ถ้าไฟล์ที่อัพโหลดไม่ตรงตามที่กำหนด
            echo '<script>
                          setTimeout(function() {
                            swal({
                                title: "อัพโหลดไฟล์ไม่ถูกต้อง",
                                text: ".jpg, .jpeg, .png เท่านั้น",
                                type: "error"
                            }, function() {
                                window.location = "admin_user.php?act=insert"; //หน้าที่ต้องการให้กระโดดไป
                            });
                          }, 1);
                      </script>';
          } //else ของเช็คนามสกุลไฟล์

        } // if($upload !='')

      } //นับจำนวนการ query ถ้า 1 คือ username ซ้ำ

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
    } // catch

  } //isset


?>
  <!-- END PHP INSERT -->