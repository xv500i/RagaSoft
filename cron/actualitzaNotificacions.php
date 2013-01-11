<?php

$archivo 	= 'fichero.txt';
$fp 		= fopen($archivo, "a");
$string 	= "[".date('l jS \of F Y h:i:s A')."] Ola k ase \n";
$write 		= fputs($fp, $string);

fclose($fp);

// SCRIPT ACTALIZA ESTADO NOTIFICACIONES
/*


require_once ("FabricaControladorsDades.php");
$f = FabricaControladorsDades::getInstance();
$cn = $f->getIControladorNotificacio();
$notificacions = $cn->tots();
foreach ($notificacions as $n) {
	$diffSeconds = 100;
	if (!$n->obteConfirmada() && $diffSeconds > 120) {
		// notificar
		$n->modificaEsPotConfirmar(FALSE);
		$cn->actualitza($n);
	}
}


*/
?> 