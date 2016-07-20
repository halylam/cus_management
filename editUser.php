<?php
ob_start();
session_start();
require_once("Includes/db.php");
$nameIsEmpty = false;
$phoneIsEmpty = false;
$addressIsEmpty = false;
$birthdayIsEmpty = false;
$loginIsEmpty = false;
$passIsEmpty = false;
$invalidEmail = false;
if (isset($_SESSION['userID'])) {
    $userId = $_SESSION['userID'];
    $userType = $_SESSION['userType'];
    $fullname = $_SESSION['fullname'];
} else {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET['userId'])) {
        $userSelectedId = $_GET['userId'];
        $source = $_GET['source'];
        $userItem = DBUtil::getInstance()->getUserDetail($userSelectedId);
    }
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $userItem = array("userId" => $_POST['userId'],
        "login" => $_POST['login'],
        "pass" => $_POST['pass'],
        "fullname" => $_POST['fullname'],
        "phone" => $_POST['phone'],
        "address" => $_POST['address'],
        "birthday" => $_POST['birthday'],
        "email" => $_POST['email']);
    $source = $_POST['source'];
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
        <title>Thông tin nhân viên</title>
    </head>
    <body> 
        <?php include 'header.php' ?>
        <h2 style="color: #188420;"><center>THÔNG TIN CHI TIẾT NHÂN VIÊN</center></h2>
        <div style="width: 90%">
            <form class="form-horizontal" role="form" action="editUser.php" method="POST" >
                <input type="hidden" name="source" value="<?php echo $source; ?>" />
                <input type="hidden" name="userId" value="<?php echo $userItem["userId"]; ?>" />
                <div class="form-group">
                    <label class="control-label col-sm-2" for="login">Tên Đăng Nhập<label style="color: red">(*)</label>: </label>
                    <div class="col-sm-10">
                        <input readonly="true" type="text" class="form-control" name="login" value="<?php echo $userItem["login"]; ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="pass">Mật Khẩu<label style="color: red">(*)</label>:</label>
                    <div class="col-sm-10"> 
                        <input type="password" class="form-control" id="pass" name="pass" value="<?php echo $userItem["pass"]; ?>" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-2" for="fullname">Họ Tên<label style="color: red">(*)</label>: </label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="fullname" value="<?php echo $userItem["fullname"]; ?>" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-2" for="phone">Số điện thoại<label style="color: red">(*)</label>: </label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="phone" value="<?php echo $userItem["phone"]; ?>" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-2" for="address">Địa chỉ<label style="color: red">(*)</label>: </label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="address" value="<?php echo $userItem["address"]; ?>" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-2" for="birthday">Ngày sinh<label style="color: red">(*)</label>: </label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="datetimepicker" name="birthday" value="<?php echo date('d-m-Y', strtotime($userItem["birthday"])); ?>" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-2" for="email">Email:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="email" value="<?php echo $userItem["email"]; ?>" />
                    </div>
                </div>

                <div class="form-group"> 
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-success">Lưu chỉnh sửa</button> 
                        <input type="button" class="btn btn-success" value="Trở lại" onClick="history.back();" />
                    </div>
                </div>
            </form>
        </div>
        <?php
        /** Check that the page was requested from itself via the POST method. */
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $mess = '';
            if ($_POST['login'] == "") {
                $loginIsEmpty = true;
                $mess = $mess . "<br/>Tên đăng nhập bắt buộc nhập ";
            }
            if ($_POST['pass'] == "") {
                $passIsEmpty = true;
                $mess = $mess . "<br/>Mật khẩu bắt buộc nhập ";
            }
            if ($_POST['fullname'] == "") {
                $nameIsEmpty = true;
                $mess = $mess . "<br/>Họ tên bắt buộc nhập ";
            }
            if ($_POST['phone'] == "") {
                $phoneIsEmpty = true;
                $mess = $mess . "<br/>Số điện thoại bắt buộc nhập ";
            }
            if ($_POST['birthday'] == "") {
                $birthdayIsEmpty = true;
                $mess = $mess . "<br/>Ngày sinh bắt buộc nhập ";
            }
            if ($_POST['address'] == "") {
                $addressIsEmpty = true;
                $mess = $mess . "<br/>Địa chỉ bắt buộc nhập ";
            }
            if ($_POST['email'] != "") {
                if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                    $invalidEmail = true;
                    $mess = $mess . "<br/>Email không đúng định dạng. vd: abc@gmail.com ";
                }
            }

            if (!$loginIsEmpty && !$passIsEmpty && !$birthdayIsEmpty && !$nameIsEmpty && !$phoneIsEmpty && !$addressIsEmpty && !$invalidEmail) {
                DBUtil::getInstance()->updateUser($_POST["userId"], $_POST["login"], $_POST["pass"], $_POST["fullname"], $_POST["phone"], $_POST["address"], date('Y-m-d H:i:s', strtotime($_POST["birthday"])), $_POST["email"]);
                if ($_POST["source"] == 1)
                    header('Location: mainPage.php');
                if ($_POST["source"] == 2)
                    header('Location: listUser.php');
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
