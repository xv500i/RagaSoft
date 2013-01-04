<?php

include_once (__DIR__ . "\\..\\dades\\FabricaControladorsDades.php");
include_once ("IControladorNotificacio.php");
include_once ("Cuidador.php");
include_once ("Notificacio.php");


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