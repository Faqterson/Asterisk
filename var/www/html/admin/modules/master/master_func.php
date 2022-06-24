<?php      
function master() {
   $today = date("Y-m-d 00:00:00");

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
      <form action='index.php?mod=master' method='post'>
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
            <td align="left">&nbsp;</td>
            <td align="left"><input name="submit" class="button" value="Search" type="submit"></td>
         </tr>
         </tbody>
         </table>
      </form>
   </div>

<?php
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
         

//NEW
            $row=db_query("SELECT 
                 count(cdr.uniqueid) as count, TIME_FORMAT(SEC_TO_TIME(sum(CASE WHEN t.duration is NULL THEN 0 ELSE t.duration END)), '%H:%i:%s') as totaltime, 
                 TIME_FORMAT(SEC_TO_TIME(sum(CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END)), '%H:%i:%s') as talktime, 
                 ROUND((sum((CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END)*(CASE WHEN t.cost is NULL THEN 0 ELSE t.cost END))), 2) as costs, 
                 CASE WHEN t.destination_name is NULL THEN 'Unknown' ELSE t.destination_name END as destname 
               FROM cdr 
                 LEFT JOIN trunkcost as t using (uniqueid) 
                 LEFT JOIN tarrif_plan AS p ON cdr.dstchannel REGEXP p.trunkname 
               WHERE 
                 cdr.dst != 's' AND cdr.dst != '' AND  
                 cdr.dstchannel != '' AND 
                 disposition = 'ANSWERED' AND 
                 p.planname is not null AND 
		 1 $where 
               GROUP BY destination_name 
               ORDER BY costs DESC", $search);

            echo "<h4>All Plans, Summary:</h4>";
            echo "<div id='dvData1'>";
            echo "<table width=100% >";
            echo "<tr><th>Destination</th><th align='right'>Count</th><th align='right'>Total Time</th><th align='right'>Talk Time</th><th align='right'>Cost</th></tr>";
            foreach($row as $key => $value)
            {
                echo "<tr>";
                if (isset($_POST['startdate'])) {
                   echo "<td width=40% align='center'><a href='index.php?mod=allplan&destination=" . $value['destname'] . "&startdate=" . $_POST['startdate'] . "&enddate=" . $_POST['enddate'] . "'>" . $value['destname'] . "</a></td>";
                } else {
                   echo "<td width=40% align='center'><a href='index.php?mod=allplan&destination=" . $value['destname'] . "&today=" . $today . "'>" . $value['destname'] . "</a></td>";                   
                }   
                echo "<td align='right'>" . $value['count'] . "</td>";
                echo "<td align='right'>" . $value['totaltime'] . "</td>";
                echo "<td align='right'>" . $value['talktime'] . "</td>";
                echo "<td align='right'>R " . $value['costs'] . "</td>";

 	        echo "</tr>";
            }
            $row=db_query("SELECT
                  count(cdr.uniqueid) as count, TIME_FORMAT(SEC_TO_TIME(sum(CASE WHEN t.duration is NULL THEN 0 ELSE t.duration END)), '%H:%i:%s') as totaltime,
                  TIME_FORMAT(SEC_TO_TIME(sum(CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END)), '%H:%i:%s') as talktime,
                  ROUND((sum((CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END)*(CASE WHEN t.cost is NULL THEN 0 ELSE t.cost END))), 2) as costs
                FROM cdr
                  LEFT JOIN trunkcost as t using (uniqueid)
                  LEFT JOIN tarrif_plan AS p ON cdr.dstchannel REGEXP p.trunkname
                WHERE
                  cdr.dst != 's' AND cdr.dst != '' AND  cdr.dstchannel != '' AND
                  disposition = 'ANSWERED' AND
                  p.planname is not null AND
		  1 $where
                ORDER BY costs DESC", $search);

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
               $row=db_query("SELECT count(cdr.uniqueid) as count, TIME_FORMAT(SEC_TO_TIME(sum(CASE WHEN t.duration is NULL THEN 0 ELSE t.duration END)), '%H:%i:%s') as totaltime, TIME_FORMAT(SEC_TO_TIME(sum(CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END)), '%H:%i:%s') as talktime, ROUND((sum((CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END)*(CASE WHEN t.cost is NULL THEN 0 ELSE t.cost END))), 2) as costs, CASE WHEN t.destination_name is NULL THEN 'Unknown' ELSE t.destination_name END as destname from cdr LEFT JOIN trunkcost as t using (uniqueid) LEFT JOIN tarrif_plan AS p ON cdr.dstchannel REGEXP p.trunkname WHERE cdr.dst != 's' AND cdr.dst != '' AND  cdr.dstchannel != '' AND disposition = 'ANSWERED' and p.planname is not null and p.planname = '" . $planvalue['planname'] . "' AND 1 $where group by destination_name ORDER BY costs DESC", $search);

               echo "<h4>Specified plan, Summary: (" . $planvalue['planname'] . ")</h4>";
               echo "<div id='dvData2'>";
               echo "<table width=100% >";
               echo "<tr><th>Destination</th><th align='right'>Count</th><th align='right'>Total Time</th><th align='right'>Talk Time</th><th align='right'>Cost</th></tr>";
               foreach($row as $key => $value)
               {
                  echo "<tr>";
                  if (isset($_POST['startdate'])) {
                     echo "<td width=40% align='center'><a href='index.php?mod=specificplan&destination=" . $value['destname'] . "&plan=" . $planvalue['planname'] . "&startdate=" . $_POST['startdate'] . "&enddate=" . $_POST['enddate'] . "'>" . $value['destname'] . "</a></td>";
                  } else {
                     echo "<td width=40% align='center'><a href='index.php?mod=specificplan&destination=" . $value['destname'] . "&plan=" . $planvalue['planname'] . "&today=" . $today . "'>" . $value['destname'] . "</a></td>";
                  }
                  echo "<td align='right'>" . $value['count'] . "</td>";
                  echo "<td align='right'>" . $value['totaltime'] . "</td>";
                  echo "<td align='right'>" . $value['talktime'] . "</td>";
                  echo "<td align='right'>R " . $value['costs'] . "</td>";
 	          echo "</tr>";
               }
               $row=db_query("SELECT count(cdr.uniqueid) as count, TIME_FORMAT(SEC_TO_TIME(sum(CASE WHEN t.duration is NULL THEN 0 ELSE t.duration END)), '%H:%i:%s') as totaltime, TIME_FORMAT(SEC_TO_TIME(sum(CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END)), '%H:%i:%s') as talktime, ROUND((sum((CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END)*(CASE WHEN t.cost is NULL THEN 0 ELSE t.cost END))), 2) as costs from cdr LEFT JOIN trunkcost as t using (uniqueid) LEFT JOIN tarrif_plan AS p ON cdr.dstchannel REGEXP p.trunkname WHERE cdr.dst != 's' AND cdr.dst != '' AND  cdr.dstchannel != '' AND disposition = 'ANSWERED' and p.planname is not null and p.planname = '" . $planvalue['planname'] . "' AND 1 $where ORDER BY costs DESC", $search);

               echo "<tr><td></td><td></td><td></td><td></td><td></td></tr><tr>";
               echo "<td width=40% align='center'>Total</td>";
               echo "<td align='right'>" . $row[0]['count'] . "</td>";
               echo "<td align='right'>" . $row[0]['totaltime'] . "</td>";
               echo "<td align='right'>" . $row[0]['talktime'] . "</td>";
               echo "<td align='right'>R " . $row[0]['costs'] . "</td>";

               echo "</table>";
               echo "</div>";
               echo "<a href='#' class='export2' style='font-size: 12px; background: none'><img class='' src='img/export-icon.png' alt='CSV Export' height='30'></a>";

            }
//End Of all plans

            $row=db_query("SELECT     
                 count(cdr.uniqueid) as count,
                 TIME_FORMAT(SEC_TO_TIME(sum(CASE WHEN t.duration is NULL THEN 0 ELSE t.duration END)), '%H:%i:%s') as totaltime,
                 TIME_FORMAT(SEC_TO_TIME(sum(CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END)), '%H:%i:%s') as talktime,
                 ROUND((sum((CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END)*(CASE WHEN t.cost is NULL THEN 0 ELSE t.cost END))), 2) as costs,
                 CASE WHEN d.department = '' then 'Unknown' ELSE (CASE WHEN d.department is null then 'Extension Missing' ELSE d.department END) END as depname  
            FROM cdr
            LEFT JOIN trunkcost as t using (uniqueid)
            LEFT JOIN tarrif_plan AS p ON cdr.dstchannel REGEXP p.trunkname
            LEFT JOIN departments as d ON SUBSTRING_INDEX(SUBSTRING_INDEX(cdr.channel, '-', 1), '/', -1) = d.sip
            WHERE
                 cdr.dst != 's' AND cdr.dst != '' AND  cdr.dstchannel != '' AND
                 disposition = 'ANSWERED' and
                 p.planname is not null and
                 (SUBSTRING_INDEX(cdr.channel, '-', 1) REGEXP d.sip or d.sip is null)  AND
                 1 $where 
            GROUP BY department
            ORDER BY costs DESC", $search);

            echo "<h4>All departments, All plans, Summary:</h4>";
            echo "<div id='dvData3'>";
            echo "<table width=100%>";
            echo "<tr><th>Department</th><th align='right'>Count</th><th align='right'>Total Time</th><th align='right'>Talk Time</th><th align='right'>Cost</th></tr>";
            foreach($row as $key => $value)
            {
                echo "<tr>";
                if (isset($_POST['startdate'])) {
                   echo "<td width=40% align='center'><a href='index.php?mod=allexten&department=" . $value['depname'] . "&startdate=" . $_POST['startdate'] . "&enddate=" . $_POST['enddate'] . "'>" . $value['depname'] . "</a></td>";
                } else {
                   echo "<td width=40% align='center'><a href='index.php?mod=allexten&department=" . $value['depname'] . "&today=" . $today . "'>" . $value['depname'] . "</a></td>";
                }
                echo "<td align='right'>" . $value['count'] . "</td>";
                echo "<td align='right'>" . $value['totaltime'] . "</td>";
                echo "<td align='right'>" . $value['talktime'] . "</td>";
                echo "<td align='right'>R " . $value['costs'] . "</td>";
 	        echo "</tr>";
            }

            $row=db_query("SELECT     count(cdr.uniqueid) as count,     TIME_FORMAT(SEC_TO_TIME(sum(CASE WHEN t.duration is NULL THEN 0 ELSE t.duration END)), '%H:%i:%s') as totaltime,     TIME_FORMAT(SEC_TO_TIME(sum(CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END)), '%H:%i:%s') as talktime,     ROUND((sum((CASE WHEN t.billsec is NULL THEN 0 ELSE t.billsec END)*(CASE WHEN t.cost is NULL THEN 0 ELSE t.cost END))), 2) as costs,     CASE WHEN d.department = '' then 'Unknown' ELSE (CASE WHEN d.department is null then 'Extension Missing' ELSE d.department END) END as depname  FROM cdr     LEFT JOIN trunkcost as t using (uniqueid)     LEFT JOIN tarrif_plan AS p ON cdr.dstchannel REGEXP p.trunkname     LEFT JOIN departments as d ON SUBSTRING_INDEX(SUBSTRING_INDEX(cdr.channel, '-', 1), '/', -1) = d.sip  WHERE     cdr.dst != 's' AND cdr.dst != '' AND  cdr.dstchannel != '' AND     disposition = 'ANSWERED' and     p.planname is not null and     (SUBSTRING_INDEX(cdr.channel, '-', 1) REGEXP d.sip or d.sip is null)  AND 1 $where;", $search);

            echo "<tr><td></td><td></td><td></td><td></td><td></td></tr><tr>";
            echo "<td width=40% align='center'>Total</td>";
            echo "<td align='right'>" . $row[0]['count'] . "</td>";
            echo "<td align='right'>" . $row[0]['totaltime'] . "</td>";
            echo "<td align='right'>" . $row[0]['talktime'] . "</td>";
            echo "<td align='right'>R " . $row[0]['costs'] . "</td>";

            echo "</table>";
            echo "</div>";
            echo "<a href='#' class='export3' style='font-size: 12px; background: none'><img class='' src='img/export-icon.png' alt='CSV Export' height='30'></a>";
}
?>

