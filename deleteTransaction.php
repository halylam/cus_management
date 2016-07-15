<?php

require_once("Includes/db.php");

if ($_SERVER['REQUEST_METHOD'] == "GET") {
     $tranId = $_GET['tranId'];
     $tranItem = DBUtil::getInstance()->getTransactionDetail($tranId);
}

DBUtil::getInstance()->deleteTransaction($_GET['tranId']);
header('Location: customerDetail.php?cusId=' . $tranItem["cusId"]);
?>