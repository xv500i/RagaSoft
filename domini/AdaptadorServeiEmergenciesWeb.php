<?php

include_once ("IAdaptadorServeiEmergencies.php");
include_once ("ServeiSMS.php");



class AdaptadorServeiEmergenciesWeb implements IAdaptadorServeiEmergencies {
	
	public function enviaSMS($telefon, $text) {
		$se = new ServeiSMS();
		$se->send($telf, $text);
	}
}

?>