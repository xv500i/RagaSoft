<?php

class Llar {
	private $adreca;
	private $contrasenya;
	private $periodeDeConfirmacio;
	private $usuari;
	private $cuidador;
	
	// getters
	
	public function obteAdreca() {
		return $this->adreca;
	}
	
	public function obteContrasenya() {
		return $this->contrasenya;
	}
	
	public function obtePeriodeDeConfirmacio() {
		return $this->periodeDeConfirmacio;
	}
	
	public function obteUsuari() {
		return $this->usuari;
	}
	
	public function obteCuidadorDeGuardia() {
		return $this->cuidador();
	}

	//setters
	
	public function modificaAdreca($adreca) {
		$this->adreca=$adreca;
	}
	
	public function modificaContrasenya($contrasenya) {
		$this->contrasenya = $contrasenya;
	}
	
	public function modificaPeriodeDeConfirmacio($periodeDeConfirmacio) {
		$this->periodeDeConfirmacio = $periodeDeConfirmacio;
	}
	
	public function modificaUsuari($usuari) {
		$this->usuari = $usuari;
	}
	
	public function modificaCuidadorDeGuardia($cuidador) {
		$this->cuidador() = $cuidador;
	}
	

}
?>