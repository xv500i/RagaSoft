<?php

include_once ("IAdaptadorServeiEmergencies.php");

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
	
	public function troba($text) {
		if($text == "ServeiSMS") {
			if ($this->AdaptadorServeiEmergencies == NULL) {
				$this->AdaptadorServeiEmergencies = new AdaptadorServeiEmergencies();
			}
			return $this->AdaptadorServeiEmergencies;
		}
	}
}

?>