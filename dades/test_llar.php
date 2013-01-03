<?php
	include_once ("DB.php");
	include_once ("FabricaControladorsDades.php");
	
	$fabrica = FabricaControladorsDades::getInstance();
	$cu = $fabrica->getIControladorLlar();
	var_dump($cu->tots());
?>