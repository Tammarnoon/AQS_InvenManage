<?php
if (isset($_GET['cate_id']) && $_GET['act'] == 'delete') {
    try {
        $cate_id = $_GET['cate_id'];

        $sqldeleteCateProdcut = $condb->prepare('DELETE FROM tbl_category WHERE cate_id=:cate_id');
        $sqldeleteCateProdcut->bindParam(':cate_id', $cate_id, PDO::PARAM_INT);
        $sqldeleteCateProdcut->execute();

        if ($sqldeleteCateProdcut->rowCount() == 1) {
            echo '<script>
                 setTimeout(function() {
                  swal({
                      title: "ลบข้อมูลสำเร็จ",
                      type: "success"
                  }, function() {
                      window.location = "staff_product_cate.php"; //หน้าที่ต้องการให้กระโดดไป
                  });
                }, 1);
            </script>';
            exit;
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
                            window.location = "staff_product_cate.php"; //หน้าที่ต้องการให้กระโดดไป
                        });
                      }, 1);
                  </script>';
    }
}
