<?php

include_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "domini" . DIRECTORY_SEPARATOR . "IControladorCuidador.php");
include_once ("DB.php");
include_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "domini" . DIRECTORY_SEPARATOR . "Cuidador.php");

class ControladorCuidador implements IControladorCuidador {
	
    private static $querySelectAbstract = "SELECT * FROM cuidador WHERE telefon = '?1';";
	private static $querySelectAll = "SELECT * FROM cuidador;";	
		
    public function obte($telefon) {
    	$query = str_replace("?1", $telefon, self::$querySelectAbstract);
		$result = DB::executeQuery($query);
		$cuidador = new Cuidador();
		while($row = mysql_fetch_array($result)) {
  			$cuidador->modificaNom($row['nom']);
			$cuidador->modificaTelefon($row['telefon']);	
		}
		return $cuidador;	
    }
	
	public function existeix($telefon) {
		$query = str_replace("?1", $telefon, self::$querySelectAbstract);
		$result = DB::executeQuery($query);
		if(mysql_num_rows($result) > 0) return TRUE;
		else return FALSE;
	}
	
	public function tots() {
		$result = DB::executeQuery(self::$querySelectAll);
		$cuidadors = array();
		while($row = mysql_fetch_array($result)) {
			$cuidador = new Cuidador();
  			$cuidador->modificaNom($row['nom']);
			$cuidador->modificaTelefon($row['telefon']);
			array_push($cuidadors, $cuidador);
		}
		return $cuidadors;
	}

}
?>