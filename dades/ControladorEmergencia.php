<?php

include_once (__DIR__ . "\\..\\domini\\IControladorEmergencia.php");

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
	}

}
?>