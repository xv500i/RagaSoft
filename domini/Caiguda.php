<?php

include_once("EmergenciaResident.php");
include_once ("Resident.php");

class Caiguda extends EmergenciaResident {
	
	public function obteMissatge() {
		return "El resident " + $this->resident->obteIdRfid() + " ha caigut";
	}
}

?>