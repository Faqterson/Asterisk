<?php 
   ob_start();

   include('include/checklogin.php');

   $row = db_query("SELECT user_type FROM webusers WHERE user_id = ?",array($login_session));

   require 'autoload.php';
   $Config = new Config();
   $update = $Config->checkUpdate();

   if (isset($row[0])) {
?>
<!doctype html>
<html class="touch-styles">
<head>
   <meta charset="utf-8" />
   <title>Desktop Group: Customized VoIP solutions, Software and Hardware providers</title>
   <link rel="stylesheet" type="text/css" href="css/site.css"/>

   <script type="text/javascript" src="js/jquery.min.js"></script>
   <script type="text/javascript" src="js/site.js"></script>

    <link rel="stylesheet" href="css/utilities.css" type="text/css">
    <link rel="stylesheet" href="css/frontend.css" type="text/css">
    <link rel="stylesheet" href="css/dcalendar.picker.css" type="text/css">

    <script src="js/jquery.knob.js" type="text/javascript"></script>
    <script src="js/esm.js" type="text/javascript"></script>
    <script src="js/dcalendar.picker.js" type="text/javascript"></script>
    <script>
    $(function(){
        $('.gauge').knob({
            'fontWeight': 'normal',
            'format' : function (value) {
                return value + '%';
            }
        });

        $('a.reload').click(function(e){
            e.preventDefault();
        });

        <?php
        if(!isset($_GET['mod'])) {
        ?>
           esm.getAll();
        <?php
        }
        ?>

        <?php 
        if ($Config->get('esm:auto_refresh') > 0): 
        ?>
            setInterval(function(){ esm.getAll(); }, <?php echo $Config->get('esm:auto_refresh') * 1000; ?>);
        <?php 
        endif; 
        ?>
    });
    </script> 
    <script>
    $(document).ready(function() {
       $('#startdate').dcalendarpicker({
          format: 'yyyy-mm-dd'
       });
       $('#enddate').dcalendarpicker({
          format: 'yyyy-mm-dd'
       });
    });
    </script>

</head>

<body>
<div id="siteWrapper" class="clearfix">
<header id="header" class="show-on-scroll header">
   <div class="header-inner">
      <div class="wrapper" id="logoWrapper">
         <h1 id="logoImage">
            <a href="index.php"><img src="img/desktop.png" alt="Desktop Group: Customized VoIP solutions, Software and Hardware providers"/></a>
         </h1>
      </div>
      <div id="headerNav">
         <div class="nav-wrapper" id="mainNavWrapper">
            <nav>
<?php
               if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
?>
               <div class="dropdown">
                  <a href="">User Applications</a>
                  <div class="dropdown-content">
                     <a href="index.php?mod=extlist">Extension List</a>
                     <a href="index.php?mod=features">Features</a>
                  </div>
               </div>         
<?php
               }
               if ($row[0]['user_type'] == '1') {
?>
               <div class="dropdown">
                  <a href="">System Setting</a>
                  <div class="dropdown-content">
                     <a href="index.php?mod=users">Users</a>
                  </div>
               </div>         
<?php
               }
               if ($row[0]['user_type'] == '1') {
?>
               <div class="dropdown">
                  <a href="">PBX Setup</a>
                  <div class="dropdown-content">
                     <a href="index.php?mod=trunk">Trunks</a>
                     <a href="index.php?mod=global">Global Settings</a>
                     <a href="index.php?mod=inbound">Inbound Routes</a>
                     <a href="index.php?mod=timeconditions">Time Conditions</a>
                     <a href="index.php?mod=ivr">IVR</a>
                     <a href="index.php?mod=officehours">Officehours</a>
                     <a href="index.php?mod=afterhours">Afterhours</a>
                     <a href="index.php?mod=holiday">Holiday</a>
                     <a href="index.php?mod=bulkextcreate">Bulk Exten Create</a>
                     <a href="index.php?mod=csvimport">CSV Import</a>
                  </div>
               </div>
<?php
               }
?>
               <div class="dropdown">
                  <a href="">PBX Setting</a>
                  <div class="dropdown-content">
<?php
                  if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
