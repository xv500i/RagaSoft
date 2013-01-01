<?php

include_once (__DIR__ . "\\..\\domini\\IControladorNotificacio.php");

class ControladorNotificacio implements IControladorNotificacio {
	
    private static $querySelectAbstract = "SELECT * FROM CONTACTES WHERE nom = '?1';";
	private static $querySelectAll = "SELECT * FROM CONTACTES;";
		
    public function obte($id) {
    	$query = str_replace("?1", $id, self::$querySelectAbstract);
		$result = DB::executeQuery($query);
		// TODO: crear usuari	
    }
	
	public function existeix($id) {
		$query = str_replace("?1", $id, self::$querySelectAbstract);
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