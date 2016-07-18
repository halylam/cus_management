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
        <title>Báo Cáo</title>
        <link rel="stylesheet" type="text/css" href="css/fileinput.min.css" />
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"/>
        <script src="js/jquery.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/fileinput.min.js"></script>
    </head>
    <body>
        <?php include 'header.php' ?>

        <h2 style="color: #188420;"><center>DANH SÁCH BÁO CÁO</center></h2>

    <center>
        <div style="width: 70%">
            <form action="baocao.php" method="post" enctype="multipart/form-data">
                <div class="panel panel-default">
                    <div class="panel-heading"></div>
                    <table class="table">
                        <tr>
                            <th>Tên File</th>
                            <th>Tên Nhân Viên</th>
                            <th>Thao Tác</th>
                        </tr>

                        <?php
                        if ($userType == 'Admin') {
                            $path = realpath('uploads/');
                            $objects = new RecursiveIteratorIterator(
                                    new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::SELF_FIRST);

                            foreach ($objects as $name => $object) {
                                $fileName = $object->getFilename();
                                $path = $object->getPathname();
                                if ($fileName != '.' && $fileName != '..' && strpos($fileName, '.') !== false) {
                                    $pathArr = explode("uploads/", $path);
                                    $pathArr = explode("/", $pathArr[1]);
                                    echo "<tr><td>$fileName</td>";
                                    echo "<td>$pathArr[0]</td>";
                                    echo "<td><a href='download.php?file=" . $fileName . "&path=$pathArr[0]'><i class='glyphicon glyphicon-download-alt'></i></a><a href='deleteFile.php?file=" . $fileName . "&path=$pathArr[0]'><i style='margin-left: 15px; color: red;' class='glyphicon glyphicon-remove'></i></a></td></tr>";
                                }
                            }
                        } else {
                            $path = $userId . '-' . $fullname;
                            $target_dir = 'uploads/' . $path;
                            if (!is_dir($target_dir)) {
                                mkdir($target_dir);
                            }
                            if ($handle = opendir($target_dir)) {
                                while (false !== ($entry = readdir($handle))) {
                                    if ($entry != "." && $entry != "..") {
                                        $file = $target_dir . '/' . $entry;
                                        echo "<tr><td>$entry</td>";
                                        echo "<td>$fullname</td>";
                                        echo "<td><a href='download.php?file=" . $entry . "&path=$path'><i class='glyphicon glyphicon-download-alt'></i></a><a href='deleteFile.php?file=" . $entry . "&path=$path'><i style='margin-left: 15px; color: red;' class='glyphicon glyphicon-remove'></i></a></td></tr>";
                                    }
                                }
                                closedir($handle);
                            }
                        }
                        ?>

                    </table>
                </div>   

                <label class="control-label"><h4>Chọn File báo cáo trong ngày</h4></label>
                <input id="fileToUpload" type="file" name="fileToUpload" class="file">
                <br/>
                <input class="btn btn-success"  type="button" value="Trang chủ" onClick="document.location.href = 'mainPage.php'" />
            </form>
        </div>
    </center>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $mess;
        $target_dir = "uploads/" . $userId . '-' . $fullname . "/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir);
        }
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

// Check if file already exists
        if (file_exists($target_file)) {
            $mess = "Xin lỗi, File đã tồn tại.";
            $uploadOk = 0;
        }
// Check file size
        if ($_FILES["fileToUpload"]["size"] > 1024000) {
            $mess = "Xin lỗi, Dung lượng file quá lơn. File phải nhỏ hơn 1Mb.";
            $uploadOk = 0;
        }
// Allow certain file formats
        if ($imageFileType != "xls" && $imageFileType != "xlsx" && $imageFileType != "pdf" && $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $mess = "Xin lỗi, chỉ XLS, XLSX, PDF, JPG, JPEG, PNG, GIF files được chấp nhận.";
            $uploadOk = 0;
        }
// Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo("<div class='alert alert-danger'><strong>Lỗi!</strong> " . $mess . "</div>");
// if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                header('Location: baocao.php');
                exit;
            } else {
                $mess = "Xin lỗi. Có lỗi trong lúc tải file lên server";
                echo("<div class='alert alert-danger'><strong>Lỗi!</strong> " . $mess . "</div>");
            }
        }
    }
    ?>


</body>


</html>