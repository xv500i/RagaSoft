<?php

include_once ("Transaccio.php");
include_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "dades" . DIRECTORY_SEPARATOR . "DB.php");


class TxCarregaNotis implements Transaccio {
	
	private $resultat;
	private $telefon;
	
	public function execu() {
		DB::executeQuery("UPDATE cuidador SET telefon = '" . $this->telefon . "' WHERE nom = 'test';");
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