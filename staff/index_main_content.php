<?php

// จำนวนคำสั่งซื้อทั้งหมด
$totalOrdersQuery = $condb->prepare("SELECT COUNT(*) AS total_orders FROM tbl_order");
$totalOrdersQuery->execute();
$totalOrders = $totalOrdersQuery->fetch(PDO::FETCH_ASSOC)['total_orders'];

// จำนวนคำสั่งซื้อที่ยังไม่จ่าย
$unpaidOrdersQuery = $condb->prepare("SELECT COUNT(*) AS unpaid_orders FROM tbl_order WHERE order_status = 'ยังไม่ได้จ่าย'");
$unpaidOrdersQuery->execute();
$unpaidOrders = $unpaidOrdersQuery->fetch(PDO::FETCH_ASSOC)['unpaid_orders'];

// จำนวนสินค้าทั้งหมด
$totalProductsQuery = $condb->prepare("SELECT COUNT(*) AS total_products FROM tbl_product");
$totalProductsQuery->execute();
$totalProducts = $totalProductsQuery->fetch(PDO::FETCH_ASSOC)['total_products'];

// จำนวนสินค้าที่หมดสต็อก
$outOfStockQuery = $condb->prepare("SELECT COUNT(*) AS out_of_stock FROM tbl_product WHERE product_qty = 0");
$outOfStockQuery->execute();
$outOfStock = $outOfStockQuery->fetch(PDO::FETCH_ASSOC)['out_of_stock'];

// ดึงข้อมูลการชำระเงิน
$paymentInfoQuery = $condb->prepare("SELECT paid_date, paid_money, money_change FROM tbl_order WHERE order_status = 'จ่ายแล้ว'");
$paymentInfoQuery->execute();
$payments = $paymentInfoQuery->fetchAll(PDO::FETCH_ASSOC);

// สร้างอาเรย์สำหรับเก็บข้อมูลการชำระเงิน
$paymentData = [];
foreach ($payments as $payment) {
    $netPayment = $payment['paid_money'] - $payment['money_change'];
    $paymentData[] = [
        'date' => date('d-m-Y', strtotime($payment['paid_date'])), // เปลี่ยนรูปแบบวันที่
        'net_payment' => $netPayment
    ];
}

