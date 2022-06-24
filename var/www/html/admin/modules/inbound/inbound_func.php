<?php

function inbound() {
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

   $row=db_query("SELECT description,didnumber,department,destination,uniqueid from inbound LIMIT $offset, $rowsperpage", array());

   echo "<table width=100%>";
   echo "<tr>";
   echo "<th align='left'>Description</th>";
   echo "<th align='left'>DDI</th>";
   echo "<th align='left'>Department</th>";
   echo "<th align='left'>Destination</th>";
   echo "<th align='left'>Modify</th>";
   echo "</tr>";
   foreach($row as $key => $value)
   {
      echo "<tr>";
      echo "<td align='left'>" . $value['description'] . "</td>";
      echo "<td align='left'>" . $value['didnumber'] . "</td>";
      echo "<td align='left'>" . $value['department'] . "</td>";
      echo "<td align='left'>" . $value['destination'] . "</td>";
      echo "<td align='left'>";
      echo "<a style='background: none' href='index.php?mod=modify_inbound&id=" . $value['uniqueid'] . "' title=''><img src=img/edit-icon.png height='20' width='20'></a>&nbsp";
      echo "<a style='background: none' href='index.php?mod=delete_inbound&id=" . $value['uniqueid'] . "' title=''><img class='delete' src=img/delete-icon.png height='20' width='20'></a>";
      echo "</td>";
      echo "</tr>";
   }
   echo "</table>";
?>
   <table width="100%" border="0" cellpadding="2" cellspacing="2">
   <tr>
      <td align="center">
      <?php
      $resultPage = db_query("SELECT description,didnumber,department,destination from inbound", array());
      pagination('index.php?mod=inbound',$rowsperpage,$resultPage,$page);                
      ?>
      </td>
   </tr>
   </table>
<?php
   echo "<form action='index.php?mod=add_inbound' method='post'>";
   echo "<input class='button' type='submit' value='Add Inbound' />";
   echo "</form>";

}

function add_inbound() {
?>
<form autocomplete="off" name="edit" id="edit" action="index.php?mod=add_in_route" method="post" onsubmit="" class="" >
   <input name="mod" id="mod" value="add_in_route" type="hidden">
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
         <input type="text" class="" id="didnumber" name="didnumber" value="" required style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="cidname">CID name prefix </label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="cidname" name="cidname" value="" style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="department">Department</label>
      </td>
      <td colspan="4">
	 <select class="button" data-last="" name="department" id="department" class="" style="width: 100%" required>
            <option value="" >== choose one ==</option>
<?php
            $row=db_query("SELECT DISTINCT(department) as department from departments", array());
            foreach ($row as $key => $value)
            {
               echo "<option value='".$value['department']."'>".$value['department']."</option>";
            }
?>
         </select>
      </td>
   </tr>
   <tr>
      <td>
         <label class="control-label" for="destination">Destination</label>
      </td>
      <td colspan="4">
	 <select class="button" name="destination" id="destination" class=""  tabindex="2" data-id="0" style="width: 100%" required>
            <option value="" >== choose one ==</option>
            <option value="timeconditions">Time Conditions</option>
            <option value="extensions">Extensions</option>
            <option value="queues">Queues</option>
            <option value="forwarders">Forwarders</option>
            <option value="cloudcall">Cloudcall</option>
         </select>
         <select class="button" name="dataid" id="extensionsid" style="width: 100%">
            <option value="" >== choose one ==</option>
<?php
            $row=db_query("SELECT ps_endpoints.id,callerid from ps_endpoints LEFT JOIN ps_registrations USING (id) WHERE ps_registrations.id is null", array());
            foreach($row as $key => $value)
            {
               echo "<option value='".$value['id']."'>".$value['id']." - ".$value['callerid']."</option>";
            }
?>
	         </select>
         <select class="button" name="dataid" id="queuesid" style="width: 100%">
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
               echo "<option value='".$value['uniqueid']."'>".$value['name']." - ".$value['number']."</option>";
            }
?>
         </select>
      </td>
   </tr>
   <tr>
      <td></td>
      <td colspan="4">
         <input class="button" type="submit" value="Add Route"</input>
      </td>
   </tr>
</table>
</div>
</form>

<?php
}

