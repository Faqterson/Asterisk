<?php
include "include/common_func.php";

function extensions() {
   mysql_connect("localhost", "root", "");

   $rowsperpage = 30;

   //find page number//
   if(isset($_GET['page']) && is_numeric($_GET['page'])) {
      $page = $_GET['page'];
   }elseif(isset($_POST['page']) && is_numeric($_POST['page'])) {
      $page = $_POST['page'];
   }else{
      $page = 1;
   }
   $offset = ($page - 1) * $rowsperpage;

//   if (isset($_POST["page"])) { 
//      $page  = $_POST["page"]; 
//   } else { 
//      $page=1; 
//   };
//   $thisfile="index.php?mod=extensions";
//   $start_from = ($page-1) * 30;


   $result = mysql_query("SELECT * FROM asterisk.ext_features LEFT JOIN departments ON sip = extension ORDER by extension LIMIT $offset, $rowsperpage");
   $result2 = mysql_query("SELECT count(extension) FROM asterisk.ext_features");

   echo "<div class=''>";
   echo "<table>";
   echo "<th>Extension</th><th>Clid</th><th>DND</th><th>Pickup group</th><th>Call Group</th><th>Department</th><th>Edit</th>";
   while($row = mysql_fetch_array( $result )) { 
      echo "<tr align='center'>";
      echo "<td>".$row["extension"]."</td>";
      echo "<td>".$row["clid"]."</td>";
      echo "<td>".$row["dnd"]."</td>";
      echo "<td>".$row["pickupgroup"]."</td>";
      echo "<td>".$row["callgroup"]."</td>";
      echo "<td>".$row["department"]."</td>";
      echo "<td><a style='background: none' href = 'index.php?mod=editext&id=".$row["id"]."'><img src=img/edit-icon.png height='20' width='20'></a>&nbsp&nbsp&nbsp";
      echo "<a style='background: none' href = 'index.php?mod=delext&id=".$row["id"]."'><img class='delete' src=img/delete-icon.png height='20' width='20'></a></td>";
      echo "</tr>";
   }
   echo "</table>"; 

?>
   <table width="100%">
   <tr>
      <td align="center">
      <?php
         $resultPage = mysql_query("SELECT * FROM asterisk.ext_features");
         pagination('index.php?mod=extensions',$rowsperpage,$resultPage,$page);
      ?>
      </td>
   </tr>
   </table>
<?php

   echo "<table align='center'><tr><td style='border-bottom: none'>";
   echo "<form action='index.php?mod=addext' method='post'>";
   echo "<input class='button' type='submit' value='Add Extension' />";
   echo "</form>";

//   echo "<td style='border-bottom: none'>";
//   include 'buttons.php';
//   echo "</td>";
//   echo "</td></tr></table>";

   echo "</div>";
}

