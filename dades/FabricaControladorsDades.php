<?php

include_once ("ControladorUsuari.php");

class FabricaControladorsDades {

	private static $instance;
	private $iControladorUsuari;
	
	private function __construct() {
		
	}
	
	public static function getInstance() {
		if (self::$instance == NULL) {
			self::$instance = new FabricaControladorsDades();
		}
		return self::$instance;
	}
	
	public function getIControladorUsuari() {
		if ($this->iControladorUsuari == NULL) {
			$this->iControladorUsuari = new ControladorUsuari();
		}
		return $this->iControladorUsuari;
	}
}
?>
