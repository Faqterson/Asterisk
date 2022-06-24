<?php

function cdr() {
   $today = date("Y-m-d 00:00:00");

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
   $search = array();
   $where = "";

   if (isset($_POST['startdate'])) {
      $startdate = $_POST['startdate'];
      $enddate = $_POST['enddate']." 23:59:59"; 
      $where .= "AND calldate > ? and calldate <= ? ";
      array_push($search,$startdate,$enddate);
   } else { 
      $where .= "AND calldate > ? ";
      array_push($search,$today);
   }
   if (isset($_POST['clid']) && $_POST['clid'] != '') { 
      $clid = $_POST['clid']."%";
      $where .= "AND clid LIKE ? ";
      array_push($search,$clid);
   }
   if (isset($_POST['src']) && $_POST['src'] != '') {
      $src = $_POST['src'];
      $where .= "AND src = ? ";
      array_push($search,$src);
   }
   if (isset($_POST['dst']) && $_POST['dst'] != '') {
      $dst = $_POST['dst'];
      $where .= "AND dst = ? ";
      array_push($search,$dst);
   }
   if (isset($_POST['channel']) && $_POST['channel'] != '') {
      $channel = $_POST['channel']."%";
      $where .= "AND channel LIKE ? ";
      array_push($search,$channel);
   }
   if (isset($_POST['dstchannel']) && $_POST['dstchannel'] != '') { 
      $dstchannel = $_POST['dstchannel']."%";
      $where .= "AND dstchannel LIKE ? ";	
      array_push($search,$dstchannel);
   }
   if (isset($_POST['accountcode']) && $_POST['accountcode'] != '') {
      $accountcode = $_POST['accountcode'];
      $where .= "AND accountcode = ? ";
      array_push($search,$accountcode);
   }
   if (isset($_POST['userfield']) && $_POST['userfield'] != '') {
      $userfield = $_POST['userfield'];
      $where .= "AND userfield = ? ";
      array_push($search,$userfield);
   }
   if (isset($_POST['disposition']) && $_POST['disposition'] != '') {
      $disposition = $_POST['disposition'];
      $where .= "AND disposition = ? ";
      array_push($search,$disposition);
   }

   $depallow = check_department_access();

   if ($depallow != "") {
      $row = db_query("SELECT id FROM ps_endpoints LEFT JOIN departments ON (id = sip) WHERE department = ? ORDER BY convert(id,signed)", array($depallow));
      $where .= "AND (";
      foreach($row as $key => $value) {
         $where .= "src = '".$value['id']."' OR dst = '".$value['id']."' OR channel LIKE 'PJSIP/".$value['id']."%' OR dstchannel LIKE 'PJSIP/".$value['id']."%' OR ";
      }
      $where .= " 0 )";
   }
   $row = db_query("SELECT * FROM cdr where 1 $where AND lastapp != 'Return' LIMIT $offset, $rowsperpage", $search);
?>
   <div class="filter-btn-container">
      <button type="button" class="button filter-trigger" id="filter-mode" onclick="javascript: 
                        jQuery(&quot;#filter-space&quot;).toggle();
			jQuery(&quot;#filter-mode&quot;).toggleClass(&quot;filter-active&quot;);
			jQuery(&quot;#filter-arrow&quot;).toggleClass(&quot;arrow-up arrow-down&quot;);
			updateUserProfile(&quot;web.overview.filter.state&quot;, jQuery(&quot;#filter-arrow&quot;).hasClass(&quot;arrow-up&quot;) ? 1 : 0, []);
			if (jQuery(&quot;.multiselect&quot;).length > 0 &amp;&amp; jQuery(&quot;#filter-arrow&quot;).hasClass(&quot;arrow-up&quot;)) {
				jQuery(&quot;.multiselect&quot;).multiSelect(&quot;resize&quot;);
			}
			if (jQuery(&quot;#filter-arrow&quot;).hasClass(&quot;arrow-up&quot;)) {
				jQuery(&quot;#filter-space [autofocus=autofocus]&quot;).focus();
			}">Filter<span id="filter-arrow" class="arrow-down"></span>
      </button>
   </div>
   <div class="filter-container" id="filter-space" style="display: none; text-align: center">
      <form action='index.php?mod=cdr' method='post'>
         <table cellspacing="2" cellpadding="2" border="0">
         <tbody><tr>
            <td align="left" width="40%"><b>Start Date</b></td>
            <td align="left" width="60%"><input name="startdate" id="startdate" size="15" maxlength="10" value="" type="text"></td>
         </tr>
         <tr>
            <td align="left"><b>End Date</b></td>
            <td align="left"><input name="enddate" id="enddate" size="15" maxlength="10" type="text"></td>
         </tr>
         <tr>
            <td align="left"><b>Caller Name</b></td>
            <td align="left"><input name="clid" size="35" maxlength="80" type="text"></td>
         </tr>
         <tr>
            <td align="left"><b>Source Number</b></td>
            <td align="left"><input name="src" size="35" maxlength="80" type="text"></td>
         </tr>
         <tr>
            <td align="left"><b>Destination Number</b></td>
            <td align="left"><input name="dst" size="35" maxlength="80" type="text"></td>
         </tr>
         <tr>
            <td align="left"><b>Channel In</b></td>
            <td align="left"><input name="channel" size="35" maxlength="80" type="text"></td>
         </tr>
         <tr>
            <td align="left"><b>Channel Out</b></td>
            <td align="left"><input name="dstchannel" size="35" maxlength="80" type="text"></td>
         </tr>
         <tr>
            <td align="left"><b>Account Code</b></td>
            <td align="left"><input name="accountcode" size="35" maxlength="20" type="text"></td>
         </tr>
         <tr>
            <td align="left"><b>Userfield</b></td>
            <td align="left"><input name="userfield" size="35" maxlength="20" type="text"></td>
         </tr>
         <tr>
            <td align="left"><b>Disposition</b></td>
            <td align="left">
               <select name="disposition" class="">
                  <option value=""></option>
                  <option value="NO ANSWER">NO ANSWER</option> 
                  <option value="ANSWERED">ANSWERED</option>
                  <option value="FAILED">FAILED</option>
                  <option value="BUSY">BUSY</option>
               </select>
            </td>
         </tr>
         <tr>
      	    <td align="left">&nbsp;</td>
            <td align="left"><input name="submit" class="button" value="Search" type="submit"></td>
         </tr>
         </tbody>
         </table>
      </form>
   </div>

<?php
   echo "<div id='dvData1'>";
   echo "<table style='width: 100%; text-align: center'>";
   echo "<th>Call Date</th><th>CLID</th><th>Source</th><th>Destination</th><th>Channel</th><th>Dst Channel</th><th>Duration</th><th>User Field</th><th>Disposition</th><th>Recording</th>";
   foreach($row as $key => $value) { 
      $clid = str_replace('"', '', $value['clid']);
      $clid = str_replace('<', '', $clid);
      $clid = str_replace('>', '', $clid);
      $clid = str_replace($value["src"], '', $clid);
      if ($clid == '') { 
         $clid = "No CLID";
      }
      echo "<tr>";
      echo "<td>".$value["calldate"]."</td>";
      echo "<td>".$clid."</td>";
      echo "<td>".$value["src"]."</td>";
      echo "<td>".$value["dst"]."</td>";
      echo "<td>".$value["channel"]."</td>";
      echo "<td>".$value["dstchannel"]."</td>";

      echo "<td>".gmdate('H:i:s', $value["billsec"])."</td>";

      echo "<td>".$value["userfield"]."</td>";
      echo "<td>".$value["disposition"]."</td>";
      if($value["disposition"] == 'ANSWERED'){
         $date = date("Y-m-d",strtotime($value['calldate']));
         $play = "";
         if (file_exists("/var/spool/asterisk/monitor/$date/".$value["uniqueid"].".WAV")) {
            $play = "<a style='background: none' href='monitor/$date/".$value["uniqueid"].".WAV'><img class='recording' src=img/play-icon.png height='20' width='20'></a>";
         } else {
            $play = "<img class='recording' src=img/nofile-icon.png height='20' width='20'>";
         }

         echo "<td align='center'>". $play ."</td>";

      } else {
         echo "<td align='center'>N/A</td>";
      }
      echo "</tr>";
   }
   echo "</table>"; 
   echo "</div>";
   echo "<a href='#' class='export1' style='font-size: 12px; background: none'><img class='' src='img/export-icon.png' alt='CSV Export' height='30'></a>";
?>
   <table width="100%">
   <tr>
      <td align="center">
      <?php
         $resultPage = db_query("SELECT * FROM cdr WHERE 1 $where AND lastapp != 'Return'", $search);
         pagination('index.php?mod=cdr',$rowsperpage,$resultPage,$page);
      ?>
      </td>
   </tr>
   </table>
<?php

   echo "</table>";
   echo "</div>";

}

?>
