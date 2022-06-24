<?php

function ivr() {
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

   $row=db_query("SELECT description,announcement,uniqueid from ivr LIMIT $offset, $rowsperpage", array());

   echo "<table width=100%>";
   echo "<tr>";
   echo "<th align='left'>Description</th>";
   echo "<th align='left'>Announcement</th>";
   echo "<th align='left'>Modify</th>";
   echo "</tr>";
   foreach($row as $key => $value)
   {
      echo "<tr>";
      echo "<td align='left'>" . $value['description'] . "</td>";
      $play = "";
      if (file_exists("/var/lib/asterisk/sounds/custom/".$value['announcement'].".wav")) {
         $play = "<a style='background: none' href=custom/".$value['announcement'].".wav><img style='margin: -5px;' src=img/play-icon.png height='20' width='20'></a>";
      } elseif (file_exists("/var/lib/asterisk/sounds/custom/".$value['announcement'].".WAV")) {
         $play = "<a style='background: none' href=custom/".$value['announcement'].".WAV><img style='margin: -5px;' src=img/play-icon.png height='20' width='20'></a>";
      }
      echo "<td align='left'>" . $value['announcement'] . "&nbsp;&nbsp;&nbsp;". $play ."</td>";
      echo "<td align='left'>";
      echo "<a style='background: none' href='index.php?mod=modify_ivr&id=" . $value['uniqueid'] . "' title=''><img src=img/edit-icon.png height='20' width='20'></a>&nbsp";
      echo "<a style='background: none' href='index.php?mod=delete_ivr&id=" . $value['uniqueid'] . "' title=''><img class='delete' src=img/delete-icon.png height='20' width='20'></a>";
      echo "</td>";
      echo "</tr>";
   }
   echo "</table>";
?>
   <table width="100%" border="0" cellpadding="2" cellspacing="2">
   <tr>
      <td align="center">
      <?php
      $resultPage = db_query("SELECT description,announcement from ivr", array());
      pagination('index.php?mod=ivr',$rowsperpage,$resultPage,$page);                
      ?>
      </td>
   </tr>
   </table>
<?php
   echo "<form action='index.php?mod=add_ivr' method='post'>";
   echo "<input class='button' type='submit' value='Add IVR' />";
   echo "</form>";

}

