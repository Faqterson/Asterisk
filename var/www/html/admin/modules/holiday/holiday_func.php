<?php

function holiday() {

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

   $row=db_query("SELECT holiday.description,didnumber,announcement,holiday.destination,holiday.uniqueid from holiday LEFT JOIN inbound ON (inbound.uniqueid = didid) LIMIT $offset, $rowsperpage", array());

   echo "<table width=100%>";
   echo "<tr>";
   echo "<th align='left'>Description</th>";
   echo "<th align='left'>DDI</th>";
   echo "<th align='left'>Announcement</th>";
   echo "<th align='left'>Destination</th>";
   echo "<th align='left'>Modify</th>";
   echo "</tr>";
   foreach($row as $key => $value)
   {
      echo "<tr>";
      echo "<td align='left'>" . $value['description'] . "</td>";
      echo "<td align='left'>" . $value['didnumber'] . "</td>";
      $play = "";
      if (file_exists("/var/lib/asterisk/sounds/custom/".$value['announcement'].".wav")) {
         $play = "<a style='background: none' href=custom/".$value['announcement'].".wav><img style='margin: -5px;' src=img/play-icon.png height='20' width='20'></a>";
      } elseif (file_exists("/var/lib/asterisk/sounds/custom/".$value['announcement'].".WAV")) {
         $play = "<a style='background: none' href=custom/".$value['announcement'].".WAV><img style='margin: -5px;' src=img/play-icon.png height='20' width='20'></a>";
      }
      echo "<td align='left'>" . $value['announcement'] . "&nbsp;&nbsp;&nbsp;". $play ."</td>";
      echo "<td align='left'>" . $value['destination'] . "</td>";
      echo "<td align='left'>";
      echo "<a style='background: none' href='index.php?mod=modify_holiday&id=" . $value['uniqueid'] . "' title=''><img src=img/edit-icon.png height='20' width='20'></a>&nbsp";
      echo "<a style='background: none' href='index.php?mod=delete_holiday&id=" . $value['uniqueid'] . "' title=''><img class='delete' src=img/delete-icon.png height='20' width='20'></a>";
      echo "</td>";
      echo "</tr>";
   }
   echo "</table>";
?>
   <table width="100%" border="0" cellpadding="2" cellspacing="2">
   <tr>
      <td align="center">
      <?php
      $resultPage = db_query("SELECT holiday.description,didnumber,announcement,holiday.destination from holiday LEFT JOIN inbound ON (inbound.uniqueid = didid)", array());
      pagination('index.php?mod=holiday',$rowsperpage,$resultPage,$page);                
      ?>
      </td>
   </tr>
   </table>
<?php
   echo "<form action='index.php?mod=add_holiday' method='post'>";
   echo "<input class='button' type='submit' value='Add Holiday' />";
   echo "</form>";

}

