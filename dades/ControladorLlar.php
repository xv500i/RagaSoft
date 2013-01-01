<?php

include_once (__DIR__ . "\\..\\domini\\IControladorResident.php");

class ControladorResident implements IControladorResident {
	
    private static $querySelectAbstract = "SELECT * FROM RESIDENT WHERE usuari = '?1';";
	private static $querySelectAll = "SELECT * FROM RESIDENT;";	
		
    public function obte($usuari) {
    	$query = str_replace("?1", $usuari, self::$querySelectAbstract);
		$result = DB::executeQuery($query);
		// TODO: crear usuari	
    }
	
	public function existeix($usuari) {
		$query = str_replace("?1", $usuari, self::$querySelectAbstract);
		$result = DB::executeQuery($query);
		if(mysql_num_rows($result) > 0) return TRUE;
		else return FALSE;
	}
	
	public function tots() {
		$result = DB::executeQuery(self::$querySelectAll);
		// TODO: crear usuaris
	}

}
?>