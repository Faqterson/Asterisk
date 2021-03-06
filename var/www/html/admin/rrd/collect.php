#!/usr/bin/php
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

getconcurcalls();

function getconcurcalls() {

   $row = db_query("SELECT count(uniqueid) as count from asterisk.cdr where calldate >= DATE_SUB(now(), interval 5 minute) AND disposition = 'ANSWERED'", array());

   echo "/usr/bin/rrdtool update /usr/local/rrd/concurcalls.rrd N:".$row[0]['count']."\n";

}

?>
