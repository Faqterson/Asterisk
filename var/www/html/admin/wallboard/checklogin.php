<?php
   include "../include/common_func.php";
   $user_check = null;
   $login_session = null;

   session_start();

   if (isset($_SESSION["user_session"]["uid"])) {
      $user_check = $_SESSION["user_session"]["uid"];
   }

   $sql = sprintf("SELECT user_id FROM webusers WHERE user_id = ?");

   $row = db_query($sql,array($user_check));

   if (isset($row[0]['user_id'])) {
      $login_session = $row[0]['user_id'];
   }

   if( !isset($_SESSION["user_session"]["uid"]) || !isset($login_session)){
      header("location:login.php");
   }

?>

