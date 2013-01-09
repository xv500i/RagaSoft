<?php

include_once ("IControladorContactes.php");
include_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "dades" . DIRECTORY_SEPARATOR . "FabricaControladorsDades.php");

class Contactes {

	private static $instance;

	
	private function __construct() {
		
	}
	
	public static function getInstance() {
		if (self::$instance == NULL) {
			self::$instance = new Contactes();
		}
		return self::$instance;
	}
	
	public function obteTelefonDelServeiDeEmergencies() {
		$ContDades = FabricaControladorsDades::getInstance();
		$CtrlContactes = $ContDades->getIControladorContactes();
		$telf = $CtrlContactes->obteTelf("SEM");
		return $telf;
	}
}