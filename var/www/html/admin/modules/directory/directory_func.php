<?php

function directory() {

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

   $row=db_query("SELECT uniqueid,name,number from directory LIMIT $offset, $rowsperpage", array());

   echo "<table width=100%>";
   echo "<tr><th align='left'>Name</th><th align='left'>Number</th><th align='left'>Edit</th></tr>";
   foreach($row as $key => $value)
   {
      echo "<tr>";
      echo "<td align='left'>" . $value['name'] . "</td>";
      echo "<td align='left'>" . $value['number'] . "</td>";
      echo "<td align='left'>";
      echo "<a style='background: none' href = 'index.php?mod=modify_directory&id=" . $value['uniqueid'] . "'><img src=img/edit-icon.png height='20' width='20'></a>&nbsp&nbsp&nbsp";
      echo "<a style='background: none' href = 'index.php?mod=delete_directory&id=" . $value['uniqueid'] . "'><img class='delete' src=img/delete-icon.png height='20' width='20'></a></td>";
      echo "</td>";
      echo "</tr>";
   }
   echo "</table>";
?>
   <table width="100%">
   <tr>
      <td align="center">
      <?php
      $resultPage = db_query("SELECT uniqueid,name,number from directory", array());
      pagination('index.php?mod=directory',$rowsperpage,$resultPage,$page);
      ?>
      </td>
   </tr>
   </table>
<?php
   echo "<form action='index.php?mod=add_directory' method='post'>";
   echo "<input class='button' type='submit' value='Add Number' />";
   echo "</form>";

}

function add_directory() {
?>
<form name="edit" id="edit" action="index.php?mod=add_directory_details" method="post" enctype="multipart/form-data" class="" >
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

function add_directory_details() {

   $name = $_POST['name'];
   $number = $_POST['number'];

   db_query("INSERT INTO directory (name,number) VALUES (?,?)", array($name,$number));

   // Connecting to LDAP
   $ldapconn = ldap_connect();

   $ldaprdn  = 'cn=admin,o=dbs';
   $ldappass = 'VGJd#$xx';
   
   ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);

   // binding to ldap server
   $ldapbind = ldap_bind($ldapconn, $ldaprdn, $ldappass);

   // verify binding
   if ($ldapbind) {
      $main["objectClass"][0] = "top";
      $main["objectClass"][1] = "organization";
      $main["o"] = "dbs";
      $m = ldap_add($ldapconn,"o=dbs",$main);

      $sub["ou"] = "users";
      $sub["objectClass"][0] = "top";
      $sub["objectClass"][1] = "organizationalUnit";
      $sub["description"] = "Phonebook Entries";
      $s = ldap_add($ldapconn,"ou=users,o=dbs",$sub);	

      $info["ou"] = "users";
      $info["o"] = "dbs";
      $info["cn"] = $name;
      $info["sn"] = $name;
      $info["objectClass"][0] = "person";
      $info["objectClass"][1] = "organizationalPerson";
      $info["objectClass"][2] = "inetOrgPerson";
      $info["objectClass"][3] = "top";
      $info["mobile"] = $number;
      $dn = "cn=".$name.",ou=users,o=dbs";
      $r = ldap_add($ldapconn,$dn,$info);
   }

   ldap_close($ldapconn);

   directory();
}

function modify_directory() {

   $id = $_GET['id'];
   $row = db_query("SELECT uniqueid,name,number from directory WHERE uniqueid = ?", array($id));


?>
<form name="edit" id="edit" action="index.php?mod=modify_directory_details" method="post" enctype="multipart/form-data" class="" >
   <input name="id" id="id" value="<?php echo $row[0]['uniqueid']; ?>" type="hidden">
   <div style="padding-left: 100px; padding-right: 100px">
   <table style="width: 100%">
   <th>Setting</th><th colspan="2">Value</th>
   <tr>
      <td>
         <label class="" for="name">Name</label>
      </td>
      <td colspan="4">
         <input type="hidden" class="" id="oldname" name="oldname" value="<?php echo $row[0]['name']; ?>">
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
      <td></td>
      <td colspan="4">
         <input class="button" type="submit" value="Modify Number"</input>
      </td>
   </tr>
</table>
</div>
</form>

<?php
   mysql_close();
}

function modify_directory_details() {

   $id = $_POST['id'];
   $oldname = $_POST['oldname'];
   $name = $_POST['name'];
   $number = $_POST['number'];

   db_query("UPDATE directory SET name = '$name',number = '$number' WHERE uniqueid = ?", array($id)); 

   // Connecting to LDAP
   $ldapconn = ldap_connect();

   $ldaprdn  = 'cn=admin,o=dbs';
   $ldappass = 'VGJd#$xx';

   // binding to ldap server
   $ldapbind = ldap_bind($ldapconn, $ldaprdn, $ldappass);

   if ($oldname == $name) {
      $info["ou"] = "users";
      $info["o"] = "dbs";
      $info["cn"] = $name;
      $info["sn"] = $name;
      $info["objectClass"][0] = "person";
      $info["objectClass"][1] = "organizationalPerson";
      $info["objectClass"][2] = "inetOrgPerson";
      $info["objectClass"][3] = "top";
      $info["mobile"] = $number;
      $dn = "cn=".$name.",ou=users,o=dbs";
      $r = ldap_modify($ldapconn,$dn,$info);
   } else {
      $dn = "cn=".$oldname.",ou=users,o=dbs";
      $r = ldap_delete($ldapconn,$dn);

      $info["ou"] = "users";
      $info["o"] = "dbs";
      $info["cn"] = $name;
      $info["sn"] = $name;
      $info["objectClass"][0] = "person";
      $info["objectClass"][1] = "organizationalPerson";
      $info["objectClass"][2] = "inetOrgPerson";
      $info["objectClass"][3] = "top";
      $info["mobile"] = $number;
      $dn = "cn=".$name.",ou=users,o=dbs";
      $r = ldap_add($ldapconn,$dn,$info);
   }

   ldap_close($ldapconn);

   directory();
}

function delete_directory() {

   $id = $_GET['id'];

   $row = db_query("SELECT name FROM directory WHERE uniqueid=?", array($id)); 
   $name = $row[0]['name'];

   $ldapconn=ldap_connect();

   $ldaprdn  = 'cn=admin,o=dbs';
   $ldappass = 'VGJd#$xx';

   ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);

   // binding to ldap server
   $ldapbind = ldap_bind($ldapconn, $ldaprdn, $ldappass);

   $dn = "cn=".$name.",ou=users,o=dbs";
   $r = ldap_delete($ldapconn,$dn);

   db_query("DELETE FROM directory WHERE uniqueid = ?", array($id));

   ldap_close($ldapconn);

   directory();
}
?>

