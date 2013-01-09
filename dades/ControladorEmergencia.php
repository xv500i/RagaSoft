<?php

include_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "domini" . DIRECTORY_SEPARATOR . "IControladorEmergencia.php");
include_once ("DB.php");
include_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "domini" . DIRECTORY_SEPARATOR . "Tardanca.php");
include_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "domini" . DIRECTORY_SEPARATOR . "Incendi.php");
include_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "domini" . DIRECTORY_SEPARATOR . "Caiguda.php");
include_once ("FabricaControladorsDades.php");

class ControladorEmergencia implements IControladorEmergencia {
	
    private static $querySelectAbstract = "SELECT * FROM emergencia WHERE moment = '?1';";
	private static $querySelectAll = "SELECT * FROM emergencia order by moment desc;";
		
    public function obte($moment) {
    	$query = str_replace("?1", $moment, self::$querySelectAbstract);
		$result = DB::executeQuery($query);
		$f = FabricaControladorsDades::getInstance();
		$row = mysql_fetch_array($result);
		switch ($row['tipus']) {
			case 'Tardanca':
				$obj = new Tardanca();
				$cr = $f->getIControladorResident();
				$obj->modificaResident($cr->obte($row['idRfidResident']));
				break;
			case 'Caiguda':
				$obj = new Caiguda();
				$cr = $f->getIControladorResident();
				$obj->modificaResident($cr->obte($row['idRfidResident']));
				break;
			case 'Incendi':
				$obj = new Incendi();
				$cl = $f->getIControladorLlar();
				$obj->modificaLLar($cl->obte($row['usuariLlar']));
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
		$f = FabricaControladorsDades::getInstance();
		$emergencies = array();
		while ($row = mysql_fetch_array($result)) {
			switch ($row['tipus']) {
				case 'Tardanca':
					$obj = new Tardanca();
					$cr = $f->getIControladorResident();
					$obj->modificaResident($cr->obte($row['idRfidResident']));
					break;
				case 'Caiguda':
					$obj = new Caiguda();
					$cr = $f->getIControladorResident();
					$obj->modificaResident($cr->obte($row['idRfidResident']));
					break;
				case 'Incendi':
					$obj = new Incendi();
					$cl = $f->getIControladorLlar();
					$obj->modificaLLar($cl->obte($row['usuariLlar']));
					break;
			}
			// FIXME: emergencia no està acabada!
			$obj->modificaMoment($row['moment']);
			array_push($emergencies, $obj);
		}
		return $emergencies;
	}

	public function creaIncendi($Llar) {
		date_default_timezone_set('Europe/Madrid');
		$date = date('Y-m-d H:i:s', time());
		$iq = "INSERT INTO EMERGENCIA (moment, tipus, usuariLlar, idRfidResident) VALUES ('?1','Incendi','?3',NULL);";
		$iq = str_replace("?1", $date, $iq);
		$iq = str_replace("?3", $Llar->obteUsuari(), $iq);
		DB::executeQuery($iq);
		$incendi = new Incendi();
		$incendi->modificaLLar($Llar);
		$incendi->modificaMoment($date);
		return $incendi;
	}
	
	public function creaTardanca($resident) {
		date_default_timezone_set('Europe/Madrid');
		$date = date('Y-m-d H:i:s', time());
		$iq = "INSERT INTO EMERGENCIA (moment, tipus, usuariLlar, idRfidResident) VALUES ('?1','Tardanca',NULL,'?4');";
		$iq = str_replace("?1", $date, $iq);
		$iq = str_replace("?4", $resident->obteIdRfid(), $iq);
		DB::executeQuery($iq);
		$tardanca = new Tardanca();
		$tardanca->modificaMoment($date);
		$tardanca->modificaResident($resident);
		return $tardanca;
	}
	
	public function creaCaiguda($resident) {
		date_default_timezone_set('Europe/Madrid');
		$date = date('Y-m-d H:i:s', time());
		$iq = "INSERT INTO EMERGENCIA (moment, tipus, usuariLlar, idRfidResident) VALUES ('?1','Caiguda',NULL,'?4');";
		$iq = str_replace("?1", $date, $iq);
		$iq = str_replace("?4", $resident->obteIdRfid(), $iq);
		DB::executeQuery($iq);
		$caiguda = new Caiguda();
		$caiguda->modificaMoment($date);
		$caiguda->modificaResident($resident);
		return $caiguda;
	}

}
?>