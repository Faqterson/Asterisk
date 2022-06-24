<?php 
$server = $_SERVER['SERVER_NAME'];
$upmac = strtoupper($_GET['mac']);
$agent = $_SERVER['HTTP_USER_AGENT'];

$agent = preg_replace("/[()]/", "", $agent);

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

if (preg_match("/^Mozilla\/4.0\ \(compatible;\ snom.*/",$_SERVER['HTTP_USER_AGENT']))
{
?>
<html>
<pre>
update_policy$: auto_update
setting_server$: http://<?php if (is_null($row[0]['vlan'])  || empty($row[0]['vlan'])) {print $server . "\n";} ELSE {print $row[0]['registrar'] . "\n";}?>
firmware_status$: http://<?php if (is_null($row[0]['vlan'])  || empty($row[0]['vlan'])) {print $server . "/snom/firmware.php\n";} ELSE {print $row[0]['registrar'] . "/snom/firmware.php\n";}?> 
ntp_server$: <?php if (is_null($row[0]['vlan'])  || empty($row[0]['vlan'])) {print $server . "\n";} ELSE {print $row[0]['registrar'] . "\n";}?> 
pnp_config$: off
http_user$: admin
http_pass$: testpass
admin_mode_password$: 6512
tone_scheme$: GBR
timezone$: CAT+2
challenge_response$: off
ignore_security_warning$: on
time_24_format$: on
block_url_dialing$: on
display_method$: display_name_number
enable_keyboard_lock$: off
cw_dialtone$: off
dkey_retrieve$: speed *10
dkey_dnd$: speed *40
watchdog$: off
vol_speaker_mic$: 5
vol_handset_mic$: 5
handsfree_mode$: quiet
intercom_enabled$: off
admin_mode$: off
subscription_delay$: 60
network_id_port$: 
user_host1$: <?php if (is_null($row[0]['vlan'])  || empty($row[0]['vlan'])) {print $server . "\n";} ELSE {print $row[0]['registrar'] . "\n";}?> 
user_outbound1$: 
sip_retry_t1$: 500
user_expiry1$: 600
reboot_after_nr$: 30
codec_tos$: 184
signaling_tos$: 184
user_subscription_expiry1$: 600
keepalive_interval1$: 30
settings_refresh_timer$: 3600
redirect_ringing$: off
message_led_other$: off
conf_hangup$: on
silence_compression$: off
advertisement$: off
advertisement_url$:
jb$: off
show_ivr_digits$: off
timer_support$: off
user_srtp1$: off
user_symmetrical_rtp1$: off
user_full_sdp_answer1$: off
user_savp1$: off
ip_frag_enable$: off
codec_priority_list1$: pcmu,pcma,g729,telephone-event
codec_priority_list2$: pcmu,pcma,gsm,g729,telephone-event
codec_priority_list3$: pcmu,pcma,gsm,g729,telephone-event
codec_priority_list4$: pcmu,pcma,gsm,g729,telephone-event
codec_priority_list5$: pcmu,pcma,gsm,g729,telephone-event
codec_priority_list6$: pcmu,pcma,gsm,g729,telephone-event
codec_priority_list7$: pcmu,pcma,gsm,g729,telephone-event
codec_priority_list8$: pcmu,pcma,gsm,g729,telephone-event
codec_priority_list9$: pcmu,pcma,gsm,g729,telephone-event
codec_priority_list10$: pcmu,pcma,gsm,g729,telephone-event
codec_priority_list11$: pcmu,pcma,gsm,g729,telephone-event
codec_priority_list12$: pcmu,pcma,gsm,g729,telephone-event
user_realname1$: <?php print $row[0]['id']. "\n";?>
user_name1$: <?php print $row[0]['id'] . "\n";?>
user_host1$: <?php if (is_null($row[0]['vlan'])  || empty($row[0]['vlan'])) {print $server . "\n";} ELSE {print $row[0]['registrar'] . "\n";}?>
user_pname1$: <?php print $row[0]['id'] . "\n";?>
user_pass1$: <?php print $row[0]['password'] . "\n";?>
user_mailbox1$: <?php print $row[0]['id'] . "\n";?>
user_idle_text1$: <?php print $row[0]['id'] . "\n";?>
user_ringer1$: Ringer6
speaker_toggles_device$: on
//fkey3$: keyevent F_DIRECTORY_SEARCH
gui_fkey1$: keyevent F_CALL_LIST
gui_fkey2$: keyevent F_DIRECTORY_SEARCH
gui_fkey3$: keyevent none
gui_fkey4$: keyevent F_SETTINGS
context_key0$: keyevent F_CALL_LIST
context_key1$: keyevent F_DIRECTORY_SEARCH
context_key2$: keyevent F_NONE
context_key3$: keyevent F_SETTINGS
dkey_directory$: keyevent F_DIRECTORY_SEARCH
ldap_server$: <?php if (is_null($row[0]['vlan'])  || empty($row[0]['vlan'])) {print $server . "\n";} ELSE {print $row[0]['registrar'] . "\n";}?>
ldap_port$: 389
ldap_base$: o=dbs
ldap_username$: cn=admin,o=dbs
ldap_password$: VGJd#$xx
ldap_max_hits$: 50
ldap_lookup_ringing$: on
ldap_search_filter$: (|(cn=%))
ldap_number_filter$: (&(mobile=%))
ldap_name_attributes$: cn
ldap_number_attributes$: mobile
ldap_display_name$: %cn
country_code$:
area_code$:
dhcp$: <?php if (($row[0]['dhcp'] == 0) && !is_null($row[0]['dhcp'])) {print "off\n";} ELSE {print "on\n";}?>
<?php
if (($row[0]['dhcp'] == 0) && !is_null($row[0]['dhcp'])) 
{ 
?>
ip_adr$: <?php print $row[0]['ip'] . "\n" ?>
netmask$: <?php print $row[0]['subnet'] . "\n" ?>
dns_server1$: <?php print $row[0]['dns'] . "\n" ?>
dns_server2$: <?php print $row[0]['dns'] . "\n" ?>
gateway$: <?php print $row[0]['gateway'] . "\n" ?>
<?php
} 
if (strpos($agent, "8.4.35") > 0) 
{ 
?>
vlan_id$: <?php print $row[0]['vlan'] . "\n" ?>
vlan_qos$: 5
<?php
} else {
?>
vlan$: <?php print $row[0]['vlan'] . "\n" ?>
<?php
}
?>
</pre>
</html>
<?php
} else {
print "HTTP/1.0 404 Not Found";
}
?>
