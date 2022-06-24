<?php

function forwarders() {

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

   $row=db_query("SELECT uniqueid,name,number,department from forwarders LIMIT $offset, $rowsperpage", array());

   echo "<table width=100%>";
   echo "<tr><th align='left'>Name</th><th align='left'>Number</th><th align='left'>Department</th><th align='left'>Edit</th></tr>";
   foreach($row as $key => $value)
   {
      echo "<tr>";
      echo "<td align='left'>" . $value['name'] . "</td>";
      echo "<td align='left'>" . $value['number'] . "</td>";
      echo "<td align='left'>" . $value['department'] . "</td>";
      echo "<td align='left'>";
      echo "<a style='background: none' href = 'index.php?mod=modify_forwarders&id=" . $value['uniqueid'] . "'><img src=img/edit-icon.png height='20' width='20'></a>&nbsp&nbsp&nbsp";
      echo "<a style='background: none' href = 'index.php?mod=delete_forwarders&id=" . $value['uniqueid'] . "'><img class='delete' src=img/delete-icon.png height='20' width='20'></a></td>";
      echo "</td>";
      echo "</tr>";
   }
   echo "</table>";
?>
   <table width="100%">
   <tr>
      <td align="center">
      <?php
      $resultPage = db_query("SELECT uniqueid,name,number from forwarders", array());
      pagination('index.php?mod=forwarders',$rowsperpage,$resultPage,$page);
      ?>
      </td>
   </tr>
   </table>
<?php
   echo "<form action='index.php?mod=add_forwarders' method='post'>";
   echo "<input class='button' type='submit' value='Add Number' />";
   echo "</form>";
}

function add_forwarders() {
?>
<form name="edit" id="edit" action="index.php?mod=add_forwarders_details" method="post" enctype="multipart/form-data" class="" >
   <div style="padding-left: 100px; padding-right: 100px">
   <table style="width: 100%">
   <th>Setting</th><th colspan="2">Value</th>
   <tr>
      <td>
         <label class="" for="name">Name</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="name" name="name" value="" required style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="number">Number</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="number" name="number" value="" required style="width:100%">
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
      <td></td>
      <td colspan="4">
         <input class="button" type="submit" value="Add Number"</input>
      </td>
   </tr>

</table>
</div>
</form>

<?php
}

function add_forwarders_details() {

   $name = $_POST['name'];
   $number = $_POST['number'];
   $department = $_POST['department'];

   db_query("INSERT INTO forwarders (name,number,department) VALUES (?,?,?)", array($name,$number,$department));

   forwarders();
}

function modify_forwarders() {

   $id = $_GET['id'];
   $row = db_query("SELECT uniqueid,name,number,department from forwarders WHERE uniqueid = ?", array($id));

?>
<form name="edit" id="edit" action="index.php?mod=modify_forwarders_details" method="post" enctype="multipart/form-data" class="" >
   <input name="id" id="id" value="<?php echo $row[0]['uniqueid']; ?>" type="hidden">
   <div style="padding-left: 100px; padding-right: 100px">
   <table style="width: 100%">
   <th>Setting</th><th colspan="2">Value</th>
   <tr>
      <td>
         <label class="" for="name">Name</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="name" name="name" value="<?php echo $row[0]['name']; ?>" required style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="number">Number</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="number" name="number" value="<?php echo $row[0]['number']; ?>" required style="width:100%">
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
      <td></td>
      <td colspan="4">
         <input class="button" type="submit" value="Modify Number"</input>
      </td>
   </tr>
</table>
</div>
</form>

<?php
}

function modify_forwarders_details() {

   $id = $_POST['id'];
   $name = $_POST['name'];
   $number = $_POST['number'];
   $department = $_POST['department'];

   db_query("UPDATE forwarders SET name = ?,number = ?,department = ? WHERE uniqueid = ?", array($name,$number,$department,$id)); 

   forwarders();
}

function delete_forwarders() {

   $id = $_GET['id'];

   db_query("DELETE FROM forwarders WHERE uniqueid = ?", array($id));

   forwarders();
}
?>

