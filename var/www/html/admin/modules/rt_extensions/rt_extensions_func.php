<?php

function rt_extensions() {
   $rowsperpage = 100;

   //find page number//
   if(isset($_GET['page']) && is_numeric($_GET['page'])) {
      $page = $_GET['page'];
   }elseif(isset($_POST['page']) && is_numeric($_POST['page'])) {
      $page = $_POST['page'];
   }else{
      $page = 1;
   }
   $offset = ($page - 1) * $rowsperpage;

   $depallow = check_department_access();

   if ($depallow != "") {
      $result=db_query("SELECT ps_endpoints.id,callerid,requirepin,named_call_group,named_pickup_group,department FROM ps_endpoints LEFT JOIN departments ON (sip = ps_endpoints.id) LEFT JOIN ext_features ON (ps_endpoints.id = extension) LEFT JOIN ps_registrations ON (ps_endpoints.id = ps_registrations.id) WHERE department = '$depallow' AND ps_registrations.id is null ORDER BY id ASC LIMIT $offset, $rowsperpage", array());
   } else {
      $result=db_query("SELECT ps_endpoints.id,callerid,requirepin,named_call_group,named_pickup_group,department FROM ps_endpoints LEFT JOIN departments ON (sip = ps_endpoints.id) LEFT JOIN ext_features ON (ps_endpoints.id = extension) LEFT JOIN ps_registrations ON (ps_endpoints.id = ps_registrations.id) WHERE ps_registrations.id is null ORDER BY id ASC LIMIT $offset, $rowsperpage", array());
   }
   echo "<table width=100%>";
   echo "<tr><th align='left'>Extension</th><th align='left'>Name</th><th align='left'>Require Pin</th><th align='left'>Call Group</th><th align='left'>Pickup Group</th><th align='left'>Department</th><th align='left'>Edit</th></tr>";
   foreach ($result as $key => $row)
   {
      echo "<tr>";
      echo "<td align='left'>" . $row['id'] . "</td>";
      echo "<td align='left'>" . $row['callerid'] . "</td>";
      echo "<td align='left'>" . $row['requirepin'] . "</td>";
      echo "<td align='left'>" . $row['named_call_group'] . "</td>";
      echo "<td align='left'>" . $row['named_pickup_group'] . "</td>";
      echo "<td align='left'>" . $row['department'] . "</td>";
      echo "<td align='left'>";
      echo "<a style='background: none' href='index.php?mod=modify_rt_extensions&id=" . $row['id'] . "' title=''><img src=img/edit-icon.png height='20' width='20'></a>&nbsp";
      echo "<a style='background: none' href='index.php?mod=delete_rt_extensions&id=" . $row['id'] . "' title=''><img class='delete' src=img/delete-icon.png height='20' width='20'></a>";
      echo "</td>";
      echo "</tr>";
   }
   echo "</table>";
?>
   <table width="100%" border="0" cellpadding="2" cellspacing="2">
   <tr>
      <td align="center">
      <?php
      if ($depallow != "") {
         $resultPage = db_query("SELECT id,callerid,named_call_group,named_pickup_group,department FROM ps_endpoints LEFT JOIN departments ON (sip = id) WHERE department = '$depallow'");
      } else {
         $resultPage = db_query("SELECT id,callerid,named_call_group,named_pickup_group,department FROM ps_endpoints LEFT JOIN departments ON (sip = id)", array()); 
      }
      pagination('index.php?mod=rt_extensions',$rowsperpage,$resultPage,$page);
      ?>
      </td>
   </tr>
   </table>

<?php
   echo "<form action='index.php?mod=add_rt_extensions' method='post'>";
   echo "<input class='button' type='submit' value='Add Extension' />";
   echo "</form>";
}

