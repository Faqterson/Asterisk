<?php

function queues() {

   if (isset($_GET['alert'])) {
      $row=db_query("SELECT recordrequest,dynamicagents from queues_features WHERE queue_name = ?", array($_GET['alert']));

      if ($row[0]['recordrequest'] == 1) {
         $record = db_query("SELECT exten FROM extensions WHERE appdata = ?", array('record-prompt,s,1('.$_GET['alert'].'-queue)'));
         $recordmsg = "Your recording code is ".$record[0]['exten']."\\r\\n";
      }

      if ($row[0]['dynamicagents'] == 1) {
         $login = db_query("SELECT exten FROM extensions WHERE appdata = ?", array('agent-login,s,1('.$_GET['alert'].')'));
         $loginmsg = "Login code is ".$login[0]['exten']."\\r\\n";
         $logoff = db_query("SELECT exten FROM extensions WHERE appdata = ?", array('agent-logoff,s,1('.$_GET['alert'].')'));
         $logoffmsg = "Logoffcode is ".$logoff[0]['exten']."\\r\\n";
      }
      $callingcode = db_query("SELECT exten FROM extensions WHERE appdata = ?", array('queue,s,1('.$_GET['alert'].')'));
      $callingcodemsg = "Calling Code is ".$callingcode[0]['exten']."\\r\\n";

      echo "<script>alert('".$recordmsg."".$loginmsg."".$logoffmsg."".$callingcodemsg."');</script>";
   }

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

   $row=db_query("SELECT name,strategy,timeout,servicelevel,ringinuse from queues LIMIT $offset, $rowsperpage", array());

   echo "<table width=100%>";
   echo "<tr><th align='left'>Queue</th><th align='left'>Ring Strategy</th><th align='left'>Agent Timeout</th><th align='left'>Service Level</th><th align='left'>Ring In Use</th><th align='left'>Edit</th></tr>";
   foreach($row as $key => $value)
   {
      echo "<tr>";
      echo "<td align='left'>" . $value['name'] . "</td>";
      echo "<td align='left'>" . $value['strategy'] . "</td>";
      echo "<td align='left'>" . $value['timeout'] . "</td>";
      echo "<td align='left'>" . $value['servicelevel'] . "</td>";
      echo "<td align='left'>" . $value['ringinuse'] . "</td>";
      echo "<td align='left'>";
      echo "<a style='background: none' href = 'index.php?mod=modify_queues&name=" . $value['name'] . "'><img src=img/edit-icon.png height='20' width='20'></a>&nbsp&nbsp&nbsp";
      echo "<a style='background: none' href = 'index.php?mod=delete_queues&name=" . $value['name'] . "'><img class='delete' src=img/delete-icon.png height='20' width='20'></a></td>";
      echo "</td>";
      echo "</tr>";
   }
   echo "</table>";
?>
   <table width="100%">
   <tr>
      <td align="center">
      <?php
      $resultPage = db_query("SELECT name,strategy,timeout,servicelevel,ringinuse from queues", array());
      pagination('index.php?mod=queues',$rowsperpage,$resultPage,$page);
      ?>
      </td>
   </tr>
   </table>
<?php
   echo "<form action='index.php?mod=add_queues' method='post'>";
   echo "<input class='button' type='submit' value='Add Queue' />";
   echo "</form>";
}

