<?php

function db_query($sqlquery,$data)
{
        $con_str = 'DRIVER={MySQL};SERVER=localhost;DATABASE=asterisk';
        $user = 'root';
        $pass = '';
        $con = odbc_connect( $con_str, $user, $pass );

        $sqlrow = array();

        $prep = odbc_prepare($con, $sqlquery);
        if(!$prep) die("could not prepare statement ".$query_string);
        $result = odbc_execute($prep, $data);

        While ($tmp = odbc_fetch_array($prep))
        {
                array_push($sqlrow, $tmp);
        }
        odbc_close($con);
        return $sqlrow;
}

if(isset($_POST["download"])) 
{  
   header('Content-Type: text/csv; charset=utf-8');  
   header('Content-Disposition: attachment; filename=extensions.csv');  

   $output = fopen("php://output", "w");
   fputcsv($output, array('id','callerid','secret','namedcallgroup','namedpickupgroup','department','disallow','allow','dtmfmode','context','mac','vlan','registrar','dhcp','ip','subnet','gateway','dns','outcallerid','queue_out','callforwarddst', 'callforwardbusydst', 'dnd', 'callwaiting', 'international', 'national', 'cellular', 'internal', 'requirepin'));

   $row = db_query("SELECT ps_endpoints.id, callerid, password, named_call_group, named_pickup_group, department, disallow, allow, dtmf_mode, context, mac, vlan, registrar, dhcp, ip, subnet, gateway, dns, outcallerid, queue_out, callforwarddst, callforwardbusydst, dnd, callwaiting, international, national, cellular, internal, requirepin FROM ps_endpoints LEFT JOIN ps_auths ON (ps_endpoints.id = ps_auths.id) LEFT JOIN departments ON (sip = ps_endpoints.id) LEFT JOIN provisioning ON (sipid = ps_endpoints.id) LEFT JOIN ext_features ON extension = ps_endpoints.id LEFT JOIN ps_registrations ON (ps_endpoints.id = ps_registrations.id) WHERE ps_registrations.id is null", array());  

   foreach($row as $key => $value)
   {  
      fputcsv($output, $value);
   }  

   fclose($output);  
}

?>