function add_rt_extensions() {
?>
<div style="padding-left: 100px; padding-right: 100px">
<table class="custborder" width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
   <td class="popup" align="left" width="50%">
   <ul>
      <li class="popup"><a href="#Edit" id="menu1">Setup</a></li>
      <li class="popup"><a href="#Features" id="menu2">Features</a></li>
      <li class="popup"><a href="#provisioning" id="menu3">Provisioning</a></li>
   </ul> 
   </td> 
   <td class="popup" align="right" width="35%"> 
      <input name="submit" type="submit" value="Add Extension" class="submit button" id="submitButton">&nbsp;&nbsp;&nbsp;
   </td> 
</tr>
</table>
</div>

<form name="PbxForm" id="PbxForm" action="index.php?mod=add_rt_extensions_details" method="post" enctype="multipart/form-data" class="myForms" >
   <div style="padding-left: 100px; padding-right: 100px">
   <table style="width: 100%">
   <tr>
      <td>
         <label class="" for="name">Extension Number</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="name" name="name" value="" required style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="callerid">Extension Name</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="callerid" name="callerid" value="" required style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="secret">Password</label>
      </td>
      <td colspan="4">
         <input type="password" class="" id="secret" name="secret" value="Random4125" required style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="namedcallgroup">Named Call Group</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="namedcallgroup" name="namedcallgroup" value="" style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="namedpickupgroup">Named Pickup Group</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="namedpickupgroup" name="namedpickupgroup" value="" style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="department">Department</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="department" name="department" value="" style="width:100%">
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
         <label class="" for="dtmf_mode">DTMF Signaling</label>
      </td>
      <td colspan="4">
	 <select class="button" name="dtmfmode" id="dtmfmode" class="" style="width: 100%" >
            <option value="rfc4733">RFC4733</option>
            <option value="info">SIP Info</option>
            <option value="inband">Inband</option>
            <option value="auto">Auto</option>
            <option value="auto_info">Auto Info</option>
         </select>
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="context">Context</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="context" name="context" value="extensions" style="width:100%">
      </td>
   </tr>
</table>
</div>
</form>
<form action="index.php?mod=add_rt_extensions_details" method="post" name="PbxForm1" id="PbxForm1" class="myForms">
<div style="padding-left: 100px; padding-right: 100px">
   <table width="100%" border="0" cellpadding="2" cellspacing="2" class="border">
      <tr>
         <td>Outbound Callerid</td>
         <td><input type='text' name='outcallerid' value='' style="width: 100%"</input></td>
      </tr>
      <tr>
         <td>Outbound Queue</td>
         <td><input type='text' name='queue_out' value='' style="width: 100%"</input></td>
      </tr>
      <tr>
         <td>Call Forward Destination</td>
         <td><input type='text' name='callforwarddst' value='0' style="width: 100%"</input></td>
      </tr>
      <tr>
         <td>Call Forward Destination on Busy</td>
         <td><input type='text' name='callforwardbusydst' value='0' style="width: 100%"</input></td>
      </tr>
      <tr>
         <td>Do Not Disturb</td>
         <td>
             <select class="button" name='dnd'>
                <option selected value='no'>No</option>
                <option value='yes'>Yes</option>
             </select>
         </td>
      </tr>
      <tr>
         <td>Call Waiting</td>
         <td>
            <select class="button" name='callwaiting'>";
                <option selected value='yes'>Yes</option>
                <option value='no'>No</option>
            </select>
         </td>
       </tr>
       <tr>
          <td>Can call International</td>
          <td>
             <select class="button" name='international'>
                <option selected value='yes'>Yes</option>
                <option value='no'>No</option>
             </select>
         </td>
       </tr>
       <tr>
          <td>Can call National</td>
          <td>
             <select class="button" name='national'>
                <option selected value='yes'>Yes</option>
                <option value='no'>No</option>
             </select>
         </td>
       </tr>
       <tr>
          <td>Can call Cellular</td>
          <td>
             <select class="button" name='cellular'>";
                <option selected value='yes'>Yes</option>
                <option value='no'>No</option>
             </select>
         </td>
       </tr>
       <tr>
          <td>Can call Internal</td>
          <td>
             <select class="button" name='internal'>
                <option selected value='yes'>Yes</option>
                <option value='no'>No</option>
             </select>
         </td>
       </tr>
       <tr>
          <td>Require Pin?</td>
          <td>
             <select class="button" name='requirepin'>
                <option selected value='no'>No</option>
                <option value='yes'>Yes</option>
             </select>
         </td>
       </tr>

   </table>
</div>
</form>
<form action="index.php?mod=add_rt_extensions_details" method="post" name="PbxForm2" id="PbxForm2" class="myForms">
<div style="padding-left: 100px; padding-right: 100px">
   <table width="100%" border="0" cellpadding="2" cellspacing="2" class="border">
   <tr>
      <td>
         <label class="" for="mac">MAC</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="mac" name="mac" value="" style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="vlan">VLAN</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="vlan" name="vlan" value="" style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="registrar">Registrar</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="registrar" name="registrar" value="<?php echo $_SERVER['SERVER_NAME']; ?>" style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="dhcp">DHCP</label>
      </td>
      <td colspan="4">
         <select class="button" name="dhcp" id="dhcp" class="" style="width: 100%" >
            <option value="1">Yes</option>
            <option value="0">No</option>
         </select>
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="ip">IP</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="ip" name="ip" value="" style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="subnet">Sub Net</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="subnet" name="subnet" value="" style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="gateway">Gateway</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="gateway" name="gateway" value="" style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="dns">DNS</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="dns" name="dns" value="" style="width:100%">
      </td>
   </tr>
   </table>
</div>
</form>

<?php
}

