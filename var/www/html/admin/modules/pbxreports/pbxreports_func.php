<?php

function sippeers() {
   $socket = fsockopen('localhost', 5038, $errnum, $errdesc) or die('Connection to host failed');
   fputs($socket, "Action: login\r\n");
   fputs($socket, "Username: admin\r\n");
   fputs($socket, "Secret: VGJd#xx&m\r\n\r\n");
   fputs($socket, "Action: PJSIPShowContacts\r\n\r\n"); //PJSIPShowEndpoints\r\n\r\n");
   fputs($socket, "Action: Logoff\r\n\r\n");
   $count=0;
   $array = array();
   $i=0;

   while (!feof($socket)) {
      $j=0;
      $wrets = fgets($socket);
      if ($wrets != "\r\n") {
         $array[$count][$i]=str_replace("\r\n","",$wrets);
         $j++; 
         $token = '';
         $i++;
      } else {
         $i=0;
         $count++; 
      }
   }

   echo "<div class=''>";
   echo "<table>";
   echo "<th>Extension</th><th>Name</th><th>Department</th><th>User Agent</th><th>DND</th><th>Call Waiting</th><th>IP</th><th>Port</th><th>Status</th><th>Round Trip Sec</th>";

   foreach ($array as $key => $value) {
//      print_r($value);
      if ($array[$key][0] == "Event: ContactList") {
         echo "<tr align='center'>";

         $ext = explode(": ", $array[$key][9]) ;
         $row = db_query("SELECT ps_endpoints.id,callerid,dnd,callwaiting,user_agent,department FROM ext_features LEFT JOIN ps_endpoints ON ps_endpoints.id = extension LEFT JOIN ps_contacts ON ps_endpoints.id = endpoint LEFT JOIN departments ON ps_endpoints.id = sip WHERE extension = ?", array($ext[1]));
         if (isset($row[0]["id"])) {
            echo "<td align='left' style='padding-left: 65px'><a href = 'index.php?mod=modify_rt_extensions&id=".$row[0]["id"]."'>".$ext[1]."</a></td>";
            echo "<td align='left' style='padding-left: 65px'><a href = 'index.php?mod=modify_rt_extensions&id=".$row[0]["id"]."'>".$row[0]['callerid']."</a></td>";

            echo "<td>".$row[0]['department']."</td>";
            echo "<td>".$row[0]['user_agent']."</td>";
            echo "<td>".$row[0]['dnd']."</td>";
            echo "<td>".$row[0]['callwaiting']."</td>";
            $contact = explode(": ", $array[$key][12]);
            $ipport = explode("@", $contact[1]);
            $ip = explode(":", $ipport[1]);

            echo "<td>".$ip[0]."</td>";
            $port = explode(";", $ip[1]) ;
            echo "<td>".$port[0]."</td>";
            $status = explode(": ", $array[$key][17]) ;
            echo "<td>".$status[1]."</td>";
            (int)$rtt = explode(": ", $array[$key][18]) ;
            $rttms = $rtt[1]/1000;
            echo "<td>".$rttms." ms</td>";
            echo "</tr>";
         } 

//else {
//            echo "<td align='left' style='padding-left: 65px'>".$ext[1]."</td>";
//         }

      }
   }
   echo "</table>";
   fclose($socket);
}

