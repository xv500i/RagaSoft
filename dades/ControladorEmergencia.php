<?php

include_once (__DIR__ . "\\..\\domini\\IControladorEmergencia.php");
include_once ("DB.php");

class ControladorEmergencia implements IControladorEmergencia {
	
    private static $querySelectAbstract = "SELECT * FROM EMERGENCIA WHERE moment = '?1';";
	private static $querySelectAll = "SELECT * FROM EMERGENCIA;";
		
    public function obte($moment) {
    	$query = str_replace("?1", $moment, self::$querySelectAbstract);
		$result = DB::executeQuery($query);
		// TODO: crear usuari	
    }
	
	public function existeix($moment) {
		$query = str_replace("?1", $moment, self::$querySelectAbstract);
		$result = DB::executeQuery($query);
		if(mysql_num_rows($result) > 0) return TRUE;
		else return FALSE;
	}
	
	public function tots() {
		$result = DB::executeQuery(self::$querySelectAll);
		// TODO: crear usuaris
		return $result;
	}

	public function creaIncendi($Llar) {
		$iq = "INSERT INTO EMERGENCIA (moment, tipus, usuariLlar, idRfidResident) VALUES (now(),'?2','?3','?4');";
		$iq = str_replace("?2", "Incendi", $iq);
		$iq = str_replace("?3", $Llar->getUsuari(), $iq);
		$iq = str_replace("?4", "NULL", $iq);
		DB::executeQuery($iq);
	}
	
	public function creaTardanca($resident) {
		$iq = "INSERT INTO EMERGENCIA (moment, tipus, usuariLlar, idRfidResident) VALUES (now(),'?2','?3','?4');";
		$iq = str_replace("?2", "Tardanca", $iq);
		$iq = str_replace("?3", "NULL", $iq);
		$iq = str_replace("?4", $resident->getUsuari(), $iq);
		DB::executeQuery($iq);
	}
	
	public function creaCaiguda($resident) {
		$iq = "INSERT INTO EMERGENCIA (moment, tipus, usuariLlar, idRfidResident) VALUES (now(),'?2','?3','?4');";
		$iq = str_replace("?2", "Caiguda", $iq);
		$iq = str_replace("?3", "NULL", $iq);
		$iq = str_replace("?4", $resident->getUsuari(), $iq);
		DB::executeQuery($iq);
	}

}
?>