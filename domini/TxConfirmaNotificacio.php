<?php

include_once ("Transaccio.php");
include_once (__DIR__ . "\\..\\dades\\FabricaControladorsDades.php");
include_once ("IControladorNotificacio.php");

class TxConfirmaNotificacio implements Transaccio {
	
	private $idNotificacio;
	private $telefon;

	public function execu() {
			$ContDades = new FabricaControladorsDades();
			$ContDades->getInstance();
			$CtrlNotificacio = $ContDades->getIControladorNotificacio();
			$n = $CtrlNotificacio->obte($idNotificacio);
			$n->confirma($telefon);
			$CtrlNotificacio->actualitza($n);		
	}
}

?>