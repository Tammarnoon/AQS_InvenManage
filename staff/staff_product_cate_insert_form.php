  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>เพิ่มข้อมูล Category</h1>
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
                      <label class="col-sm-2">หมวดหมู่</label>
                      <div class="col-sm-4">
                        <input type="text" name="cate_name" class="form-control" required placeholder="หมวดหมู่">
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-sm-2"></label>
                      <div class="col-sm-4">
                        <button type="submit" class="btn btn-primary">ตกลง</button>
                        <a href="staff_product_cate.php" class="btn btn-danger">ยกเลิก</a>
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
  if (isset($_POST['cate_name'])) {
    try {
      // ประกาศตัวแปรรับค่าจากฟอร์ม
      $cate_name = $_POST['cate_name'];

      //เช็ค cate_name ซ้ำ
      $sqlcheckCateNameDUP = $condb->prepare("SELECT cate_name FROM tbl_category WHERE cate_name= :cate_name");

      //Single row query แสดง 1 รายการ FETCH_ASSOC
      //blindParam
      $sqlcheckCateNameDUP->bindParam(':cate_name', $cate_name, PDO::PARAM_STR);
      $sqlcheckCateNameDUP->execute();
      $row = $sqlcheckCateNameDUP->fetch(PDO::FETCH_ASSOC);

      //นับจำนวนการ query ถ้า 1 คือ cate_name ซ้ำ
      // echo $sqlcheckCateNameDUP->rowCount();
      // echo '<hr>';
      // exit;

      if ($sqlcheckCateNameDUP->rowCount() == 1) {
        // echo ' cate_name ซ้ำ ';

        echo
        '<script>
                         setTimeout(function() {
                          swal({
                              title: "มีหมวดหมู่นี้อยู่เเล้ว",
                              text: "กรุณาใส่หมวดหมู่อื่น",
                              type: "error"
                          }, function() {
                              window.location = "staff_product_cate.php?act=insert"; //หน้าที่ต้องการให้กระโดดไป
                          });
                        }, 1);  
                    </script>';
      } else {
        // echo ' cate_name ไม่ซ้ำ ';

        //sql insert
        $sqlcheckCateNameDUP = $condb->prepare("INSERT INTO tbl_category (cate_name)
      VALUES (:cate_name)");

        //blindParam
        $sqlcheckCateNameDUP->bindParam(':cate_name', $cate_name, PDO::PARAM_STR);

        $resultCate = $sqlcheckCateNameDUP->execute();

        $condb = null; //close connect db

        if ($resultCate) {
          echo '<script>
           setTimeout(function() {
            swal({
                title: "เพิ่มข้อมูลสำเร็จ",
                type: "success"
            }, function() {
                window.location = "staff_product_cate.php"; //หน้าที่ต้องการให้กระโดดไป
            });
          }, 1);
      </script>';
        }
      } //else echo ' cate_name ไม่ซ้ำ ';
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
                          window.location = "staff_product_cate.php"; //หน้าที่ต้องการให้กระโดดไป
                      });
                    }, 1);
                </script>';
    }
  } // isset

  ?>
  <!-- END PHP INSERT -->