<?php

include_once ("Transaccio.php");
include_once (__DIR__ . "\\..\\dades\\FabricaControladorsDades.php");


class TxCreaCaiguda implements Transaccio {
	
	private $idResident;
	private $caiguda;

	public function execu() {
			$ContDades = FabricaControladorsDades::getInstance();
			$CtrlResident = $ContDades->getIControladorResident();
			$resident = $CtrlResident->obte($this->idResident);
			if(is_null($resident)) {
				throw new Exception ("residentNoExisteix");
			} else {
				$CtrlEmergencia = $ContDades->getIControladorEmergencia();
				$c = $CtrlEmergencia->creaCaiguda($resident);
				$this->caiguda = $c;				
			}		
	}
	
	public function obteResultat() {
		return $this->caiguda;
	}
	
	public function modificaIdResident($id) {
		$this->idResident = $id;
	}
}

?>