function add_ivr() {
?>
<form name="edit" id="edit" action="index.php?mod=add_ivr_details" method="post" enctype="multipart/form-data" class="" >
   <input name="mod" id="mod" value="add_ivr_details" type="hidden">
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
         <label class="" for="announcement">Annoucement</label>
      </td>
      <td colspan="4">
         <input type="file" class="" id="announcement" name="announcement" value="" style="width:100%">
         <input name="recordrequest" value="1" type="checkbox"><label class="record-request">Request Recording code</label>
      </td>
   </tr>



<?php
   $entries = array('1','2','3','4','5','6','7','8','9','0','i','t');
   foreach ($entries as $key => $i) {
?>
   <tr>
      <td>
         <label class="control-label" for="destination[<?php echo $i; ?>]"><?php if($i == 'i') { echo "Invalid "; } elseif($i == 't') { echo "Time Out"; } else { echo $i; } ?></label>
      </td>
      <td colspan="4">
	 <select class="button" data-last="" name="destination[<?php echo $i; ?>]" id="destination<?php echo $i; ?>" class=""  tabindex="2" data-id="0" style="width: 100%" >
<?php
            if (isset($destination[$i])) {
?>
            <option value="<?php echo $destination[$i]; ?>"><?php echo $destination[$i]; ?></option>
<?php
            } else {
?>
            <option value="" >== choose one ==</option>
            <option value="ivr">IVR</option>
            <option value="extensions">Extensions</option>
            <option value="queues">Queues</option>
            <option value="forwarders">Forwarders</option>
            <option value="operator">Operator</option>
<?php
            }
?>
<?php
            if ($destination[$i] == "extensions") {
?>
            <option value="ivr">IVR</option>
            <option value="queues">Queues</option>
            <option value="forwarders">Forwarders</option>
            <option value="operator">Operator</option>
            <option value="delete">Remove</option>
<?php
            } elseif ($destination[$i] == "ivr") {
?>
            <option value="extensions">Extensions</option>
            <option value="queues">Queues</option>
            <option value="forwarders">Forwarders</option>
            <option value="operator">Operator</option>
            <option value="delete">Remove</option>
<?php
            } elseif ($destination[$i] == "queues") {
?>
            <option value="ivr">IVR</option>
            <option value="extensions">Extensions</option>
            <option value="forwarders">Forwarders</option>
            <option value="operator">Operator</option>
            <option value="delete">Remove</option>
<?php
            } elseif ($destination[$i] == "forwarders") {
?>
            <option value="ivr">IVR</option>
            <option value="extensions">Extensions</option>
            <option value="queues">Queues</option>
            <option value="operator">Operator</option>
            <option value="delete">Remove</option>
<?php
            } elseif ($destination[$i] == "operator") {
?>
            <option value="ivr">IVR</option>
            <option value="extensions">Extensions</option>
            <option value="queues">Queues</option>
            <option value="forwarders">Forwarders</option>
            <option value="delete">Remove</option>
<?php
            }
?>
         </select>
         <select class="button" name="dataid[<?php echo $i; ?>]" id="extensionsid<?php echo $i; ?>" style="width: 100%" hidden>

            <option value="" >== choose one ==</option>
<?php
            $row=db_query("SELECT ps_endpoints.id,callerid from ps_endpoints LEFT JOIN ps_registrations ON (ps_endpoints.id = ps_registrations.id) WHERE ps_registrations.id is null", array());
            foreach($row as $key => $value)
            {
               echo "<option value='".$value['id']."'>".$value['id']." - ".$value['callerid']."</option>";
            }
?>
         </select>
         <select class="button" name="dataid[<?php echo $i; ?>]" id="queuesid<?php echo $i; ?>" style="width: 100%" hidden>
            <option value="" >== choose one ==</option>
<?php
            $row=db_query("SELECT name from queues", array());
            foreach($row as $key => $value)
            {
               echo "<option value='".$value['name']."'>".$value['name']."</option>";
            }
?>
         </select>
         <select class="button" name="dataid[<?php echo $i; ?>]" id="forwardersid<?php echo $i; ?>" style="width: 100%">
            <option value="" >== choose one ==</option>
<?php
            $row=db_query("select uniqueid,number,name from forwarders", array());
            foreach($row as $key => $value)
            {
               echo "<option value='".$value['uniqueid']."'>".$value['number']." - ".$value['name']."</option>";
            }
?>
         </select>
         <select class="button" name="dataid[<?php echo $i; ?>]" id="ivrid<?php echo $i; ?>" style="width: 100%" hidden>
            <option value="" >== choose one ==</option>
<?php
            $row=db_query("SELECT uniqueid,description from ivr", array());
            foreach($row  as $key => $value)
            {
               echo "<option value='".$value['uniqueid']."'>".$value['description']."</option>";
            }
?>
         </select>
      </td>
   </tr>
<?php
   }
?>
   <tr>
      <td></td>
      <td colspan="4">
         <input class="button" type="submit" value="Add IVR"</input>
      </td>
   </tr>

</table>
</div>
</form>

<?php
}

