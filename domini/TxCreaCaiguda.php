<?php

class TxCreaCaiguda implements Transaccio {
	
	private $idResident;
	private $caiguda;

	public function execu() {
			include 'dades/FabricaControladorsDades.php';
			$ContDades = new FabricaControladorsDades();
			$ContDades->getInstance();
			include 'IControladorResident.php';
			$CtrlResident = $ContDades->getIControladorResident();
			$resident = $CtrlResident->obte($idResident);
			if(is_null($Resident)) {
				throw new Exception ("residentNoExisteix");
			} else {
				include 'IControladorEmergencia.php';
				$CtrlEmergencia = $ContDades->getIControladorEmergencia();
				$c = $CtrlEmergencia->creaCaiguda($resident);
				$caiguda = $c;				
			}		
	}
}

?>