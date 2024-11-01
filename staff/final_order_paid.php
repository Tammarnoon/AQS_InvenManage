<?php
// ตรวจสอบว่ามีข้อมูลบิลจากการชำระเงินหรือไม่
if (!isset($_GET['order_id'])) {
    echo "ไม่พบข้อมูลบิล";
    exit;
}

$orderId = $_GET['order_id'];

// คิวรี่เพื่อดึงข้อมูลบิลและข้อมูลลูกค้า
$selectOrder = $condb->prepare("
    SELECT o.bill_number, o.order_date, o.order_status, o.payment_mode, o.paid_money, o.money_change,
           c.name, c.tel, o.order_paid_confirm_user, o.paid_date
    FROM tbl_order AS o
    JOIN tbl_customer AS c ON o.ref_cus_id = c.cus_id
    WHERE o.id = :orderId
");
$selectOrder->bindParam(':orderId', $orderId, PDO::PARAM_INT);
$selectOrder->execute();
$orderData = $selectOrder->fetch(PDO::FETCH_ASSOC);

if (!$orderData) {
    echo "ไม่พบข้อมูลบิล";
    exit;
}

// เก็บข้อมูลบิลและลูกค้า
$billNumber = $orderData['bill_number'];
$orderDate = $orderData['paid_date'];
$orderStatus = $orderData['order_status'];
$paymentMode = $orderData['payment_mode'];
$paidMoney = $orderData['paid_money'];
$moneyChange = $orderData['money_change'];
$customerName = $orderData['name'];
$customerTel = $orderData['tel'];
$orderPaidConfirmUsername = $orderData['order_paid_confirm_user'];

// คิวรี่เพื่อดึงข้อมูลผู้ทำรายการจากตาราง tbl_user โดยใช้ username
$selectUser = $condb->prepare("
    SELECT title_name, name, surname
    FROM tbl_user
    WHERE username = :username
");
$selectUser->bindParam(':username', $orderPaidConfirmUsername, PDO::PARAM_STR);
$selectUser->execute();
$userData = $selectUser->fetch(PDO::FETCH_ASSOC);

if ($userData) {
    $userFullName = $userData['title_name'] . ' ' . $userData['name'] . ' ' . $userData['surname'];
} else {
    $userFullName = "ไม่พบข้อมูลผู้ทำรายการ";
}

// คิวรี่เพื่อดึงข้อมูลสินค้าที่สั่งซื้อ
$selectOrderItems = $condb->prepare("
    SELECT p.product_name, p.product_price, oi.quantity
    FROM tbl_order_item AS oi
    JOIN tbl_product AS p ON oi.ref_product_id = p.product_id
    WHERE oi.ref_bill_number = :billNumber
");
$selectOrderItems->bindParam(':billNumber', $billNumber, PDO::PARAM_STR);
$selectOrderItems->execute();
$orderItems = $selectOrderItems->fetchAll(PDO::FETCH_ASSOC);

if (!$orderItems) {
    echo "ไม่พบข้อมูลสินค้าในบิลนี้";
    exit;
}

// คำนวณราคาและ VAT
$vatRate = 0.07; // VAT 7%
$totalPrice = 0;
foreach ($orderItems as $item) {
    $itemTotal = $item['product_price'] * $item['quantity'];
    $totalPrice += $itemTotal;
}
$vatAmount = $totalPrice * $vatRate;
$netPrice = $totalPrice + $vatAmount;
?>

<style>
    .bill-card {
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        max-width: 600px;
        margin: auto;
        background-color: #fff;
    }

    h2,
    h4 {
        text-align: center;
    }

    .customer-info {
        margin-bottom: 20px;
    }

    .payment-summary {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
    }
</style>

<div class="bill-card">
    <h2>บิลการสั่งซื้อ</h2>
    <h4>เลขที่บิล: <?php echo $billNumber; ?></h4>
    <p><strong>วันที่:</strong> <?php echo $orderDate; ?></p>
    <p><strong>ชื่อลูกค้า:</strong> <?php echo $customerName; ?></p>
    <p><strong>เบอร์โทร:</strong> <?php echo $customerTel; ?></p>
    <p><strong>สถานะบิล:</strong> <?php echo $orderStatus; ?></p>
    <p><strong>วิธีชำระเงิน:</strong> <?php echo $paymentMode; ?></p>
    <p><strong>ชื่อผู้ทำรายการ:</strong> <?php echo $userFullName; ?></p>

    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>ลำดับ</th>
                <th>ชื่อสินค้า</th>
                <th>จำนวน</th>
                <th>ราคาต่อหน่วย</th>
                <th>รวม</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($orderItems as $key => $item) {
                $itemTotal = $item['product_price'] * $item['quantity'];
            ?>
                <tr>
                    <td><?php echo $key + 1; ?></td>
                    <td><?php echo $item['product_name']; ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td><?php echo number_format($item['product_price'], 2); ?></td>
                    <td><?php echo number_format($itemTotal, 2); ?></td>
                </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-right"><strong>รวมทั้งหมด:</strong></td>
                <td><?php echo number_format($totalPrice, 2); ?></td>
            </tr>
            <tr>
                <td colspan="4" class="text-right"><strong>VAT (7%):</strong></td>
                <td><?php echo number_format($vatAmount, 2); ?></td>
            </tr>
            <tr>
                <td colspan="4" class="text-right"><strong>ราคาสุทธิ:</strong></td>
                <td><?php echo number_format($netPrice, 2); ?></td>
            </tr>
            <tr>
                <td colspan="4" class="text-right"><strong>จำนวนเงินที่จ่าย:</strong></td>
                <td><?php echo number_format($paidMoney, 2); ?></td>
            </tr>
            <tr>
                <td colspan="4" class="text-right"><strong>เงินทอน:</strong></td>
                <td><?php echo number_format($moneyChange, 2); ?></td>
            </tr>
        </tfoot>
    </table>

    <div class="text-center mt-4">
        <button onclick="window.print()" class="btn btn-primary">พิมพ์บิล</button>
    </div>
</div>
