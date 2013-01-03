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
	
	$factory = FabricaControladorsDades::getInstance();
	echo "Contactes<br>";
	$c = Contactes::getInstance();
	echo $c->obteTelefonDelServeiDeEmergencies();
	echo "<br>";
	
	echo "Cuidador<br>";
	$cu = $factory->getIControladorCuidador();
	var_dump($cu->tots());
	echo "<br>";
	
	
	echo "Notificacio<br>";
	$cu = $factory->getIControladorNotificacio();
	var_dump($cu->tots());
	echo "<br>";
	
	// TODO: acabar aixo quan emergencia estigui fet
	echo "Emergencia<br>";
	$cu = $factory->getIControladorEmergencia();
	displayRows($cu->tots());
	echo "<br>";
	
	// TODO: acabar aixo quan resident estigui fet
	echo "Resident<br>";
	$cu = $factory->getIControladorResident();
	displayRows($cu->tots());
	echo "<br>";
	
	echo "Llar<br>";
	$cu = $factory->getIControladorLlar();
	var_dump($cu->tots());
	echo "<br>";
?>
