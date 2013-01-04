<?php

include_once("Emergencia.php");
include_once ("Resident.php");


abstract class EmergenciaResident extends Emergencia {
	
	protected $resident;
	
	public function obteCuidador() {
		return $this->resident->obteCuidadorDeGuardia();
	}
	
	public function obtePeriodeDeConfirmacio() {
		return $this->resident->obtePeriodeDeConfirmacio();
	}
}

?>