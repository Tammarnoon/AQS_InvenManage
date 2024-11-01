<?php
session_start();
require_once '../config/condb.php'; // เชื่อมต่อฐานข้อมูล

if (isset($_POST['product_id']) && isset($_POST['product_qty'])) {
    $productId = $_POST['product_id'];
    $newQty = $_POST['product_qty'];

    // คิวรี่ข้อมูลเพื่อตรวจสอบจำนวนที่มีในคลัง
    $stmt = $condb->prepare("SELECT product_qty FROM tbl_product WHERE product_id = :product_id");
    $stmt->execute(['product_id' => $productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        echo json_encode(['status' => 'error', 'message' => 'ไม่พบสินค้านี้']);
        exit;
    }

    $availableQty = $product['product_qty'];

    // ตรวจสอบว่าจำนวนใหม่จะเกินจำนวนที่มีในคลังหรือไม่
    if ($newQty > $availableQty) {
        echo json_encode(['status' => 'error', 'message' => 'จำนวนสินค้าเกินที่มีในคลัง']);
        exit;
    }

    // อัปเดตจำนวนในเซสชัน
    foreach ($_SESSION['productItem'] as $key => $item) {
        if ($item['product_id'] == $productId) {
            $_SESSION['productItem'][$key]['quantity'] = $newQty;
            $totalItemPrice = $item['product_price'] * $newQty; // คำนวณราคารวมของสินค้าแต่ละรายการ
        }
    }

    // คำนวณราคารวมทั้งหมด
    $totalPrice = 0;
    foreach ($_SESSION['productItem'] as $item) {
        $totalPrice += $item['product_price'] * $item['quantity'];
    }

    // ส่งผลลัพธ์กลับไปที่ฝั่ง JavaScript
    echo json_encode([
        'status' => 'success',
        'total_price' => $totalPrice,  // ราคารวมทั้งหมดของสินค้าทั้งหมด
        'total_item_price' => $totalItemPrice // ราคารวมของสินค้าแต่ละรายการ
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'ข้อมูลไม่ถูกต้อง']);
}
