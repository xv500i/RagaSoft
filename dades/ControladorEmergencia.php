<?php

include_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "domini" . DIRECTORY_SEPARATOR . "IControladorEmergencia.php");
include_once ("DB.php");
include_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "domini" . DIRECTORY_SEPARATOR . "Tardanca.php");
include_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "domini" . DIRECTORY_SEPARATOR . "Incendi.php");
include_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "domini" . DIRECTORY_SEPARATOR . "Caiguda.php");

class ControladorEmergencia implements IControladorEmergencia {
	
    private static $querySelectAbstract = "SELECT * FROM EMERGENCIA WHERE moment = '?1';";
	private static $querySelectAll = "SELECT * FROM EMERGENCIA;";
		
    public function obte($moment) {
    	$query = str_replace("?1", $moment, self::$querySelectAbstract);
		$result = DB::executeQuery($query);
		
		$row = mysql_fetch_array($result);
		switch ($row['tipus']) {
			case 'Tardanca':
				$obj = new Tardanca();
				break;
			case 'Caiguda':
				$obj = new Caiguda();
				break;
			case 'Incendi':
				$obj = new Incendi();
				break;
		}
		// FIXME: emergencia no està acabada!
		$obj->modificaMoment($row['moment']);			
		return $obj;	
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
		$iq = "INSERT INTO EMERGENCIA (moment, tipus, usuariLlar, idRfidResident) VALUES (now(),'Incendi','?3',NULL);";
		$iq = str_replace("?3", $Llar->obteUsuari(), $iq);
		DB::executeQuery($iq);
		$incendi = new Incendi();
		$incendi->modificaLLar($Llar);
		return $incendi;
	}
	
	public function creaTardanca($resident) {
		$iq = "INSERT INTO EMERGENCIA (moment, tipus, usuariLlar, idRfidResident) VALUES (now(),'Tardanca','?3','?4');";
		$iq = str_replace("?3", $resident->obteLlar(), $iq);
		$iq = str_replace("?4", $resident->obteIdRfid(), $iq);
		DB::executeQuery($iq);
		$tardanca = new Tardanca();
		$tardanca->modificaResident($resident);
		return $tardanca;
	}
	
	public function creaCaiguda($resident) {
		$iq = "INSERT INTO EMERGENCIA (moment, tipus, usuariLlar, idRfidResident) VALUES (now(),'Caiguda','?3','?4');";
		$iq = str_replace("?3", $resident->obteLlar(), $iq);
		$iq = str_replace("?4", $resident->obteIdRfid(), $iq);
		DB::executeQuery($iq);
		$caiguda = new Caiguda();
		$caiguda->modificaResident($resident);
		return $caiguda;
	}

}
?>