function add_rt_extensions_details() {
   $id = $_POST['name'];
   $callerid = $_POST['callerid'];
   $secret = $_POST['secret'];
   $namedcallgroup = $_POST['namedcallgroup'];
   $namedpickupgroup = $_POST['namedpickupgroup'];
   $department = $_POST['department'];
   $disallow = $_POST['disallow'];
   $allow = $_POST['allow'];
   $dtmfmode = $_POST['dtmfmode'];
   $context = $_POST['context'];

   $outcallerid = $_POST['outcallerid'];
   $queue_out = $_POST['queue_out'];
   $callforwarddst = $_POST['callforwarddst'];
   $callforwardbusydst = $_POST['callforwardbusydst'];
   $dnd = $_POST['dnd'];
   $callwaiting = $_POST['callwaiting'];
   $international = $_POST['international'];
   $national = $_POST['national'];
   $cellular = $_POST['cellular'];
   $internal = $_POST['internal'];
   $requirepin = $_POST['requirepin'];	

   $mac = $_POST['mac'];
   $vlan = $_POST['vlan'];
   $registrar = $_POST['registrar'];
   $dhcp = $_POST['dhcp'];
   $ip = $_POST['ip'];
   $subnet = $_POST['subnet'];
   $gateway = $_POST['gateway'];
   $dns = $_POST['dns'];

   db_query("insert into ps_aors (id, max_contacts, qualify_frequency, qualify_timeout) values (?, ?, ?, ?);", array($id, 10, 5, 3));
   db_query("insert into ps_auths (id, auth_type, password, username) values (?, ?, ?, ?)", array($id, 'userpass', $secret, $id));
   db_query("insert into ps_endpoints (id, transport, aors, auth, context, disallow, allow, direct_media, force_rport, mailboxes, moh_suggest, callerid, named_call_group, named_pickup_group, tos_audio, tos_video, webrtc, dtmf_mode, disable_direct_media_on_nat, rtp_symmetric, rewrite_contact) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", array($id, 'transport-udp', $id, $id, $context, $disallow, $allow, 'no', 'yes', $id, $department, $callerid, $namedcallgroup, $namedpickupgroup, '0xb8', '0xb8', 'no', $dtmfmode, 'yes', 'yes', 'yes', 'yes'));

   db_query("INSERT INTO departments (sip,department) VALUES (?,?)", array($id,$department));
   db_query("INSERT INTO ext_features (extension,outcallerid,queue_out,callforwarddst, callforwardbusydst, dnd, callwaiting, international, national, cellular, internal, requirepin) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", array($id, $outcallerid, $queue_out, $callforwarddst, $callforwardbusydst, $dnd, $callwaiting, $international, $national, $cellular, $internal, $requirepin));
   db_query("INSERT INTO extensions (context,exten,priority,app,appdata) VALUES (?,?,?,?,?)", array('extensions',$id,'1','Gosub','exten,s,1(${EXTEN})'));

   db_query("INSERT INTO provisioning (sipid,mac,vlan,registrar,dhcp,ip,subnet,gateway,dns) VALUES (?,?,?,?,?,?,?,?,?)", array($id,$mac,$vlan,$registrar,$dhcp,$ip,$subnet,$gateway,$dns));
}

