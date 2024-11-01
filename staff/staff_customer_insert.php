  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>เพิ่มข้อมูล Customer</h1>
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
                <form action="" method="post">
                  <div class="card-body">

                    <div class="form-group row">
                      <label class="col-sm-2">ชื่อ</label>
                      <div class="col-sm-4">
                        <input type="text" name="name" class="form-control" required placeholder="ชื่อลูกค้า // ชื่อบริษัท">
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-sm-2">ที่อยู่</label>
                      <div class="col-sm-8">
                        <textarea name="address" id="summernote"></textarea>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-sm-2">เบอร์โทร</label>
                      <div class="col-sm-4">
                        <input type="number" name="tel" class="form-control" required placeholder="เบอร์โทร"
                          maxlength="10" oninput="if(this.value.length > 10) this.value = this.value.slice(0, 10);"
                          pattern="[0-9]{10}">
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-sm-2"></label>
                      <div class="col-sm-4">
                        <button type="submit" name="submit-cus" class="btn btn-primary">ตกลง</button>
                        <a href="staff_customer.php" class="btn btn-danger">ยกเลิก</a>
                      </div>
                    </div>

                  </div>

                  <?php
                  //เช็คข้อมูล
                  // echo '<pre>';
                  // print_r($_POST);
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
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- PHP INSERT -->
  <?php

  //เช็ค input ที่มาจาก form
  if (isset($_POST['submit-cus'])) {
    try {
        // ประกาศตัวแปรรับค่าจากฟอร์ม และลบช่องว่างที่เกินออก
        $name = trim($_POST['name']);
        $address = strip_tags($_POST['address']);
        $tel = trim($_POST['tel']);

        // เช็ค name ซ้ำ
        $sqlCheckNameDUP = $condb->prepare("SELECT name FROM tbl_customer WHERE name = :name");
        $sqlCheckNameDUP->bindParam(':name', $name, PDO::PARAM_STR);
        $sqlCheckNameDUP->execute();

        if ($sqlCheckNameDUP->rowCount() > 0) {
            echo '<script>
                      setTimeout(function() {
                          swal({
                              title: "มีชื่อลูกค้านี้อยู่แล้ว",
                              text: "กรุณาใช้ชื่ออื่น",
                              type: "error"
                          }, function() {
                              window.location = "staff_customer.php?act=insert";
                          });
                      }, 1);  
                  </script>';
            exit;
        }

        // เช็คว่าความยาวของเบอร์โทรครบ 10 หลัก
        if (strlen($tel) !== 10) {
            echo '<script>
                      setTimeout(function() {
                          swal({
                              title: "เบอร์โทรไม่ถูกต้อง",
                              text: "กรุณากรอกเบอร์โทร 10 หลัก",
                              type: "error"
                          }, function() {
                              window.location = "staff_customer.php?act=insert";
                          });
                      }, 1);
                  </script>';
            exit;
        }

        // เช็ค tel ซ้ำ
        $sqlcheckDUP = $condb->prepare("SELECT tel FROM tbl_customer WHERE tel = :tel");
        $sqlcheckDUP->bindParam(':tel', $tel, PDO::PARAM_STR);
        $sqlcheckDUP->execute();

        if ($sqlcheckDUP->rowCount() > 0) {
            echo '<script>
                      setTimeout(function() {
                          swal({
                              title: "มีเบอร์โทรนี้อยู่แล้ว",
                              text: "กรุณาใช้เบอร์โทรอื่น",
                              type: "error"
                          }, function() {
                              window.location = "staff_customer.php?act=insert";
                          });
                      }, 1);  
                  </script>';
            exit;
        }

        // sql insert ข้อมูลใหม่
        $sqlinsertCus = $condb->prepare("INSERT INTO tbl_customer (name, address, tel) VALUES (:name, :address, :tel)");
        $sqlinsertCus->bindParam(':name', $name, PDO::PARAM_STR);
        $sqlinsertCus->bindParam(':address', $address, PDO::PARAM_STR);
        $sqlinsertCus->bindParam(':tel', $tel, PDO::PARAM_STR);

        $resultCus = $sqlinsertCus->execute();

        $condb = null; // close connection

        if ($resultCus) {
            echo '<script>
                      setTimeout(function() {
                          swal({
                              title: "เพิ่มข้อมูลสำเร็จ",
                              type: "success"
                          }, function() {
                              window.location = "staff_customer.php";
                          });
                      }, 1);
                  </script>';
        }
    } catch (PDOException $e) {
        echo '<script>
                  setTimeout(function() {
                      swal({
                          title: "เกิดข้อผิดพลาด",
                          type: "error"
                      }, function() {
                          window.location = "staff_customer.php";
                      });
                  }, 1);
              </script>';
    }
}

//isset


  ?>
  <!-- END PHP INSERT -->