function add_holiday() {
?>
<form name="edit" id="edit" action="index.php?mod=add_holiday_details" method="post" enctype="multipart/form-data" class="" >
   <div style="padding-left: 100px; padding-right: 100px">
   <table style="width: 100%">
   <tr>
      <td>
         <label class="" for="description">Description</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="description" name="description" value="" required style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="didnumber">DID Number</label>
      </td>
      <td colspan="4">
	 <select class="button" data-last="" name="didid" id="didid" class="" style="width: 100%" required>
            <option value="" >== choose one ==</option>
<?php
            $row=db_query("SELECT uniqueid,didnumber from inbound WHERE destination = 'timeconditions'", array());
            foreach($row as $key => $value)
            {
               echo "<option value='".$value['uniqueid']."'>".$value['didnumber']."</option>";
            }
?>
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="announcement">Annoucement</label>
      </td>
      <td colspan="4">
         <input type="file" class="" id="announcement" name="announcement" value="" style="width:100%">
         <input name="recordrequest" value="1" type="checkbox"><label class="record-request">Request Recording code</label>
      </td>
   </tr>
   <tr>
      <td>
         <label class="control-label" for="goto0">Destination</label>
      </td>
      <td colspan="4">
	 <select class="button" data-last="" name="destination" id="destination" class=""  tabindex="2" data-id="0" style="width: 100%" required>
            <option value="">== choose one ==</option>
            <option value="ivr">IVR</option>
            <option value="extensions">Extensions</option>
            <option value="queues">Queues</option>
            <option value="forwarders">Forwarders</option>
            <option value="operator">Operator</option>
            <option value="voicemail">Voicemail</option>
            <option value="hangup">Hangup</option>
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
         <select class="button" name="dataid" id="forwardersid" style="width: 100%" hidden>
            <option value="" >== choose one ==</option>
<?php
            $row=db_query("select uniqueid,number from forwarders", array());
            foreach($row as $key => $value)
            {
               echo "<option value='".$value['uniqueid']."'>".$value['name']."</option>";
            }
?>
         </select>
         <select class="button" name="dataid" id="ivrid" style="width: 100%" hidden>
            <option value="" >== choose one ==</option>
<?php
            $row=db_query("SELECT uniqueid,description from ivr", array());
            foreach($row as $key => $value)
            {
               echo "<option value='".$value['uniqueid']."'>".$value['description']."</option>";
            }
?>
         </select>
         <select class="button" name="dataid" id="voicemailid" style="width: 100%" hidden>
            <option value="" >== choose one ==</option>
<?php
            $row=db_query("SELECT uniqueid,mailbox,fullname from voicemail", array());
            foreach($row as $key => $value)
            {
               echo "<option value='".$value['uniqueid']."'>".$value['mailbox']." ".$value['fullname']."</option>";
            }
?>
         </select>
      </td>
   </tr>
   <tr>
      <td></td>
      <td colspan="4">
         <input class="button" type="submit" value="Add holiday"</input>
      </td>
   </tr>

</table>
</div>
</form>

<?php
}

function add_holiday_details() {

   if ($_FILES["announcement"]["name"] != "") {
      $target_dir = "/var/lib/asterisk/sounds/custom/";
      $target_file = str_replace(' ', '_', $target_dir . basename($_FILES["announcement"]["name"]));
      $uploadOk = 1;
      $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

      if(isset($_POST["submit"])) {
         $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
         if($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
         } else {
            echo "File is not an image.";
            $uploadOk = 0;
         }
      }

      // Check if file already exists
      if (file_exists($target_file)) { 
         unlink($target_file);
         //echo "Sorry, file already exists.\n";
         $uploadOk = 1;
      } 
      // Check file size
      if ($_FILES["fileToUpload"]["size"] > 10240000) {
         echo "Sorry, your file is too large.\n";
         $uploadOk = 0;
      }
      // Allow certain file formats
      if($imageFileType != "wav" && $imageFileType != "WAV" && $imageFileType != "mp3" && $imageFileType != "g729" ) {
         echo "Sorry, only WAV, MP3 & G729 files are allowed.\n";
         $uploadOk = 0;
      }

      // Check if $uploadOk is set to 0 by an error
      if ($uploadOk == 0) {
         echo "Sorry, your file was not uploaded.\n";
      // if everything is ok, try to upload file
      } else {
         if (move_uploaded_file($_FILES["announcement"]["tmp_name"], $target_file)) {
            echo "The file ". basename( $_FILES["announcement"]["name"]). " has been uploaded.\n";
            exec('sudo /usr/local/scripts/audioconvert '.$target_file);
         } else {
            echo "Sorry, there was an error uploading your file.\n";
         }
      }
   } else {
      $_FILES["announcement"]["name"] = "silence";
   }

   $description = $_POST['description'];
   $didid = $_POST['didid'];

   $row = db_query("SELECT didnumber from inbound WHERE uniqueid = ?", array($didid));
   $ddi = $row[0]['didnumber'];

   if (isset($_POST['recordrequest'])) {
      $recordrequest = $_POST['recordrequest'];

      $row = db_query("SELECT MAX(CAST(SUBSTR(exten, 2) AS UNSIGNED)) as max FROM extensions WHERE appdata REGEXP '^record-prompt';" ,array());
      $recordcode = $row[0]['max']+1;
      if ($row[0]['max'] != null) {
         db_query("INSERT INTO extensions (context,exten,priority,app,appdata) VALUES (?,?,?,?,?)", array('extensions','*'.$recordcode,'1','Gosub','record-prompt,s,1('.$ddi.'-holiday)'));
         $_FILES["announcement"]["name"] = $ddi."-holiday.wav";
         echo "<script>alert('Your recording code is *$recordcode');</script>";
      } else {
         db_query("INSERT INTO extensions (context,exten,priority,app,appdata) VALUES (?,?,?,?,?)", array('extensions','*1000','1','Gosub','record-prompt,s,1('.$ddi.'-holiday)'));
         $_FILES["announcement"]["name"] = $ddi."-holiday.wav";
         echo "<script>alert('Your recording code is *1000');</script>";
      }

   } else {
      $recordrequest = '0';
   }

   $format = array(".wav",".mp3",".WAV",".g729");
   $announcement = str_replace(' ' ,'_', str_replace($format, "", $_FILES["announcement"]["name"]));

   $destination = $_POST['destination'];
   if (isset($_POST['dataid'])) {
      $dataid = $_POST['dataid'];
   } else {
      $dataid = '';
   }

   db_query("INSERT INTO holiday (description,didid,announcement,destination,data,recordrequest) VALUES (?,?,?,?,?,?)", array($description,$didid,$announcement,$destination,$dataid,$recordrequest));

   holiday();
}

