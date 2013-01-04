<?php

include_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "domini" . DIRECTORY_SEPARATOR . "IControladorResident.php");
include_once ("DB.php");
include_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "domini" . DIRECTORY_SEPARATOR . "Resident.php");

class ControladorResident implements IControladorResident {
	
    private static $querySelectAbstract = "SELECT * FROM RESIDENT WHERE idRfid = '?1';";
	private static $querySelectAll = "SELECT * FROM RESIDENT;";	
		
    public function obte($usuari) {
    	$query = str_replace("?1", $usuari, self::$querySelectAbstract);
		$result = DB::executeQuery($query);
		$resident = NULL;
		while($row = mysql_fetch_array($result)) {
			$resident = new Resident();
  			$resident->modificaHoraArribada($row['horaArribada']);
			$resident->modificaIdRfid($row['idRfid']);
			$resident->modificaNom($row['nom']);
			$resident->modificaTeCaigudaActivada((bool)$row['teCaigudaActivada']);
			$resident->modificaTeTardancaActivada((bool)$row['teTardancaActivada']);
			$resident->modificaTeMancaActivada((bool)$row['teMancaActivada']);
			$resident->modificaTePermanenciaProlongadaActivada((bool)$row['tePermanenciaActivada']);
			$resident->modificaLlar($row['usuariLlar']);
		}
		return $resident;	
    }
	
	public function existeix($usuari) {
		$query = str_replace("?1", $usuari, self::$querySelectAbstract);
		$result = DB::executeQuery($query);
		if(mysql_num_rows($result) > 0) return TRUE;
		else return FALSE;
	}
	
	public function tots() {
		$result = DB::executeQuery(self::$querySelectAll);
		$residents = array();
		while($row = mysql_fetch_array($result)) {
			$resident = new Resident();
  			$resident->modificaHoraArribada($row['horaArribada']);
			$resident->modificaIdRfid($row['idRfid']);
			$resident->modificaNom($row['nom']);
			$resident->modificaTeCaigudaActivada((bool)$row['teCaigudaActivada']);
			$resident->modificaTeTardancaActivada((bool)$row['teTardancaActivada']);
			$resident->modificaTeMancaActivada((bool)$row['teMancaActivada']);
			$resident->modificaTePermanenciaProlongadaActivada((bool)$row['tePermanenciaActivada']);
			$resident->modificaLlar($row['usuariLlar']);
			array_push($residents, $resident);
		}
		return $residents;
	}

}
?>