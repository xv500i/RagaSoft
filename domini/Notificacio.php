<?php

include_once ("Cuidador.php");
include_once ("ServiceLocator.php");
include_once ("AdaptadorServeiEmergencies.php");
include_once (__DIR__ . "\\..\\dades\\FabricaControladorsDades.php");
include_once ("IControladorNotificacio.php");
include_once ("Contactes.php");

class Notificacio {
	
	private $id;
	private $confirmada;
	private $esPotConfirmar;
	private $cuidador;
	private $emergencia;

	private function __construct() {
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

	public function confirma($telf1) {
		$telf2 = $this->cuidador->obteTelefon();
		if($telf1 == $telf2) {
			if($this->esPotConfirmar) {
				$this->esPotconfirmar = FALSE;
				$this->confirmada = TRUE;
			} else {
				enviaSMS($telf2, "Aquesta notificació ja no es pot confirmar");
			}
		}
	}
	
	public function enviarSMS($t, $m) {
		$sl = new ServiceLocator();
		$sl->getInstance();
		$ase = $sl->troba("ServeiSMS");
		$ase->enviaSMS($t, $m);	
	}
	
	public function enviaNotificacioAlServeiDeEmergencies() {
		$mis = $this->emergencia->obteMissatge();
		$con = new Contactes();
		$con->getInstance();
		$telf = $con->obteTelefonDelServeiDeEmergencies();
		enviarSMS($telf,$mis);		
	}
	
	public function callBackTimerNotificacio() 	{
		$this->esPotConfirmar = FALSE;
		$ContDades = new FabricaControladorsDades();
		$ContDades->getInstance();
		$CtrlNotificacio = $ContDades->getIControladorNotificacio();
		$CtrlNotificacio->actualitza($this);
		if (!$this->confirmada) {
			enviaNotificacioAlServeiDeEmergencies();
		}
	}
	
	public function notifica() {
		$m = $this->emergencia->obteMissatge();
		$t = $this->cuidador->obteTelefon();
		enviaSMS($t,$m);
		$s = $this->emergencia->obtePeriodeDeConfirmacio();
		sleep($s);
		callBackTimerNotificacio();
	}
}

?>