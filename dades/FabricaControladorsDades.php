<?php

require_once ("ControladorResident.php");
require_once ("ControladorEmergencia.php");
require_once ("ControladorNotificacio.php");
require_once ("ControladorCuidador.php");
require_once ("ControladorLlar.php");
require_once ("ControladorContactes.php");

class FabricaControladorsDades {

	private static $instance;
	private $iControladorCuidador;
	private $iControladorResident;
	private $iControladorEmergencia;
	private $iControladorNotificacio;
	private $iControladorLlar;
	private $iControladorContactes;
	
	public function __construct() {
		
	}
	
	public static function getInstance() {
		if (self::$instance == NULL) {
			self::$instance = new FabricaControladorsDades();
		}
		return self::$instance;
	}
	
	public function getIControladorResident() {
		if ($this->iControladorResident == NULL) {
			$this->iControladorResident = new ControladorResident();
		}
		return $this->iControladorResident;
	}
	
	public function getIControladorCuidador() {
		if ($this->iControladorCuidador == NULL) {
			$this->iControladorCuidador = new ControladorCuidador();
		}
		return $this->iControladorCuidador;
	}
	
	public function getIControladorContactes() {
		if ($this->iControladorContactes == NULL) {
			$this->iControladorContactes = new ControladorContactes();
		}
		return $this->iControladorContactes;
	}
	
	public function getIControladorLlar() {
		if ($this->iControladorLlar == NULL) {
			$this->iControladorLlar = new ControladorLlar();
		}
		return $this->iControladorLlar;
	}
	
	public function getIControladorEmergencia() {
		if ($this->iControladorEmergencia == NULL) {
			$this->iControladorEmergencia = new ControladorEmergencia();
		}
		return $this->iControladorEmergencia;
	}
	
	public function getIControladorNotificacio() {
		if ($this->iControladorNotificacio == NULL) {
			$this->iControladorNotificacio = new ControladorNotificacio();
		}
		return $this->iControladorNotificacio;
	}
	
}
?>
