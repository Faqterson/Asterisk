<?php      
function allplan() {
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

//NEW
            if ($_GET['destination'] == 'Unknown' ) {
               $row=db_query("SELECT dst, TIME_FORMAT(SEC_TO_TIME(CASE WHEN t.duration is NULL THEN 0 ELSE t.duration END), '%H:%i:%s') as totaltime, TIME_FORMAT(SEC_TO_TIME(CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END), '%H:%i:%s') as talktime, ROUND(((CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END)*(CASE WHEN t.cost is NULL THEN 0 ELSE t.cost END)), 2) as costs, channel from cdr LEFT JOIN trunkcost as t using (uniqueid) LEFT JOIN tarrif_plan AS p ON cdr.dstchannel REGEXP p.trunkname WHERE cdr.dst != 's' AND cdr.dst != '' AND  cdr.dstchannel != '' AND disposition = 'ANSWERED' and p.planname is not null and t.destination_name is NULL AND 1 $where order by costs DESC", $search);
            } else {
               array_push($search,$_GET['destination']);
               $row=db_query("SELECT dst, TIME_FORMAT(SEC_TO_TIME(CASE WHEN t.duration is NULL THEN 0 ELSE t.duration END), '%H:%i:%s') as totaltime, TIME_FORMAT(SEC_TO_TIME(CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END), '%H:%i:%s') as talktime, ROUND(((CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END)*(CASE WHEN t.cost is NULL THEN 0 ELSE t.cost END)), 2) as costs, channel from cdr LEFT JOIN trunkcost as t using (uniqueid) LEFT JOIN tarrif_plan AS p ON cdr.dstchannel REGEXP p.trunkname WHERE cdr.dst != 's' AND cdr.dst != '' AND  cdr.dstchannel != '' AND disposition = 'ANSWERED' and p.planname is not null and 1 $where AND t.destination_name = ? order by costs DESC", $search);
            }

            echo "<h4>All Plans, Detailed: (" . $_GET[destination] . ")</h4>";
            echo "<div id='dvData1'>";
            echo "<table width=100%>";
            echo "<tr><th align='left'>Number</th><th>Source</th><th align='right'>Total Time</th><th align='right'>Talk Time</th><th align='right'>Cost</th></tr>";
            foreach($row as $key => $value)
            {
                echo "<tr>";
                echo "<td align='left'>" . $value['dst'] . "</td>";
                echo "<td width=40% align='center'>" . strstr($value['channel'], '-', true) . "</td>";
                echo "<td align='right'>" . $value['totaltime'] . "</td>";
                echo "<td align='right'>" . $value['talktime'] . "</td>";
                echo "<td align='right'>R " . $value['costs'] . "</td>";
 	        echo "</tr>";
            }
            echo "</table>";
            echo "</div>";
            echo "<a href='#' class='export1' style='font-size: 12px; background: none'><img class='' src='img/export-icon.png' alt='CSV Export' height='30'></a>";
}

