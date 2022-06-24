<?php

function trunk() {
   echo "<table  id='menutable'><th>Select trunk type:</th><tr><td style='border-bottom: none' align='center'>";
   echo "<form action='index.php?mod=trunkselect' method='post'>";
   echo "<select class='button' name='trunk'><option value='iax'>IAX</option><option value='sip'>SIP</option></select><br>";
   echo "<input class='button' type='submit' value='Add/Modify Trunk' />";
   echo "</form>";
   echo "</table>";
}

function trunkselect() {
   if ($_POST['trunk'] == "sip") {
      siptrunk();
   } elseif ($_POST['trunk'] == "iax") {
      iaxtrunk();
   }
}

function siptrunk() {
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

   $row=db_query("SELECT ps_auths.id,username,server_uri from ps_auths LEFT JOIN ps_aors USING (id) LEFT JOIN ps_registrations USING (id) WHERE server_uri is not NULL LIMIT $offset, $rowsperpage", array());

   echo "<table width=100%>";
   echo "<tr><th align='left'>Name</th><th align='left'>Username</th><th align='left'>Host</th><th align='left'>Edit</th></tr>";
   foreach ($row as $key => $value)
   {
      echo "<tr>";
      echo "<td align='left'>" . $value['id'] . "</td>";
      echo "<td align='left'>" . $value['username'] . "</td>";
      echo "<td align='left'>" . $value['server_uri'] . "</td>";
      echo "<td align='left'>";
      echo "<a style='background: none' href='index.php?mod=modify_siptrunk&id=" . $value['id'] . "'><img src=img/edit-icon.png height='20' width='20'></a>&nbsp&nbsp&nbsp";
      echo "<a style='background: none' href='index.php?mod=delete_siptrunk&id=" . $value['id'] . "'><img class='delete' src=img/delete-icon.png height='20' width='20'></a></td>";
      echo "</td>";
      echo "</tr>";
   }
   echo "</table>";
?>
   <table width="100%">
   <tr>
      <td align="center">
      <?php
      $resultPage = db_query("SELECT ps_auths.id,username,server_uri from ps_auths LEFT JOIN ps_aors USING (id) LEFT JOIN ps_registrations USING (id) WHERE server_uri is not NULL", array());
      pagination('index.php?mod=siptrunk',$rowsperpage,$resultPage,$page);
      ?>
      </td>
   </tr>
   </table>
<?php
   echo "<table align='center'><tr><td style='border-bottom: none'>";
   echo "<form action='index.php?mod=add_siptrunk' method='post'>";
   echo "<input class='button' type='submit' value='Add Trunk' />";
   echo "</form>";
}

function add_siptrunk() {
?>
<form name="edit" id="edit" action="index.php?mod=add_siptrunk_details" method="post" enctype="multipart/form-data" class="" >
   <div style="padding-left: 100px; padding-right: 100px">
   <table style="width: 100%">
   <th>Setting</th><th colspan="2">Value</th>
   <tr>
      <td>
         <label class="" for="name">Trunk Name</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="id" name="id" value="" required style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="defaultuser">Default User</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="defaultuser" name="defaultuser" value="" required style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="secret">Password</label>
      </td>
      <td colspan="4">
         <input type="password" class="" id="secret" name="secret" value="" required style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="host">Host</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="host" name="host" value="" style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="disallow">Codecs Disallow</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="disallow" name="disallow" value="all" style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="allow">Codecs Allow</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="allow" name="allow" value="g729" style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="context">Context</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="context" name="context" value="external" style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="planname">Provider Name</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="planname" name="planname" value="" style="width:100%">
      </td>
   </tr>
   <tr>
      <td></td>
      <td colspan="4">
         <input type="submit" class="button" value="Add Trunk"</input>
      </td>
   </tr>

</table>
</div>
</form>
<?php
}

