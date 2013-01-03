<?php

include_once ("Cuidador.php");
include_once ("ServiceLocator.php");
include_once ("AdaptadorServeiEmergencies.php");
//include_once (__DIR__ . "\\..\\dades\\FabricaControladorsDades.php");
//include_once ("IControladorNotificacio.php");

class Notificacio {
	
	private $id;
	private $confirmada;
	private $esPotConfirmar;
	private $cuidador;
	private $emergencia;

	private function __construct() {
	}

	public function obteId() {
		return $id;
	}
	
	public function modificaId($i) {
		$id = $i;
	}
	
	public function obteConfirmada() {
		return $confirmada;
	}
	
	public function modificaConfirmada($c) {
		$confirmada = $c;
	}
	
	public function obteEsPotConfirmar() {
		return $esPotConfirmar;
	}
	
	public function modificaEsPotConfirmar($e) {
		$esPotConfirmar = $e;
	}
	
	public function obteCuidador() {
		return $cuidador;
	}
	
	public function modificaCuidador($c) {
		$cuidador = $c;
	}

	public function obteEmergencia() {
		return $emergencia;
	}
	
	public function modificaEmergencia($e) {
		$emergencia = $e;
	}

	public function confirma($telf1) {
		$telf2 = $cuidador->obteTelefon();
		if($telf1 == $telf2) {
			if($esPotConfirmar) {
				$esPotconfirmar = FALSE;
				$confirmada = TRUE;
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
}

?>