function specificplan() {
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

//NEW
            if ($_GET['destination'] == 'Unknown' ) {
               array_push($search,$_GET['plan']);
               $row=db_query("SELECT dst, TIME_FORMAT(SEC_TO_TIME(CASE WHEN t.duration is NULL THEN 0 ELSE t.duration END), '%H:%i:%s') as totaltime, TIME_FORMAT(SEC_TO_TIME(CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END), '%H:%i:%s') as talktime, ROUND(((CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END)*(CASE WHEN t.cost is NULL THEN 0 ELSE t.cost END)), 2) as costs, cdr.channel from cdr LEFT JOIN trunkcost as t using (uniqueid) LEFT JOIN tarrif_plan AS p ON cdr.dstchannel REGEXP p.trunkname WHERE cdr.dst != 's' AND cdr.dst != '' AND  cdr.dstchannel != '' AND disposition = 'ANSWERED' and p.planname is not null and t.destination_name is NULL AND 1 $where AND p.planname = ? order by costs DESC", $search);
            } else {
               array_push($search,$_GET['destination'], $_GET['plan']);
               $row=db_query("SELECT dst, TIME_FORMAT(SEC_TO_TIME(CASE WHEN t.duration is NULL THEN 0 ELSE t.duration END), '%H:%i:%s') as totaltime, TIME_FORMAT(SEC_TO_TIME(CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END), '%H:%i:%s') as talktime, ROUND(((CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END)*(CASE WHEN t.cost is NULL THEN 0 ELSE t.cost END)), 2) as costs, cdr.channel from cdr LEFT JOIN trunkcost as t using (uniqueid) LEFT JOIN tarrif_plan AS p ON cdr.dstchannel REGEXP p.trunkname WHERE cdr.dst != 's' AND cdr.dst != '' AND  cdr.dstchannel != '' AND disposition = 'ANSWERED' and p.planname is not null AND 1 $where AND t.destination_name = ? AND p.planname = ? order by costs DESC", $search);
            }

            echo "<h4>" . $_GET[plan] . "</h4>";
            echo "<h4>Specific Plans, Detailed: (" . $_GET['destination'] . ")</h4>";
            echo "<div id='dvData1'>";
            echo "<table width=100% >";
            echo "<tr><th align='left'>Number</th><th>Source</th><th align='right'>Total Time</th><th align='right'>Talk Time</th><th align='right'>Cost</th></tr>";
            foreach($row as $key => $value)
            {
                echo "<tr>";
                echo "<td align='left'>" . $value['dst'] . "</td>";
                echo "<td width=40% align='center'>" . strstr($value['channel'], '-', true) . "</td>";
                echo "<td align='right'>" . $value['totaltime'] . "</td>";
                echo "<td align='right'>" . $value['talktime'] . "</td>";
                echo "<td align='right'>R " . $value['costs'] . "</td>";
 	        echo "</tr>";
            }
            echo "</table>";
            echo "</div>";
            echo "<a href='#' class='export1' style='font-size: 12px; background: none'><img class='' src='img/export-icon.png' alt='CSV Export' height='30'></a>";
}

