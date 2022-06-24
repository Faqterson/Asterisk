<?php
//include "include/common_func.php";

function user() {
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

   $row = db_query("SELECT name,username,user_id from webusers LIMIT $offset, $rowsperpage", array());

   echo "<table width=100% border='0'>";
   echo "<tr><th align='left'>Name</th><th align='left'>Username</th><th align='left'>Edit</th></tr>";
   foreach ($row as $key => $value)
   {
      echo "<tr>";
      echo "<td align='left'>" . $value['name'] . "</td>";
      echo "<td align='left'>" . $value['username'] . "</td>";
      echo "<td><a style='background: none' href = 'index.php?mod=modify_user&id=".$value['user_id']."'><img src=img/edit-icon.png height='20' width='20'></a>&nbsp&nbsp&nbsp";
      echo "<a style='background: none' href = 'index.php?mod=delete_user&id=".$value['user_id']."'><img class='delete' src=img/delete-icon.png height='20' width='20'></a></td>";
      echo "</tr>";
   }
   echo "</table>";
?>
   <table width="100%">
   <tr>
      <td align="center">
      <?php
      $resultPage = db_query("SELECT name,username,user_id from webusers", array());
      pagination('index.php?mod=users',$rowsperpage,$resultPage,$page);
      ?>
      </td>
   </tr>
   </table>

<?php
   echo "<form action='index.php?mod=add_user' method='post'>";
   echo "<input class='button' type='submit' value='Add User' />";
   echo "</form>";
}

function add_user() {
?>
<form name="edit" id="edit" action="index.php?mod=add_user_details" method="post" enctype="multipart/form-data" class="myForms" >
   <div style="padding-left: 100px; padding-right: 100px">
   <table style="width: 100%">
   <tr><th align='left'>Settings</th><th align='left'>Value</th></tr>
   <tr>
      <td>
         <label class="" for="username">Username</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="username" name="username" value="" required style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="secret">Password</label>
      </td>
      <td colspan="4">
         <input type="password" class="" id="secret" name="secret" value="" required style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="control-label" for="name">Name</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="name" name="name" value="" style="width:100%">
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
         <label class="" for="user_type">Account Type</label>
      </td>
      <td> 
         <select class="button" name="user_type" id="user_type"> 
      	    <option value="1">Admin</option>
            <option value="2">Reports</option>
            <option value="3">Moderator</option>
         </select>
      </td>
   <tr>
      <td colspan="4">
         <input class="button" type="submit" value="Add User" <="" input="">
      </td>
   </tr>
   <tr>
</table>
</div>
</form>
<?php
}

function add_user_details() {
   $username = $_POST['username'];
   $secret = md5($_POST['secret']);
   $name = $_POST['name'];
   $department = $_POST['department'];
   $user_type = $_POST['user_type'];
   
   db_query("INSERT INTO webusers(name,username,password,user_type,department) VALUES (?,?,?,?,?)",array($name,$username,$secret,$user_type,$department));

   user();
}

function modify_user() {
   $ID = $_GET['id'];
   $row = db_query("SELECT username,password,name,user_type,department FROM webusers WHERE user_id = ?", array($ID));
?>
<form name="PbxForm" id="PbxForm" action="index.php?mod=modify_user_details" method="post" enctype="multipart/form-data" class="" >
   <input name="id" id="id" value="<?php echo $ID; ?>" type="hidden">
   <div style="padding-left: 100px; padding-right: 100px">
   <table style="width: 100%">
   <tr><th align='left'>Settings</th><th align='left'>Value</th></tr>
   <tr>
      <td>
         <label class="" for="username">Username</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="username" name="username" value="<?php echo $row[0]['username']; ?>" required style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="secret">Password</label>
      </td>
      <td colspan="4">
         <input type="password" class="" id="secret" name="secret" value="" style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="control-label" for="name">Name</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="name" name="name" value="<?php echo $row[0]['name']; ?>" style="width:100%">
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
         <label class="" for="user_type">Account Type</label>
      </td>
      <td>
         <select class="button" name="user_type" id="user_type">
<?php
            if ($row[0]['user_type'] == "1") {
?>
            <option value="1">Admin</option>
            <option value="2">Reports</option>
            <option value="3">Moderator</option>
<?php
            } elseif ($row[0]['user_type'] == "2") {
?>
            <option value="2">Reports</option>
            <option value="1">Admin</option>
            <option value="3">Moderator</option>
<?php
            } elseif ($row[0]['user_type'] == "3") {
?>
            <option value="3">Moderator</option>
            <option value="1">Admin</option>
            <option value="2">Reports</option>
<?php
            }
?>
         </select>
      </td>
   </tr>
   <tr>
      <td colspan="4">
         <input class="button" type="submit" value="Modify User"</input>
      </td>
   </tr>
</table>
</div>
</form>
<?php
}

function modify_user_details() {
   $id = $_POST['id'];
   $username = $_POST['username'];
   $secret = md5($_POST['secret']);
   $name = $_POST['name'];
   $department = $_POST['department'];
   $user_type = $_POST['user_type'];
      
   if ($_POST['secret'] == "") {
      db_query("UPDATE webusers SET username = ?,name = ?,user_type = ?, department = ? WHERE user_id  = ?",array($username,$name,$user_type,$department,$id));
   } else {
      db_query("UPDATE webusers SET username = ?,password = ?,name = ?,user_type = ?, department = ? WHERE user_id  = ?",array($username,$secret,$name,$user_type,$department,$id));
   }
 
   user();
}

function delete_user() {
   $id = $_GET['id'];

   db_query("DELETE FROM webusers WHERE user_id = ?", array($id));

   user();
}

?>

