<?php

include_once ("Transaccio.php");
include_once ("Emergencia.php");


class TxNotifica implements Transaccio {
	
	private $emergencia;

	public function execu() {
		$emergencia->creaNotificacio();
	}
}

?>