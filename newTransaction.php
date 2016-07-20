<?php
ob_start();
session_start();
require_once("Includes/db.php");
$cusIsEmpty = false;
$datetimeIsEmpty = false;
$toalAmountIsEmpty = false;
if (isset($_SESSION['userID'])) {
    $userId = $_SESSION['userID'];
    $fullname = $_SESSION['fullname'];
} else {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Thêm Mới Giao Dịch</title>
    </head>
    <body>
        <?php include 'header.php' ?>
        <h2 style="color: #188420;"><center>THÔNG TIN CHI TIẾT GIAO DỊCH</center></h2>

        <div style="width: 90%">
            <form class="form-horizontal" role="form" action="newTransaction.php" method="POST" >
                <div class="form-group">
                    <label class="control-label col-sm-2" for="cusId">Khách hàng<label style="color: red">(*)</label>: </label>
                    <div class="col-sm-10">
                        <select class="selectpicker"  name='cusId' id='cusId'>
                            <option value=''>Chọn</option>
                            <?php
                            $listCus = DBUtil::getInstance()->getListCustomer($userId);
                            while ($row = mysqli_fetch_array($listCus)) {
                                echo "<option value=" . htmlentities($row["cusId"]) . ">" . htmlentities($row["cusName"]) . "</option>\n";
                                "<td>" . htmlentities($row["position"]) . "</td></tr>\n";
                            }
                            mysqli_free_result($listCus);
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-2" for="datetime">Ngày giờ giao dịch<label style="color: red">(*)</label>: </label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="datetime" id="datetimepicker" value="" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-2" for="project">Dự án: </label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="project" value="" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-2" for="assetNum">Số đất/Số nhà: </label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="assetNum" value="" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-2" for="size">Diện tích: </label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="size" value="" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-2" for="totalAmount">Tổng tiền<label style="color: red">(*)</label>: </label>
                    <div class="col-sm-10">
                        <input type="text" class="numberOnly form-control" name="totalAmount" value="" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-2" for="depositAmount">Đặt cọc: </label>
                    <div class="col-sm-10">
                        <input type="text" class="numberOnly form-control" name="depositAmount" value="" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-2" for="note">Ghi chú: </label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="note" value="" />
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
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $mess = '';
            /** Check whether validate required */
            if ($_POST['cusId'] == "") {
                $cusIsEmpty = true;
                $mess = $mess . "<br/>Khách hàng bắt buộc nhập ";
            }

            if ($_POST['datetime'] == "") {
                $datetimeIsEmpty = true;
                $mess = $mess . "<br/>Ngày giờ giao dịch bắt buộc nhập ";
            }
            if ($_POST['totalAmount'] == "") {
                $toalAmountIsEmpty = true;
                $mess = $mess . "<br/>Tổng tiền bắt buộc nhập ";
            }
            if (!$cusIsEmpty && !$datetimeIsEmpty && !$toalAmountIsEmpty) {
                DBUtil::getInstance()->insertTransaction($_POST["cusId"], $_POST["project"], str_replace(',', '', $_POST["depositAmount"]), str_replace(',', '', $_POST["totalAmount"]), $_POST["note"], date('Y-m-d H:i:s', strtotime($_POST["datetime"])), $_POST["size"], $_POST["assetNum"], $userId);
                header('Location: mainPage.php');
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

        $(".numberOnly").keypress(function (e) {
            //if the letter is not digit then display error and don't type anything
            if (e.which != 8 && e.which != 46 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                return false;
            } else {
                $('input').keyup(function () {
                    formatNumber(this);
                });
            }
        });

        $(".numberOnly").change(function () {
            formatNumber(this);
        });

        function replaceAll(str, find, replace) {
            return str.replace(new RegExp(find, 'g'), replace);
        }
        function formatNumber(obj) {
            var tmp = replaceAll(obj.value.toString(), ',', '');
            obj.value = tmp.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        $(document).ready(function () {
            $(".numberOnly").each(function () {
                formatNumber(this);
            });
        });

    </script>
</html>