function depallplan() {
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

//NEW
            if ($_GET['destination'] == 'Unknown' ) {
               if ($_GET['department'] == 'Unknown' ) {
                  $row=db_query("SELECT dst, TIME_FORMAT(SEC_TO_TIME(t.duration), '%H:%i:%s') as totaltime, TIME_FORMAT(SEC_TO_TIME(t.billsec), '%H:%i:%s') as talktime, ROUND((t.billsec*t.cost), 2) as costs, d.sip from cdr LEFT JOIN trunkcost as t using (uniqueid) LEFT JOIN tarrif_plan AS p ON cdr.dstchannel REGEXP p.trunkname LEFT JOIN departments as d ON SUBSTRING_INDEX(SUBSTRING_INDEX(cdr.channel, '-', 1), '/', -1) = d.sip WHERE cdr.dst != 's' AND cdr.dst != '' AND  cdr.dstchannel != '' AND disposition = 'ANSWERED' and p.planname is not null and     (SUBSTRING_INDEX(cdr.channel, '-', 1) REGEXP d.sip or d.sip is null) AND t.destination_name is NULL  AND 1 $where ORDER BY costs DESC", $search);
               } else {
                  array_push($search,$_GET['department']);
                  $row=db_query("SELECT dst, TIME_FORMAT(SEC_TO_TIME(t.duration), '%H:%i:%s') as totaltime, TIME_FORMAT(SEC_TO_TIME(t.billsec), '%H:%i:%s') as talktime, ROUND((t.billsec*t.cost), 2) as costs, d.sip from cdr LEFT JOIN trunkcost as t using (uniqueid) LEFT JOIN tarrif_plan AS p ON cdr.dstchannel REGEXP p.trunkname LEFT JOIN departments as d ON SUBSTRING_INDEX(SUBSTRING_INDEX(cdr.channel, '-', 1), '/', -1) = d.sip WHERE cdr.dst != 's' AND cdr.dst != '' AND  cdr.dstchannel != '' AND disposition = 'ANSWERED' and p.planname is not null and     (SUBSTRING_INDEX(cdr.channel, '-', 1) REGEXP d.sip or d.sip is null) AND t.destination_name is NULL AND 1 $where and department = ? ORDER BY costs DESC", $search);
               }
            } elseif ($_GET['department'] == 'Unknown' ) {
               array_push($search,$_GET['destination']);
               $row=db_query("SELECT cdr.dst, TIME_FORMAT(SEC_TO_TIME(t.duration), '%H:%i:%s') as totaltime, TIME_FORMAT(SEC_TO_TIME(t.billsec), '%H:%i:%s') as talktime, ROUND((t.billsec*t.cost), 2) as costs, SUBSTRING_INDEX(cdr.channel, '-', 1) from cdr LEFT JOIN trunkcost as t using (uniqueid) LEFT JOIN tarrif_plan AS p ON cdr.dstchannel REGEXP p.trunkname LEFT JOIN departments as d ON SUBSTRING_INDEX(SUBSTRING_INDEX(cdr.channel, '-', 1), '/', -1) = d.sip WHERE cdr.dst != 's' AND cdr.dst != '' AND  cdr.dstchannel != '' AND disposition = 'ANSWERED' and p.planname is not null and     (SUBSTRING_INDEX(cdr.channel, '-', 1) REGEXP d.sip or d.sip is null) AND 1 $where AND t.destination_name = ? ORDER BY costs DESC", $search);
            } elseif ($_GET['department'] == 'Extension Missing' ) {
               array_push($search,$_GET['destination']);
               $row=db_query("SELECT dst, TIME_FORMAT(SEC_TO_TIME(t.duration), '%H:%i:%s') as totaltime, TIME_FORMAT(SEC_TO_TIME(t.billsec), '%H:%i:%s') as talktime, ROUND((t.billsec*t.cost), 2) as costs, SUBSTRING_INDEX(cdr.channel, '-', 1) from cdr LEFT JOIN trunkcost as t using (uniqueid) LEFT JOIN tarrif_plan AS p ON cdr.dstchannel REGEXP p.trunkname LEFT JOIN departments as d ON SUBSTRING_INDEX(SUBSTRING_INDEX(cdr.channel, '-', 1), '/', -1) = d.sip WHERE cdr.dst != 's' AND cdr.dst != '' AND  cdr.dstchannel != '' AND disposition = 'ANSWERED' and p.planname is not null and     (SUBSTRING_INDEX(cdr.channel, '-', 1) REGEXP d.sip or d.sip is null) and department is null AND 1 $where AND t.destination_name = ? ORDER BY costs DESC", $search);
            } else {
               array_push($search,$_GET['destination'], $_GET['department']);
               $row=db_query("SELECT dst, TIME_FORMAT(SEC_TO_TIME(t.duration), '%H:%i:%s') as totaltime, TIME_FORMAT(SEC_TO_TIME(t.billsec), '%H:%i:%s') as talktime, ROUND((t.billsec*t.cost), 2) as costs, d.sip from cdr LEFT JOIN trunkcost as t using (uniqueid) LEFT JOIN tarrif_plan AS p ON cdr.dstchannel REGEXP p.trunkname LEFT JOIN departments as d ON SUBSTRING_INDEX(SUBSTRING_INDEX(cdr.channel, '-', 1), '/', -1) = d.sip WHERE cdr.dst != 's' AND cdr.dst != '' AND  cdr.dstchannel != '' AND disposition = 'ANSWERED' and p.planname is not null and     (SUBSTRING_INDEX(cdr.channel, '-', 1) REGEXP d.sip or d.sip is null) AND 1 $where AND t.destination_name = ? and department = ? ORDER BY costs DESC", $search);
            }

            echo "<h4>" . $_GET['destination'] . "</h4>";
            echo "<h4>All Plans, Detailed: (" . $_GET['destination'] . ")</h4>";
            echo "<div id='dvData1'>";
            echo "<table width=100%>";
            echo "<tr><th align='left'>Number</th><th>Extension</th><th align='right'>Total Time</th><th align='right'>Talk Time</th><th align='right'>Cost</th></tr>";
            foreach($row as $key => $value)
            {
                echo "<tr>";
                echo "<td align='left'>" . $value['dst'] . "</td>";
                echo "<td width=40% align='center'>" . $value['channel'] . "</td>";
                echo "<td align='right'>" . $value['totaltime'] . "</td>";
                echo "<td align='right'>" . $value['talktime'] . "</td>";
                echo "<td align='right'>R " . $value['costs'] . "</td>";
 	        echo "</tr>";
            }
            echo "</table>";
            echo "</div>";
            echo "<a href='#' class='export1' style='font-size: 12px; background: none'><img class='' src='img/export-icon.png' alt='CSV Export' height='30'></a>";

}