function add_ivr_details() {
   if ($_FILES["announcement"]["name"] != "") {
      $target_dir = "/var/lib/asterisk/sounds/custom/";
      $target_file = str_replace(' ', '_', $target_dir . basename($_FILES["announcement"]["name"]));
      $uploadOk = 1;
      $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

      if(isset($_POST["submit"])) {
         $check = getimagesize($_FILES["announcement"]["tmp_name"]);
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
      if ($_FILES["announcement"]["size"] > 10240000) {
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
   }

   $description = $_POST['description'];

   if (isset($_POST['recordrequest'])) {
      $recordrequest = $_POST['recordrequest'];

      $row = db_query("SELECT MAX(CAST(SUBSTR(exten, 2) AS UNSIGNED)) as max FROM extensions WHERE appdata REGEXP '^record-prompt'", array());
      $recordcode = $row[0]['max']+1;
      if ($row[0]['max'] != null) {
         db_query("INSERT INTO extensions (context,exten,priority,app,appdata) VALUES (?,?,?,?,?)", array('extensions','*'.$recordcode,'1','Gosub','record-prompt,s,1('.$description.'-ivr)'));
         $_FILES["announcement"]["name"] = $description."-ivr.wav";
         echo "<script>alert('Your recording code is *$recordcode');</script>";
      } else {
         db_query("INSERT INTO extensions (context,exten,priority,app,appdata) VALUES (?,?,?,?,?)", array('extensions','*1000','1','Gosub','record-prompt,s,1('.$description.'-ivr)'));
         $_FILES["announcement"]["name"] = $description."-ivr.wav";
         echo "<script>alert('Your recording code is *1000');</script>";
      }

   } else {
      $recordrequest = '0';
   }

   $format = array(".wav",".mp3",".WAV",".g729");
   $announcement = str_replace(' ' ,'_', str_replace($format, "", $_FILES["announcement"]["name"]));

   foreach ($_POST['destination'] as $key => $value) {
      $destination[$key]=$value;
   }
   foreach ($_POST['dataid'] as $key => $value) {
      $dataid[$key]=$value;
   }

   db_query("INSERT INTO ivr (description,announcement,recordrequest) VALUES (?,?,?)", array($description,$announcement,$recordrequest));

   $row=db_query("SELECT uniqueid FROM ivr WHERE description = ?", array($description));
   $ivrid = $row[0]['uniqueid'];

   foreach ($destination as $key => $value) {
      if (isset($dataid[$key])) {
         $dataval = $dataid[$key];
      } else {
         $dataval = '';
      }

      if ($value != "") {
         db_query("INSERT INTO ivr_entries (ivrid,entry,destination,data) VALUES (?,?,?,?)", array($ivrid,$key,$value,$dataval));
      }
   }

   ivr();
}

function modify_ivr() {

   $ID = $_GET['id'];
   $row=db_query("SELECT description,announcement,uniqueid,recordrequest from ivr WHERE uniqueid = ?", array($ID));

   $entriesrow=db_query("SELECT entry,destination,data,uniqueid from ivr_entries WHERE ivrid = ?", array($row[0]['uniqueid']));

   foreach($entriesrow as $entrieskey => $entriesvalue) {
      $destination[$entriesvalue['entry']] = $entriesvalue['destination'];
      $data[$entriesvalue['entry']] = $entriesvalue['data'];
      $entryid[$entriesvalue['entry']] = $entriesvalue['uniqueid'];
   }
?>
<form name="edit" id="edit" action="index.php?mod=modify_ivr_details" method="post" enctype="multipart/form-data" class="" >
   <input name="id" id="id" value="<?php echo $ID; ?>" type="hidden">
   <div style="padding-left: 100px; padding-right: 100px">
   <table style="width: 100%">
   <tr>
      <td>
         <label class="" for="description">Description</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="description" name="description" value="<?php echo $row[0]['description'] ?>" required style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="announcement">Annoucement</label>
      </td>
      <td>
         <input type="text" class="" id="announcement_ext" name="announcement_ext" value="<?php if ($row[0]['announcement'] != "") { echo $row[0]['announcement'] . ".wav"; } ?>" readonly="readonly" style="width:100%">
         <input name="recordrequest" value="1" <?php if ($row[0]['recordrequest'] == '1') {echo 'checked';}?> type="checkbox"><label class="record-request">Request Recording code</label>
      </td>
      <td colspan="3">
         <input type="file" class="" id="announcement" name="announcement" style="width:100%">
      </td>
   </tr>

<?php
   $entries = array('1','2','3','4','5','6','7','8','9','0','i','t');
   foreach ($entries as $key => $i) {
?>
   <tr>
      <td>
         <label class="control-label" for="destination[<?php echo $i; ?>]"><?php if($i == 'i') { echo "Invalid "; } elseif($i == 't') { echo "Time Out"; } else { echo $i; } ?></label>
      </td>
      <td colspan="4">
         <input class="" id="entryid<?php echo $i; ?>" name="entryid[<?php echo $i; ?>]" value="<?php echo $entryid[$i]; ?>" type="hidden">
	 <select class="button" data-last="" name="destination[<?php echo $i; ?>]" id="destination<?php echo $i; ?>" class=""  tabindex="2" data-id="0" style="width: 100%" >
<?php
            if (isset($destination[$i])) {
?>
            <option value="<?php echo $destination[$i]; ?>"><?php echo $destination[$i]; ?></option>
<?php
            } else {
?>
            <option value="" >== choose one ==</option>
            <option value="ivr">IVR</option>
            <option value="extensions">Extensions</option>
            <option value="queues">Queues</option>
            <option value="forwarders">Forwarders</option>
            <option value="operator">Operator</option>
<?php
            }
?>
<?php
            if ($destination[$i] == "extensions") {
?>
            <option value="ivr">IVR</option>
            <option value="queues">Queues</option>
            <option value="forwarders">Forwarders</option>
            <option value="operator">Operator</option>
            <option value="delete">Remove</option>
<?php
            } elseif ($destination[$i] == "ivr") {
?>
            <option value="extensions">Extensions</option>
            <option value="queues">Queues</option>
            <option value="forwarders">Forwarders</option>
            <option value="operator">Operator</option>
            <option value="delete">Remove</option>
<?php
            } elseif ($destination[$i] == "queues") {
?>
            <option value="ivr">IVR</option>
            <option value="extensions">Extensions</option>
            <option value="forwarders">Forwarders</option>
            <option value="operator">Operator</option>
            <option value="delete">Remove</option>
<?php
            } elseif ($destination[$i] == "forwarders") {
?>
            <option value="ivr">IVR</option>
            <option value="extensions">Extensions</option>
            <option value="queues">Queues</option>
            <option value="operator">Operator</option>
            <option value="delete">Remove</option>
<?php
            } elseif ($destination[$i] == "operator") {
?>
            <option value="ivr">IVR</option>
            <option value="extensions">Extensions</option>
            <option value="queues">Queues</option>
            <option value="forwarders">Forwarders</option>
            <option value="delete">Remove</option>
<?php
            }
?>
         </select>
         <select class="button" name="dataid[<?php echo $i; ?>]" id="extensionsid<?php echo $i; ?>" style="width: 100%" hidden>
<?php
            $extselrow=db_query("SELECT ps_endpoints.id,callerid from ps_endpoints LEFT JOIN ps_registrations USING (id) WHERE ps_registrations.id is null and ps_endpoints.id = ?",array($data[$i]));
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
         <select class="button" name="dataid[<?php echo $i; ?>]" id="queuesid<?php echo $i; ?>" style="width: 100%" hidden>
<?php
            $queselrow=db_query("SELECT name from queues WHERE name = ?", array($data[$i]));
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
         <select class="button" name="dataid[<?php echo $i; ?>]" id="forwardersid<?php echo $i; ?>" style="width: 100%" hidden>
<?php
            $forwselrow=db_query("SELECT uniqueid,name,number from forwarders WHERE uniqueid = ?", array($data[$i]));
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
         <select class="button" name="dataid[<?php echo $i; ?>]" id="ivrid<?php echo $i; ?>" style="width: 100%" hidden>
<?php
            $ivrselrow=db_query("SELECT uniqueid,description from ivr WHERE uniqueid = ?", array($data[$i]));
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
      </td>
   </tr>
<?php
   }
?>
   <tr>
      <td></td>
      <td colspan="4">
         <input class="button" type="submit" value="Modify IVR"</input>
      </td>
   </tr>

</table>
</div>
</form>

<?php
}

function modify_ivr_details() {

   if ($_FILES["announcement"]["name"] != "") {
      $target_dir = "/var/lib/asterisk/sounds/custom/";
      $target_file = str_replace(' ', '_', $target_dir . basename($_FILES["announcement"]["name"]));
      $uploadOk = 1;
      $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

      if(isset($_POST["submit"])) {
         $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
         if($check !== false) {
            echo "File is an recording - " . $check["mime"] . ".";
            $uploadOk = 1;
         } else {
            echo "File is not an recording.";
            $uploadOk = 0;
         }
      }

      // Check if file already exists
      if (file_exists($target_file)) {
         unlink($target_file);
         echo "File already exists.\n";
         $uploadOk = 0;
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

   if (isset($_POST['recordrequest'])) {
      $recordrequest = $_POST['recordrequest'];

      $row = db_query("SELECT exten FROM extensions WHERE appdata like ?", array('%'.$description.'-ivr%'));
      $exten = $row[0]['exten'];
      if ($row[0]['exten'] != null) {
         $_FILES["announcement"]["name"] = $description."-ivr.wav";
         echo "<script>alert('Your recording code is $exten');</script>";
      } else {
         $row = db_query("SELECT MAX(CAST(SUBSTR(exten, 2) AS UNSIGNED)) as max FROM extensions WHERE appdata REGEXP '^record-prompt';", array());
         $recordcode = $row[0]['max']+1;
         if ($row[0]['max'] != null) {
             db_query("INSERT INTO extensions (context,exten,priority,app,appdata) VALUES (?,?,?,?,?)", array('extensions','*'.$recordcode,'1','Gosub','record-prompt,s,1('.$description.'-ivr)'));
             $_FILES["announcement"]["name"] = $description."-ivr.wav";
             echo "<script>alert('Your recording code is *$recordcode');</script>";
         } else {
             db_query("INSERT INTO extensions (context,exten,priority,app,appdata) VALUES (?,?,?,?,?)", array('extensions','*1000','1','Gosub','record-prompt,s,1('.$description.'-ivr)'));
             $_FILES["announcement"]["name"] = $description."-ivr.wav";
             echo "<script>alert('Your recording code is *1000');</script>";
         }
      }
   } else {	
      $recordrequest = '0';
   }

   $format = array(".wav",".mp3",".WAV",".g729");
   $announcement = str_replace(' ' ,'_', str_replace($format, "", $_FILES["announcement"]["name"]));

   foreach ($_POST['destination'] as $key => $value) {
      $destination[$key]=$value;
   }
   foreach ($_POST['dataid'] as $key => $value) {
      $dataid[$key]=$value;
   }
   foreach ($_POST['entryid'] as $key => $value) {
      $entryid[$key]=$value;
   }

   db_query("UPDATE ivr SET description = ?,announcement = ?,recordrequest = ? WHERE uniqueid = ?", array($description,$announcement,$recordrequest,$id)); 

   foreach ($destination as $key => $value) {
      if ($value != "" && $value != "delete") {
         $data = $dataid[$key];
         db_query("REPLACE INTO ivr_entries (uniqueid,ivrid,entry,destination,data) VALUES (?,?,?,?,?)", array($entryid[$key],$id,$key,$value,$data));
      } elseif ($value == "delete") {
         db_query("DELETE FROM ivr_entries WHERE ivrid = '$id' AND entry = '$key'", array());
      }
   }

   ivr();
}

function delete_ivr() {

   $id = $_GET['id'];

   $row = db_query("SELECT description from ivr WHERE uniqueid = ?", array($id));
   $description = $row[0]['description'];

   db_query("DELETE FROM ivr WHERE uniqueid = ?", array($id));
   db_query("DELETE FROM extensions WHERE appdata like ?", array('%'.$description.'-ivr%'));
   db_query("DELETE FROM ivr_entries WHERE ivrid = ?", array($id));

   ivr();
}
?>