?>
                     <a href="index.php?mod=queues">Queues</a>
                     <a href="index.php?mod=rt_extensions">Realtime Extensions</a>
                     <a href="index.php?mod=agents">Agents</a>
                     <a href="index.php?mod=voicemail">Voicemail</a>
                     <a href="index.php?mod=pincodes">Pin Codes</a>
                     <a href="index.php?mod=directory">Directory</a>
                     <a href="index.php?mod=cloudcall">Cloudcall</a>
                     <a href="index.php?mod=speeddials">Speed Dial</a>
                     <a href="index.php?mod=forwarders">Forwarders</a>
<?php
                  }
?>
                  </div>
               </div>
<?php
               if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '2' || $row[0]['user_type'] == '3') {
?>
               <div class="dropdown" style="padding-right: 50px">
                  <a href="">PBX Reports</a>
                  <div class="dropdown-content">
                     <a href="index.php?mod=callgraphs">Call Graphs</a>
                     <a href="index.php?mod=cdr">CDR's</a>
                     <a href="index.php?mod=ext_usage">Usage Report</a>
                     <a href="index.php?mod=topdialnum">Top 100 Dial Numbers</a>
<?php
		     if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
?>
                     <a href="index.php?mod=cereport">C.E Report</a>
<?php
		     }
?>
                     <a href="index.php?mod=surveys">Surveys</a>
                     <a href="index.php?mod=sippeers">PJSIP Peers</a>
                     <a href="index.php?mod=showtrunks">Trunks Status</a>
                     <a href="index.php?mod=queuesreport">Queues Report</a>
                     <a href="index.php?mod=activechannels">Active Channels</a>
                  </div>
               </div>
<?php
               }
?>
            </nav>
         </div>
      </div>
   </div>
</header>

<main id="page" role="main">
   <div id="content" class="main-content">
      <div class="sqs-layout sqs-grid-12 columns-12">
         <div class="row sqs-row">
             <div class="col sqs-col-12 span-12">
