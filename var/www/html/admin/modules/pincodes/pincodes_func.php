<?php

function pincodes() {

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

   $row = db_query("SELECT name,pin,international,national,cellular,internal,id FROM pin_codes ORDER by pin LIMIT $offset, $rowsperpage", array());

   echo "<div class=''>";
   echo "<table>";
   echo "<th>Name</th><th>Pin</th><th>International</th><th>National</th><th>Cellular</th><th>Internal</th><th>Edit</th>";
   foreach($row as $key => $value) { 
      echo "<tr align='center'>";
      echo "<td>".$value["name"]."</td>";
      echo "<td>".$value["pin"]."</td>";
      echo "<td>".$value["international"]."</td>";
      echo "<td>".$value["national"]."</td>";
      echo "<td>".$value["cellular"]."</td>";
      echo "<td>".$value["internal"]."</td>";
      echo "<td><a style='background: none' href = 'index.php?mod=editpin&id=".$value['id']."'><img src=img/edit-icon.png height='20' width='20'></a>&nbsp&nbsp&nbsp";
      echo "<a style='background: none' href = 'index.php?mod=delpin&id=".$value['id']."'><img class='delete' src=img/delete-icon.png height='20' width='20'></a></td>";
      echo "</tr>";
   }   
echo "</table>"; 
?>
   <table width="100%" style="background-color: none;">
   <tr style="background-color: none;">
      <td align="center">
      <?php
         $resultPage = db_query("SELECT name,pin,international,national,cellular,internal,id FROM pin_codes", array());
         pagination('index.php?mod=pincodes',$rowsperpage,$resultPage,$page);
      ?>
      </td>
   </tr>
   </table>
<?php

   echo "<table align='center'><tr><td style='border-bottom: none'>";
   echo "<form action='index.php?mod=addpin' method='post'>";
   echo "<input class='button' type='submit' value='Add Pin Code' />";
   echo "</form>";
   echo "</div>";
}

function add_pincodes() {

   echo "<table>";
   echo "<th>Setting</th><th>Value</th>";
   echo "<form action='index.php?mod=addpindetails' method='post'>";
   echo "<tr><td>Name</td><td><input type='text' name='name' value='Username' style='width:100%'></td></tr>";
   echo "<tr><td>Pin</td><td><input type='text' name='pin' style='width:100%'></td></tr>";
   echo "<tr><td>Can call International</td><td><select class='button' name='international' style='width:100%'>";
   echo "<option selected value='yes'>Yes</option> <option value='no'>No</option></select></td></tr>";
   echo "<tr><td>Can call National</td><td><select class='button' name='national' style='width:100%'>";
   echo "<option selected value='yes'>Yes</option> <option value='no'>No</option></select></td></tr>";
   echo "<tr><td>Can call Cellular</td><td><select class='button' name='cellular' style='width:100%'>";
   echo "<option selected value='yes'>Yes</option> <option value='no'>No</option></select></td></tr>";
   echo "<tr><td>Can call Internal</td><td><select class='button' name='internal' style='width:100%'>";
   echo "<option selected value='yes'>Yes</option> <option value='no'>No</option></select></td></tr>";
   echo "<tr><td></td><td><input type='hidden' name='newid' value='yes'</input><input class='button' type='submit' value='Add Pin Code'> </input> </td></tr> </form>";
   echo "</tr>";
   echo "</table>";
}

function add_pincodes_details() {

   $name = $_POST['name'];
   $pin = $_POST['pin'];
   $international = $_POST['international'];
   $national = $_POST['national'];
   $cellular = $_POST['cellular'];
   $internal = $_POST['internal'];

   db_query("INSERT INTO asterisk.pin_codes (name, pin, international, national, cellular, internal) VALUES (?, ?,?, ?, ?, ?);", array($name,$pin,$international,$national,$cellular,$internal));

   pincodes();
}

function edit_pincodes() {

   $id = $_GET['id'];
   $row =  db_query("SELECT name,pin,international,national,cellular,internal FROM pin_codes WHERE id = ?", array($id));

   echo "<table>";
   echo "<th>Setting</th><th>Value</th>";
   echo "<form action='index.php?mod=editpindetails' method='post'>";

   echo "<tr><td>Name</td><td><input type='text' name='name' value='".$row[0]["name"]."' style='width:100%'></td></tr>";
   echo "<tr><td>Pin</td><td><input type='text' name='pin' value='".$row[0]["pin"]."' style='width:100%'></td></tr>";
   echo "<tr><td>Can call International</td><td><select class='button' name='international' style='width:100%'>";
   if ($row[0]["international"] == 'yes'){
      echo "<option selected value='yes'>Yes</option> <option value='no'>No</option></select></td></tr>";
   } else { 
      echo "<option value='yes'>Yes</option> <option selected  value='no'>No</option></select></td></tr>";
   }
   echo "<tr><td>Can call National</td><td><select class='button' name='national' style='width:100%'>";
   if ($row[0]["national"] == 'yes'){
      echo "<option selected value='yes'>Yes</option> <option value='no'>No</option></select></td></tr>";
   } else { 
      echo "<option value='yes'>Yes</option> <option selected  value='no'>No</option></select></td></tr>";
   }
   echo "<tr><td>Can call Cellular</td><td><select class='button' name='cellular' style='width:100%'>";
   if ($row[0]["cellular"] == 'yes'){
      echo "<option selected value='yes'>Yes</option> <option value='no'>No</option></select></td></tr>";
   } else { 
      echo "<option value='yes'>Yes</option> <option selected  value='no'>No</option></select></td></tr>";
   }
   echo "<tr><td>Can call Internal</td><td><select class='button' name='internal' style='width:100%'>";
   if ($row[0]["internal"] == 'yes'){
      echo "<option selected value='yes'>Yes</option> <option value='no'>No</option></select></td></tr>";
   } else { 
      echo "<option value='yes'>Yes</option> <option selected  value='no'>No</option></select></td></tr>";
   }
   echo "<tr><td></td><td><input type='hidden' name='id' value='".$id."'</input><input class='button' type='submit' value='Modify Pin Codes'> </input> </td></tr> </form>";
   echo "</tr>";
   echo "</table>";
}

function edit_pincodes_details() {

   $name = $_POST['name'];
   $international = $_POST['international'];
   $national = $_POST['national'];
   $cellular = $_POST['cellular'];
   $internal = $_POST['internal'];
   $pin = $_POST['pin'];
   $id = $_POST['id'];

   db_query("UPDATE pin_codes SET name=?, pin=?, international=?, national=?, cellular=?, internal=? WHERE id=?", array($name,$pin,$international,$national,$cellular,$internal,$id));

   pincodes();
}

function del_pincodes() {

   $id = $_GET['id'];
   db_query("DELETE FROM pin_codes WHERE id=?", array($id));

   pincodes();
}
?>

