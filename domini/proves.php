<?php
	include_once ("TxCreaIncendi.php");
	include_once ("TxCreaCaiguda.php");
	include_once ("TxCreaTardanca.php");
	
	echo "crear caiguda";
	echo "<br>";
	
	$tr = new TxCreaCaiguda();
	$tr->execu();
?>