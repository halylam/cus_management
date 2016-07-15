<?php
  require_once("Includes/db.php");
  
  DBUtil::getInstance()->deleteUser($_GET['userId']);
  header('Location: listUser.php' );
?>