function depspecificplan() {
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

//NEW
            if ($_GET['destination'] == 'Unknown' ) {
               if ($_GET['department'] == 'Unknown' ) {
                  $row=db_query("SELECT cdr.dst, TIME_FORMAT(SEC_TO_TIME(t.duration), '%H:%i:%s') as totaltime, TIME_FORMAT(SEC_TO_TIME(t.billsec), '%H:%i:%s') as talktime, ROUND((t.billsec*t.cost), 2) as costs, d.sip from cdr LEFT JOIN trunkcost as t using (uniqueid) LEFT JOIN tarrif_plan AS p ON cdr.dstchannel REGEXP p.trunkname LEFT JOIN departments as d ON SUBSTRING_INDEX(SUBSTRING_INDEX(cdr.channel, '-', 1), '/', -1) = d.sip WHERE cdr.dst != 's' AND cdr.dst != '' AND  cdr.dstchannel != '' AND disposition = 'ANSWERED' and p.planname is not null AND  (SUBSTRING_INDEX(cdr.channel, '-', 1) REGEXP d.sip or d.sip is null) AND p.planname = '" . $_GET[plan] . "' AND t.destination_name is null  AND 1 $where ORDER BY costs DESC", $search);
               } else {
                  $row=db_query("SELECT cdr.dst, TIME_FORMAT(SEC_TO_TIME(t.duration), '%H:%i:%s') as totaltime, TIME_FORMAT(SEC_TO_TIME(t.billsec), '%H:%i:%s') as talktime, ROUND((t.billsec*t.cost), 2) as costs, d.sip from cdr LEFT JOIN trunkcost as t using (uniqueid) LEFT JOIN tarrif_plan AS p ON cdr.dstchannel REGEXP p.trunkname LEFT JOIN departments as d ON SUBSTRING_INDEX(SUBSTRING_INDEX(cdr.channel, '-', 1), '/', -1) = d.sip WHERE cdr.dst != 's' AND cdr.dst != '' AND  cdr.dstchannel != '' AND disposition = 'ANSWERED' and p.planname is not null and   and department = '" . $_GET['destination'] . "'  (SUBSTRING_INDEX(cdr.channel, '-', 1) REGEXP d.sip or d.sip is null) AND p.planname = '" . $_GET[plan] . "' AND t.destination_name is null  AND 1 $where ORDER BY costs DESC", $search);
               }
            } elseif ($_GET['destination'] == 'Unknown' ) {
               $row=db_query("SELECT cdr.dst, TIME_FORMAT(SEC_TO_TIME(t.duration), '%H:%i:%s') as totaltime, TIME_FORMAT(SEC_TO_TIME(t.billsec), '%H:%i:%s') as talktime, ROUND((t.billsec*t.cost), 2) as costs, SUBSTRING_INDEX(cdr.channel, '-', 1) from cdr LEFT JOIN trunkcost as t using (uniqueid) LEFT JOIN tarrif_plan AS p ON cdr.dstchannel REGEXP p.trunkname LEFT JOIN departments as d ON SUBSTRING_INDEX(SUBSTRING_INDEX(cdr.channel, '-', 1), '/', -1) = d.sip WHERE cdr.dst != 's' AND cdr.dst != '' AND  cdr.dstchannel != '' AND disposition = 'ANSWERED' and p.planname is not null and     (SUBSTRING_INDEX(cdr.channel, '-', 1) REGEXP d.sip or d.sip is null) AND p.planname = '" . $_GET[plan] . "' AND t.destination_name = '" . $_GET[destination] . "'  AND 1 $where ORDER BY costs DESC");               
            } elseif ($_GET['destination'] == 'Extension Missing' ) {
               $row=db_query("SELECT cdr.dst, TIME_FORMAT(SEC_TO_TIME(t.duration), '%H:%i:%s') as totaltime, TIME_FORMAT(SEC_TO_TIME(t.billsec), '%H:%i:%s') as talktime, ROUND((t.billsec*t.cost), 2) as costs, SUBSTRING_INDEX(cdr.channel, '-', 1) from cdr LEFT JOIN trunkcost as t using (uniqueid) LEFT JOIN tarrif_plan AS p ON cdr.dstchannel REGEXP p.trunkname LEFT JOIN departments as d ON SUBSTRING_INDEX(SUBSTRING_INDEX(cdr.channel, '-', 1), '/', -1) = d.sip WHERE cdr.dst != 's' AND cdr.dst != '' AND  cdr.dstchannel != '' AND disposition = 'ANSWERED' and p.planname is not null and     (SUBSTRING_INDEX(cdr.channel, '-', 1) REGEXP d.sip or d.sip is null) and department is null AND p.planname = '" . $_GET[plan] . "' AND t.destination_name = '" . $_GET[destination] . "'  AND 1 $where ORDER BY costs DESC");               
            } else {
               $row=db_query("SELECT cdr.dst, TIME_FORMAT(SEC_TO_TIME(t.duration), '%H:%i:%s') as totaltime, TIME_FORMAT(SEC_TO_TIME(t.billsec), '%H:%i:%s') as talktime, ROUND((t.billsec*t.cost), 2) as costs, d.sip from cdr LEFT JOIN trunkcost as t using (uniqueid) LEFT JOIN tarrif_plan AS p ON cdr.dstchannel REGEXP p.trunkname LEFT JOIN departments as d ON SUBSTRING_INDEX(SUBSTRING_INDEX(cdr.channel, '-', 1), '/', -1) = d.sip WHERE cdr.dst != 's' AND cdr.dst != '' AND  cdr.dstchannel != '' AND disposition = 'ANSWERED' and p.planname is not null and     (SUBSTRING_INDEX(cdr.channel, '-', 1) REGEXP d.sip or d.sip is null) and department = '" . $_GET['destination'] . "' AND p.planname = '" . $_GET[plan] . "' AND t.destination_name = '" . $_GET[destination] . "'  AND 1 $where ORDER BY costs DESC");
            }

            echo "<h4>" . $_GET[plan] . "</h4>";
            echo "<h4>Specific Plans, Detailed: (" . $_GET['destination'] . " - " . $_GET[destination] . " - " . $_GET[plan] . ")</h4>";
            echo "<div id='dvData1'>";
            echo "<table width=100% >";
            echo "<tr><th align='left'>Number</th><th>Extension</th><th align='right'>Total Time</th><th align='right'>Talk Time</th><th align='right'>Cost</th></tr>";
            while($row = mysql_fetch_array($result))
            {
                echo "<tr>";
                echo "<td align='left'>" . $row[0] . "</td>";
                echo "<td width=40% align='center'>" . $row[4] . "</td>";
                echo "<td align='right'>" . $row[1] . "</td>";
                echo "<td align='right'>" . $row[2] . "</td>";
                echo "<td align='right'>R " . $row[3] . "</td>";
 	        echo "</tr>";
            }
            echo "</table>";
            echo "</div>";
            echo "<a href='#' class='export1' style='font-size: 12px; background: none'><img class='' src='img/export-icon.png' alt='CSV Export' height='30'></a>";

}