function add_siptrunk_details() {

   $id = $_POST['id'];
   $defaultuser = $_POST['defaultuser'];
   $secret = $_POST['secret'];
   $host = $_POST['host'];
   $disallow = $_POST['disallow'];
   $allow = $_POST['allow'];
   $context = $_POST['context'];
   $planname = $_POST['planname'];

   db_query("insert into ps_aors (id, contact, qualify_frequency, qualify_timeout) values (?, ?, ?, ?)", array($id, 'sip:'.$host.':5060', 5, 3));
   db_query("insert into ps_auths (id, auth_type, password, username) values (?, ?, ?, ?)", array($id, 'userpass', $secret, $defaultuser));
   db_query("insert into ps_endpoints (id,context,disallow,allow,outbound_auth,aors) values (?,?,?,?,?,?)", array($id,$context,$disallow,$allow,$id,$id));
   db_query("insert into ps_registrations (id, transport, outbound_auth, server_uri, client_uri, retry_interval) VALUES (?,?,?,?,?,?)", array($id,'transport-udp',$id,'sip:'.$host,'sip:'.$defaultuser.'@'.$host,'60'));
   db_query("insert into ps_endpoint_id_ips (id, endpoint, `match`) VALUES (?,?,?)", array($id, $id, $host));

   db_query("insert into tarrif_plan (planname, trunkname) VALUES (?,?)", array($planname, $id));

   db_query("INSERT INTO `tarrif_rate_domestic` VALUES ('','Mobile','^0[7][1-9]|^0[6][0-8]|^0[8][1-4]','0.019',?),('','National','^0[1-59][0-9]','0.009166667',?),('','Telkom Special','^102[36]|^1021[37]|^10210|^080|^0861','0.009166667',?),('','Other Providers','^0[8][67]','0.009166667',?)", array($planname,$planname,$planname,$planname));

   exec('sudo /usr/local/scripts/pjsiptrunks');

    // Connect to Asterisk Manager
    $socket = fsockopen("127.0.0.1","5038", $errno, $errstr, 300);
    fwrite($socket, "action: login\r\n");
    fwrite($socket, "username: admin\r\n");
    fwrite($socket, "secret: VGJd#xx&m\r\n");
    $actionid = rand(000000000,9999999999);
    fwrite($socket, "actionid: ".$actionid."\r\n\r\n");
    fwrite($socket, "action: Reload\r\n");
    fwrite($socket, "Module: res_pjsip_outbound_registration.so\r\n\r\n");

    if ($socket)
    {
     	while (!feof($socket))
        {
            $buffer = fgets($socket);
            if(stristr($buffer,"Authentication accepted"))
            {
             	break;
            }
            elseif(stristr($buffer,"Authentication failed"))
            {
             	fclose ($socket);
                echo("Username or password incorrect");
                exit();
            }
	}
    }
    siptrunk();   
}

function modify_siptrunk() {

   $ID = $_GET['id'];
   $row = db_query("SELECT ps_auths.id,username,password,server_uri,disallow,allow,context,planname from ps_auths LEFT JOIN ps_aors USING (id) LEFT JOIN ps_endpoints USING (id) LEFT JOIN ps_registrations USING (id) LEFT JOIN tarrif_plan ON (ps_auths.id = trunkname) WHERE ps_auths.id = ?", array($ID));

   $host = substr($row[0]['server_uri'], 4);

?>
<form name="edit" id="edit" action="index.php?mod=modify_siptrunk_details" method="post" enctype="multipart/form-data" class="" >
   <input name="id" id="id" value="<?php echo $ID; ?>" type="hidden">
   <div style="padding-left: 100px; padding-right: 100px">
   <table style="width: 100%">
   <th>Setting</th><th colspan="2">Value</th>
   <tr>
      <td>
         <label class="" for="name">Trunk Name</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="id" name="id" value="<?php echo $row[0]['id']; ?>" required style="width:100%">
         <input class="" id="origid" name="origid" value="<?php echo $row[0]['id']; ?>" type="hidden">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="defaultuser">Default User</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="defaultuser" name="defaultuser" value="<?php echo $row[0]['username']; ?>" required style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="secret">Password</label>
      </td>
      <td colspan="4">
         <input type="password" class="" id="secret" name="secret" value="<?php echo $row[0]['password']; ?>" required style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="host">Host</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="host" name="host" value="<?php echo $host; ?>" style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="disallow">Codecs Disallow</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="disallow" name="disallow" value="<?php echo $row[0]['disallow']; ?>" style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="allow">Codecs Allow</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="allow" name="allow" value="<?php echo $row[0]['allow']; ?>" style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="context">Context</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="context" name="context" value="<?php echo $row[0]['context']; ?>" style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="planname">Provider name</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="planname" name="planname" value="<?php echo $row[0]['planname']; ?>" style="width:100%">
         <input class="" id="origplanname" name="origplanname" value="<?php echo $row[0]['planname']; ?>" type="hidden">
      </td>
   </tr>
   <tr>
      <td></td>
      <td colspan="4">
         <input type="submit" class="button" value="Modify Trunk"</input>
      </td>
   </tr>

</table>
</div>
</form>

<?php
}

