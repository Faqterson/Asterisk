<?php 

header('Content-Type: text/xml');

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


$row=db_query("SELECT name, secret, callerid, dhcp, vlan, registrar, ip, subnet, gateway, dns FROM sip LEFT OUTER JOIN provisioning ON (sip.uniqueid = sipid) WHERE mac = ?", array($upmac));

$numrange = substr($row[0]['name'], 0, 1);

if (preg_match("/^FileTransport\ Polycom.*/",$_SERVER['HTTP_USER_AGENT']))
{
?>
<<?php print "?"?>xml version="1.0" encoding="utf-8" standalone="yes"<?php print "?"?>>
<!-- Generated reg-basic.cfg Configuration File -->
<PHONE_CONFIG>
	<WEB
            	dialplan.digitmap="<?php print $numrange ?>xxx|01x.T|02x.T|03x.T|04x.T|05x.T|06x.T|08x.T|07x.T|00x.T|*x.T|**x.T|5xx|3xx"
                tcpIpApp.sntp.daylightSavings.enable="0"

                device.set="1"
                device.sntp.serverName.set="1"
                device.sntp.serverName="<?php if (is_null($row[0]['vlan'])  || empty($row[0]['vlan'])) {print $server . "\n";} ELSE {print $row[0]['registrar'] . "\n";}?>"
                device.sntp.gmtOffset.set="1"
                device.sntp.gmtOffset="7200"
                device.sntp.gmtOffsetcityID.set="1"
                device.sntp.gmtOffsetcityID="67"

                device.dhcp.enabled.set="1"
                device.dhcp.enabled="<?php if (($row[0]['dhcp'] == 0) && !is_null($row[0]['dhcp'])) {print "0\n";} ELSE {print "1";}?>"
                device.net.ipAddress.set="1"
                device.net.ipAddress="<?php print $row[0]['ip'] ?>"
                device.net.subnetMask.set="1"
                device.net.subnetMask="<?php print $row[0]['subnet'] ?>"
                device.net.IPgateway.set="1"
                device.net.IPgateway="<?php print $row[0]['gateway'] ?>"
                device.net.vlanId.set="1"
                device.net.vlanId="<?php print $row[0]['vlan'] ?>"

                feature.urlDialing.enabled="0"

               	qos.ip.callControl.dscp="46"
                qos.ip.rtp.dscp="46"
        />
</PHONE_CONFIG>
<polycomConfig>
  <voIpProt>
    <voIpProt.SIP voIpProt.SIP.enable="1">
      <voIpProt.SIP.outboundProxy
        voIpProt.SIP.outboundProxy.address="<?php if (is_null($row[0]['vlan'])  || empty($row[0]['vlan'])) {print $server . "\n";} ELSE {print $row[0]['registrar'] . "\n";}?>">
      </voIpProt.SIP.outboundProxy>
      <voIpProt.SIP.alertInfo>
        voIpProt.SIP.alertInfo.1.class="4"
        voIpProt.SIP.alertInfo.1.value="Ring Answer"
      </voIpProt.SIP.alertInfo>
    </voIpProt.SIP>
    <voIpProt.server
        voIpProt.server.1.address="<?php if (is_null($row[0]['vlan'])  || empty($row[0]['vlan'])) {print $server . "\n";} ELSE {print $row[0]['registrar'] . "\n";}?>"
        voIpProt.server.1.port="5060">
    </voIpProt.server>
  </voIpProt>
  <reg
      	reg.1.displayName="<?php print $row[0][name];?>"
        reg.1.address="<?php print $row[0][name];?>"
        reg.1.auth.password="<?php print $row[0][secret];?>"
        reg.1.auth.userId="<?php print $row[0][name];?>"
        reg.1.label="<?php print $row[0][name];?>"
        reg.1.outboundProxy.address="<?php if (is_null($row[0]['vlan'])  || empty($row[0]['vlan'])) {print $server . "\n";} ELSE {print $row[0]['registrar'] . "\n";}?>"
        reg.1.lineKeys="2"
  </reg>
  <httpd httpd.enabled="1">
    <httpd.cfg httpd.cfg.enabled="1" httpd.cfg.port="80" httpd.cfg.secureTunnelEnabled="0" httpd.cfg.secureTunnelPort="443" httpd.cfg.secureTunnelRequired="0">
      <httpd.cfg.lockWebUI httpd.cfg.lockWebUI.enable="0" httpd.cfg.lockWebUI.lockOutDuration="60" httpd.cfg.lockWebUI.noOfInvalidAttempts="5" httpd.cfg.lockWebUI.noOfInvalidAttemptsDuration="60">
      </httpd.cfg.lockWebUI>
    </httpd.cfg>
  </httpd>
</polycomConfig>
<?php
} else {
print "HTTP/1.0 404 Not Found";
}
?>
