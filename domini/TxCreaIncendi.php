<?php

include_once ("Transaccio.php");
include_once (__DIR__ . "\\..\\dades\\FabricaControladorsDades.php");
include_once ("IControladorEmergencia.php");
include_once ("IControladorLlar.php");

class TxCreaIncedi implements Transaccio {
	
	private $Incendi;
	private $Usuari;

	public function execu() {
			$ContDades = new FabricaControladorsDades();
			$ContDades->getInstance();
			$CtrlLlar = $ContDades->getIControladorLlar();
			$Llar = $CtrlLlar->obte($Usuari);
			if(is_null($Llar)) {
				throw new Exception ("llarNoExisteix");
			} else {
				$CtrlEmergencia = $ContDades->getIControladorEmergencia();
				$i = $CtrlEmergencia->creaIncendi($Llar);
				$Incendi = $i;				
			}
		
			
			
	}
}

?>