<?php
	include_once ("TxCreaIncendi.php");
	include_once ("TxCreaCaiguda.php");
	include_once ("TxCreaTardanca.php");
	
	echo "Notifica";
	echo "<br>";
	
	$tr = new TxNotifica();
	$tr->execu();
?>