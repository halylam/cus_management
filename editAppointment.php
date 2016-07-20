<?php
ob_start();
session_start();
require_once("Includes/db.php");
$cusIsEmpty = false;
$datetimeIsEmpty = false;
$positionIsEmpty = false;
if (isset($_SESSION['userID'])) {
    $userId = $_SESSION['userID'];
    $userType = $_SESSION['userType'];
    $fullname = $_SESSION['fullname'];
} else {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET['appId'])) {
        $appId = $_GET['appId'];
        $appItem = DBUtil::getInstance()->getListAppointmentByAppId($appId);
    }
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $appItem = array("appId" => $_POST['appId'],
        "cusId" => $_POST['cusId'],
        "datetime" => $_POST['datetime'],
        "reason" => $_POST['reason'],
        "position" => $_POST['position']);
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
        <title>Chỉnh sửa lịch hẹn</title>
    </head>
    <body>
        <?php include 'header.php' ?>
        <h2 style="color: #188420;"><center>THÔNG TIN CHI TIẾT CUỘC HẸN</center></h2>

        <div style="width: 90%">
            <form class="form-horizontal" role="form" action="editAppointment.php" method="POST" >
                <input type="hidden" name="appId" value="<?php echo $appItem["appId"]; ?>" />
                <div class="form-group">
                    <label class="control-label col-sm-2" for="cusId">Khách hàng<label style="color: red">(*)</label>: </label>
                    <div class="col-sm-10">
                        <select class="selectpicker"  name='cusId' id='cusId' value='<?php echo $appItem["cusId"]; ?>'>
                            <option value=''>Chọn</option>
                            <?php
                            if ($userType == 'Admin') {
                                $listCus = DBUtil::getInstance()->getListCustomerAdmin();
                            } else {
                                $listCus = DBUtil::getInstance()->getListCustomer($userId);
                            }

                            while ($row = mysqli_fetch_array($listCus)) {
                                if ($appItem["cusId"] == $row["cusId"]) {
                                    $selected = ' selected="selected"';
                                } else {
                                    $selected = '';
                                }
                                echo "<option value=" . htmlentities($row["cusId"]) . $selected . ">" . htmlentities($row["cusName"]) . "</option>\n";
                                "<td>" . htmlentities($row["position"]) . "</td></tr>\n";
                            }
                            mysqli_free_result($listCus);
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-2" for="datetime">Ngày giờ hẹn<label style="color: red">(*)</label>: </label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="datetime" id="datetimepicker" value="<?php echo date('d-m-Y H:i:s', strtotime($appItem["datetime"])); ?>" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-2" for="position">Địa điểm<label style="color: red">(*)</label>: </label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="position" value="<?php echo $appItem["position"]; ?>" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-2" for="reason">Lý do: </label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="reason" value="<?php echo $appItem["reason"]; ?>" />
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
            /** Check whether validate required */
            if ($_POST['cusId'] == "") {
                $cusIsEmpty = true;
                $mess = $mess . "<br/>Khách hàng bắt buộc nhập ";
            }

            if ($_POST['datetime'] == "") {
                $datetimeIsEmpty = true;
                $mess = $mess . "<br/>Ngày giờ hẹn bắt buộc nhập ";
            }
            if ($_POST['position'] == "") {
                $positionIsEmpty = true;
                $mess = $mess . "<br/>Địa điểm bắt buộc nhập ";
            }
            if (!$cusIsEmpty && !$datetimeIsEmpty && !$positionIsEmpty) {
                DBUtil::getInstance()->updateAppointment($_POST["cusId"], date('Y-m-d H:i:s', strtotime($_POST["datetime"])), $_POST["position"], $_POST["reason"], $_POST["appId"]);
                header('Location: customerDetail.php?cusId=' . $_POST["cusId"]);
                exit;
            } else {
                echo("<div class='alert alert-danger'><strong>Lỗi!</strong> " . $mess . "</div>");
            }
        }
        ?>
    </body>
    <script>
        jQuery('#datetimepicker').datetimepicker({
            format: 'd-m-Y H:i'
        });
    </script>
</html>