function modify_rt_extensions() {

   $ID = $_GET['id'];
   $row = db_query("SELECT password, context, disallow, allow, callerid, named_call_group, named_pickup_group, department, dtmf_mode, mac, vlan, registrar, dhcp, ip, subnet, gateway, dns, outcallerid, queue_out, callforwarddst, callforwardbusydst, dnd, callwaiting, international, national, cellular, internal, requirepin FROM ps_endpoints LEFT JOIN ps_auths USING (id) LEFT JOIN departments ON (sip = ps_endpoints.id) LEFT JOIN provisioning ON (sipid = ps_endpoints.id) LEFT JOIN ext_features ON extension = ps_endpoints.id WHERE ps_endpoints.id = ?", array($ID));

?>
<div style="padding-left: 100px; padding-right: 100px">
<table class="custborder" width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
   <td class="popup" align="left" width="50%">
   <ul>
      <li class="popup"><a href="#Edit" id="menu1">Setup</a></li>
      <li class="popup"><a href="#Features" id="menu2">Features</a></li>
      <li class="popup"><a href="#provisioning" id="menu3">Provisioning</a></li>
   </ul>
   </td>
   <td class="popup" align="right" width="35%">
      <input name="submit" type="submit" value="Modify Extension" class="submit button" id="submitButton2">&nbsp;&nbsp;&nbsp;
   </td> 
</tr>
</table>
</div>

<form name="PbxForm" id="PbxForm" action="index.php?mod=modify_rt_extensions_details" method="post" enctype="multipart/form-data" class="" >
   <input name="id" id="id" value="<?php echo $ID; ?>" type="hidden">
   <div style="padding-left: 100px; padding-right: 100px">
   <table style="width: 100%">
   <tr>
      <td>
         <label class="" for="name">Extension Number</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="name" name="name" value="<?php echo $ID; ?>" required style="width:100%">
         <input class="" id="origid" name="origid" value="<?php echo $ID; ?>" type="hidden">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="callerid">Extension Name</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="callerid" name="callerid" value="<?php echo $row[0]['callerid']; ?>" required style="width:100%">
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
         <label class="" for="namedcallgroup">Named Call Group</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="namedcallgroup" name="namedcallgroup" value="<?php echo $row[0]['named_call_group']; ?>" style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="namedpickupgroup">Named Pickup Group</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="namedpickupgroup" name="namedpickupgroup" value="<?php echo $row[0]['named_pickup_group']; ?>" style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="department">Department</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="department" name="department" value="<?php echo $row[0]['department']; ?>" style="width:100%">
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
         <label class="" for="dtmfmode">DTMF Signaling</label>
      </td>
      <td colspan="4">
	 <select class="button" name="dtmfmode" id="dtmfmode" class="" style="width: 100%" >
            <option value="<?php echo $row[0]['dtmf_mode']; ?>" ><?php echo $row[0]['dtmf_mode']; ?></option>
<?php
            if ($row[0]['dtmf_mode'] == "rfc4733") {
?>
            <option value="info">SIP Info</option>
            <option value="inband">Inband</option>
            <option value="auto">Auto</option>
            <option value="auto_info">Auto Info</option>
<?php
            } elseif ($row[0]['dtmf_mode'] == "info") {
?>
            <option value="rfc4733">RFC4733</option>
            <option value="inband">Inband</option>
            <option value="auto">Auto</option>
            <option value="auto_info">Auto Info</option>
<?php
            } elseif ($row[0]['dtmf_mode'] == "inband") {
?>
            <option value="rfc4733">RFC4733</option>
            <option value="info">SIP Info</option>
            <option value="auto">Auto</option>
            <option value="auto_info">Auto Info</option>
<?php
            } elseif ($row[0]['dtmf_mode'] == "auto") {
?>
            <option value="rfc4733">RFC4733</option>
            <option value="info">SIP Info</option>
            <option value="inband">Inband</option>
            <option value="auto_info">Auto Info</option>
<?php
            } elseif ($row[0]['dtmf_mode'] == "auto_info") {
?>
            <option value="rfc4733">RFC4733</option>
            <option value="info">SIP Info</option>
            <option value="inband">Inband</option>
            <option value="auto">Auto</option>
<?php
            } else {
?>
            <option value="rfc4733">RFC4733</option>
            <option value="info">SIP Info</option>
            <option value="inband">Inband</option>
            <option value="auto">Auto</option>
            <option value="auto_info">Auto Ifno</option>
<?php
            }
?>
         </select>
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
</table>
</div>
</form>
<form action="index.php?mod=modify_rt_extensions_details" method="post" name="PbxForm1" id="PbxForm1" class="myForms">
<div style="padding-left: 100px; padding-right: 100px">
   <table width="100%" border="0" cellpadding="2" cellspacing="2" class="border">
      <tr>
         <td>Outbound Callerid</td>
         <td><input type='text' name='outcallerid' value='<?php echo $row[0]['outcallerid']; ?>' style="width: 100%"</input></td>
      </tr>
      <tr>
         <td>Outbound Queue</td>
         <td><input type='text' name='queue_out' value='<?php echo $row[0]['queue_out']; ?>' style="width: 100%"</input></td>
      </tr>
      <tr>
         <td>Call Forward Destination</td>
         <td><input type='text' name='callforwarddst' value='<?php echo $row[0]['callforwarddst']; ?>' style="width: 100%"</input></td>
      </tr>
      <tr>
         <td>Call Forward Destination on Busy</td>
         <td><input type='text' name='callforwardbusydst' value='<?php echo $row[0]['callforwardbusydst']; ?>' style="width: 100%"</input></td>
      </tr>
      <tr>
         <td>Do Not Disturb</td>
         <td>
             <select class="button" name='dnd'>
<?php
            if (isset($row[0]['dnd'])) {
?>
            <option value="<?php echo $row[0]['dnd']; ?>" ><?php echo $row[0]['dnd']; ?></option>
<?php
            } else {
?>
            <option value="yes">Yes</option>            
            <option value="no">No</option>            
<?php
            }
            if ($row[0]['dnd'] == "yes") {
?>
            <option value="no">No</option>
<?php
            } elseif ($row[0]['dnd'] == "no") {
?>
            <option value="yes">Yes</option>
<?php
            }
?>
             </select>
         </td>
      </tr>
      <tr>
         <td>Call Waiting</td>
         <td>
            <select class="button" name='callwaiting'>";
<?php
            if (isset($row[0]['callwaiting'])) {
?>
            <option value="<?php echo $row[0]['callwaiting']; ?>" ><?php echo $row[0]['callwaiting']; ?></option>
<?php
            } else {
?>
            <option value="yes">Yes</option>            
            <option value="no">No</option>            
<?php
            }
            if ($row[0]['callwaiting'] == "yes") {
?>
            <option value="no">No</option>
<?php
            } elseif ($row[0]['callwaiting'] == "no") {
?>
            <option value="yes">Yes</option>
<?php
            }
?>
            </select>
         </td>
       </tr>
       <tr>
          <td>Can call International</td>
          <td>
             <select class="button" name='international'>
<?php
            if (isset($row[0]['international'])) {
?>
            <option value="<?php echo $row[0]['international']; ?>" ><?php echo $row[0]['international']; ?></option>
<?php
            } else {
?>
            <option value="yes">Yes</option>            
            <option value="no">No</option>            
<?php
            }
            if ($row[0]['international'] == "yes") {
?>
            <option value="no">No</option>
<?php
            } elseif ($row[0]['international'] == "no") {
?>
            <option value="yes">Yes</option>
<?php
            }
?>
             </select>
         </td>
       </tr>
       <tr>
          <td>Can call National</td>
          <td>
             <select class="button" name='national'>
<?php
            if (isset($row[0]['national'])) {
?>
            <option value="<?php echo $row[0]['national']; ?>" ><?php echo $row[0]['national']; ?></option>
<?php
            } else {
?>
            <option value="yes">Yes</option>            
            <option value="no">No</option>            
<?php
            }
            if ($row[0]['national'] == "yes") {
?>
            <option value="no">No</option>
<?php
            } elseif ($row[0]['national'] == "no") {
?>
            <option value="yes">Yes</option>
<?php
            }
?>
             </select>
         </td>
       </tr>
       <tr>
          <td>Can call Cellular</td>
          <td>
             <select class="button" name='cellular'>";
<?php
            if (isset($row[0]['cellular'])) {
?>
            <option value="<?php echo $row[0]['cellular']; ?>" ><?php echo $row[0]['cellular']; ?></option>
<?php
            } else {
?>
            <option value="yes">Yes</option>            
            <option value="no">No</option>            
<?php
            }
            if ($row[0]['cellular'] == "yes") {
?>
            <option value="no">No</option>
<?php
            } elseif ($row[0]['cellular'] == "no") {
?>
            <option value="yes">Yes</option>
<?php
            }
?>
             </select>
         </td>
       </tr>
       <tr>
          <td>Can call Internal</td>
          <td>
             <select class="button" name='internal'>
<?php
            if (isset($row[0]['internal'])) {
?>
            <option value="<?php echo $row[0]['internal']; ?>" ><?php echo $row[0]['internal']; ?></option>
<?php
            } else {
?>
            <option value="yes">Yes</option>            
            <option value="no">No</option>            
<?php
            }
            if ($row[0]['internal'] == "yes") {
?>
            <option value="no">No</option>
<?php
            } elseif ($row[0]['internal'] == "no") {
?>
            <option value="yes">Yes</option>
<?php
            }
?>
             </select>
         </td>
       </tr>
       <tr>
          <td>Require Pin?</td>
          <td>
             <select class="button" name='requirepin'>
<?php
            if (isset($row[0]['requirepin'])) {
?>
            <option value="<?php echo $row[0]['requirepin']; ?>" ><?php echo $row[0]['requirepin']; ?></option>
<?php
            } else {
?>
            <option value="yes">Yes</option>            
            <option value="no">No</option>            
<?php
            }
            if ($row[0]['requirepin'] == "yes") {
?>
            <option value="no">No</option>
<?php
            } elseif ($row[0]['requirepin'] == "no") {
?>
            <option value="yes">Yes</option>
<?php
            }
?>
             </select>
         </td>
       </tr>
   </table>
</div>
</form>
<form action="index.php?mod=modify_rt_extensions_details" method="post" name="PbxForm2" id="PbxForm2" class="myForms">
<div style="padding-left: 100px; padding-right: 100px">
   <table width="100%" border="0" cellpadding="2" cellspacing="2" class="border">
   <tr>
      <td>
         <label class="" for="mac">MAC</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="mac" name="mac" value="<?php echo $row[0]['mac']; ?>" style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="vlan">VLAN</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="vlan" name="vlan" value="<?php echo $row[0]['vlan']; ?>" style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="registrar">Registrar</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="registrar" name="registrar" value="<?php echo $row[0]['registrar']; ?>" style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="dhcp">DHCP</label>
      </td>
      <td colspan="4">
         <select class="button" name="dhcp" id="dhcp" class="" style="width: 100%" >
<?php
            if (isset($row[0]['dhcp'])) {
?>
            <option value="<?php echo $row[0]['dhcp']; ?>" ><?php if ($row[0]['dhcp'] == "1") { echo "Yes"; } else { echo "No";}  ?></option>
<?php
            } else {
?>
            <option value="1">Yes</option>            
            <option value="0">No</option>            
<?php
            }
            if ($row[0]['dhcp'] == "1") {
?>
            <option value="0">No</option>
<?php
            } elseif ($row[0]['dhcp'] == "0") {
?>
            <option value="1">Yes</option>
<?php
            }
?>
         </select>
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="ip">IP</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="ip" name="ip" value="<?php echo $row[0]['ip']; ?>" style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="subnet">Sub Net</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="subnet" name="subnet" value="<?php echo $row[0]['subnet']; ?>" style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="gateway">Gateway</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="gateway" name="gateway" value="<?php echo $row[0]['gateway']; ?>" style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="dns">DNS</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="dns" name="dns" value="<?php echo $row[0]['dns']; ?>" style="width:100%">
      </td>
   </tr>
   </table>
</div>
</form>

<?php
}

