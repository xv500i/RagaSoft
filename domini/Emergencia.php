<?php

include_once (__DIR__ . "\\..\\dades\\FabricaControladorsDades.php");
include_once ("IControladorEmergencia.php");

abstract class Emergencia {
	
	protected $moment;
	

	abstract public function obteCuidador();
	abstract public function obteMissatge();
	abstract public function obtePeriodeDeConfirmacio();
	
	public function creaNotificacio() {
		$ContDades = new FabricaControladorsDades();
		$ContDades->getInstance();
		$CtrlNotificacio = $ContDades->getIControladorNotificacio();
		obteCuidador();
		
	}
	
}

?>