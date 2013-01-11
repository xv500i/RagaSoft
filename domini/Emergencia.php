<?php

require_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "dades" . DIRECTORY_SEPARATOR . "FabricaControladorsDades.php");
require_once ("IControladorNotificacio.php");
require_once ("Cuidador.php");
require_once ("Notificacio.php");



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