function modify_rt_extensions_details() {

   $id = $_POST['name'];
   $origid = $_POST['origid'];
   $callerid = $_POST['callerid'];
   $secret = $_POST['secret'];
   $namedcallgroup = $_POST['namedcallgroup'];
   $namedpickupgroup = $_POST['namedpickupgroup'];
   $department = $_POST['department'];
   $disallow = $_POST['disallow'];
   $allow = $_POST['allow'];
   $dtmfmode = $_POST['dtmfmode'];
   $context = $_POST['context'];

   $outcallerid = $_POST['outcallerid'];
   $queue_out = $_POST['queue_out'];
   $callforwarddst = $_POST['callforwarddst'];
   $callforwardbusydst = $_POST['callforwardbusydst'];
   $dnd = $_POST['dnd'];
   $callwaiting = $_POST['callwaiting'];
   $international = $_POST['international'];
   $national = $_POST['national'];
   $cellular = $_POST['cellular'];
   $internal = $_POST['internal'];
   $requirepin = $_POST['requirepin'];

   $mac = $_POST['mac'];
   $vlan = $_POST['vlan'];
   $registrar = $_POST['registrar'];
   $dhcp = $_POST['dhcp'];
   $ip = $_POST['ip'];
   $subnet = $_POST['subnet'];
   $gateway = $_POST['gateway'];
   $dns = $_POST['dns'];


   db_query("UPDATE ps_aors SET id = ? WHERE id = ?;", array($id, $origid));
   db_query("UPDATE ps_auths SET id = ?, auth_type = ?, password = ?, username = ? WHERE id = ?", array($id, 'userpass', $secret, $id, $origid));
   db_query("UPDATE ps_endpoints SET id = ?, transport = ?, aors = ?, auth = ?, context = ?, disallow = ?, allow = ?, direct_media = ?, force_rport = ?, mailboxes = ?, moh_suggest = ?, callerid = ?, named_call_group = ?, named_pickup_group = ?, tos_audio = ?, tos_video = ?, webrtc = ?, dtmf_mode = ? WHERE id = ?", array($id, 'transport-udp', $id, $id, $context, $disallow, $allow, 'no', 'yes', $id, $department, $callerid, $namedcallgroup, $namedpickupgroup, '0xb8', '0xb8', 'no' , $dtmfmode, $origid));

   db_query("UPDATE departments SET department = ?, sip = ? WHERE sip = ?", array($department,$id,$origid));
   db_query("UPDATE ext_features SET extension=?, outcallerid = ?,queue_out=?,callforwarddst=?, callforwardbusydst=?, dnd=?, callwaiting=?, international=?, national=?, cellular=?, internal=?, requirepin=? WHERE extension = ?", array($id,$outcallerid,$queue_out,$callforwarddst,$callforwardbusydst,$dnd,$callwaiting,$international,$national,$cellular,$internal,$requirepin,$origid));
   db_query("UPDATE extensions SET exten = '$id' WHERE exten = '$origid'", array($id,$origid));

   db_query("UPDATE provisioning SET sipid = ?, mac = ?,vlan = ?,registrar = ?,dhcp = ?,ip = ?,subnet = ?,gateway = ?,dns = ? WHERE sipid = ?", array($id,$mac,$vlan,$registrar,$dhcp,$ip,$subnet,$gateway,$dns,$origid)); 

   db_query("UPDATE queue_members SET membername = ?,interface = ? WHERE interface = ?", array($callerid,'PJSIP/'.$id,'PJSIP/'.$origid)); 

   rt_extensions();
}

