<?php

class Cuidador {
	
	private $nom;
	private $telefon;

	private function __construct() {
	}

	public function obteTelefon() {
		return $telefon;
	}
	
	public function modificaTelefon($t) {
		$telefon = $t;
	}
	
	public function obteNom() {
		return $nom;
	}
	
	public function modificaNom($n) {
		$nom = $n;
	}
}

?>