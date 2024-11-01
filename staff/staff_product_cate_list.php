<?php
  //คิวรี่ข้อมูลมาแสดงในตาราง
  $sqlquerycategory = $condb->prepare("SELECT* FROM tbl_category ORDER BY cate_id ASC");
  $sqlquerycategory->execute();

  //fetchAll แสดงทุกรายการ
  $resultcategory = $sqlquerycategory->fetchAll();

?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>จัดการข้อมูล Category
              <a href="staff_product_cate.php?act=insert" class="btn btn-primary">เพิ่มข้อมูล</a>
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
                      <th width="10%" class="text-center">No.</th>
                      <th width="70%">หมวดหมู่ </th>
                      <th width="10%" class="text-center">แก้ไข</th>
                      <th width="10%" class="text-center">ลบ</th>
                    </tr>
                  </thead>
                  
                  <tbody>

                    <?php 
                      $i = 1;
                    ?>

                    <!-- ลูปข้อมูลในตาราง category ออกมาแสดง -->
                    <?php foreach($resultcategory as $row){ ?>

                    <tr>
                      <td align="center">
                        <?php echo $i++ ?>
                        <!-- <?=$row['cate_id'];?> -->
                      </td>

                      <td>
                          <?=$row['cate_name'];?>
                      </td>

                      <td align="center"><a href="staff_product_cate.php?cate_id=<?=$row['cate_id']; ?>
                      &act=edit" class="btn btn-warning btn-sm">แก้ไข</a></td>
                      
                      <td align="center"><a href="staff_product_cate.php?cate_id=<?=$row['cate_id']; ?>
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