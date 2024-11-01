<?php 
  include 'header.php';
  include 'navbar.php';
  include 'sidebar_menu_admin.php';

 
  $act = (isset($_GET['act']) ? $_GET['act'] : '');

  //เงื่อนไขในการเรียกใช้ไฟล์
  if($act == 'insert'){
      include 'admin_user_form_insert.php';

  }else if($act == 'delete'){
      include 'admin_user_delete.php';

  }else if($act == 'edit'){
    include 'admin_user_form_edit.php';

  }else if($act == 'password'){
    include 'admin_user_form_edit_password.php';
  }
  else{
      include 'admin_user_list.php';
  }

  include 'footer.php';

?>

  
