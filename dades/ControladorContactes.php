<?php

include_once (__DIR__ . "\\..\\domini\\IControladorContactes.php");
include_once ("DB.php");

class ControladorContactes implements IControladorContactes {
	
    private static $querySelectAbstract = "SELECT * FROM CONTACTES WHERE telefon = '?1';";
	private static $querySelectAll = "SELECT * FROM CONTACTES;";
		
    public function obte($telefon) {
    	$query = str_replace("?1", $telefon, self::$querySelectAbstract);
		$result = DB::executeQuery($query);
		// TODO: crear usuari	
    }
	
	public function existeix($telefon) {
		$query = str_replace("?1", $telefon, self::$querySelectAbstract);
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