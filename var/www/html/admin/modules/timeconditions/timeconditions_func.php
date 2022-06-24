<?php

function timeconditions() {
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

   $row=db_query("SELECT uniqueid,description,time,wday,mday,month,context,department from timeconditions order by department LIMIT $offset, $rowsperpage", array());

   echo "<table width=100%>";
   echo "<tr>";
   echo "<th align='left'>Description</th>";
   echo "<th align='left'>Times</th>";
   echo "<th align='left'>Week Day</th>";
   echo "<th align='left'>Month Day</th>";
   echo "<th align='left'>Month</th>";
   echo "<th align='left'>Context</th>";
   echo "<th align='left'>Department</th>";
   echo "<th align='left'>Modify</th>";
   echo "</tr>";
   foreach($row as $key => $value)
   {
      echo "<tr>";
      echo "<td align='left'>" . $value['description'] . "</td>";
      echo "<td align='left'>" . $value['time'] . "</td>";
      echo "<td align='left'>" . $value['wday'] . "</td>";
      echo "<td align='left'>" . $value['mday'] . "</td>";
      echo "<td align='left'>" . $value['month'] . "</td>";
      echo "<td align='left'>" . $value['context'] . "</td>";
      echo "<td align='left'>" . $value['department'] . "</td>";
      echo "<td align='left'>";
      echo "<a style='background: none' href='index.php?mod=modify_timeconditions&id=" . $value['uniqueid'] . "' title=''><img src=img/edit-icon.png height='20' width='20'></a>&nbsp";
      echo "<a style='background: none' href='index.php?mod=delete_timeconditions&id=" . $value['uniqueid'] . "' title=''><img class='delete' src=img/delete-icon.png height='20' width='20'></a>";
      echo "</td>";
      echo "</tr>";
   }
   echo "</table>";
?>
   <table width="100%" border="0" cellpadding="2" cellspacing="2">
   <tr>
      <td align="center">
      <?php
      $resultPage = db_query("SELECT uniqueid,description,time,wday,mday,month,context,department from timeconditions order by department", array());
      pagination('index.php?mod=timeconditions',$rowsperpage,$resultPage,$page);                
      ?>
      </td>
   </tr>
   </table>

<?php
   echo "<form action='index.php?mod=add_timeconditions' method='post'>";
   echo "<input class='button' type='submit' value='Add Timeconditions' />";
   echo "</form>";
}