function depspecificexten() {
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

//NEW
            if ($_GET['department'] == 'Unknown' ) {
               $row=db_query("SELECT cdr.dst, calldate, TIME_FORMAT(SEC_TO_TIME(t.duration), '%H:%i:%s') as totaltime, TIME_FORMAT(SEC_TO_TIME(t.billsec), '%H:%i:%s') as talktime, ROUND((t.billsec*t.cost), 2) as costs, t.destination_name from cdr LEFT JOIN trunkcost as t using (uniqueid) LEFT JOIN tarrif_plan AS p ON cdr.dstchannel REGEXP p.trunkname LEFT JOIN departments as d ON SUBSTRING_INDEX(SUBSTRING_INDEX(cdr.channel, '-', 1), '/', -1) = d.sip WHERE cdr.dst != 's' AND cdr.dst != '' AND  cdr.dstchannel != '' AND disposition = 'ANSWERED' and p.planname is not null and     (SUBSTRING_INDEX(cdr.channel, '-', 1) REGEXP d.sip or d.sip is null) AND d.sip != '' and d.sip = '" . $_GET['extension'] . "'  AND 1 $where order by costs DESC ", $search);
            } elseif ($_GET['department'] == 'Extension Missing' ) {
               $row=db_query("SELECT cdr.dst, calldate, TIME_FORMAT(SEC_TO_TIME(t.duration), '%H:%i:%s') as totaltime, TIME_FORMAT(SEC_TO_TIME(t.billsec), '%H:%i:%s') as talktime, ROUND((t.billsec*t.cost), 2) as costs, t.destination_name from cdr LEFT JOIN trunkcost as t using (uniqueid) LEFT JOIN tarrif_plan AS p ON cdr.dstchannel REGEXP p.trunkname LEFT JOIN departments as d ON SUBSTRING_INDEX(SUBSTRING_INDEX(cdr.channel, '-', 1), '/', -1) = d.sip WHERE cdr.dst != 's' AND cdr.dst != '' AND  cdr.dstchannel != '' AND disposition = 'ANSWERED' and p.planname is not null and     (SUBSTRING_INDEX(cdr.channel, '-', 1) REGEXP d.sip or d.sip is null) and department is null and cdr.channel REGEXP '" . $_GET['extension'] . "'  AND 1 $where order by costs DESC ", $search);
            } else {
               $row=db_query("SELECT cdr.dst, calldate, TIME_FORMAT(SEC_TO_TIME(CASE WHEN t.duration is NULL THEN 0 ELSE t.duration END), '%H:%i:%s') as totaltime, TIME_FORMAT(SEC_TO_TIME(CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END), '%H:%i:%s') as talktime, ROUND(((CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END)*(CASE WHEN t.cost is NULL THEN 0 ELSE t.cost END)), 2) as costs, CASE WHEN t.destination_name is NULL THEN 'Unknown' ELSE t.destination_name END as destname from cdr LEFT JOIN trunkcost as t using (uniqueid) LEFT JOIN tarrif_plan AS p ON cdr.dstchannel REGEXP p.trunkname LEFT JOIN departments as d ON SUBSTRING_INDEX(SUBSTRING_INDEX(cdr.channel, '-', 1), '/', -1) = d.sip WHERE cdr.dst != 's' AND cdr.dst != '' AND  cdr.dstchannel != '' AND disposition = 'ANSWERED' and p.planname is not null and     (SUBSTRING_INDEX(cdr.channel, '-', 1) REGEXP d.sip or d.sip is null) AND d.sip != '' and department = '" . $_GET['department'] . "' and d.sip = '" . $_GET['extension'] . "'  AND 1 $where order by costs DESC", $search);
            }

            echo "<h4>" . $_GET['extension'] . "</h4>";
            echo "<h4>All Plans, Detailed: (" . $_GET['department'] . " - " . $_GET['extension'] . ")</h4>";
            echo "<div id='dvData1'>";
            echo "<table width=100% >";
            echo "<tr><th align='left'>Number</th><th align='right'>Calldate</th><th align='center'>Destination</th><th align='right'>Total Time</th><th align='right'>Talk Time</th><th align='right'>Cost</th></tr>";
            foreach($row as $key => $value)
            {
                echo "<tr>";
                echo "<td align='left'>" . $value['dst'] . "</td>";
                echo "<td align='right'>" . $value['calldate'] . "</td>";
                echo "<td width=40% align='center'>" . $value['destination_name'] . "</td>";
                echo "<td align='right'>" . $value['totaltime'] . "</td>";
                echo "<td align='right'>" . $value['talktime'] . "</td>";
                echo "<td align='right'>R " . $value['costs'] . "</td>";
 	        echo "</tr>";
            }
            echo "</table>";
            echo "</div>";
            echo "<a href='#' class='export1' style='font-size: 12px; background: none'><img class='' src='img/export-icon.png' alt='CSV Export' height='30'></a>";

}

