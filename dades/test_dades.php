<?php
	include_once ("DB.php");
	include_once ("FabricaControladorsDades.php");
	
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
	
	/*
	$user="alex";
	$password="";
	$database="test";
	$location = "localhost:3306";
	
	mysql_connect($location, $user, $password);
		@mysql_select_db($database) or die ("Impossible de selecionar la base de dades");
		$result = mysql_query("SELECT * FROM usuari");
		mysql_close();
	$n = mysql_num_rows($result);
	$i = 0;
	while ($i < $n){
		echo mysql_result($result, $i, "id") . "<br>";
		$i++;
	}
	*/
	$factory = FabricaControladorsDades::getInstance();
	echo "Contactes<br>";
	$cu = $factory->getIControladorContactes();
	displayRows($cu->tots());
	echo "<br>";
	
	echo "Resident<br>";
	$cu = $factory->getIControladorResident();
	displayRows($cu->tots());
	echo "<br>";
?>
