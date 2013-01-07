<?php

include_once ("Transaccio.php");
include_once (__DIR__ . "\\..\\dades\\FabricaControladorsDades.php");
include_once ("IControladorNotificacio.php");
include_once ("Notificacio.php");


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
			$res = array($tipus, $afectat, $telf, $mom, $per, $con, $id);
			$result[] = $res;
		}
		$this->resultat = $result;
	}

	public function obteResultat() {
		return $this->resultat;
	}
}

?>