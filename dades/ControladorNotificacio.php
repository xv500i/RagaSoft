<?php

include_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "domini" . DIRECTORY_SEPARATOR . "IControladorNotificacio.php");
include_once ("DB.php");
include_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "domini" . DIRECTORY_SEPARATOR . "Notificacio.php");

class ControladorNotificacio implements IControladorNotificacio {
	
    private static $querySelectAbstract = "SELECT * FROM NOTIFICACIO WHERE id = '?1';";
	private static $querySelectAll = "SELECT * FROM NOTIFICACIO;";
		
    public function obte($id) {
    	$query = str_replace("?1", $id, self::$querySelectAbstract);
		$result = DB::executeQuery($query);
		$notificacio = new Notificacio();
		while($row = mysql_fetch_array($result)) {
  			$notificacio->modificaId((int)$row['id']);
			$notificacio->modificaConfirmada((bool)$row['confirmada']);
			$notificacio->modificaEsPotConfirmar((bool)$row['esPotConfirmar']);
			$notificacio->modificaCuidador($row['idCuidador']);
			$notificacio->modificaEmergencia($row['momentEmergencia']);	
		}
		return $notificacio;	
    }
	
	public function existeix($id) {
		$query = str_replace("?1", $id, self::$querySelectAbstract);
		$result = DB::executeQuery($query);
		if(mysql_num_rows($result) > 0) return TRUE;
		else return FALSE;
	}
	
	public function tots() {
		$result = DB::executeQuery(self::$querySelectAll);
		$notificacions = array();
		while($row = mysql_fetch_array($result)) {
			$notificacio = new Notificacio();
  			$notificacio->modificaId((int)$row['id']);
			$notificacio->modificaConfirmada((bool)$row['confirmada']);
			$notificacio->modificaEsPotConfirmar((bool)$row['esPotConfirmar']);
			$notificacio->modificaCuidador($row['idCuidador']);
			$notificacio->modificaEmergencia($row['momentEmergencia']);
			array_push($notificacions, $notificacio);
		}
		return $notificacions;
	}

	public function actualitza($notificacio) {
		// FIXME: falta que el implementador del domini fagi les operacions
		$id = $notificacio->getId();
		$c = ($notificacio->getConfirmada() ? "true": "false");
		$epc = ($notificacio->getEsPotconfirmar() ? "true": "false");
		$uq = "UPDATE NOTIFICACIO SET confirmada=" . $c . ", esPotConfirmar=" . $epc . " WHERE id='" . $id . "';";
		DB::executeQuery($uq);
	}
	
	public function creaNotificacio($emergencia, $cuidador) {
		$momentEmergencia = $emergencia->obteMoment();
		$idCuidador = $cuidador->obteTelefon();
		
		echo $momentEmergencia;
		echo "<br>";
		echo "cuidador: ".$idCuidador;
		echo "<br>";
		$iq = "INSERT INTO NOTIFICACIO (idCuidador, momentEmergencia, confirmada, esPotConfirmar) VALUES('?1','?2',false,true)";
		$iq = str_replace("?1", $idCuidador, $iq);
		$iq = str_replace("?2", $momentEmergencia, $iq);
		DB::executeQuery($iq);
	}
}
?>