function modify_holiday() {

   $ID = $_GET['id'];
   $row = db_query("SELECT holiday.description,didnumber,announcement,holiday.destination,holiday.data,recordrequest from holiday LEFT JOIN inbound ON (inbound.uniqueid = didid) WHERE holiday.uniqueid = ?", array($ID));

?>
<form name="edit" id="edit" action="index.php?mod=modify_holiday_details" method="post" enctype="multipart/form-data" class="" >
   <input name="id" id="id" value="<?php echo $ID; ?>" type="hidden">
   <div style="padding-left: 100px; padding-right: 100px">
   <table style="width: 100%">
   <tr>
      <td>
         <label class="" for="description">Description</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="description" name="description" value="<?php echo $row[0]['description']; ?>" required style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="didnumber">DID Number</label>
      </td>
      <td colspan="4">
	 <select class="button" data-last="" name="didid" id="didid" class="" style="width: 100%" required>
<?php
            $inbselrow=db_query("SELECT uniqueid,didnumber from inbound WHERE didnumber = ?", array($row[0]['didnumber']));
            if (isset($inbselrow[0]['uniqueid'])) {
               echo "<option value='".$inbselrow[0]['uniqueid']."'>".$inbselrow[0]['didnumber']."</option>";
            } else {
               echo "<option value=''>== choose one ==</option>";
            }

            $inbrow=db_query("SELECT uniqueid,didnumber from inbound WHERE destination = 'timeconditions'", array());
            foreach($inbrow as $inbkey => $inbvalue)
            {
               if ($inbselrow[0]['uniqueid'] != $inbvalue['uniqueid'] ) {
                  echo "<option value='".$inbvalue['uniqueid']."'>".$inbvalue['didnumber']."</option>";
               }
            }
?>
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="announcement">Annoucement</label>
      </td>
      <td>
         <input type="text" class="" id="announcement_ext" name="announcement_ext" value="<?php if ($row[0]['announcement'] != "") { echo $row[0]['announcement'] . ".wav"; } ?>" readonly="readonly" style="width:100%">
      </td>
      <td colspan="3">
         <input type="file" class="" id="announcement" name="announcement" style="width:100%">
         <input name="recordrequest" value="1" <?php if ($row[0]['recordrequest'] == '1') {echo 'checked';}?> type="checkbox"><label class="record-request">Request Recording code</label>
      </td>
   </tr>
   <tr>
      <td>
         <label class="control-label" for="goto0">Destination</label>
      </td>
      <td colspan="4">
	 <select class="button" data-last="" name="destination" id="destination" class=""  tabindex="2" data-id="0" style="width: 100%" required>
<?php
            if (isset($row[0]['destination'])) {
?>
            <option value="<?php echo $row[0]['destination']; ?>"><?php echo $row[0]['destination']; ?></option>
<?php
            } else {
?>
            <option value="" >== choose one ==</option>
            <option value="ivr">IVR</option>
            <option value="extensions">Extensions</option>
            <option value="queues">Queues</option>
            <option value="forwarders">Forwarders</option>
            <option value="operator">Operator</option>
            <option value="voicemail">Voicemail</option>
            <option value="hangup">Hangup</option>
<?php
            }
            if ($row[0]['destination'] == "extensions") {
?>
            <option value="ivr">IVR</option>
            <option value="queues">Queues</option>
            <option value="forwarders">Forwarders</option>
            <option value="operator">Operator</option>
            <option value="voicemail">Voicemail</option>
            <option value="hangup">Hangup</option>
<?php
            } elseif ($row[0]['destination'] == "ivr") {
?>
            <option value="extensions">Extensions</option>
            <option value="queues">Queues</option>
            <option value="forwarders">Forwarders</option>
            <option value="operator">Operator</option>
            <option value="voicemail">Voicemail</option>
            <option value="hangup">Hangup</option>
<?php
            } elseif ($row[0]['destination'] == "queues") {
?>
            <option value="ivr">IVR</option>
            <option value="forwarders">Forwarders</option>
            <option value="extensions">Extensions</option>
            <option value="operator">Operator</option>
            <option value="voicemail">Voicemail</option>
            <option value="hangup">Hangup</option>
<?php
            } elseif ($row[0]['destination'] == "forwarders") {
?>
            <option value="ivr">IVR</option>
            <option value="queues">Queues</option>
            <option value="extensions">Extensions</option>
            <option value="operator">Operator</option>
            <option value="voicemail">Voicemail</option>
            <option value="hangup">Hangup</option>
<?php
            } elseif ($row[0]['destination'] == "operator") {
?>
            <option value="ivr">IVR</option>
            <option value="extensions">Extensions</option>
            <option value="queues">Queues</option>
            <option value="forwarders">Forwarders</option>
            <option value="voicemail">Voicemail</option>
            <option value="hangup">Hangup</option>
<?php
            } elseif ($row[0]['destination'] == "voicemail") {
?>
            <option value="ivr">IVR</option>
            <option value="extensions">Extensions</option>
            <option value="queues">Queues</option>
            <option value="forwarders">Forwarders</option>
            <option value="voicemail">Voicemail</option>
            <option value="hangup">Hangup</option>
<?php
            } elseif ($row[0]['destination'] == "hangup") {
?>
            <option value="ivr">IVR</option>
            <option value="extensions">Extensions</option>
            <option value="queues">Queues</option>
            <option value="forwarders">Forwarders</option>
            <option value="voicemail">Voicemail</option>
            <option value="hangup">Hangup</option>
<?php
            }
?>
         </select>
         <select class="button" name="dataid" id="extensionsid" style="width: 100%" hidden>
<?php
            $extselrow=db_query("SELECT ps_endpoints.id,callerid from ps_endpoints LEFT JOIN ps_registrations USING (id) WHERE ps_registrations.id is null and ps_endpoints.id = ?",array($row[0]['data']));
            if (isset($extselrow[0]['id'])) {
               echo "<option value='".$extselrow[0]['id']."'>".$extselrow[0]['id']." - ".$extselrow[0]['callerid']."</option>";
            } else {
               echo "<option value=''>== choose one ==</option>";
            }

            $extrow=db_query("SELECT ps_endpoints.id,callerid from ps_endpoints LEFT JOIN ps_registrations USING (id) WHERE ps_registrations.id is null", array());
            foreach($extrow as $extkey => $extvalue)
            {
               if ($extvalue['id'] != $extselrow[0]['id']) {
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
               if ( $quevalue['name'] != $queselrow[0]['name']) {
                  echo "<option value='".$quevalue['name']."'>".$quevalue['name']."</option>";
               }
            }
?>
         </select>
         <select class="button" name="dataid" id="forwardersid" style="width: 100%" hidden>
<?php
            $forwselrow=db_query("SELECT uniqueid,name,number from forwarders WHERE uniqueid = ?", array($row[0]['data']));
            if (isset($forwselrow[0]['uniqueid'])) {
               echo "<option value='".$forwselrow[0]['uniqueid']."'>".$forwselrow[0]['name']." - ".$forwselrow[0]['number']."</option>";
            } else {
               echo "<option value=''>== choose one ==</option>";
            }

            $forwrow=db_query("SELECT uniqueid,name,number from forwarders", array());
            foreach($forwrow as $forwkey => $forwvalue)
            {
               if ( $forwvalue['uniqueid'] != $forwselrow[0]['uniqueid']) {
                  echo "<option value='".$forwvalue['uniqueid']."'>".$forwvalue['name']." - ".$forwvalue['number']."</option>";
               }
            }
?>
         </select>
         <select class="button" name="dataid" id="ivrid" style="width: 100%" hidden>
<?php
            $ivrselrow=db_query("SELECT uniqueid,description from ivr WHERE uniqueid = ?", array($row[0]['data']));
            if (isset($ivrselrow[0]['uniqueid'])) {
            echo "<option value='".$ivrselrow[0]['uniqueid']."'>".$ivrselrow[0]['description']."</option>";
            } else {
               echo "<option value=''>== choose one ==</option>";
            }

            $ivrrow=db_query("SELECT uniqueid,description from ivr", array());
            foreach($ivrrow as $ivrkey => $ivrvalue)
            {
               if ( $ivrvalue['uniqueid'] != $ivrselrow[0]['uniqueid']) {
                  echo "<option value='".$ivrvalue['uniqueid']."'>".$ivrvalue['description']."</option>";
               }
            }
?>
         </select>
         <select class="button" name="dataid" id="voicemailid" style="width: 100%" hidden>
<?php
            $vmselrow=db_query("SELECT uniqueid,mailbox,fullname from voicemail WHERE uniqueid = ?", array($row[0]['data']));
            if (isset($vmselrow[0]['uniqueid'])) {
            echo "<option value='".$vmselrow[0]['uniqueid']."'>".$vmselrow[0]['mailbox']." ".$vmselrow[0]['fullname']."</option>";
            } else {
               echo "<option value=''>== choose one ==</option>";
            }

            $vmrow=db_query("SELECT uniqueid,mailbox,fullname from voicemail", array());
            foreach($vmrow as $vmkey => $vmvalue)
            {
               if ( $vmvalue['uniqueid'] != $vmselrow[0]['uniqueid']) {
                  echo "<option value='".$vmvalue['uniqueid']."'>".$vmvalue['mailbox']." ".$vmvalue['fullname']."</option>";
               }
            }
?>
         </select>
      </td>
   </tr>
   <tr>
      <td></td>
      <td colspan="4">
         <input class="button" type="submit" value="Modify Holiday"</input>
      </td>
   </tr>

</table>
</div>
</form>

<?php
}

function modify_holiday_details() {

   if ($_FILES["announcement"]["name"] != "") {
      $target_dir = "/var/lib/asterisk/sounds/custom/";
      $target_file = str_replace(' ', '_', $target_dir . basename($_FILES["announcement"]["name"]));
      $uploadOk = 1;
      $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

      if(isset($_POST["submit"])) {
         $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
         if($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
         } else {
            echo "File is not an image.";
            $uploadOk = 0;
         }
      }

      // Check if file already exists
      if (file_exists($target_file)) { 
         unlink($target_file);
         //echo "Sorry, file already exists.\n";
         $uploadOk = 1;
      } 
      // Check file size
      if ($_FILES["fileToUpload"]["size"] > 10240000) {
         echo "Sorry, your file is too large.\n";
         $uploadOk = 0;
      }
      // Allow certain file formats
      if($imageFileType != "wav" && $imageFileType != "WAV" && $imageFileType != "mp3" && $imageFileType != "g729" ) {
         echo "Sorry, only WAV, MP3 & G729 files are allowed.\n";
         $uploadOk = 0;
      }

      // Check if $uploadOk is set to 0 by an error
      if ($uploadOk == 0) {
         echo "Sorry, your file was not uploaded.\n";
      // if everything is ok, try to upload file
      } else {
         if (move_uploaded_file($_FILES["announcement"]["tmp_name"], $target_file)) {
            echo "The file ". basename( $_FILES["announcement"]["name"]). " has been uploaded.\n";
            exec('sudo /usr/local/scripts/audioconvert '.$target_file);
         } else {
            echo "Sorry, there was an error uploading your file.\n";
         }
      }
   } else {
      $_FILES["announcement"]["name"] = $_POST['announcement_ext'];
   }

   $id = $_POST['id'];
   $description = $_POST['description'];
   $didid = $_POST['didid'];

   $row = db_query("SELECT didnumber from inbound WHERE uniqueid = ?", array($didid));
   $ddi = $row[0]['didnumber'];

   if (isset($_POST['recordrequest'])) {
      $recordrequest = $_POST['recordrequest'];

      $row = db_query("SELECT exten FROM extensions WHERE appdata like ?", array('%'.$ddi.'-holiday%'));
      $exten = $row[0]['exten'];
      if ($row[0]['exten'] != null) {
         $_FILES["announcement"]["name"] = $ddi."-holiday.wav";
         echo "<script>alert('Your recording code is $exten');</script>";
      } else {
         $row =db_query("SELECT MAX(CAST(SUBSTR(exten, 2) AS UNSIGNED)) as max FROM extensions WHERE appdata REGEXP '^record-prompt';", array());
         $recordcode = $row[0]['max']+1;
         if ($row[0]['max'] != null) {
             db_query("INSERT INTO extensions (context,exten,priority,app,appdata) VALUES (?,?,?,?,?)", array('extensions','*'.$recordcode,'1','Gosub','record-prompt,s,1('.$ddi.'-holiday)'));
             $_FILES["announcement"]["name"] = $ddi."-holiday.wav";
             echo "<script>alert('Your recording code is *$recordcode');</script>";
         } else {
             db_query("INSERT INTO extensions (context,exten,priority,app,appdata) VALUES (?,?,?,?,?)", array('extensions','*1000','1','Gosub','record-prompt,s,1('.$ddi.'-holiday)'));
             $_FILES["announcement"]["name"] = $ddi."-holiday.wav";
             echo "<script>alert('Your recording code is *1000');</script>";
         }
      }
   } else {
      $recordrequest = '0';
   }

   $format = array(".wav",".mp3",".WAV",".g729");
   $announcement = str_replace(' ' ,'_', str_replace($format, "", $_FILES["announcement"]["name"]));

   $destination = $_POST['destination'];
   if (isset($_POST['dataid'])) {
      $dataid = $_POST['dataid'];
   } else {
      $dataid = '';
   }

   db_query("UPDATE holiday SET description = ?,didid = ?,announcement = ?,destination = ?,data = ?,recordrequest = ? WHERE uniqueid = ?", array($description,$didid,$announcement,$destination,$dataid,$recordrequest,$id));

   holiday();
}

function delete_holiday() {

   $id = $_GET['id'];

   $row = db_query("SELECT didnumber from inbound LEFT JOIN holiday ON (inbound.uniqueid = didid) WHERE holiday.uniqueid = ?", array($id));
   $didnumber = $row[0]['didnumber'];

   db_query("DELETE FROM extensions WHERE appdata like ?", array('%'.$didnumber.'-holiday%'));

   db_query("DELETE FROM holiday WHERE uniqueid = ?", array($id));

   holiday();
}
?>