function delete_rt_extensions() {

   $id = $_GET['id'];

   db_query("DELETE FROM  ps_aors WHERE id = ?", array($id));
   db_query("DELETE FROM  ps_auths WHERE id = ?", array($id));
   db_query("DELETE FROM  ps_endpoints WHERE id = ?", array($id));

   db_query("DELETE FROM departments WHERE sip = ?", array($id));
   db_query("DELETE FROM extensions WHERE exten = ?", array($id));
   db_query("DELETE FROM ext_features WHERE extension = ?", array($id));
   db_query("DELETE FROM provisioning WHERE sipid = ?", array($id));
   db_query("DELETE FROM queue_members WHERE interface = ?", array($id));

   rt_extensions();
}

function bulkextcreate() {

   $row = db_query("SELECT max(id) as name FROM ps_endpoints LEFT JOIN ps_registrations USING (id) WHERE ps_registrations.id is null", array());
 
   if ($row[0]['name'] == null || $row[0]['name'] == '') {
      echo "<script>alert('You require atlease 1 extension to be created first!');</script>";
      rt_extensions();
   } else {
      echo "<form action='index.php?mod=add_bulkextcreate_details' method='post'>";
?>
      <table style="width: 100%">
      <tr><th>Setting</th><th>Value</th></tr>
      <tr>
         <td>
            <label class="" for="bulkcount">Number Of Extensions to Create:</label>
         </td>
         <td colspan="4">
            <input type="text" class="" id="bulkcount" name="bulkcount" value="" required style="width:100%">
            <input name="name" value="<?php echo $row[0]['name'] ?>" hidden>
         </td>
      </tr>
   </table>
<?php
   echo "<input class='button' type='submit' value='Create Bulk Extensions' />";
   echo "</form>";
   }
}

