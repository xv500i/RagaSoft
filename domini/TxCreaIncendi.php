<?php

class TxCreaIncedi implements Transaccio {
	
	private $Incendi;
	private $Usuari;

	public function execu() {
			include 'dades/FabricaControladorsDades.php';
			$ContDades = new FabricaControladorsDades();
			$ContDades->getInstance();
			include 'IControladorLlar.php';
			$CtrlLlar = $ContDades->getIControladorLlar();
			$Llar = $CtrlLlar->obte($Usuari);
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