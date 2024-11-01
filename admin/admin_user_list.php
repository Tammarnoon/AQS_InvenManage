<?php
//คิวรี่ข้อมูลมาแสดงในตาราง
$sqlqueryUser = $condb->prepare("SELECT* FROM tbl_user");
$sqlqueryUser->execute();

//fetchAll แสดงทุกรายการ
$resultUser = $sqlqueryUser->fetchAll();

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>จัดการข้อมูล User
            <a href="admin_user.php?act=insert" class="btn btn-primary">เพิ่มข้อมูล</a>
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
                    <th width="10%">ภาพ</th>
                    <th width="35%">ชื่อ - นามสกุล </th>
                    <th width="10%" class="">Username</th>
                    <th width="10%" class="">Level</th>
                    <th width="10%" class="text-center">แก้รหัส</th>
                    <th width="10%" class="text-center">แก้ไข</th>
                    <th width="10%" class="text-center">ลบ</th>
                  </tr>
                </thead>

                <tbody>

                  <?php
                  $i = 1; //เลขลำดับ
                  ?>

                  <!-- ลูปข้อมูลในตาราง user ออกมาแสดง -->
                  <?php foreach ($resultUser as $row) { ?>

                    <tr>
                      <td align="center"> <?php echo $i++ ?> </td>
                      <td>
                        <?php if (!empty($row['user_img'])): ?>
                          <img src="../assets/user_img/<?= htmlspecialchars($row['user_img']); ?>" width="70px">
                        <?php else: ?>
                          <img src="../assets/user_img/default.jpg" width="70px" alt="No Image Available">
                        <?php endif; ?>
                      </td>
                      <td>
                        <?= $row['title_name'] . ' ' . $row['name'] . ' ' . $row['surname']; ?>
                      </td>
                      <td>
                        <?= $row['username']; ?>
                      </td>

                      <td>
                        <?= $row['user_level']; ?>
                      </td>

                      <td align="center"><a href="admin_user.php?id=<?= $row['id']; ?>
                      &act=password" class="btn btn-success btn-sm">แก้รหัส</a></td>

                      <td align="center"><a href="admin_user.php?id=<?= $row['id']; ?>
                      &act=edit" class="btn btn-warning btn-sm">แก้ไข</a></td>

                      <td align="center"><a href="admin_user.php?id=<?= $row['id']; ?>
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