function add_queues() {
?>
<div style="padding-left: 100px; padding-right: 100px">
<table class="custborder" width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
   <td class="popup" align="left" width="50%">
   <ul>
      <li class="popup"><a href="#Edit" id="menu1">Setup</a></li>
      <li class="popup"><a href="#Features" id="menu2">Features</a></li>
   </ul> 
   </td> 
   <td class="popup" align="right" width="35%"> 
      <input name="submit" type="submit" value="Add Queue" class="submit button" id="queuesubmit">&nbsp;&nbsp;&nbsp;
   </td> 
</tr>
</table>
</div>
<form name="PbxForm" id="PbxForm" action="index.php?mod=add_queues_details" method="post" enctype="multipart/form-data" class="myForms" >
   <div style="padding-left: 100px; padding-right: 100px">
   <table style="width: 100%">
   <tr>
      <td>
         <label class="" for="name">Queue Number</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="name" name="name" value="" required style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="strategy">Ring Strategy</label>
      </td>
      <td colspan="4">
	 <select class="button" name="strategy" id="strategy" style="width: 100%" >
            <option value="ringall">Ring All</option>
            <option value="leastrecent">Least Recent</option>
            <option value="fewestcalls">Fewest Calls</option>
            <option value="random">Random</option>
            <option value="rrmemory">Round Robin Memory</option>
            <option value="linear">Linear</option>
            <option value="wrandom">Weight Random</option>
            <option value="rrordered">Round Robin Ordered</option>
         </select>
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="timeout">Agent Ring Timeout</label>
      </td>
      <td colspan="4">
	 <select class="button" name="timeout" id="timeout" style="width: 100%" >
            <option value="15">15 Second (Default)</option>
<?php
         for ($i = 1; $i <= 60; $i++) {
            echo "<option value='$i'>".$i." Second</option>";
         }
?>
         </select>
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="servicelevel">Service Level</label>
      </td>
      <td colspan="4">
	 <select class="button" name="servicelevel" id="servicelevel" style="width: 100%" >
            <option value="20">20 Second (Default)</option>
<?php
         for ($i = 10; $i <= 60; $i=$i+10) {
            echo "<option value='$i'>".$i." Second</option>";
         }
?>
         </select>
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="qwrapuptime">Wrapup Time</label>
      </td>
      <td colspan="4">
	 <select class="button" name="qwrapuptime" id="qwrapuptime"  style="width: 100%" >
            <option value="0">0 Second (Disabled)</option>
<?php
         for ($i = 1; $i <= 60; $i++) {
            echo "<option value='$i'>".$i." Second</option>";
         }
?>
         </select>
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="joinempty">Join Empty</label>
      </td>
      <td colspan="4">
         <input type="text"  id="joinempty" name="joinempty" value="unavailable,invalid,unknown" required style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="ringinuse">Ring in use</label>
      </td>
      <td colspan="4">
	 <select class="button" name="ringinuse" id="ringinuse"  style="width: 100%" >
            <option value="yes">No</option>
            <option value="no">Yes</option>
         </select>
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="announce_position">Announce Position</label>
      </td>
      <td colspan="4">
	 <select class="button" name="announce_position" id="announce_position"  style="width: 100%" >
            <option value="no">No</option>
            <option value="yes">Yes</option>
         </select>
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="announce_holdtime">Announce Holdtime</label>
      </td>
      <td colspan="4">
	 <select class="button" name="announce_holdtime" id="announce_holdtime"  style="width: 100%" >
            <option value="no">No</option>
            <option value="yes">Yes</option>
         </select>
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="announce_frequency">Announce Frequency</label>
      </td>
      <td colspan="4">
	 <select class="button" name="announce_frequency" id="announce_frequency"  style="width: 100%" >
            <option value="0">0 Second (Disabled)</option>
<?php
         for ($i = 1; $i <= 60; $i++) {
            echo "<option value='$i'>".$i." Second</option>";
         }
?>
         </select>
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="periodic_announce">Periodic Announcement</label>
      </td>
      <td colspan="4">
         <input type="file" id="periodic_announce" name="periodic_announce" value="" style="width:100%">
         <input id="recordrequest" name="recordrequest" value="1" type="checkbox"><label class="record-request">Request Recording code</label>
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="periodic_announce_frequency">Periodic Announce Frequency</label>
      </td>
      <td colspan="4">
	 <select class="button" name="periodic_announce_frequency" id="periodic_announce_frequency"  style="width: 100%" >
            <option value="0">0 Second (Disabled)</option>
<?php
         for ($i = 10; $i <= 60; $i=$i+10) {
            echo "<option value='$i'>".$i." Second</option>";
         }
?>
         </select>
      </td>
   </tr>
</table>
<div style="overflow-y: scroll; height:300px;">
<table>
   <tr>
      <th>Extensions/Forwarders</th>
      <th>Enabled</th>
      <th>Penalty</th>
      <th>Wrap Up Time</th>
   </tr>
<?php
   $depallow = check_department_access();

   if ($depallow != "") {
      $row=db_query("SELECT ps_endpoints.id,callerid FROM ps_endpoints LEFT JOIN ps_registrations ON (ps_endpoints.id = ps_registrations.id) LEFT JOIN departments ON (sip = ps_endpoints.id) WHERE department = '$depallow' AND ps_registrations.id is null ORDER BY id ASC", array());
   } else {
      $row=db_query("SELECT ps_endpoints.id,callerid FROM ps_endpoints LEFT JOIN ps_registrations ON (ps_endpoints.id = ps_registrations.id) WHERE ps_registrations.id is null ORDER BY id ASC ", array());
   }

   foreach ($row as $key => $value)
   {
?>
      <tr>
         <td align="center"><?php echo $value['id']." ".$value['callerid']; ?></td>
         <td align="center"><input name="enable[<?php echo $value['id']; ?>]" value="<?php echo $value['id'].",".$value['callerid']; ?>" type="checkbox"></td>
         <td align="center">
            <select class="button" name="penalty[<?php echo $value['id']; ?>]" id="penalty<?php echo $value['id']; ?>"  style="width: 30%" >
               <option value="0">0</option>
<?php
               for ($i = 1; $i <= 10; $i=$i+1) {
                  echo "<option value='$i'>".$i."</option>";
               }
?>
            </select>
         </td>
         <td align="center">
            <select class="button" name="wrapuptime[<?php echo $value['id']; ?>]" id="wrapuptime<?php echo $value['id']; ?>"  style="width: 30%" >
               <option value="0">0 Seconds (Disabled)</option>
<?php
               for ($i = 5; $i <= 30; $i=$i+5) {
                  echo "<option value='$i'>".$i." Second</option>";
               }
?>
            </select>
         </td>
      </tr>
<?php
   }

   if ($depallow != "") {
      $row=db_query("SELECT uniqueid,name,number FROM forwarders WHERE department = '$depallow' ORDER BY name ASC", array());
   } else {
      $row=db_query("SELECT uniqueid,name,number FROM forwarders ORDER BY name ASC ", array());
   }

   foreach ($row as $key => $value)
   {
?>
      <tr>
         <td align="center"><?php echo $value['number']." ".$value['name']; ?></td>
         <td align="center"><input name="forenable[<?php echo $value['uniqueid']; ?>]" value="<?php echo $value['number'].",".$value['name']; ?>" type="checkbox"></td>
         <td align="center">
            <select class="button" name="forpenalty[<?php echo $value['uniqueid']; ?>]" id="forpenalty<?php echo $value['uniqueid']; ?>"  style="width: 30%" >
               <option value="0">0</option>
<?php
               for ($i = 1; $i <= 10; $i=$i+1) {
                  echo "<option value='$i'>".$i."</option>";
               }
?>
            </select>
         </td>
         <td align="center">
            <select class="button" name="forwrapuptime[<?php echo $value['uniqueid']; ?>]" id="forwrapuptime<?php echo $value['uniqueid']; ?>"  style="width: 30%" >
               <option value="0">0 Seconds (Disabled)</option>
<?php
               for ($i = 5; $i <= 30; $i=$i+5) {
                  echo "<option value='$i'>".$i." Second</option>";
               }
?>
            </select>
         </td>
      </tr>
<?php
   }

?>
</table>
</div>
</div>
</form>
<form action="index.php?mod=add_queues_details" method="post" name="PbxForm1" id="PbxForm1" class="myForms">
<div style="padding-left: 100px; padding-right: 100px">
   <table width="100%" border="0" cellpadding="2" cellspacing="2" class="border">
      <tr>
         <td width="350px">Play Music on Hold</td>
         <td>
            <input name="musiconhold" value="m" checked type="checkbox">
         </td>
      </tr>
      <tr>
         <td width="350px">Dynamic Agents</td>
         <td>
            <input id="dynamicagents" name="dynamicagents" value="1" type="checkbox"><label class="record-request">Request Dynamic code</label>
         </td>
      </tr>
      <tr>
         <td>Queue Forward</td>
         <td>
            <select class="button" name='queueforward' id="queueforward">
                <option selected value='no'>No</option>
                <option value='yes'>Yes</option>
            </select>
         </td>
      </tr>
      <tr name="queuetimeout" id="queuetimeout" hidden>
         <td>
            <label class="" for="timeout">Queue Timeout</label>
         </td>
         <td colspan="4">
            <select class="button" name="queuetimeout" id="queuetimeout" class="button" style="width: 100%" >
               <option value="30">30 Second (Default)</option>
<?php
            for ($i = 1; $i <= 120; $i++) {
               echo "<option value='$i'>".$i." Second</option>";
            }
?>
            </select>
         </td>
      </tr>
      <tr name="queuedest" id="queuedest" hidden>
         <td>
            <label class="control-label" for="goto0">Destination</label>
         </td>
         <td colspan="4">
	    <select class="button" name="destination" id="destination"  tabindex="2" data-id="0" style="width: 100%" required>
               <option value="">== choose one ==</option>
               <option value="extensions">Extensions</option>
               <option value="queues">Queues</option>
               <option value="operator">Operator</option>
               <option value="forwarders">Forwarders</option>
            </select>
            <select class="button" name="dataid" id="extensionsid" style="width: 100%" hidden>
               <option value="" >== choose one ==</option>
<?php
            $row=db_query("SELECT ps_endpoints.id,callerid from ps_endpoints LEFT JOIN ps_registrations USING (id) WHERE ps_registrations.id is null", array());
            foreach($row as $key => $value)
            {
               echo "<option value='".$value['id']."'>".$value['id']." - ".$value['callerid']."</option>";
            }
?>
            </select>
            <select class="button" name="dataid" id="queuesid" style="width: 100%" hidden>
               <option value="" >== choose one ==</option>
<?php
            $row=db_query("SELECT name from queues", array());
            foreach($row as $key => $value)
            {
               echo "<option value='".$value['name']."'>".$value['name']."</option>";
            }
?>
            </select>
            <select class="button" name="dataid" id="forwardersid" style="width: 100%">
               <option value="" >== choose one ==</option>
<?php
            $row=db_query("select uniqueid,name,number from forwarders", array());
            foreach($row as $key => $value)
            {
               echo "<option value='".$value['uniqueid']."'>".$value['name']." ".$value['number']."</option>";
            }
?>
            </select>
         </td>
      </tr>
      </div>
   </table>
</div>
</form>
<?php
}

