<?php
	include_once ("TxCreaIncendi.php");
	include_once ("TxCreaCaiguda.php");
	include_once ("TxCreaTardanca.php");
	
	echo "crear tardança";
	echo "<br>";
	
	$tr = new TxCreaTardanca();
	$tr->execu();
?>