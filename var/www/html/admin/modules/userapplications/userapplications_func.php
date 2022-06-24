<?php

function extlist() {

   $row = db_query("SELECT ps_endpoints.id,callerid,email,department FROM ps_endpoints LEFT JOIN voicemail ON ps_endpoints.id = voicemail.mailbox LEFT JOIN departments ON sip = ps_endpoints.id LEFT JOIN ps_registrations ON (ps_endpoints.id = ps_registrations.id) WHERE ps_registrations.id is null ORDER by ps_endpoints.id", array());

   echo "<div class=''>";
   echo "<table id='extlist' >";
   echo "<th>Extension</th><th>User</th><th>Email</th><th>Department</th>";
   foreach($row as $key => $value) {
      echo "<tr align='center'>";
      echo "<td>".$value["id"]."</td>";
      echo "<td>".$value["callerid"]."</td>";
      echo "<td>".$value["email"]."</td>";
      echo "<td>".$value["department"]."</td>";
      echo "</tr>";
   }
   echo "</table>";
   echo "</div>";
   echo "<button class='button' id='printbutton'>Print</button>";
}

function features() {

   echo "<div class=''>";
   echo "<table id='features' >";
   echo "<th>Feature</th><th>Code</th>";

   echo "<tr align='center'>";
   echo "<td>Voicemail</td>";
   echo "<td>*10</td>";
   echo "</tr>";

   echo "<tr align='center'>";
   echo "<td>DND</td>";
   echo "<td>*40</td>";
   echo "</tr>";

   echo "<tr align='center'>";
   echo "<td>Call Forward</td>";
   echo "<td>*41</td>";
   echo "</tr>";

   echo "<tr align='center'>";
   echo "<td>Speed Dial</td>";
   echo "<td>**XXX</td>";
   echo "</tr>";

   echo "<tr align='center'>";
   echo "<td>Say Time</td>";
   echo "<td>*60</td>";
   echo "</tr>";

   echo "<tr align='center'>";
   echo "<td>Say Extensions</td>";
   echo "<td>*65</td>";
   echo "</tr>";

   echo "<tr align='center'>";
   echo "<td>Say IP</td>";
   echo "<td>*66</td>";
   echo "</tr>";

   echo "<tr align='center'>";
   echo "<td>Music On Hold</td>";
   echo "<td>*67</td>";
   echo "</tr>";

   echo "<tr align='center'>";
   echo "<td>Agent Pause</td>";
   echo "<td>*603</td>";
   echo "</tr>";

   echo "<tr align='center'>";
   echo "<td>Agent Pause Reason</td>";
   echo "<td>*603X</td>";
   echo "</tr>";

   echo "<tr align='center'>";
   echo "<td>Agent Unpause</td>";
   echo "<td>*604</td>";
   echo "</tr>";

   echo "<tr align='center'>";
   echo "<td>Conference</td>";
   echo "<td>*800-*899</td>";
   echo "</tr>";

   echo "<tr align='center'>";
   echo "<td>Nightmode</td>";
   echo "<td>*90</td>";
   echo "</tr>";

   echo "<tr align='center'>";
   echo "<td>Call Parking</td>";
   echo "<td>1701-1720</td>";
   echo "</tr>";

   echo "<th colspan='2'>Generated Codes</th>";

   $row = db_query("SELECT exten,appdata from extensions WHERE appdata like ? or appdata like ? or appdata like ?", array('record-prompt%', 'agent%', 'queue%'));

   foreach($row as $key => $value) {
      echo "<tr align='center'>";
      echo "<td>".$value['appdata']."</td>";
      echo "<td>".$value['exten']."</td>";
      echo "</tr>";
   }

   echo "</table>";
   echo "</div>";
   echo "<button class='button' id='printbutton'>Print</button>";

}

?>
