<?php

class Caiguda extends EmergenciaResident {
	
	public function obteMissatge() {
		return "El resident " + $resident->obteIdRfid() + " ha caigut";
	}
}

?>