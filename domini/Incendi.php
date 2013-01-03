<?php

class Incendi extends Emergencia {
	
	private $llar;
	
	public function obteCuidador() {
		return $this->llar->obteCuidadorDeGuardia();
	}
	
	public function obtePeriodeDeConfirmacio() {
		return $this->llar->obtePeriodeDeConfirmacio();
	}
	
	public function obteMissatge() {
		return "Hi ha un incendi a la casa " + $this->llar->obteUsuari();
	}
	
	public function obteLlar() {
		return $this->llar;
	}
	
	public function modificaLLar($Llar) {
		$this->llar = $Llar;
	}
}

?>