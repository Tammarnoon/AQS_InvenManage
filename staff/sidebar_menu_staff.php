<?php

// echo '<pre>';
// print_r($_SESSION);
// echo '</pre>';

?>


<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color: rgb(45, 68, 87);">

  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <?php if (isset($_SESSION['user_img']) && !empty($_SESSION['user_img'])): ?>
          <img src="../assets/user_img/<?= htmlspecialchars($_SESSION['user_img']) ?>?v=<?= time(); ?>" class="brand-image img-circle elevation-3" style="opacity: .8; width: 30px; height: 30px;" alt="User Image">
        <?php else: ?>
          <img src="../assets/user_img/default.jpg" class="brand-image img-circle elevation-3" style="opacity: .8; width: 30px; height: 30px;" alt="Default User Image">
        <?php endif; ?>
      </div>
      <div class="info">
        <a class="brand-text">
          <?= sprintf(
            '%s %s',
            isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : '---',
            isset($_SESSION['surname']) ? htmlspecialchars($_SESSION['surname']) : '---'
          ); ?>
        </a>
      </div>
    </div>


    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

        <li class="nav-header">User</li>
        <li class="nav-item">
          <a href="staff_profile.php" class="nav-link">
            <i class="nav-icon fas fa-user"></i>
            <p>
              Profile
            </p>
          </a>

        <li class="nav-header">Page</li>

        <li class="nav-item">

        <li class="nav-item">
          <a href="./index.php" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <li class="nav-header">Manage</li>

        </li>

        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas">&#xf0b1;</i>
            <p>
              Customer
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="staff_customer.php?act=insert" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Add customer</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="staff_customer.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Customer list</p>
              </a>
            </li>
          </ul>
        </li>

        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-book"></i>
            <p>
              Category product
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="staff_product_cate.php?act=insert" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Add category product</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="staff_product_cate.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Category product list</p>
              </a>
            </li>
          </ul>
        </li>

        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-box"></i>
            <p>
              Product
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="staff_product.php?act=insert" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Add product</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="staff_product.php?act=storckin" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Stock-in</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="staff_product.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Product list</p>
              </a>
            </li>
          </ul>
        </li>

        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas">&#xf571;</i>
            <p>
              Order
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="staff_order.php?act=insert" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Add order</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="staff_order.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Order list</p>
              </a>
            </li>
          </ul>
        </li>



        <hr>

        <li class="nav-item">
          <a href="../logout.php" class="nav-link" onclick="return confirmLogout();">
            <i class="fas fa-circle nav-icon"></i>
            <p>Logout</p>
          </a>

          <script>
            // ฟังก์ชันสำหรับแจ้งเตือนยืนยัน
            function confirmLogout() {
              return confirm('คุณต้องการออกจากระบบใช่หรือไม่?');
            }
          </script>
        </li>

      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>