function depspecificaccountcode() {
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

//NEW
            if ($_GET['destination'] == 'Unknown' ) {
               $row=db_query("SELECT cdr.dst, TIME_FORMAT(SEC_TO_TIME(t.duration), '%H:%i:%s') as totaltime, TIME_FORMAT(SEC_TO_TIME(t.billsec), '%H:%i:%s') as talktime, ROUND((t.billsec*t.cost), 2) as costs, cdr.channel, t.destination_name from cdr LEFT JOIN trunkcost as t using (uniqueid) LEFT JOIN tarrif_plan AS p ON cdr.dstchannel REGEXP p.trunkname LEFT JOIN departments as d USING (accountcode) WHERE cdr.dst != 's' AND cdr.dst != '' AND  cdr.dstchannel != '' AND disposition = 'ANSWERED' and p.planname is not null and     cdr.accountcode = d.accountcode and department = '' and d.accountcode = '" . $_GET[accountcode] . "'  AND 1 $where order by costs DESC", $search);
            } elseif ($_GET['destination'] == 'Extension Missing' ) {
               $row=db_query("SELECT cdr.dst, TIME_FORMAT(SEC_TO_TIME(t.duration), '%H:%i:%s') as totaltime, TIME_FORMAT(SEC_TO_TIME(t.billsec), '%H:%i:%s') as talktime, ROUND((t.billsec*t.cost), 2) as costs, SUBSTRING_INDEX(cdr.channel, '-', 1), t.destination_name from cdr LEFT JOIN trunkcost as t using (uniqueid) LEFT JOIN tarrif_plan AS p ON cdr.dstchannel REGEXP p.trunkname LEFT JOIN departments as d ON cdr.accountcode REGEXP d.accountcode WHERE cdr.dst != 's' AND cdr.dst != '' AND  cdr.dstchannel != '' AND disposition = 'ANSWERED' and p.planname is not null and     (SUBSTRING_INDEX(cdr.channel, '-', 1) REGEXP d.sip or d.sip is null) and department is null and cdr.accountcode REGEXP '" . $_GET[accountcode] . "'  AND 1 $where order by costs DESC ", $search);
            } else {
               $row=db_query("SELECT cdr.dst, TIME_FORMAT(SEC_TO_TIME(t.duration), '%H:%i:%s') as totaltime, TIME_FORMAT(SEC_TO_TIME(t.billsec), '%H:%i:%s') as talktime, ROUND((t.billsec*t.cost), 2) as costs, SUBSTRING_INDEX(cdr.channel, '-', 1), t.destination_name from cdr LEFT JOIN trunkcost as t using (uniqueid) LEFT JOIN tarrif_plan AS p ON cdr.dstchannel REGEXP p.trunkname LEFT JOIN departments as d USING (accountcode) WHERE cdr.dst != 's' AND cdr.dst != '' AND  cdr.dstchannel != '' AND disposition = 'ANSWERED' and p.planname is not null and     cdr.accountcode = d.accountcode and department = '" . $_GET['department'] . "' and d.accountcode = '" . $_GET[accountcode] . "'  AND 1 $where order by costs DESC", $search);
            }

            echo "<h4>" . $_GET[accountcode] . "</h4>";
            echo "<h4>All Plans, Detailed: (" . $_GET['destination'] . " - " . $_GET[accountcode] . ")</h4>";
            echo "<div id='dvData1'>";
            echo "<table width=100%>";
            echo "<tr><th align='left'>Number</th><th align='center'>Source</th><th align='center'>Destination</th><th align='right'>Total Time</th><th align='right'>Talk Time</th><th align='right'>Cost</th></tr>";
            while($row = mysql_fetch_array($result))
            {
                echo "<tr>";
                echo "<td align='left'>" . $row[0] . "</td>";
                echo "<td align='center'>" . $row[4] . "</td>";
                echo "<td width=40% align='center'>" . $row[5] . "</td>";
                echo "<td align='right'>" . $row[1] . "</td>";
                echo "<td align='right'>" . $row[2] . "</td>";
                echo "<td align='right'>R " . $row[3] . "</td>";
 	        echo "</tr>";
            }
            echo "</table>";
            echo "</div>";
            echo "<a href='#' class='export1' style='font-size: 12px; background: none'><img class='' src='img/export-icon.png' alt='CSV Export' height='30'></a>";

}
?>