function add_timeconditions() {
?>
<form autocomplete="off" name="edit" id="edit" action="index.php?mod=add_time" method="post" onsubmit="" class="" >
<!--Description-->
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
<!--END Description-->
<!--Time-->
   <tr>
      <td style="width: 25%">
         <label class="" for="timewraper">Time(s)</label>
      </td>
      <td>
         <label for="times[0]hours" class="">Time to Start</label>
      </td>
      <td>
         <select class="button" name="hour_start" id="times[0]hours" class="" style="width:100%">
            <option value="*">-</option><option value="00" > 00<option value="01" > 01<option value="02" > 02<option value="03" > 03<option value="04" > 04<option value="05" > 05<option value="06" > 06<option value="07" > 07<option value="08" > 08<option value="09" > 09<option value="10" > 10<option value="11" > 11<option value="12" > 12<option value="13" > 13<option value="14" > 14<option value="15" > 15<option value="16" > 16<option value="17" > 17<option value="18" > 18<option value="19" > 19<option value="20" > 20<option value="21" > 21<option value="22" > 22<option value="23" > 23
         </select>
      </td>
      <td>
         <select class="button" name="minute_start" id="times[0]minutes" class="" style="width:100%">
            <option value="*">-</option><option value="00" > 00<option value="01" > 01<option value="02" > 02<option value="03" > 03<option value="04" > 04<option value="05" > 05<option value="06" > 06<option value="07" > 07<option value="08" > 08<option value="09" > 09<option value="10" > 10<option value="11" > 11<option value="12" > 12<option value="13" > 13<option value="14" > 14<option value="15" > 15<option value="16" > 16<option value="17" > 17<option value="18" > 18<option value="19" > 19<option value="20" > 20<option value="21" > 21<option value="22" > 22<option value="23" > 23<option value="24" > 24<option value="25" > 25<option value="26" > 26<option value="27" > 27<option value="28" > 28<option value="29" > 29<option value="30" > 30<option value="31" > 31<option value="32" > 32<option value="33" > 33<option value="34" > 34<option value="35" > 35<option value="36" > 36<option value="37" > 37<option value="38" > 38<option value="39" > 39<option value="40" > 40<option value="41" > 41<option value="42" > 42<option value="43" > 43<option value="44" > 44<option value="45" > 45<option value="46" > 46<option value="47" > 47<option value="48" > 48<option value="49" > 49<option value="50" > 50<option value="51" > 51<option value="52" > 52<option value="53" > 53<option value="54" > 54<option value="55" > 55<option value="56" > 56<option value="57" > 57<option value="58" > 58<option value="59" > 59
         </select>
      </td>
      <td style="width: 25%"></td>
   </tr>
   <tr>
      <td></td>
      <td>
         <label for="times[0]houre" class="">Time to finish</label>
      </td>
      <td>
         <select class="button" name="hour_finish" id="times[0]houre" class="" style="width:100%">
            <option value="*">-</option><option value="00" > 00<option value="01" > 01<option value="02" > 02<option value="03" > 03<option value="04" > 04<option value="05" > 05<option value="06" > 06<option value="07" > 07<option value="08" > 08<option value="09" > 09<option value="10" > 10<option value="11" > 11<option value="12" > 12<option value="13" > 13<option value="14" > 14<option value="15" > 15<option value="16" > 16<option value="17" > 17<option value="18" > 18<option value="19" > 19<option value="20" > 20<option value="21" > 21<option value="22" > 22<option value="23" > 23
         </select>
      </td>
      <td>
         <select class="button" name="minute_finish" id="times[0]minutee" class="" style="width:100%">
            <option value="*">-</option><option value="00" > 00<option value="01" > 01<option value="2" > 02<option value="03" > 03<option value="04" > 04<option value="05" > 05<option value="06" > 06<option value="07" > 07<option value="08" > 08<option value="09" > 09<option value="10" > 10<option value="11" > 11<option value="12" > 12<option value="13" > 13<option value="14" > 14<option value="15" > 15<option value="16" > 16<option value="17" > 17<option value="18" > 18<option value="19" > 19<option value="20" > 20<option value="21" > 21<option value="22" > 22<option value="23" > 23<option value="24" > 24<option value="25" > 25<option value="26" > 26<option value="27" > 27<option value="28" > 28<option value="29" > 29<option value="30" > 30<option value="31" > 31<option value="32" > 32<option value="33" > 33<option value="34" > 34<option value="35" > 35<option value="36" > 36<option value="37" > 37<option value="38" > 38<option value="39" > 39<option value="40" > 40<option value="41" > 41<option value="42" > 42<option value="43" > 43<option value="44" > 44<option value="45" > 45<option value="46" > 46<option value="47" > 47<option value="48" > 48<option value="49" > 49<option value="50" > 50<option value="51" > 51<option value="52" > 52<option value="53" > 53<option value="54" > 54<option value="55" > 55<option value="56" > 56<option value="57" > 57<option value="58" > 58<option value="59" > 59
         </select>
      </td>
      <td style="width: 25%"></td>
   </tr>
   <tr>
      <td></td>
      <td>
         <label for="times[0]wds" class="">Week Day Start</label>
      </td>
      <td colspan="2">
         <select class="button" name="wday_start" id="times[0]wds" class="" style="width:100%">
            <option value="*">-</option><option value="sun" >Sunday</option><option value="mon" >Monday</option><option value="tue" >Tuesday</option><option value="wed" >Wednesday</option><option value="thu" >Thursday</option><option value="fri" >Friday</option><option value="sat" >Saturday</option>
         </select>
      </td>
      <td style="width: 25%"></td>
   </tr>
   <tr>
      <td></td>
      <td>
         <label for="times[0]wde" class="">Week Day finish</label>
      </td>
      <td colspan="2">
         <select class="button" name="wday_finish" id="times[0]wde" class="" style="width:100%">
            <option value="*">-</option><option value="sun" >Sunday</option><option value="mon" >Monday</option><option value="tue" >Tuesday</option><option value="wed" >Wednesday</option><option value="thu" >Thursday</option><option value="fri" >Friday</option><option value="sat" >Saturday</option>
         </select>
      </td>
      <td style="width: 25%"></td>
   </tr>
   <tr>
      <td></td>
      <td>
         <label for="times[0]mds" class="">Month Day start</label>
      </td>
      <td colspan="2">
         <select class="button" name="mday_start" id="times[0]mds" class="" style="width: 100%">
            <option value="*">-</option><option value="1" > 01<option value="2" > 02<option value="3" > 03<option value="4" > 04<option value="5" > 05<option value="6" > 06<option value="7" > 07<option value="8" > 08<option value="9" > 09<option value="10" > 10<option value="11" > 11<option value="12" > 12<option value="13" > 13<option value="14" > 14<option value="15" > 15<option value="16" > 16<option value="17" > 17<option value="18" > 18<option value="19" > 19<option value="20" > 20<option value="21" > 21<option value="22" > 22<option value="23" > 23<option value="24" > 24<option value="25" > 25<option value="26" > 26<option value="27" > 27<option value="28" > 28<option value="29" > 29<option value="30" > 30<option value="31" > 31
         </select>
      </td>
      <td style="width: 25%"></td>
   </tr>
   <tr>
      <td></td>
      <td>
         <label for="times[0]mdf" class="">Month Day finish</label>
      </td>
      <td colspan="2">
         <select class="button" name="mday_finish" id="times[0]mdf" class="" style="width: 100%">
            <option value="*">-</option><option value="1" > 01<option value="2" > 02<option value="3" > 03<option value="4" > 04<option value="5" > 05<option value="6" > 06<option value="7" > 07<option value="8" > 08<option value="9" > 09<option value="10" > 10<option value="11" > 11<option value="12" > 12<option value="13" > 13<option value="14" > 14<option value="15" > 15<option value="16" > 16<option value="17" > 17<option value="18" > 18<option value="19" > 19<option value="20" > 20<option value="21" > 21<option value="22" > 22<option value="23" > 23<option value="24" > 24<option value="25" > 25<option value="26" > 26<option value="27" > 27<option value="28" > 28<option value="29" > 29<option value="30" > 30<option value="31" > 31
         </select>
      </td>
      <td style="width: 25%"></td>
   </tr>
   <tr>
      <td></td>
      <td>
         <label for="times[0]mons" class="">Month</label>
      </td>
      <td colspan="2">      
         <select class="button" name="month" id="times[0]mons" class="" style="width: 100%">
            <option value="*">-</option><option value="jan" >January</option><option value="feb" >February</option><option value="mar" >March</option><option value="apr" >April</option><option value="may" >May</option><option value="jun" >June</option><option value="jul" >July</option><option value="aug" >August</option><option value="sep" >September</option><option value="oct" >October</option><option value="nov" >November</option><option value="dec" >December</option>
         </select>
      </td>
      <td style="width: 25%"></td>
   </tr>
<!--   <tr>
      <td></td>
      <td>
         <label for="times[0]monf" class="">Month finish</label>
      </td>
      <td colspan="2">      
         <select class="button" name="month_finish" id="times[0]monf" class="" style="width: 100%">
            <option value="*">-</option><option value="jan" >January</option><option value="feb" >February</option><option value="mar" >March</option><option value="apr" >April</option><option value="may" >May</option><option value="jun" >June</option><option value="jul" >July</option><option value="aug" >August</option><option value="sep" >September</option><option value="oct" >October</option><option value="nov" >November</option><option value="dec" >December</option>
         </select>
      </td>
   </tr>
-->   <tr>
      <td>
         <label class="control-label" for="goto0">Destination Context</label>
      </td>
      <td colspan="4">
	 <select class="button" data-last="" name="context" id="goto0" class=""  tabindex="2" data-id="0" style="width: 100%" required>
            <option value="" >== choose one ==</option>
            <option value="Officehours">Officehours</option>
            <option value="Afterhours">Afterhours</option>
            <option value="Holiday">Holiday</option>
         </select>
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="department">Department</label>
      </td>
      <td colspan="4">
         <select class="button" data-last="" name="department" id="department" class="" style="width: 100%">
            <option value="" >== choose one ==</option>
<?php
            $row=db_query("SELECT DISTINCT(department) from departments", array());
            foreach($row as $key => $value)
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
         <input class="button" type="submit" value="Add Time"</input>
      </td>
   </tr>

</table>
</div>
</form>

<?php
}

