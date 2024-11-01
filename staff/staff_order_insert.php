<?php

// คิวรี่ข้อมูลมาแสดงในตาราง
$selectProd = $condb->prepare("SELECT p.product_id, p.product_name, p.product_qty, p.product_price, p.product_img, COALESCE(c.cate_name, 'ไม่มีหมวดหมู่') AS cate_name
FROM tbl_product as p 
LEFT JOIN tbl_category AS c 
ON p.ref_cate_id = c.cate_id
GROUP BY p.product_id;");
$selectProd->execute();

// fetchAll แสดงทุกรายการ
$resulProd = $selectProd->fetchAll();

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>เพิ่มข้อมูล Order</h1>
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
                  <div class="row">
                    <div class="col-md-3 mb-3">
                      <label>เลือกสินค้า</label>
                      <select name="product_id" class="form-control">
                        <option value="" disabled>-- เลือกข้อมูล --</option>
                        <?php foreach ($resulProd as $row) { ?>
                          <option value="<?php echo $row['product_id']; ?>">
                            <?php echo $row['product_name']; ?>
                          </option>
                        <?php } ?>
                      </select>
                    </div>

                    <div class="col-md-2 mb-3">
                      <label>จำนวน</label>
                      <input type="number" name="product_qty" class="form-control" value="1" min="1">
                    </div>

                    <div class="col-md-3 mb-3">
                      <button type="submit" name="submit-additem" class="btn btn-primary" style="margin-top: 30px;">เพิ่มสินค้า</button>
                      <button type="submit" name="clear-order" class="btn btn-danger" style="margin-top: 30px;">ล้างสินค้าในรายการ</button>
                      <?php
                      if (isset($_POST['clear-order'])) {
                        // ลบสินค้าทั้งหมดในรายการ แต่ยังแสดงตารางเปล่า
                        unset($_SESSION['productItem'], $_SESSION['productItemId']);

                        echo '<script>
                        setTimeout(function() {
                          swal({
                              title: "ล้างสินค้าทั้งหมดในรายการเรียบร้อยแล้ว",
                              type: "success"
                          }, function() {
                              window.location.href="staff_order.php?act=insert"; //หน้าที่ต้องการให้กระโดดไป
                          });
                        }, 1);
                      </script>';
                      }
                      ?>
                    </div>
                  </div>
                </div>
              </form>
            </div>

            <!-- staff_order_table.php -->
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th width="5%" class="text-center">No.</th>
                  <th width="40%">ชื่อสินค้า</th>
                  <th width="15%">หมวดหมู่</th> <!-- เพิ่มคอลัมน์หมวดหมู่ -->
                  <th width="15%" class="text-center">จำนวน</th>
                  <th width="15%" class="text-center">ราคา</th>
                  <th width="15%" class="text-center">รวมราคา</th>
                  <th width="10%" class="text-center">ลบ</th>
                </tr>
              </thead>

              <tbody>
                <?php

                if (!isset($_SESSION['productItem'])) {
                  $_SESSION['productItem'] = [];
                }

                if (!isset($_SESSION['productItemId'])) {
                  $_SESSION['productItemId'] = [];
                }

                // เช็คถ้ามีการส่งข้อมูลเพื่อลงสินค้า
                if (!isset($_SESSION['productItemId'])) {
                  $_SESSION['productItemId'] = [];
                }

                $totalPrice = 0;
                foreach ($_SESSION['productItem'] as $key => $item) {
                  $totalPriceItem = $item['product_price'] * $item['quantity'];
                  $totalPrice += $totalPriceItem;
                ?>
                  <tr>
                    <td class="text-center"><?php echo $key + 1; ?></td>
                    <td><?php echo $item['product_name']; ?></td>
                    <td><?php echo $item['cate_name']; ?></td>
                    <td style="text-align: center;">
                      <div class="input-group" style="display: flex; justify-content: center; align-items: center;">
                        <input type="hidden" value="<?php echo $item['product_id']; ?>" class="product-id">
                        <button type="button" class="btn btn-danger btn-decrement" style="height: 33px; width: 33px;">-</button>
                        <input type="text" class="text-center product-quantity" value="<?php echo $item['quantity']; ?>" readonly style="height: 33px; width: 45px;">
                        <button type="button" class="btn btn-primary btn-increment" style="height: 33px; width: 33px;">+</button>
                      </div>
                    </td>
                    <td class="text-center"><?php echo number_format($item['product_price'], 2); ?></td>
                    <td class="text-center total-item-price"><?php echo number_format($item['product_price'] * $item['quantity'], 2); ?></td>
                    <td>
                      <form method="post">
                        <input type="hidden" name="remove-item-id" value="<?php echo $item['product_id']; ?>">
                        <button type="submit" class="btn btn-danger btn-sm">ลบ</button>
                        <?php
                        // เช็คถ้ามีการส่งข้อมูลเพื่อลบสินค้า
                        if (isset($_POST['remove-item-id'])) {
                          $removeId = $_POST['remove-item-id'];

                          // ค้นหาและลบสินค้าที่ระบุในเซสชัน
                          foreach ($_SESSION['productItem'] as $key => $item) {
                            if ($item['product_id'] == $removeId) {
                              unset($_SESSION['productItem'][$key]);
                              unset($_SESSION['productItemId'][array_search($removeId, $_SESSION['productItemId'])]); // ลบ ID ของสินค้า
                              echo '<script>
                                    setTimeout(function() {
                                        swal({
                                            title: "ลบสินค้าสำเร็จ",
                                            type: "success"
                                        }, function() {
                                            window.location = "staff_order.php?act=insert"; //หน้าที่ต้องการให้กระโดดไป
                                        });
                                    }, 1);
                                </script>';
                              break;
                            }
                          }
                        }

                        ?>
                      </form>
                    </td>
                  </tr>
                <?php
                }
                ?>
              </tbody>

              <tfoot>
                <tr>
                  <td colspan="5" class="text-right"><strong>รวมทั้งหมด:</strong></td>
                  <td id="total-price"><?php echo number_format($totalPrice, 2); ?></td>
                  <td></td>
                </tr>
              </tfoot>
            </table>

            <hr>
            <form method="post" action="staff_order.php?act=create">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-3 mb-3">
                    <label>วิธีชำระ</label>
                    <select name="payment_mode" class="form-control">
                      <option value="รอดำเนินการ"selected>รอดำเนินการ</option>
                    </select>
                  </div>

                  <div class="col-md-2 mb-3">
                    <label>กรอกเบอร์โทรลูกค้า</label>
                    <input type="text" name="tel" class="form-control" value="" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
                    <small class="form-text text-muted">กรุณากรอกเบอร์โทร 10 หลัก</small>
                  </div>

                  <div class="col-md-3 mb-3">
                    <button type="submit" name="submit-order" class="btn btn-primary" style="margin-top: 30px;width: 70%;">ทำรายการ</button>
                  </div>

                </div>
              </div>
            </form>
          </div>

        </div>

      </div>

    </div>

  </section>

</div>

<!-- js ปุ่มเพิ่มจำนวนสินต้าในตาราง -->
<script>
  $(document).ready(function() {
    $('.btn-increment').on('click', function() {
      var $row = $(this).closest('tr');
      var productId = $row.find('.product-id').val();
      var $qtyField = $row.find('.product-quantity');
      var currentQty = parseInt($qtyField.val());

      updateQuantity(productId, currentQty + 1); // อัปเดตจำนวนสินค้า
    });

    $('.btn-decrement').on('click', function() {
      var $row = $(this).closest('tr');
      var productId = $row.find('.product-id').val();
      var $qtyField = $row.find('.product-quantity');
      var currentQty = parseInt($qtyField.val());

      if (currentQty > 1) {
        updateQuantity(productId, currentQty - 1); // อัปเดตจำนวนสินค้า
      }
    });

    function updateQuantity(productId, newQty) {
      $.ajax({
        url: 'update_quantity.php',
        type: 'POST',
        data: {
          product_id: productId,
          product_qty: newQty
        },
        success: function(response) {
          var data = JSON.parse(response);

          if (data.status === 'success') {
            var $row = $('input[value="' + productId + '"]').closest('tr');

            // อัปเดตจำนวนสินค้าในตาราง
            $row.find('.product-quantity').val(newQty);

            // อัปเดตราคาสินค้ารายการนั้น
            $row.find('.total-item-price').text(data.total_item_price.toFixed(2));

            // อัปเดตราคารวมทั้งหมดด้านล่าง
            $('#total-price').text(data.total_price.toFixed(2));
          } else {
            alert(data.message);
          }
        },
        error: function() {
          alert('เกิดข้อผิดพลาดในการอัปเดตจำนวนสินค้า');
        }
      });
    }

  });
</script>
<!-- js ปุ่มเพิ่มจำนวนสินต้าในตาราง -->

<!-- PHP ส่งสินส่งเข้ารายการ -->
<?php
// เช็คถ้ามีการส่งข้อมูลเพื่อลงสินค้า
if (isset($_POST['submit-additem'])) {
  try {
    // รับค่าจากฟอร์ม
    $product_id = $_POST['product_id'];
    $product_qty = $_POST['product_qty'];

    // ตรวจสอบว่าผู้ใช้ได้เลือกสินค้าหรือไม่
    if (empty($product_id)) {
      echo '<script>
                  setTimeout(function() {
                      swal({
                          title: "กรุณาเลือกสินค้า",
                          type: "warning"
                      }, function() {
                          window.location = "staff_order.php?act=insert"; //หน้าที่ต้องการให้กระโดดไป
                      });
                  }, 1);
              </script>';
      exit;
    }

    // ตรวจสอบจำนวนสินค้าว่าถูกต้องหรือไม่
    if ($product_qty <= 0) {
      echo '<script>
                  setTimeout(function() {
                      swal({
                          title: "กรุณากรอกจำนวนสินค้าที่ถูกต้อง",
                          type: "warning"
                      }, function() {
                          window.location = "staff_order.php?act=insert"; //หน้าที่ต้องการให้กระโดดไป
                      });
                  }, 1);
              </script>';
      exit;
    }

    // ตรวจสอบการเชื่อมต่อ PDO
    $stmt = $condb->prepare("SELECT p.product_id, p.product_name, p.product_qty, p.product_price, 
          p.product_img, COALESCE(c.cate_name, 'ไม่มีหมวดหมู่') AS cate_name
          FROM tbl_product AS p 
          LEFT JOIN tbl_category AS c ON p.ref_cate_id = c.cate_id
          WHERE p.product_id = :product_id
          LIMIT 1");
    $stmt->execute(['product_id' => $product_id]);

    // เช็คว่ามีสินค้าหรือไม่
    if ($stmt->rowCount() > 0) {
      $row = $stmt->fetch(PDO::FETCH_ASSOC);

      // ตรวจสอบจำนวนในคลัง
      if ($product_qty > $row['product_qty']) {
        echo '<script>
                  setTimeout(function() {
                      swal({
                          title: "จำนวนที่เพิ่มเกินกว่าที่มีในคลัง",
                          type: "warning"
                      }, function() {
                          window.location = "staff_order.php?act=insert"; //หน้าที่ต้องการให้กระโดดไป
                      });
                  }, 1);
              </script>';
        exit;
      }

      // สร้าง array สำหรับเก็บข้อมูลสินค้า
      $productData = [
        'product_id' => $row['product_id'],
        'product_name' => $row['product_name'],
        'product_img' => $row['product_img'],
        'product_price' => $row['product_price'],
        'quantity' => $product_qty,
        'cate_name' => isset($row['cate_name']) ? $row['cate_name'] : 'ไม่มีหมวดหมู่'
      ];

      // ตรวจสอบว่าสินค้าเคยถูกเพิ่มไปแล้วหรือไม่
      if (!in_array($row['product_id'], $_SESSION['productItemId'])) {
        // ถ้ายังไม่มีสินค้าในรายการ ให้เพิ่มลงไปใหม่
        $_SESSION['productItemId'][] = $row['product_id'];
        $_SESSION['productItem'][] = $productData;
      } else {
        // อัปเดตจำนวนสินค้าที่มีอยู่แล้ว
        foreach ($_SESSION['productItem'] as $key => $prodSessionItem) {
          if ($prodSessionItem['product_id'] == $row['product_id']) {
            // ตรวจสอบจำนวนที่มีในคลังอีกครั้งก่อนอัปเดต
            $currentQty = $prodSessionItem['quantity'] + $product_qty;
            if ($currentQty > $row['product_qty']) {
              echo '<script>
                              setTimeout(function() {
                                  swal({
                                      title: "จำนวนที่เพิ่มเกินกว่าที่มีในคลัง",
                                      type: "warning"
                                  }, function() {
                                      window.location = "staff_order.php?act=insert"; //หน้าที่ต้องการให้กระโดดไป
                                  });
                              }, 1);
                          </script>';
              exit;
            }
            // อัปเดตจำนวนสินค้า
            $_SESSION['productItem'][$key]['quantity'] += $product_qty;
          }
        }
      }

      // เพิ่มแจ้งเตือนเมื่อเพิ่มสินค้าเรียบร้อยแล้ว
      echo '<script>
              setTimeout(function() {
                  swal({
                      title: "เพิ่มสินค้าเรียบร้อยแล้ว",
                      type: "success"
                  }, function() {
                      window.location = "staff_order.php?act=insert";
                  });
              }, 500);
          </script>';
    } else {
      echo '<script>
              setTimeout(function() {
                  swal({
                      title: "ไม่พบข้อมูลสินค้า",
                      type: "warning"
                  }, function() {
                      window.location = "staff_order.php?act=insert"; //หน้าที่ต้องการให้กระโดดไป
                  });
              }, 1);
          </script>';
      exit;
    }
  } catch (PDOException $e) {
    echo '<script>alert("เกิดข้อผิดพลาด: ' . $e->getMessage() . '");</script>';
  }
}

?>
<!-- END PHP  -->
 