<?php

include_once ("Transaccio.php");
include_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "dades" . DIRECTORY_SEPARATOR . "FabricaControladorsDades.php");
include_once ("IControladorNotificacio.php");
include_once ("Notificacio.php");


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