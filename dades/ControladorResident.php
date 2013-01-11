<?php

require_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "domini" . DIRECTORY_SEPARATOR . "IControladorResident.php");
//require_once ("DB.php");
require_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "domini" . DIRECTORY_SEPARATOR . "Resident.php");
require_once ("FabricaControladorsDades.php");

class ControladorResident implements IControladorResident {
	
    private static $querySelectAbstract = "SELECT * FROM resident WHERE idRfid = '?1';";
	private static $querySelectAll = "SELECT * FROM resident;";	
		
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
			$f = FabricaControladorsDades::getInstance();
			$cl = $f->getIControladorLlar();
			$resident->modificaLlar($cl->obte($row['usuariLlar']));
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
			$f = FabricaControladorsDades::getInstance();
			$cl = $f->getIControladorLlar();
			$resident->modificaLlar($cl->obte($row['usuariLlar']));
			array_push($residents, $resident);
		}
		return $residents;
	}

}
?>