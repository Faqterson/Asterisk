<?php
ob_start();

   include('include/checklogin.php');

    header("Refresh:10");
 
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

    $socket = connect();
    $command = "action: queuestatus\r\n";
    $command .= "Queue: ".$_SESSION['queuename']."\r\n";
    $answer = exec_cmd($command,$socket,"Event: QueueStatusComplete");
    $queues = ArrayQueues($answer);
    $command = "action: logoff\r\n";
    fclose($socket);
 
?>

<!doctype html>
<html class="touch-styles">
<head>
   <meta charset="utf-8" />
   <title>Desktop Group: Customized VoIP solutions, Software and Hardware providers</title>
   <link rel="stylesheet" type="text/css" href="../css/site.css"/>

   <script type="text/javascript" src="../js/jquery.min.js"></script>
   <script type="text/javascript" src="../js/site.js"></script>

   <script src="jquery.appear.min.js"></script>
   <script src="jquery.easypiechart.min.js"></script>

<style>

.row {
  display: -ms-flexbox;
  display: flex;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
  margin-right: -15px;
  margin-left: -15px;
}

.col-md-3 {
  -ms-flex: 0 0 20%;
  flex: 0 0 20%;
  max-width: 20%;
  display: block ruby;
}

.b-skills
{
        text-align: center;
	font-size: 14px;
}

.b-skills:last-child { margin-bottom: -30px; }

.b-skills h2 { margin-bottom: 50px; font-weight: 900; text-transform: uppercase;}

.skill-item {
        position: relative;
        max-width: 125px;
        width: 100%;
        color: #555;
}

.chart-container
{
        position: relative;
        width: 100%;
        height: 0;
        padding-top: 100%;
        margin-bottom: 27px;
}

.skill-item .chart,
.skill-item .chart canvas
{
        position: absolute;
        top: 0;
        left: 0;
        width: 100% !important;
        height: 100% !important;
}

.skill-item .chart:before
{
        content: "";
        width: 0;
        height: 100%;
}

.skill-item .chart:before,
.skill-item .percent
{
        display: inline-block;
        vertical-align: middle;
}

.skill-item .percent
{
        position: relative;
        line-height: 1;
        font-size: 40px;
        font-weight: 900;
        z-index: 2;
}

.skill-item  .percent:after
{
        content: attr(data-after);
        font-size: 20px;
}

       p{
           font-weight: 900;
       }

    </style>


<style>

