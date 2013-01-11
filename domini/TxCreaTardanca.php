<?php

require_once ("Transaccio.php");
require_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "dades" . DIRECTORY_SEPARATOR . "FabricaControladorsDades.php");


class TxCreaTardanca implements Transaccio {
	
	private $idResident;
	private $tardanca;

	public function execu() {
			$ContDades = FabricaControladorsDades::getInstance();
			$CtrlResident = $ContDades->getIControladorResident();
			$resident = $CtrlResident->obte($this->idResident);
			if(is_null($resident)) {
				throw new Exception ("residentNoExisteix");
			} else {
				$CtrlEmergencia = $ContDades->getIControladorEmergencia();
				$t = $CtrlEmergencia->creaTardanca($resident);
				$this->tardanca = $t;				
			}		
	}
	
	public function obteResultat() {
		return $this->tardanca;
	}
	
	public function modificaIdResident($id) {
		$this->idResident = $id;
	}
}

?>