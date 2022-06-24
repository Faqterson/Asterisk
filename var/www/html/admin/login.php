<?php
   include "include/common_func.php";

   session_start();
   
   if (isset($_POST["username"])) {
      $username = $_POST["username"];
      $password = md5($_POST["password"]);

      $sql = sprintf("SELECT user_id, name, user_type FROM webusers WHERE (username=? AND password=?)");
      $rst = db_query($sql,array($username, $password));

      if($rst[0]['user_id'] != null){
         $_SESSION["user_session"]["uid"] = $rst[0]["user_id"];
         $_SESSION["user_session"]["name"] = $rst[0]["name"];
         $_SESSION["user_session"]["type"] = $rst[0]["user_type"];
         header("location:index.php");
      } 
   }
?>
<!doctype html>
<html class="touch-styles">
<head>
   <meta charset="utf-8" />
   <title>Desktop Group: Customized VoIP solutions, Software and Hardware providers</title>
   <link rel="stylesheet" type="text/css" href="css/site.css"/>
</head>
<body>
<div class='login' align='center'>
<form action='' method='post'>
<table  align="center" width="200" >
   <th colspan="2">Desktop Network Solutions</th>
   <tr>
      <td>Username</td>
      <td><input type="text" name="username" style="width:260px;"></td>
   </tr>
   <tr>
      <td>Password</td>
      <td><input type="password" name="password" style="width:260px;"></td>
   </tr>
   <tr>
      <td align="center" colspan="2" style="text-align:center; border-bottom: none">
         <input class='button' type="submit" value="Login"/>
      </td>
   </tr>
</table>
</form>
</div>
</body>

