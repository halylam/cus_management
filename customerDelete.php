<?php
  require_once("Includes/db.php");
  
  DBUtil::getInstance()->deleteCustomer($_GET['cusId']);
  header('Location: listCustomer.php' );
?>
