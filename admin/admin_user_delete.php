<?php
if (isset($_GET['id']) && $_GET['act'] == 'delete') {
    try {

        $id = $_GET['id'];

        $sqlQuerySeclectPicName = $condb->prepare("SELECT user_img FROM tbl_user WHERE id=?");
        $sqlQuerySeclectPicName->execute([$_GET['id']]);
        $row = $sqlQuerySeclectPicName->fetch(PDO::FETCH_ASSOC);

        //เงื่อนไขการลบภาพ
        if ($sqlQuerySeclectPicName->rowCount() == 0) {
            // echo 'ไม่ผ่าน';
            echo '<script>
             setTimeout(function() {
              swal({
                  title: "เกิดข้อผิดพลาด",
                  type: "error"
              }, function() {
                  window.location = "admin_user.php"; //หน้าที่ต้องการให้กระโดดไป
              });
            }, 1);
        </script>';
            exit;
        } else {
            // echo 'ผ่าน';

            // ลบข้อมูลใน tbl_user
            $sqldeleteUser = $condb->prepare('DELETE FROM tbl_user WHERE id=:id');
            $sqldeleteUser->bindParam(':id', $id, PDO::PARAM_INT);
            $sqldeleteUser->execute();

            $condb = null;

            if ($sqldeleteUser->rowCount() == 1) {
                //ลบไฟล์ภาพ
                unlink('../assets/user_img/' . $row['user_img']);
                echo '<script>
                                setTimeout(function() {
                                    swal({
                                    title: "ลบข้อมูลสำเร็จ",
                                    type: "success"
                                }, function() {
                                    window.location = "admin_user.php"; //หน้าที่ต้องการให้กระโดดไป
                                });
                                }, 1);
                        </script>';
                exit;
            }
        }
    } catch (PDOException $e) {
        // error show
        // echo 'Message: '. $e->getMessage();

        // handle PDOException errors
        echo '<script>
                       setTimeout(function() {
                        swal({
                            title: "เกิดข้อผิดพลาด",
                            type: "error"
                        }, function() {
                            window.location = "admin_user.php"; //หน้าที่ต้องการให้กระโดดไป
                        });
                      }, 1);
                  </script>';
    }
}
