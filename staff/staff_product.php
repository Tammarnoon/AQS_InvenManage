<?php 
  include 'header.php';
  include 'navbar.php';
  include 'sidebar_menu_staff.php';

 
  $act = (isset($_GET['act']) ? $_GET['act'] : '');

  //เงื่อนไขในการเรียกใช้ไฟล์
  if($act == 'insert'){
    include 'staff_product_form_insert.php';

  }else if($act == 'delete'){
    include 'staff_product_delete.php';

  }else if($act == 'edit'){
    include 'staff_product_form_edit.php';

  }else if($act == 'img'){
    include 'staff_product_img_upload.php';

  }else if($act == 'storckin'){
    include 'staff_product_stock-in_list.php';

  }else if($act == 'storckInsert'){
    include 'staff_product_stock-in_insert.php';
  }
  else{
    include 'staff_product_list.php';
  }

  include 'footer.php';

?>

  