function add_time() {
   $description = $_POST['description'];
   $hour_start = $_POST['hour_start'];
   $minute_start = $_POST['minute_start'];
   $hour_finish = $_POST['hour_finish'];
   $minute_finish = $_POST['minute_finish']; 
   $wday_start = $_POST['wday_start'];
   $wday_finish = $_POST['wday_finish'];
   $mday_start = $_POST['mday_start'];
   $mday_finish = $_POST['mday_finish'];
   $month = $_POST['month'];
   $context = $_POST['context'];
   $department = $_POST['department'];

   if ($hour_start == "*" | $minute_start == "*" | $hour_finish == "*"  | $minute_finish == "*") {
      $times = "*";
   } else {
      $times = "$hour_start:$minute_start-$hour_finish:$minute_finish";
   }

   if ($wday_start == "*" | $wday_finish == "*") {
      $wday = "*";
   } elseif ($wday_start == $wday_finish) {
      $wday = "$wday_start";
   } else {
      $wday = "$wday_start-$wday_finish";
   }

   if ($mday_start == "*" | $mday_finish == "*") {
      $mday = "*";
   } elseif ($mday_start == $mday_finish) {
      $mday = $mday_start;
   } else {
      $mday = "$mday_start-$mday_finish";
   }

   db_query("INSERT INTO timeconditions (description,time,wday,mday,month,context,department) VALUES (?,?,?,?,?,?,?)", array($description,$times,$wday,$mday,$month,$context,$department));

   timeconditions();
}

