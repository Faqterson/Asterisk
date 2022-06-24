<?php
function allexten () {
   $where = "";
   $search = array();

   if (isset($_GET['startdate'])) {
      $startdate = $_GET['startdate'];
      $enddate = $_GET['enddate']." 23:59:59";
      $where .= "AND calldate > ? and calldate <= ? ";
      array_push($search,$startdate,$enddate);
   } else {
      $today = $_GET['today'];
      $where .= "AND calldate > ? ";
      array_push($search, $today);
   }

            if ($_GET['department'] == 'Unknown' ) {
               $row=db_query("SELECT count(cdr.uniqueid) as count, TIME_FORMAT(SEC_TO_TIME(sum(CASE WHEN t.duration is NULL THEN 0 ELSE t.duration END)), '%H:%i:%s') as totaltime, TIME_FORMAT(SEC_TO_TIME(sum(CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END)), '%H:%i:%s') as talktime, ROUND((sum((CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END)*(CASE WHEN t.cost is NULL THEN 0 ELSE t.cost END))), 2) as costs, CASE WHEN t.destination_name is NULL THEN 'Unknown' ELSE t.destination_name END as destname from cdr LEFT JOIN trunkcost as t using (uniqueid) LEFT JOIN tarrif_plan AS p ON cdr.dstchannel REGEXP p.trunkname LEFT JOIN departments as d ON SUBSTRING_INDEX(SUBSTRING_INDEX(cdr.channel, '-', 1), '/', -1) = d.sip WHERE cdr.dst != 's' AND cdr.dst != '' AND  cdr.dstchannel != '' AND disposition = 'ANSWERED' and p.planname is not null and     (SUBSTRING_INDEX(cdr.channel, '-', 1) REGEXP d.sip or d.sip is null) and department = ''  AND 1 $where group by destination_name order by costs DESC", $search);
            } elseif ($_GET['department'] == 'Extension Missing') {
               $row=db_query("SELECT count(cdr.uniqueid) as count, TIME_FORMAT(SEC_TO_TIME(sum(CASE WHEN t.duration is NULL THEN 0 ELSE t.duration END)), '%H:%i:%s') as totaltime, TIME_FORMAT(SEC_TO_TIME(sum(CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END)), '%H:%i:%s') as talktime, ROUND((sum((CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END)*(CASE WHEN t.cost is NULL THEN 0 ELSE t.cost END))), 2) as costs, CASE WHEN t.destination_name is NULL THEN 'Unknown' ELSE t.destination_name END as destname from cdr LEFT JOIN trunkcost as t using (uniqueid) LEFT JOIN tarrif_plan AS p ON cdr.dstchannel REGEXP p.trunkname LEFT JOIN departments as d ON SUBSTRING_INDEX(SUBSTRING_INDEX(cdr.channel, '-', 1), '/', -1) = d.sip WHERE cdr.dst != 's' AND cdr.dst != '' AND  cdr.dstchannel != '' AND disposition = 'ANSWERED' and p.planname is not null and     (SUBSTRING_INDEX(cdr.channel, '-', 1) REGEXP d.sip or d.sip is null) and department is null  AND 1 $where group by destination_name order by costs DESC", $search);
            } else {
               array_push($search,$_GET['department']);
               $row=db_query("SELECT count(cdr.uniqueid) as count, TIME_FORMAT(SEC_TO_TIME(sum(CASE WHEN t.duration is NULL THEN 0 ELSE t.duration END)), '%H:%i:%s') as totaltime, TIME_FORMAT(SEC_TO_TIME(sum(CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END)), '%H:%i:%s') as talktime, ROUND((sum((CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END)*(CASE WHEN t.cost is NULL THEN 0 ELSE t.cost END))), 2) as costs, CASE WHEN t.destination_name is NULL THEN 'Unknown' ELSE t.destination_name END as destname from cdr LEFT JOIN trunkcost as t using (uniqueid) LEFT JOIN tarrif_plan AS p ON cdr.dstchannel REGEXP p.trunkname LEFT JOIN departments as d ON SUBSTRING_INDEX(SUBSTRING_INDEX(cdr.channel, '-', 1), '/', -1) = d.sip WHERE cdr.dst != 's' AND cdr.dst != '' AND  cdr.dstchannel != '' AND disposition = 'ANSWERED' and p.planname is not null and (SUBSTRING_INDEX(cdr.channel, '-', 1) REGEXP d.sip or d.sip is null) AND 1 $where and department = ? group by destination_name order by costs DESC", $search);            
            }

            echo "<h4>Specified departments, All plans, Summary: (" . $_GET['department'] . ")</h4>";
            echo "<div id='dvData1'>";
            echo "<table width=100% >";
            echo "<tr><th>Destination</th><th align='right'>Count</th><th align='right'>Total Time</th><th align='right'>Talk Time</th><th align='right'>Cost</th></tr>";
            foreach($row as $key => $value)
            {
               echo "<tr>";
               if (isset($_GET['startdate'])) {
                  echo "<td width=40% align='center'><a href='index.php?mod=depallplan&destination=" . $value['destname'] . "&department=" . $_GET['department'] . "&startdate=" . $_GET['startdate'] . "&enddate=" . $_GET['enddate'] . "'>" . $value['destname'] . "</a></td>";
               } else {
                  echo "<td width=40% align='center'><a href='index.php?mod=depallplan&destination=" . $value['destname'] . "&department=" . $_GET['department'] . "&today=" . $_GET['today'] . "'>" . $value['destname'] . "</a></td>";
               }
               echo "<td align='right'>" . $value['count'] . "</td>";
               echo "<td align='right'>" . $value['totaltime'] . "</td>";
               echo "<td align='right'>" . $value['talktime'] . "</td>";
               echo "<td align='right'>R " . $value['costs'] . "</td>";
               echo "</tr>";
            }

            if ($_GET['department'] == 'Unknown' ) {
               $row=db_query("SELECT count(cdr.uniqueid), TIME_FORMAT(SEC_TO_TIME(sum(CASE WHEN t.duration is NULL THEN 0 ELSE t.duration END)), '%H:%i:%s') as totaltime, TIME_FORMAT(SEC_TO_TIME(sum(CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END)), '%H:%i:%s') as talktime, ROUND((sum((CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END)*(CASE WHEN t.cost is NULL THEN 0 ELSE t.cost END))), 2) as costs, CASE WHEN t.destination_name is NULL THEN 'Unknown' ELSE t.destination_name END as destname from cdr LEFT JOIN trunkcost as t using (uniqueid) LEFT JOIN tarrif_plan AS p ON cdr.dstchannel REGEXP p.trunkname LEFT JOIN departments as d ON SUBSTRING_INDEX(SUBSTRING_INDEX(cdr.channel, '-', 1), '/', -1) = d.sip WHERE cdr.dst != 's' AND cdr.dst != '' AND  cdr.dstchannel != '' AND disposition = 'ANSWERED' and p.planname is not null and     (SUBSTRING_INDEX(cdr.channel, '-', 1) REGEXP d.sip or d.sip is null) and department = '' AND 1 $where  order by costs DESC", $search);
            } elseif ($_GET['department'] == 'Extension Missing') {
               $row=db_query("SELECT count(cdr.uniqueid) as count, TIME_FORMAT(SEC_TO_TIME(sum(CASE WHEN t.duration is NULL THEN 0 ELSE t.duration END)), '%H:%i:%s') as totaltime, TIME_FORMAT(SEC_TO_TIME(sum(CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END)), '%H:%i:%s') as talktime, ROUND((sum((CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END)*(CASE WHEN t.cost is NULL THEN 0 ELSE t.cost END))), 2) as costs from cdr LEFT JOIN trunkcost as t using (uniqueid) LEFT JOIN tarrif_plan AS p ON cdr.dstchannel REGEXP p.trunkname LEFT JOIN departments as d ON SUBSTRING_INDEX(SUBSTRING_INDEX(cdr.channel, '-', 1), '/', -1) = d.sip WHERE cdr.dst != 's' AND cdr.dst != '' AND  cdr.dstchannel != '' AND disposition = 'ANSWERED' and p.planname is not null and     (SUBSTRING_INDEX(cdr.channel, '-', 1) REGEXP d.sip or d.sip is null) and department is null  AND 1 $where order by costs DESC", $search);
            } else {
               array_push($search,$_GET['department']);
               $row=db_query("SELECT 
                  count(cdr.uniqueid) as count, TIME_FORMAT(SEC_TO_TIME(sum(CASE WHEN t.duration is NULL THEN 0 ELSE t.duration END)), '%H:%i:%s') as totaltime,
                  TIME_FORMAT(SEC_TO_TIME(sum(CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END)), '%H:%i:%s') as talktime,
                  ROUND((sum((CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END)*(CASE WHEN t.cost is NULL THEN 0 ELSE t.cost END))), 2) as costs               
               from cdr 
                  LEFT JOIN trunkcost as t using (uniqueid) 
                  LEFT JOIN tarrif_plan AS p ON cdr.dstchannel REGEXP p.trunkname 
                  LEFT JOIN departments as d ON SUBSTRING_INDEX(SUBSTRING_INDEX(cdr.channel, '-', 1), '/', -1) = d.sip 
               WHERE 
                  cdr.dst != 's' AND cdr.dst != '' AND  cdr.dstchannel != '' AND 
                  disposition = 'ANSWERED' and 
                  p.planname is not null and 
                  (SUBSTRING_INDEX(cdr.channel, '-', 1) REGEXP d.sip or d.sip is null) and 
                  1 $where AND
                  department = ?
               order by costs DESC", $search);
            }

               echo "<tr><td></td><td></td><td></td><td></td><td></td></tr><tr>";
               echo "<td width=40% align='center'>Total</td>";
               echo "<td align='right'>" . $row[0]['count'] . "</td>";
               echo "<td align='right'>" . $row[0]['totaltime'] . "</td>";
               echo "<td align='right'>" . $row[0]['talktime'] . "</td>";
               echo "<td align='right'>R " . $row[0]['costs'] . "</td>";
               echo "</tr>";

            echo "</table>";
            echo "</div>";
            echo "<a href='#' class='export1' style='font-size: 12px; background: none'><img class='' src='img/export-icon.png' alt='CSV Export' height='30'></a>";

//Check all plans
            $planrow=db_query("SELECT planname from tarrif_plan", array());

            foreach($planrow as $plankey => $planvalue)
            {
               if ($_GET['department'] == 'Unknown' ) {
                  $row=db_query("SELECT count(cdr.uniqueid) as count, TIME_FORMAT(SEC_TO_TIME(sum(CASE WHEN t.duration is NULL THEN 0 ELSE t.duration END)), '%H:%i:%s') as totaltime, TIME_FORMAT(SEC_TO_TIME(sum(CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END)), '%H:%i:%s') as talktime, ROUND((sum((CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END)*(CASE WHEN t.cost is NULL THEN 0 ELSE t.cost END))), 2) as costs, CASE WHEN t.destination_name is NULL THEN 'Unknown' ELSE t.destination_name END as destname from cdr LEFT JOIN trunkcost as t using (uniqueid) LEFT JOIN tarrif_plan AS p ON cdr.dstchannel REGEXP p.trunkname LEFT JOIN departments as d ON SUBSTRING_INDEX(SUBSTRING_INDEX(cdr.channel, '-', 1), '/', -1) = d.sip WHERE cdr.dst != 's' AND cdr.dst != '' AND  cdr.dstchannel != '' AND disposition = 'ANSWERED' and p.planname is not null and     (SUBSTRING_INDEX(cdr.channel, '-', 1) REGEXP d.sip or d.sip is null) AND d.sip != '' and department = '' AND p.planname = '" . $planvalue['planname'] . "'  AND 1 $where group by destination_name order by costs DESC", $search);
               } elseif ($_GET['department'] == 'Extension Missing') {
                  $row=db_query("SELECT count(cdr.uniqueid) as count, TIME_FORMAT(SEC_TO_TIME(sum(CASE WHEN t.duration is NULL THEN 0 ELSE t.duration END)), '%H:%i:%s') as totaltime, TIME_FORMAT(SEC_TO_TIME(sum(CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END)), '%H:%i:%s') as talktime, ROUND((sum((CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END)*(CASE WHEN t.cost is NULL THEN 0 ELSE t.cost END))), 2) as costs, CASE WHEN t.destination_name is NULL THEN 'Unknown' ELSE t.destination_name END as destname from cdr LEFT JOIN trunkcost as t using (uniqueid) LEFT JOIN tarrif_plan AS p ON cdr.dstchannel REGEXP p.trunkname LEFT JOIN departments as d ON SUBSTRING_INDEX(SUBSTRING_INDEX(cdr.channel, '-', 1), '/', -1) = d.sip WHERE cdr.dst != 's' AND cdr.dst != '' AND  cdr.dstchannel != '' AND disposition = 'ANSWERED' and p.planname is not null and     (SUBSTRING_INDEX(cdr.channel, '-', 1) REGEXP d.sip or d.sip is null) and department is null AND p.planname = '" . $planvalue['planname'] . "'  AND 1 $where group by destination_name order by costs DESC", $search);
               } else {
                  $row=db_query("SELECT count(cdr.uniqueid) as count, TIME_FORMAT(SEC_TO_TIME(sum(CASE WHEN t.duration is NULL THEN 0 ELSE t.duration END)), '%H:%i:%s') as totaltime, TIME_FORMAT(SEC_TO_TIME(sum(CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END)), '%H:%i:%s') as talktime, ROUND((sum((CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END)*(CASE WHEN t.cost is NULL THEN 0 ELSE t.cost END))), 2) as costs, CASE WHEN t.destination_name is NULL THEN 'Unknown' ELSE t.destination_name END as destname from cdr LEFT JOIN trunkcost as t using (uniqueid) LEFT JOIN tarrif_plan AS p ON cdr.dstchannel REGEXP p.trunkname LEFT JOIN departments as d ON SUBSTRING_INDEX(SUBSTRING_INDEX(cdr.channel, '-', 1), '/', -1) = d.sip WHERE cdr.dst != 's' AND cdr.dst != '' AND  cdr.dstchannel != '' AND disposition = 'ANSWERED' and p.planname is not null and     (SUBSTRING_INDEX(cdr.channel, '-', 1) REGEXP d.sip or d.sip is null) AND d.sip != '' AND p.planname = '" . $planvalue['planname'] . "'  AND 1 $where and department = ? group by destination_name order by costs DESC", $search);            
	       }

               echo "<h4>Specified departments, Specified plan, Summary: (" . $_GET['department'] . " - " . $planvalue['planname'] . ")</h4>";
               echo "<div id='dvData2'>";
               echo "<table width=100% >";
               echo "<tr><th>Destination</th><th align='right'>Count</th><th align='right'>Total Time</th><th align='right'>Talk Time</th><th align='right'>Cost</th></tr>";
               foreach($row as $key => $value)
               {
                  echo "<tr>";
                  if (isset($_GET['startdate'])) {
                     echo "<td width=40% align='center'><a href='index.php?mod=depspecificplan&destination=" . $value['destname'] . "&plan=" . $planvalue['planname'] . "&department=" . $_GET['department'] . "&startdate=" . $_GET['startdate'] . "&enddate=" . $_GET['enddate'] . "'>" . $value['destname'] . "</a></td>";
                  } else {
                     echo "<td width=40% align='center'><a href='index.php?mod=depspecificplan&destination=" . $value['destname'] . "&plan=" . $planvalue['planname'] . "&department=" . $_GET['department'] . "&today=" . $_GET['today'] . "'>" . $value['destname'] . "</a></td>";
                  }
                  echo "<td align='right'>" . $value['count'] . "</td>";
                  echo "<td align='right'>" . $value['totaltime'] . "</td>";
                  echo "<td align='right'>" . $value['talktime'] . "</td>";
                  echo "<td align='right'>R " . $value['costs'] . "</td>";
 	          echo "</tr>";
               }
               if ($_GET['department'] == 'Unknown' ) {
                  $row=db_query("SELECT                   count(cdr.uniqueid) as count, TIME_FORMAT(SEC_TO_TIME(sum(CASE WHEN t.duration is NULL THEN 0 ELSE t.duration END)), '%H:%i:%s') as totaltime,                  TIME_FORMAT(SEC_TO_TIME(sum(CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END)), '%H:%i:%s') as talktime,                  ROUND((sum((CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END)*(CASE WHEN t.cost is NULL THEN 0 ELSE t.cost END))), 2) as costs from cdr LEFT JOIN trunkcost as t using (uniqueid) LEFT JOIN tarrif_plan AS p ON cdr.dstchannel REGEXP p.trunkname LEFT JOIN departments as d ON SUBSTRING_INDEX(SUBSTRING_INDEX(cdr.channel, '-', 1), '/', -1) = d.sip WHERE cdr.dst != 's' AND cdr.dst != '' AND  cdr.dstchannel != '' AND disposition = 'ANSWERED' and p.planname is not null and     (SUBSTRING_INDEX(cdr.channel, '-', 1) REGEXP d.sip or d.sip is null) AND d.sip != '' and department = '' AND p.planname = '" . $planvalue['planname'] . "'  AND 1 $where order by costs DESC", $search);
               } elseif ($_GET['department'] == 'Extension Missing') {
                  $row=db_query("SELECT                   count(cdr.uniqueid) as count, TIME_FORMAT(SEC_TO_TIME(sum(CASE WHEN t.duration is NULL THEN 0 ELSE t.duration END)), '%H:%i:%s') as totaltime,                  TIME_FORMAT(SEC_TO_TIME(sum(CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END)), '%H:%i:%s') as talktime,                  ROUND((sum((CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END)*(CASE WHEN t.cost is NULL THEN 0 ELSE t.cost END))), 2) as costs               from cdr LEFT JOIN trunkcost as t using (uniqueid) LEFT JOIN tarrif_plan AS p ON cdr.dstchannel REGEXP p.trunkname LEFT JOIN departments as d ON SUBSTRING_INDEX(SUBSTRING_INDEX(cdr.channel, '-', 1), '/', -1) = d.sip WHERE cdr.dst != 's' AND cdr.dst != '' AND  cdr.dstchannel != '' AND disposition = 'ANSWERED' and p.planname is not null and     (SUBSTRING_INDEX(cdr.channel, '-', 1) REGEXP d.sip or d.sip is null) and department is null AND p.planname = '" . $planvalue['planname'] . "'  AND 1 $where order by costs DESC", $search);
               } else {
                  $row=db_query("SELECT count(cdr.uniqueid) as count, TIME_FORMAT(SEC_TO_TIME(sum(CASE WHEN t.duration is NULL THEN 0 ELSE t.duration END)), '%H:%i:%s') as totaltime, TIME_FORMAT(SEC_TO_TIME(sum(CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END)), '%H:%i:%s') as talktime, ROUND((sum((CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END)*(CASE WHEN t.cost is NULL THEN 0 ELSE t.cost END))), 2) as costs from cdr LEFT JOIN trunkcost as t using (uniqueid) LEFT JOIN tarrif_plan AS p ON cdr.dstchannel REGEXP p.trunkname LEFT JOIN departments as d ON SUBSTRING_INDEX(SUBSTRING_INDEX(cdr.channel, '-', 1), '/', -1) = d.sip WHERE cdr.dst != 's' AND cdr.dst != '' AND  cdr.dstchannel != '' AND disposition = 'ANSWERED' and p.planname is not null and     (SUBSTRING_INDEX(cdr.channel, '-', 1) REGEXP d.sip or d.sip is null) AND d.sip != '' AND p.planname = '" . $planvalue['planname'] . "'  AND 1 $where and department = ? order by costs DESC", $search);            
	       }

               echo "<tr><td></td><td></td><td></td><td></td><td></td></tr><tr>";
               echo "<td width=40% align='center'>Total</td>";
               echo "<td align='right'>" . $row[0]['count'] . "</td>";
               echo "<td align='right'>" . $row[0]['totaltime'] . "</td>";
               echo "<td align='right'>" . $row[0]['talktime'] . "</td>";
               echo "<td align='right'>R " . $row[0]['costs'] . "</td>";
               echo "</tr>";

               echo "</table>";
               echo "</div>";
               echo "<a href='#' class='export2' style='font-size: 12px; background: none'><img class='' src='img/export-icon.png' alt='CSV Export' height='30'></a>";

            }
//End Of all plans


//All extension, specific department
            if ($_GET['department'] == 'Unknown' ) {
               $row=db_query("SELECT count(cdr.uniqueid) as count, TIME_FORMAT(SEC_TO_TIME(sum(CASE WHEN t.duration is NULL THEN 0 ELSE t.duration END)), '%H:%i:%s') as totaltime, TIME_FORMAT(SEC_TO_TIME(sum(CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END)), '%H:%i:%s') as talktime, ROUND((sum((CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END)*(CASE WHEN t.cost is NULL THEN 0 ELSE t.cost END))), 2) as costs, d.sip, clid from cdr LEFT JOIN trunkcost as t using (uniqueid) LEFT JOIN tarrif_plan AS p ON cdr.dstchannel REGEXP p.trunkname LEFT JOIN departments as d ON SUBSTRING_INDEX(SUBSTRING_INDEX(cdr.channel, '-', 1), '/', -1) = d.sip WHERE cdr.dst != 's' AND cdr.dst != '' AND  cdr.dstchannel != '' AND disposition = 'ANSWERED' and p.planname is not null and     (SUBSTRING_INDEX(cdr.channel, '-', 1) REGEXP d.sip or d.sip is null) AND d.sip != '' and department = ''  AND 1 $where group by d.sip order by costs DESC", $search);
            } elseif ($_GET['department'] == 'Extension Missing') {
               $row=db_query("SELECT count(cdr.uniqueid) as count, TIME_FORMAT(SEC_TO_TIME(sum(CASE WHEN t.duration is NULL THEN 0 ELSE t.duration END)), '%H:%i:%s') as totaltime, TIME_FORMAT(SEC_TO_TIME(sum(CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END)), '%H:%i:%s') as talktime, ROUND((sum((CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END)*(CASE WHEN t.cost is NULL THEN 0 ELSE t.cost END))), 2) as costs, SUBSTRING_INDEX(cdr.channel, '-', 1) as srcchannel from cdr LEFT JOIN trunkcost as t using (uniqueid) LEFT JOIN tarrif_plan AS p ON cdr.dstchannel REGEXP p.trunkname LEFT JOIN departments as d ON SUBSTRING_INDEX(SUBSTRING_INDEX(cdr.channel, '-', 1), '/', -1) = d.sip WHERE cdr.dst != 's' AND cdr.dst != '' AND  cdr.dstchannel != '' AND disposition = 'ANSWERED' and p.planname is not null and     (SUBSTRING_INDEX(cdr.channel, '-', 1) REGEXP d.sip or d.sip is null) and department is null  AND 1 $where group by srcchannel order by costs DESC", $search);
            } else {
               $row=db_query("SELECT count(cdr.uniqueid) as count, TIME_FORMAT(SEC_TO_TIME(sum(CASE WHEN t.duration is NULL THEN 0 ELSE t.duration END)), '%H:%i:%s') as totaltime, TIME_FORMAT(SEC_TO_TIME(sum(CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END)), '%H:%i:%s') as talktime, ROUND((sum((CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END)*(CASE WHEN t.cost is NULL THEN 0 ELSE t.cost END))), 2) as costs, d.sip, clid from cdr LEFT JOIN trunkcost as t using (uniqueid) LEFT JOIN tarrif_plan AS p ON cdr.dstchannel REGEXP p.trunkname LEFT JOIN departments as d ON SUBSTRING_INDEX(SUBSTRING_INDEX(cdr.channel, '-', 1), '/', -1) = d.sip WHERE cdr.dst != 's' AND cdr.dst != '' AND  cdr.dstchannel != '' AND disposition = 'ANSWERED' and p.planname is not null and     (SUBSTRING_INDEX(cdr.channel, '-', 1) REGEXP d.sip or d.sip is null) AND d.sip != '' AND 1 $where and department = ? group by d.sip order by costs DESC", $search);
	    }

            echo "<h4>All extension, Specified departments, All plans, Summary: (" . $_GET['department'] . ")</h4>";
            echo "<div id='dvData3'>";
            echo "<table width=100% >";
            echo "<tr><th>Extension</th><th align='right'>Count</th><th align='right'>Total Time</th><th align='right'>Talk Time</th><th align='right'>Cost</th></tr>";
            foreach($row as $key => $value)
            {
               echo "<tr>";
               if (isset($_GET['startdate'])) {
                  echo "<td width=40% align='center'><a href='index.php?mod=depspecificexten&extension=" . $value['sip'] . "&department=" . $_GET['department'] . "&startdate=" . $_GET['startdate'] . "&enddate=" . $_GET['enddate'] . "'>" . $value['sip'] . "-".$value['clid']."</a></td>";
               } else {
                  echo "<td width=40% align='center'><a href='index.php?mod=depspecificexten&extension=" . $value['sip'] . "&department=" . $_GET['department'] . "&today=" . $_GET['today'] . "'>" . $value['sip'] . "-".$value['clid']."</a></td>";
               }
               echo "<td align='right'>" . $value['count'] . "</td>";
               echo "<td align='right'>" . $value['totaltime'] . "</td>";
               echo "<td align='right'>" . $value['talktime'] . "</td>";
               echo "<td align='right'>R " . $value['costs'] . "</td>";
               echo "</tr>";
            }

            if ($_GET['department'] == 'Unknown' ) {
               $row=db_query("SELECT count(cdr.uniqueid) as count, TIME_FORMAT(SEC_TO_TIME(sum(CASE WHEN t.duration is NULL THEN 0 ELSE t.duration END)), '%H:%i:%s') as totaltime, TIME_FORMAT(SEC_TO_TIME(sum(CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END)), '%H:%i:%s') as talktime, ROUND((sum((CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END)*(CASE WHEN t.cost is NULL THEN 0 ELSE t.cost END))), 2) as costs, d.sip from cdr LEFT JOIN trunkcost as t using (uniqueid) LEFT JOIN tarrif_plan AS p ON cdr.dstchannel REGEXP p.trunkname LEFT JOIN departments as d ON SUBSTRING_INDEX(SUBSTRING_INDEX(cdr.channel, '-', 1), '/', -1) = d.sip WHERE cdr.dst != 's' AND cdr.dst != '' AND  cdr.dstchannel != '' AND disposition = 'ANSWERED' and p.planname is not null and     (SUBSTRING_INDEX(cdr.channel, '-', 1) REGEXP d.sip or d.sip is null) AND d.sip != '' and department = ''  AND 1 $where order by costs DESC", $search);
            } elseif ($_GET['department'] == 'Extension Missing') {
               $row=db_query("SELECT count(cdr.uniqueid) as count, TIME_FORMAT(SEC_TO_TIME(sum(CASE WHEN t.duration is NULL THEN 0 ELSE t.duration END)), '%H:%i:%s') as totaltime, TIME_FORMAT(SEC_TO_TIME(sum(CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END)), '%H:%i:%s') as talktime, ROUND((sum((CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END)*(CASE WHEN t.cost is NULL THEN 0 ELSE t.cost END))), 2) as costs, SUBSTRING_INDEX(cdr.channel, '-', 1) from cdr LEFT JOIN trunkcost as t using (uniqueid) LEFT JOIN tarrif_plan AS p ON cdr.dstchannel REGEXP p.trunkname LEFT JOIN departments as d ON SUBSTRING_INDEX(SUBSTRING_INDEX(cdr.channel, '-', 1), '/', -1) = d.sip WHERE cdr.dst != 's' AND cdr.dst != '' AND  cdr.dstchannel != '' AND disposition = 'ANSWERED' and p.planname is not null and     (SUBSTRING_INDEX(cdr.channel, '-', 1) REGEXP d.sip or d.sip is null) and department is null  AND 1 $where order by costs DESC", $search);
            } else {
               $row=db_query("SELECT count(cdr.uniqueid) as count, TIME_FORMAT(SEC_TO_TIME(sum(CASE WHEN t.duration is NULL THEN 0 ELSE t.duration END)), '%H:%i:%s') as totaltime, TIME_FORMAT(SEC_TO_TIME(sum(CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END)), '%H:%i:%s') as talktime, ROUND((sum((CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END)*(CASE WHEN t.cost is NULL THEN 0 ELSE t.cost END))), 2) as costs, d.sip from cdr LEFT JOIN trunkcost as t using (uniqueid) LEFT JOIN tarrif_plan AS p ON cdr.dstchannel REGEXP p.trunkname LEFT JOIN departments as d ON SUBSTRING_INDEX(SUBSTRING_INDEX(cdr.channel, '-', 1), '/', -1) = d.sip WHERE cdr.dst != 's' AND cdr.dst != '' AND  cdr.dstchannel != '' AND disposition = 'ANSWERED' and p.planname is not null and     (SUBSTRING_INDEX(cdr.channel, '-', 1) REGEXP d.sip or d.sip is null) AND d.sip != '' AND 1 $where and department = ? order by costs DESC", $search);
	    }

               echo "<tr><td></td><td></td><td></td><td></td><td></td></tr><tr>";
               echo "<td width=40% align='center'>Total</td>";
               echo "<td align='right'>" . $row[0]['count'] . "</td>";
               echo "<td align='right'>" . $row[0]['totaltime'] . "</td>";
               echo "<td align='right'>" . $row[0]['talktime'] . "</td>";
               echo "<td align='right'>R " . $row[0]['costs'] . "</td>";
               echo "</tr>";

            echo "</table>";
            echo "</div>";
            echo "<a href='#' class='export3'  style='font-size: 12px; background: none'><img class='' src='img/export-icon.png' alt='CSV Export' height='30'></a>";

//End of all extensions

//All accountcodes, specific department  
            $accrow=db_query("SELECT name from pin_codes", array());

            if (sizeof($accrow) == 0) {
               $accountcode = 0;
            } else {               
               if ($_GET['department'] == 'Unknown' ) {
                  $row=db_query("SELECT count(cdr.uniqueid) as count, TIME_FORMAT(SEC_TO_TIME(sum(t.duration)), '%H:%i:%s') as totaltime, TIME_FORMAT(SEC_TO_TIME(sum(t.billsec)), '%H:%i:%s') as talktime, ROUND((sum(t.billsec*t.cost)), 2) as costs, pc.name from cdr LEFT JOIN trunkcost as t using (uniqueid) LEFT JOIN tarrif_plan AS p ON cdr.dstchannel REGEXP p.trunkname LEFT JOIN pin_codes as pc ON (pc.name = cdr.userfield) LEFT JOIN departments as d ON cdr.accountcode = pc.name WHERE cdr.dst != 's' AND cdr.dst != '' AND  cdr.dstchannel != '' AND disposition = 'ANSWERED' and p.planname is not null and     cdr.accountcode = pc.name AND pc.name != '' and department = ''  AND 1 $where group by pc.name order by costs DESC", $search);
               } elseif ($_GET['department'] == 'Extension Missing') {
                  $row=db_query("SELECT count(cdr.uniqueid) as count, TIME_FORMAT(SEC_TO_TIME(sum(t.duration)), '%H:%i:%s') as totaltime, TIME_FORMAT(SEC_TO_TIME(sum(t.billsec)), '%H:%i:%s') as talktime, ROUND((sum(t.billsec*t.cost)), 2) as costs, cdr.accountcode from cdr LEFT JOIN trunkcost as t using (uniqueid) LEFT JOIN tarrif_plan AS p ON cdr.dstchannel REGEXP p.trunkname LEFT JOIN pin_codes as pc ON (pc.name = cdr.userfield) LEFT JOIN departments as d ON cdr.accountcode REGEXP pc.name WHERE cdr.dst != 's' AND cdr.dst != '' AND  cdr.dstchannel != '' AND disposition = 'ANSWERED' and p.planname is not null AND cdr.accountcode != '' and department is null  AND 1 $where group by pc.name order by costs DESC", $search);
               } else {
                  $row=db_query("SELECT count(cdr.uniqueid) as count, TIME_FORMAT(SEC_TO_TIME(sum(t.duration)), '%H:%i:%s') as totaltime, TIME_FORMAT(SEC_TO_TIME(sum(t.billsec)), '%H:%i:%s') as talktime, ROUND((sum(t.billsec*t.cost)), 2) as costs, pc.name from cdr LEFT JOIN trunkcost as t using (uniqueid) LEFT JOIN tarrif_plan AS p ON cdr.dstchannel REGEXP p.trunkname LEFT JOIN pin_codes as pc ON (pc.name = cdr.userfield) LEFT JOIN departments as d ON cdr.accountcode = pc.name WHERE cdr.dst != 's' AND cdr.dst != '' AND  cdr.dstchannel != '' AND disposition = 'ANSWERED' and p.planname is not null and cdr.accountcode = pc.name AND cdr.accountcode != '' AND 1 $where and department = ? group by pc.name order by costs DESC", $search);
	       }

               echo "<h4>All Accountcodes, Specified departments, All plans, Summary: (" . $_GET['department'] . ")</h4>";
               echo "<div id='dvData4'>";
               echo "<table width=100% >";
               echo "<tr><th>Accountcode</th><th align='right'>Count</th><th align='right'>Total Time</th><th align='right'>Talk Time</th><th align='right'>Cost</th></tr>";
               foreach($row as $key => $value)
               {
                  echo "<tr>";
                  if (isset($_GET['startdate'])) {
                     echo "<td width=40% align='center'><a href='index.php?mod=depspecificaccountcode&accountcode=" . $value['name'] . "&department=" . $_GET['department'] . "&startdate=" . $_GET['startdate'] . "&enddate=" . $_GET['enddate'] . "'>" . $value['name'] . "</a></td>";
                  } else {
                     echo "<td width=40% align='center'><a href='index.php?mod=depspecificaccountcode&accountcode=" . $value['name'] . "&department=" . $_GET['department'] . "&todate=" . $_GET['todate'] . "'>" . $value['name'] . "</a></td>";
                  }
                  echo "<td align='right'>" . $value['count'] . "</td>";
                  echo "<td align='right'>" . $value['totaltime'] . "</td>";
                  echo "<td align='right'>" . $value['talktime'] . "</td>";
                  echo "<td align='right'>R " . $value['costs'] . "</td>";
                  echo "</tr>";
               }

               if ($_GET['department'] == 'Unknown' ) {
                  $row=db_query("SELECT count(cdr.uniqueid) as count, TIME_FORMAT(SEC_TO_TIME(sum(t.duration)), '%H:%i:%s') as totaltime, TIME_FORMAT(SEC_TO_TIME(sum(t.billsec)), '%H:%i:%s') as talktime, ROUND((sum(t.billsec*t.cost)), 2) as costs, pc.name from cdr LEFT JOIN trunkcost as t using (uniqueid) LEFT JOIN tarrif_plan AS p ON cdr.dstchannel REGEXP p.trunkname LEFT JOIN pin_codes as pc ON (pc.name = cdr.userfield) LEFT JOIN departments as d ON cdr.accountcode = pc.name WHERE cdr.dst != 's' AND cdr.dst != '' AND  cdr.dstchannel != '' AND disposition = 'ANSWERED' and p.planname is not null and     cdr.accountcode = pc.name AND pc.name != '' and department = ''  AND 1 $where order by costs DESC", $search);
               } elseif ($_GET['department'] == 'Extension Missing') {
                  $row=db_query("SELECT count(cdr.uniqueid) as count, TIME_FORMAT(SEC_TO_TIME(sum(t.duration)), '%H:%i:%s') as totaltime, TIME_FORMAT(SEC_TO_TIME(sum(t.billsec)), '%H:%i:%s') as talktime, ROUND((sum(t.billsec*t.cost)), 2) as costs, cdr.accountcode from cdr LEFT JOIN trunkcost as t using (uniqueid) LEFT JOIN tarrif_plan AS p ON cdr.dstchannel REGEXP p.trunkname LEFT JOIN pin_codes as pc ON (pc.name = cdr.userfield) LEFT JOIN departments as d ON cdr.accountcode REGEXP pc.name WHERE cdr.dst != 's' AND cdr.dst != '' AND  cdr.dstchannel != '' AND disposition = 'ANSWERED' and p.planname is not null AND cdr.accountcode != '' and department is null  AND 1 $where order by costs DESC", $search);
               } else {
                  $row=db_query("SELECT count(cdr.uniqueid) as count, TIME_FORMAT(SEC_TO_TIME(sum(t.duration)), '%H:%i:%s') as totaltime, TIME_FORMAT(SEC_TO_TIME(sum(t.billsec)), '%H:%i:%s') as talktime, ROUND((sum(t.billsec*t.cost)), 2) as costs, pc.name from cdr LEFT JOIN trunkcost as t using (uniqueid) LEFT JOIN tarrif_plan AS p ON cdr.dstchannel REGEXP p.trunkname LEFT JOIN pin_codes as pc ON (pc.name = cdr.userfield) LEFT JOIN departments as d ON cdr.accountcode = pc.name WHERE cdr.dst != 's' AND cdr.dst != '' AND  cdr.dstchannel != '' AND disposition = 'ANSWERED' and p.planname is not null and cdr.accountcode = pc.name AND cdr.accountcode != '' AND 1 $where and department = ? order by costs DESC", $search);
	       }

               echo "<tr><td></td><td></td><td></td><td></td><td></td></tr><tr>";
               echo "<td width=40% align='center'>Total</td>";
               echo "<td align='right'>" . $row[0]['count'] . "</td>";
               echo "<td align='right'>" . $row[0]['totaltime'] . "</td>";
               echo "<td align='right'>" . $row[0]['talktime'] . "</td>";
               echo "<td align='right'>R " . $row[0]['costs'] . "</td>";
               echo "</tr>";

               echo "</table>";
               echo "</div>";
               echo "<a href='#' class='export4'  style='font-size: 12px; background: none'><img class='' src='img/export-icon.png' alt='CSV Export' height='30'></a>";

//End of all extensions

            }
}

//function departments() {

//            $depresult=mysql_query("SELECT department from departments WHERE department != '' group by department");
 
//            echo "<table width=100%><tr><td ><h1>Department List:</td></h1><td align='right'><h1><a href='index.php?mod=depnew'>Add New</a></hr></td></tr></table>";
//            echo "<table width=100% >";               
//            echo "<th align='left' width='85%' height='20' ><strong>Name</strong></th><th align='center' width='85%' height='20' ><strong>Edit</strong></th>";

//            while($deprow = mysql_fetch_array($depresult))
//            {
//               echo "<tr>";
//               echo "<td>" . $deprow[0] . "</td>";
//               echo "<td align='center'><a href='index.php?mod=edit&department=" . $deprow[0] . "' title=''><img src=images/edit.png></a></td>";
//               echo "</tr>";
//            }
//            echo "</table>";

//}

//function edit() {

//            $depresult=mysql_query("SELECT department from departments WHERE department = '" . $_GET[department] . "' group by department");
 
//            echo "<h1>Edit Department: (" . $_GET[department]. ")</h1>\n";
//            echo "<table width=100% >\n";               
//            echo "<th align='left' width='35%' ></th><th align='center' width='35%'height='20' ></th><th></th>\n";

//            while($deprow = mysql_fetch_array($depresult))
//            {
//               echo "<tr></tr>";
//               echo "<tr>";
//               echo "<td align='center' height='40'>Name: </td><td><input type='text' name='depname' value='" . $deprow[0] . "'></td><td></td>";
//               echo "</tr>";

//               $row=db_query("SELECT name, callerid from sip LEFT JOIN departments ON name = exten WHERE department ='" . $deprow[0] . "' order by name");

//	               echo "<tr><th width=50% align='center'><strong>Name</strong></th><th align='left'><strong>Extension</strong></th><th><strong>Alter Extension</strong></th></tr>";

//               while($row = mysql_fetch_array($result))
//               {
//                  echo "<tr>";
//                  echo "<td width=50% align='center'>" . $row[1] . "</td>";
//                  echo "<td align='left'>" . $row[0] . "</td>";
//                  echo "<td align='center'><input type='checkbox' name='" . $row[0] . "' value=''></td>";
//                  echo "</tr>";
//               }
//               echo "<tr><td></td><td></td><td></td></tr>";
       
//               $row=db_query("SELECT accountcode from accountcodes LEFT JOIN departments USING (accountcode) WHERE department = '" . $deprow[0] . "' ORDER BY accountcode");

//               echo "<tr><th width=50% align='center'><strong>Account Code</strong></th><th align='left'></th><th><strong>Alter Account code</strong></th></tr>";

//               while($row = mysql_fetch_array($result))
//               {
//                  echo "<tr>";
//                  echo "<td width=50% align='center'>" . $row[0] . "</td>";
//                  echo "<td></td>";
//                  echo "<td width=50% align='center'><input type='checkbox' name='" . $row[0] . "' value=''></td>";
//                  echo "</tr>";
//               }
//            }

//            echo "<tr><td align='right'><button type='button'>Submit</td><td></td><td></td></tr>";
//            echo "</table>";

//}

//function depnew() {

//            echo "<h1>New Department:</h1>\n";
//            echo "<table width=100% bgcolor='lightblue'>\n";
//            echo "<th align='left' width='35%' bgcolor='#33ccff'></th><th align='center' width='35%'height='20' bgcolor='#33ccff'></th>\n";

//            echo "<tr><td><br></td></tr>";
//            echo "<tr>";
//            echo "<td align='center' height='40'>Name: </td><td><input type='text' name='depname' value='" . $deprow[0] . "'></td>";
//            echo "</tr>";

//            echo "<tr><td align='right'><button type='button'>Submit</td></tr>";
//            echo "</table>";

//}
?>
