<?php

require_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "domini" . DIRECTORY_SEPARATOR . "IControladorLlar.php");
//require_once ("DB.php");
require_once ("FabricaControladorsDades.php");
require_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "domini" . DIRECTORY_SEPARATOR . "Llar.php");
require_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "domini" . DIRECTORY_SEPARATOR . "Cuidador.php");

class ControladorLlar implements IControladorLlar {
	
    private static $querySelectAbstract = "SELECT * FROM llar WHERE usuari = '?1';";
	private static $querySelectAll = "SELECT * FROM llar;";	
		
    public function obte($usuari) {
    	$query = str_replace("?1", $usuari, self::$querySelectAbstract);
		$result = DB::executeQuery($query);
		$Llar = NULL;
		while($row = mysql_fetch_array($result)) {
			$Llar = new Llar();
  			$Llar->modificaAdreca($row['adreca']);
			$Llar->modificaContrasenya($row['contrasenya']);
			$Llar->modificaUsuari($row['usuari']);
			$Llar->modificaPeriodeDeConfirmacio((int)$row['periodeConfirmacio']);
			$sq = DB::executeQuery("SELECT telefon FROM cuidador WHERE usuariLlar = '" . $row['usuari'] . "';");
			// aqui hi ha l'ultim canvi per afegir més d'un cuidador
			$f = FabricaControladorsDades::getInstance();
			$cc = $f->getIControladorCuidador();
			$cuidadors = array();
			while($rr = mysql_fetch_array($sq)) {
				$idCuidador = $rr['telefon'];
				$cuidador = $cc->obte($idCuidador);
				array_push($cuidadors, $cuidador);
			}
			$Llar->modificaCuidadors($cuidadors);
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
			$Llar->modificaPeriodeDeConfirmacio((int)$row['periodeConfirmacio']);
			$sq = DB::executeQuery("SELECT telefon FROM cuidador WHERE usuariLlar = '" . $row['usuari'] . "';");
			// aqui hi ha l'ultim canvi per afegir més d'un cuidador
			$f = FabricaControladorsDades::getInstance();
			$cc = $f->getIControladorCuidador();
			$cuidadors = array();
			while($rr = mysql_fetch_array($sq)) {
				$idCuidador = $rr['telefon'];
				$cuidador = $cc->obte($idCuidador);
				array_push($cuidadors, $cuidador);
			}
			$Llar->modificaCuidadors($cuidadors);
			array_push($arr, $Llar);	
		}
		return $arr;
	}

}
?>