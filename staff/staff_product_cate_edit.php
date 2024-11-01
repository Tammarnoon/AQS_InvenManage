<?php
// query ข้อมูลมาแสดง
if (isset($_GET['cate_id']) && $_GET['act'] == 'edit') {

  $sqlQueryeditProduct = $condb->prepare("SELECT* FROM tbl_category WHERE cate_id=?");

  //Single row query แสดง 1 รายการ FETCH_ASSOC
  $sqlQueryeditProduct->execute([$_GET['cate_id']]);
  $row = $sqlQueryeditProduct->fetch(PDO::FETCH_ASSOC);

  //ถ้าคิวรี่ผิดพลาดให้หยุดการทำงาน
  if ($sqlQueryeditProduct->rowCount() != 1) {
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
          <h1>แก้ไขข้อมูล Category</h1>
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
                    <label class="col-sm-2">หมวดหมู่</label>
                    <div class="col-sm-4">
                      <input type="text" name="cate_name" class="form-control" value="<?php echo $row['cate_name'] ?>">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-2"></label>
                    <div class="col-sm-4">
                      <input type="hidden" name="cate_id" value="<?php echo $row['cate_id'] ?>">

                      <button type="submit" class="btn btn-primary">ตกลง</button>
                      <a href="staff_product_cate.php" class="btn btn-danger">ยกเลิก</a>
                    </div>
                  </div>

                </div>

              </form>

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

<!-- PHP EDIT -->
<?php

if (isset($_POST['cate_id']) && isset($_POST['cate_name'])) {
  try {
    // ประกาศตัวแปรที่รับมาจาก form
    $cate_id = $_POST['cate_id'];
    $cate_name = $_POST['cate_name'];

    //// เช็ค cate_name ซ้ำ โดยต้องไม่เป็นหมวดหมู่เดียวกับที่กำลังแก้ไข
    $sqlcheckCateNameDUP = $condb->prepare("SELECT cate_name FROM tbl_category WHERE cate_name= :cate_name AND cate_id != :cate_id");

    // BlindParam
    $sqlcheckCateNameDUP->bindParam(':cate_name', $cate_name, PDO::PARAM_STR);
    $sqlcheckCateNameDUP->bindParam(':cate_id', $cate_id, PDO::PARAM_INT);
    $sqlcheckCateNameDUP->execute();

    // ถ้าเจอข้อมูลที่ซ้ำกัน
    if ($sqlcheckCateNameDUP->rowCount() == 1) {
      echo
      '<script>
        setTimeout(function() {
          swal({
            title: "มีหมวดหมู่นี้อยู่เเล้ว",
            text: "กรุณาใส่หมวดหมู่อื่น",
            type: "error"
          }, function() {
            window.location = window.location.href; // รีเฟรชหน้าเดิม
          });
        }, 1);  
      </script>';
    } else {
      // ถ้าไม่เจอข้อมูลที่ซ้ำกัน ทำการอัพเดทข้อมูล
      $sqleditProduct = $condb->prepare("UPDATE tbl_category SET cate_name=:cate_name WHERE cate_id=:cate_id");
      $sqleditProduct->bindParam(':cate_id', $cate_id, PDO::PARAM_INT);
      $sqleditProduct->bindParam(':cate_name', $cate_name, PDO::PARAM_STR);

      $result = $sqleditProduct->execute();

      $condb = null; // ปิดการเชื่อมต่อฐานข้อมูล

      if ($result) {
        echo '<script>
        setTimeout(function() {
          swal({
            title: "แก้ไขข้อมูลสำเร็จ",
            type: "success"
          }, function() {
            window.location = "staff_product_cate.php"; // หน้าที่ต้องการให้กระโดดไป
          });
        }, 1);
      </script>';
      }
    }
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
}


?>
<!-- END PHP EDIT -->