// เตรียมข้อมูลสำหรับ Highcharts
$chartData = [];
foreach ($paymentData as $data) {
    $chartData[] = "['" . $data['date'] . "', " . $data['net_payment'] . "]";
}
$chartDataString = implode(',', $chartData);
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Dashboard</h1>
        </div>
      </div>
    </div>
  </div>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <!-- Card for dashboard statistics -->
      <div class="card">
        <div class="card-body">
          <!-- Row of stat boxes -->
          <div class="row">
            <div class="col-lg-3 col-6">
              <!-- Total Orders -->
              <div class="small-box bg-info">
                <div class="inner">
                  <h3><?php echo $totalOrders; ?> Orders</h3>
                  <p>All Orders</p>
                </div>
                <div class="icon">
                  <i class="ion ion-bag"></i>
                </div>
                <a href="staff_order.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>

            <div class="col-lg-3 col-6">
              <!-- Unpaid Orders -->
              <div class="small-box bg-danger">
                <div class="inner">
                  <h3><?php echo $unpaidOrders; ?> Orders</h3>
                  <p>Unpaid Orders</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <a href="staff_order.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>

            <div class="col-lg-3 col-6">
              <!-- Total Products -->
              <div class="small-box bg-warning">
                <div class="inner">
                  <h3><?php echo $totalProducts; ?> Products</h3>
                  <p>All Products</p>
                </div>
                <div class="icon">
                  <i class="ion ion-person-add"></i>
                </div>
                <a href="staff_product.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>

            <div class="col-lg-3 col-6">
              <!-- Out of Stock Products -->
              <div class="small-box bg-danger">
                <div class="inner">
                  <h3><?php echo $outOfStock; ?> Products</h3>
                  <p>Out of Stock</p>
                </div>
                <div class="icon">
                  <i class="ion ion-pie-graph"></i>
                </div>
                <a href="staff_product.php?act=storckin" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>

          </div>
          <!-- /.row -->

        </div><!-- /.card-body -->
      </div><!-- /.card -->
    </div>

    <!-- Card for payment information -->
    <div class="card">
      <div class="card-body">
        <h3>Payment Information</h3>
        <div id="paymentChart" style="height: 400px;"></div>
      </div><!-- /.card-body -->
    </div><!-- /.card -->

    <div class="card">
      <div class="card-body">
        <h3>Orders Over Time</h3>
        <div id="ordersChart" style="height: 400px;"></div>
      </div><!-- /.card-body -->
    </div><!-- /.card -->

    <!-- Card for comparison of paid and cancelled orders -->
    <div class="card">
      <div class="card-body">
        <h3>Comparison of Paid and Cancelled Orders Over Time</h3>
        <div id="ordersComparisonChart" style="height: 400px;"></div>
      </div><!-- /.card-body -->
    </div><!-- /.card -->

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script>
      // Chart for payment information
      Highcharts.chart('paymentChart', {
        chart: {
          type: 'column'
        },
        title: {
          text: 'Net Payments Over Time'
        },
        xAxis: {
          type: 'category',
          title: {
            text: 'Date'
          }
        },
        yAxis: {
          title: {
            text: 'Net Payment (฿)'
          }
        },
        series: [{
          name: 'Net Payment',
          data: [<?php echo $chartDataString; ?>]
        }],
        tooltip: {
          headerFormat: '<span style="font-size:11px">{point.key}</span><br>',
          pointFormat: '<span style="color:{point.color}">{series.name}: <b>{point.y:.2f} ฿</b></span><br/>'
        }
      });

      // Chart for orders over time
      Highcharts.chart('ordersChart', {
        chart: {
          type: 'line' // เปลี่ยนประเภทของกราฟเป็น line
        },
        title: {
          text: 'Total Orders Over Time'
        },
        xAxis: {
          type: 'category',
          title: {
            text: 'Order Date'
          }
        },
        yAxis: {
          title: {
            text: 'Total Orders'
          }
        },
        series: [{
          name: 'Total Orders',
          data: [
            // เพิ่มข้อมูลวันที่และจำนวนออเดอร์
            <?php
            $ordersOverTimeQuery = $condb->prepare("SELECT order_date, COUNT(*) AS order_count FROM tbl_order GROUP BY order_date ORDER BY order_date");
            $ordersOverTimeQuery->execute();
            $ordersOverTime = $ordersOverTimeQuery->fetchAll(PDO::FETCH_ASSOC);
            $chartDataOrders = [];
            foreach ($ordersOverTime as $order) {
              $chartDataOrders[] = "['" . date('d-m-Y', strtotime($order['order_date'])) . "', " . $order['order_count'] . "]";
            }
            echo implode(',', $chartDataOrders);
            ?>
          ]
        }]
      });


      // Chart for comparison of paid, unpaid, and cancelled orders as a pie chart
      Highcharts.chart('ordersComparisonChart', {
        chart: {
          type: 'pie' // เปลี่ยนประเภทของกราฟเป็น pie
        },
        title: {
          text: 'Comparison of Paid, Unpaid, and Cancelled Orders'
        },
        series: [{
          name: 'Orders',
          colorByPoint: true,
          data: [{
            name: 'Paid Orders',
            y: <?php
                // Query สำหรับคำสั่งซื้อตามสถานะที่จ่ายแล้ว
                $paidOrdersCountQuery = $condb->prepare("SELECT COUNT(*) AS total_paid FROM tbl_order WHERE order_status = 'จ่ายแล้ว'");
                $paidOrdersCountQuery->execute();
                $totalPaidOrders = $paidOrdersCountQuery->fetch(PDO::FETCH_ASSOC)['total_paid'];
                echo $totalPaidOrders;
                ?>,
            sliced: true, // ทำให้ดูโดดเด่น
            color: 'green' // สีสำหรับคำสั่งซื้อที่จ่ายแล้ว
          }, {
            name: 'Unpaid Orders',
            y: <?php
                // Query สำหรับคำสั่งซื้อตามสถานะที่ยังไม่ได้จ่าย
                $unpaidOrdersCountQuery = $condb->prepare("SELECT COUNT(*) AS total_unpaid FROM tbl_order WHERE order_status = 'ยังไม่ได้จ่าย'");
                $unpaidOrdersCountQuery->execute();
                $totalUnpaidOrders = $unpaidOrdersCountQuery->fetch(PDO::FETCH_ASSOC)['total_unpaid'];
                echo $totalUnpaidOrders;
                ?>,
            color: 'orange' // สีสำหรับคำสั่งซื้อที่ยังไม่ได้จ่าย
          }, {
            name: 'Cancelled Orders',
            y: <?php
                // Query สำหรับคำสั่งซื้อตามสถานะที่ถูกยกเลิก
                $cancelledOrdersCountQuery = $condb->prepare("SELECT COUNT(*) AS total_cancelled FROM tbl_order WHERE order_status = 'ถูกยกเลิก'");
                $cancelledOrdersCountQuery->execute();
                $totalCancelledOrders = $cancelledOrdersCountQuery->fetch(PDO::FETCH_ASSOC)['total_cancelled'];
                echo $totalCancelledOrders;
                ?>,
            color: 'red' // สีสำหรับคำสั่งซื้อที่ถูกยกเลิก
          }]
        }],
        tooltip: {
          pointFormat: '<b>{point.name}: {point.y}</b>'
        }
      });
    </script>