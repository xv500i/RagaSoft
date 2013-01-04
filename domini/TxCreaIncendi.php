<?php

include_once ("Transaccio.php");
include_once (__DIR__ . "\\..\\dades\\FabricaControladorsDades.php");
include_once ("IControladorEmergencia.php");
include_once ("IControladorLlar.php");
include_once ("Llar.php");
include_once ("Incendi.php");


class TxCreaIncedi implements Transaccio {
	
	private $incendi;
	private $usuari;

	public function execu() {
			$ContDades = FabricaControladorsDades::getInstance();
			$CtrlLlar = $ContDades->getIControladorLlar();
			//$this->usuari = "PisBarriAntic"; He comentat això perquè l'usuari ens l'han de donar, no el podem assignar nosaltres
			$Llar = $CtrlLlar->obte($this->usuari);
			if(is_null($Llar)) {
				throw new Exception ("llarNoExisteix");
			} else {
				$CtrlEmergencia = $ContDades->getIControladorEmergencia();
				$i = $CtrlEmergencia->creaIncendi($Llar);
				$this->incendi = $i;				
			}
		
			
			
	}
}

?>