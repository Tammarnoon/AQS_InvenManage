<?php
session_start();
include '../config/condb.php'; // เชื่อมต่อฐานข้อมูล

// ตรวจสอบข้อมูลที่ส่งมา
if (isset($_POST['product_id'], $_POST['product_qty'])) {
    $product_id = $_POST['product_id'];
    $new_qty = $_POST['product_qty'];

    // อัปเดตสินค้าในฐานข้อมูล
    // ตัวอย่างการอัปเดตสินค้า (คุณอาจต้องการตรวจสอบและอัปเดตตามฐานข้อมูลจริง)
    foreach ($_SESSION['productItem'] as &$item) {
        if ($item['product_id'] == $product_id) {
            $item['quantity'] = $new_qty;
            $total_item_price = $item['product_price'] * $new_qty;
            break;
        }
    }

    // คำนวณราคารวมทั้งหมด
    $total_price = array_reduce($_SESSION['productItem'], function($sum, $item) {
        return $sum + ($item['product_price'] * $item['quantity']);
    }, 0);

    // ตอบกลับข้อมูลแบบ JSON
    echo json_encode([
        'status' => 'success',
        'total_item_price' => $total_item_price,
        'total_price' => $total_price
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'ข้อมูลไม่ครบถ้วน']);
}
?>
