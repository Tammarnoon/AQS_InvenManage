<?php
// query tbl_product as p INNER JOIN tbl_category
$sqlQueryeditProduct_Detail = $condb->prepare("SELECT p.*, COALESCE(c.cate_name, 'ไม่มีหมวดหมู่') AS cate_name 
FROM tbl_product as p 
LEFT JOIN tbl_category AS c 
ON p.ref_cate_id = c.cate_id
WHERE p.product_id =:product_id");


// Single row query แสดง 1 รายการ FETCH_ASSOC
// bindParam
$sqlQueryeditProduct_Detail->bindParam(':product_id', $_GET['product_id'], PDO::PARAM_INT);
$sqlQueryeditProduct_Detail->execute();
$rowProduct_dail = $sqlQueryeditProduct_Detail->fetch(PDO::FETCH_ASSOC);


//query check
// echo '<pre>';
// print_r($rowProduct_dail);
// echo $sqlQueryeditProduct_Detail->rowCount();
// exit;

//เงื่อนไขตรวจสอบ query
if ($sqlQueryeditProduct_Detail->rowCount() == 0) {
  // echo 'ไม่ผ่าน';
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
  exit;
}

//คิวรี่ข้อมูลมาแสดงในตาราง tbl_category
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
          <h1>แก้ไขข้อมูล Product</h1>
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

                        <option value="<?php echo $rowProduct_dail['ref_cate_id'] ?>">-- <?php echo $rowProduct_dail['cate_name'] ?> --</option>

                        <option disabled>-- เลือกข้อมูลใหม่ --</option>

                        <?php foreach ($resultproductCate as $resultproductCate) { ?>
                          <option value="<?php echo $resultproductCate['cate_id'] ?>">-- <?php echo $resultproductCate['cate_name'] ?> --</option>
                        <?php } ?>

                      </select>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-2">ชื่อสินค้า</label>
                    <div class="col-sm-4">
                      <input type="text" name="product_name" class="form-control" required placeholder="ชื่อสินค้า" value="<?php echo $rowProduct_dail['product_name']; ?>">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-2">รายละเอียดสินค้า</label>
                    <div class="col-sm-8">
                      <textarea name="product_detail" id="summernote">
                        <?php echo $rowProduct_dail['product_detail']; ?>
                      </textarea>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-2">ราคาสินค้า</label>
                    <div class="col-sm-4">
                      <input type="number" name="product_price" class="form-control" min="0" max="9999999" step="0.01" value="<?php echo $rowProduct_dail['product_price']; ?>">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-2">รูปภาพสินค้า</label>
                    <div class="col-sm-4">
                      รูปภาพเดิม
                      <br>
                      <img src="../assets/product_img/<?= $rowProduct_dail['product_img'] ?>" width="200px">
                      <br>
                      <br>
                      เลือกรูปภาพใหม่
                      <div class="input-group">
                        <div class="custom-file">
                          <input type="file" name="product_img" class="custom-file-input" id="exampleInputFile">
                          <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-2"></label>
                    <div class="col-sm-4">
                      <input type="hidden" name="product_id" value="<?php echo $rowProduct_dail['product_id']; ?>"></button>
                      <input type="hidden" name="oldImg" value="<?php echo $rowProduct_dail['product_img']; ?>"></button>
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

if (isset($_POST['product_id']) && isset($_POST['product_name']) && isset($_POST['product_price'])) {

  try {
    //ประกาศตัวแปรที่รับมาจาก formm
    $ref_cate_id = $_POST['ref_cate_id'];
    $product_name = $_POST['product_name'];
    $product_detail = $_POST['product_detail'];
    $product_price = $_POST['product_price'];
    $product_id = $_POST['product_id'];

    //เงื่อนไขการตรวจสอบการอัพโหลดไฟล์
    if ($_FILES['product_img']['name'] == '') {
      // echo'ไม่มีการอัพไฟล์';

      //sql edit
      $sqlnofileUPProduct = $condb->prepare("UPDATE tbl_product SET product_name=:product_name , product_detail=:product_detail,
      product_price=:product_price, ref_cate_id=:ref_cate_id WHERE product_id=:product_id");

      //blindParam
      $sqlnofileUPProduct->bindParam(':product_id', $product_id, PDO::PARAM_INT);
      $sqlnofileUPProduct->bindParam(':product_name', $product_name, PDO::PARAM_STR);
      $sqlnofileUPProduct->bindParam(':product_detail', $product_detail, PDO::PARAM_STR);
      $sqlnofileUPProduct->bindParam(':product_price', $product_price, PDO::PARAM_STR);
      $sqlnofileUPProduct->bindParam(':ref_cate_id', $ref_cate_id, PDO::PARAM_INT);

      $resultnofileUPProduct = $sqlnofileUPProduct->execute();

      //เงื่อนไขตรวจสอบการเพิ่มข้อมูล
      if ($resultnofileUPProduct) {
        echo '<script>
                          setTimeout(function() {
                            swal({
                                title: "แก้ข้อมูลสำเร็จ",
                                type: "success"
                            }, function() {
                                window.location = "staff_product.php"; //หน้าที่ต้องการให้กระโดดไป
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
      $product_img = (isset($_POST['product_img']) ? $_POST['product_img'] : '');

      //ตัดขื่อเอาเฉพาะนามสกุล
      $typefile = strrchr($_FILES['product_img']['name'], ".");

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
                            window.location = "staff_product.php?product_id=' . $product_id . '&act=edit"; //หน้าที่ต้องการให้กระโดดไป
                        });
                      }, 1);
                  </script>';
        exit;
      } else {
        // echo 'อัพโหลดไฟล์ถูก';

        //ลบภาพเก่า
        unlink('../assets/product_img/' . $_POST['oldImg']);

        //โฟลเดอร์ที่เก็บไฟล์
        $path = "../assets/product_img/";

        //ตั้งชื่อไฟล์ใหม่เป็น สุ่มตัวเลข+วันที่
        $newname = $numrand . $date1 . $typefile;
        $path_copy = $path . $newname;

        //คัดลอกไฟล์ไปยังโฟลเดอร์
        move_uploaded_file($_FILES['product_img']['tmp_name'], $path_copy);

        //sql edit
        $sqlfileUPProduct = $condb->prepare("UPDATE tbl_product SET product_name=:product_name , product_detail=:product_detail,
        product_price=:product_price, ref_cate_id=:ref_cate_id, product_img='$newname' WHERE product_id=:product_id");

        //blindParam
        $sqlfileUPProduct->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $sqlfileUPProduct->bindParam(':product_name', $product_name, PDO::PARAM_STR);
        $sqlfileUPProduct->bindParam(':product_detail', $product_detail, PDO::PARAM_STR);
        $sqlfileUPProduct->bindParam(':product_price', $product_price, PDO::PARAM_STR);
        $sqlfileUPProduct->bindParam(':ref_cate_id', $ref_cate_id, PDO::PARAM_INT);

        $resultfileUPProduct = $sqlfileUPProduct->execute();

        //เงื่อนไขตรวจสอบการเพิ่มข้อมูล
        if ($resultfileUPProduct) {
          echo '<script>
                          setTimeout(function() {
                            swal({
                                title: "แก้ข้อมูลสำเร็จ",
                                type: "success"
                            }, function() {
                                window.location = "staff_product.php"; //หน้าที่ต้องการให้กระโดดไป
                            });
                          }, 1);
                      </script>';
          exit;
        }
      } //check file type

    } // echo'มีการอัพไฟล์';
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
<!-- END PHP EDIT -->