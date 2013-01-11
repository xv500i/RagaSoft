<?php

require_once ("Transaccio.php");
require_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "dades" . DIRECTORY_SEPARATOR . "FabricaControladorsDades.php");
require_once ("IControladorNotificacio.php");
require_once ("Notificacio.php");


class TxConfirmaNotificacio implements Transaccio {
	
	private $idNotificacio;
	private $telefon;

	public function execu() {
			$ContDades = FabricaControladorsDades::getInstance();
			$CtrlNotificacio = $ContDades->getIControladorNotificacio();
			$n = $CtrlNotificacio->obte($this->idNotificacio);
			$n->confirma($this->telefon);
			$CtrlNotificacio->actualitza($n);		
	}
	
	public function modificaIdNotificacio($id) {
		$this->idNotificacio = $id;
	}
	
	public function modificaTelefon($t) {
		$this->telefon = $t;
	}
	
	public function obteResultat() {
		return null;
	}
}

?>