function add_extensions() {
   mysql_connect("localhost", "root", "");
   $today = date("Y-m-d 00:00:00");

   $result = mysql_query("SELECT MAX(extension) FROM asterisk.ext_features");	
   $row = mysql_fetch_row($result);
   $nextextension = $row[0] + 1;
   $ip = "192.168.1.".$nextextension;

   echo "<div class=''>";
   echo "<form action='index.php?mod=addextdetails' method='post'>";
   echo "<table>";
   echo "<th>Setting</th><th>Value</th>";
   echo "<tr><td>Extension</td><td><input type='text' name='extension' value='".$nextextension."'</input></td></tr>";
   echo "<tr><td>Name</td><td><input type='text' name='clid' value='Username'</input></td></tr>";
   echo "<tr><td>Password</td><td><input type='password' name='secret' value='".$row["secret"]."'</input></td></tr>";
   echo "<tr><td>Phone Hardware</td><td><input type='text' name='mac' value='".$row["mac"]."'</input></td></tr>";
   echo "<tr><td>Call Forward Destination</td><td><input type='text' name='callforwarddst' value='0'</input></td></tr>";
   echo "<tr><td>Call Forward Destination on Busy</td><td><input type='text' name='callforwardbusydst' value='0'</input></td></tr>";
   echo "<tr><td>Registration Server</td><td><input type='text' name='registrar' value='".$_SERVER['SERVER_ADDR']."'</input></td></tr>";
   echo "<tr><td>Hardware IP Adress</td><td><input type='text' name='ipaddr' value='".$ip."'</input></td></tr>";
   echo "<tr><td>Pickup Group</td><td><input type='text' name='pickupgroup' value='1'</input></td></tr>";
   echo "<tr><td>Call Group</td><td><input type='text' name='callgroup' value='1'</input></td></tr>";
   echo "<tr><td>Department</td><td><input type='text' name='department' value=''</input></td></tr>";
   echo "<tr><td>Do Not Disturb</td><td><select name='dnd'>";
   if ($row["dnd"] == 'yes'){
      echo "<option selected value='yes'>Yes</option> <option value='no'>No</option></select></td></tr>";
   } else { 
      echo "<option value='yes'>Yes</option> <option selected  value='no'>No</option></select></td></tr>";
   }
   echo "<tr><td>Call Waiting</td><td><select name='callwaiting'>";
   if ($row["callwaiting"] != 'yes'){
      echo "<option selected value='yes'>Yes</option> <option value='no'>No</option></select></td></tr>";
   } else { 
      echo "<option value='yes'>Yes</option> <option selected  value='no'>No</option></select></td></tr>";
   }
   echo "<tr><td>Can call International</td><td><select name='international'>";
   if ($row["international"] != 'yes'){
      echo "<option selected value='yes'>Yes</option> <option value='no'>No</option></select></td></tr>";
   } else { 
      echo "<option value='yes'>Yes</option> <option selected  value='no'>No</option></select></td></tr>";
   }
   echo "<tr><td>Can call National</td><td><select name='national'>";
   if ($row["national"] != 'yes'){
      echo "<option selected value='yes'>Yes</option> <option value='no'>No</option></select></td></tr>";
   } else { 
      echo "<option value='yes'>Yes</option> <option selected  value='no'>No</option></select></td></tr>";
   }
   echo "<tr><td>Can call Cellular</td><td><select name='cellular'>";
   if ($row["cellular"] != 'yes'){
      echo "<option selected value='yes'>Yes</option> <option value='no'>No</option></select></td></tr>";
   } else { 
      echo "<option value='yes'>Yes</option> <option selected  value='no'>No</option></select></td></tr>";
   }
   echo "<tr><td>Can call Internal</td><td><select name='internal'>";
   if ($row["internal"] != 'yes'){
      echo "<option selected value='yes'>Yes</option> <option value='no'>No</option></select></td></tr>";
   } else { 
      echo "<option value='yes'>Yes</option> <option selected  value='no'>No</option></select></td></tr>";
   }
   echo "<tr><td>Require Pin?</td><td><select name='requirepin'>";
   if ($row["requirepin"] == 'yes'){
      echo "<option selected value='yes'>Yes</option> <option value='no'>No</option></select></td></tr>";
   } else { 
      echo "<option value='yes'>Yes</option> <option selected  value='no'>No</option></select></td></tr>";
   }
   echo "<tr><td></td><td><input type='hidden' name='newid' value='yes'</input><input class='button' type='submit' value='Save'> </input> </td></tr>";
   echo "</tr>";
   echo "</table>";
   echo "</form>";
   echo "</div>";
}