function add_queues_details() {

   $name = $_POST['name'];
   $strategy = $_POST['strategy'];
   $timeout = $_POST['timeout'];
   $servicelevel = $_POST['servicelevel'];
   $qwrapuptime = $_POST['qwrapuptime'];
   $joinempty = $_POST['joinempty'];
   $ringinuse = $_POST['ringinuse'];
   $announce_position = $_POST['announce_position'];
   $announce_holdtime = $_POST['announce_holdtime'];
   $announce_frequency = $_POST['announce_frequency'];
   $periodic_announce_frequency = $_POST['periodic_announce_frequency'];

   if ( $_POST['periodic_announce'] != "undefined" ) {
      $format = array(".wav",".mp3",".WAV",".g729");      
      $periodic_announce = 'custom/'.str_replace(' ' ,'_', str_replace($format, "", $_POST['periodic_announce']));
   } else {
      $periodic_announce = "";
   }

   if (isset($_POST['musiconhold'])) {
      $musiconhold = $_POST['musiconhold'];
   } else {
      $musiconhold = 'r';
   }
   $queueforward = $_POST['queueforward'];

   if (isset($_POST['queuetimeout'])) {
      $queuetimeout = $_POST['queuetimeout'];
   } else {
      $queuetimeout = '';
   }
   if (isset($_POST['destination'])) {
      $destination = $_POST['destination'];
   } else {
      $destination = '';
   }
   if (isset($_POST['dataid'])) {
      $dataid = $_POST['dataid'];
   } else {
      $dataid = '';
   }

   if (isset($_POST['recordrequest'])) {
      $recordrequest = $_POST['recordrequest'];
      $periodic_announce = 'custom/'.$name."-queue.wav";
   } else {
      $recordrequest = '0';
   }

   if (isset($_POST['dynamicagents'])) {
      $dynamicagents = $_POST['dynamicagents'];
   } else {
      $dynamicagents = '0';
   }

   db_query("INSERT INTO queues (name,strategy,timeout,servicelevel,wrapuptime,joinempty,ringinuse,announce_position,announce_holdtime,announce_frequency,periodic_announce,periodic_announce_frequency) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)", array($name,$strategy,$timeout,$servicelevel,$qwrapuptime,$joinempty,$ringinuse,$announce_position,$announce_holdtime,$announce_frequency,$periodic_announce,$periodic_announce_frequency));
   db_query("INSERT INTO queues_features (queue_name,musiconhold,queueforward,queuetimeout,destination,data,recordrequest,dynamicagents) VALUES (?,?,?,?,?,?,?,?)", array($name,$musiconhold,$queueforward,$queuetimeout,$destination,$dataid,$recordrequest,$dynamicagents));   

   foreach ($_POST['penalty'] as $key => $value) {
      $penalty[$key]=$value;
   }
   foreach ($_POST['wrapuptime'] as $key => $value) {
      $wrapuptime[$key]=$value;
   }

   if (isset($_POST['enable'])) {
      foreach ($_POST['enable'] as $key => $value) {
         $pieces = explode(",", $value);
            db_query("INSERT INTO queue_members (queue_name,interface,membername,state_interface,penalty,paused,wrapuptime) VALUES (?,?,?,?,?,?,?)", array($name,'PJSIP/'.$pieces[0],$pieces[1],'PJSIP/'.$pieces[0],$penalty[$key],'0',$wrapuptime[$key]));
      }
   }

   foreach ($_POST['forpenalty'] as $key => $value) {
      $forpenalty[$key]=$value;
   }
   foreach ($_POST['forwrapuptime'] as $key => $value) {
      $forwrapuptime[$key]=$value;
   }

   foreach ($_POST['forenable'] as $key => $value) {
      $pieces = explode(",", $value);
         db_query("INSERT INTO queue_members (queue_name,interface,membername,state_interface,penalty,paused,wrapuptime) VALUES (?,?,?,?,?,?,?)", array($name,'local/'.$pieces[0].'@forwarders','local/'.$pieces[0].'@forwarders','',$forpenalty[$key],'0',$forwrapuptime[$key]));
   }
 
   queues();
}

