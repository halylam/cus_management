<?php
ob_start();
session_start();
require_once("Includes/db.php");
$nameIsEmpty = false;
$phoneIsEmpty = false;
$addressIsEmpty = false;
$birthdayIsEmpty = false;
$invalidEmail = false;
if (isset($_SESSION['userID'])) {
    $userId = $_SESSION['userID'];
    $fullname = $_SESSION['fullname'];
    $userType = $_SESSION['userType'];
} else {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET['cusId'])) {
        $cusId = $_GET['cusId'];
        $cusItem = DBUtil::getInstance()->getCustomerDetail($cusId);
    }
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $cusItem = array("cusId" => $_POST['cusId'],
        "cusName" => $_POST['cusName'],
        "cusPhone" => $_POST['cusPhone'],
        "email" => $_POST['email'],
        "cusAddress" => $_POST['cusAddress'],
        "birthday" => $_POST['birthday'],
        "cusDescription" => $_POST['cusNote']);
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
        <title>Chi tiết khách hàng</title>
    </head>
    <body> 
        <?php include 'header.php' ?>
        <h2 style="color: #188420;"><center>THÔNG TIN CHI TIẾT KHÁCH HÀNG</center></h2>
        <div style="width: 90%">
            <form class="form-horizontal" role="form" action="customerDetail.php" method="POST" >
                <input type="hidden" name="cusId" value="<?php echo $cusItem["cusId"]; ?>" />
                <div class="form-group">
                    <label class="control-label col-sm-2" for="cusName">Họ Tên<label style="color: red">(*)</label>: </label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="cusName" value="<?php echo $cusItem["cusName"]; ?>" />
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-2" for="cusPhone">Số điện thoại<label style="color: red">(*)</label>: </label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="cusPhone" value="<?php echo $cusItem["cusPhone"]; ?>" />
                    </div>
                </div>
                
                 <div class="form-group">
                    <label class="control-label col-sm-2" for="cusAddress">Địa chỉ<label style="color: red">(*)</label>: </label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="cusAddress" value="<?php echo $cusItem["cusAddress"]; ?>" />
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-2" for="birthday">Ngày sinh<label style="color: red">(*)</label>: </label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="datetimepicker" name="birthday" value="<?php echo date('d-m-Y', strtotime($cusItem["birthday"])); ?>" />
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-2" for="email">Email:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="email" value="<?php echo $cusItem["email"]; ?>" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-2" for="cusNote">Ghi chú:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="cusNote" value="<?php echo $cusItem["cusDescription"]; ?>" />
                    </div>
                </div>

                <div class="form-group"> 
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-success">Lưu chỉnh sửa</button> 
                        <input type="button" class="btn btn-success" value="Trở lại" onClick="document.location.href = 'listCustomer.php'" />
                        <input type="button" class="btn btn-success" value="Trang chủ" onClick="document.location.href = 'mainPage.php'" />
                    </div>
                </div>
            </form>
        </div>
        
        <div class="panel panel-default">
            <div class="panel-heading"><h3>Danh sách tất cả các cuộc hẹn</h3></div>
            <table class="table">
                <tr>
                <th>Ngày giờ</th>
                <th>Địa điểm</th>
                <th>Lý do</th>
                <th>Mã Nhân Viên</th>
            </tr>
            <?php
            $listAppointment = DBUtil::getInstance()->getListAppointmentByCusId($cusItem["cusId"]);
            while ($row = mysqli_fetch_array($listAppointment)) {
                $appId = $row["appId"];
                echo "<td>" . date('d-m-Y H:i:s', strtotime(htmlentities($row["datetime"]))) . "</td>";
                echo "<td>" . htmlentities($row["position"]) . "</td>";
                echo "<td>" . htmlentities($row["reason"]) . "</td>";
                echo "<td>" . htmlentities($row["login"]) . "</td>";
                echo "<td><a href='editAppointment.php?appId=$appId'><i class='glyphicon glyphicon-pencil'></i></a>"
                        . "<a href='deleteAppointment.php?appId=" . $appId . "' onClick=\"javascript:return confirm('Bạn có chắc chắn xóa lịch hẹn không?');\">"
                        . "<i style='margin-left: 15px; color: red;' class='glyphicon glyphicon-remove'></i></a></td><tr>";
            }
            mysqli_free_result($listAppointment);
            ?>
            </table>
        </div>
        
        <div class="panel panel-default">
            <div class="panel-heading"><h3>Danh sách tất cả giao dịch</h3></div>
            <table class="table">
                <tr>
                <th>Ngày giờ</th>
                <th>Dự án</th>
                <th>Số đất/Số nhà</th>
                <th>Diện tích</th>
                <th>Tổng tiền</th>
                <th>Đặt cọc</th>
                <th>Ghi chú</th>
                <th>Mã Nhân Viên</th>
            </tr>

            <?php
            $listTrans = DBUtil::getInstance()->getListTransactionByCusId($cusItem["cusId"]);
            while ($row = mysqli_fetch_array($listTrans)) {
                $tranId = $row["tranId"];
                echo "<td>" . date('d-m-Y H:i:s', strtotime(htmlentities($row["datetime"]))) . "</td>";
                echo "<td>" . htmlentities($row["project"]) . "</td>";
                echo "<td>" . htmlentities($row["assetNum"]) . "</td>";
                echo "<td>" . htmlentities($row["size"]) . "</td>";
                echo "<td>" . htmlentities($row["totalAmount"]) . "</td>";
                echo "<td>" . htmlentities($row["depositAmount"]) . "</td>";
                echo "<td>" . htmlentities($row["note"]) . "</td>";
                echo "<td>" . htmlentities($row["login"]) . "</td>";
                echo "<td><a href='editTransaction.php?tranId=$tranId'><i class='glyphicon glyphicon-pencil'></i></a>"
                        . "<a href='deleteTransaction.php?tranId=" . $tranId . "' onClick=\"javascript:return confirm('Bạn có chắc chắn xóa giao dịch không?');\">"
                        . "<i style='margin-left: 15px; color: red;' class='glyphicon glyphicon-remove'></i></a></td><tr>\n";
            }
            mysqli_free_result($listTrans);
            ?>
            </table>
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
            if ($_POST['cusAddress'] == "") {
                $addressIsEmpty = true;
                $mess = $mess . "<br/>Địa chỉ bắt buộc nhập ";
            }
            if ($_POST['birthday'] == "") {
                $birthdayIsEmpty = true;
                $mess = $mess . "<br/>Ngày sinh bắt buộc nhập ";
            }
            if ($_POST['email'] != "") {
                if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                    $invalidEmail = true;
                    $mess = $mess . "<br/>Email không đúng định dạng. vd: abc@gmail.com ";
                }
            }
            

            if (!$nameIsEmpty && !$phoneIsEmpty && !$addressIsEmpty && !$birthdayIsEmpty && !$invalidEmail) {
                DBUtil::getInstance()->updateCustomer($_POST["cusName"], $_POST["cusPhone"], $_POST["email"], date('Y-m-d H:i:s', strtotime($_POST["birthday"])), $_POST["cusAddress"], $_POST["cusNote"], $_POST["cusId"]);
                header('Location: listCustomer.php');
                exit;
            } else {
                echo("<div class='alert alert-danger'><strong>Lỗi!</strong> " . $mess . "</div>");
            }
        }
        ?>
    </body>
    <script src="jquery.js"></script>
    <script src="jquery.datetimepicker.full.min.js"></script>
    <script>
                jQuery('#datetimepicker').datetimepicker({
                    timepicker: false,
                    format: 'd-m-Y'
                });
    </script>
</html>
