<?php
	include_once ("TxCreaIncendi.php");
	include_once ("TxCreaCaiguda.php");
	include_once ("TxCreaTardanca.php");
	include_once ("TxNotifica.php");
	

	
	echo "Crear Incendi";
	echo "<br>";
	$TxIn = new TxCreaIncedi();
	$TxIn->execu();
	$i = $TxIn->obteResultat();
	var_dump($i);
	
	echo "Notifica";
	echo "<br>";
	$tr = new TxNotifica();
	$tr->modificaEmergencia($i);
	$tr->execu();
?>