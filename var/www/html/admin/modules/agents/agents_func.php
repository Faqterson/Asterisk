<?php

function agents() {

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

   $row=db_query("SELECT uniqueid,agent,description,password from agents LIMIT $offset, $rowsperpage", array());

   echo "<table width=100%>";
   echo "<tr><th align='left'>Agent</th><th align='left'>Description</th><th align='left'>password</th><th align='left'>Edit</th></tr>";
   foreach($row as $key => $value)
   {
      echo "<tr>";
      echo "<td align='left'>" . $value['agent'] . "</td>";
      echo "<td align='left'>" . $value['description'] . "</td>";
      echo "<td align='left'>" . $value['password'] . "</td>";
      echo "<td align='left'>";
      echo "<a style='background: none' href = 'index.php?mod=modify_agents&id=" . $value['uniqueid'] . "'><img src=img/edit-icon.png height='20' width='20'></a>&nbsp&nbsp&nbsp";
      echo "<a style='background: none' href = 'index.php?mod=delete_agents&id=" . $value['uniqueid'] . "&agent=" . $value['agent'] . "'><img class='delete' src=img/delete-icon.png height='20' width='20'></a></td>";
      echo "</td>";
      echo "</tr>";
   }
   echo "</table>";
?>
   <table width="100%">
   <tr>
      <td align="center">
      <?php
      $resultPage = db_query("SELECT uniqueid,agent,password from agents", array());
      pagination('index.php?mod=agents',$rowsperpage,$resultPage,$page);
      ?>
      </td>
   </tr>
   </table>
<?php
   echo "<form action='index.php?mod=add_agents' method='post'>";
   echo "<input class='button' type='submit' value='Add Agent' />";
   echo "</form>";
}

function add_agents() {
?>
<form name="edit" id="edit" action="index.php?mod=add_agents_details" method="post" enctype="multipart/form-data" class="" >
   <div style="padding-left: 100px; padding-right: 100px">
   <table style="width: 100%">
   <th>Setting</th><th colspan="2">Value</th>
   <tr>
      <td>
         <label class="" for="agent">Agent</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="agent" name="agent" value="" required style="width:100%">
      </td>
   </tr>
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
         <label class="" for="password">Password</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="password" name="password" value="" required style="width:100%">
      </td>
   </tr>
   <tr>
      <td></td>
      <td colspan="4">
         <input class="button" type="submit" value="Add Agent"</input>
      </td>
   </tr>

</table>
</div>
</form>

<?php
}

function add_agents_details() {

   $agent = $_POST['agent'];
   $description = $_POST['description'];
   $password = $_POST['password'];

   db_query("INSERT INTO agents (agent,description,password) VALUES (?,?,?)", array($agent,$description,$password));
   db_query("INSERT INTO extensions (context,exten,priority,app,appdata) VALUES (?,?,?,?,?)", array('extensions',$agent,'1','Goto','agents,${EXTEN},1'));

   agents();
}

function modify_agents() {

   $id = $_GET['id'];
   $row = db_query("SELECT uniqueid,agent,description,password from agents WHERE uniqueid = ?", array($id));

?>
<form name="edit" id="edit" action="index.php?mod=modify_agents_details" method="post" enctype="multipart/form-data" class="" >
   <input name="id" id="id" value="<?php echo $row[0]['uniqueid']; ?>" type="hidden">
   <div style="padding-left: 100px; padding-right: 100px">
   <table style="width: 100%">
   <th>Setting</th><th colspan="2">Value</th>
   <tr>
      <td>
         <label class="" for="agent">Agent</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="agent" name="agent" value="<?php echo $row[0]['agent']; ?>" required style="width:100%">
         <input class="" id="origid" name="origid" value="<?php echo $row[0]['agent']; ?>" type="hidden">
      </td>
   </tr>
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
         <label class="" for="password">Password</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="password" name="password" value="<?php echo $row[0]['password']; ?>" required style="width:100%">
      </td>
   </tr>
   <tr>
      <td></td>
      <td colspan="4">
         <input class="button" type="submit" value="Modify Agent"</input>
      </td>
   </tr>
</table>
</div>
</form>

<?php
}

function modify_agents_details() {

   $id = $_POST['id'];
   $origid = $_POST['origid'];
   $agent = $_POST['agent'];
   $description = $_POST['description'];
   $password = $_POST['password'];

   db_query("UPDATE agents SET agent = ?,description = ?, password = ? WHERE uniqueid = ?", array($agent,$description,$password,$id)); 
   db_query("UPDATE extensions SET exten = ? WHERE exten = ?", array($agent,$origid));

   agents();
}

function delete_agents() {

   $id = $_GET['id'];
   $agent = $_GET['agent'];

   db_query("DELETE FROM agents WHERE uniqueid = ?", array($id));
   db_query("DELETE FROM extensions WHERE exten = ?", array($agent));

   agents();
}
?>

