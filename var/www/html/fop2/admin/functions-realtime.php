<?php
function realtime_populate_contexts_from_tenants() {
   $panelcontexts[0]='GENERAL';
   return $panelcontexts;
}
//require_once("dblib.php");
//require_once("functions.php");

//$db     = new dbcon('localhost','root','','asterisk', true, true, false);

//print_r(realtime_check_extension_usage());

// Same as functions-astdb but querying the asterisk database for sip peers and voicemail tables
function realtime_check_extension_usage() {
    global $db, $astman, $conf;
    $extenlist = array();

    $dbtablesip       = "asterisk.ps_endpoints AS ps_endpoints";
    $dbtableiax       = "asterisk.iaxfriends AS iaxfriends";
    $dbtablevoicemail = "asterisk.voicemail AS voicemail";
    $dbtablequeues = "asterisk.queues AS queues";

    // Extensions
    $fields= "CONCAT('PJSIP/',ps_endpoints.id) as dial,ps_endpoints.id as extension,ps_endpoints.context as context,callerid as name,ps_endpoints.id as mailbox,voicemail.password as vmpin,email";
    $results = $db->select($fields,$dbtablesip,"LEFT JOIN $dbtablevoicemail on ps_endpoints.id=voicemail.mailbox","","","","");
    if(is_array($results)) {
        $trunkcount=0;
        foreach ($results as $result) {

            if(preg_match("/[,&]/",$result['dial'])) {
                $partes = preg_split("/[,&]/",$result['dial']);
                $result['dial']=$partes[0];
            }

            $thisexten = $result['extension'];
            $vmpin     = $result['vmpin'];
            $vmemail   = $result['email'];
            $data = array();
            $data['name'] = $result['name'];
            $data['channel'] = $result['dial'];
            $data['mailbox'] = $result['mailbox'];

            if( $result['name'] != "") {
               $data['type']='extension';
               $data['exten']   = $thisexten;
            } else {
               $trunkcount++;
               $data['type']='trunk';
               $data['exten']=$thisexten;
            }

            $data['context'] = $result['context'];
            $data['vmpin']   = $vmpin;
            $data['email']   = $vmemail;
            $data['customastdb'] = 'CF/'.$thisexten;
            $data['context_id'] = 0; 

            $extenlist[$data['channel']]  = $data;

        }
    }

    //IAX2
    $fields= "CONCAT('IAX2/',name) as dial,name,context";
    $results = $db->select($fields,$dbtableiax);
    if(is_array($results)) {
        $trunkcount=0;
        foreach ($results as $result) {

            if(preg_match("/[,&]/",$result['dial'])) {
                $partes = preg_split("/[,&]/",$result['dial']);
                $result['dial']=$partes[0];
            }

            $thisexten = $result['name'];
            $vmpin     = "";
            $vmemail   = "";
            $data = array();
            $data['name'] = $result['name'];
            $data['channel'] = $result['dial'];
            $data['mailbox'] = "";

            $trunkcount++;
            $data['type']='trunk';
            $data['exten']=$thisexten;

            $data['context'] = $result['context'];
            $data['vmpin']   = $vmpin;
            $data['email']   = $vmemail;
            $data['customastdb'] = 'CF/'.$thisexten;
            $data['context_id'] = 0;

            $extenlist[$data['channel']]  = $data;
        }
    }

    // Queues
    $fields= "name";
    $results = $db->select($fields,$dbtablequeues,"","","","","");
    if(is_array($results)) {
        foreach ($results as $result) {
           $thisexten = $result['name'];
           $data = array();
           $data['channel'] = "QUEUE/$thisexten";
           $data['type']    = "queue";
           $data['context'] = "queue-$thisexten";
           $data['name']    = $thisexten;
           $data['exten']   = $thisexten;
           $data['context_id'] = 0; 
           $extenlist[$data['channel']] = $data;
        }
    }

    return $extenlist;

}