tr.Available   {background-color: #69b7f8; color: white;}
tr.Busy   {background-color: #fa5d4e; color: white;}
tr.Disconnected   {background-color: lightgrey;}
tr.Paused   {background-color: #ffb742;}

</style>

</head>

<body>
<div id="siteWrapper" class="clearfix">
   <div class="header-inner">
      <div class="wrapper" id="logoWrapper">
         <h1 id="logoImage">
            <select class="button" name="queuesid" id="queuesid" style="width: 100%" hidden>
               <option value="" >== choose one ==</option>
<?php
               $row=db_query("SELECT name from queues", array());
               foreach($row as $key => $value)
               {
                  echo "<option value='".$value['name']."'>".$value['name']."</option>";
               }
?>
            </select>
         </h1>
      </div>
   </div>
</header>

   <script>
   $("#queuesid").change(function(){
      var queuesname = $("#queuesid").val();
      $.post("queueset.php", {queuename: queuesname},
        function(data,status){
            location.reload();
//          alert("Data: " + data + "\nStatus: " + status);
      });
   });
   </script>

<main id="page" role="main">
   <div id="content" class="main-content">
      <div class="sqs-layout sqs-grid-12 columns-12">
         <div class="row sqs-row">
             <div class="col sqs-col-12 span-12">

<?php

    // Cycle through queues
    foreach($queues as $queue)
    {
//    print_r($queue);

    $missed = number_format($queue['ServicelevelPerf']);

    $totalcalls = $queue['Abandoned'] + $queue['Completed'];
    $completedperc = ($queue['Completed']/$totalcalls)*100;
        {
            // Check status of queue and display colours accordingly
            if ($queue['ServicelevelPerf'] >= 0)                                $class = 'critical';
            if ($queue['ServicelevelPerf'] >= 60)                               $class = 'warn';
            if ($queue['ServicelevelPerf'] >= 80)                               $class = 'good';
            if ($queue['ServicelevelPerf'] == 0 && $queue['Completed'] == 0)    $class = 'good';



?>
			<div class="b-skills">
                                <div class="container">
                                        <div class="row">
                                                <div class="col-xs-12 col-sm-6 col-md-3">
                                                        <div class="skill-item center-block">
                                                                <p>Calls Completed</p>
                                                                <div class="chart-container">
                                                                        <div class="chart " data-percent="100" data-bar-color="red">
                                                                                <span class="percent" data-after=""><?php echo $queue['Completed']; ?></span>
                                                                        </div>
                                                                        <div class="chart " data-percent="<?php echo $completedperc; ?>" data-bar-color="#23afe3">
                                                                        </div>
                                                                </div>
                                                                <p>Unanswered: <?php echo $queue['Abandoned']; ?></p>
                                                        </div>
                                                </div>

                                                <div class="col-xs-12 col-sm-6 col-md-3">
                                                        <div class="skill-item center-block">
                                                                <p>Service Level</p>
                                                                <div class="chart-container">
                                                                        <div class="chart " data-percent="<?php echo $missed; ?>" data-bar-color="#a7d212">
                                                                                <span class="percent" data-after="%"><?php echo $missed; ?></span>
                                                                        </div>
                                                                </div>
                                                                <p>SLA: <?php echo $queue['ServiceLevel']; ?>s</p>
                                                        </div>
                                                </div>

                                                <div class="col-xs-12 col-sm-6 col-md-3">
                                                        <div class="skill-item center-block">
                                                                <p>Total Agents</p>
                                                                <div class="chart-container">
                                                                        <div class="chart " data-percent="100" data-bar-color="#ff42f6">
                                                                                <span class="percent" data-after=""><?php echo $queue['Agents']; ?></span>
                                                                        </div>
                                                                </div>
                                                        </div>
                                                </div>

                                                <div class="col-xs-12 col-sm-6 col-md-3">
                                                        <div class="skill-item center-block">
                                                                <p>Avg Hold Time</p>
                                                                <div class="chart-container">
                                                                        <div class="chart " data-percent="100" data-bar-color="#158ecb">
                                                                                <span class="percent" data-after=""><?php echo $queue['Holdtime']; ?></span>
                                                                        </div>
                                                                </div>
                                                        </div>
                                                </div>

                                                <div class="col-xs-12 col-sm-6 col-md-3">
                                                        <div class="skill-item center-block">
                                                                <p>Queued</p>
                                                                <div class="chart-container">
                                                                        <div class="chart " data-percent="100" data-bar-color="#edc214">
                                                                                <span class="percent" data-after=""><?php echo $queue['Calls']; ?></span>
                                                                        </div>
                                                                </div>
                                                        </div>
                                                </div>
                                        </div>
                                </div>
                        </div>


<?php


            echo('<table width=600 align=center><caption class='.$class.'>'.$queue['Queue'].' - '.$queue['Strategy'].'</caption>');
            echo('<thead>');
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
                    if ($queue[$a]['Status'] == 1)      $agentstatus = 'Available';
                    if ($queue[$a]['Status'] == 2)      $agentstatus = 'Busy';
                    if ($queue[$a]['Status'] == 3)      $agentstatus = 'Busy';
                    if ($queue[$a]['Status'] == 5)      $agentstatus = 'Disconnected';
                    if ($queue[$a]['Status'] == 6)      $agentstatus = 'Ringing';
                    if ($queue[$a]['Status'] == 7)      $agentstatus = 'Ringinuse';
                    if ($queue[$a]['Status'] == 8)      $agentstatus = 'On Hold';
                    if ($queue[$a]['Paused'] == 1)      $agentstatus = 'Paused';

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
?>

             </div>
         </div>
      </div>
   </div>
</main>

<footer id="footer" class="footer" role="contentinfo">
   <div class="footer-inner">
         <nav>
            <div id="siteInfo">
            </div>
         </nav>
   </div>
</footer>
</div>

 <script>
    'use strict';

var $window = $(window);

function run()
{
        var fName = arguments[0],
                aArgs = Array.prototype.slice.call(arguments, 1);
        try {
                fName.apply(window, aArgs);
        } catch(err) {

        }
};

/* chart
================================================== */
function _chart ()
{
        $('.b-skills').appear(function() {
                setTimeout(function() {
                        $('.chart').easyPieChart({
                                easing: 'easeOutElastic',
                                delay: 1500,
                                barColor: '#369670',
                                trackColor: false,
                                scaleColor: false,
                                lineWidth: 12,
                                trackWidth: 12,
                                size: 115,
                                lineCap: 'round',
                                //onStep: function(from, to, percent) {
                                //      this.el.children[0].innerHTML = Math.round(percent);
                                //}
                        });
                }, 150);
        });
};


$(document).ready(function() {

        run(_chart);


});
    </script>


</body>
</html>