function showtrunks() {

   echo "<div class='divheader'>PJSIP Trunks</div>";

   echo "<div class=''>";
   echo "<table>";
   echo "<th>Trunk</th><th>IP</th><th>Port</th><th>Status</th><th>Round Trip Sec</th>";

   $row=db_query("SELECT ps_auths.id from ps_auths LEFT JOIN ps_aors USING (id) LEFT JOIN ps_registrations USING (id) WHERE server_uri is not NULL ", array());

   foreach ($row as $trkey => $trvalue)
   {
      $socket = fsockopen('localhost', 5038, $errnum, $errdesc) or die('Connection to host failed');
      fputs($socket, "Action: login\r\n");
      fputs($socket, "Username: admin\r\n");
      fputs($socket, "Secret: VGJd#xx&m\r\n\r\n");
      fputs($socket, "Action: PJSIPShowEndpoint\r\n");
      fputs($socket, "Endpoint: ".$trvalue['id']."\r\n\r\n");
      fputs($socket, "Action: Logoff\r\n\r\n");
      $count=0; 
      $array = array();
      $i=0;

      while (!feof($socket)) {
         $j=0;
         $wrets = fgets($socket);
         if ($wrets != "\r\n") {
            $array[$count][$i]=str_replace("\r\n","",$wrets);
            $j++;
            $token = '';
            $i++;
         } else {
            $i=0;
            $count++;
         }
      }


      foreach ($array as $key => $value) {
//         print_r($value);

         if ($array[$key][0] == "Event: AorDetail") {
            $contact = explode(": ", $array[$key][11]);
            $ipport = explode("/", $contact[1]);
            $ip = explode(":", $ipport[1]);
            $ext = explode(": ", $array[$key][2]) ;
         }

         if ($array[$key][0] == "Event: ContactStatusDetail") {
            echo "<tr align='center'>";
            echo "<td align='center'>".$ext[1]."</td>";

            echo "<td>".$ip[1]."</td>";
            echo "<td>".$ip[2]."</td>";
            $status = explode(": ", $array[$key][5]) ;
            echo "<td>".$status[1]."</td>";
            (int)$rtt = explode(": ", $array[$key][6]) ;
            $rttms = $rtt[1]/1000;
            echo "<td>".$rttms." ms</td>";
            echo "</tr>";
         }
      }
      fclose($socket);
   }

   echo "</table>";
   echo "<br>";
   echo "<div class='divheader'>IAX Trunks</div>";

   $socket = fsockopen('localhost', 5038, $errnum, $errdesc) or die('Connection to host failed');
   fputs($socket, "Action: login\r\n");
   fputs($socket, "Username: admin\r\n");
   fputs($socket, "Secret: VGJd#xx&m\r\n\r\n");
   fputs($socket, "Action: IAXpeers\r\n\r\n");
   fputs($socket, "Action: Logoff\r\n\r\n");
   $count=0;
   $array = array();
   $i=0;

   while (!feof($socket)) {
      $j=0;
      $wrets = fgets($socket);
      if ($wrets != "\r\n") {
         $array[$count][$i]=str_replace("\r\n","",$wrets);
         $j++; 
         $token = '';
         $i++;
      } else {
         $i=0;
         $count++; 
      }
   }

   echo "<div class=''>";
   echo "<table>";
   echo "<th>Trunk Name</th><th>IP</th><th>Port</th><th>Status</th>";

   foreach ($array as $key => $value) {
      if ($array[$key][0] == "Event: PeerEntry") {
         echo "<tr align='center'>";

         $name = explode(": ", $array[$key][2]) ;
         echo "<td>".$name[1]."</td>";
         $ip = explode(": ", $array[$key][4]) ;
         echo "<td>".$ip[1]."</td>";
         $port = explode(": ", $array[$key][5]) ;
         echo "<td>".$port[1]."</td>";
         $status = explode(": ", $array[$key][9]) ;
         echo "<td>".$status[1]."</td>";
         echo "</tr>";
      }
   }
   echo "</table>";
   fclose($socket);


}

function queuesreport() {
    header("Refresh:5");

    $socket = connect();
    $command = "action: queuestatus\r\n";
    $answer = exec_cmd($command,$socket,"Event: QueueStatusComplete");
    $queues = ArrayQueues($answer);
    $command = "action: logoff\r\n";
    fclose($socket);

    // Cycle through queues
    foreach($queues as $queue)
    {
	{
	    // Check status of queue and display colours accordingly
	    if ($queue['ServicelevelPerf'] >= 0)				$class = 'critical';
	    if ($queue['ServicelevelPerf'] >= 60)				$class = 'warn';
	    if ($queue['ServicelevelPerf'] >= 80)				$class = 'good';
	    if ($queue['ServicelevelPerf'] == 0 && $queue['Completed'] == 0)	$class = 'good';
	    
	    
	    echo('<table width=600 align=center><caption class='.$class.'><a href=index.php?mod=singlequeuereport&queue='.$queue['Queue'].' style="color: white">'.$queue['Queue'].' - '.$queue['Strategy'].'</a></caption>');
	    echo('<thead>');
    	    echo('<tr><th align=center width=50%>QUEUED - '.$queue['Calls'].'</th><th align=center width=50%>COMPLETED - '.$queue['Completed'].'</th></tr>');
    	    echo('<tr><th align=center width=50%>AGENTS - '.$queue['Agents'].'</th><th align=center width=50%>ABANDONED - '.$queue['Abandoned'].'</th></tr>');
    	    echo('<tr><th align=center width=50%>AVERAGE HOLD - '.$queue['Holdtime'].'s</th><th align=center width=50%>SERVICE - '.$queue['ServicelevelPerf'].'% - '.$queue['ServiceLevel'].'s</th></tr>');
	    echo('</thead>');
	    echo('</table>');
	    
	    echo('<table width=600 align=center>');
	    echo('<tbody>');
	    $numagents=$queue['Agents'];
	    if ($numagents > 0)
	    {
		echo('<tr>');
	        echo('<td align=left width=30%>Agent</td><td align=center width=20%>Penalty</td><td align=center width=20%>Status</td><td align=center width=15%>Calls</td><td align=center width=15%>Last</td>');
	        echo('</tr>');
	        
	        // Cycle through agents of each queue
	        for($a=1;$a<=$numagents;$a++)
	        {
	    	    if ($queue[$a]['LastCall'] > 0)
	    	    {
	    		$queue[$a]['LastCall'] = time() - $queue[$a]['LastCall'];
	    	    }

		    // Get the agents status
		    if ($queue[$a]['Status'] == 1)	$agentstatus = 'Available';
		    if ($queue[$a]['Status'] == 2)	$agentstatus = 'Busy';
		    if ($queue[$a]['Status'] == 3)	$agentstatus = 'Busy';
		    if ($queue[$a]['Status'] == 5)	$agentstatus = 'Disconnected';
		    if ($queue[$a]['Status'] == 6)	$agentstatus = 'Ringing';
		    if ($queue[$a]['Paused'] == 1)	$agentstatus = 'Paused';

		    echo('<tr class="'.$agentstatus.'">');
		    
		    $agentnum  = $queue[$a]['Location'];
		    $agentname = '';

		    // Lookup extension name
		    $row = db_query("SELECT callerid FROM ps_endpoints WHERE id = ?", array($agentnum));   
		    foreach($row as $key => $value)
		    {
	        	$agentname = $value['callerid'];
	    	    }
	    	    
	    	    // Lookup agent name
		    $row = db_query("SELECT description FROM agents WHERE agent = ?", array($agentnum));
	    	    foreach($row as $key => $value)
		    {
	        	$agentname = $value['description'];
	    	    }
		    		    
		    echo("<td align=left>$agentnum - $agentname</td>");
	    	    echo('<td align=center>'.$queue[$a]['Penalty'].'</td>');
		    echo("<td align=center>$agentstatus</td>");
	    	    echo('<td align=center>'.$queue[$a]['CallsTaken'].'</td>');
	    	    echo('<td align=center>'.$queue[$a]['LastCall'].'s</td>');
		    echo('</tr>');
	        }
	    }
    	    echo('</tbody>');
    	    echo('</tbody></table>');
    	    echo('<br><br>');
	}
    }    
//    queuesreport();

}

