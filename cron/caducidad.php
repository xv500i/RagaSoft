<?php
require_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "dades" . DIRECTORY_SEPARATOR . "DB.php");
require_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "dades" . DIRECTORY_SEPARATOR . "FabricaControladorsDades.php");
require_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "domini" . DIRECTORY_SEPARATOR . "AdaptadorServeiEmergenciesWeb.php");

$f = FabricaControladorsDades::getInstance();
$cn = $f->getIControladorNotificacio();
$notificacions = $cn->tots();
$actualDate = new DateTime(date('Y-m-d H:i:s'));
//$actualDate->add(new DateInterval("P0000-00-00T01:00:00"));
//print_r($actualDate);
//print "<br><br>";

foreach ($notificacions as $n) {
	
	$d2=new DateTime($n->obteEmergencia()->obteMoment());
	$diff = $d2->diff($actualDate);
	//print_r($d2);
	//echo "<br />";
	
	$diffSeconds = $diff->s + (60 * $diff->i) + (3600 * $diff->h);
	//print_r($diffSeconds);
	//echo "<br />";
	if ($n->obteEsPotConfirmar() && !$n->obteConfirmada() && $diffSeconds > 120) {
	
		// notificar
		//var_dump($n);
		//echo "<br />";
		$n->modificaEsPotConfirmar(FALSE);
		$cn->actualitza($n);
		$sms = new AdaptadorServeiEmergenciesWeb();
		$id = $n->obteId();
		$sms->enviaSMS($n->obteCuidador()->obteTelefon(), "S'hauria avisat al SEM perque ha expirat la notificació amb id " . $id);
		// enviar missatge al cuidador
	}
}
?>