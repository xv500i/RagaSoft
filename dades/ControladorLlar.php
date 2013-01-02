<?php

include_once (__DIR__ . "\\..\\domini\\IControladorLlar.php");
include_once ("DB.php");

class ControladorLlar implements IControladorLlar {
	
    private static $querySelectAbstract = "SELECT * FROM LLAR WHERE usuari = '?1';";
	private static $querySelectAll = "SELECT * FROM LLAR;";	
		
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
		return $result;
	}

}
?>