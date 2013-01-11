<?php
	require_once ("TxCreaIncendi.php");
	require_once ("TxCreaCaiguda.php");
	require_once ("TxCreaTardanca.php");
	require_once ("TxNotifica.php");
	require_once ("TxConfirmaNotificacio.php");
	

	

	echo "Crear Incendi";
	echo "<br>";
	
	$txin = new TxCreaIncedi();
	$txin->modificaUsuari("PisBarriAntic");
	$txin->execu();
	$i = $txin->obteResultat();
	
	echo "crear notificacio";
	echo "<br>";
	
	$txnot = new TxNotifica();
	$txnot->modificaEmergencia($i);
	$txnot->execu();
	
	

?>