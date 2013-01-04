<?php
	include_once ("TxCreaIncendi.php");
	include_once ("TxCreaCaiguda.php");
	include_once ("TxCreaTardanca.php");
	

	
	echo "Crear Incendi";
	echo "<br>";
	$TxIn = new TxCreaIncedi();
	$TxIn->execu();
	$i = $TxIn->obteResultat();
	
	echo "Notifica";
	echo "<br>";
	$tr = new TxNotifica();
	$tr->modificaEmergencia($i);
	$tr->execu();
?>