function modify_timeconditions() {
   $ID = $_GET['id'];
   $row=db_query("SELECT description,time,wday,mday,month,context,department from timeconditions WHERE uniqueid = ?",array($ID));

?>
<form autocomplete="off" name="edit" id="edit" action="index.php?mod=modify_time_details" method="post" onsubmit="" class="" >
   <input name="id" id="id" value="<?php echo $ID; ?>" type="hidden">
<!--Description-->
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
<!--END Description-->
<!--Time-->
<?php
   if ($row[0]['time'] != "*") {
      $splitstartend = explode("-", $row[0]['time']);

      $splitstarthourmin = explode(":", $splitstartend[0]);
      $splitendhourmin = explode(":", $splitstartend[1]);
   }

   if ($row[0]['wday'] != "*") {
      $splitweekday = explode("-", $row[0]['wday']);
   }

   if ($row[0]['mday'] != "*") {
      $splitmonthday = explode("-", $row[0]['mday']);
   }
?>
   <tr>
      <td style="width: 25%">
         <label class="" for="timewraper">Time(s)</label>
      </td>
      <td>
         <label for="times[0]hours" class="">Time to Start</label>
      </td>
      <td>
         <select class="button" name="hour_start" id="times[0]hours" class="" style="width:100%">
<?php
         if (isset($splitstarthourmin[0])) {
?>
            <option value="<?php echo $splitstarthourmin[0]; ?>"><?php echo $splitstarthourmin[0]; ?></option>
<?php
         }
?>
            <option value="*">-</option><option value="00" > 00<option value="01" > 01<option value="02" > 02<option value="03" > 03<option value="04" > 04<option value="05" > 05<option value="06" > 06<option value="07" > 07<option value="08" > 08<option value="09" > 09<option value="10" > 10<option value="11" > 11<option value="12" > 12<option value="13" > 13<option value="14" > 14<option value="15" > 15<option value="16" > 16<option value="17" > 17<option value="18" > 18<option value="19" > 19<option value="20" > 20<option value="21" > 21<option value="22" > 22<option value="23" > 23
         </select>
      </td>
      <td>
         <select class="button" name="minute_start" id="times[0]minutes" class="" style="width:100%">
<?php
         if (isset($splitstarthourmin[1])) {
?>
            <option value="<?php echo $splitstarthourmin[1]; ?>"><?php echo $splitstarthourmin[1]; ?></option>
<?php
         } 
?>
            <option value="*">-</option><option value="00" > 00<option value="01" > 01<option value="02" > 02<option value="03" > 03<option value="04" > 04<option value="05" > 05<option value="06" > 06<option value="07" > 07<option value="08" > 08<option value="09" > 09<option value="10" > 10<option value="11" > 11<option value="12" > 12<option value="13" > 13<option value="14" > 14<option value="15" > 15<option value="16" > 16<option value="17" > 17<option value="18" > 18<option value="19" > 19<option value="20" > 20<option value="21" > 21<option value="22" > 22<option value="23" > 23<option value="24" > 24<option value="25" > 25<option value="26" > 26<option value="27" > 27<option value="28" > 28<option value="29" > 29<option value="30" > 30<option value="31" > 31<option value="32" > 32<option value="33" > 33<option value="34" > 34<option value="35" > 35<option value="36" > 36<option value="37" > 37<option value="38" > 38<option value="39" > 39<option value="40" > 40<option value="41" > 41<option value="42" > 42<option value="43" > 43<option value="44" > 44<option value="45" > 45<option value="46" > 46<option value="47" > 47<option value="48" > 48<option value="49" > 49<option value="50" > 50<option value="51" > 51<option value="52" > 52<option value="53" > 53<option value="54" > 54<option value="55" > 55<option value="56" > 56<option value="57" > 57<option value="58" > 58<option value="59" > 59
         </select>
      </td>
      <td style="width: 25%"></td>
   </tr>
   <tr>
      <td></td>
      <td>
         <label for="times[0]houre" class="">Time to finish</label>
      </td>
      <td>
         <select class="button" name="hour_finish" id="times[0]houre" class="" style="width:100%">
<?php
         if (isset($splitendhourmin[0])) {
?>
            <option value="<?php echo $splitendhourmin[0]; ?>"><?php echo $splitendhourmin[0]; ?></option>
<?php
         }
?>
            <option value="*">-</option><option value="00" > 00<option value="01" > 01<option value="02" > 02<option value="03" > 03<option value="04" > 04<option value="05" > 05<option value="06" > 06<option value="07" > 07<option value="08" > 08<option value="09" > 09<option value="10" > 10<option value="11" > 11<option value="12" > 12<option value="13" > 13<option value="14" > 14<option value="15" > 15<option value="16" > 16<option value="17" > 17<option value="18" > 18<option value="19" > 19<option value="20" > 20<option value="21" > 21<option value="22" > 22<option value="23" > 23
         </select>
      </td>
      <td>
         <select class="button" name="minute_finish" id="times[0]minutee" class="" style="width:100%">
<?php
         if (isset($splitendhourmin[0])) {
?>
            <option value="<?php echo $splitendhourmin[1]; ?>"><?php echo $splitendhourmin[1]; ?></option>
<?php
         }
?>
            <option value="*">-</option><option value="00" > 00<option value="01" > 01<option value="2" > 02<option value="03" > 03<option value="04" > 04<option value="05" > 05<option value="06" > 06<option value="07" > 07<option value="08" > 08<option value="09" > 09<option value="10" > 10<option value="11" > 11<option value="12" > 12<option value="13" > 13<option value="14" > 14<option value="15" > 15<option value="16" > 16<option value="17" > 17<option value="18" > 18<option value="19" > 19<option value="20" > 20<option value="21" > 21<option value="22" > 22<option value="23" > 23<option value="24" > 24<option value="25" > 25<option value="26" > 26<option value="27" > 27<option value="28" > 28<option value="29" > 29<option value="30" > 30<option value="31" > 31<option value="32" > 32<option value="33" > 33<option value="34" > 34<option value="35" > 35<option value="36" > 36<option value="37" > 37<option value="38" > 38<option value="39" > 39<option value="40" > 40<option value="41" > 41<option value="42" > 42<option value="43" > 43<option value="44" > 44<option value="45" > 45<option value="46" > 46<option value="47" > 47<option value="48" > 48<option value="49" > 49<option value="50" > 50<option value="51" > 51<option value="52" > 52<option value="53" > 53<option value="54" > 54<option value="55" > 55<option value="56" > 56<option value="57" > 57<option value="58" > 58<option value="59" > 59
         </select>
      </td>
      <td style="width: 25%"></td>
   </tr>
   <tr>
      <td></td>
      <td>
         <label for="times[0]wds" class="">Week Day Start</label>
      </td>
      <td colspan="2">
         <select class="button" name="wday_start" id="times[0]wds" class="" style="width:100%">
<?php
         if (isset($splitweekday[0])) {
?>
            <option value="<?php echo $splitweekday[0]; ?>"><?php echo $splitweekday[0]; ?></option>
<?php
         }
?>
            <option value="*">-</option><option value="sun" >Sunday</option><option value="mon" >Monday</option><option value="tue" >Tuesday</option><option value="wed" >Wednesday</option><option value="thu" >Thursday</option><option value="fri" >Friday</option><option value="sat" >Saturday</option>
         </select>
      </td>
      <td style="width: 25%"></td>
   </tr>
   <tr>
      <td></td>
      <td>
         <label for="times[0]wde" class="">Week Day finish</label>
      </td>
      <td colspan="2">
         <select class="button" name="wday_finish" id="times[0]wde" class="" style="width:100%">
<?php
         if (isset($splitweekday[1])) {
?>
            <option value="<?php echo $splitweekday[1]; ?>"><?php echo $splitweekday[1]; ?></option>
<?php
         } elseif (isset($splitweekday[0])) {
?>
            <option value="<?php echo $splitweekday[0]; ?>"><?php echo $splitweekday[0]; ?></option>
<?php
         }
?>
            <option value="*">-</option><option value="sun" >Sunday</option><option value="mon" >Monday</option><option value="tue" >Tuesday</option><option value="wed" >Wednesday</option><option value="thu" >Thursday</option><option value="fri" >Friday</option><option value="sat" >Saturday</option>
         </select>
      </td>
      <td style="width: 25%"></td>
   </tr>
   <tr>
      <td></td>
      <td>
         <label for="times[0]mds" class="">Month Day start</label>
      </td>
      <td colspan="2">
         <select class="button" name="mday_start" id="times[0]mds" class="" style="width: 100%">
<?php
         if (isset($splitmonthday[0])) {
?>
            <option value="<?php echo $splitmonthday[0]; ?>"><?php echo $splitmonthday[0]; ?></option>
<?php
         }
?>
            <option value="*">-</option><option value="1" > 01<option value="2" > 02<option value="3" > 03<option value="4" > 04<option value="5" > 05<option value="6" > 06<option value="7" > 07<option value="8" > 08<option value="9" > 09<option value="10" > 10<option value="11" > 11<option value="12" > 12<option value="13" > 13<option value="14" > 14<option value="15" > 15<option value="16" > 16<option value="17" > 17<option value="18" > 18<option value="19" > 19<option value="20" > 20<option value="21" > 21<option value="22" > 22<option value="23" > 23<option value="24" > 24<option value="25" > 25<option value="26" > 26<option value="27" > 27<option value="28" > 28<option value="29" > 29<option value="30" > 30<option value="31" > 31
         </select>
      </td>
      <td style="width: 25%"></td>
   </tr>
   <tr>
      <td></td>
      <td>
         <label for="times[0]mdf" class="">Month Day finish</label>
      </td>
      <td colspan="2">
         <select class="button" name="mday_finish" id="times[0]mdf" class="" style="width: 100%">
<?php
         if (isset($splitmonthday[1])) {
?>
            <option value="<?php echo $splitmonthday[1]; ?>"><?php echo $splitmonthday[1]; ?></option>
<?php
         } elseif (isset($splitmonthday[0])) {
?>
            <option value="<?php echo $splitmonthday[0]; ?>"><?php echo $splitmonthday[0]; ?></option>
<?php
         }
?>
            <option value="*">-</option><option value="1" > 01<option value="2" > 02<option value="3" > 03<option value="4" > 04<option value="5" > 05<option value="6" > 06<option value="7" > 07<option value="8" > 08<option value="9" > 09<option value="10" > 10<option value="11" > 11<option value="12" > 12<option value="13" > 13<option value="14" > 14<option value="15" > 15<option value="16" > 16<option value="17" > 17<option value="18" > 18<option value="19" > 19<option value="20" > 20<option value="21" > 21<option value="22" > 22<option value="23" > 23<option value="24" > 24<option value="25" > 25<option value="26" > 26<option value="27" > 27<option value="28" > 28<option value="29" > 29<option value="30" > 30<option value="31" > 31
         </select>
      </td>
      <td style="width: 25%"></td>
   </tr>
   <tr>
      <td></td>
      <td>
         <label for="times[0]mons" class="">Month</label>
      </td>
      <td colspan="2">      
         <select class="button" name="month" id="times[0]mons" class="" style="width: 100%">
<?php
         if ($row[0]['month'] != "*") {
?>
            <option value="<?php echo $row[0]['month']; ?>"><?php echo $row[0]['month']; ?></option>
<?php
         }
?>
            <option value="*">-</option><option value="jan" >January</option><option value="feb" >February</option><option value="mar" >March</option><option value="apr" >April</option><option value="may" >May</option><option value="jun" >June</option><option value="jul" >July</option><option value="aug" >August</option><option value="sep" >September</option><option value="oct" >October</option><option value="nov" >November</option><option value="dec" >December</option>
         </select>
      </td>
      <td style="width: 25%"></td>
   </tr>
   <tr>
      <td>
         <label class="control-label" for="goto0">Destination Context</label>
      </td>
      <td colspan="4">
	 <select class="button" data-last="" name="context" id="goto0" class=""  tabindex="2" data-id="0" style="width: 100%" required>
            <option value="<?php echo $row[0]['context']; ?>" ><?php echo $row[0]['context']; ?></option>
<?php
            if ($row[0]['context'] == "Officehours") {
?>
            <option value="Afterhours">Afterhours</option>
            <option value="Holiday">Holiday</option>
<?php
            } elseif ($row[0]['context'] == "Afterhours") {
?>
            <option value="Officehours">Officehours</option>
            <option value="Holiday">Holiday</option>
<?php
            } elseif ($row[0]['context'] == "Holiday") {
?>
            <option value="Officehours">Officehours</option>
            <option value="Afterhours">Afterhours</option>
<?php
            }
?>
         </select>
      </td>
   </tr>
   <tr>
      <td>
         <label class="" for="department">Department</label>
      </td>
      <td colspan="4">
         <select class="button" data-last="" name="department" id="department" class="" style="width: 100%" >
            <option value="<?php echo $row[0]['department']; ?>" ><?php echo $row[0]['department']; ?></option>
<?php
            $deprow=db_query("SELECT DISTINCT(department) as department from departments", array());
            foreach($deprow as $depkey => $depvalue)
            {
               if ($depvalue['department'] != $row[0]['department'] ) {
                  echo "<option value='".$depvalue['department']."'>".$depvalue['department']."</option>";
               }
            }
?>
         </select>
      </td>
   </tr>
   <tr>
      <td></td>
      <td colspan="4">
         <input class="button" type="submit" value="Modify Time"</input>
      </td>
   </tr>

</table>
</div>
</form>

<?php
}

