<?php
// คิวรี่ข้อมูลสินค้าจาก tbl_product
$sqlqueryProduct = $condb->prepare("SELECT product_id, product_name FROM tbl_product");
$sqlqueryProduct->execute();

// fetchAll แสดงทุกรายการ
$resultProducts = $sqlqueryProduct->fetchAll();

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>เพิ่มข้อมูล Stock In</h1>
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
                    <label class="col-sm-2">เลือกสินค้า</label>
                    <div class="col-sm-4">
                      <select name="product_id" class="form-control" required>
                        <option value="">-- เลือกสินค้า --</option>
                        <?php foreach ($resultProducts as $row) { ?>
                          <option value="<?php echo $row['product_id'] ?>">-- <?php echo $row['product_name'] ?> --</option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-2">จำนวนที่เข้า</label>
                    <div class="col-sm-4">
                      <input type="number" name="quantity" class="form-control" value="1" min="1" required>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-2"></label>
                    <div class="col-sm-4">
                      <button type="submit" name="submit-stock-in" class="btn btn-primary">บันทึก Stock In</button>
                      <a href="staff_product.php?act=storckin" class="btn btn-danger">ยกเลิก</a>
                    </div>
                  </div>

                </div>
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

if (isset($_POST['submit-stock-in'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // ตรวจสอบว่าผู้ใช้ได้เลือกสินค้าหรือไม่
    if (empty($product_id) || $quantity <= 0) {
        echo '<script>alert("กรุณาเลือกสินค้าและกรอกจำนวนที่ถูกต้อง");</script>';
        exit;
    }

    // บันทึกข้อมูลลงใน tbl_stock_in
    $stmt = $condb->prepare("INSERT INTO tbl_stock_in (product_id, quantity) VALUES (:product_id, :quantity)");
    $stmt->execute(['product_id' => $product_id, 'quantity' => $quantity]);

    // อัปเดตตารางสินค้าเพื่อเพิ่มจำนวนในคลัง
    $stmt = $condb->prepare("UPDATE tbl_product SET product_qty = product_qty + :quantity WHERE product_id = :product_id");
    $stmt->execute(['quantity' => $quantity, 'product_id' => $product_id]);

    echo '<script>
                      setTimeout(function() {
                        swal({
                            title: "บันทึกข้อมูล Stock In เรียบร้อยแล้ว",
                            type: "success"
                        }, function() {
                            window.location = "staff_product.php?act=storckin"; //หน้าที่ต้องการให้กระโดดไป
                        });
                      }, 1);
                  </script>';
}

?>
