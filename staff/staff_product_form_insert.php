<?php
//คิวรี่ข้อมูลมาแสดงในตาราง
$sqlqueryproductCate = $condb->prepare("SELECT* FROM tbl_category ");
$sqlqueryproductCate->execute();

//fetchAll แสดงทุกรายการ
$resultproductCate = $sqlqueryproductCate->fetchAll();

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>เพิ่มข้อมูล Product</h1>
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
                    <div class="col-sm-2">
                      <select name="ref_cate_id" class="form-control" required>

                        <option value="">-- เลือกข้อมูล --</option>

                        <?php foreach ($resultproductCate as $row) { ?>
                          <option value="<?php echo $row['cate_id'] ?>">-- <?php echo $row['cate_name'] ?> --</option>

                        <?php } ?>

                      </select>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-2">ชื่อสินค้า</label>
                    <div class="col-sm-4">
                      <input type="text" name="product_name" class="form-control" required placeholder="ชื่อสินค้า">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-2">รายละเอียดสินค้า</label>
                    <div class="col-sm-8">
                      <textarea name="product_detail" id="summernote"></textarea>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-2">ราคาสินค้า</label>
                    <div class="col-sm-4">
                      <input type="number" name="product_price" class="form-control" value="0" min="0" max="9999999" step="0.01">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-2">รูปภาพสินค้า</label>
                    <div class="col-sm-4">
                      <div class="input-group">
                        <div class="custom-file">
                          <input type="file" name="product_img" class="custom-file-input" required id="exampleInputFile">
                          <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-2"></label>
                    <div class="col-sm-4">
                      <button type="submit" class="btn btn-primary">ตกลง</button>
                      <a href="staff_product.php" class="btn btn-danger">ยกเลิก</a>
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
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- PHP INSERT -->
<?php

//เช็ค input ที่มาจาก form
if (isset($_POST['product_name']) && isset($_POST['ref_cate_id']) && isset($_POST['product_price'])) {
  try {
    // ประกาศตัวแปรรับค่าจากฟอร์ม
    $ref_cate_id = $_POST['ref_cate_id'];
    $product_name = $_POST['product_name'];
    $product_detail = $_POST['product_detail'];
    $product_qty = 0;
    $product_price = $_POST['product_price'];

    //สร้างตัวแปรวันที่เพื่อเอาไปตั้งชื่อไฟล์ใหม่
    $date1 = date("Ymd_His");

    //สร้างตัวแปรสุ่มตัวเลขเพื่อเอาไปตั้งชื่อไฟล์ที่อัพโหลดไม่ให้ชื่อไฟล์ซ้ำกัน
    $numrand = (mt_rand());
    $product_img = (isset($_POST['product_img']) ? $_POST['product_img'] : '');
    $upload = $_FILES['product_img']['name'];

    //มีการอัพโหลดไฟล์
    if ($upload != '') {
      //ตัดขื่อเอาเฉพาะนามสกุล
      $typefile = strrchr($_FILES['product_img']['name'], ".");

      //สร้างเงื่อนไขตรวจสอบนามสกุลของไฟล์ที่อัพโหลดเข้ามา
      if ($typefile == '.jpg' || $typefile  == '.jpeg' || $typefile  == '.png') {

        //โฟลเดอร์ที่เก็บไฟล์
        $path = "../assets/product_img/"; //ยังไม่ได้

        //ตั้งชื่อไฟล์ใหม่เป็น สุ่มตัวเลข+วันที่
        $newname = $numrand . $date1 . $typefile;
        $path_copy = $path . $newname;

        //คัดลอกไฟล์ไปยังโฟลเดอร์
        move_uploaded_file($_FILES['product_img']['tmp_name'], $path_copy);

        //sql insert
        $sqlinsertProduct = $condb->prepare("INSERT INTO tbl_product (product_name , product_detail, product_qty, product_price, product_img, ref_cate_id) 
        VALUES (:product_name, :product_detail, :product_qty, :product_price, '$newname', :ref_cate_id)");

        //blindParam
        $sqlinsertProduct->bindParam(':product_name', $product_name, PDO::PARAM_STR);
        $sqlinsertProduct->bindParam(':product_detail', $product_detail, PDO::PARAM_STR);
        $sqlinsertProduct->bindParam(':product_qty', $product_qty, PDO::PARAM_INT);
        $sqlinsertProduct->bindParam(':product_price', $product_price, PDO::PARAM_STR);
        $sqlinsertProduct->bindParam(':ref_cate_id', $ref_cate_id, PDO::PARAM_INT);

        $resultProduct = $sqlinsertProduct->execute();

        $condb = null; //close connect db

        //เงื่อนไขตรวจสอบการเพิ่มข้อมูล
        if ($resultProduct) {
          echo '<script>
                      setTimeout(function() {
                        swal({
                            title: "เพิ่มข้อมูลสำเร็จ",
                            type: "success"
                        }, function() {
                            window.location = "staff_product.php"; //หน้าที่ต้องการให้กระโดดไป
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
                                window.location = "staff_product.php?act=insert"; //หน้าที่ต้องการให้กระโดดไป
                            });
                          }, 1);
                      </script>';
      } //else ของเช็คนามสกุลไฟล์

    } // if($upload !='')
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
                        window.location = "staff_product.php"; //หน้าที่ต้องการให้กระโดดไป
                    });
                  }, 1);
              </script>';
  }
} // isset
?>
<!-- END PHP INSERT -->