function connect()
{
    // Connect to Asterisk Manager
    $socket = fsockopen("127.0.0.1","5038", $errno, $errstr, 30);
    fwrite($socket, "action: login\r\n");
    fwrite($socket, "username: admin\r\n");
    fwrite($socket, "secret: VGJd#xx&m\r\n");
    $actionid = rand(000000000,9999999999);
    fwrite($socket, "actionid: ".$actionid."\r\n\r\n");

    if ($socket)
    {
	while (!feof($socket))
	{
	    $buffer = fgets($socket);
	    if(stristr($buffer,"Authentication accepted"))
	    {	
		break;
	    }
	    elseif(stristr($buffer,"Authentication failed"))
	    {
		fclose ($socket);
		echo("Username or password incorrect");
		exit();
	    }
	}
    }
    return $socket;
}

function exec_cmd($command,$socket,$eventEnd)
{
    $actionid = rand(000000000,9999999999);
    $actionid = "actionid: ".$actionid."\r\n";
    $command .= $actionid."\r\n";	
    $package = false;
    $data = "";
    $answer = array();
    	
    fwrite($socket, $command);
    
    while (!feof($socket))
    {
	$buffer = fgets($socket);
	$data .= $buffer;
	
	if(strtolower($buffer) == strtolower($actionid))
	{
       	    $package = true;
	}
	if(strtolower($buffer) == "\r\n" && $package == true)
	{
	    $package = false;
    	    $answer['events'][] = package($data);
       	    if(stristr($data,$eventEnd))
       	    {
       		$data = "";
       		return $answer;
       		break;
       	    }
       	    elseif(stristr($data,"Error"))
       	    {
       		return $answer;
       		break;
       	    }
       	    else
       	    {
       	    	$data = "";
       	    }
	}
    }
}

function package($data)
{	
	$items = preg_split("/\r\n/",$data);
	foreach ($items as $item)
	{
		if( strlen($item) >0 )
		{
			$tmp = preg_split("/: /",$item);
			$key = $tmp[0];
			$value = $tmp[1];
			$event[$key] = $value;
		}
	}
	return $event;
}

function ArrayQueues($qsResponse)
{
	$result = array();
	foreach ($qsResponse['events'] as $item)
	{
	    if (isset($item['Event'])) {
	    if($item['Event'] == "QueueParams")
	    {
		$result[$item['Queue']]['Queue'] = $item['Queue'];
		$result[$item['Queue']]['Max'] = $item['Max'];
		$result[$item['Queue']]['Strategy'] = $item['Strategy'];
		$result[$item['Queue']]['Calls'] = $item['Calls'];
		$result[$item['Queue']]['Holdtime'] = $item['Holdtime'];
		$result[$item['Queue']]['Completed'] = $item['Completed'];
		$result[$item['Queue']]['Abandoned'] = $item['Abandoned'];
		$result[$item['Queue']]['ServiceLevel'] = $item['ServiceLevel'];
		$result[$item['Queue']]['ServicelevelPerf'] = $item['ServicelevelPerf'];
		$result[$item['Queue']]['Agents'] = 0;
		$numagents=0;
	    }
	    if($item['Event'] == "QueueMember")
	    {
		$numagents++;
		$result[$item['Queue']]['Agents'] = $numagents;
		$item['Location'] = preg_replace("/[^0-9]/","", $item['Location']);
		$result[$item['Queue']][$numagents]['Location'] = $item['Location'];
		$result[$item['Queue']][$numagents]['CallsTaken'] = $item['CallsTaken'];
		$result[$item['Queue']][$numagents]['LastCall'] = $item['LastCall'];
		$result[$item['Queue']][$numagents]['Paused'] = $item['Paused'];
		$result[$item['Queue']][$numagents]['Status'] = $item['Status'];
		$result[$item['Queue']][$numagents]['Penalty'] = $item['Penalty'];
	    }
	    
	}
	}
	return $result;
}


