<?php
// Query to get total user count
$countUsersQuery = $condb->prepare("SELECT COUNT(*) AS total_users FROM tbl_user");
$countUsersQuery->execute();
$totalUsers = $countUsersQuery->fetch(PDO::FETCH_ASSOC)['total_users'];

// Query to get user count per date (assuming you have a date column)
$userCountQuery = $condb->prepare("SELECT DATE(date) AS registration_date, COUNT(*) AS user_count FROM tbl_user GROUP BY registration_date");
$userCountQuery->execute();
$userData = $userCountQuery->fetchAll(PDO::FETCH_ASSOC);

// Prepare data for the chart
$dates = [];
$userCounts = [];
foreach ($userData as $row) {
    $dates[] = $row['registration_date'];
    $userCounts[] = (int)$row['user_count'];
}

// Query to get user levels
$userLevelQuery = $condb->prepare("SELECT user_level, COUNT(*) AS count FROM tbl_user GROUP BY user_level");
$userLevelQuery->execute();
$userLevels = $userLevelQuery->fetchAll(PDO::FETCH_ASSOC);

// Prepare data for user level chart
$userLevelData = [];
foreach ($userLevels as $level) {
    $userLevelData[] = [
        'name' => $level['user_level'],
        'y' => (int)$level['count']
    ];
}
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Dashboard</h1>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <!-- Card for dashboard statistics -->
      <div class="card">
        <div class="card-body">
          <!-- Small boxes (Stat box) -->
          <div class="row">
            <!-- ./col -->
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-warning">
                <div class="inner">
                  <h3><?php echo $totalUsers; ?> Accounts</h3>
                  <p>User Registrations</p>
                </div>
                <div class="icon">
                  <i class="ion ion-person-add"></i>
                </div>
                <a href="admin_user.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>

            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-info">
                <div class="inner">
                  <h3>2 Levels</h3>
                  <p>User Level</p>
                </div>
                <div class="icon">
                  <i class="ion ion-pie-graph"></i>
                </div>
                <a href="admin_user.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col -->
          </div>
          <!-- /.row -->
        </div><!-- /.card-body -->
      </div><!-- /.card -->
      <!-- Highcharts integration for User Registrations Over Time -->
      <figure class="highcharts-figure">
          <div id="container-registrations"></div>
      </figure>
      <hr>
      <!-- Highcharts integration for User Level Composition -->
      <figure class="highcharts-figure">
          <div id="container-levels"></div>
      </figure>

      <script src="https://code.highcharts.com/highcharts.js"></script>
      <script src="https://code.highcharts.com/modules/exporting.js"></script>
      <script src="https://code.highcharts.com/modules/export-data.js"></script>
      <script src="https://code.highcharts.com/modules/accessibility.js"></script>

      <script>
      // Chart for User Registrations Over Time
      Highcharts.chart('container-registrations', {
          title: {
              text: 'User Registrations Over Time'
          },
          xAxis: {
              title: {
                  text: 'Date'
              },
              categories: <?php echo json_encode($dates); ?>
          },
          yAxis: {
              title: {
                  text: 'Number of Users'
              }
          },
          tooltip: {
              headerFormat: '<b>{series.name}</b><br />',
              pointFormat: '{point.y} user(s)'
          },
          series: [{
              name: 'User Registrations',
              data: <?php echo json_encode($userCounts); ?>
          }]
      });

      // Chart for User Level Composition
      Highcharts.chart('container-levels', {
          chart: {
              type: 'pie'
          },
          title: {
              text: 'User Level Composition'
          },
          tooltip: {
              valueSuffix: '%'
          },
          plotOptions: {
              pie: {
                  allowPointSelect: true,
                  cursor: 'pointer',
                  dataLabels: {
                      enabled: true,
                      format: '{point.name}: {point.y} user(s)',
                      distance: -30
                  }
              }
          },
          series: [{
              name: 'Users',
              colorByPoint: true,
              data: <?php echo json_encode($userLevelData); ?>
          }]
      });
      </script>

    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
