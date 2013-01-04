<?php

include_once (__DIR__ . "\\..\\dades\\FabricaControladorsDades.php");
include_once ("IControladorEmergencia.php");

abstract class Emergencia {
	
	protected $moment;
	

	abstract public function obteCuidador();
	abstract public function obteMissatge();
	abstract public function obtePeriodeDeConfirmacio();
	
	public function creaNotificacio() {
		$ContDades = FabricaControladorsDades::getInstance();
		$CtrlNotificacio = $ContDades->getIControladorNotificacio();
		$c = $this->obteCuidador();
		$n = $CtrlNotificacio->creaNotificacio($this, $c);
		$n->notifica();
	}
	
}

?>