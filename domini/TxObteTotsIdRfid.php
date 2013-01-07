<?php

include_once ("Transaccio.php");


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
	}
	
	public function obteResultat() {
		return $ids;
	}
}

?>