function modify_queues() {

   $name = $_GET['name'];
   $row = db_query("SELECT name,strategy,timeout,servicelevel,wrapuptime,joinempty,ringinuse,announce_position,announce_holdtime,announce_frequency,periodic_announce,periodic_announce_frequency,queues_features.musiconhold,queueforward,queuetimeout,destination,data,recordrequest,dynamicagents from queues LEFT JOIN queues_features ON (name = queue_name) WHERE name = ?", array($name));

?>
<div style="padding-left: 100px; padding-right: 100px">
<table class="custborder" width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
   <td class="popup" align="left" width="50%">
   <ul>
      <li class="popup"><a href="#Edit" id="menu1">Setup</a></li>
      <li class="popup"><a href="#Features" id="menu2">Features</a></li>
   </ul> 
   </td> 
   <td class="popup" align="right" width="35%"> 
      <input name="submit" type="submit" value="Modify Queue" class="submit button" id="queuesubmit2">&nbsp;&nbsp;&nbsp;
   </td> 
</tr>
</table>
</div>
<form name="PbxForm" id="PbxForm" action="index.php?mod=modify_queues_details" method="post" enctype="multipart/form-data" class="" >
   <input name="name" id="name" value="<?php echo $name; ?>" type="hidden">
   <div style="padding-left: 100px; padding-right: 100px">
   <table style="width: 100%">
   <tr>
      <td>
         <label class="" for="name">Queue Number</label>
      </td>
      <td colspan="4">
         <input type="text"  id="name" name="name" value="<?php echo $row[0]['name']; ?>" required style="width:100%">
         <input type="hidden"  id="origname" name="origname" value="<?php echo $row[0]['name']; ?>" style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="strategy">Ring Strategy</label>
      </td>
      <td colspan="4">
	 <select class="button" name="strategy" id="strategy"  style="width: 100%" >
            <option value="<?php echo $row[0]['strategy']; ?>"><?php echo $row[0]['strategy']; ?></option>
<?php
            if ($row[0]['strategy'] == "ringall") {
?>
            <option value="leastrecent">Least Recent</option>
            <option value="fewestcalls">Fewest Calls</option>
            <option value="random">Random</option>
            <option value="rrmemory">Round Robin Memory</option>
            <option value="linear">Linear</option>
            <option value="wrandom">Weight Random</option>
            <option value="rrordered">Round Robin Ordered</option>
<?php
            } elseif ($row[0]['strategy'] == "leastrecent") {
?>
            <option value="ringall">Ring All</option>
            <option value="fewestcalls">Fewest Calls</option>
            <option value="random">Random</option>
            <option value="rrmemory">Round Robin Memory</option>
            <option value="linear">Linear</option>
            <option value="wrandom">Weight Random</option>
            <option value="rrordered">Round Robin Ordered</option>
<?php
            } elseif ($row[0]['strategy'] == "fewestcalls") {
?>
            <option value="ringall">Ring All</option>
            <option value="leastrecent">Least Recent</option>
            <option value="random">Random</option>
            <option value="rrmemory">Round Robin Memory</option>
            <option value="linear">Linear</option>
            <option value="wrandom">Weight Random</option>
            <option value="rrordered">Round Robin Ordered</option>
<?php
            } elseif ($row[0]['strategy'] == "random") {
?>
            <option value="ringall">Ring All</option>
            <option value="leastrecent">Least Recent</option>
            <option value="fewestcalls">Fewest Calls</option>
            <option value="rrmemory">Round Robin Memory</option>
            <option value="linear">Linear</option>
            <option value="wrandom">Weight Random</option>
            <option value="rrordered">Round Robin Ordered</option>
<?php
            } elseif ($row[0]['strategy'] == "rrmemory") {
?>
            <option value="ringall">Ring All</option>
            <option value="leastrecent">Least Recent</option>
            <option value="fewestcalls">Fewest Calls</option>
            <option value="random">Random</option>
            <option value="linear">Linear</option>
            <option value="wrandom">Weight Random</option>
            <option value="rrordered">Round Robin Ordered</option>
<?php
            } elseif ($row[0]['strategy'] == "linear") {
?>
            <option value="ringall">Ring All</option>
            <option value="leastrecent">Least Recent</option>
            <option value="fewestcalls">Fewest Calls</option>
            <option value="random">Random</option>
            <option value="rrmemory">Round Robin Memory</option>
            <option value="wrandom">Weight Random</option>
            <option value="rrordered">Round Robin Ordered</option>
<?php
            } elseif ($row[0]['strategy'] == "wrandom") {
?>
            <option value="ringall">Ring All</option>
            <option value="leastrecent">Least Recent</option>
            <option value="fewestcalls">Fewest Calls</option>
            <option value="random">Random</option>
            <option value="rrmemory">Round Robin Memory</option>
            <option value="linear">Linear</option>
            <option value="rrordered">Round Robin Ordered</option>
<?php
            }  elseif ($row[0]['strategy'] == "rrordered") {
?>
            <option value="ringall">Ring All</option>
            <option value="leastrecent">Least Recent</option>
            <option value="fewestcalls">Fewest Calls</option>
            <option value="random">Random</option>
            <option value="rrmemory">Round Robin Memory</option>
            <option value="linear">Linear</option>
            <option value="wrandom">Weight Random</option>
<?php
            }
?>
         </select>
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="timeout">Agent Ring Timeout</label>
      </td>
      <td colspan="4">
	 <select class="button" name="timeout" id="timeout"  style="width: 100%" >
            <option value="<?php echo $row[0]['timeout']; ?>"><?php echo $row[0]['timeout']; ?> Second</option>
<?php
         for ($i = 1; $i <= 60; $i++) {
            if ($i != $row[0]['timeout']) {
               echo "<option value='$i'>".$i." Second</option>";
            }
         }
?>
         </select>
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="servicelevel">Service Level</label>
      </td>
      <td colspan="4">
	 <select class="button" name="servicelevel" id="servicelevel"  style="width: 100%" >
            <option value="<?php echo $row[0]['servicelevel']; ?>"><?php echo $row[0]['servicelevel']; ?> Second</option>
<?php
         for ($i = 10; $i <= 60; $i=$i+10) {
       	    if ($i != $row[0]['servicelevel']) {
               echo "<option value='$i'>".$i." Second</option>";
            }
         }
?>
         </select>
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="qwrapuptime">Wrapup Time</label>
      </td>
      <td colspan="4">
	 <select class="button" name="qwrapuptime" id="qwrapuptime"  style="width: 100%" >
            <option value="<?php echo $row[0]['wrapuptime']; ?>"><?php echo $row[0]['wrapuptime']; ?> Second</option>
<?php
         for ($i = 1; $i <= 60; $i++) {
       	    if ($i != $row[0]['wrapuptime']) {
               echo "<option value='$i'>".$i." Second</option>";
            }
         }
?>
         </select>
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="joinempty">Join Empty</label>
      </td>
      <td colspan="4">
         <input type="text"  id="joinempty" name="joinempty" value="<?php echo $row[0]['joinempty']; ?>" required style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="ringinuse">Ring in use</label>
      </td>
      <td colspan="4">
	 <select class="button" name="ringinuse" id="ringinuse"  style="width: 100%" >
            <option value="<?php echo $row[0]['ringinuse']; ?>"><?php echo $row[0]['ringinuse']; ?></option>
<?php
            if ($row[0]['ringinuse'] == "yes") {
?>
            <option value="no">No</option>
<?php
            } elseif ($row[0]['ringinuse'] == "no") {
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
         <label class="" for="announce_position">Announce Position</label>
      </td>
      <td colspan="4">
	 <select class="button" name="announce_position" id="announce_position"  style="width: 100%" >
            <option value="<?php echo $row[0]['announce_position']; ?>" ><?php echo $row[0]['announce_position']; ?></option>
<?php
            if ($row[0]['announce_position'] == "yes") {
?>
            <option value="no">No</option>
<?php
            } elseif ($row[0]['announce_position'] == "no") {
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
         <label class="" for="announce_holdtime">Announce Holdtime</label>
      </td>
      <td colspan="4">
	 <select class="button" name="announce_holdtime" id="announce_holdtime"  style="width: 100%" >
            <option value="<?php echo $row[0]['announce_holdtime']; ?>" ><?php echo $row[0]['announce_holdtime']; ?></option>
<?php
            if ($row[0]['announce_holdtime'] == "yes") {
?>
            <option value="no">No</option>
<?php
            } elseif ($row[0]['announce_holdtime'] == "no") {
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
         <label class="" for="announce_frequency">Announce Frequency</label>
      </td>
      <td colspan="4">
	 <select class="button" name="announce_frequency" id="announce_frequency"  style="width: 100%" >
            <option value="<?php echo $row[0]['announce_frequency']; ?>"><?php echo $row[0]['announce_frequency']; ?> Second</option>
<?php
         for ($i = 1; $i <= 60; $i++) {
       	    if ($i != $row[0]['announce_frequency']) {
               echo "<option value='$i'>".$i." Second</option>";
            }
         }
?>
         </select>
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="periodic_announce">Periodic Announcement</label>
      </td>
      <td>
<?php
         $play = "";
         if (file_exists("/var/lib/asterisk/sounds/".$row[0]['periodic_announce'].".wav")) {
            $play = "<a style='background: none' href=".$row[0]['periodic_announce'].".wav><img style='margin: -5px;' src=img/play-icon.png height='20' width='20'></a>";
         }
         echo "<label align='left'>" . $row[0]['periodic_announce'] . "&nbsp;&nbsp;&nbsp;". $play ."</label>";
?>
         <input type="hidden" class="" id="announcement_ext" name="announcement_ext" value="<?php if ($row[0]['periodic_announce'] != "") { echo $row[0]['periodic_announce'] . ".wav"; } ?>" readonly="readonly" style="width:95%">
      </td>
      <td colspan="3">
         <input type="file" class="" id="periodic_announce" name="periodic_announce" style="width:100%">
         <input id="recordrequest" name="recordrequest" value="1" <?php if ($row[0]['recordrequest'] == '1') {echo 'checked';}?> type="checkbox"><label class="record-request">Request Recording code</label>
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="periodic_announce_frequency">Periodic Announce Frequency</label>
      </td>
      <td colspan="4">
	 <select class="button" name="periodic_announce_frequency" id="periodic_announce_frequency"  style="width: 100%" >
            <option value="<?php echo $row[0]['periodic_announce_frequency']; ?>"><?php echo $row[0]['periodic_announce_frequency']; ?> Second</option>
<?php
         for ($i = 10; $i <= 60; $i=$i+10) {
       	    if ($i != $row[0]['periodic_announce_frequency']) {
               echo "<option value='$i'>".$i." Second</option>";
            }
         }
?>
         </select>
      </td>
   </tr>
</table>
<div style="overflow-y: scroll; height:300px;">
<table>
   <tr>
      <th>Extensions/Forwarders</th>
      <th>Enabled</th>
      <th>Penalty</th>
      <th>Wrap Up Time</th>
   </tr>
<?php
   $depallow = check_department_access();

   if ($depallow != "") {
      $extrow=db_query("SELECT ps_endpoints.id,callerid FROM ps_endpoints LEFT JOIN ps_registrations ON (ps_endpoints.id = ps_registrations.id) LEFT JOIN departments ON (sip = ps_endpoints.id) WHERE department = '$depallow' AND ps_registrations.id is null ORDER BY id ASC", array());
   } else {
      $extrow=db_query("SELECT ps_endpoints.id,callerid FROM ps_endpoints LEFT JOIN ps_registrations ON (ps_endpoints.id = ps_registrations.id) WHERE ps_registrations.id is null ORDER BY id ASC ", array());
   }

   foreach ($extrow as $key => $value)
   {
      $memrow=db_query("SELECT interface,membername,state_interface,penalty,wrapuptime FROM queue_members WHERE queue_name = ? and interface = ?", array($row[0]['name'],'PJSIP/'.$value['id']));
?>
      <tr>
         <td align="center"><?php echo $value['id']." ".$value['callerid']; ?></td>
         <td align="center"><input name="enable[<?php echo $value['id']; ?>]" value="<?php echo $value['id'].",".$value['callerid']; ?>" <?php if (isset($memrow[0]['interface'])) {echo 'checked'; }?> type="checkbox"></td>
         <td align="center">
            <select class="button" name="penalty[<?php echo $value['id']; ?>]" id="penalty<?php echo $value['id']; ?>"  style="width: 30%" >
<?php
            if (isset($memrow[0]['penalty'])) {
?>
               <option value="<?php echo $memrow[0]['penalty']; ?>"><?php echo $memrow[0]['penalty']; ?></option>
<?php
               for ($i = 0; $i <= 10; $i=$i+1) {
                  if ($i != $memrow[0]['penalty']) {
                     echo "<option value='$i'>".$i."</option>";
                  }
               }
            } else {
               for ($i = 0; $i <= 10; $i=$i+1) {
                  echo "<option value='$i'>".$i."</option>";
               }
            }
?>
            </select>
         </td>
         <td align="center">
            <select class="button" name="wrapuptime[<?php echo $value['id']; ?>]" id="wrapuptime<?php echo $value['id']; ?>"  style="width: 30%" >
<?php
            if (isset($memrow[0]['wrapuptime'])) {
?>
               <option value="<?php echo $memrow[0]['wrapuptime']; ?>"><?php echo $memrow[0]['wrapuptime']; ?> Seconds</option>
<?php
               for ($i = 0; $i <= 30; $i=$i+5) {
                  if ($i != $memrow[0]['wrapuptime']) {
                     echo "<option value='$i'>".$i." Second</option>";
                  }
               }

            } else {
               for ($i = 0; $i <= 30; $i=$i+5) {
                  echo "<option value='$i'>".$i." Second</option>";
               }
            }
?>
            </select>
         </td>
      </tr>
<?php
   }

   if ($depallow != "") {
      $forrow=db_query("SELECT uniqueid,name,number FROM forwarders WHERE department = '$depallow' ORDER BY name ASC", array());
   } else {
      $forrow=db_query("SELECT uniqueid,name,number FROM forwarders ORDER BY name ASC ", array());
   }

   foreach ($forrow as $key => $value)
   {
      $memrow=db_query("SELECT interface,membername,state_interface,penalty,wrapuptime FROM queue_members WHERE queue_name = ? and interface = ?", array($row[0]['name'],'local/'.$value['number'].'@forwarders'));
?>
      <tr>
         <td align="center"><?php echo $value['number']." ".$value['name']; ?></td>
         <td align="center"><input name="forenable[<?php echo $value['uniqueid']; ?>]" value="<?php echo $value['number'].",".$value['name']; ?>" <?php if (isset($memrow[0]['interface'])) {echo 'checked'; }?> type="checkbox"></td>
         <td align="center">
            <select class="button" name="forpenalty[<?php echo $value['uniqueid']; ?>]" id="forpenalty<?php echo $value['uniqueid']; ?>"  style="width: 30%" >
<?php
            if (isset($memrow[0]['penalty'])) {
?>
               <option value="<?php echo $memrow[0]['penalty']; ?>"><?php echo $memrow[0]['penalty']; ?></option>
<?php
               for ($i = 0; $i <= 10; $i=$i+1) {
                  if ($i != $memrow[0]['penalty']) {
                     echo "<option value='$i'>".$i."</option>";
                  }
               }
            } else {
               for ($i = 0; $i <= 10; $i=$i+1) {
                  echo "<option value='$i'>".$i."</option>";
               }
            }
?>
            </select>
         </td>
         <td align="center">
            <select class="button" name="forwrapuptime[<?php echo $value['uniqueid']; ?>]" id="forwrapuptime<?php echo $value['uniqueid']; ?>"  style="width: 30%" >
<?php
            if (isset($memrow[0]['wrapuptime'])) {
?>
               <option value="<?php echo $memrow[0]['wrapuptime']; ?>"><?php echo $memrow[0]['wrapuptime']; ?> Seconds</option>
<?php
               for ($i = 0; $i <= 30; $i=$i+5) {
                  if ($i != $memrow[0]['wrapuptime']) {
                     echo "<option value='$i'>".$i." Second</option>";
                  }
               }

            } else {
               for ($i = 0; $i <= 30; $i=$i+5) {
                     echo "<option value='$i'>".$i." Second</option>";
               }
            }
?>
            </select>
         </td>
      </tr>
<?php
   }
?>
</table>
</div>
</div>
</form>
<form action="index.php?mod=modify_queues_details" method="post" name="PbxForm1" id="PbxForm1" class="myForms">
<div style="padding-left: 100px; padding-right: 100px">
   <table width="100%" border="0" cellpadding="2" cellspacing="2" class="border">
      <tr>
         <td width="350px">Play Music on Hold</td>
         <td>
            <input name="musiconhold" value="m" <?php if ($row[0]['musiconhold'] == 'm') {echo 'checked';}?> type="checkbox">
         </td>
      </tr>
      <tr>
         <td width="350px">Dynamic Agents</td>
         <td>
            <input id="dynamicagents" name="dynamicagents" value="1" <?php if ($row[0]['dynamicagents'] == '1') {echo 'checked';}?> type="checkbox"><label class="record-request">Request Dynamic code</label>
         </td>
      </tr>
      <tr>
         <td>Queue Forward</td>
         <td>
            <select class="button" name='queueforward' id="queueforward">
               <option value="<?php echo $row[0]['queueforward']; ?>" ><?php if ($row[0]['queueforward'] == "yes") { echo "Yes"; } else { echo "No"; }; ?></option>
<?php
               if ($row[0]['queueforward'] == "yes") {
?>
               <option value="no">No</option>
<?php
               } elseif ($row[0]['queueforward'] == "no") {
?>
               <option value="yes">Yes</option>
<?php
               }
?>
            </select>
         </td>
      </tr>
      <tr name="queuetimeout" id="queuetimeout" hidden>
         <td>
            <label class="" for="queuetimeout">Queue Timeout</label>
         </td>
         <td colspan="4">
	    <select class="button" name="queuetimeout" id="queuetimeout" class="button" style="width: 100%" >
<?php
            if ($row[0]['queuetimeout'] == '') {
?>
               <option value="30">30 Second (Default)</option>
<?php
            } else {
?>
               <option value="<?php echo $row[0]['queuetimeout']; ?>"><?php echo $row[0]['queuetimeout']; ?> Second</option>
<?php
            }
            for ($i = 1; $i <= 120; $i++) {
               if ($i != $row[0]['queuetimeout']) {
                  echo "<option value='$i'>".$i." Second</option>";
               }
            }
?>
            </select>
         </td>
      </tr>
      <tr name="queuedest" id="queuedest" hidden>
      <td>
         <label class="control-label" for="goto0">Destination</label>
      </td>
      <td colspan="4">
	 <select class="button" data-last="" name="destination" id="destination"   tabindex="2" data-id="0" style="width: 100%" required>
<?php
            if ($row[0]['destination'] != '') {
?>
            <option value="<?php echo $row[0]['destination']; ?>"><?php echo $row[0]['destination']; ?></option>
<?php
            } else {
?>
            <option value="" >== choose one ==</option>
            <option value="extensions">Extensions</option>
            <option value="queues">Queues</option>
            <option value="operator">Operator</option>
            <option value="forwarders">Forwarders</option>
<?php
            }
?>
<?php
            if ($row[0]['destination'] == "extensions") {
?>
            <option value="queues">Queues</option>
            <option value="operator">Operator</option>
            <option value="forwarders">Forwarders</option>
<?php
            } elseif ($row[0]['destination'] == "queues") {
?>
            <option value="extensions">Extensions</option>
            <option value="operator">Operator</option>
            <option value="forwarders">Forwarders</option>
<?php
            } elseif ($row[0]['destination'] == "operator") {
?>
            <option value="extensions">Extensions</option>
            <option value="queues">Queues</option>
            <option value="forwarders">Forwarders</option>
<?php
            } elseif ($row[0]['destination'] == "forwarders") {
?> 
            <option value="extensions">Extensions</option>
            <option value="queues">Queues</option>
            <option value="operator">Operator</option>
<?php
            }
?> 
         </select>
         <select class="button" name="dataid" id="extensionsid" style="width: 100%" hidden>
<?php
            if (isset($row[0]['data'])) {
               $extselrow=db_query("SELECT ps_endpoints.id,callerid from ps_endpoints LEFT JOIN ps_registrations USING (id) WHERE ps_registrations.id is null and ps_endpoints.id = ?",array($row[0]['data']));
               if (isset($extselrow[0]['id'])) {
                  echo "<option value='".$extselrow[0]['id']."'>".$extselrow[0]['id']." - ".$extselrow[0]['callerid']."</option>";
               } else {
                  echo "<option value=''>== choose one ==</option>";
               }
            }

            $extrow=db_query("SELECT ps_endpoints.id,callerid from ps_endpoints LEFT JOIN ps_registrations USING (id) WHERE ps_registrations.id is null", array());
            foreach($extrow as $extkey => $extvalue)
            {
               if ($extvalue['id'] != $row[0]['data']) {
                  echo "<option value='".$extvalue['id']."'>".$extvalue['id']." - ".$extvalue['callerid']."</option>";
               }
            }
?>
         </select>
         <select class="button" name="dataid" id="queuesid" style="width: 100%" hidden>
<?php
            $queselrow=db_query("SELECT name from queues WHERE name = ?", array($row[0]['data']));
            if (isset($queselrow[0]['name'])) {
            echo "<option value='".$queselrow[0]['name']."'>".$queselrow[0]['name']."</option>";
            } else {
               echo "<option value=''>== choose one ==</option>";
            }

            $querow=db_query("SELECT name from queues", array());
            foreach($querow as $quekey => $quevalue)
            {
               if ( $quevalue['name'] != $row[0]['data']) {
                  echo "<option value='".$quevalue['name']."'>".$quevalue['name']."</option>";
               }
            }
?>
         </select>
         <select class="button" name="dataid" id="forwardersid" style="width: 100%">
<?php
            $forwselrow=db_query("SELECT uniqueid,name,number from forwarders WHERE uniqueid = ?", array($row[0]['data']));
            if (isset($forwselrow[0]['uniqueid'])) {
               echo "<option value='".$forwselrow[0]['uniqueid']."'>".$forwselrow[0]['name']." ".$forwselrow[0]['number']."</option>";
            } else {
               echo "<option value=''>== choose one ==</option>";
            }

            $forwrow=db_query("SELECT uniqueid,name,number from forwarders", array());
            foreach($forwrow as $forwkey => $forwvalue)
            {
               if ( $forwvalue['uniqueid'] != $forwselrow[0]['uniqueid']) {
                  echo "<option value='".$forwvalue['uniqued']."'>".$forwvalue['name']." ".$forwvalue['number']."</option>";
               }
            }
?>
         </select>
      </td>
   </tr>
</table>
</div>
</form>

<?php
}

function modify_queues_details() {

   error_log(isset($_POST['recordrequest']));

   $name = $_POST['name'];
   $origname = $_POST['origname'];
   $strategy = $_POST['strategy'];
   $timeout = $_POST['timeout'];
   $servicelevel = $_POST['servicelevel'];
   $qwrapuptime = $_POST['qwrapuptime'];
   $joinempty = $_POST['joinempty'];
   $ringinuse = $_POST['ringinuse'];
   $announce_position = $_POST['announce_position'];
   $announce_holdtime = $_POST['announce_holdtime'];
   $announce_frequency = $_POST['announce_frequency'];
   $periodic_announce_frequency = $_POST['periodic_announce_frequency'];

   $format = array(".wav",".mp3",".WAV",".g729");
   if ( $_POST['periodic_announce'] != "undefined" ) {
      $periodic_announce = 'custom/'.str_replace(' ' ,'_', str_replace($format, "", $_POST['periodic_announce']));
   } else {
      $periodic_announce = str_replace($format, "", $_POST["announcement_ext"]);
   }

   if (isset($_POST['musiconhold'])) {
      $musiconhold = $_POST['musiconhold'];
   } else {
      $musiconhold = 'r';
   }
   $queueforward = $_POST['queueforward'];

   if (isset($_POST['queuetimeout'])) {
      $queuetimeout = $_POST['queuetimeout'];
   } else {
      $queuetimeout = '';
   }
   if (isset($_POST['destination'])) {
      $destination = $_POST['destination'];
   } else {
      $destination = '';
   }
   if (isset($_POST['dataid'])) {
      $dataid = $_POST['dataid'];
   } else {
      $dataid = '';
   }

   if (isset($_POST['recordrequest'])) {
      $recordrequest = $_POST['recordrequest'];
      $periodic_announce = 'custom/'.$name."-queue.wav";
   } else {
      $recordrequest = '0';
   }

   if (isset($_POST['dynamicagents'])) {
      $dynamicagents = $_POST['dynamicagents'];
   } else {
      $dynamicagents = '0';
   }

   db_query("UPDATE queues SET name = ?,strategy = ?,timeout = ?,servicelevel = ?,wrapuptime = ?,joinempty = ?,ringinuse = ?,announce_position = ?,announce_holdtime = ?,announce_frequency = ?,periodic_announce = ?,periodic_announce_frequency = ? WHERE name = ?" ,array($name,$strategy,$timeout,$servicelevel,$qwrapuptime,$joinempty,$ringinuse,$announce_position,$announce_holdtime,$announce_frequency,$periodic_announce,$periodic_announce_frequency,$origname)); 
   db_query("UPDATE queues_features SET queue_name = ?, musiconhold = ?,queueforward = ?,queuetimeout = ?,destination = ?, data = ?, recordrequest = ?, dynamicagents = ? WHERE queue_name = ?", array($name,$musiconhold,$queueforward,$queuetimeout,$destination,$dataid,$recordrequest,$dynamicagents,$origname));

   db_query("DELETE FROM queue_members WHERE queue_name = ?", array($origname)); 

   foreach ($_POST['penalty'] as $key => $value) {
      $penalty[$key]=$value;
   }
   foreach ($_POST['wrapuptime'] as $key => $value) {
      $wrapuptime[$key]=$value;
   }

   foreach ($_POST['enable'] as $key => $value) {
      $pieces = explode(",", $value);
         db_query("INSERT INTO queue_members (queue_name,interface,membername,state_interface,penalty,paused,wrapuptime) VALUES (?,?,?,?,?,?,?)", array($name,'PJSIP/'.$pieces[0],$pieces[1],'PJSIP/'.$pieces[0],$penalty[$key],'0',$wrapuptime[$key]));
   }

   foreach ($_POST['forpenalty'] as $key => $value) {
      $forpenalty[$key]=$value;
   }
   foreach ($_POST['forwrapuptime'] as $key => $value) {
      $forwrapuptime[$key]=$value;
   }

   foreach ($_POST['forenable'] as $key => $value) {
      $pieces = explode(",", $value);
         db_query("INSERT INTO queue_members (queue_name,interface,membername,state_interface,penalty,paused,wrapuptime) VALUES (?,?,?,?,?,?,?)", array($name,'local/'.$pieces[0].'@forwarders','local/'.$pieces[0].'@forwarders','',$forpenalty[$key],'0',$forwrapuptime[$key]));
   }
 
   queues();
}

function delete_queues() {

   $name = $_GET['name'];

   db_query("DELETE FROM queues WHERE name = ?", array($name));
   db_query("DELETE FROM queues_features WHERE queue_name = ?", array($name));
   db_query("DELETE FROM queue_members WHERE queue_name = ?", array($name));
   db_query("DELETE FROM extensions WHERE appdata = ?", array('record-prompt,s,1('.$name.'-queue)'));
   db_query("DELETE FROM extensions WHERE appdata = ?", array('agent-login,s,1('.$name.')'));
   db_query("DELETE FROM extensions WHERE appdata = ?", array('agent-logoff,s,1('.$name.')'));
   db_query("DELETE FROM extensions WHERE appdata = ?", array('queue,s,1('.$name.')'));

   queues();
}
?>


