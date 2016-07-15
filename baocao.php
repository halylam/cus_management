<?php
ob_start();
session_start();
require_once("Includes/db.php");
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
                            <th>Download</th>
                        </tr>

                        <?php
                        if ($handle = opendir('uploads/' . $userId)) {
                            while (false !== ($entry = readdir($handle))) {
                                if ($entry != "." && $entry != "..") {
                                    $file = 'uploads/' . $userId . '/' . $entry;
                                    echo "<tr><td>$entry<td>";
                                    echo "<td><a onclick='".file_put_contents("Tmpfile.jpg", $file);"'/><i class='glyphicon glyphicon-pencil'></i></a><td></tr>";
                                }
                            }
                            closedir($handle);
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

            $target_dir = "uploads/" . $userId . "/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir);
            }

            $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
            $uploadOk = 1;
            $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
            if (isset($_POST["submit"])) {
                $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                if ($check !== false) {
                    echo "File is an image - " . $check["mime"] . ".";
                    $uploadOk = 1;
                } else {
                    echo "File is not an image.";
                    $uploadOk = 0;
                }
            }
// Check if file already exists
            if (file_exists($target_file)) {
                echo "Sorry, file already exists.";
                $uploadOk = 0;
            }
// Check file size
            if ($_FILES["fileToUpload"]["size"] > 500000) {
                echo "Sorry, your file is too large.";
                $uploadOk = 0;
            }
// Allow certain file formats
            if ($imageFileType != "xls" && $imageFileType != "xlsx" && $imageFileType != "pdf" && $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                echo "Sorry, only XLS, XLSX, PDF, JPG, JPEG, PNG, GIF files are allowed.";
                $uploadOk = 0;
            }
// Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
            } else {
                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                    echo "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }
            
            header('Location: baocao.php');
                exit;
        }
        ?>
    
    
    </body>
    
    
</html>
