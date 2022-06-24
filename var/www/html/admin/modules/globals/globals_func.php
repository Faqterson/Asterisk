<?php

function globals() {

   db_query("insert ignore dep_trunk(name,value,department) select 'TRUNK','Use Global',department from departments WHERE department != ''", array());
   db_query("insert ignore dep_trunk(name,value,department) select 'FAILTRUNK','Use Global',department from departments WHERE department != ''", array());   
   db_query("insert ignore dep_trunk(name,value,department) select 'NIGHTMODE','NO',department from departments WHERE department != ''", array());
   db_query("insert ignore dep_trunk(name,value,department) select 'DEFAULTCLI','Use Global',department from departments WHERE department != ''", array());

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

   $row=db_query("SELECT uniqueid,name,value from global LIMIT $offset, $rowsperpage", array());

   echo "<table width=100%>";
   echo "<tr><th align='left'>Name</th><th align='left'>Value</th><th align='left'>Edit</th></tr>";
   foreach ($row as $key => $value)
   {
      echo "<tr>";
      echo "<td align='left'>" . $value['name'] . "</td>";
      echo "<td align='left'>" . $value['value'] . "</td>";
      echo "<td align='left'>";
      echo "<a style='background: none' href = 'index.php?mod=modify_global&id=" . $value['uniqueid'] . "'><img src=img/edit-icon.png height='20' width='20'></a>&nbsp&nbsp&nbsp";
      echo "</td>";
      echo "</tr>";
   }
   
   $row=db_query("SELECT DISTINCT(department) as department from departments",array());

   foreach ($row as $key => $value)
   {
      echo "<tr><th align='left'>" . $value['department'] . "</th><th></th><th></th></tr>";

      $dep = $value['department'];
      $deprow=db_query("SELECT uniqueid,name,value from dep_trunk WHERE department = ?", array($dep));

      foreach ($deprow as $depkey => $depvalue)
      {
         echo "<tr>";
         echo "<td align='left'>" . $depvalue['name'] . "</td>";
         echo "<td align='left'>" . $depvalue['value'] . "</td>";
         echo "<td align='left'>";
         echo "<a style='background: none' href = 'index.php?mod=modify_dep_trunk&id=" . $depvalue['uniqueid'] . "'><img src=img/edit-icon.png height='20' width='20'></a>&nbsp&nbsp&nbsp";
         echo "</td>";
         echo "</tr>";
      }
   }
   echo "</table>";
?>
   <table width="100%">
   <tr>
      <td align="center">
      <?php
      $resultPage = db_query("SELECT uniqueid,name,value from global", array());
      pagination('index.php?mod=global',$rowsperpage,$resultPage,$page);
      ?>
      </td>
   </tr>
   </table>
<?php
}

function modify_global() {

   $id = $_GET['id'];
   $row = db_query("SELECT name,value from global WHERE uniqueid = ?", array($id));

?>
<form name="edit" id="edit" action="index.php?mod=modify_global_details" method="post" enctype="multipart/form-data" class="" >
   <input name="id" id="id" value="<?php echo $id; ?>" type="hidden">
   <div style="padding-left: 100px; padding-right: 100px">
   <table style="width: 100%">
   <th>Setting</th><th colspan="2">Value</th>
   <tr>
      <td>
         <label class="" for="name">Name</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="name" name="name" value="<?php echo $row[0]['name']; ?>" disabled style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="value">Value</label>
      </td>
      <td colspan="4">
<?php
         if ($row[0]['name'] == "RECORD") {
?>
	 <select class='button' name="value" id="value" class="" style="width: 100%" >
            <option value="<?php echo $row[0]['value']; ?>" ><?php if ($row[0]['value'] == "YES") { echo "YES"; } else { echo "NO"; }; ?></option>
<?php
            if ($row[0]['value'] == "YES") {
?>
            <option value="NO">NO</option>
<?php
            } elseif ($row[0]['value'] == "NO") {
?>
            <option value="YES">YES</option>
<?php
            }
         } elseif ($row[0]['name'] == "FORWARD_TIMEOUT" || $row[0]['name'] == "VOICEMAIL_TIMEOUT" || $row[0]['name'] == "RETURN_TIMEOUT") {
?>
           <select class='button' name="value" id="value" class="" style="width: 100%" >
            <option value="<?php echo $row[0]['value']; ?>"><?php echo $row[0]['value']; ?> Second</option>
<?php
            for ($i = 1; $i <= 60; $i++) {
       	       if ($i != $row[0]['value']) {
                  echo "<option value='$i'>".$i." Second</option>";
               }
            }
         } elseif ($row[0]['name'] == 'TRUNK' || $row[0]['name'] == 'FAILTRUNK') {
?>
           <select class='button' name="value" id="value" class="" style="width: 100%" >
            <option value="<?php echo $row[0]['value']; ?>"><?php echo $row[0]['value']; ?></option>
<?php
            $iaxrow=db_query("SELECT name from iaxfriends",array());
            $siprow=db_query("SELECT id from ps_registrations ",array());

            foreach ($iaxrow as $iaxkey => $iaxvalue)
            {
               echo "<option value='IAX2/".$iaxvalue['name']."/dst'>IAX2/".$iaxvalue['name']."/dst</option>";
            }
            foreach ($siprow as $sipkey => $sipvalue)
            {
               echo "<option value='PJSIP/dst@".$sipvalue['id']."'>PJSIP/dst@".$sipvalue['id']."</option>";
            }
         } elseif ($row[0]['name'] == "NIGHTMODE") {
?>
	 <select class='button' name="value" id="value" class="" style="width: 100%" >
            <option value="<?php echo $row[0]['value']; ?>" ><?php if ($row[0]['value'] == "YES") { echo "YES"; } else { echo "NO"; }; ?></option>
<?php
            if ($row[0]['value'] == "YES") {
?>
            <option value="NO">NO</option>
<?php
            } elseif ($row[0]['value'] == "NO") {
?>
            <option value="YES">YES</option>
<?php
            }
         } elseif ($row[0]['name'] == "DEFAULTCLI") {
?>
            <input type="text" class="" id="value" name="value" value="<?php echo $row[0]['value']; ?>" style="width:100%">
<?php            
         } elseif ($row[0]['name'] == "STEREO") {
?>
	 <select class='button' name="value" id="value" class="" style="width: 100%" >
            <option value="<?php echo $row[0]['value']; ?>" ><?php if ($row[0]['value'] == "YES") { echo "YES"; } else { echo "NO"; }; ?></option>
<?php
            if ($row[0]['value'] == "YES") {
?>
            <option value="NO">NO</option>
<?php
            } elseif ($row[0]['value'] == "NO") {
?>
            <option value="YES">YES</option>
<?php
            }
         }
?>
      <td>
   </tr>
   <tr>
      <td></td>
      <td colspan="4">
         <input class="button" type="submit" id="submit" value="Modify Global"></input>
      </td>
   </tr>

</table>
</div>
</form>

<?php
}

