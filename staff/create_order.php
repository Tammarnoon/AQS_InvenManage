<?php

if (isset($_POST['submit-order'])) {

    // ตรวจสอบว่ามีข้อมูลวิธีการชำระเงิน
    if (empty($_POST['payment_mode'])) {
        echo '<script>
            setTimeout(function() {
                swal({
                    title: "กรุณาเลือกวิธีการชำระเงิน",
                    type: "warning"
                }, function() {
                    window.location = "staff_order.php?act=insert"; 
                });
            }, 1);
        </script>';
        exit;
    }

    // เก็บ payment_mode ไว้ในตัวแปร
    $paymentMode = $_POST['payment_mode'];

    // ตรวจสอบว่าได้กรอกเบอร์โทรลูกค้าหรือยัง
    if (empty($_POST['tel'])) {
        echo '<script>
            setTimeout(function() {
                swal({
                    title: "กรุณากรอกเบอร์โทรลูกค้า",
                    type: "warning"
                }, function() {
                    window.location = "staff_order.php?act=insert"; 
                });
            }, 1);
        </script>';
        exit;
    }

    // ตรวจสอบว่าความยาวของเบอร์โทรต้องเท่ากับ 10 หลัก
    if (strlen($_POST['tel']) !== 10) {
        echo '<script>
            setTimeout(function() {
                swal({
                    title: "เบอร์โทรต้องมี 10 หลัก",
                    type: "warning"
                }, function() {
                    window.location = "staff_order.php?act=insert";
                });
            }, 1);
        </script>';
        exit;
    }

    // ตรวจสอบว่าเบอร์โทรอยู่ในฐานข้อมูลหรือไม่
    $tel = $_POST['tel'];
    $stmt = $condb->prepare("SELECT * FROM tbl_customer WHERE tel = :tel");
    $stmt->bindParam(':tel', $tel);
    $stmt->execute();
    $customer = $stmt->fetch();

    // ถ้าไม่พบเบอร์ในฐานข้อมูล
    if (!$customer) {
        echo '<script>
            setTimeout(function() {
                swal({
                    title: "ไม่มีเบอร์นี้ในระบบ",
                    type: "warning"
                }, function() {
                    window.location = "staff_order.php?act=insert";
                });
            }, 1);
        </script>';
        exit;
    }

    // ตรวจสอบว่ามีสินค้าในรายการหรือไม่
    if (empty($_SESSION['productItem'])) {
        echo '<script>
            setTimeout(function() {
                swal({
                    title: "ไม่มีสินค้าในรายการ กรุณาเพิ่มสินค้า",
                    type: "warning"
                }, function() {
                    window.location = "staff_order.php?act=insert";
                });
            }, 1);
        </script>';
        exit;
    }

    // ดึงข้อมูลสินค้าจาก session
    $user_name = $_SESSION['name'];
    $user_surname = $_SESSION['surname'];
    $customerName = $customer['name'];
    $customerId = $customer['cus_id'];
    $customerTel = $customer['tel'];
    $customerAddress = $customer['address'];
    $productItems = $_SESSION['productItem'];
    $userName = $_SESSION['username'] ?? null; // ใช้ null coalescing operator เพื่อป้องกัน error
    $totalPrice = 0;
    $billDate = date('d-m-Y');

    foreach ($productItems as $product) {
        $productQty = $product['quantity'];
        $productPrice = $product['product_price'];
        $subtotal = $productQty * $productPrice;
        $totalPrice += $subtotal;
    }

    // ตรวจสอบข้อมูลก่อนส่งไปยังหน้า summary
    // สำหรับการดีบั๊ก
    // echo '<pre>';
    // echo "userId : $userName\n";
    // echo "user_name : $user_name\n";
    // echo "user_surname : $user_surname\n";
    // echo "cus_id : $customerId\n";
    // echo "Customer Name: $customerName\n";
    // echo "Customer Tel: $customerTel\n";
    // echo "Customer Address: $customerAddress\n";
    // echo "Payment Mode: $paymentMode\n"; 
    // echo "Total Price: $totalPrice\n";
    // echo "Product Items: ";
    // print_r($productItems);
    // echo '</pre>';
    // exit; 

    $_SESSION['orderDate'] = $billDate;
    $_SESSION['orderStatus'] = 'ยังไม่ได้จ่าย'; //
    $_SESSION['username'] = $userName; 
    $billNumber = 'BIL-' . date('Ymd') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);

    // เก็บข้อมูลที่จำเป็นไว้ใน session เพื่อส่งไปหน้า summary
    $_SESSION['customer'] = [
        'cus_id' => $customerId,
        'name' => $customerName,
        'tel' => $customerTel,
        'address' => $customerAddress
    ];

    $_SESSION['userFullName'] = $user_name . ' ' . $user_surname;
    $_SESSION['productItems'] = $productItems;
    $_SESSION['totalPrice'] = $totalPrice;
    $_SESSION['billNumber'] = $billNumber;
    $_SESSION['payment_mode'] = $paymentMode; 
    

    if (isset($_SESSION['customer']) && isset($_SESSION['customer']['cus_id'])) {
        $customerId = htmlspecialchars($_SESSION['customer']['cus_id']);
    } else {
        echo '<script>
            setTimeout(function() {
                swal({
                    title: "ข้อมูลลูกค้าไม่ถูกต้อง",
                    type: "error"
                }, function() {
                    window.location = "staff_order.php?act=insert";
                });
            }, 1);
        </script>';
        exit;
    }

    echo '<script>
    setTimeout(function() {
        window.location = "staff_order.php?act=summary"; 
    }, 1);
    </script>';
    exit;
}
?>
