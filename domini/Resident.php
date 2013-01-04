<?php
include_once("Llar.php");

class Resident {
	
	private $horaArribada;
	private $idRfid;
	private $nom;
	private $teCaigudaActivada;
	private $teMancaActivada;
	private $tePermanenciaProlongadaActivada;
	private $teTardancaActivada;
	private $llar;
	
	public function __construct() {
	}

	public function obteHoraArribada() {
		return $this->horaArribada;
	}
	
	public function modificaHoraArribada($h) {
		$this->horaArribada = $h;
	}

	public function obteIdRfid() {
		return $this->idRfid;
	}
	
	public function modificaIdRfid($i) {
		$this->idRfid = $i;
	}

	public function obteNom() {
		return $this->nom;
	}
	
	public function modificaNom($n) {
		$this->nom = $n;
	}
	
	public function obteTeCaigudaActivada() {
		return $this->teCaigudaActivada;
	}
	
	public function modificaTeCaigudaActivada($t) {
		$this->teCaigudaActivada = $t;
	}
	
	public function obteTeMancaActivada() {
		return $this->teMancaActivada;
	}
	
	public function modificaTeMancaActivada($t) {
		$this->teMancaActivada = $t;
	}
	
	public function obteTePermanenciaProlongadaActivada() {
		return $this->tePermanenciaProlongadaActivada;
	}
	
	public function modificaTePermanenciaProlongadaActivada($t) {
		$this->tePermanenciaProlongadaActivada = $t;
	}
	
	public function obteTeTardancaActivada() {
		return $this->teTardancaActivada;
	}
	
	public function modificaTeTardancaActivada($t) {
		$this->teTardancaActivada = $t;
	}
	
	public function obteLlar() {
		return $this->llar;
	}
	
	public function modificaLlar($ll) {
		$this->llar = $ll;
	}
	
	public function obtePeriodeDeConfirmacio() {
		return $this->llar->obtePeriodeDeConfirmacio();
	}
	
	public function obteCuidadorDeGuardia() {
		return $this->llar->obteCuidadorDeGuardia();
	}
}

?>