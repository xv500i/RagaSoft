<?php

require_once ("Transaccio.php");
require_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "dades" . DIRECTORY_SEPARATOR . "FabricaControladorsDades.php");


class TxObteTotsUsuaris implements Transaccio {
	
	private $usuaris;

	public function execu() {
		$f = FabricaControladorsDades::getInstance();
		$cl = $f->getIControladorLlar();
		$llars = $cl->tots();
		$usuaris = array();
		foreach ($llars as $llar) {
			array_push($usuaris, $llar->obteUsuari());
		}
		$this->usuaris = $usuaris;
	}
	
	public function obteResultat() {
		return $this->usuaris;
	}
}

?>