function modify_global_details() {

   $id = $_POST['id'];
   $name = $_POST['name'];
   $value = $_POST['value'];

   db_query("UPDATE global SET value = ? WHERE uniqueid = ?", array($value,$id)); 

   globals();
}

function modify_dep_trunk() {

   $id = $_GET['id'];
   $row = db_query("SELECT name,value from dep_trunk WHERE uniqueid = ?", array($id));

?>
<form name="edit" id="edit" action="index.php?mod=modify_dep_trunk_details" method="post" enctype="multipart/form-data" class="" >
   <input name="id" id="id" value="<?php echo $id; ?>" type="hidden">
   <div style="padding-left: 100px; padding-right: 100px">
   <table style="width: 100%">
   <th>Setting</th><th colspan="2">Value</th>
   <tr>
      <td>
         <label class="" for="name">Name</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="name" name="name" value="<?php echo $row[0]['name']; ?>" disabled style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="value">Value</label>
      </td>
      <td colspan="4">
<?php
         if ($row[0]['name'] == 'TRUNK' || $row[0]['name'] == 'FAILTRUNK') {
?>
           <select class='button' name="value" id="value" class="" style="width: 100%" >
            <option value="<?php echo $row[0]['value']; ?>"><?php echo $row[0]['value']; ?></option>
<?php
            $iaxrow=db_query("SELECT name from iaxfriends",array());
            $siprow=db_query("SELECT id from ps_registrations ",array());

            foreach ($iaxrow as $iaxkey => $iaxvalue)
            {
               echo "<option value='IAX2/".$iaxvalue['name']."/dst'>IAX2/".$iaxvalue['name']."/dst</option>";
            }
            foreach ($siprow as $sipkey => $sipvalue)
            {
               echo "<option value='PJSIP/dst@".$sipvalue['id']."'>PJSIP/dst@".$sipvalue['id']."</option>";
            }
         } elseif ($row[0]['name'] == "NIGHTMODE") {
?>
         <select class='button' name="value" id="value" class="" style="width: 100%" >
            <option value="<?php echo $row[0]['value']; ?>" ><?php if ($row[0]['value'] == "YES") { echo "YES"; } else { echo "NO"; }; ?></option>
<?php
            if ($row[0]['value'] == "YES") {
?>
            <option value="NO">NO</option>
<?php
            } elseif ($row[0]['value'] == "NO") {
?>
            <option value="YES">YES</option>
<?php
            }
         } elseif ($row[0]['name'] == "DEFAULTCLI") {
?>
            <input type="text" class="" id="value" name="value" value="<?php echo $row[0]['value']; ?>" style="width:100%">
<?php
         }
?>
      <td>
   </tr>
   <tr>
      <td></td>
      <td colspan="4">
         <input class="button" type="submit" id="submit" value="Modify Department"></input>
      </td>
   </tr>

</table>
</div>
</form>

<?php
}

function modify_dep_trunk_details() {
   $id = $_POST['id'];
   $name = $_POST['name'];
   $value = $_POST['value'];

   db_query("UPDATE dep_trunk SET value = ? WHERE uniqueid = ?", array($value,$id)); 

   globals();
}
?>