function activechannels() {
   header("Refresh:5");

   $socket = fsockopen('localhost', 5038, $errnum, $errdesc) or die('Connection to host failed');
   fputs($socket, "Action: login\r\n");
   fputs($socket, "Username: admin\r\n");
   fputs($socket, "Secret: VGJd#xx&m\r\n\r\n");
   fputs($socket, "Action: Command\r\n");
   fputs($socket, "Command: Core show channels concise\r\n\r\n");
   fputs($socket, "Action: Logoff\r\n\r\n");

   $count=0;
   $array = array();
   $i=0;

   while (!feof($socket)) {
      $j=0;
      $wrets = fgets($socket);
      if ($wrets != "\r\n") {
         $array[$count][$i]=str_replace("\r\n","",$wrets);
         $j++;
         $token = '';
         $i++;
      } else {
         $i=0;
         $count++;
      }
   }

   echo "<div class=''>";
   echo "<table>";
   echo "<th>Channel</th><th>Context</th><th>Exten</th><th>Status</th><th>Application</th><th>Data</th><th>CallerID</th><th>Accountcode</th><th>Duration</th><th>Bridge</th>";

//   print_r($array);
   foreach ($array as $key => $value) {
      foreach ($array[$key] as $key2 => $value2) {
 //       if ($value2 != "Asterisk Call Manager/5.0.1" && $value2 != "Response: Success" && $value2 != "Message: Authentication accepted" && $value2 != "Event: FullyBooted" && $value2 != "Privilege: system,all" && $value2 != "Status: Fully Booted" && $value2 != "Response: Follows" && $value2 != "Privilege: Command" && $value2 != "--END COMMAND--" && $value2 != "Response: Goodbye" && $value2 !=  "Message: Thanks for all the fish." && $value2 != "" && $value2 != "Message: Command output follows" && $value2 != "Output: ") {
 //      print substr($value2, 0, 7); 
       if (substr($value2, 0, 7) == "Output:") {
           $mystring = $value2;
           $pieces = explode("!", $value2);
           echo "<tr>";
           echo "<td>".$pieces[0]."</td>";
           echo "<td>".$pieces[1]."</td>";
           echo "<td>".$pieces[2]."</td>";
           echo "<td>".$pieces[4]."</td>";
           echo "<td>".$pieces[5]."</td>";
           echo "<td>".$pieces[6]."</td>";
           echo "<td>".$pieces[7]."</td>";
           echo "<td>".$pieces[8]."</td>";
           echo "<td>".$pieces[11]."</td>";
           echo "<td>".$pieces[12]."</td>";
           echo "</tr>";
        }
      }
   }
   echo "</table>";
}

function callgraphs(){
   ### Day ###
   $options = array(
     "-h 125", 
     "-s -86400",
     "--color","SHADEA#f2f2f2",
     "--color","SHADEB#f2f2f2",
     "--color","BACK#FFFFFF00",
     "--x-grid","HOUR:1:HOUR:1:HOUR:2:0:%H",
     "--rigid",
     "--alt-autoscale",
     "--vertical-label=Calls",
     "--disable-rrdtool-tag",
     "DEF:concur=/usr/local/rrd/concurcalls.rrd:ccalls:LAST",
     "AREA:concur#158ecb:Concurrent\j",
     "GPRINT:concur:MAX: Max\:%7.2lf",
     "GPRINT:concur:AVERAGE: Avg\:%7.2lf",
     "GPRINT:concur:LAST: Last\:%7.2lf\j"
   );

   $fileName = "img/concurcallday.png";

   $ret = rrd_graph($fileName, $options);
   if (! $ret) {
     echo "<b>Graph error: </b>".rrd_error()."\n";
   } 

   ### Week ###
   $options1 = array(
     "-h 125", 
     "-s -604800",
     "--color","SHADEA#f2f2f2",
     "--color","SHADEB#f2f2f2",
     "--color","BACK#FFFFFF00",
     "--x-grid","HOUR:24:HOUR:24:DAY:1:0:%a",
     "--alt-y-grid",
     "--rigid",
     "--alt-autoscale",
     "--vertical-label=Calls",
     "--disable-rrdtool-tag",
     "DEF:concur=/usr/local/rrd/concurcalls.rrd:ccalls:LAST",
     "AREA:concur#158ecb:Concurrent\j",
     "GPRINT:concur:MAX: Max\:%7.2lf",
     "GPRINT:concur:AVERAGE: Avg\:%7.2lf",
     "GPRINT:concur:LAST: Last\:%7.2lf\j"
   );

   $fileName1 = "img/concurcallweek.png";
   
   rrd_graph($fileName1, $options1);

   echo "<table>";
   echo "<tr><th colspan='2'>Concurrent Calls</th></tr>";
   echo "<tr><td>Daily Graph (5 Minute Average)";
   echo "</td><td>Weekly Graph (30 Minute Average)</td></tr>";
   echo "</td><td>";
   echo "<img src='img/concurcallday.png' alt='Generated RRD image 1'>";
   echo "</td><td>";
   echo "<img src='img/concurcallweek.png' alt='Generated RRD image 2'>";
   echo "</td></tr>";
   echo "</table>";
}

