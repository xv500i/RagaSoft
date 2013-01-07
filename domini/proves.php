<?php
	include_once ("TxCreaIncendi.php");
	include_once ("TxCreaCaiguda.php");
	include_once ("TxCreaTardanca.php");
	include_once ("TxNotifica.php");
	include_once ("TxConfirmaNotificacio.php");
	

	

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