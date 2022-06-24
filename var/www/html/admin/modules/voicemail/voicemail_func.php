<?php

function voicemail() {

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

   $row=db_query("SELECT mailbox,fullname,email,deletevoicemail,uniqueid FROM voicemail LIMIT $offset, $rowsperpage", array());

   echo "<table width=100%>";
   echo "<tr><th align='left'>Mailbox</th><th align='left'>Full Name</th><th align='left'>Email</th><th align='left'>Delete On Sent</th><th align='left'>Edit</th></tr>";
   foreach($row as $key => $value)
   {
      echo "<tr>";
      echo "<td align='left'>" . $value['mailbox'] . "</td>";
      echo "<td align='left'>" . $value['fullname'] . "</td>";
      echo "<td align='left'>" . $value['email'] . "</td>";
      echo "<td align='left'>" . $value['deletevoicemail'] . "</td>";
      echo "<td align='left'>";
      echo "<a style='background: none' href='index.php?mod=modify_voicemail&id=" . $value['uniqueid'] . "'><img src=img/edit-icon.png height='20' width='20'></a>&nbsp&nbsp&nbsp";
      echo "<a style='background: none' href='index.php?mod=delete_voicemail&id=" . $value['uniqueid'] . "'><img class='delete' src=img/delete-icon.png height='20' width='20'></a></td>";
      echo "</td>";
      echo "</tr>";
   }
   echo "</table>";
?>
   <table width="100%">
   <tr>
      <td align="center">
      <?php
      $resultPage = db_query("SELECT mailbox,fullname,email,deletevoicemail FROM voicemail", array());
      pagination('index.php?mod=voicemail',$rowsperpage,$resultPage,$page);
      ?>
      </td>
   </tr>
   </table>
<?php
   echo "<form action='index.php?mod=add_voicemail' method='post'>";
   echo "<input class='button' type='submit' value='Add Voicemail' />";
   echo "</form>";

}

function add_voicemail() {
?>
<form name="edit" id="edit" action="index.php?mod=add_voicemail_details" method="post" enctype="multipart/form-data" class="" >
   <div style="padding-left: 100px; padding-right: 100px">
   <table style="width: 100%">
   <th>Setting</th><th colspan="2">Value</th>
   <tr>
      <td>
         <label class="" for="mailbox">Mailbox Number</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="mailbox" name="mailbox" value="" required style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="fullname">Full Name</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="fullname" name="fullname" value="" required style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="password">Password</label>
      </td>
      <td colspan="4">
         <input type="password" class="" id="password" name="password" value="" required style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="control-label" for="email">Email</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="email" name="email" value="" style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="delete">Delete</label>
      </td>
      <td colspan="4">
	 <select name="delete" id="" class="button" style="width: 100%" >
            <option value="no">No</option>
            <option value="yes">Yes</option>
         </select>
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="context">Context</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="context" name="context" value="default" style="width:100%">
      </td>
   </tr>
   <tr>
      <td></td>
      <td colspan="4">
         <input class="button" type="submit" value="Add Voicemail"</input>
      </td>
   </tr>

</table>
</div>
</form>

<?php
}

function add_voicemail_details() {

   $mailbox = $_POST['mailbox'];
   $password = $_POST['password'];
   $fullname = $_POST['fullname'];
   $email = $_POST['email'];
   $delete = $_POST['delete'];
   $context = $_POST['context'];

   db_query("INSERT INTO voicemail (mailbox,password,fullname,email,deletevoicemail,context) VALUES (?,?,?,?,?,?)", array($mailbox,$password,$fullname,$email,$delete,$context));
 
   voicemail();
}

function modify_voicemail() {

   $ID = $_GET['id'];
   $row = db_query("SELECT mailbox,password,fullname,email,deletevoicemail,context FROM voicemail WHERE uniqueid = ?", array($ID));

?>
<form name="edit" id="edit" action="index.php?mod=modify_voicemail_details" method="post" enctype="multipart/form-data" class="" >
   <input name="id" id="id" value="<?php echo $ID; ?>" type="hidden">
   <div style="padding-left: 100px; padding-right: 100px">
   <table style="width: 100%">
   <th>Setting</th><th colspan="2">Value</th>
   <tr>
      <td>
         <label class="" for="mailbox">Mailbox Number</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="mailbox" name="mailbox" value="<?php echo $row[0]['mailbox']; ?>" required style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="fullname">Full Name</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="fullname" name="fullname" value="<?php echo $row[0]['fullname']; ?>" required style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="password">Password</label>
      </td>
      <td colspan="4">
         <input type="password" class="" id="password" name="password" value="<?php echo $row[0]['password']; ?>" required style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="control-label" for="email">Email</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="email" name="email" value="<?php echo $row[0]['email']; ?>" style="width:100%">
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="delete">Delete</label>
      </td>
      <td colspan="4">
	 <select name="delete" id="" class="button" style="width: 100%" >
<?php
            if (isset($row[0]['deletevoicemail'])) {
?>
            <option value="<?php echo $row[0]['deletevoicemail']; ?>" ><?php echo $row[0]['deletevoicemail']; ?></option>
<?php
            } else {
?>
            <option value="no">No</option>            
            <option value="yes">Yes</option>            
<?php
            }
            if ($row[0]['deletevoicemail'] == "yes") {
?>
            <option value="no">No</option>
<?php
            } elseif ($row[0]['deletevoicemail'] == "no") {
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
         <label class="" for="context">Context</label>
      </td>
      <td colspan="4">
         <input type="text" class="" id="context" name="context" value="<?php echo $row[0]['context']; ?>" style="width:100%">
      </td>
   </tr>
   <tr>
      <td></td>
      <td colspan="4">
         <input class="button" type="submit" value="Modify Voicemail"</input>
      </td>
   </tr>

</table>
</div>
</form>

<?php
}

function modify_voicemail_details() {

   $id = $_POST['id'];
   $mailbox = $_POST['mailbox'];
   $password = $_POST['password'];
   $fullname = $_POST['fullname'];
   $email = $_POST['email'];
   $delete = $_POST['delete'];
   $context = $_POST['context'];

   db_query("UPDATE voicemail SET mailbox = ?,password = ?,fullname = ?,email = ?,deletevoicemail = ?,context = ? WHERE uniqueid = ?", array($mailbox,$password,$fullname,$email,$delete,$context,$id)); 
 
   voicemail();
}

function delete_voicemail() {

   $id = $_GET['id'];

   db_query("DELETE FROM voicemail WHERE uniqueid = ?", array($id));

   voicemail();
}
?>