function singlequeuereport() {
    header("Refresh:5");

    $socket = connect();
    $command = "action: queuestatus\r\n";
    $answer = exec_cmd($command,$socket,"Event: QueueStatusComplete");
    $queues = ArrayQueues($answer);
    $command = "action: logoff\r\n";
    fclose($socket);

    // Cycle through queues
    foreach ($queues as $queue)
    {
    if ($queue['Queue'] == $_GET['queue'])
    {
	{
	    // Check status of queue and display colours accordingly
	    if ($queue['ServicelevelPerf'] >= 0)				$class = 'critical';
	    if ($queue['ServicelevelPerf'] >= 60)				$class = 'warn';
	    if ($queue['ServicelevelPerf'] >= 80)				$class = 'good';
	    if ($queue['ServicelevelPerf'] == 0 && $queue['Completed'] == 0)	$class = 'good';
	    
	    
	    echo('<table width=600 align=center><caption class='.$class.'>'.$queue['Queue'].' - '.$queue['Strategy'].'	</caption>');
	    echo('<thead>');
    	    echo('<tr><th align=center width=50%>QUEUED - '.$queue['Calls'].'</th><th align=center width=50%>COMPLETED - '.$queue['Completed'].'</th></tr>');
    	    echo('<tr><th align=center width=50%>AGENTS - '.$queue['Agents'].'</th><th align=center width=50%>ABANDONED - '.$queue['Abandoned'].'</th></tr>');
    	    echo('<tr><th align=center width=50%>AVERAGE HOLD - '.$queue['Holdtime'].'s</th><th align=center width=50%>SERVICE - '.$queue['ServicelevelPerf'].'% - '.$queue['ServiceLevel'].'s</th></tr>');
	    echo('</thead>');
	    echo('</table>');
	    
	    echo('<table width=600 align=center>');
	    echo('<tbody>');
	    $numagents=$queue['Agents'];
	    if ($numagents > 0)
	    {
		echo('<tr>');
                echo('<td align=left width=30%>Agent</td><td align=center width=20%>Penalty</td><td align=center width=20%>Status</td><td align=center width=15%>Calls</td><td align=center width=15%>Last</td>');
	        echo('</tr>');
	        
	        // Cycle through agents of each queue
	        for($a=1;$a<=$numagents;$a++)
	        {
	    	    if ($queue[$a]['LastCall'] > 0)
	    	    {
	    		$queue[$a]['LastCall'] = time() - $queue[$a]['LastCall'];
	    	    }
		    echo('<tr>');
		    
		    $agentnum  = $queue[$a]['Location'];
		    $agentname = '';

		    // Lookup extension name
		    $row = db_query("SELECT callerid FROM ps_endpoints WHERE id = ?", array($agentnum));   
		    foreach($row as $key => $value)
		    {
	        	$agentname = $value['callerid'];
	    	    }
	    	    
	    	    // Lookup agent name
		    $row = db_query("SELECT description FROM agents WHERE agent = ?", array($agentnum));
	    	    foreach($row as $key => $value)
		    {
	        	$agentname = $value['description'];
	    	    }
		    
		    // Get the agents status
		    if ($queue[$a]['Status'] == 1)	$agentstatus = 'Available';
		    if ($queue[$a]['Status'] == 2)	$agentstatus = 'Busy';
		    if ($queue[$a]['Status'] == 3)	$agentstatus = 'Busy';
		    if ($queue[$a]['Status'] == 5)	$agentstatus = 'Disconnected';
		    if ($queue[$a]['Status'] == 6)	$agentstatus = 'Ringing';
		    if ($queue[$a]['Paused'] == 1)	$agentstatus = 'Paused';
		    
		    echo("<td align=left>$agentnum - $agentname</td>");
                    echo('<td align=center>'.$queue[$a]['Penalty'].'</td>');
		    echo("<td align=center>$agentstatus</td>");
	    	    echo('<td align=center>'.$queue[$a]['CallsTaken'].'</td>');
	    	    echo('<td align=center>'.$queue[$a]['LastCall'].'s</td>');
		    echo('</tr>');
	        }
	    }
    	    echo('</tbody>');
    	    echo('</tbody></table>');
    	    echo('<br><br>');
	}
    }
    }    
}

