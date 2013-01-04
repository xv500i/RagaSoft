<?php

include_once ("Transaccio.php");
include_once (__DIR__ . "\\..\\dades\\FabricaControladorsDades.php");
include_once ("IControladorNotificacio.php");

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
}

?>