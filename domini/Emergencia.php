<?php

include_once (__DIR__ . "\\..\\dades\\FabricaControladorsDades.php");
include_once ("IControladorNotificacio.php");
include_once ("Cuidador.php");
include_once ("Notificacio.php");


abstract class Emergencia {
	
	protected $moment;
	
	
	public function obteMoment() {
		return $this->moment;
	}
	
	public function modificaMoment($m) {
		$this->moment = $m;
	}

	abstract public function queEts();
	abstract public function obteAfectat();
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