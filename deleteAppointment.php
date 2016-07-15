<?php

require_once("Includes/db.php");

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $appItem = DBUtil::getInstance()->getListAppointmentByAppId($appId);
}

DBUtil::getInstance()->deleteAppointment($_GET['appId']);
header('Location: customerDetail.php?cusId=' . $appItem["cusId"]);
?>