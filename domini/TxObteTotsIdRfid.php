<?php

include_once ("Transaccio.php");
include_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "dades" . DIRECTORY_SEPARATOR . "FabricaControladorsDades.php");


class TxObteTotsIdRfid implements Transaccio {
	
	private $ids;

	public function execu() {
		$f = FabricaControladorsDades::getInstance();
		$cr = $f->getIControladorResident();
		$residents = $cr->tots();
		$ids = array();
		foreach ($residents as $resident) {
			array_push($ids, $resident->obteIdRfid());
		}
		$this->ids = $ids;
	}
	
	public function obteResultat() {
		return $this->ids;
	}
}

?>