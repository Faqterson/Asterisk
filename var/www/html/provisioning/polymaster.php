<?php Header('Content-type: text/xml');
if (preg_match("/^FileTransport\ Polycom.*/",$_SERVER['HTTP_USER_AGENT']))
{
?>
<<?print "?";?>xml version="1.0" standalone="yes"<?php print "?";?>>
<!-- Default Master SIP Configuration File-->
<!-- Edit and rename this file to <Ethernet-address>.cfg for each phone.-->
<!-- $Revision: 1.14 $  $Date: 2005/07/27 18:43:30 $ -->
<APPLICATION APP_FILE_PATH="provisioning/sip.ld" CONFIG_FILES="" MISC_FILES="" LOG_FILE_DIRECTORY="" OVERRIDES_DIRECTORY="" CONTACTS_DIRECTORY=""/>
<?php
} else {
print "HTTP/1.0 404 Not Found";
}
?>

