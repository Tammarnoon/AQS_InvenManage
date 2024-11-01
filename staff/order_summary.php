<style>
    body {
        height: 100vh;
        /* ใช้ความสูงทั้งหมดของ viewport */
    }

    .container {
        display: flex;
        justify-content: center;
        /* จัดกลางแนวนอน */
        align-items: center;
        /* จัดกลางแนวตั้ง */
        height: 100%;
        /* ใช้ความสูงทั้งหมด */
        padding: 20px;
    }

    .bill-card {
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        width: 100%;
        /* ให้บิลขยายเต็มความกว้าง */
        max-width: 600px;
        /* จำกัดความกว้างของบิล */
        display: flex;
        /* ใช้ flexbox เพื่อจัดเรียง */
        flex-direction: column;
        /* แนวตั้ง */
    }

    .customer-info {
        display: flex;
        /* ใช้ flexbox สำหรับข้อมูลลูกค้า */
        justify-content: space-between;
        /* จัดให้ข้อมูลลูกค้าอยู่ด้านซ้ายและเลขบิลด้านขวา */
        align-items: flex-start;
        /* จัดแนวข้อมูล */
        margin-bottom: 20px;
        /* เพิ่มระยะห่างด้านล่าง */
    }

    .table {
        margin-top: 20px;
        font-size: 0.9em;
        /* ปรับขนาดตัวอักษรในตาราง */
    }

    h2 {
        font-size: 1.5em;
        /* ปรับขนาดหัวเรื่อง */
        margin-bottom: -20px;
    }

    h4 {
        font-size: 1.2em;
        /* ปรับขนาดหัวเรื่องย่อย */
        margin-top: 30px;
        color: #333;
    }

    p {
        font-size: 0.9em;
        /* ปรับขนาดตัวอักษรใน paragraph */
    }

    .bill-number {
        font-size: 1em;
        /* ปรับขนาดตัวอักษรสำหรับเลขบิล */
        text-align: right;
    }

    .bill-date {
        font-size: 0.9em;
        /* ปรับขนาดตัวอักษรสำหรับวันที่ */
        margin-top: 0px;
        /* เพิ่มระยะห่างจากเลขบิล */
        text-align: right;
    }

    .payment-summary {
        font-size: 1em;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card {
        margin: 20px;
        /* เพิ่มระยะห่างรอบๆ card */
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        max-width: 700px;
        width: 100%;
    }

    .bill-card {
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 100%;
    }
</style>

<div class="container">
    <div class="card">
        <div class="bill-card">
            <h2 class="text-center">รายการออเดอร์</h2>
            <h4 class="text-center">Aquatech supply</h4>

            <?php


            if (isset($_SESSION['customer']) && isset($_SESSION['productItems']) && isset($_SESSION['totalPrice']) && isset($_SESSION['billNumber'])) {
                $customerName = htmlspecialchars($_SESSION['customer']['name']);
                $customerId = htmlspecialchars($_SESSION['customer']['cus_id']);
                $customerTel = htmlspecialchars($_SESSION['customer']['tel']);
                $customerAddress = htmlspecialchars($_SESSION['customer']['address']);
                $totalPrice = htmlspecialchars($_SESSION['totalPrice']);
                $billNumber = htmlspecialchars($_SESSION['billNumber']);
                $orderDate = htmlspecialchars($_SESSION['orderDate']);
                $paymentMode = htmlspecialchars($_SESSION['payment_mode']);
                $userFullName = htmlspecialchars($_SESSION['userFullName']);
                $totalItems = 0;
                foreach ($_SESSION['productItems'] as $item) {
                    $totalItems += $item['quantity'];
                }
            ?>
                <div class="customer-info">
                    <div>
                        <h4>ข้อมูลลูกค้า :</h4>
                        <p><strong>ชื่อ :</strong> <?= $customerName ?></p>
                        <p><strong>เบอร์:</strong> <?= $customerTel ?></p>
                    </div>
                    <div class="bill-number">
                        <h4>ข้อมูลออเดอร์ :</h4>
                        <p><strong>รหัสออเดอร์ :</strong> <?= $billNumber ?></p>
                        <p class="bill-date"><strong>วันที่ : </strong><?= $orderDate ?></p>
                        <p><strong>ชื่อผู้ทำรายการ :</strong> <?= $userFullName ?></p>
                    </div>
                </div>

                <h4>สินค้าในรายการ</h4>
                <table class="table">
                    <thead>
                        <tr>
                            <th>สินค้า</th>
                            <th>จำนวน</th>
                            <th>ราคา</th>
                            <th>ราคารวม</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($_SESSION['productItems'] as $item) {
                            $subtotal = $item['quantity'] * $item['product_price'];
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($item['product_name']) ?></td>
                                <td><?= htmlspecialchars($item['quantity']) ?></td>
                                <td><?= htmlspecialchars($item['product_price']) ?> ฿</td>
                                <td><?= htmlspecialchars($subtotal) ?> ฿</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

                <div class="payment-summary" style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <h4 style="margin-right: 20px;">วิธีการชำระเงิน: <?= $paymentMode ?></h4>
                    <div style="text-align: right;">
                        <h4>สินค้าในรายการ: <?= $totalItems ?> ชิ้น</h4>
                        <h4>รวมทั้งหมด: <span class="text"><?= $totalPrice ?> บาท</span></h4>
                    </div>
                </div>

                <br>
                <hr>

                <!-- ฟอร์มส่งข้อมูลไป confirm_order.php -->
                <form action="staff_order.php?act=confirm" method="POST">
                    <div style="text-align: center; margin-top: 20px;">
                        <a href="staff_order.php?act=insert" class="btn btn-secondary">กลับไปหน้าเพิ่มรายการ</a>
                        <button type="submit" name="confirm-order" class="btn btn-success">ยืนยันออเดอร์</button>
                    </div>
                </form>
            <?php
            } else {
                echo '<script>
                setTimeout(function() {
                    swal({
                        title: "ข้อมูลการสั่งซื้อไม่ถูกต้อง",
                        type: "error"
                    }, function() {
                        window.location = "staff_order.php?act=insert";
                    });
                }, 1);
                </script>';
                exit;
            }
            ?>
        </div>
    </div>
</div>