<?php

include_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "domini" . DIRECTORY_SEPARATOR . "IControladorLlar.php");
include_once ("DB.php");
include_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "domini" . DIRECTORY_SEPARATOR . "Llar.php");

class ControladorLlar implements IControladorLlar {
	
    private static $querySelectAbstract = "SELECT * FROM LLAR WHERE usuari = '?1';";
	private static $querySelectAll = "SELECT * FROM LLAR;";	
		
    public function obte($usuari) {
    	$query = str_replace("?1", $usuari, self::$querySelectAbstract);
		$result = DB::executeQuery($query);
		$Llar = new Llar();
		while($row = mysql_fetch_array($result)) {
  			$Llar->modificaAdreca($row['adreca']);
			$Llar->modificaContrasenya($row['contrasenya']);
			$Llar->modificaUsuari($row['usuari']);
			$Llar->modificaPeriodeDeConfirmacio($row['periodeConfirmacio']);	
		}
		return $Llar;
    }
	
	public function existeix($usuari) {
		$query = str_replace("?1", $usuari, self::$querySelectAbstract);
		$result = DB::executeQuery($query);
		if(mysql_num_rows($result) > 0) return TRUE;
		else return FALSE;
	}
	
	public function tots() {
		$result = DB::executeQuery(self::$querySelectAll);
		$arr = array();
		while($row = mysql_fetch_array($result)) {
  			$Llar = new Llar();
  			$Llar->modificaAdreca($row['adreca']);
			$Llar->modificaContrasenya($row['contrasenya']);
			$Llar->modificaUsuari($row['usuari']);
			$Llar->modificaPeriodeDeConfirmacio($row['periodeConfirmacio']);
			array_push($arr, $Llar);	
		}
		return $arr;
	}

}
?>