function add_extensions_details() {
   mysql_connect("localhost", "root", "");

   $secret = $_POST['secret'];
   $extension = $_POST['extension'];
   $clid = $_POST['clid'];
   $mac = $_POST['mac'];
   $callforwarddst = $_POST['callforwarddst'];
   $callforwardbusydst = $_POST['callforwardbusydst'];
   $dnd = $_POST['dnd'];
   $callwaiting = $_POST['callwaiting'];
   $pickupgroup = $_POST['pickupgroup'];
   $callgroup = $_POST['callgroup'];
   $department = $_POST['department'];
   $registrar = $_POST['registrar'];
   $ipaddr = $_POST['ipaddr'];
   $international = $_POST['international'];
   $national = $_POST['national'];
   $cellular = $_POST['cellular'];
   $internal = $_POST['internal'];
   $requirepin = $_POST['requirepin'];	

   $createext = mysql_query("INSERT INTO asterisk.ext_features (extension, secret, clid, mac, callforwarddst, callforwardbusydst, dnd, callwaiting, international, national, cellular, internal, requirepin, pickupgroup, callgroup, registrar, ipaddr) VALUES ('$extension', '$secret','$clid', '$mac', '$callforwarddst', '$callforwardbusydst', '$dnd', '$callwaiting', '$international', '$national', '$cellular', '$internal', '$requirepin', '$pickupgroup', '$callgroup', '$registrar', '$ipaddr');");
   $createext = mysql_query("INSERT INTO asterisk.departments(sip,department) VALUES ('$extension','$department')");

   extensions();
}

function edit_extensions() {
   mysql_connect("localhost", "root", "");
   $today = date("Y-m-d 00:00:00");

   $result = mysql_query("SELECT * FROM asterisk.ext_features LEFT JOIN departments ON sip = extension WHERE id = ".$_GET['id']);
   $row = mysql_fetch_array( $result );
   echo "<div class=''>";
   echo "<form action='index.php?mod=modifyext' method='post'>";
   echo "<table>";
   echo "<th>Setting</th><th>Value</th>";
   echo "<tr><td>Extension</td><td><input type='text' name='extension' value='".$row["extension"]."'</input></td></tr>";
   echo "<tr><td>Name</td><td><input type='text' name='clid' value='".$row["clid"]."'</input></td></tr>";
   echo "<tr><td>Password</td><td><input type='password' name='secret' value='".$row["secret"]."'</input></td></tr>";
   echo "<tr><td>Phone Hardware</td><td><input type='text' name='mac' value='".$row["mac"]."'</input></td></tr>";
   echo "<tr><td>Call Forward Destination</td><td><input type='text' name='callforwarddst' value='".$row["callforwarddst"]."'</input></td></tr>";
   echo "<tr><td>Call Forward Destination on Busy</td><td><input type='text' name='callforwardbusydst' value='".$row["callforwardbusydst"]."'</input></td></tr>";
   echo "<tr><td>Registration Server</td><td><input type='text' name='registrar' value='".$row["registrar"]."'</input></td></tr>";
   echo "<tr><td>Hardware IP Adress</td><td><input type='text' name='ipaddr' value='".$row["ipaddr"]."'</input></td></tr>";
   echo "<tr><td>Pickup Group</td><td><input type='text' name='pickupgroup' value='".$row["pickupgroup"]."'</input></td></tr>";
   echo "<tr><td>Call Group</td><td><input type='text' name='callgroup' value='".$row["callgroup"]."'</input></td></tr>";
   echo "<tr><td>Department</td><td><input type='text' name='department' value='".$row["department"]."'</input></td></tr>";
   echo "<tr><td>Do Not Disturb</td><td><select name='dnd'>";
   if ($row["dnd"] == 'yes'){
      echo "<option selected value='yes'>Yes</option> <option value='no'>No</option></select></td></tr>";
   } else { 
      echo "<option value='yes'>Yes</option> <option selected  value='no'>No</option></select></td></tr>";
   }
   echo "<tr><td>Call Waiting</td><td><select name='callwaiting'>";
   if ($row["callwaiting"] == 'yes'){
      echo "<option selected value='yes'>Yes</option> <option value='no'>No</option></select></td></tr>";
   } else { 
      echo "<option value='yes'>Yes</option> <option selected  value='no'>No</option></select></td></tr>";
   }
   echo "<tr><td>Can call International</td><td><select name='international'>";
   if ($row["international"] == 'yes'){
      echo "<option selected value='yes'>Yes</option> <option value='no'>No</option></select></td></tr>";
   } else { 
      echo "<option value='yes'>Yes</option> <option selected  value='no'>No</option></select></td></tr>";
   }
   echo "<tr><td>Can call National</td><td><select name='national'>";
   if ($row["national"] == 'yes'){
      echo "<option selected value='yes'>Yes</option> <option value='no'>No</option></select></td></tr>";
   } else { 
      echo "<option value='yes'>Yes</option> <option selected  value='no'>No</option></select></td></tr>";
   }
   echo "<tr><td>Can call Cellular</td><td><select name='cellular'>";
   if ($row["cellular"] == 'yes'){
      echo "<option selected value='yes'>Yes</option> <option value='no'>No</option></select></td></tr>";
   } else { 
      echo "<option value='yes'>Yes</option> <option selected  value='no'>No</option></select></td></tr>";
   }
   echo "<tr><td>Can call Internal</td><td><select name='internal'>";
   if ($row["internal"] == 'yes'){
      echo "<option selected value='yes'>Yes</option> <option value='no'>No</option></select></td></tr>";
   } else { 
      echo "<option value='yes'>Yes</option> <option selected  value='no'>No</option></select></td></tr>";
   }
   echo "<tr><td>Require Pin?</td><td><select name='requirepin'>";
   if ($row["requirepin"] == 'yes'){
      echo "<option selected value='yes'>Yes</option> <option value='no'>No</option></select></td></tr>";
   } else { 
      echo "<option value='yes'>Yes</option> <option selected  value='no'>No</option></select></td></tr>";
   }
   echo "<tr><td></td><td><input type='hidden' name='id' value='".$row["id"]."'</input><input class='button' type='submit' value='Save'> </input> </td></tr>";
   echo "</tr>";
   echo "</table>";
   echo "</form>";
   echo "</div>";
}

