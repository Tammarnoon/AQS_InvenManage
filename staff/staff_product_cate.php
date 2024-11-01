<?php 
  include 'header.php';
  include 'navbar.php';
  include 'sidebar_menu_staff.php';

 
  $act = (isset($_GET['act']) ? $_GET['act'] : '');

  //เงื่อนไขในการเรียกใช้ไฟล์
  if($act == 'insert'){
      include 'staff_product_cate_insert_form.php';

  }else if($act == 'delete'){
      include 'staff_prodcut_cate_delete.php';

  }else if($act == 'edit'){
    include 'staff_product_cate_edit.php';

  }else{
      include 'staff_product_cate_list.php';
  }

  include 'footer.php';

?>

  
