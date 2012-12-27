<?php
	include_once ("DB.php");
	include_once ("FabricaControladorsDades.php");
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
	$cu = $factory->getIControladorUsuari();
	echo var_dump($cu->existeix(10));
?>
