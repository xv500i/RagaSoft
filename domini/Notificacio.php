<?php

require_once ("Cuidador.php");
require_once ("ServiceLocator.php");
require_once ("IAdaptadorServeiEmergencies.php");
require_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "dades" . DIRECTORY_SEPARATOR . "FabricaControladorsDades.php");
require_once ("IControladorNotificacio.php");
require_once ("Contactes.php");
require_once ("Emergencia.php");


class Notificacio {
	
	private $id;
	private $confirmada;
	private $esPotConfirmar;
	private $cuidador;
	private $emergencia;

	public function __construct() {
	}

	public function obteId() {
		return $this->id;
	}
	
	public function modificaId($i) {
		$this->id = $i;
	}
	
	public function obteConfirmada() {
		return $this->confirmada;
	}
	
	public function modificaConfirmada($c) {
		$this->confirmada = $c;
	}
	
	public function obteEsPotConfirmar() {
		return $this->esPotConfirmar;
	}
	
	public function modificaEsPotConfirmar($e) {
		$this->esPotConfirmar = $e;
	}
	
	public function obteCuidador() {
		return $this->cuidador;
	}
	
	public function modificaCuidador($c) {
		$this->cuidador = $c;
	}

	public function obteEmergencia() {
		return $this->emergencia;
	}
	
	public function modificaEmergencia($e) {
		$this->emergencia = $e;
	}
	
	public function enviaSMS($t, $m) {
		$sl = ServiceLocator::getInstance();
		$ase = $sl->troba("ServeiSMS");
		$ase->enviaSMS($t, $m);	
	}
	

	public function deQueEts() {
		return $this->emergencia->queEts();
	}
	
	public function obteMoment() {
		return $this->emergencia->obteMoment();
	}
	
	public function obtePeriode() {
		return $this->emergencia->obtePeriodeDeConfirmacio();
	}
	
	public function obteAfectat() {
		return $this->emergencia->obteAfectat();
	}
	
	public function obteTelefonCuidador() {
		return $this->cuidador->obteTelefon();
	}
	
	public function confirma($telf1) {
		$telf2 = $this->cuidador->obteTelefon();
		if($telf1 == $telf2) {
			if($this->esPotConfirmar) {
				$this->esPotconfirmar = FALSE;
				$this->confirmada = TRUE;
			} else {
				$this->enviaSMS($telf2, "Aquesta notificació ja no es pot confirmar");
				throw new Exception("FORA_TEMPS");
			}
		}
		else {
			throw new Exception("No és una notificació teva");
		}
		
	}
	

	public function enviaNotificacioAlServeiDeEmergencies() {
		$mis = $this->emergencia->obteMissatge();
		$con = Contactes::getInstance();
		$telf = $con->obteTelefonDelServeiDeEmergencies();
		//enviaSMS($telf,$mis);		
	}
	
	public function callBackTimerNotificacio() 	{
		$this->esPotConfirmar = FALSE;
		$ContDades = FabricaControladorsDades::getInstance();
		$CtrlNotificacio = $ContDades->getIControladorNotificacio();
		$CtrlNotificacio->actualitza($this);
		if (!$this->confirmada) {
			$this->enviaNotificacioAlServeiDeEmergencies();
		}
	}
	
	public function notifica() {
		$m = $this->emergencia->obteMissatge();
		$t = $this->cuidador->obteTelefon();
		$this->enviaSMS($t,$m);
		$s = $this->emergencia->obtePeriodeDeConfirmacio();
		
		//exec ('script.php');
		
		
		//$this->callBackTimerNotificacio();
	}
}

?>