<?php 
Header('Content-type: text/xml');

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

$row = db_query("select DISTINCT name, number FROM directory", array());

if (preg_match("/^FileTransport\ Polycom.*/",$_SERVER['HTTP_USER_AGENT']))
{
?>
<<?print "?";?>xml version="1.0" encoding="UTF-8" standalone="yes"<?php print "?"?>>
<!-- Generated reg-basic.cfg Configuration File -->
<directory>
  <item_list>
<?php
Foreach($row as $key => $value)
  {

        print "             <item>\n";
        print "                     <ln> </ln>\n";
        print "                     <fn>" . $value['name'] . "</fn>\n";
        print "                     <ct>" . $value['number'] . "</ct>\n";
        print "                     <rt>3</rt>\n";
        print "                     <dc/>\n";
        print "                     <ad>0</ad>\n";
        print "                     <ar>0</ar>\n";
        print "                     <bw>0</bw>\n";
        print "                     <bb>0</bb>\n";
        print "             </item>\n";
  }
mysqli_close($con);
?>
  </item_list>
</directory>
<?php
} else {
print "HTTP/1.0 404 Not Found";
}
?>

