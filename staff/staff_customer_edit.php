<?php
// query ข้อมูลมาแสดง
if (isset($_GET['cus_id']) && $_GET['act'] == 'edit') {

  $sqlQueryeditCus = $condb->prepare("SELECT* FROM tbl_customer WHERE cus_id=?");

  //Single row query แสดง 1 รายการ FETCH_ASSOC
  $sqlQueryeditCus->execute([$_GET['cus_id']]);
  $row = $sqlQueryeditCus->fetch(PDO::FETCH_ASSOC);

  //ถ้าคิวรี่ผิดพลาดให้หยุดการทำงาน
  if ($sqlQueryeditCus->rowCount() != 1) {
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
          <h1>แก้ไขข้อมูล Customer</h1>
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
                      <input type="text" name="name" class="form-control" required value="<?php echo $row['name'] ?>">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-2">ที่อยู่</label>
                    <div class="col-sm-8">
                      <textarea name="address" id="summernote">
                            <?php echo $row['address']; ?>
                        </textarea>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-2">เบอร์โทร</label>
                    <div class="col-sm-4">
                      <input type="number" name="tel" class="form-control" required value="<?php echo $row['tel'] ?>"
                        maxlength="10" oninput="if(this.value.length > 10) this.value = this.value.slice(0, 10);"
                        pattern="[0-9]{10}">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-2"></label>
                    <div class="col-sm-4">
                    <input type="hidden" name="cus_id" value="<?php echo $row['cus_id'] ?>">
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

<!-- PHP EDIT -->
<?php

// ตรวจสอบว่ามีการ submit แบบฟอร์มแก้ไขลูกค้า
if (isset($_POST['submit-cus'])) {
  try {
    $cus_id = $_POST['cus_id'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $tel = $_POST['tel'];

    // ตรวจสอบความยาวของเบอร์โทรให้ครบ 10 หลัก
    if (strlen($tel) < 10) {
      echo '<script>
                    setTimeout(function() {
                        swal({
                            title: "เบอร์โทรไม่ถูกต้อง",
                            text: "กรุณากรอกเบอร์โทร 10 หลัก",
                            type: "error"
                        }, function() {
                            window.location = "staff_customer.php?cus_id='.$cus_id.'&act=edit";
                        });
                    }, 1);
                </script>';
      exit;
    }

    // ตรวจสอบข้อมูลลูกค้าปัจจุบันก่อน
    $sqlCheckCurrent = $condb->prepare("SELECT name, tel FROM tbl_customer WHERE cus_id = :cus_id");
    $sqlCheckCurrent->bindParam(':cus_id', $cus_id, PDO::PARAM_INT);
    $sqlCheckCurrent->execute();
    $currentCustomer = $sqlCheckCurrent->fetch(PDO::FETCH_ASSOC);

    // ตรวจสอบชื่อซ้ำถ้าชื่อใหม่ไม่ตรงกับชื่อเดิม
    if ($name != $currentCustomer['name']) {
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
                            window.location = "staff_customer.php?cus_id='.$cus_id.'&act=edit";
                        });
                    }, 1);
                </script>';
        exit;
      }
    }

    // ตรวจสอบเบอร์โทรซ้ำถ้าเบอร์ใหม่ไม่ตรงกับเบอร์เดิม
    if ($tel != $currentCustomer['tel']) {
      $sqlcheckDUP = $condb->prepare("SELECT tel FROM tbl_customer WHERE tel = :tel");
      $sqlcheckDUP->bindParam(':tel', $tel, PDO::PARAM_STR);
      $sqlcheckDUP->execute();
      if ($sqlcheckDUP->rowCount() > 0) {
        echo '<script>
                  setTimeout(function() {
                      swal({
                          title: "มีเบอร์โทรนี้อยู่เเล้ว",
                          text: "กรุณาใช้เบอร์โทรอื่น",
                          type: "error"
                      }, function() {
                          window.location = "staff_customer.php?cus_id='.$cus_id.'&act=edit";
                      });
                  }, 1);  
              </script>';
        exit;
      }
    }

    // ถ้าไม่ซ้ำ ให้ทำการอัพเดทข้อมูล
    $sqleditCus = $condb->prepare("UPDATE tbl_customer SET name=:name, address=:address, tel=:tel WHERE cus_id=:cus_id");
    $sqleditCus->bindParam(':name', $name, PDO::PARAM_STR);
    $sqleditCus->bindParam(':address', $address, PDO::PARAM_STR);
    $sqleditCus->bindParam(':tel', $tel, PDO::PARAM_STR);
    $sqleditCus->bindParam(':cus_id', $cus_id, PDO::PARAM_INT);

    $result = $sqleditCus->execute();
    $condb = null;

    if ($result) {
      echo '<script>
        setTimeout(function() {
          swal({
            title: "แก้ไขข้อมูลสำเร็จ",
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

?>

<!-- END PHP EDIT -->