<?php 
  include 'header.php';
  include 'navbar.php';
  include 'sidebar_menu_staff.php';

 
  $act = (isset($_GET['act']) ? $_GET['act'] : '');

  //เงื่อนไขในการเรียกใช้ไฟล์
  if($act == 'insert'){
    include 'staff_customer_insert.php';

  }else if($act == 'delete'){
    include 'staff_customer_delete.php';

  }else if($act == 'edit'){
    include 'staff_customer_edit.php';

  }else{
    include 'staff_customer_list.php';
  }

  include 'footer.php';

?>

  
