<?php 
  include 'header.php';
  include 'navbar.php';
  include 'sidebar_menu_staff.php';

 
  $act = (isset($_GET['act']) ? $_GET['act'] : '');

  //เงื่อนไขในการเรียกใช้ไฟล์
  if($act == 'insert'){
      include 'staff_user_form_insert.php';

  }else if($act == 'delete'){
      include 'staff_user_delete.php';

  }else if($act == 'edit'){
    include 'staff_user_form_edit.php';

  }else if($act == 'password'){
    include 'staff_user_form_edit_password.php';
  }
  else{
      include 'staff_profile_show.php';
  }

  include 'footer.php';

?>

  
