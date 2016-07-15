<?php
ob_start();
session_start();
unset($_SESSION["userID"]);  
unset($_SESSION["userType"]);
unset($_SESSION["fullname"]);
header("Location: index.php");


