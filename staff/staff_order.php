<?php 
  include 'header.php';
  include 'navbar.php';
  include 'sidebar_menu_staff.php';

 
  $act = (isset($_GET['act']) ? $_GET['act'] : '');

  //เงื่อนไขในการเรียกใช้ไฟล์
  if($act == 'insert'){
      include 'staff_order_insert.php';

  }else if($act == 'delete'){
      include 'staff_order_delete.php';

  }else if($act == 'create'){
    include 'create_order.php';

  }else if($act == 'summary'){
    include 'order_summary.php';

  }else if($act == 'confirm'){
    include 'order_confirm.php';

  }else if($act == 'paid'){
    include 'final_order_paid.php';
  } 
  else if($act == 'detail'){
    include 'order_detail.php';

  }
  else{
      include 'staff_order_list.php';
  }

  include 'footer.php';

?>

  
