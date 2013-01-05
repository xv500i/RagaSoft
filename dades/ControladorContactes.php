<?php

include_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "domini" . DIRECTORY_SEPARATOR . "IControladorContactes.php");
include_once ("DB.php");

// NINGU FA SERVIR AQUESTA CLASSE

class ControladorContactes implements IControladorContactes {
	
    private static $querySelectAbstract = "SELECT * FROM CONTACTES WHERE telefon = '?1';";
	private static $querySelectAll = "SELECT * FROM CONTACTES;";
		
    public function obte($telefon) {
    	$query = str_replace("?1", $telefon, self::$querySelectAbstract);
		$result = DB::executeQuery($query);
		// TODO: crear usuari
    }
	
	public function obteTelf($nom) {
		$query = "select telefon from contactes where descripcio = '?1'";
		$query = str_replace("?1", $nom, $query);
		$result = DB::executeQuery($query);
		while($row = mysql_fetch_array($result))
		{
			$telf = $row['telefon'];
		}
		return $telf;
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
		return $result;
	}

}
?>