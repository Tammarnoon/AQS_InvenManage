<?php
// ดึงข้อมูลจากฐานข้อมูล Stock-in
$selectStockIn = $condb->prepare("
    SELECT si.id, p.product_name, si.quantity, si.date_in, c.cate_name
    FROM tbl_stock_in AS si
    JOIN tbl_product AS p ON si.product_id = p.product_id
    JOIN tbl_category AS c ON p.ref_cate_id = c.cate_id
    ORDER BY si.date_in DESC
");
$selectStockIn->execute();
$resulStockIn = $selectStockIn->fetchAll();

// ดึงข้อมูลสินค้าที่หมดสต็อก
$selectOutOfStock = $condb->prepare("
    SELECT p.product_name, p.product_qty, c.cate_name
    FROM tbl_product AS p
    JOIN tbl_category AS c ON p.ref_cate_id = c.cate_id
    WHERE p.product_qty = 0
");
$selectOutOfStock->execute();
$resultOutOfStock = $selectOutOfStock->fetchAll();
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>จัดการข้อมูล Stock-in
                        <a href="staff_product.php?act=storckInsert" class="btn btn-primary">เพิ่มข้อมูล</a>
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
                    <!-- ตารางสินค้าที่หมดสต็อก -->
                    <div class="card">
                        <div class="card-body">
                            <h3>สินค้าที่หมดสต็อก</h3>
                            <table id="outOfStockTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>ชื่อสินค้า</th>
                                        <th>จำนวนที่มี</th>
                                        <th>หมวดหมู่</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($resultOutOfStock as $key => $row) { ?>
                                        <tr>
                                            <td><?php echo $key + 1; ?></td>
                                            <td><?php echo $row['product_name']; ?></td>
                                            <td><?php echo $row['product_qty']; ?></td>
                                            <td><?php echo $row['cate_name']; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
            
            <div class="row">
                <div class="col-12">
                    <!-- ตาราง Stock-in -->
                    <div class="card">
                        <div class="card-body">
                            <h3>สินค้าเข้า</h3>
                            <table id="stockInTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>ชื่อสินค้า</th>
                                        <th>จำนวนที่เข้า</th>
                                        <th>วันที่</th>
                                        <th>หมวดหมู่</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($resulStockIn as $key => $row) { ?>
                                        <tr>
                                            <td><?php echo $key + 1; ?></td>
                                            <td><?php echo $row['product_name']; ?></td>
                                            <td><?php echo $row['quantity']; ?></td>
                                            <td><?php echo $row['date_in']; ?></td>
                                            <td><?php echo $row['cate_name']; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
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

<!-- รวม jQuery และ DataTables -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

<script>
$(document).ready(function() {
    var outOfStockTable = $('#outOfStockTable').DataTable({
        "searching": true,
        "pageLength": 10,
        "lengthMenu": [5, 10, 25, 50],
        "drawCallback": function() {
            // ปรับแต่ง DOM เพื่อให้ปุ่ม pagination ปรากฏขึ้นตามต้องการ
            customizePagination($(this));
        }
    });
    
    var stockInTable = $('#stockInTable').DataTable({
        "searching": true,
        "pageLength": 10,
        "lengthMenu": [5, 10, 25, 50],
        "drawCallback": function() {
            // ปรับแต่ง DOM เพื่อให้ปุ่ม pagination ปรากฏขึ้นตามต้องการ
            customizePagination($(this));
        }
    });

    function customizePagination(table) {
        // ลบคลาส paginate_button และ page-item
        table.closest('.dataTables_wrapper').find('.paginate_button').removeClass('paginate_button page-item');

        // สร้างปุ่มใหม่
        table.closest('.dataTables_wrapper').find('.dataTables_paginate').each(function() {
            var $this = $(this);
            $this.find('a').each(function() {
                $(this).addClass('btn btn-primary'); // เพิ่มคลาสปุ่ม
                $(this).removeClass('disabled'); // ลบคลาส disabled
                // ปรับแต่ง margin ของปุ่ม
                $(this).css('margin', '0 5px'); // เพิ่มระยะห่างระหว่างปุ่ม
            });
        });
    }
});
</script>


<style>
    /* ปรับความกว้างของ dropdown สำหรับการเลือกจำนวนแถว */
    #outOfStockTable_length select,
    #stockInTable_length select {
        width: 45px; /* ปรับความกว้างที่ต้องการ */
    }

    /* ปรับระยะห่างระหว่างปุ่ม pagination */
    .paginate_button {
        margin: 10px; /* ปรับระยะห่างตามที่ต้องการ */
    }

    /* เปลี่ยนสีตัวหนังสือในปุ่ม */
    .dataTables_wrapper .dataTables_paginate .btn {
        color: black; /* เปลี่ยนเป็นสีดำ หรือ gray สำหรับสีเทา */
    }
</style>