function modify_siptrunk_details() {

   $id = $_POST['id'];
   $origid = $_POST['origid'];
   $defaultuser = $_POST['defaultuser'];
   $secret = $_POST['secret'];
   $host = $_POST['host'];
   $disallow = $_POST['disallow'];
   $allow = $_POST['allow'];
   $context = $_POST['context'];
   $planname = $_POST['planname'];
   $origplanname = $_POST['origplanname'];

   db_query("UPDATE ps_aors SET id = ?, contact = ? WHERE id = ?", array($id, 'sip:'.$host.':5060', $origid));
   db_query("UPDATE ps_auths SET id = ?, auth_type = ?, password = ?, username = ? WHERE id = ?", array($id, 'userpass', $secret, $defaultuser, $origid));
   db_query("UPDATE ps_endpoints SET id = ?,context = ?,disallow = ?,allow = ?,outbound_auth = ?,aors = ? WHERE id = ?", array($id,$context,$disallow,$allow,$id,$id,$origid));
   db_query("UPDATE ps_registrations SET id = ?, transport = ?, outbound_auth = ?, server_uri = ?, client_uri = ?, retry_interval = ? WHERE id = ?", array($id,'transport-udp',$id,'sip:'.$host,'sip:'.$defaultuser.'@'.$host,'60',$origid));
   db_query("UPDATE ps_endpoint_id_ips SET id = ?, endpoint = ?, `match` = ? WHERE id = ?", array($id, $id, $host, $origid));

   db_query("UPDATE tarrif_plan SET planname = ? WHERE trunkname = ?", array($planname, $origid));
   db_query("UPDATE tarrif_rate_domestic SET planname = ? WHERE planname = ?", array($planname, $origplanname));

   exec('sudo /usr/local/scripts/pjsiptrunks');

    // Connect to Asterisk Manager
    $socket = fsockopen("127.0.0.1","5038", $errno, $errstr, 300);
    fwrite($socket, "action: login\r\n");
    fwrite($socket, "username: admin\r\n");
    fwrite($socket, "secret: VGJd#xx&m\r\n");
    $actionid = rand(000000000,9999999999);
    fwrite($socket, "actionid: ".$actionid."\r\n\r\n");
    fwrite($socket, "action: Reload\r\n");
    fwrite($socket, "Module: res_pjsip_outbound_registration.so\r\n\r\n");

    if ($socket)
    {
     	while (!feof($socket))
        {
            $buffer = fgets($socket);
            if(stristr($buffer,"Authentication accepted"))
            {
             	break;
            }
            elseif(stristr($buffer,"Authentication failed"))
            {
             	fclose ($socket);
                echo("Username or password incorrect");
                exit();
            }
	}
    }
 
   siptrunk();
}

