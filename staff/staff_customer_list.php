<?php
//คิวรี่ข้อมูลมาแสดงในตาราง
$resultCus = $condb->prepare("SELECT* FROM tbl_customer");
$resultCus->execute();

//fetchAll แสดงทุกรายการ
$resultCus = $resultCus->fetchAll();

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>จัดการข้อมูล Customer
            <a href="staff_customer.php?act=insert" class="btn btn-primary">เพิ่มข้อมูล</a>
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
                    <th width="30%">ชื่อ </th>
                    <th width="25%" class="">ที่อยู่</th>
                    <th width="20%">เบอร์โทร</th>
                    <th width="10%" class="text-center">แก้ไข</th>
                    <th width="10%" class="text-center">ลบ</th>
                  </tr>
                </thead>

                <tbody>

                  <?php
                  $i = 1; //เลขลำดับ
                  ?>

                  <!-- ลูปข้อมูลในตาราง customer ออกมาแสดง -->
                  <?php foreach ($resultCus as $row) { ?>

                    <tr>
                      <td align="center"> <?php echo $i++ ?> </td>
                      <td>
                        <?= $row['name']; ?>
                      </td>
                      <td>
                        <?= $row['address']; ?>
                      </td>

                      <td>
                        <?= $row['tel']; ?>
                      </td>

                      <td align="center"><a href="staff_customer.php?cus_id=<?= $row['cus_id']; ?>
                      &act=edit" class="btn btn-warning btn-sm">แก้ไข</a></td>

                      <td align="center"><a href="staff_customer.php?cus_id=<?= $row['cus_id']; ?>
                      &act=delete" class="btn btn-danger btn-sm" onclick="return confirm('ยืนยันการลบข้อมูล ? **หากลบข้อมูลนี้ข้อมูลการสั่งซื้อที่เกี่ยวข้องจะหายไปเเล้วกู้คืนไม่ได้**');">ลบ</a></td>
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