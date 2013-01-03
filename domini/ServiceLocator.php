<?php

include_once ("AdaptadorServeiEmergencies.php");

class ServiceLocator {

	private static $instance;
	private $AdaptadorServeiEmergencies;

	
	private function __construct() {
		
	}
	
	public static function getInstance() {
		if (self::$instance == NULL) {
			self::$instance = new ServiceLocator();
		}
		return self::$instance;
	}
	
	public function getIControladorResident() {
		if ($this->AdaptadorServeiEmergencies == NULL) {
			$this->AdaptadorServeiEmergencies = new AdaptadorServeiEmergencies();
		}
		return $this->AdaptadorServeiEmergencies;
	}
	
}
?>