function add_bulkextcreate_details() {

   $bulkcount = $_POST['bulkcount'];
   $name = $_POST['name'];

   $row = db_query("SELECT ps_endpoints.id, password, context, disallow, allow, callerid, named_call_group, named_pickup_group, department, dtmf_mode, mac, vlan, registrar, dhcp, ip, subnet, gateway, dns, outcallerid, queue_out, callforwarddst, callforwardbusydst, dnd, callwaiting, international, national, cellular, internal, requirepin FROM ps_endpoints LEFT JOIN ps_auths USING (id) LEFT JOIN departments ON (sip = ps_endpoints.id) LEFT JOIN provisioning ON (sipid = ps_endpoints.id) LEFT JOIN ext_features ON extension = ps_endpoints.id WHERE ps_endpoints.id = ?", array($name));

   $id = $row[0]['id'];
   $callerid = $row[0]['callerid'];
   $secret = $row[0]['password'];
   $namedcallgroup = $row[0]['named_call_group'];
   $namedpickupgroup = $row[0]['named_pickup_group'];
   $department = $row[0]['department'];
   $disallow = $row[0]['disallow'];
   $allow = $row[0]['allow'];
   $dtmfmode = $row[0]['dtmf_mode'];
   $context = $row[0]['context'];

   $outcallerid = $row[0]['outcallerid'];
   $queue_out = $row[0]['queue_out'];
   $callforwarddst = $row[0]['callforwarddst'];
   $callforwardbusydst = $row[0]['callforwardbusydst'];
   $dnd = $row[0]['dnd'];
   $callwaiting = $row[0]['callwaiting'];
   $international = $row[0]['international'];
   $national = $row[0]['national'];
   $cellular = $row[0]['cellular'];
   $internal = $row[0]['internal'];
   $requirepin = $row[0]['requirepin'];

   if (isset($row[0]['vlan'])) {
      $vlan = $row[0]['vlan'];
   } else {
      $vlan = '';
   }
   $registrar = $row[0]['registrar'];
   $dhcp = $row[0]['dhcp'];

   echo "<form action='index.php?mod=autherized_bulkextcreate' method='post'>";
   echo "<input name='secret' value='".$secret."' hidden>";
   echo "<input name='namedcallgroup' value='".$namedcallgroup."' hidden>";
   echo "<input name='namedpickupgroup' value='".$namedpickupgroup."' hidden>";
   echo "<input name='department' value='".$department."' hidden>";
   echo "<input name='disallow' value='".$disallow."' hidden>";
   echo "<input name='allow' value='".$allow."' hidden>";
   echo "<input name='dtmfmode' value='".$dtmfmode."' hidden>";
   echo "<input name='context' value='".$context."' hidden>";

   echo "<input name='outcallerid' value='".$outcallerid."' hidden>";
   echo "<input name='queue_out' value='".$queue_out."' hidden>";
   echo "<input name='callforwarddst' value='".$callforwarddst."' hidden>";
   echo "<input name='callforwardbusydst' value='".$callforwardbusydst."' hidden>";
   echo "<input name='dnd' value='".$dnd."' hidden>";
   echo "<input name='callwaiting' value='".$callwaiting."' hidden>";
   echo "<input name='international' value='".$international."' hidden>";
   echo "<input name='national' value='".$national."' hidden>";
   echo "<input name='cellular' value='".$cellular."' hidden>";
   echo "<input name='internal' value='".$internal."' hidden>";
   echo "<input name='requirepin' value='".$requirepin."' hidden>";

   echo "<input name='vlan' value='".$vlan."' hidden>";
   echo "<input name='registrar' value='".$registrar."' hidden>";
   echo "<input name='dhcp' value='".$dhcp."' hidden>";

   echo "<table width=100%>";
   echo "<tr><th align='left'>Extension</th><th align='left'>Name</th><th align='left'>Accountcode</th><th align='left'>Call Group</th><th align='left'>Pickup Group</th><th align='left'>Department</th><th align='left'>Authorized</th></tr>";
   for ($x = 0; $x < $bulkcount; $x++) {
        $name = $name+1;
        $callerid = "Exten-".$name;

        echo "<input name='name[$x]' value=".$name." hidden>";
        echo "<input name='callerid[$x]' value=".$callerid." hidden>";

        echo "<tr>";
        echo "<td align='left'>" . $name . "</td>";
        echo "<td align='left'>" . $callerid . "</td>";
        echo "<td align='left'>" . $accountcode . "</td>";
        echo "<td align='left'>" . $namedcallgroup . "</td>";
        echo "<td align='left'>" . $namedpickupgroup . "</td>";
        echo "<td align='left'>" . $department . "</td>";
        echo "<td align='left'><input type='checkbox' name='authorize[$x]' value='1'</td>";
        echo "</tr>";
   }
   echo "</table>";
   echo "<input class='button' type='submit' value='Authorize' />";
   echo "</form>";

}

