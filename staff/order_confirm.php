<?php
if (isset($_SESSION['customer']) && isset($_SESSION['productItems']) && isset($_SESSION['totalPrice']) && isset($_SESSION['billNumber'])) {
    // รับค่าจากเซสชันและแปลงเป็นข้อความที่ปลอดภัย
    $customerId = htmlspecialchars($_SESSION['customer']['cus_id']);
    $billNumber = htmlspecialchars($_SESSION['billNumber']);
    $totalPrice = htmlspecialchars($_SESSION['totalPrice']);
    $orderDate = htmlspecialchars($_SESSION['orderDate']);
    $paymentMode = htmlspecialchars($_SESSION['payment_mode']);
    $orderStatus = htmlspecialchars($_SESSION['orderStatus']);
    $orderPlacedById = htmlspecialchars($_SESSION['username']);

    // เชื่อมต่อฐานข้อมูล
    try {
        $condb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $condb->beginTransaction();

        // Insert into tbl_order
        $sqlInsertOrder = "INSERT INTO tbl_order (ref_cus_id, bill_number, total_price, order_date, order_status, payment_mode, order_placed_by_id)
            VALUES (:customerId, :billNumber, :totalPrice, :orderDate, :orderStatus, :paymentMode, :orderPlacedById)";
        $stmtInsertOrder = $condb->prepare($sqlInsertOrder);

        // Binding parameters
        $stmtInsertOrder->bindParam(':customerId', $customerId);
        $stmtInsertOrder->bindParam(':billNumber', $billNumber);
        $stmtInsertOrder->bindParam(':totalPrice', $totalPrice);
        $stmtInsertOrder->bindParam(':orderDate', $orderDate);
        $stmtInsertOrder->bindParam(':orderStatus', $orderStatus);
        $stmtInsertOrder->bindParam(':paymentMode', $paymentMode);
        $stmtInsertOrder->bindParam(':orderPlacedById', $orderPlacedById);

        // Execute the order insert
        $stmtInsertOrder->execute();

        // Prepare the SQL statement for inserting into tbl_order_item
        $sqlInsertOrderItem = "INSERT INTO tbl_order_item (ref_bill_number, ref_product_id, price, quantity)
            VALUES (:refBillNumber, :refProductId, :price, :quantity)";
        $stmtInsertOrderItem = $condb->prepare($sqlInsertOrderItem);

        // เตรียมคำสั่ง SQL สำหรับการตรวจสอบจำนวนสินค้าในคลัง
        $sqlCheckStock = "SELECT product_id, product_qty FROM tbl_product WHERE product_id = :productId";
        $stmtCheckStock = $condb->prepare($sqlCheckStock);

        // Loop through product items in the session
        foreach ($_SESSION['productItems'] as $item) {
            // Get the product details
            $refProductId = htmlspecialchars($item['product_id']); // Assume you have 'product_id' in your item
            $price = htmlspecialchars($item['product_price']);
            $quantity = htmlspecialchars($item['quantity']);

            // Binding parameters for the order item
            $stmtInsertOrderItem->bindParam(':refBillNumber', $billNumber); // ใช้ bill_number แทน
            $stmtInsertOrderItem->bindParam(':refProductId', $refProductId);
            $stmtInsertOrderItem->bindParam(':price', $price);
            $stmtInsertOrderItem->bindParam(':quantity', $quantity);

            // Execute the statement for each product item
            $stmtInsertOrderItem->execute();

            // ตรวจสอบจำนวนสินค้าในคลัง
            $stmtCheckStock->bindParam(':productId', $refProductId);
            $stmtCheckStock->execute();
            $stockData = $stmtCheckStock->fetch(PDO::FETCH_ASSOC);

            if ($stockData) {
                $currentStock = $stockData['product_qty'];

                // เช็คว่ามีสินค้าเพียงพอในคลังหรือไม่
                if ($currentStock >= $quantity) {
                    // ลดจำนวนสินค้าในคลัง
                    $newStock = $currentStock - $quantity;

                    // เตรียมคำสั่ง SQL สำหรับการอัพเดทจำนวนสินค้าในคลัง
                    $sqlUpdateStock = "UPDATE tbl_product SET product_qty = :newStock WHERE product_id = :productId";
                    $stmtUpdateStock = $condb->prepare($sqlUpdateStock);

                    // Binding parameters
                    $stmtUpdateStock->bindParam(':newStock', $newStock);
                    $stmtUpdateStock->bindParam(':productId', $refProductId);

                    // Execute การอัพเดท
                    $stmtUpdateStock->execute();
                } else {
                    // แจ้งเตือนหากจำนวนสินค้าในคลังไม่เพียงพอ
                    throw new Exception("สินค้า '{$item['product_name']}' จำนวน $quantity ชิ้น ไม่เพียงพอในคลัง");
                }
            }
        }

        // Commit the transaction
        $condb->commit();

        unset($_SESSION['customer']);
        unset($_SESSION['productItems']);
        unset($_SESSION['totalPrice']);
        unset($_SESSION['billNumber']);
        unset($_SESSION['orderDate']);
        unset($_SESSION['payment_mode']);
        unset($_SESSION['orderStatus']);
        unset($_SESSION['productItem'], $_SESSION['productItemId']);

        echo '<script>
        setTimeout(function() {
            swal({
                title: "ทำรายการสำเสร็จ",
                type: "success"
            }, function() {
                window.location = "staff_order.php"; // หน้าที่ต้องการให้กระโดดไป
            });
        }, 1);
      </script>';


        exit;

    } catch (PDOException $e) {
        // Rollback if an error occurs
        $condb->rollBack();
        // Show error message
        echo '<script>
                setTimeout(function() {
                    swal({
                        title: "เกิดข้อผิดพลาด",
                        text: "' . htmlspecialchars($e->getMessage()) . '",
                        type: "error"
                    }, function() {
                        window.location = "staff_order.php?act=insert"; // หน้าที่ต้องการให้กระโดดไป
                    });
                }, 1);
              </script>';
    }
} else {
    echo "ไม่มีข้อมูลการสั่งซื้อ";
}