function delete_siptrunk() {
   $id = $_GET['id'];

   db_query("DELETE FROM ps_aors WHERE id = ?", array($id));
   db_query("DELETE FROM ps_auths WHERE id = ?", array($id));
   db_query("DELETE FROM ps_endpoints WHERE id = ?", array($id));
   db_query("DELETE FROM ps_registrations WHERE id = ?", array($id));
   db_query("DELETE FROM ps_endpoint_id_ips WHERE id = ?", array($id));

   $row = db_query("SELECT planname FROM tarrif_plan WHERE trunkname = ?", array($id));
   db_query("DELETE FROM tarrif_rate_domestic WHERE planname = ?", array($row[0]['planname']));

   db_query("DELETE FROM tarrif_plan WHERE trunkname = ?", array($id));

   exec('sudo /usr/local/scripts/pjsiptrunks');

    // Connect to Asterisk Manager
    $socket = fsockopen("127.0.0.1","5038", $errno, $errstr, 300);
    fwrite($socket, "action: login\r\n");
    fwrite($socket, "username: admin\r\n");
    fwrite($socket, "secret: VGJd#xx&m\r\n");
    $actionid = rand(000000000,9999999999);
    fwrite($socket, "actionid: ".$actionid."\r\n\r\n");
    fwrite($socket, "action: Reload\r\n");
    fwrite($socket, "Module: res_pjsip_outbound_registration.so\r\n\r\n");

    if ($socket)
    {
     	while (!feof($socket))
        {
            $buffer = fgets($socket);
            if(stristr($buffer,"Authentication accepted"))
            {
             	break;
            }
            elseif(stristr($buffer,"Authentication failed"))
            {
             	fclose ($socket);
                echo("Username or password incorrect");
                exit();
            }
	}
    }

   siptrunk();
}

function iaxtrunk() {

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

   $row=db_query("SELECT name,username,host,id from iaxfriends LIMIT $offset, $rowsperpage", array());

   echo "<table width=100%>";
   echo "<tr><th align='left'>Name</th><th align='left'>Username</th><th align='left'>Host</th><th align='left'>Edit</th></tr>";
   foreach ($row as $key => $value)
   {
      echo "<tr>";
      echo "<td align='left'>" . $value['name'] . "</td>";
      echo "<td align='left'>" . $value['username'] . "</td>";
      echo "<td align='left'>" . $value['host'] . "</td>";
      echo "<td align='left'>";
      echo "<a style='background: none' href='index.php?mod=modify_iaxtrunk&id=" . $value['id'] . "'><img src=img/edit-icon.png height='20' width='20'></a>&nbsp&nbsp&nbsp";
      echo "<a style='background: none' href='index.php?mod=delete_iaxtrunk&id=" . $value['id'] . "'><img class='delete' src=img/delete-icon.png height='20' width='20'></a></td>";
      echo "</td>";
      echo "</tr>";
   }
   echo "</table>";
?>
   <table width="100%">
   <tr>
      <td align="center">
      <?php
      $resultPage = db_query("SELECT name,username,host from iaxfriends", array());
      pagination('index.php?mod=trunk',$rowsperpage,$resultPage,$page);
      ?>
      </td>
   </tr>
   </table>
<?php
   echo "<table align='center'><tr><td style='border-bottom: none'>";
   echo "<form action='index.php?mod=add_iaxtrunk' method='post'>";
   echo "<input class='button' type='submit' value='Add Trunk' />";
   echo "</form>";

}

