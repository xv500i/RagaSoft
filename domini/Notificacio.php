<?php

include_once ("Cuidador.php");
//include_once (__DIR__ . "\\..\\dades\\FabricaControladorsDades.php");
//include_once ("IControladorNotificacio.php");

class Notificacio {
	
	private $id;
	private $confirmada;
	private $esPotConfirmar;
	private $cuidador;
	private $emergencia;

	private function __construct($idN, $con, $esPo, $cui, $emer) {
		$id = $idN;
		$confirmada = $con;
		$esPotConfirmar = $esPo;
		$cuidador = $cui;
		$emergencia = $emer;
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
}

?>