function edit_extensions_details() {
   mysql_connect("localhost", "root", "");

   $secret = $_POST['secret'];
   $extension = $_POST['extension'];
   $clid = $_POST['clid'];
   $mac = $_POST['mac'];
   $callforwarddst = $_POST['callforwarddst'];
   $callforwardbusydst = $_POST['callforwardbusydst'];
   $dnd = $_POST['dnd'];
   $callwaiting = $_POST['callwaiting'];
   $pickupgroup = $_POST['pickupgroup'];
   $callgroup = $_POST['callgroup'];
   $department = $_POST['department'];
   $registrar = $_POST['registrar'];
   $ipaddr = $_POST['ipaddr'];
   $international = $_POST['international'];
   $national = $_POST['national'];
   $cellular = $_POST['cellular'];
   $internal = $_POST['internal'];
   $requirepin = $_POST['requirepin'];
   $id = $_POST['id'];

   $updateext = mysql_query("UPDATE asterisk.ext_features SET extension='$extension', secret='$secret', clid='$clid', mac='$mac', callforwarddst='$callforwarddst', callforwardbusydst='$callforwardbusydst', dnd='$dnd', callwaiting='$callwaiting', international='$international', national='$national', cellular='$cellular', internal='$internal', requirepin='$requirepin', pickupgroup='$pickupgroup', callgroup='$callgroup', registrar='$registrar', ipaddr='$ipaddr' WHERE id='$id ';");
   $updateext = mysql_query("UPDATE asterisk.departments SET department='$department' WHERE sip='$extension';");

   extensions();
}

function del_extensions() {
   $id = $_GET['id'];
   $result = mysql_query("SELECT extension FROM ext_features WHERE id = '$id'");
   $row = mysql_fetch_array($result);

   $createext = mysql_query("DELETE FROM asterisk.ext_features WHERE id='$id';");
   $createext = mysql_query("DELETE FROM asterisk.departments WHERE sip='$row[0]';");

   extensions();
}

function bulkextcreate() {
   echo "Coming Soon";
}

