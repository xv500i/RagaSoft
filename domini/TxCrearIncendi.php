<?php

class TxCrearIncedi implements Transaccio {
	
	private $Incendi;

	public function execu($usuari) {
			include 'dades/FabricaControladorsDades.php';
			$ContDades = new FabricaControladorsDades();
			$ContDades->getInstance();
			include 'IControladorLlar.php';
			$CtrlLlar = $ContDades->getIControladorLlar();
			$Llar = $CtrlLlar->obte($usuari);
			if(is_null($Llar)) {
				throw new Exception ("llarNoExisteix");
			} else {
				include 'IControladorEmergencia.php';
				$CtrlEmergencia = $ContDades->getIControladorEmergencia();
				$i = $CtrlEmergencia->creaIncendi($Llar);
				$Incendi = $i;				
			}
		
			
			
	}
}

?>