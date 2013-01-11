<?php

require_once ("Transaccio.php");
require_once ("Emergencia.php");


class TxNotifica implements Transaccio {
	
	private $emergencia;

	public function execu() {
		$this->emergencia->creaNotificacio();
	}
	
	public function modificaEmergencia($e) {
		$this->emergencia = $e;
	}
	
	public function obteResultat() {
		return null;
	}
}

?>