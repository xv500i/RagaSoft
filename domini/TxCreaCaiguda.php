<?php

include_once ("Transaccio.php");
include_once (__DIR__ . "\\..\\dades\\FabricaControladorsDades.php");
include_once ("IControladorEmergencia.php");
include_once ("IControladorResident.php");

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
}

?>