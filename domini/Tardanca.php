<?php

class Tardanca extends EmergenciaResident {
	
	public function obteMissatge() {
		return "El resident " + $this->resident->obteIdRfid() + " no és a casa a l'hora que hi hauria de ser";
	}
}

?>