function csvimport() {
//   echo "Coming Soon";
?>
<div>
<form name="upload" id="upload" action="index.php?mod=upload_csvimport" method="post" enctype="multipart/form-data" class="" >
   <div style="padding-left: 100px; padding-right: 100px">
   <table style="width: 100%">
   <tr>
      <td>
         <label class="" for="description">Select File</label>
      </td>
      <td colspan="4">
         <input type="file" name="file" id="file" class="input-large">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="description">Import data</label>
      </td>
      <td colspan="4">
         <button type="submit" id="submit" name="import" class="button" data-loading-text="Loading...">Import</button>
      </td>
   </tr>
   </table>
</form>
</div>
<br>
<br>
<form name="download" id="download" action="modules/extensions/csvimport_func.php" method="post" enctype="multipart/form-data" class="" >
   <div style="padding-left: 100px; padding-right: 100px">
   <table style="width: 100%">
   <tr>
      <td>
         <label class="" for="description">Download Template</label>
      </td>
      <td colspan="4">
         <button type="submit" id="submit" name="download" class="button" data-loading-text="Loading...">Download</button>
      </td>
   </tr>
   </table>
</form>
<?php
}

function upload_csvimport() {
   mysql_connect("localhost", "root", "");

   if(isset($_POST["import"]))
   {    
      $filename=$_FILES["file"]["tmp_name"];
      if($_FILES["file"]["size"] > 0)
      {
         $lines = 1;
         $handle = fopen($filename, "r");
         echo "<table>";

         while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $num = count($data);
            if ($lines != '1') {
               $name = $data['0'];
               $callerid = $data['1'];
               $secret = $data['2'];
               $accountcode = $data['3'];
               $namedcallgroup = $data['4'];
               $namedpickupgroup = $data['5'];
               $department = $data['6'];
               $disallow = $data['7'];
               $allow = $data['8'];
               $videosupport = $data['9'];
               $dtmfmode = $data['10'];
               $context = $data['11'];

               $outcallerid = $data['20'];
               $queue_out = $data['21'];
               $callforwarddst = $data['22'];
               $callforwardbusydst = $data['23'];
               $dnd = $data['24'];
               $callwaiting = $data['25'];
               $international = $data['26'];
               $national = $data['27'];
               $cellular = $data['28'];
               $internal = $data['29'];
               $requirepin = $data['30'];	

               $mac = $data['12'];
               $vlan = $data['13'];
               $registrar = $data['14'];
               $dhcp = $data['15'];
               $ip = $data['16'];
               $subnet = $data['17'];
               $gateway = $data['18'];
               $dns = $data['19'];

               $result = mysql_query("SELECT name FROM sip WHERE name = '$name'");
               $row = mysql_fetch_array( $result );

               if (isset($row['name'])) {
                  mysql_query("UPDATE sip SET callerid = '$callerid',secret = '$secret',accountcode = '$accountcode',namedcallgroup = '$namedcallgroup',namedpickupgroup = '$namedpickupgroup',disallow = '$disallow',allow = '$allow',videosupport = '$videosupport',dtmfmode = '$dtmfmode',context = '$context' WHERE name = '$name'");
                  mysql_query("UPDATE departments SET department = '$department' WHERE sip = '$name'");
                  mysql_query("UPDATE asterisk.ext_features SET outcallerid = '$outcallerid',queue_out='$queue_out',callforwarddst='$callforwarddst', callforwardbusydst='$callforwardbusydst', dnd='$dnd', callwaiting='$callwaiting', international='$international', national='$national', cellular='$cellular', internal='$internal', requirepin='$requirepin' WHERE extension = '$name';");

                  $proresult = mysql_query("SELECT uniqueid FROM sip WHERE name = '$name'");
                  $prorow = mysql_fetch_array($proresult);
                  mysql_query("UPDATE provisioning SET mac = '$mac',vlan = '$vlan',registrar = '$registrar',dhcp = '$dhcp',ip = '$ip',subnet = '$subnet',gateway = '$gateway',dns = '$dns' WHERE sipid = '$prorow[0]'");
                  mysql_query("UPDATE queuemembers SET membername = '$callerid' WHERE interface = 'SIP/$name'"); 

                  echo "<tr><td>Updated ".$name."</td></tr>";
               } else {
                  mysql_query("INSERT INTO sip (name,callerid,secret,accountcode,namedcallgroup,namedpickupgroup,disallow,allow,videosupport,dtmfmode,context) VALUES ('$name','$callerid','$secret','$accountcode','$namedcallgroup','$namedpickupgroup','$disallow','$allow','$videosupport','$dtmfmode','$context')");
                  mysql_query("INSERT INTO departments (sip,department) VALUES ('$name','$department')");
                  mysql_query("INSERT INTO asterisk.ext_features (extension,outcallerid,queue_out,callforwarddst, callforwardbusydst, dnd, callwaiting, international, national, cellular, internal, requirepin) VALUES ('$name', '$outcallerid', '$queue_out', '$callforwarddst', '$callforwardbusydst', '$dnd', '$callwaiting', '$international', '$national', '$cellular', '$internal', '$requirepin');");
                  mysql_query("INSERT INTO dlp_extensions (context,exten,priority,app,appdata) VALUES ('extensions','$name','1','Macro','exten,\${EXTEN}')");

                  $proresult = mysql_query("SELECT uniqueid FROM sip WHERE name = '$name'");
                  $prorow = mysql_fetch_array($proresult);

                  mysql_query("INSERT INTO provisioning (sipid,mac,vlan,registrar,dhcp,ip,subnet,gateway,dns) VALUES ('$prorow[0]','$mac','$vlan','$registrar','$dhcp','$ip','$subnet','$gateway','$dns')");

                  echo "<tr><td>Imported ".$name."</td></tr>";
                }
            }
            $lines++;
         }
         echo "</table>";

         fclose($handle);
 
         mysql_close();
     }
   }   
}

