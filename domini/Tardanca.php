<?php

require_once("EmergenciaResident.php");
require_once("Resident.php");

class Tardanca extends EmergenciaResident {
	
	public function obteMissatge() {
		//return "Tardanca";
		return "El resident " . $this->resident->obteNom() . "(" . $this->resident->obteIdRfid() . ")" . " no és a casa a l'hora que hi hauria de ser";
	}
	public function queEts() {
		return 'tardanca';
	}
}

?>