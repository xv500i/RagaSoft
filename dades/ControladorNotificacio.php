<?php

include_once (__DIR__ . "\\..\\domini\\IControladorNotificacio.php");
include_once ("DB.php");

class ControladorNotificacio implements IControladorNotificacio {
	
    private static $querySelectAbstract = "SELECT * FROM NOTIFICACIO WHERE id = '?1';";
	private static $querySelectAll = "SELECT * FROM NOTIFICACIO;";
		
    public function obte($id) {
    	$query = str_replace("?1", $id, self::$querySelectAbstract);
		$result = DB::executeQuery($query);
		// TODO: crear usuari	
    }
	
	public function existeix($id) {
		$query = str_replace("?1", $id, self::$querySelectAbstract);
		$result = DB::executeQuery($query);
		if(mysql_num_rows($result) > 0) return TRUE;
		else return FALSE;
	}
	
	public function tots() {
		$result = DB::executeQuery(self::$querySelectAll);
		// TODO: crear usuaris
		return $result;
	}

	public function actualitza($notificacio) {
		// FIXME: falta que el implementador del domini fagi les operacions
		$id = $notificacio->getId();
		$c = ($notificacio->getConfirmada() ? "true": "false");
		$epc = ($notificacio->getEsPotconfirmar() ? "true": "false");
		$uq = "UPDATE NOTIFICACIO SET confirmada='" . $c . "', esPotConfirmar='" . $epc . "' WHERE id='" . $id . "';";
		DB::executeQuery($uq);
	}
	
	public function creaNotificacio($emergencia, $cuidador) {
		$momentEmergencia = $emergencia->getMoment();
		$idCuidador = $cuidador->getTelefon();
		$iq = "INSERT INTO NOTIFICACIO (idCuidador, momentEmergencia, confirmada, esPotConfirmar) VALUES('?1','?2','?3','?4')";
		$iq = str_replace("?1", $idCuidador, $iq);
		$iq = str_replace("?2", $momentEmergencia, $iq);
		$iq = str_replace("?3", "false", $iq);
		$iq = str_replace("?4", "true", $iq);
		DB::executeQuery($iq);
	}
}
?>