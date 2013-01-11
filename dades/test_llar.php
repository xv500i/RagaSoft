<?php
	require_once ("DB.php");
	require_once ("FabricaControladorsDades.php");
	
	$fabrica = FabricaControladorsDades::getInstance();
	$cu = $fabrica->getIControladorLlar();
	$casa = $cu->obte("CasaPuig");
	$ce = $fabrica->getIControladorEmergencia();
	$ce->creaIncendi($casa);
?>