<?php

abstract class EmergenciaResident extends Emergencia {
	
	protected $resident;
	
	public function obteCuidador() {
		return $resident->obteCuidadorDeGuardia();
	}
	
	public function obtePeriodeDeConfirmacio() {
		return $resident->obtePeriodeDeConfirmacio();
	}
}

?>