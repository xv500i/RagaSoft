<?php

require_once("Emergencia.php");
require_once ("Resident.php");


abstract class EmergenciaResident extends Emergencia {
	
	protected $resident;
	
	public function modificaResident($res) {
		$this->resident = $res;
	}
	
	public function obteAfectat() {
		return $this->resident->obteNom() . "(" . $this->resident->obteIdRfid() . ")";
	}
	
	public function obteCuidador() {
		return $this->resident->obteCuidadorDeGuardia();
	}
	
	public function obtePeriodeDeConfirmacio() {
		return $this->resident->obtePeriodeDeConfirmacio();
	}
}

?>