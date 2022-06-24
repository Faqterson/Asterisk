<?php
include "include/common_func.php";

function loadavg() {
   $load = sys_getloadavg();

   echo "<div class=''>";
   echo "<table>";
   echo "<th>1 Min</th><th>5 Min</th><th>15 Min</th>";
   echo "<tr align='center'>";
   echo "<td>".$load[0]."</td>";
   echo "<td>".$load[1]."</td>";
   echo "<td>".$load[2]."</td>";
   echo "</td>";
   echo "</table>";
}

?>