function add_in_route() {
   $description = $_POST['description'];
   $didnumber = $_POST['didnumber'];
   $cidname = $_POST['cidname'];
   $department = $_POST['department'];
   $destination = $_POST['destination'];

   if (isset($_POST['dataid'])) {
      $dataid = $_POST['dataid'];
   } else {
      $dataid = '';
   }

   db_query("INSERT INTO inbound (description,didnumber,cidname,department,destination,data) VALUES (?,?,?,?,?,?)", array($description,$didnumber,$cidname,$department,$destination,$dataid));

   inbound();
}

function modify_inbound() {
   $ID = $_GET['id'];
   $row=db_query("SELECT description,didnumber,cidname,department,destination,data from inbound WHERE uniqueid = ?", array($ID));

?>
<form autocomplete="off" name="edit" id="edit" action="index.php?mod=modify_inbound_details" method="post" onsubmit="" class="" >
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
         <input type="text" class="" id="didnumber" name="didnumber" value="<?php echo $row[0]['didnumber']; ?>" required style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="cidname">CID name prefix </label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="cidname" name="cidname" value="<?php echo $row[0]['cidname']; ?>" style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="department">Department</label>
      </td>
      <td colspan="4">
	 <select class="button" data-last="" name="department" id="department" class="" style="width: 100%">
            <option value="<?php echo $row[0]['department']; ?>" ><?php echo $row[0]['department']; ?></option>
<?php
            $deprow=db_query("SELECT DISTINCT(department) as department from departments",array());
            foreach($deprow as $depkey => $depvalue)
            {
               echo "<option value='".$depvalue['department']."'>".$depvalue['department']."</option>";
            }
?>
         </select>
      </td>
   </tr>
   <tr>
      <td>
         <label class="control-label" for="destination">Destination</label>
      </td>
      <td colspan="4">
	 <select class="button" name="destination" id="destination" class=""  tabindex="2" data-id="0" style="width: 100%" required>
            <option value="<?php echo $row[0]['destination']; ?>" ><?php echo $row[0]['destination']; ?></option>
<?php
            if ($row[0]['destination'] == "extensions") {
?>
            <option value="timeconditions">Time Conditions</option>
            <option value="queues">Queues</option>
            <option value="forwarders">Forwarders</option>
            <option value="cloudcall">Cloudcall</option>
<?php
            } elseif ($row[0]['destination'] == "timeconditions") {
?> 
            <option value="extensions">Extensions</option>
            <option value="queues">Queues</option>
            <option value="forwarders">Forwarders</option>
            <option value="cloudcall">Cloudcall</option>
<?php
            } elseif ($row[0]['destination'] == "queues") {
?> 
            <option value="timeconditions">Time Conditions</option>
            <option value="extensions">Extensions</option>
            <option value="forwarders">Forwarders</option>
            <option value="cloudcall">Cloudcall</option>
<?php
            } elseif ($row[0]['destination'] == "forwarders") {
?> 
            <option value="timeconditions">Time Conditions</option>
            <option value="extensions">Extensions</option>
            <option value="queues">Queues</option>
            <option value="cloudcall">Cloudcall</option>
<?php
            } elseif ($row[0]['destination'] == "cloudcall") {
?> 
            <option value="timeconditions">Time Conditions</option>
            <option value="extensions">Extensions</option>
            <option value="queues">Queues</option>
            <option value="forwarders">Forwarders</option>
<?php
            }
?> 
         </select>
         <select class="button" name="dataid" id="extensionsid" style="width: 100%">
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
         <select class="button" name="dataid" id="queuesid" style="width: 100%">
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
         <select class="button" name="dataid" id="forwardersid" style="width: 100%">
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
      </td>
   </tr>
   <tr>
      <td></td>
      <td colspan="4">
         <input class="button" type="submit" value="Add Route"</input>
      </td>
   </tr>
</table>
</div>
</form>

<?php
}

function modify_inbound_details() {
   $id = $_POST['id'];
   $description = $_POST['description'];
   $didnumber = $_POST['didnumber'];
   $cidname = $_POST['cidname'];
   $department = $_POST['department'];
   $destination = $_POST['destination'];
   $dataid = $_POST['dataid'];

   db_query("UPDATE inbound SET description = ?,didnumber = ?,cidname = ?,department = ?,destination = ?,data = ? WHERE uniqueid = ?", array($description,$didnumber,$cidname,$department,$destination,$dataid,$id)); 

   inbound();
}

function delete_inbound() {
   $id = $_GET['id'];

   db_query("DELETE FROM inbound WHERE uniqueid = ?", array($id)); 

   inbound();
}
?>

