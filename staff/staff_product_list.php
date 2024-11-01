<?php
//คิวรี่ข้อมูลมาแสดงในตาราง
$sqlqueryproduct = $condb->prepare("SELECT p.product_id, p.product_name, p.product_qty, p.product_price, p.product_img, COALESCE(c.cate_name, 'ไม่มีหมวดหมู่') AS cate_name
FROM tbl_product as p 
LEFT JOIN tbl_category AS c 
ON p.ref_cate_id = c.cate_id
GROUP BY p.product_id;");

$sqlqueryproduct->execute();

//fetchAll แสดงทุกรายการ
$resultproduct = $sqlqueryproduct->fetchAll();

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>จัดการข้อมูล Product
            <a href="staff_product.php?act=insert" class="btn btn-primary">เพิ่มข้อมูล</a>
          </h1>
        </div>
      </div>
    </div>
    <!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <!-- /.card -->
          <div class="card">
            <!-- /.card-header -->
            <div class="card-body">

              <table id="example1" class="table table-bordered table-striped">

                <thead>
                  <tr>
                    <th width="5%" class="text-center">No.</th>
                    <th width="10%">ภาพสินค้า</th>
                    <th width="40%" class="">ชื่อสินค้า</th>
                    <th width="10%" class="">หมวดหมู่</th>
                    <th width="10%" class="text-center">จำนวน</th>
                    <th width="10%" class="text-center">ราคา</th>
                    <th width="5%" class="text-center">แก้ไข</th>
                    <th width="5%" class="text-center">ลบ</th>
                  </tr>
                </thead>

                <tbody>

                  <?php
                  $i = 1; //เลขลำดับ
                  ?>

                  <!-- ลูปข้อมูลในตาราง product ออกมาแสดง -->
                  <?php foreach ($resultproduct as $row) { ?>

                    <tr>
                      <td align="center"> <?php echo $i++ ?> </td>
                      <td>
                        <?php if (!empty($row['product_img'])): ?>
                          <img src="../assets/product_img/<?= htmlspecialchars($row['product_img']); ?>" width="70px">
                        <?php else: ?>
                          <!-- กรณีไม่มีรูปภาพ ใช้รูป default -->
                          <img src="../assets/product_img/default.jpg" alt="Default User Image">
                        <?php endif; ?>
                      </td>
                      <td>
                        <?= $row['product_name']; ?>
                      </td>
                      <td>
                        <?= $row['cate_name']; ?>
                      </td>
                      <td align="right">
                        <?= $row['product_qty']; ?>
                      </td>
                      <td align="right">
                        <?= number_format($row['product_price'], 2); ?>
                      </td>

                      <td align="center"><a href="staff_product.php?product_id=<?= $row['product_id']; ?>
                      &act=edit" class="btn btn-warning btn-sm">แก้ไข</a></td>

                      <td align="center"><a href="staff_product.php?product_id=<?= $row['product_id']; ?>
                      &act=delete" class="btn btn-danger btn-sm" onclick="return confirm('ยืนยันการลบข้อมูล ?');">ลบ</a></td>

                    </tr>
                  <?php } ?>
                  </tfoot>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->