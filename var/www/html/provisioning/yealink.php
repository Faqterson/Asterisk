<?php 
$server = $_SERVER['SERVER_NAME'];
$upmac = strtoupper($_GET['mac']);

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

$row = db_query("SELECT ps_endpoints.id as id, password, callerid, mac, vlan, registrar, dhcp, ip, subnet, gateway, dns FROM ps_endpoints LEFT JOIN ps_auths USING (id) LEFT JOIN departments ON (sip = ps_endpoints.id) LEFT JOIN provisioning ON (sipid = ps_endpoints.id) LEFT JOIN ext_features ON extension = ps_endpoints.id WHERE mac = ?", array($upmac));
//$row=db_query("SELECT name, secret, callerid, dhcp, vlan, registrar, ip, subnet, gateway, dns FROM sip LEFT OUTER JOIN provisioning ON (sip.uniqueid = sipid) WHERE mac = ?", array($upmac));

if (preg_match("/^Yealink.*/",$_SERVER['HTTP_USER_AGENT'])) 
{
header('Content-Type: text');
?>
#!version:1.0.0.1

##File header "#!version:1.0.0.1" can not be edited or deleted.##
#######################################################################################
##                          Network                                                  ## 
#######################################################################################
#Configure the WAN port type; 0-DHCP (default), 1-PPPoE, 2-Static IP Address;
#Require reboot;
network.internet_port.type = <?php if (($row[0]['dhcp'] == 0) && !is_null($row[0]['dhcp'])) {print "2\n";} ELSE {print "0\n";}?>

#Configure the static IP address, subnet mask, gateway and DNS server;
#Require Reboot;
network.internet_port.ip = <?php print $row[0]['ip'] . "\n" ?>
network.internet_port.mask = <?php print $row[0]['subnet'] . "\n" ?>
network.internet_port.gateway = <?php print $row[0]['gateway'] . "\n" ?>
network.primary_dns= <?php print $row[0]['dns'] . "\n" ?>
network.secondary_dns =
network.dhcp_host_name = <?php print $row[0]['id'] . "-yealink\n" ?>

#Enable or disable the VLAN of WAN prot; 0-Disabled (default), 1-Enabled;
#Require reboot;
network.vlan.internet_port_enable = <?php if (is_null($row[0]['vlan']) || empty($row[0]['vlan'])) {print "0\n";} ELSE {print "1\n";}?>

#Configure the VLAN ID, it ranges from 0 to 4094, the default value is 0.
#Require reboot;
network.vlan.internet_port_vid = <?php print $row[0]['vlan'] . "\n" ?>

#Configure the VLAN priority, it ranges from 0 (default) to 7. 
#Require reboot;            
network.vlan.internet_port_priority = 5

#Configure the voice QOS. It ranges from 0 to 63, the default value is 46.
#Require reboot;
network.qos.rtptos = 46

#Configure the SIP QOS. It ranges from 0 to 63, the default value is 26.
#Require reboot;
network.qos.signaltos = 46

#######################################################################################
##                 Auto Provisioning                                                 ##      
#######################################################################################

#Configure the auto provision mode;
#0-Disabled (default), 1-Power on, 4-Repeatedly, 5-Weekly, 6-Power on + Repeatedly, 7-Power on + Weekly; 
auto_provision.mode = 6

#Configure the interval (in minutes) for the phone to check new configuration files. It ranges from 1 to 43200, the default value is 1440.
#It is only applicable to "Repeatedly" and "Power on + Repeatedly" modes.
auto_provision.schedule.periodic_minute = 240

#Configure the start time of the day for the phone to check new configuration files. The default value is 00:00.
#It is only applicable to "Weekly" and "Power on + Weekly" modes. 
#If the desired start time of the day is seven forty-five a.m., the value format is 07:45.
auto_provision.schedule.time_from = 1:00

#Configure the end time of the day for the phone to check new configuration files.  The default time is 00:00.
#It is only applicable to "Weekly" and "Power on + Weekly" modes.
#If the desired end time of the day is seven forty-five p.m., the value format is 19:45.
auto_provision.schedule.time_to = 2:00

#Configure the day of week for the phone to check new configuration files. The default vaule is 0123456.
#0-Sunday,1-Monday,2-Tuesday,3-Wednesday,4-Thursday,5-Friday,6-Saturday;
#It is only applicable to "Weekly" and "Power on + Weekly" modes.
#If the desired week is Monday, Tuesday and Wednesday, the value format is 012.
auto_provision.schedule.dayofweek = 0123456

#Configure the URL of the auto provisioning server.
auto_provision.server.url = http://<?php if (is_null($row[0]['vlan'])  || empty($row[0]['vlan'])) {print $server . "\n";} ELSE {print $row[0]['registrar'] . "\n";}?>

#######################################################################################
##         	                   Time Settings                                         ##
#######################################################################################

#Configure the time zone and time zone name. The time zone ranges from -11 to +12, the default value is +8. 
#The default time zone name is China(Beijing).  
#Refer to Yealink IP Phones User Guide for more available time zones and time zone names. 
#local_time.time_zone = +8
#local_time.time_zone_name = China(Beijing) 
local_time.time_zone = +2

#Configure the domain name or the IP address of the NTP server. The default value is cn.pool.ntp.org.
local_time.ntp_server1 = <?php if (is_null($row[0]['vlan']) || empty($row[0]['vlan'])) {print $server . "\n";} ELSE {print $row[0]['registrar'] . "\n";}?>
local_time.ntp_server2 = <?php if (is_null($row[0]['vlan']) || empty($row[0]['vlan'])) {print $server . "\n";} ELSE {print $row[0]['registrar'] . "\n";}?>

#Configure the update interval (in seconds) when using the NTP server. The default value is 1000.
local_time.interval = 1000

#Configure the daylight saving time feature; 0-Disabled, 1-Enabled, 2-Automatic (default); 
local_time.summer_time = 0

#######################################################################################
##         	              LDAP Settings                                              ##
#######################################################################################
#Configure the search criteria for name and number lookups.
ldap.enable = 1
ldap.name_filter = (&(cn=%))
ldap.number_filter = (|(mobile=%))
ldap.host = <?php if (is_null($row[0]['vlan']) || empty($row[0]['vlan'])) {print $server . "\n";} ELSE {print $row[0]['registrar'] . "\n";}?>
ldap.port = 389
ldap.base = o=dbs
ldap.user = cn=admin,o=dbs
ldap.password = VGJd#$xx

#Specify the maximum of the displayed search results. It ranges from 1 to 32000, the default value is 50.
ldap.max_hits = 50
ldap.name_attr = cn
ldap.numb_attr = mobile
ldap.display_name = %cn

#Configure the LDAP version. The valid value is 2 or 3 (default).
ldap.version = 3

#Conifugre the search delay time. It ranges from 0 (default) to 2000.
ldap.search_delay = 0 

#Enable or disable the phone to query the contact name from the LDAP server when receiving an incoming call; 0-Disabled (default), 1-Enabled;
ldap.call_in_lookup = 1 

#Enable or disable the phone to sort the search results in alphabetical order; 0-Disabled (default), 1-Enabled; 
ldap.ldap_sort = 1

#Enable or disable the phone to query the LDAP server when in the pre-dialing or the dialing state; 0-Disabled (default), 1-Enabled;
ldap.dial_lookup = 1  

#######################################################################################
##         	              Phone Features                                             ##
#######################################################################################

#Enable or disable the intercom feature; 0-Disabled, 1-Enabled (default);
features.intercom.allow = 

#Enable or disable the phone to suppress the display of DTMF digits; 0-Disabled (default), 1-Enabled;
features.dtmf.hide = 1

features.direct_ip_call_enable = 0

features.dnd.on_code = *40
features.dnd.off_code = *40

#######################################################################################
##                     Configure the access URL of firmware                          ##                                 
#######################################################################################
#Before using this parameter, you should store the desired firmware (x.70.x.x.rom) to the provisioning server.
firmware.url = 

#######################################################################################
##                           Account1 Settings                                       ##                                                                          
#######################################################################################

#Enable or disable the account1, 0-Disabled (default), 1-Enabled;
account.1.enable = 1

#Configure the label displayed on the LCD screen for account1.
account.1.label = <?php print $row[0]['id'] . "\n";?>

#Configure the display name of account1.
account.1.display_name = <?php print $row[0]['id'] . "\n";?>

#Configure the username and password for register authentication.
account.1.auth_name = <?php print $row[0]['id'] . "\n";?>
account.1.password = <?php print $row[0]['password'] . "\n";?>

#Configure the register user name.
account.1.user_name = <?php print $row[0]['id'] . "\n";?>

#Configure the SIP server address.
account.1.sip_server_host = <?php if (is_null($row[0]['vlan']) || empty($row[0]['vlan'])) {print $server . "\n";} ELSE {print $row[0]['registrar'] . "\n";}?>

#Specify the port for the SIP server. The default value is 5060.
account.1.sip_server_port = 5060

#Enable or disable to use the outbound proxy server; 0-Disabled (default), 1-Enabled; 
account.1.outbound_proxy_enable = 0

#Configure the transport type; 0-UDP (default), 1-TCP, 2-TLS, 3-DNS SRV;
account.1.transport = UDP

#Configure the voice mail number of account1.
voice_mail.number.1 = *10

#Enable or disable the anonymous call feature; 0-Disabled (default), 1-Enabled;
account.1.anonymous_call = 0

#Configure the register expiry time (in seconds), the default value is 3600.
account.1.expires = 60

#Enable or disable the session timer, 0-Disabled (default), 1-Enabled;  
account.1.session_timer.enable = 1

#Configure the refresh session timer interval (in seconds). It ranges from 1 to 9999.
account.1.session_timer.expires = 3600     

#Configure SRTP
#account.1.srtp_encryption = 1

#Configure the RTP packet time. The valid values are 0 (Disabled), 10, 20 (default), 30, 40, 50, 60.
account.1.ptime = 20

#Enable or disable call pickup using dialog-info SIP header; 0-Disabled (default), 1-Enabled;
account.1.dialoginfo_callpickup = 1

#Configure the DTMF type; 0-INBAND, 1-RFC2833 (default), 2-SIP INFO, 3-AUTO+SIP INFO;
account.1.dtmf.type = 1

account.1.codec.1.enable = 1
account.1.codec.1.payload_type = G729
account.1.codec.1.priority = 1
account.1.codec.1.rtpmap = 18

account.1.codec.2.enable = 0
account.1.codec.2.payload_type = PCMA
account.1.codec.2.priority = 2 
account.1.codec.2.rtpmap = 8

account.1.codec.3.enable = 0 
account.1.codec.3.payload_type = G723_53
account.1.codec.3.priority =0
account.1.codec.3.rtpmap = 4

account.1.codec.4.enable = 0
account.1.codec.4.payload_type = G723_63
account.1.codec.4.priority = 0
account.1.codec.4.rtpmap = 4

account.1.codec.5.enable = 1
account.1.codec.5.payload_type = G729
account.1.codec.5.priority = 3
account.1.codec.5.rtpmap = 18

account.1.codec.6.enable = 0
account.1.codec.6.payload_type = G722
account.1.codec.6.priority = 4
account.1.codec.6.rtpmap = 9

account.1.codec.7.enable = 0
account.1.codec.7.payload_type = iLBC
account.1.codec.7.priority =  0
account.1.codec.7.rtpmap = 102

account.1.codec.8.enable = 0
account.1.codec.8.payload_type = G726-16
account.1.codec.8.priority = 0
account.1.codec.8.rtpmap = 112

account.1.codec.9.enable = 0
account.1.codec.9.payload_type = G726-24
account.1.codec.9.priority = 0
account.1.codec.9.rtpmap = 102

account.1.codec.10.enable = 0
account.1.codec.10.payload_type = G726-32 
account.1.codec.10.priority = 0 
account.1.codec.10.rtpmap = 9

account.1.codec.11.enable = 0
account.1.codec.11.payload_type = G726-40
account.1.codec.11.priority = 0
account.1.codec.11.rtpmap = 104

account.1.codec.12.enable = 0
account.1.codec.12.payload_type = iLBC_13_3
account.1.codec.12.priority = 0 
account.1.codec.12.rtpmap = 97

account.1.codec.13.enable = 0
account.1.codec.13.payload_type = iLBC_15_2
account.1.codec.13.priority = 0 
account.1.codec.13.rtpmap = 97 

#######################################################################################
##                                   Security                                        ##       
#######################################################################################

###Define the login username and password of the user, var and administrator.
###If you change the username of the administrator from "admin" to "admin1", your new administrator's username should be configured as: security.user_name.admin = admin1.
###If you change the password of the administrator from "admin" to "admin1pwd", your new administrator's password should be configured as: security.user_password = admin1:admin1pwd.

###The following examples change the user's username to "user23" and the user's password to "user23pwd".
###security.user_name.user = user23
###security.user_password = user23:user23pwd
###The following examples change the var's username to "var55" and the var's password to "var55pwd".
###security.user_name.var = var55
###security.user_password = var55:var55pwd
#security.user_name.user = 
#security.user_name.admin = 
#security.user_name.var = 
security.user_password = admin:n3wpass69

#######################################################################################
##                                   LLDP                                            ##       
#######################################################################################
network.lldp.enable = 0

#######################################################################################
##                          DND                                                      ##                                       
#######################################################################################

#Configure the DND key mode; 0-Phone mode (default), 1-Custom mode.
features.dnd_mode =

#Enable or disable the DND feautre for account1; 0-Disabled (default), 1-Enabled;
#account.1.dnd.enable = 

#Configure the DND on mode and off code for account1
#account.1.dnd.on_code = *40
#account.1.dnd.off_code = *40

linekey.2.type = 15
linekey.2.line = 1

programablekey.2.type = 38
programablekey.2.line = 0
programablekey.2.xml_phonebook = -1
programablekey.2.history_type = -1
programablekey.6.type = 38
programablekey.6.line = 0
programablekey.6.xml_phonebook = -1
programablekey.6.history_type = -1

programablekey.1.type = 28
programablekey.2.type = 38
programablekey.3.type = 0
programablekey.4.type = 30
programablekey.6.type = 38

<?php
} else {
print "HTTP/1.0 404 Not Found";
}
?>
