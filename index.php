<?php
ob_start();
session_start();
require_once("Includes/db.php");
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
        <title>Đăng Nhập</title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"/>
        <link rel="stylesheet" type="text/css" href="css/bootstrap-theme.min.css" />
        <script src="js/jquery.js"></script>
        <script src="js/bootstrap.min.js"></script>
    </head>
    <body>
    <center><h1 style="color: #188420; padding-top: 50px; padding-bottom: 100px;">PHẦN MỀM QUẢN LÝ BẤT ĐỘNG SẢN</h1></center>
    
    <div style="width: 90%"> 
    <center><h3 style="color: #188420;">Đăng Nhập Tài Khoản</h3></center>
    <form class="form-horizontal" role="form" action="index.php" method="POST" >
        <div class="form-group">
            <label class="control-label col-sm-2" for="login">Tên Đăng Nhập:</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="login" name="login" placeholder="Nhập tên đăng nhập"/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="pass">Mật khẩu:</label>
            <div class="col-sm-10"> 
                <input type="password" class="form-control" id="pass" name="pass" placeholder="Nhập mật khẩu"/>
            </div>
        </div>
        <div class="form-group"> 
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-success">Đồng ý</button>
            </div>
        </div>
    </form>
    </div>
    <div style="text-align:  right; position: fixed; bottom: 0; padding: 5px; width:100%; background: #bbdbbd;"><center><i class='glyphicon glyphicon-copyright-mark'></i>Made by: Lâm Đình Hà Lý</center></div>
    <?php
    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        $user = DBUtil::getInstance()->checkLogin($_POST["login"], $_POST["pass"]);
        if ($user == null) {
            exit("<div class='alert alert-danger'><strong>Lỗi!</strong> Tên Đăng Nhập ko tồn tại hoặc mật khẩu nhập không đúng. Vui lòng thử lại!</div>");
            header('Location: index.php');
        } else {
            $_SESSION['userID'] = $user["userId"];
            $_SESSION['fullname'] = $user["fullname"];
            $_SESSION['userType'] = $user["type"];
        }
        header('Location: mainPage.php');
        exit;
    }
    ?>
</body>
<script>
         $(document).ready(function () {
            $("#login").focus();
        });
    </script>
</html>
