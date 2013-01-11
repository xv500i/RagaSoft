<?php

require_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "domini" . DIRECTORY_SEPARATOR . "IControladorNotificacio.php");
//require_once ("DB.php");
require_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "domini" . DIRECTORY_SEPARATOR . "Notificacio.php");
require_once ("FabricaControladorsDades.php");

class ControladorNotificacio implements IControladorNotificacio {
	
    private static $querySelectAbstract = "SELECT * FROM notificacio WHERE id = '?1';";
	private static $querySelectAll = "SELECT * FROM notificacio order by momentEmergencia DESC;";
		
    public function obte($id) {
    	$query = str_replace("?1", $id, self::$querySelectAbstract);
		$result = DB::executeQuery($query);
		$notificacio = new Notificacio();
		while($row = mysql_fetch_array($result)) {
  			$notificacio->modificaId((int)$row['id']);
			$notificacio->modificaConfirmada((bool)$row['confirmada']);
			$notificacio->modificaEsPotConfirmar((bool)$row['esPotConfirmar']);
			$f = FabricaControladorsDades::getInstance();
			$cc = $f->getIControladorCuidador();
			$notificacio->modificaCuidador($cc->obte($row['idCuidador']));
			$ce = $f->getIControladorEmergencia();
			$notificacio->modificaEmergencia($ce->obte($row['momentEmergencia']));	
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
			$f = FabricaControladorsDades::getInstance();
			$cc = $f->getIControladorCuidador();
			$notificacio->modificaCuidador($cc->obte($row['idCuidador']));
			$ce = $f->getIControladorEmergencia();
			$notificacio->modificaEmergencia($ce->obte($row['momentEmergencia']));
			array_push($notificacions, $notificacio);
		}
		return $notificacions;
	}

	public function actualitza($notificacio) {
		// FIXME: falta que el implementador del domini fagi les operacions
		$id = $notificacio->obteId();
		$c = ($notificacio->obteConfirmada() ? "true": "false");
		$epc = ($notificacio->obteEsPotconfirmar() ? "true": "false");
		$uq = "UPDATE notificacio SET confirmada=" . $c . ", esPotConfirmar=" . $epc . " WHERE id='" . $id . "';";
		DB::executeQuery($uq);
	}
	
	public function creaNotificacio($emergencia, $cuidador) {
		$momentEmergencia = $emergencia->obteMoment();
		$idCuidador = $cuidador->obteTelefon();
		$iq = "INSERT INTO notificacio (idCuidador, momentEmergencia, confirmada, esPotConfirmar) VALUES('?1','?2',false,true)";
		$iq = str_replace("?1", $idCuidador, $iq);
		$iq = str_replace("?2", $momentEmergencia, $iq);
		DB::executeQuery($iq);
		$n = new Notificacio();
		$n->modificaEmergencia($emergencia);
		$n->modificaCuidador($cuidador);
		$n->modificaConfirmada(FALSE);
		$n->modificaEsPotConfirmar(TRUE);
		$query = "SELECT id FROM notificacio WHERE momentEmergencia='?1'";
		$query = str_replace("?1", $momentEmergencia, $query);
		$result = DB::executeQuery($query);
		while($row = mysql_fetch_array($result))
		{
			$id = $row['id'];
		  	$n->modificaId($id);
		}
		return $n;
	}
}
?>