<?php
       if(!isset($_GET['mod'])) {
?>
    <div class="box " id="esm-New">
       <div class="box-header">
            <h1>New</h1>
        </div>
        <div class="box-content">
       	    <ul>
               <li>Queues can use forwarders</li>
               <li>Centos 7 OS</li>
               <li>Change to pjsip stack</li>
       	    </ul>
        </div>
    </div>

     <div class="box " id="esm-load_average">
        <div class="box-header">
            <h1>Load Average</h1>
            <ul>
                <li><a href="#" class="reload" onclick="esm.reloadBlock('load_average');"><span class=reload>&#x21bb;</span></a></li>
            </ul>
        </div>

        <div class="box-content t-center">
            <div class="f-left w33p">
                <h3>1 min</h3>
                <input type="text" class="gauge" id="load-average_1" value="0" data-height="100" data-width="150" data-min="0" data-max="100" data-readOnly="true" data-fgColor="#BED7EB" data-angleOffset="-90" data-angleArc="180">
            </div>

            <div class="f-right w33p">
                <h3>15 min</h3>
                <input type="text" class="gauge" id="load-average_15" value="0" data-height="100" data-width="150" data-min="0" data-max="100" data-readOnly="true" data-fgColor="#BED7EB" data-angleOffset="-90" data-angleArc="180">
            </div>

            <div class="t-center">
                <h3>5 min</h3>
                <input type="text" class="gauge" id="load-average_5" value="0" data-height="100" data-width="150" data-min="0" data-max="100" data-readOnly="true" data-fgColor="#BED7EB" data-angleOffset="-90" data-angleArc="180">
            </div>
        </div>
    </div>

    <div class="box" id="esm-system">
        <div class="box-header">
            <h1>System</h1>
            <ul>
                <li><a href="#" class="reload" onclick="esm.reloadBlock('system');"><span class=reload>&#x21bb;</span></a></li>
            </ul>
        </div>
        <div class="box-content">
            <table class="firstBold">
                <tbody>
                    <tr>
                        <td>Hostname</td>
                        <td id="system-hostname"></td>
                    </tr>
                    <tr>
                        <td>OS</td>
                        <td id="system-os"></td>
                    </tr>
                    <tr>
                        <td>Kernel version</td>
                        <td id="system-kernel"></td>
                    </tr>
                    <tr>
                        <td>Uptime</td>
                        <td id="system-uptime"></td>
                    </tr>
                    <tr>
                        <td>Last boot</td>
                        <td id="system-last_boot"></td>
                    </tr>
                    <tr>
                        <td>Current user(s)</td>
                        <td id="system-current_users"></td>
                    </tr>
                    <tr>
                        <td>Server date & time</td>
                        <td id="system-server_date"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="box" id="esm-services">
         <div class="box-header">
             <h1>Services status</h1>
             <ul>
                 <li><a href="#" class="reload" onclick="esm.reloadBlock('services');"><span class=reload>&#x21bb;</span></a></li>
             </ul>
         </div>
         <div class="box-content">
             <table>
                 <tbody></tbody>
             </table>
         </div>
    </div>

    <div class="box" id="esm-disk">
        <div class="box-header">
            <h1>Disk usage</h1>
            <ul>
                <li><a href="#" class="reload" onclick="esm.reloadBlock('disk');"><span class=reload>&#x21bb;</span></a></li>
            </ul>
        </div>

        <div class="box-content">
            <table>
                <thead>
                    <tr>
                        <?php if ($Config->get('disk:show_filesystem')): ?>
                            <th class="w10p filesystem">Filesystem</th>
                        <?php endif; ?>
                        <th class="w20p">Mount</th>
                        <th>Use</th>
                        <th class="w15p">Free</th>
                        <th class="w15p">Used</th>
                        <th class="w15p">Total</th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
        </div>
    </div>

    <div class="box " id="esm-memory">
        <div class="box-header">
            <h1>Memory</h1>
            <ul>
                <li><a href="#" class="reload" onclick="esm.reloadBlock('memory');"><span class=reload>&#x21bb;</span></a></li>
            </ul>
        </div>

        <div class="box-content">
            <table class="firstBold">
                <tbody>
                    <tr>
                        <td class="w20p">Used %</td>
                        <td><div class="progressbar-wrap"><div class="progressbar" style="width: 0%;">0%</div></div></td>
                    </tr>
                    <tr>
                        <td class="w20p">Used</td>
                        <td id="memory-used"></td>
                    </tr>
                    <tr>
                        <td class="w20p">Free</td>
                        <td id="memory-free"></td>
                    </tr>
                    <tr>
                        <td class="w20p">Total</td>
                        <td id="memory-total"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="box " id="esm-cpu">
        <div class="box-header">
            <h1>CPU</h1>
            <ul>
                <li><a href="#" class="reload" onclick="esm.reloadBlock('cpu');"><span class=reload>&#x21bb;</span></a></li>
            </ul>
        </div>

        <div class="box-content">
            <table class="firstBold">
                <tbody>
                    <tr>
                        <td>Model</td>
                        <td id="cpu-model"></td>
                    </tr>
                    <tr>
                        <td>Cores</td>
                        <td id="cpu-num_cores"></td>
                    </tr>
                    <tr>
                        <td>Speed</td>
                        <td id="cpu-frequency"></td>
                    </tr>
                    <?php if ($Config->get('cpu:enable_temperature')): ?>
                        <tr>
                            <td>Temperature</td>
                            <td id="cpu-temp"></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php
       } else {
          switch ($_GET['mod'])   {
             case 'extlist':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
                   include_once "modules/userapplications/userapplications_func.php";
                   extlist();
                }
             break;
             case 'features':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
                   include_once "modules/userapplications/userapplications_func.php";
                   features();
                }
             break;
             case 'users':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/users/users_func.php";
                   user();
                }
             break;
             case 'add_user':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/users/users_func.php";
                   add_user();
                }
             break;
             case 'add_user_details':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/users/users_func.php";
                   add_user_details();
                }
             break;
             case 'modify_user':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/users/users_func.php";
                   modify_user();
                }
             break;
             case 'modify_user_details':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/users/users_func.php";
                   modify_user_details();
                }
             break;
             case 'delete_user':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/users/users_func.php";
                   delete_user();
                }
             break;
             case 'bulkextcreate':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/rt_extensions/rt_extensions_func.php";
                   bulkextcreate();
                }
             break;
             case 'add_bulkextcreate_details':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/rt_extensions/rt_extensions_func.php";
                   add_bulkextcreate_details();
                }
             break;
             case 'autherized_bulkextcreate':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/rt_extensions/rt_extensions_func.php";
                   autherized_bulkextcreate();
                }
             break;
             case 'csvimport':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/rt_extensions/rt_extensions_func.php";
                   csvimport();
                }
             break;
             case 'upload_csvimport':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/rt_extensions/rt_extensions_func.php";
                   upload_csvimport();
                }
             break;
             case 'voicemail':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
                   include_once "modules/voicemail/voicemail_func.php";
                   voicemail();
                }
             break;
             case 'add_voicemail':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
                   include_once "modules/voicemail/voicemail_func.php";
                    add_voicemail();
                }
             break;
             case 'add_voicemail_details':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
                   include_once "modules/voicemail/voicemail_func.php";
                   add_voicemail_details();
                }
             break;
             case 'modify_voicemail':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
                   include_once "modules/voicemail/voicemail_func.php";
                   modify_voicemail();
                }
             break;
             case 'modify_voicemail_details':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
                   include_once "modules/voicemail/voicemail_func.php";
                   modify_voicemail_details();
                }
             break;
             case 'delete_voicemail':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
                   include_once "modules/voicemail/voicemail_func.php";
                   delete_voicemail();
                }
             break;
             case 'pincodes':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
                   include_once "modules/pincodes/pincodes_func.php";
                   pincodes();
                }
             break;
             case 'addpin':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
                   include_once "modules/pincodes/pincodes_func.php";
                   add_pincodes();
                }
             break;
             case 'addpindetails':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
                   include_once "modules/pincodes/pincodes_func.php";
                   add_pincodes_details();
                }
             break;
             case 'editpin':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
                   include_once "modules/pincodes/pincodes_func.php";
                   edit_pincodes();
                }
             break;
             case 'editpindetails':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
                   include_once "modules/pincodes/pincodes_func.php";
                   edit_pincodes_details();
                }
             break;
             case 'delpin':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
                   include_once "modules/pincodes/pincodes_func.php";
                   del_pincodes();
                }
             break;
             case 'directory':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
                   include_once "modules/directory/directory_func.php";
                   directory();
                }
             break;
             case 'add_directory':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
                   include_once "modules/directory/directory_func.php";
                    add_directory();
                }
             break;
             case 'add_directory_details':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
                   include_once "modules/directory/directory_func.php";
                   add_directory_details();
                }
             break;
             case 'modify_directory':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
                   include_once "modules/directory/directory_func.php";
                   modify_directory();
                }
             break;
             case 'modify_directory_details':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
                   include_once "modules/directory/directory_func.php";
                   modify_directory_details();
                }
             break;
             case 'delete_directory':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
                   include_once "modules/directory/directory_func.php";
                   delete_directory();
                }
             break;
             case 'cloudcall':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
                   include_once "modules/cloudcall/cloudcall_func.php";
                   cloudcall();
                }
             break;
             case 'add_cloudcall':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
                   include_once "modules/cloudcall/cloudcall_func.php";
                    add_cloudcall();
                }
             break;
             case 'add_cloudcall_details':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
                   include_once "modules/cloudcall/cloudcall_func.php";
                   add_cloudcall_details();
                }
             break;
             case 'modify_cloudcall':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
                   include_once "modules/cloudcall/cloudcall_func.php";
                   modify_cloudcall();
                }
             break;
             case 'modify_cloudcall_details':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
                   include_once "modules/cloudcall/cloudcall_func.php";
                   modify_cloudcall_details();
                }
             break;
             case 'delete_cloudcall':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
                   include_once "modules/cloudcall/cloudcall_func.php";
                   delete_cloudcall();
                }
             break;
             case 'agents':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
                   include_once "modules/agents/agents_func.php";
                   agents();
                }
             break;
             case 'add_agents':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
                   include_once "modules/agents/agents_func.php";
                    add_agents();
                }
             break;
             case 'add_agents_details':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
                   include_once "modules/agents/agents_func.php";
                   add_agents_details();
                }
             break;
             case 'modify_agents':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
                   include_once "modules/agents/agents_func.php";
                   modify_agents();
                }
             break;
             case 'modify_agents_details':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
                   include_once "modules/agents/agents_func.php";
                   modify_agents_details();
                }
             break;
             case 'delete_agents':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
                   include_once "modules/agents/agents_func.php";
                   delete_agents();
                }
             break;
             case 'speeddials':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
                   include_once "modules/speeddial/speeddial_func.php";
                   speeddials();
                }
             break;
             case 'add_speeddials':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
                   include_once "modules/speeddial/speeddial_func.php";
                    add_speeddials();
                }
             break;
             case 'add_speeddials_details':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
                   include_once "modules/speeddial/speeddial_func.php";
                   add_speeddials_details();
                }
             break;
             case 'modify_speeddials':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
                   include_once "modules/speeddial/speeddial_func.php";
                   modify_speeddials();
                }
             break;
             case 'modify_speeddials_details':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
                   include_once "modules/speeddial/speeddial_func.php";
                   modify_speeddials_details();
                }
             break;
             case 'delete_speeddials':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
                   include_once "modules/speeddial/speeddial_func.php";
                   delete_speeddials();
                }
             break;
             case 'queues':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
                   include_once "modules/queues/queues_func.php";
                   queues();
                }
             break;
             case 'add_queues':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
                   include_once "modules/queues/queues_func.php";
                   add_queues();
                }
             break;
             case 'add_queues_details':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
                   include_once "modules/queues/queues_func.php";
                   add_queues_details();
                }
             break;
             case 'modify_queues':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
                   include_once "modules/queues/queues_func.php";
                   modify_queues();
                }
             break;
             case 'modify_queues_details':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
                   include_once "modules/queues/queues_func.php";
                   modify_queues_details();
                }
             break;
             case 'delete_queues':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
                   include_once "modules/queues/queues_func.php";
                   delete_queues();
                }
             break;
             case 'cdr':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '2' || $row[0]['user_type'] == '3') {
                   include_once "modules/cdr/cdr_func.php";
                   cdr();
                }
             break;
             case 'ext_usage':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '2' || $row[0]['user_type'] == '3') {
                   include_once "modules/pbxreports/pbxreports_func.php";
                   ext_usage();
                }
             break;
             case 'topdialnum':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '2' || $row[0]['user_type'] == '3') {
                   include_once "modules/pbxreports/pbxreports_func.php";
                   topdialnum();
                }
             break;
             case 'surveys':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '2' || $row[0]['user_type'] == '3') {
                   include_once "modules/surveys/surveys_func.php";
                   surveys();
                }
             break;
             case 'sippeers':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '2' || $row[0]['user_type'] == '3') {
                   include_once "modules/pbxreports/pbxreports_func.php";
                   sippeers();
                }
             break;
             case 'showtrunks':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '2' || $row[0]['user_type'] == '3') {
                   include_once "modules/pbxreports/pbxreports_func.php";
                   showtrunks();
                }
             break;
             case 'queuesreport':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '2' || $row[0]['user_type'] == '3') {
                   include_once "modules/pbxreports/pbxreports_func.php";
                   queuesreport();
                }
             break;
             case 'singlequeuereport':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '2' || $row[0]['user_type'] == '3') {
                   include_once "modules/pbxreports/pbxreports_func.php";
                   singlequeuereport();
                }
             break;
             case 'activechannels':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '2' || $row[0]['user_type'] == '3') {
                   include_once "modules/pbxreports/pbxreports_func.php";
                   activechannels();
                }
             break;
             case 'callgraphs':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '2' || $row[0]['user_type'] == '3') {
                   include_once "modules/pbxreports/pbxreports_func.php";
                   callgraphs();
                }
             break;
             case 'trunk':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/trunk/trunk_func.php";
                   trunk();
                }
             break;
             case 'trunkselect':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/trunk/trunk_func.php";
                   trunkselect();
                }
             break;
             case 'add_iaxtrunk':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/trunk/trunk_func.php";
                   add_iaxtrunk();
                }
             break;
             case 'add_iaxtrunk_details':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/trunk/trunk_func.php";
                   add_iaxtrunk_details();
                }
             break;
             case 'modify_iaxtrunk':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/trunk/trunk_func.php";
                   modify_iaxtrunk();
                }
             break;
             case 'modify_iaxtrunk_details':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/trunk/trunk_func.php";
                   modify_iaxtrunk_details();
                }
             break;
             case 'delete_iaxtrunk':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/trunk/trunk_func.php";
                   delete_iaxtrunk();
                }
             break;
             case 'add_siptrunk':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/trunk/trunk_func.php";
                   add_siptrunk();
                }
             break;
             case 'add_siptrunk_details':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/trunk/trunk_func.php";
                   add_siptrunk_details();
                }
             break;
             case 'modify_siptrunk':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/trunk/trunk_func.php";
                   modify_siptrunk();
                }
             break;
             case 'modify_siptrunk_details':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/trunk/trunk_func.php";
                   modify_siptrunk_details();
                }
             break;
             case 'delete_siptrunk':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/trunk/trunk_func.php";
                   delete_siptrunk();
                }
             break;
             case 'forwarders':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
                   include_once "modules/forwarders/forwarders_func.php";
                   forwarders();
                }
             break;
             case 'add_forwarders':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
                   include_once "modules/forwarders/forwarders_func.php";
                   add_forwarders();
                }
             break;
             case 'add_forwarders_details':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
                   include_once "modules/forwarders/forwarders_func.php";
                   add_forwarders_details();
                }
             break;
             case 'modify_forwarders':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
                   include_once "modules/forwarders/forwarders_func.php";
                   modify_forwarders();
                }
             break;
             case 'modify_forwarders_details':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
                   include_once "modules/forwarders/forwarders_func.php";
                   modify_forwarders_details();
                }
             break;
             case 'delete_forwarders':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
                   include_once "modules/forwarders/forwarders_func.php";
                   delete_forwarders();
                }
             break;
             case 'add_timeconditions':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/timeconditions/timeconditions_func.php";
                   add_timeconditions();
                }
             break;
             case 'timeconditions':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/timeconditions/timeconditions_func.php";
                   timeconditions();
                }
             break;
             case 'add_time':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/timeconditions/timeconditions_func.php";
                   add_time();
                }
             break;
             case 'modify_timeconditions':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/timeconditions/timeconditions_func.php";
                   modify_timeconditions();
                }
             break;
             case 'modify_time_details':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/timeconditions/timeconditions_func.php";
                   modify_time_details();
                }
             break;
             case 'delete_timeconditions':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/timeconditions/timeconditions_func.php";
                   delete_timeconditions();
                }
             break;
             case 'global':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/globals/globals_func.php";
                   globals();
                }
             break;
             case 'modify_global':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/globals/globals_func.php";
                   modify_global();
                }
             break;
             case 'modify_global_details':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/globals/globals_func.php";
                   modify_global_details();
                }
             break;
             case 'modify_dep_trunk':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/globals/globals_func.php";
                   modify_dep_trunk();
                }
             break;
             case 'modify_dep_trunk_details':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/globals/globals_func.php";
                   modify_dep_trunk_details();
                }
             break;
             case 'inbound':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/inbound/inbound_func.php";
                   inbound();
                }
             break;
             case 'add_inbound':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/inbound/inbound_func.php";
                   add_inbound();
                }
             break;
             case 'add_in_route':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/inbound/inbound_func.php";
                   add_in_route();
                }
             break;
             case 'modify_inbound':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/inbound/inbound_func.php";
                   modify_inbound();
                }
             break;
             case 'modify_inbound_details':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/inbound/inbound_func.php";
                   modify_inbound_details();
                }
             break;
             case 'delete_inbound':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/inbound/inbound_func.php";
                   delete_inbound();
                }
             break;
             case 'ivr':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/ivr/ivr_func.php";
                   ivr();
                }
             break;
             case 'add_ivr':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/ivr/ivr_func.php";
                   add_ivr();
                }
             break;
             case 'add_ivr_details':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/ivr/ivr_func.php";
                   add_ivr_details();
                }
             break;
             case 'modify_ivr':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/ivr/ivr_func.php";
                   modify_ivr();
                }
             break;
             case 'modify_ivr_details':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/ivr/ivr_func.php";
                   modify_ivr_details();
                }
             break;
             case 'delete_ivr':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/ivr/ivr_func.php";
                   delete_ivr();
                }
             break;
             case 'officehours':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/officehours/officehours_func.php";
                   officehours();
                }
             break;
             case 'add_officehours':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/officehours/officehours_func.php";
                   add_officehours();
                }
             break;
             case 'add_officehours_details':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/officehours/officehours_func.php";
                   add_officehours_details();
                }
             break;
             case 'modify_officehours':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/officehours/officehours_func.php";
                   modify_officehours();
                }
             break;
             case 'modify_officehours_details':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/officehours/officehours_func.php";
                   modify_officehours_details();
                }
             break;
             case 'delete_officehours':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/officehours/officehours_func.php";
                   delete_officehours();
                }
             break;
             case 'afterhours':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/afterhours/afterhours_func.php";
                   afterhours();
                }
             break;
             case 'add_afterhours':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/afterhours/afterhours_func.php";
                   add_afterhours();
                }
             break;
             case 'add_afterhours_details':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/afterhours/afterhours_func.php";
                   add_afterhours_details();
                }
             break;
             case 'modify_afterhours':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/afterhours/afterhours_func.php";
                   modify_afterhours();
                }
             break;
             case 'modify_afterhours_details':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/afterhours/afterhours_func.php";
                   modify_afterhours_details();
                }
             break;
             case 'delete_afterhours':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/afterhours/afterhours_func.php";
                   delete_afterhours();
                }
             break;
             case 'holiday':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/holiday/holiday_func.php";
                   holiday();
                }
             break;
             case 'add_holiday':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/holiday/holiday_func.php";
                   add_holiday();
                }
             break;
             case 'add_holiday_details':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/holiday/holiday_func.php";
                   add_holiday_details();
                }
             break;
             case 'modify_holiday':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/holiday/holiday_func.php";
                   modify_holiday();
                }
             break;
             case 'modify_holiday_details':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/holiday/holiday_func.php";
                   modify_holiday_details();
                }
             break;
             case 'delete_holiday':
                if ($row[0]['user_type'] == '1') {
                   include_once "modules/holiday/holiday_func.php";
                   delete_holiday();
                }
             break;
             case 'rt_extensions':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
                   include_once "modules/rt_extensions/rt_extensions_func.php";
                   rt_extensions();
                }
             break;
             case 'add_rt_extensions':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
                   include_once "modules/rt_extensions/rt_extensions_func.php";
                   add_rt_extensions();
                }
             break;
             case 'add_rt_extensions_details':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
                   include_once "modules/rt_extensions/rt_extensions_func.php";
                   add_rt_extensions_details();
                }
             break;
             case 'modify_rt_extensions':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
                   include_once "modules/rt_extensions/rt_extensions_func.php";
                   modify_rt_extensions();
                }
             break;
             case 'modify_rt_extensions_details':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
                   include_once "modules/rt_extensions/rt_extensions_func.php";
                   modify_rt_extensions_details();
                }
             break;
             case 'delete_rt_extensions':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '3') {
                   include_once "modules/rt_extensions/rt_extensions_func.php";
                   delete_rt_extensions();
                }
             break;
             case 'cereport':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '2' || $row[0]['user_type'] == '3') {
                   include_once "modules/master/master_func.php";
                   master();
                }
             break;
             case 'master':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '2' || $row[0]['user_type'] == '3') {
                   include_once "modules/master/master_func.php";
                   master();
                }
             break;
             case 'allplan':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '2' || $row[0]['user_type'] == '3') {
                  include_once "modules/details/details_func.php";
                  allplan();
                }
             break;
             case 'specificplan':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '2' || $row[0]['user_type'] == '3') {
                  include_once "modules/details/details_func.php";
                  specificplan();
                }
             break;
             case 'depallplan':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '2' || $row[0]['user_type'] == '3') {
                  include_once "modules/details/details_func.php";
                  depallplan();
                }
             break;
             case 'depspecificplan':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '2' || $row[0]['user_type'] == '3') {
                  include_once "modules/details/details_func.php";
                  depspecificplan();
                }
             break;
             case 'depspecificexten':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '2' || $row[0]['user_type'] == '3') {
                  include_once "modules/details/details_func.php";
                  depspecificexten();
                }
             break;
             case 'depspecificaccountcode':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '2' || $row[0]['user_type'] == '3') {
                  include_once "modules/details/details_func.php";
                  depspecificaccountcode();
                }
             break;
             case 'allexten':
                if ($row[0]['user_type'] == '1' || $row[0]['user_type'] == '2' || $row[0]['user_type'] == '3') {
                  include_once "modules/departments/departments_func.php";
                  allexten();
                }
             break;

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
</body>
</html>

<?php
}
