<?php

include_once("EmergenciaResident.php");
include_once("Resident.php");

class Tardanca extends EmergenciaResident {
	
	public function obteMissatge() {
		return "El resident " . $this->resident->obteNom() . "(" . $this->resident->obteIdRfid() . ")" . " no és a casa a l'hora que hi hauria de ser";
	}
	public function queEts() {
		return 'tardanca';
	}
}

?>