function ext_usage() {
   $today = date("Y-m-d 00:00:00");

   $rowsperpage = 1000;

   //find page number//
   if(isset($_GET['page']) && is_numeric($_GET['page'])) {
      $page = $_GET['page'];
   }elseif(isset($_POST['page']) && is_numeric($_POST['page'])) {
      $page = $_POST['page'];
   }else{
      $page = 1;
   }
   $offset = ($page - 1) * $rowsperpage;

   $thisfile = "index.php?mod=ext_usage";

   $depallow = check_department_access();

   if ($depallow != "") {
      if (isset($_POST["startdate"])) { 
         $startdate = $_POST['startdate'];
         $enddate = $_POST['enddate']." 23:59:59"; 
         $where = "AND calldate > ? and calldate <= ?";
         $row = db_query("select ps_endpoints.id,count(cdr.uniqueid) as count,sum(billsec) as talktime,sum(duration) as totaltime,ps_endpoints.callerid as callerid from cdr LEFT JOIN ps_endpoints ON dstchannel like CONCAT('PJSIP/', id, '%') LEFT JOIN departments ON (id = sip) LEFT JOIN ps_registrations USING (id) WHERE department = ? AND calldate > ? and calldate <= ? AND dstchannel like CONCAT('PJSIP/', id, '%') AND ps_registrations.id is null group by id LIMIT $offset, $rowsperpage", array($depallow,$startdate,$enddate));
         $row2 = db_query("select ps_endpoints.id,count(cdr.uniqueid) as count,sum(billsec) as talktime,sum(duration) as totaltime,ps_endpoints.callerid as callerid from cdr LEFT JOIN ps_endpoints ON channel like CONCAT('PJSIP/', id, '%') LEFT JOIN departments ON (id = sip) LEFT JOIN ps_registrations USING (id) WHERE department = ? AND calldate > ? and calldate < ? AND channel like CONCAT('PJSIP/', id, '%') AND ps_registrations.id is null AND disposition = 'ANSWERED' group by id LIMIT $offset, $rowsperpage", array($depallow,$startdate,$enddate));
      } else { 
         $row = db_query("select ps_endpoints.id,count(cdr.uniqueid) as count,sum(billsec) as talktime,sum(duration) as totaltime,ps_endpoints.callerid as callerid from cdr LEFT JOIN ps_endpoints ON dstchannel like CONCAT('PJSIP/', id, '%') LEFT JOIN departments ON (id = sip) LEFT JOIN ps_registrations USING (id) WHERE department = ? AND calldate > ? AND dstchannel like CONCAT('PJSIP/', id, '%') AND ps_registrations.id is nullps_registrations.id is null group by id LIMIT $offset, $rowsperpage", array($depallow,$today));
         $row2 = db_query("select ps_endpoints.id,count(cdr.uniqueid) as count,sum(billsec) as talktime,sum(duration) as totaltime,ps_endpoints.callerid as callerid from cdr LEFT JOIN ps_endpoints ON channel like CONCAT('PJSIP/', id, '%') LEFT JOIN departments ON (id = sip) LEFT JOIN ps_registrations USING (id) WHERE department = ? AND calldate > ? AND channel like CONCAT('PJSIP/', id, '%') AND ps_registrations.id is null AND disposition = 'ANSWERED' group by id LIMIT $offset, $rowsperpage", array($depallow,$today));
         $where = "AND calldate > '$today'";
      }
   } else {
      if (isset($_POST["startdate"])) { 
         $startdate = $_POST['startdate'];
         $enddate = $_POST['enddate']." 23:59:59"; 
         $where = "AND calldate > '$startdate' and calldate <= '$enddate'";
         $row = db_query("select ps_endpoints.id,count(cdr.uniqueid) as count,sum(billsec) as talktime,sum(duration) as totaltime,ps_endpoints.callerid as callerid from cdr LEFT JOIN ps_endpoints ON dstchannel like CONCAT('PJSIP/', id, '%') LEFT JOIN ps_registrations USING (id) where calldate > ? and calldate <= ? AND dstchannel like CONCAT('PJSIP/', id, '%') AND ps_registrations.id is null group by id LIMIT $offset, $rowsperpage", array($startdate,$enddate));
         $row2 = db_query("select ps_endpoints.id,count(cdr.uniqueid) as count,sum(billsec) as talktime,sum(duration) as totaltime,ps_endpoints.callerid as callerid from cdr LEFT JOIN ps_endpoints ON channel like CONCAT('PJSIP/', id, '%') LEFT JOIN ps_registrations USING (id) where calldate > ? and calldate < ? AND channel like CONCAT('PJSIP/', id, '%') AND ps_registrations.id is null AND disposition = 'ANSWERED' group by id LIMIT $offset, $rowsperpage", array($startdate,$enddate));
      } else { 
         $row = db_query("select ps_endpoints.id,count(cdr.uniqueid) as count,sum(billsec) as talktime,sum(duration) as totaltime,ps_endpoints.callerid as callerid from cdr LEFT JOIN ps_endpoints ON dstchannel like CONCAT('PJSIP/', id, '%') LEFT JOIN ps_registrations USING (id) where calldate > ? AND dstchannel like CONCAT('PJSIP/', id, '%') AND ps_registrations.id is null group by id LIMIT $offset, $rowsperpage", array($today));
         $row2 = db_query("select ps_endpoints.id,count(cdr.uniqueid) as count,sum(billsec) as talktime,sum(duration) as totaltime,ps_endpoints.callerid as callerid from cdr LEFT JOIN ps_endpoints ON channel like CONCAT('PJSIP/', id, '%') LEFT JOIN ps_registrations USING (id) where calldate > ? AND channel like CONCAT('PJSIP/', id, '%') AND ps_registrations.id is null AND disposition = 'ANSWERED' group by id LIMIT $offset, $rowsperpage", array($today));
         $where = "AND calldate > '$today'";
      }
   }

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
      <form action='index.php?mod=ext_usage' method='post'>
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

   $var = array();
   echo "<div id='dvData1'>";
   echo "<table style='width: 100%'>";
   echo "<th>Extension</th><th>Inbound</th><th>Total Time</th><th>Talk Time</th><th>Outbound</th><th>Total Time</th><th>Talk Time</th>";
   foreach($row as $key => $value) { 
      $var[$value['id']][callerid]=$value["callerid"];
      $var[$value['id']][incount]=$value["count"];
      $var[$value['id']][intotaltime]=$value["totaltime"];
      $var[$value['id']][intalktime]=$value["talktime"];
   }
   foreach($row2 as $key2 => $value2) { 
      $var[$value2['id']][callerid]=$value2["callerid"];
      $var[$value2['id']][outcount]=$value2["count"];
      $var[$value2['id']][outtotaltime]=$value2["totaltime"];
      $var[$value2['id']][outtalktime]=$value2["talktime"];
   }

   $intotal = '0';
   $outtotal = '0';
   $intotaltotal = '0';
   $outtotaltotal = '0';
   $intalktotal = '0';
   $outtalktotal = '0';
   foreach ($var as $key => $value) {
      echo "<tr>";
      echo "<td align='center'>".$key." ".$var[$key][callerid]."</td>";
      echo "<td align='center'>".$var[$key][incount]."</td>";
      $intotal = $var[$key][incount] + $intotal;
      echo "<td align='center'>".gmdate('H:i:s', $var[$key][intotaltime])."</td>";
      $intotaltotal = $var[$key][intotaltime] + $intotaltotal;
      echo "<td align='center'>".gmdate('H:i:s', $var[$key][intalktime])."</td>";
      $intalktotal = $var[$key][intalktime] + $intalktotal;
      echo "<td align='center'>".$var[$key][outcount]."</td>";
      $outtotal = $var[$key][outcount] + $outtotal;
      echo "<td align='center'>".gmdate('H:i:s', $var[$key][outtotaltime])."</td>";
      $outtotaltotal = $var[$key][outtotaltime] + $outtotaltotal;
      echo "<td align='center'>".gmdate('H:i:s', $var[$key][outtalktime])."</td>";
      $outtalktotal = $var[$key][outtalktime] + $outtalktotal;
      echo "</tr>";
   }
   echo "<tr>";
   echo "<td align='center' style='font-weight: bold;'>Total:</td>";
   echo "<td align='center' style='font-weight: bold;'>".$intotal."</td>";
   echo "<td align='center' style='font-weight: bold;'>".gmdate('H:i:s', $intotaltotal)."</td>";
   echo "<td align='center' style='font-weight: bold;'>".gmdate('H:i:s', $intalktotal)."</td>";
   echo "<td align='center' style='font-weight: bold;'>".$outtotal."</td>";
   echo "<td align='center' style='font-weight: bold;'>".gmdate('H:i:s', $outtotaltotal)."</td>";
   echo "<td align='center' style='font-weight: bold;'>".gmdate('H:i:s', $outtalktotal)."</td>";
   echo "</tr>";

   echo "</table>"; 
   echo "</div>";
   echo "<a href='#' class='export1' style='font-size: 12px; background: none'><img class='' src='img/export-icon.png' alt='CSV Export' height='30'></a>";
?>
   <table width="100%">
   <tr>
      <td align="center">
      <?php
      ?>
      </td>
   </tr>
   </table>
<?php

   echo "</table>";
   echo "</div>";
}