function modify_time_details() {
   $id = $_POST['id'];
   $description = $_POST['description'];
   $hour_start = $_POST['hour_start'];
   $minute_start = $_POST['minute_start'];
   $hour_finish = $_POST['hour_finish'];
   $minute_finish = $_POST['minute_finish']; 
   $wday_start = $_POST['wday_start'];
   $wday_finish = $_POST['wday_finish'];
   $mday_start = $_POST['mday_start'];
   $mday_finish = $_POST['mday_finish'];
   $month = $_POST['month'];
   $context = $_POST['context'];
   $department = $_POST['department'];

   if ($hour_start == "*" | $minute_start == "*" | $hour_finish == "*"  | $minute_finish == "*") {
      $times = "*";
   } else {
      $times = "$hour_start:$minute_start-$hour_finish:$minute_finish";
   }

   if ($wday_start == "*" | $wday_finish == "*") {
      $wday = "*";
   } elseif ($wday_start == $wday_finish) {
      $wday = "$wday_start";
   } else {
      $wday = "$wday_start-$wday_finish";
   }

   if ($mday_start == "*" | $mday_finish == "*") {
      $mday = "*";
   } elseif ($mday_start == $mday_finish) {
      $mday = $mday_start;
   } else {
      $mday = "$mday_start-$mday_finish";
   }

   db_query("UPDATE timeconditions SET description = ?,time = ?,wday = ?,mday = ?,month = ?,context = ?,department = ? WHERE uniqueid = ?", array($description,$times,$wday,$mday,$month,$context,$department,$id)); 

   timeconditions();
}

function delete_timeconditions() {
   $id = $_GET['id'];

   db_query("DELETE FROM timeconditions WHERE uniqueid = ?", array($id));

   timeconditions();
}
?>