function autherized_bulkextcreate() {

   $secret = $_POST['secret'];
   $namedcallgroup = $_POST['namedcallgroup'];
   $namedpickupgroup = $_POST['namedpickupgroup'];
   $department = $_POST['department'];
   $disallow = $_POST['disallow'];
   $allow = $_POST['allow'];
   $dtmfmode = $_POST['dtmfmode'];
   $context = $_POST['context'];

   $outcallerid = $_POST['outcallerid'];
   $queue_out = $_POST['queue_out'];
   $callforwarddst = $_POST['callforwarddst'];
   $callforwardbusydst = $_POST['callforwardbusydst'];
   $dnd = $_POST['dnd'];
   $callwaiting = $_POST['callwaiting'];
   $international = $_POST['international'];
   $national = $_POST['national'];
   $cellular = $_POST['cellular'];
   $internal = $_POST['internal'];
   $requirepin = $_POST['requirepin'];	

   $vlan = $_POST['vlan'];
   $registrar = $_POST['registrar'];
   $dhcp = $_POST['dhcp'];

   foreach ($_POST['name'] as $key => $value) {
      if ($_POST['authorize'][$key] == '1') {
         $id = $_POST['name'][$key];
         $callerid = $_POST['callerid'][$key];

         db_query("insert into ps_aors (id, max_contacts, qualify_frequency, qualify_timeout) values (?, ?, ?, ?);", array($id, 10, 5, 3));
         db_query("insert into ps_auths (id, auth_type, password, username) values (?, ?, ?, ?)", array($id, 'userpass', $secret, $id));
         db_query("insert into ps_endpoints (id, transport, aors, auth, context, disallow, allow, direct_media, force_rport, mailboxes, moh_suggest, callerid, named_call_group, named_pickup_group, tos_audio, tos_video, webrtc, dtmf_mode, disable_direct_media_on_nat, rtp_symmetric, rewrite_contact) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", array($id, 'transport-udp', $id, $id, $context, $disallow, $allow, 'no', 'yes', $id, $department, $callerid, $namedcallgroup, $namedpickupgroup, '0xb8', '0xb8', 'no', $dtmfmode, 'yes', 'yes', 'yes', 'yes'));

         db_query("INSERT INTO departments (sip,department) VALUES (?,?)", array($id,$department));
         db_query("INSERT INTO ext_features (extension,outcallerid,queue_out,callforwarddst, callforwardbusydst, dnd, callwaiting, international, national, cellular, internal, requirepin) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", array($id, $outcallerid, $queue_out, $callforwarddst, $callforwardbusydst, $dnd, $callwaiting, $international, $national, $cellular, $internal, $requirepin));
         db_query("INSERT INTO extensions (context,exten,priority,app,appdata) VALUES (?,?,?,?,?)", array('extensions',$id,'1','Gosub','exten,s,1(${EXTEN})'));

         db_query("INSERT INTO provisioning (sipid,mac,vlan,registrar,dhcp,ip,subnet,gateway,dns) VALUES (?,?,?,?,?,?,?,?,?)", array($id,$mac,$vlan,$registrar,$dhcp,$ip,$subnet,$gateway,$dns));
      }
   }
   rt_extensions();
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
               $id = $data['0'];
               $callerid = $data['1'];
               $secret = $data['2'];
               $namedcallgroup = $data['3'];
               $namedpickupgroup = $data['4'];
               $department = $data['5'];
               $disallow = $data['6'];
               $allow = $data['7'];
               $dtmfmode = $data['8'];
               $context = $data['9'];

               $outcallerid = $data['18'];
               $queue_out = $data['19'];
               $callforwarddst = $data['20'];
               $callforwardbusydst = $data['21'];
               $dnd = $data['22'];
               $callwaiting = $data['23'];
               $international = $data['24'];
               $national = $data['25'];
               $cellular = $data['26'];
               $internal = $data['27'];
               $requirepin = $data['28'];

               $mac = $data['10'];
               $vlan = $data['11'];
               $registrar = $data['12'];
               $dhcp = $data['13'];
               $ip = $data['14'];
               $subnet = $data['15'];
               $gateway = $data['16'];
               $dns = $data['17'];

               $row = db_query("SELECT id FROM ps_endpoints WHERE id = ?", array($id));

               if (isset($row[0]['id'])) {

                  db_query("UPDATE ps_auths SET auth_type = ?, password = ?, username = ? WHERE id = ?", array('userpass', $secret, $id, $id));
                  db_query("UPDATE ps_endpoints SET transport = ?, aors = ?, auth = ?, context = ?, disallow = ?, allow = ?, direct_media = ?, force_rport = ?, mailboxes = ?, moh_suggest = ?, callerid = ?, named_call_group = ?, named_pickup_group = ?, tos_audio = ?, tos_video = ?, webrtc = ?, dtmf_mode = ? WHERE id = ?", array('transport-udp', $id, $id, $context, $disallow, $allow, 'no', 'yes', $id, $department, $callerid, $namedcallgroup, $namedpickupgroup, '0xb8', '0xb8', 'no' , $dtmfmode, $id));

                  db_query("UPDATE departments SET department = ? WHERE sip = ?", array($department,$id));
                  db_query("UPDATE ext_features SET outcallerid = ?,queue_out=?,callforwarddst=?, callforwardbusydst=?, dnd=?, callwaiting=?, international=?, national=?, cellular=?, internal=?, requirepin=? WHERE extension = ?", array($outcallerid,$queue_out,$callforwarddst,$callforwardbusydst,$dnd,$callwaiting,$international,$national,$cellular,$internal,$requirepin,$id));

                  db_query("UPDATE provisioning SET mac = ?,vlan = ?,registrar = ?,dhcp = ?,ip = ?,subnet = ?,gateway = ?,dns = ? WHERE sipid = ?", array($mac,$vlan,$registrar,$dhcp,$ip,$subnet,$gateway,$dns,$id));

                  db_query("UPDATE queue_members SET membername = ? WHERE interface = ?", array($callerid,'PJSIP/'.$id));

                  echo "<tr><td>Updated ".$name."</td></tr>";
               } else {

                  db_query("insert into ps_aors (id, max_contacts, qualify_frequency, qualify_timeout) values (?, ?, ?, ?);", array($id, 10, 5, 3));
                  db_query("insert into ps_auths (id, auth_type, password, username) values (?, ?, ?, ?)", array($id, 'userpass', $secret, $id));
                  db_query("insert into ps_endpoints (id, transport, aors, auth, context, disallow, allow, direct_media, force_rport, mailboxes, moh_suggest, callerid, named_call_group, named_pickup_group, tos_audio, tos_video, webrtc, dtmf_mode, disable_direct_media_on_nat, rtp_symmetric, rewrite_contact) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", array($id, 'transport-udp', $id, $id, $context, $disallow, $allow, 'no', 'yes', $id, $department, $callerid, $namedcallgroup, $namedpickupgroup, '0xb8', '0xb8', 'no', $dtmfmode, 'yes', 'yes', 'yes', 'yes'));

                  db_query("INSERT INTO departments (sip,department) VALUES (?,?)", array($id,$department));
                  db_query("INSERT INTO ext_features (extension,outcallerid,queue_out,callforwarddst, callforwardbusydst, dnd, callwaiting, international, national, cellular, internal, requirepin) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", array($id, $outcallerid, $queue_out, $callforwarddst, $callforwardbusydst, $dnd, $callwaiting, $international, $national, $cellular, $internal, $requirepin));
                  db_query("INSERT INTO extensions (context,exten,priority,app,appdata) VALUES (?,?,?,?,?)", array('extensions',$id,'1','Gosub','exten,s,1(${EXTEN})'));

                  db_query("INSERT INTO provisioning (sipid,mac,vlan,registrar,dhcp,ip,subnet,gateway,dns) VALUES (?,?,?,?,?,?,?,?,?)", array($id,$mac,$vlan,$registrar,$dhcp,$ip,$subnet,$gateway,$dns));

                  echo "<tr><td>Imported ".$name."</td></tr>";
                }
            }
            $lines++;
         }
         echo "</table>";

         fclose($handle);
     }
   }
}

?>
