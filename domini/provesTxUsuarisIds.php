<?php
	require_once("TxObteTotsIdRfid.php");
	require_once("TxObteTotsUsuaris.php");
	
	$txUsuaris = new TxObteTotsUsuaris();
	$txUsuaris->execu();
	var_dump($txUsuaris->obteResultat());
	
	$txIds = new TxObteTotsIdRfid();
	$txIds->execu();
	var_dump($txIds->obteResultat());
?>