<?php

function displayRows($mysqlresult) {
	$nr = mysql_num_rows($mysqlresult);
	$i = 0;
	$nc = mysql_num_fields($mysqlresult);
	echo "<table border=\"1\">";
	// fields
	$j = 0;
	echo "<tr>";
	while ($j < $nc) {
		echo "<th>" . mysql_field_name($mysqlresult, $j) . "</th>";
		$j++;
	}
	echo "</tr>";
	// rows
	while ($i < $nr) {
		$j = 0;
		echo "<tr>";
		while ($j < $nc) {
			echo "<td>" . mysql_result($mysqlresult, $i, mysql_field_name($mysqlresult, $j)) . "</td>";
			$j++;
		}
		echo "</tr>";
		$i++;
	}
	echo "</table>";
}

mysql_connect("localhost", "alex", "") or die(mysql_error());
echo "Connected to MySQL<br />";
mysql_select_db("test") or die(mysql_error());
echo "Connected to Database";
$result = mysql_query("SELECT * FROM RESIDENT;");
displayRows($result);
?>