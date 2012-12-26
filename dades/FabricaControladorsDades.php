<?php
class FabricaControladorsDades {

	private $instance;
	private $iControladorUsuari;
	
	private function __construct() {
		// init controladors
		$iControladorUsuari = new ControladorUsuari();
	}
	
	public static function getInstance() {
		if ($instance == NULL) {
			$instance = new FabricaControladorsDades();
		}
		return $instance;
	}
	
	public function getIControladorUsuari() {
		if ($iControladorUsuari == NULL) {
			//init
		}
		return $iControladorUsuari;
	}
}
?>