function add_iaxtrunk() {
?>
<form name="edit" id="edit" action="index.php?mod=add_iaxtrunk_details" method="post" enctype="multipart/form-data" class="" >
   <div style="padding-left: 100px; padding-right: 100px">
   <table style="width: 100%">
   <th>Setting</th><th colspan="2">Value</th>
   <tr>
      <td>
         <label class="" for="name">Trunk Name</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="name" name="name" value="" required style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="username">Username</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="username" name="username" value="" required style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="secret">Password</label>
      </td>
      <td colspan="4">
         <input type="password" class="" id="secret" name="secret" value="" required style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="host">Host</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="host" name="host" value="" style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="type">Type</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="type" name="type" value="friend" required style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="control-label" for="qualify">Qualify</label>
      </td>
      <td colspan="4">
	 <select name="qualify" id="qualify" class="" style="width: 100%" >
            <option value="yes">Yes</option>
            <option value="no">No</option>
         </select>
      </td>
   </tr>
   <tr>
      <td>
         <label class="control-label" for="trunk">Trunk</label>
      </td>
      <td colspan="4">
	 <select name="trunk" id="trunk" class="" style="width: 100%" >
            <option value="yes">Yes</option>
            <option value="no">No</option>
         </select>
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="disallow">Codecs Disallow</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="disallow" name="disallow" value="all" style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="allow">Codecs Allow</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="allow" name="allow" value="g729" style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="context">Context</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="context" name="context" value="external" style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="planname">Provider Name</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="planname" name="planname" value="" style="width:100%">
      </td>
   </tr>
   <tr>
      <td></td>
      <td colspan="4">
         <input type="submit" class="button" value="Add Trunk"</input>
      </td>
   </tr>

</table>
</div>
</form>

<?php
}

function add_iaxtrunk_details() {

   $name = $_POST['name'];
   $username = $_POST['username'];
   $secret = $_POST['secret'];
   $type = $_POST['type'];
   $qualify = $_POST['qualify'];
   $trunk = $_POST['trunk'];
   $host = $_POST['host'];
   $disallow = $_POST['disallow'];
   $allow = $_POST['allow'];
   $context = $_POST['context'];
   $planname = $_POST['planname'];

   db_query("INSERT INTO iaxfriends (name,username,type,secret,qualify,trunk,host,disallow,allow,context) VALUES (?,?,?,?,?,?,?,?,?,?)", array($name,$username,$type,$secret,$qualify,$trunk,$host,$disallow,$allow,$context));

   db_query("insert into tarrif_plan (planname, trunkname) VALUES (?,?)", array($planname, $name));

   db_query("INSERT INTO `tarrif_rate_domestic` VALUES ('','Mobile','^0[7][1-9]|^0[6][0-8]|^0[8][1-4]','0.019',?),('','National','^0[1-59][0-9]','0.009166667',?),('','Telkom Special','^102[36]|^1021[37]|^10210|^080|^0861','0.009166667',?),('','Other Providers','^0[8][67]','0.009166667',?)", array($planname,$planname,$planname,$planname));

   exec('sudo /usr/local/scripts/iaxtrunks');

   iaxtrunk();
}

