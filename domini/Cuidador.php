<?php

class Cuidador {
	
	private $nom;
	private $telefon;

	public function __construct() {
	}

	public function obteTelefon() {
		return $this->telefon;
	}
	
	public function modificaTelefon($t) {
		$this->telefon = $t;
	}
	
	public function obteNom() {
		return $this->nom;
	}
	
	public function modificaNom($n) {
		$this->nom = $n;
	}
}

?>