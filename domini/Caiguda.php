<?php

include_once("EmergenciaResident.php");
include_once ("Resident.php");

class Caiguda extends EmergenciaResident {
	
	public function obteMissatge() {
		//return "Caiguda";	
		return "El resident " . $this->resident->obteNom() . "(" . $this->resident->obteIdRfid() . ")" . " ha caigut";
	}
	
	public function queEts() {
		return 'caiguda';
	}
}

?>