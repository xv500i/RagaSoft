<?php

include_once ("Transaccio.php");
include_once (__DIR__ . "\\..\\dades\\FabricaControladorsDades.php");
include_once ("IControladorEmergencia.php");
include_once ("IControladorResident.php");

class TxCreaTardanca implements Transaccio {
	
	private $idResident;
	private $tardanca;

	public function execu() {
			$ContDades = new FabricaControladorsDades();
			$ContDades->getInstance();
			$CtrlResident = $ContDades->getIControladorResident();
			$resident = $CtrlResident->obte($idResident);
			if(is_null($Resident)) {
				throw new Exception ("residentNoExisteix");
			} else {
				$CtrlEmergencia = $ContDades->getIControladorEmergencia();
				$t = $CtrlEmergencia->creaTardanca($resident);
				$tardanca = $t;				
			}		
	}
}

?>