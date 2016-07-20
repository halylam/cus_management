<?php
ob_start();
session_start();
require_once("Includes/db.php");
$nameIsEmpty = false;
$phoneIsEmpty = false;
$invalidEmail = false;
if (isset($_SESSION['userID'])) {
    $userId = $_SESSION['userID'];
    $fullname = $_SESSION['fullname'];
} else {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Thêm mới khách hàng</title>
    </head>
    <body>
        <?php include 'header.php' ?>
        <h2 style="color: #188420;"><center>THÔNG TIN CHI TIẾT KHÁCH HÀNG</center></h2>

        <div style="width: 90%">
            <form class="form-horizontal" role="form" action="newCustomer.php" method="POST" >
                <div class="form-group">
                    <label class="control-label col-sm-2" for="cusName">Họ Tên<label style="color: red">(*)</label>: </label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="cusName" value="" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-2" for="cusPhone">Số điện thoại<label style="color: red">(*)</label>: </label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="cusPhone" value="" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-2" for="cusAddress">Địa chỉ: </label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="cusAddress" value="" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-2" for="birthday">Ngày sinh: </label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="datetimepicker" name="birthday" value="" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-2" for="email">Email:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="email" value="" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-2" for="cusNote">Ghi chú:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="cusNote" value="" />
                    </div>
                </div>

                <div class="form-group"> 
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-success">Đồng ý</button> 
                        <input type="button" class="btn btn-success" value="Trở lại" onClick="history.back();" />
                    </div>
                </div>
            </form>
        </div>

        <?php
        /** Check that the page was requested from itself via the POST method. */
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $mess = '';
            /** Check whether validate required */
            if ($_POST['cusName'] == "") {
                $nameIsEmpty = true;
                $mess = $mess . "<br/>Họ tên bắt buộc nhập ";
            }
            if ($_POST['cusPhone'] == "") {
                $phoneIsEmpty = true;
                $mess = $mess . "<br/>Số điện thoại bắt buộc nhập ";
            }

            if ($_POST['email'] != "") {
                if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                    $invalidEmail = true;
                    $mess = $mess . "<br/>Email không đúng định dạng. vd: abc@gmail.com ";
                }
            }

            if (!$nameIsEmpty && !$phoneIsEmpty && !$invalidEmail) {
                DBUtil::getInstance()->insertCustomer($_POST["cusName"], $_POST["cusPhone"], $_POST["email"], date('Y-m-d H:i:s', strtotime($_POST["birthday"])), $_POST["cusAddress"], $_POST["cusNote"], $userId);
                header('Location: listCustomer.php');
                exit;
            } else {
                echo("<div class='alert alert-danger'><strong>Lỗi!</strong> " . $mess . "</div>");
            }
        }
        ?>
    </body>

    <script>
        jQuery('#datetimepicker').datetimepicker({
            timepicker: false,
            format: 'd-m-Y'
        });
    </script>
</html>
