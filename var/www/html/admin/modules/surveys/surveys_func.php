<?php

function surveys() {
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

   if (isset($_POST['startdate'])) {
      $startdate = $_POST['startdate'];
      $enddate = $_POST['enddate']." 23:59:59"; 
      $where .= "AND calldate > ? and calldate <= ? ";
      array_push($search, $startdate,$enddate);
   } else { 
      $where .= "AND calldate > ? ";
      array_push($search, $today);
   }
   if (isset($_POST['clid']) && $_POST['clid'] != '') { 
      $clid = $_POST['clid']."%";;
      $where .= "AND clid LIKE ? ";
      array_push($search, $clid);
   }
   if (isset($_POST['src']) && $_POST['src'] != '') {
      $src = $_POST['src'];
      $where .= "AND src = ? ";
      array_push($search, $src);
   }
   if (isset($_POST['agent']) && $_POST['agent'] != '') {
      $agent = $_POST['agent'];
      $where .= "AND agent = ? ";
      array_push($search, $agent);
   }
   if (isset($_POST['answers']) && $_POST['answers'] != '') {
      $answers = $_POST['answers'];
      $where .= "AND answers = ? ";
      array_push($search, $answers);
   }
   $sumrow = db_query("SELECT SUBSTRING_INDEX(clid,'<',1) as queue,answers,count(*) as count FROM surveys where 1 $where group by answers,queue", $search);

   $row = db_query("SELECT surveys.uniqueid,clid,calldate,src,agent,callerid,answers FROM surveys LEFT JOIN ps_endpoints ON (ps_endpoints.id = agent) where 1 $where LIMIT $offset, $rowsperpage", $search);

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
      <form action='index.php?mod=surveys' method='post'>
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
            <td align="left"><b>Agent</b></td>
            <td align="left"><input name="agent" size="35" maxlength="80" type="text"></td>
         </tr>
         <tr>
            <td align="left"><b>Answers</b></td>
            <td align="left"><input name="answers" size="35" maxlength="80" type="text"></td>
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
   echo "<div id='dvData2'>";
   echo "<table style='width: 100%'>";
   echo "<th>Queue</th><th>Answers</th><th>Count</th>";
   foreach($sumrow as $key => $value) { 
      echo "<tr align='center'>";
      echo "<td>".$value["queue"]."</td>";
      echo "<td>".$value["answers"]."</td>";
      echo "<td>".$value["count"]."</td>";
      echo "</tr>";
   }
   echo "</table>"; 
   echo "</div>";
   echo "<a href='#' class='export2' style='font-size: 12px; background: none'><img class='' src='img/export-icon.png' alt='CSV Export' height='30'></a>";

   echo "<div id='dvData1'>";
   echo "<table style='width: 100%'>";
   echo "<th>Call Date</th><th>CLID</th><th>Source</th><th>Agent</th><th>Answer</th><th>Recording</th>";
   foreach($row as $key => $value) { 
      $clid = str_replace('"', '', $value['clid']);
      $clid = str_replace('<', '', $clid);
      $clid = str_replace('>', '', $clid);
      $clid = str_replace($value["src"], '', $clid);
      if ($clid == '') { 
         $clid = "No CLID";
      }
      echo "<tr align='center'>";
      echo "<td>".$value["calldate"]."</td>";
      echo "<td>".$clid."</td>";
      echo "<td>".$value["src"]."</td>";
      echo "<td>".$value["agent"]." (".$row["callerid"].")</td>";
      echo "<td>".$value["answers"]."</td>";
      $date = date("Y-m-d",strtotime($value['calldate']));
      $play = "";
      if (file_exists("/var/spool/asterisk/monitor/$date/".$value["uniqueid"].".WAV")) {
         $play = "<a style='background: none' href='monitor/$date/".$value["uniqueid"].".WAV'><img class='recording' src=img/play-icon.png height='20' width='20'></a>";
      } else {
         $play = "<img class='recording' src=img/nofile-icon.png height='20' width='20'>";
      }
      echo "<td align='center'>". $play ."</td>";
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
         $resultPage = db_query("SELECT * FROM surveys WHERE 1 $where", $search);
         pagination('index.php?mod=surveys',$rowsperpage,$resultPage,$page);
      ?>
      </td>
   </tr>
   </table>
<?php

   echo "</table>";
   echo "</div>";

}

?>