function csvimport() {
?>
<div>
<form name="upload" id="upload" action="index.php?mod=upload_csvimport" method="post" enctype="multipart/form-data" class="" >
   <div style="padding-left: 100px; padding-right: 100px">
   <table style="width: 100%">
   <tr>
      <td>
         <label class="" for="description">Select File</label>
      </td>
      <td colspan="4">
         <input type="file" name="file" id="file" class="input-large">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="description">Import data</label>
      </td>
      <td colspan="4">
         <button type="submit" id="submit" name="import" class="button" data-loading-text="Loading...">Import</button>
      </td>
   </tr>
   </table>
</form>
</div>
<br>
<br>
<form name="download" id="download" action="modules/rt_extensions/csvimport_func.php" method="post" enctype="multipart/form-data" class="" >
   <div style="padding-left: 100px; padding-right: 100px">
   <table style="width: 100%">
   <tr>
      <td>
         <label class="" for="description">Download Template</label>
      </td>
      <td colspan="4">
         <button type="submit" id="submit" name="download" class="button" data-loading-text="Loading...">Download</button>
      </td>
   </tr>
   </table>
</form>
<?php
}

function upload_csvimport() {

   if(isset($_POST["import"]))
   {
      $filename=$_FILES["file"]["tmp_name"];
      if($_FILES["file"]["size"] > 0)
      {
         $lines = 1;
         $handle = fopen($filename, "r");
         echo "<table>";

         while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $num = count($data);
            if ($lines != '1') {
               $name = $data['0'];
               $callerid = $data['1'];
               $secret = $data['2'];
               $accountcode = $data['3'];
               $namedcallgroup = $data['4'];
               $namedpickupgroup = $data['5'];
               $department = $data['6'];
               $disallow = $data['7'];
               $allow = $data['8'];
               $videosupport = $data['9'];
               $dtmfmode = $data['10'];
               $context = $data['11'];

               $outcallerid = $data['20'];
               $queue_out = $data['21'];
               $callforwarddst = $data['22'];
               $callforwardbusydst = $data['23'];
               $dnd = $data['24'];
               $callwaiting = $data['25'];
               $international = $data['26'];
               $national = $data['27'];
               $cellular = $data['28'];
               $internal = $data['29'];
               $requirepin = $data['30'];

               $mac = $data['12'];
               $vlan = $data['13'];
               $registrar = $data['14'];
               $dhcp = $data['15'];
               $ip = $data['16'];
               $subnet = $data['17'];
               $gateway = $data['18'];
               $dns = $data['19'];

               $result = mysql_query("SELECT name FROM sip WHERE name = '$name'");
               $row = mysql_fetch_array( $result );

               if (isset($row['name'])) {
                  mysql_query("UPDATE sip SET callerid = '$callerid',secret = '$secret',accountcode = '$accountcode',namedcallgroup = '$namedcallgroup',namedpickupgroup = '$namedpickupgroup',disallow = '$disallow',allow = '$allow',videosupport = '$videosupport',dtmfmode = '$dtmfmode',context = '$context' WHERE name = '$name'");
                  mysql_query("UPDATE departments SET department = '$department' WHERE sip = '$name'");
                  mysql_query("UPDATE asterisk.ext_features SET outcallerid = '$outcallerid',queue_out='$queue_out',callforwarddst='$callforwarddst', callforwardbusydst='$callforwardbusydst', dnd='$dnd', callwaiting='$callwaiting', international='$international', national='$national', cellular='$cellular', internal='$internal', requirepin='$requirepin' WHERE extension = '$name';");

                  $proresult = mysql_query("SELECT uniqueid FROM sip WHERE name = '$name'");
                  $prorow = mysql_fetch_array($proresult);
                  mysql_query("UPDATE provisioning SET mac = '$mac',vlan = '$vlan',registrar = '$registrar',dhcp = '$dhcp',ip = '$ip',subnet = '$subnet',gateway = '$gateway',dns = '$dns' WHERE sipid = '$prorow[0]'");
                  mysql_query("UPDATE queuemembers SET membername = '$callerid' WHERE interface = 'SIP/$name'");

                  echo "<tr><td>Updated ".$name."</td></tr>";
               } else {
                  mysql_query("INSERT INTO sip (name,callerid,secret,accountcode,namedcallgroup,namedpickupgroup,disallow,allow,videosupport,dtmfmode,context) VALUES ('$name','$callerid','$secret','$accountcode','$namedcallgroup','$namedpickupgroup','$disallow','$allow','$videosupport','$dtmfmode','$context')");
                  mysql_query("INSERT INTO departments (sip,department) VALUES ('$name','$department')");
                  mysql_query("INSERT INTO asterisk.ext_features (extension,outcallerid,queue_out,callforwarddst, callforwardbusydst, dnd, callwaiting, international, national, cellular, internal, requirepin) VALUES ('$name', '$outcallerid', '$queue_out', '$callforwarddst', '$callforwardbusydst', '$dnd', '$callwaiting', '$international', '$national', '$cellular', '$internal', '$requirepin');");
                  mysql_query("INSERT INTO dlp_extensions (context,exten,priority,app,appdata) VALUES ('extensions','$name','1','Macro','exten,\${EXTEN}')");

                  $proresult = mysql_query("SELECT uniqueid FROM sip WHERE name = '$name'");
                  $prorow = mysql_fetch_array($proresult);

                  mysql_query("INSERT INTO provisioning (sipid,mac,vlan,registrar,dhcp,ip,subnet,gateway,dns) VALUES ('$prorow[0]','$mac','$vlan','$registrar','$dhcp','$ip','$subnet','$gateway','$dns')");

                  echo "<tr><td>Imported ".$name."</td></tr>";
                }
            }
            $lines++;
         }
         echo "</table>";

         fclose($handle);

         mysql_close();
     }
   }
}

?>
