<?php

function apply() {
	echo exec('sudo /usr/local/logicgate/applyconfig');
	echo "<table>";
	echo "<th>Settings applied</th>";
	echo "</table>";
}
?>
