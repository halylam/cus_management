<?php
ob_start();
session_start();
require_once("Includes/db.php");
if (isset($_SESSION['userID'])) {
    $userId = $_SESSION['userID'];
    $userType = $_SESSION['userType'];
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
        <title>Danh sách khách hàng</title>
    </head>
    <body>
        <?php include 'header.php' ?>
        <h2 style="color: #188420;"><center>DANH SÁCH KHÁCH HÀNG</center></h2>
        
        <div class="panel panel-default">
            <div class="panel-heading"></div>
            <table class="table">
                <tr>
                <th>Mã Khách Hàng</th>
                <th>Họ Tên</th>
                <th>Số điện thoại</th>
                <th>Email</th>
                <th>Địa chỉ</th>
                <th>Ngày sinh</th>
                <th>Mã Nhân viên</th>
                <th>Thao tác</th>

            </tr>
            <?php
            if ($userType == 'Admin') {
                $listCustomer = DBUtil::getInstance()->getListCustomerAdmin();
            } else {
                $listCustomer = DBUtil::getInstance()->getListCustomer($userId);
            }

            while ($row = mysqli_fetch_array($listCustomer)) {
                $cusId = $row["cusId"];
                echo "<tr><td>" . htmlentities($cusId) . "</td>";
                echo "<td>" . htmlentities($row["cusName"]) . "</td>";
                echo "<td>" . htmlentities($row["cusPhone"]) . "</td>";
                echo "<td>" . htmlentities($row["email"]) . "</td>";
                echo "<td>" . htmlentities($row["cusAddress"]) . "</td>";
                echo "<td>" . date('d-m-Y', strtotime(htmlentities($row["birthday"]))) . "</td>";
                echo "<td>" . htmlentities($row["login"]) . "</td>";
                echo "<td><a href='customerDetail.php?cusId=$cusId'><i class='glyphicon glyphicon-pencil'></i></a>"
                    . "<a href='customerDelete.php?cusId=" . $cusId . "' onClick=\"javascript:return confirm('Bạn có chắc chắn xóa Khách hàng ko? Các lịch hẹn và giao dịch sẽ bị xóa hết?');\">"
                        . "<i style='margin-left: 15px; color: red;' class='glyphicon glyphicon-remove'></i></a></td><tr>";
            }
            mysqli_free_result($listCustomer);
            ?>
            </table>
        </div>
        
        <br/>
        <input class="btn btn-success" type="button" value="Thêm mới khách hàng" onClick="document.location.href = 'newCustomer.php'" />     

        <input class="btn btn-success" type="button" value="Trang chủ" onClick="document.location.href = 'mainPage.php'" />

    </body>
</html>