function modify_iaxtrunk() {

   $ID = $_GET['id'];
   $row = db_query("SELECT name,username,type,secret,qualify,trunk,host,disallow,allow,context,planname FROM iaxfriends LEFT JOIN tarrif_plan ON (name = trunkname) WHERE id = ?", array($ID));

?>
<form name="edit" id="edit" action="index.php?mod=modify_iaxtrunk_details" method="post" enctype="multipart/form-data" class="" >
   <input name="id" id="id" value="<?php echo $ID; ?>" type="hidden">
   <div style="padding-left: 100px; padding-right: 100px">
   <table style="width: 100%">
   <tr>
      <td>
         <label class="" for="name">Trunk Name</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="name" name="name" value="<?php echo $row[0]['name']; ?>" required style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="username">Username</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="username" name="username" value="<?php echo $row[0]['username']; ?>" required style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="secret">Password</label>
      </td>
      <td colspan="4">
         <input type="password" class="" id="secret" name="secret" value="<?php echo $row[0]['secret']; ?>" required style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="host">Host</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="host" name="host" value="<?php echo $row[0]['host']; ?>" style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="type">Type</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="type" name="type" value="<?php echo $row[0]['type']; ?>" required style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="control-label" for="qualify">Qualify</label>
      </td>
      <td colspan="4">
	 <select name="qualify" id="qualify" class="" style="width: 100%" >
<?php
            if (isset($row[0]['qualify'])) {
?>
            <option value="<?php echo $row[0]['qualify']; ?>" ><?php echo $row[0]['qualify']; ?></option>
<?php
            } else {
?>
            <option value="no">No</option>            
            <option value="yes">Yes</option>            
<?php
            }
            if ($row[0]['qualify'] == "yes") {
?>
            <option value="no">No</option>
<?php
            } elseif ($row[0]['qualify'] == "no") {
?>
            <option value="yes">Yes</option>
<?php
            }
?>
         </select>
      </td>
   </tr>
   <tr>
      <td>
         <label class="control-label" for="trunk">Trunk</label>
      </td>
      <td colspan="4">
	 <select name="trunk" id="trunk" class="" style="width: 100%" >
<?php
            if (isset($row[0]['trunk'])) {
?>
            <option value="<?php echo $row[0]['trunk']; ?>" ><?php echo $row[0]['trunk']; ?></option>
<?php
            } else {
?>
            <option value="no">No</option>            
            <option value="yes">Yes</option>            
<?php
            }
            if ($row[0]['trunk'] == "yes") {
?>
            <option value="no">No</option>
<?php
            } elseif ($row[0]['trunk'] == "no") {
?>
            <option value="yes">Yes</option>
<?php
            }
?>
         </select>
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="disallow">Codecs Disallow</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="disallow" name="disallow" value="<?php echo $row[0]['disallow']; ?>" style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="allow">Codecs Allow</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="allow" name="allow" value="<?php echo $row[0]['allow']; ?>" style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="context">Context</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="context" name="context" value="<?php echo $row[0]['context']; ?>" style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="planname">Provider Name</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="planname" name="planname" value="<?php echo $row[0]['planname']; ?>" style="width:100%">
         <input class="" id="origplanname" name="origplanname" value="<?php echo $row[0]['planname']; ?>" type="hidden">
      </td>
   </tr>
   <tr>
      <td></td>
      <td colspan="4">
         <input type="submit" class="button" value="Modify Trunk"</input>
      </td>
   </tr>

</table>
</div>
</form>

<?php
}

function modify_iaxtrunk_details() {

   $id = $_POST['id'];
   $name = $_POST['name'];
   $username = $_POST['username'];
   $secret = $_POST['secret'];
   $type = $_POST['type'];
   $qualify = $_POST['qualify'];
   $trunk = $_POST['trunk'];
   $host = $_POST['host'];
   $disallow = $_POST['disallow'];
   $allow = $_POST['allow'];
   $context = $_POST['context'];
   $planname = $_POST['planname'];
   $origplanname = $_POST['origplanname'];

   db_query("UPDATE iaxfriends SET name = ?,username = ?,type = ?,secret = ?,qualify = ?,trunk = ?,host = ?,disallow = ?,allow = ?,context = ? WHERE id = ?", array($name,$username,$type,$secret,$qualify,$trunk,$host,$disallow,$allow,$context,$id)); 

   db_query("UPDATE tarrif_plan SET planname = ? WHERE trunkname = ?", array($planname, $name));
   db_query("UPDATE tarrif_rate_domestic SET planname = ? WHERE planname = ?", array($planname, $origplanname));

   exec('sudo /usr/local/scripts/iaxtrunks');

   iaxtrunk();
}

function delete_iaxtrunk() {

   $id = $_GET['id'];

   $row = db_query("SELECT name FROM iaxfriends WHERE id = ?", array($id));
   db_query("DELETE FROM iaxfriends WHERE id = ?", array($id));

   $row2 = db_query("SELECT planname FROM tarrif_plan WHERE trunkname = ?", array($row[0]['name']));
   db_query("DELETE FROM tarrif_rate_domestic WHERE planname = ?", array($row2[0]['planname']));

   db_query("DELETE FROM tarrif_plan WHERE trunkname = ?", array($row[0]['name']));

   exec('sudo /usr/local/scripts/iaxtrunks');

   iaxtrunk();
}
?>

