<?php

require_once ("Transaccio.php");
//require_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "dades" . DIRECTORY_SEPARATOR . "DB.php"); 


class TxModificaTelefonTest implements Transaccio {
	
	private $resultat;
	private $telefon;
	
	public function execu() {
		DB::executeQuery("UPDATE cuidador SET telefon = '" . $this->telefon . "' WHERE nom = 'cuidador_test';");
		$this->resultat = TRUE;
	}

	public function obteResultat() {
		return $this->resultat;
	}
	
	public function modificaTelefon($tel) {
		$this->telefon = $tel;
	}
}

?>