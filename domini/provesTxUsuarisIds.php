<?php
	include_once("TxObteTotsIdRfid.php");
	include_once("TxObteTotsUsuaris.php");
	
	$txUsuaris = new TxObteTotsUsuaris();
	$txUsuaris->execu();
	var_dump($txUsuaris->obteResultat());
	
	$txIds = new TxObteTotsIdRfid();
	$txIds->execu();
	var_dump($txIds->obteResultat());
?>