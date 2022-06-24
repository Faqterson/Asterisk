<?php
session_start();

   unset( $_SESSION['queuename'] );

   $_SESSION['queuename']=$_POST['queuename'];
   print_r($_SESSION);
?>
