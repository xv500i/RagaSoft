<?php

require_once ("Transaccio.php");
require_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "dades" . DIRECTORY_SEPARATOR . "FabricaControladorsDades.php");
require_once ("IControladorNotificacio.php");
require_once ("Notificacio.php");


class TxCarregaNotis implements Transaccio {
	
	private $resultat;
	
	public function execu() {
		$ContDades = FabricaControladorsDades::getInstance();
		$CN = $ContDades->getIControladorNotificacio();
		$ns = $CN->tots();
		$result = array();
		foreach ($ns as $n) {
    		$tipus = $n->deQueEts();
			$afectat = $n->obteAfectat();
			$telf = $n->obteTelefonCuidador();
			$mom = $n->obteMoment();
			$per = $n->obtePeriode();
			$con = $n->obteConfirmada();
			$id = $n->obteId();
			$epc = $n->obteEsPotConfirmar();
			$res = array($tipus, $afectat, $telf, $mom, $per, $con, $id, $epc);
			$result[] = $res;
		}
		$this->resultat = $result;
	}

	public function obteResultat() {
		return $this->resultat;
	}
}

?>