<?php
if (isset($_GET['cus_id']) && $_GET['act'] == 'delete') {
    try {
        $cus_id = $_GET['cus_id'];

        $sqldeleteCateProdcut = $condb->prepare('DELETE FROM tbl_customer WHERE cus_id=:cus_id');
        $sqldeleteCateProdcut->bindParam(':cus_id', $cus_id, PDO::PARAM_INT);
        $sqldeleteCateProdcut->execute();

        if ($sqldeleteCateProdcut->rowCount() == 1) {
            echo '<script>
                 setTimeout(function() {
                  swal({
                      title: "ลบข้อมูลสำเร็จ",
                      type: "success"
                  }, function() {
                      window.location = "staff_customer.php"; //หน้าที่ต้องการให้กระโดดไป
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
                            window.location = "staff_customer.php"; //หน้าที่ต้องการให้กระโดดไป
                        });
                      }, 1);
                  </script>';
    }
}
