<?php 
session_start(); //ประกาศใช้ session
session_destroy(); //เคลียร์ค่า session
header('Location: login_form.php'); //Logout เรียบร้อยและกระโดดไปหน้าตามที่ต้องการ

?>

<html></html>