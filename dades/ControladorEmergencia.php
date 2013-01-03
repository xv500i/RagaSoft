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
		$Llar = new Llar();
		$Llar->modificaAdreca($row['adreca']);
		$Llar->modificaContrasenya($row['contrasenya']);
		$Llar->modificaUsuari($row['usuari']);
		$Llar->modificaPeriodeDeConfirmacio($row['periodeConfirmacio']);	

		return $Llar;	
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
	}
	
	public function creaTardanca($resident) {
		$iq = "INSERT INTO EMERGENCIA (moment, tipus, usuariLlar, idRfidResident) VALUES (now(),'Tardanca',NULL,'?4');";
		$iq = str_replace("?4", $resident->obteUsuari(), $iq);
		DB::executeQuery($iq);
	}
	
	public function creaCaiguda($resident) {
		$iq = "INSERT INTO EMERGENCIA (moment, tipus, usuariLlar, idRfidResident) VALUES (now(),'Caiguda',NULL,'?4');";
		$iq = str_replace("?4", $resident->obteUsuari(), $iq);
		DB::executeQuery($iq);
	}

}
?>