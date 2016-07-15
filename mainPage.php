<?php
ob_start();
session_start();
require_once("Includes/db.php");

if (isset($_SESSION['userID'])) {
    $userId = $_SESSION['userID'];
    $fullname = $_SESSION['fullname'];
    $userType = $_SESSION['userType'];
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
        <title>Trang chủ</title>
    </head>
    <body>        
        <?php include 'header.php' ?>
        <h2 style="color: #188420;"><center>TRANG CHỦ CÁ NHÂN</center></h2>

        <div class="panel panel-default">
            <div class="panel-heading"><h3>Danh sách cuộc hẹn sắp tới</h3></div>
            <table class="table">
                <tr>
                    <th>Khách Hàng</th>
                    <th>Ngày giờ</th>
                    <th>Địa điểm</th>
                    <th>Lý do</th>
                    <th>Thao tác</th>
                </tr>
                <?php
                $listAppointment = DBUtil::getInstance()->getListAppointment($userId);
                while ($row = mysqli_fetch_array($listAppointment)) {
                    $appId = $row["appId"];
                    echo "<tr><td>" . htmlentities($row["cusName"]) . "</td>";
                    echo "<td>" . date('d-m-Y H:i:s', strtotime(htmlentities($row["datetime"]))) . "</td>";
                    echo "<td>" . htmlentities($row["position"]) . "</td>";
                    echo "<td>" . htmlentities($row["reason"]) . "</td>";
                    echo "<td><a href='editAppointment.php?appId=$appId'><i class='glyphicon glyphicon-pencil'></i></a>"
                    . "  <a href='deleteAppointment.php?appId=" . $appId . "' onClick=\"javascript:return confirm('Bạn có chắc chắn xóa lịch hẹn không?');\">"
                    . "<i style='margin-left: 15px; color: red;' class='glyphicon glyphicon-remove'></i></a></td>";
                }
                mysqli_free_result($listAppointment);
                ?>
            </table>
        </div>


        <div class="panel panel-default">
            <div class="panel-heading"><h3>Danh sách giao dịch 7 ngày gần nhất</h3></div>
            <table class="table">
                <tr>
                    <th>Khách Hàng</th>
                    <th>Ngày giờ</th>
                    <th>Dự án</th>
                    <th>Số đất/Số nhà</th>
                    <th>Diện tích</th>
                    <th>Tổng tiền</th>
                    <th>Đặt cọc</th>
                    <th>Ghi chú</th>
                    <th>Thao tác</th>
                </tr>
                <?php
                $listTrans = DBUtil::getInstance()->getListTransaction($userId);
                while ($row = mysqli_fetch_array($listTrans)) {
                    $tranId = $row["tranId"];
                    echo "<tr><td>" . htmlentities($row["cusName"]) . "</td>";
                    echo "<td>" . date('d-m-Y H:i:s', strtotime(htmlentities($row["datetime"]))) . "</td>";
                    echo "<td>" . htmlentities($row["project"]) . "</td>";
                    echo "<td>" . htmlentities($row["assetNum"]) . "</td>";
                    echo "<td>" . htmlentities($row["size"]) . "</td>";
                    echo "<td>" . htmlentities($row["totalAmount"]) . "</td>";
                    echo "<td>" . htmlentities($row["depositAmount"]) . "</td>";
                    echo "<td>" . htmlentities($row["note"]) . "</td>";
                    echo "<td><a href='editTransaction.php?tranId=$tranId'><i class='glyphicon glyphicon-pencil'></i></a>"
                    . " <a href='deleteTransaction.php?tranId=" . $tranId . "' onClick=\"javascript:return confirm('Bạn có chắc chắn xóa giao dịch không?');\">"
                    . "<i style='margin-left: 15px; color: red;' class='glyphicon glyphicon-remove'></i></a></td>";
                }
                mysqli_free_result($listTrans);
                ?>
            </table>
        </div>

        <br/>
        <input class="btn btn-success" type="button" value="Thông Tin Cá Nhân" onClick="document.location.href = 'editUser.php?userId=<?php echo $userId; ?>&source=1'" />      
        <input class="btn btn-success" type="button" value="Danh sách khách hàng" onClick="document.location.href = 'listCustomer.php'" />     
        <input class="btn btn-success" type="<?php if ($userType == 'Admin')
                    echo 'button';
                else
                    echo 'hidden';
                ?>" value="Danh sách nhân viên" onClick="document.location.href = 'listUser.php'" />     
        <input class="btn btn-success" type="button" value="Thêm Mới Lịch Hẹn" onClick="document.location.href = 'newAppointment.php'" />      
        <input class="btn btn-success" type="button" value="Thêm Mới Giao Dịch" onClick="document.location.href = 'newTransaction.php'" />      
        <input class="btn btn-success" type="button" value="Báo Cáo Mỗi Ngày" onClick="document.location.href = 'baocao.php'" />      
    </body>

</html>
