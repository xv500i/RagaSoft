<?php

require_once ("Transaccio.php");
require_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "dades" . DIRECTORY_SEPARATOR . "FabricaControladorsDades.php");



class TxCreaIncedi implements Transaccio {
	
	private $incendi;
	private $usuari;

	public function execu() {
			$ContDades = FabricaControladorsDades::getInstance();
			$CtrlLlar = $ContDades->getIControladorLlar();
			$Llar = $CtrlLlar->obte($this->usuari);
			if(is_null($Llar)) {
				throw new Exception ("llarNoExisteix");
			} else {
				$CtrlEmergencia = $ContDades->getIControladorEmergencia();
				$i = $CtrlEmergencia->creaIncendi($Llar);
				$this->incendi = $i;				
			}			
	}
	
	public function obteResultat() {
		return $this->incendi;
	}
	
	public function modificaUsuari($u) {
		$this->usuari = $u;
	}
}

?>