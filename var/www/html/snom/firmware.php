<?php 

$server = $_SERVER['SERVER_NAME'];
$agent = $_SERVER['HTTP_USER_AGENT'];

#$agent = "Mozilla/4.0 (compatible; snom320-SIP 7.3.31 1.1.3-u)";

$agent = preg_replace("/[()]/", "", $agent);


$info = explode(';', $agent);
$type = "snom";

if (strpos($agent, "snom300") > 0)
{
	$type = "snom300";
	$update = "$type-8.7.3.25.9-SIP-f.bin";
}
else if (strpos($agent, "snom320") > 0)
{
	$type = "snom320";
	$update = "$type-8.7.3.25.9-SIP-f.bin";
}
else if (strpos($agent, "snom360") > 0)
{
	$type = "snom360";
	$update = "$type-8.7.3.25.9-SIP-f.bin";
}
else if (strpos($agent, "snom370") > 0)
{
	$type = "snom370";
	$update = "$type-8.7.5.44-SIP-f.bin";
}
else if (strpos($agent, "snomMP") > 0)
{	
	$type = "snomMP";
	$update = "$type-8.7.5.35-SIP-r.bin";
}
else if (strpos($agent, "snom710") > 0)
{
	$type = "snom710";
	$update = "$type-8.7.3.25.9-SIP-r.bin";
}

else if (strpos($agent, "snom715") > 0)
{	
	$type = "snom715";
	$update = "$type-10.1.64.14-SIP-r.bin";
}

else if (strpos($agent, "snom720") > 0)
{
	$type = "snom720";
	$update = "$type-8.9.3.60-SIP-r.bin";
}

else if (strpos($agent, "snom725") > 0)
{
	$type = "snom725";
	$update = "$type-10.1.64.14-SIP-r.bin";
}

else if (strpos($agent, "snom760") > 0)
{
	$type = "snom760";
	$update = "$type-8.7.3.25.9-SIP-r.bin";
}

else if (strpos($agent, "snomD712") > 0)
{
        $type = "snomD712";
        $update = "$type-8.9.3.80-SIP-r.bin";
}

else if (strpos($agent, "snomD305") > 0)
{
        $type = "snomD305";
        $update = "$type-8.9.3.60-SIP-r.bin";
}

$url="http://$server/snom/firmware/$update";

?>
<html>
<pre>
<?php
    echo "firmware: $url\n";
?>
</pre>
</html>

