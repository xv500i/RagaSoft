<?php
	include_once ("TxCreaIncendi.php");
	include_once ("TxCreaCaiguda.php");
	include_once ("TxCreaTardanca.php");
	include_once ("TxNotifica.php");
	include_once ("TxConfirmaNotificacio.php");
	

	

	echo "Confirma Notificacio";
	$txConf = new TxConfirmaNotificacio();
	$txConf->modificaIdNotificacio(18);
	$txConf->modificaTelefon(633768939);
	$txConf->execu();

?>