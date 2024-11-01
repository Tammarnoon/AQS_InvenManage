<?php
if (isset($_GET['product_id']) && $_GET['act'] == 'delete') {
    try {

        $product_id = $_GET['product_id'];

        //Single row query แสดง 1 รายการ FETCH_ASSOC เอาชื่อไฟล์ภาพไปลบ
        $sqlQuerySeclectPicName = $condb->prepare("SELECT product_img FROM tbl_product WHERE product_id=?");
        $sqlQuerySeclectPicName->execute([$_GET['product_id']]);
        $row = $sqlQuerySeclectPicName->fetch(PDO::FETCH_ASSOC);

        //เช็ตชื่อรูปจาก query
        // echo 'image name'. $row['product_img'];
        // exit;

        //แสดงจำนวน query row
        // echo $sqlQuerySeclectPicName->rowCount();
        // exit;

        //เงื่อนไขการลบภาพ
        if ($sqlQuerySeclectPicName->rowCount() == 0) {
            // echo 'ไม่ผ่าน';
            echo '<script>
             setTimeout(function() {
              swal({
                  title: "เกิดข้อผิดพลาด",
                  type: "error"
              }, function() {
                  window.location = "staff_product.php"; //หน้าที่ต้องการให้กระโดดไป
              });
            }, 1);
        </script>';
            exit;
        } else {
            // echo 'ผ่าน';

            // ลบข้อมูลใน tbl_product
            $sqldeleteProduct = $condb->prepare('DELETE FROM tbl_product WHERE product_id=:product_id');
            $sqldeleteProduct->bindParam(':product_id', $product_id, PDO::PARAM_INT);
            $sqldeleteProduct->execute();

            // Query สำหรับดึงชื่อไฟล์ภาพจาก tbl_product_img
            $sqlSelectSubImages = $condb->prepare('SELECT sub_product_img FROM tbl_product_img WHERE ref_product_id=:product_id');
            $sqlSelectSubImages->bindParam(':product_id', $product_id, PDO::PARAM_INT);
            $sqlSelectSubImages->execute();
            $subImages = $sqlSelectSubImages->fetchAll(PDO::FETCH_ASSOC);

            // ลบไฟล์ภาพจาก tbl_product_img
            foreach ($subImages as $img) {
                if (!empty($img['sub_product_img'])) {
                    unlink('../assets/product_gallary/' . $img['sub_product_img']);
                }
            }

            // ลบข้อมูลจาก tbl_product_img
            $sqldeleteProductImg = $condb->prepare('DELETE FROM tbl_product_img WHERE ref_product_id=:product_id');
            $sqldeleteProductImg->bindParam(':product_id', $product_id, PDO::PARAM_INT);
            $sqldeleteProductImg->execute();

            $condb = null;

            if ($sqldeleteProduct->rowCount() == 1) {
                //ลบไฟล์ภาไ
                unlink('../assets/product_img/' . $row['product_img']);

                echo '<script>
                                setTimeout(function() {
                                    swal({
                                    title: "ลบข้อมูลสำเร็จ",
                                    type: "success"
                                }, function() {
                                    window.location = "staff_product.php"; //หน้าที่ต้องการให้กระโดดไป
                                });
                                }, 1);
                        </script>';
                exit;
            }
        } // เงื่อนไขการลบภาพ
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
                            window.location = "staff_product.php"; //หน้าที่ต้องการให้กระโดดไป
                        });
                      }, 1);
                  </script>';
    }
}// isset
