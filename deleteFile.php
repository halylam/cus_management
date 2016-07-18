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

$file = basename($_GET['file']);
$path = basename($_GET['path']);
$file = 'uploads/' . $path . '/' . $file;
if ($file) {
    if (unlink($file)) {
        header('Location: baocao.php');
        exit;
    }
}
?>