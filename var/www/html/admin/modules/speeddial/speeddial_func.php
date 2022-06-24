<?php

function speeddials() {

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

   $row=db_query("SELECT uniqueid,name,number,speeddial from speeddials LIMIT $offset, $rowsperpage", array());

   echo "<table width=100%>";
   echo "<tr><th align='left'>Name</th><th align='left'>Number</th><th align='left'>Speeddial</th><th align='left'>Edit</th></tr>";
   foreach($row as $key => $value)
   {
      echo "<tr>";
      echo "<td align='left'>" . $value['name'] . "</td>";
      echo "<td align='left'>" . $value['number'] . "</td>";
      echo "<td align='left'>" . $value['speeddial'] . "</td>";
      echo "<td align='left'>";
      echo "<a style='background: none' href = 'index.php?mod=modify_speeddials&id=" . $value['uniqueid'] . "'><img src=img/edit-icon.png height='20' width='20'></a>&nbsp&nbsp&nbsp";
      echo "<a style='background: none' href = 'index.php?mod=delete_speeddials&id=" . $value['uniqueid'] . "'><img class='delete' src=img/delete-icon.png height='20' width='20'></a></td>";
      echo "</td>";
      echo "</tr>";
   }
   echo "</table>";
?>
   <table width="100%">
   <tr>
      <td align="center">
      <?php
      $resultPage = db_query("SELECT uniqueid,name,number,speeddial from speeddials", array());
      pagination('index.php?mod=speeddials',$rowsperpage,$resultPage,$page);
      ?>
      </td>
   </tr>
   </table>
<?php
   echo "<form action='index.php?mod=add_speeddials' method='post'>";
   echo "<input class='button' type='submit' value='Add Speeddial' />";
   echo "</form>";
}

function add_speeddials() {
?>
<form name="edit" id="edit" action="index.php?mod=add_speeddials_details" method="post" enctype="multipart/form-data" class="" >
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
         <label class="" for="speeddial">Speed Dial</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="speeddial" name="speeddial" value="" required style="width:100%">
      </td>
   </tr>
   <tr>
      <td></td>
      <td colspan="4">
         <input class="button" type="submit" value="Add Speeddial"</input>
      </td>
   </tr>

</table>
</div>
</form>

<?php
}

function add_speeddials_details() {

   $name = $_POST['name'];
   $number = $_POST['number'];
   $speeddial = $_POST['speeddial'];

   db_query("INSERT INTO speeddials (name,number,speeddial) VALUES (?,?,?)", array($name,$number,$speeddial));

   speeddials();
}

function modify_speeddials() {

   $id = $_GET['id'];
   $row = db_query("SELECT uniqueid,name,number,speeddial from speeddials WHERE uniqueid = ?", array($id));

?>
<form name="edit" id="edit" action="index.php?mod=modify_speeddials_details" method="post" enctype="multipart/form-data" class="" >
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
         <label class="" for="speeddial">Speed Dial</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="speeddial" name="speeddial" value="<?php echo $row[0]['speeddial']; ?>" required style="width:100%">
      </td>
   </tr>
   <tr>
      <td></td>
      <td colspan="4">
         <input class="button" type="submit" value="Modify Speeddial"</input>
      </td>
   </tr>
</table>
</div>
</form>

<?php
}

function modify_speeddials_details() {

   $id = $_POST['id'];
   $name = $_POST['name'];
   $number = $_POST['number'];
   $speeddial = $_POST['speeddial'];

   db_query("UPDATE speeddials SET name = ?,number = ?,speeddial = ? WHERE uniqueid = ?", array($name,$number,$speeddial,$id)); 

   speeddials();
}

function delete_speeddials() {

   $id = $_GET['id'];

   db_query("DELETE FROM speeddials WHERE uniqueid = ?", array($id));

   speeddials();
}
?>

