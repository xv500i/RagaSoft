<?php
class FabricaControladorsDades {

	private $instance;
	private $iControladorUsuari;
	
	private function __construct() {
		
	}
	
	public static function getInstance() {
		if ($instance == NULL) {
			$instance = new FabricaControladorsDades();
		}
		return $instance;
	}
	
	public function getIControladorUsuari() {
		if ($iControladorUsuari == NULL) {
			$iControladorUsuari = new ControladorUsuari();
		}
		return $iControladorUsuari;
	}
}
?>
