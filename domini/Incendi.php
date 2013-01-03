<?php

class Incendi extends Emergencia {
	
	private $llar;
	
	public function obteCuidador() {
		return $llar->obteCuidadorDeGuardia();
	}
	
	public function obtePeriodeDeConfirmacio() {
		return $llar->obtePeriodeDeConfirmacio();
	}
	
	public function obteMissatge() {
		return "Hi ha un incendi a la casa " + $llar->obteUsuari();
	}
}

?>