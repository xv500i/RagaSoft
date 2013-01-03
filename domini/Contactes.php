<?php


class Contactes {

	private static $instance;
	private $telf = 112;

	
	private function __construct() {
		
	}
	
	public static function getInstance() {
		if (self::$instance == NULL) {
			self::$instance = new Contactes();
		}
		return self::$instance;
	}
	
	public function obteTelefonDelServeiDeEmergencies() {
		return $this->telf;
	}
}