function topdialnum() {
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

   $depallow = check_department_access();

   if ($depallow != "") {
      if (isset($_POST["startdate"])) { 
         $startdate = $_POST['startdate'];
         $enddate = $_POST['enddate']." 23:59:59"; 
         $where = "AND department = ? AND calldate > ? and calldate <= ?";
         $row = db_query("select dst,count(cdr.uniqueid) as total,sum(duration),sum(billsec) from cdr LEFT JOIN ps_endpoints ON (SUBSTRING_INDEX(cdr.channel, '-', 1) REGEXP id) LEFT JOIN departments ON (id = sip) WHERE 1 $where AND department = ? AND (SUBSTRING_INDEX(cdr.channel, '-', 1) REGEXP id) AND disposition = 'ANSWERED' AND dstchannel REGEXP '^IAX|^DAHDI|^PJSIP' group by dst ORDER BY total DESC LIMIT 100", array($startdate,$enddate,$depallow));
      } else { 
         $row = mysql_query("select dst,count(cdr.uniqueid) as total,sum(duration),sum(billsec) from cdr LEFT JOIN ps_endpoints ON (SUBSTRING_INDEX(cdr.channel, '-', 1) REGEXP id) LEFT JOIN departments ON (id = sip) WHERE department = ? AND calldate > ? AND (SUBSTRING_INDEX(cdr.channel, '-', 1) REGEXP id) AND disposition = 'ANSWERED' AND dstchannel REGEXP '^IAX|^DAHDI|^PJSIP' group by dst ORDER BY total DESC LIMIT 100". array($depallow,$today));
         $where = "AND calldate > ?";
      }
   } else {
      if (isset($_POST["startdate"])) { 
         $startdate = $_POST['startdate'];
         $enddate = $_POST['enddate']." 23:59:59"; 
         $where = "AND calldate > ? and calldate <= ?";
         $row = db_query("select dst,count(cdr.uniqueid) as total,sum(duration),sum(billsec) from cdr LEFT JOIN ps_endpoints ON (SUBSTRING_INDEX(cdr.channel, '-', 1) REGEXP id) WHERE 1 $where AND (SUBSTRING_INDEX(cdr.channel, '-', 1) REGEXP id) AND disposition = 'ANSWERED' AND dstchannel REGEXP '^IAX|^DAHDI|^PJSIP' group by dst ORDER BY total DESC LIMIT 100", array($startdate,$enddate));
      } else { 
         $row = db_query("select dst,count(cdr.uniqueid) as total,sum(duration),sum(billsec) from cdr LEFT JOIN ps_endpoints ON (SUBSTRING_INDEX(cdr.channel, '-', 1) REGEXP id) WHERE calldate > ? AND (SUBSTRING_INDEX(cdr.channel, '-', 1) REGEXP id) AND disposition = 'ANSWERED' AND dstchannel REGEXP '^IAX|^DAHDI|^PJSIP' group by dst ORDER BY total DESC LIMIT 100", array($today));
         $where = "AND calldate > ?";
      }
   }

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
      <form action='index.php?mod=topdialnum' method='post'>
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

   echo "<div id='dvData1'>";
   echo "<table style='width: 100%'>";
   echo "<th>Number</th><th>Count</th><th>Total Time</th><th>Talk Time</th>";
   foreach($row as $key => $value) { 
      echo "<tr>";
      echo "<td align='center'>". $value['dst'] ."</td>";
      echo "<td align='center'>". $value['total'] ."</td>";
      echo "<td align='center'>".gmdate('H:i:s', $value['sum(duration)'])."</td>";
      echo "<td align='center'>".gmdate('H:i:s', $value['sum(billsec)'])."</td>";
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
         if (isset($startdate)) {
            $resultPage = db_query("select dst,count(cdr.uniqueid) as total,sum(duration),sum(billsec) from cdr LEFT JOIN ps_endpoints ON (SUBSTRING_INDEX(cdr.channel, '-', 1) REGEXP id) WHERE 1 $where AND (SUBSTRING_INDEX(cdr.channel, '-', 1) REGEXP id) AND disposition = 'ANSWERED' AND dstchannel REGEXP '^IAX|^DAHDI|^PJSIP' group by dst ORDER BY total DESC LIMIT 100", array($startdate,$enddate));
         } else {
            $resultPage = db_query("select dst,count(cdr.uniqueid) as total,sum(duration),sum(billsec) from cdr LEFT JOIN ps_endpoints ON (SUBSTRING_INDEX(cdr.channel, '-', 1) REGEXP id) WHERE 1 $where AND (SUBSTRING_INDEX(cdr.channel, '-', 1) REGEXP id) AND disposition = 'ANSWERED' AND dstchannel REGEXP '^IAX|^DAHDI|^PJSIP' group by dst ORDER BY total DESC LIMIT 100", array($today));
         }
         pagination('index.php?mod=topdialnum',$rowsperpage,$resultPage,$page);
      ?>
      </td>
   </tr>
   </table>
<?php

   echo "</table>";
   echo "</div>";
}
?>
