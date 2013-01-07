<?php

include_once ("IAdaptadorServeiEmergencies.php");
include_once ("ServeiSMS.php");



class AdaptadorServeiEmergenciesWeb implements IAdaptadorServeiEmergencies {
	
	public function enviaSMS($telefon, $text) {
		//FIXME: per si un cas	
		return;
		require_once("mensatek.inc");
		$Mensatek=new cMensatek("xv500i@gmail.com","ragasoft");
		$variables=array(
		"Remitente"=>"Ragasoft",  //Remitente que aparece, puede ser n�mero de m�vil o texto (hasta 11 caracteres)
		"Destinatarios"=>"34" . $telefon, // Destinatarios del mensaje, si es m�s de 1 sep�relos por punto y coma
		"Mensaje"=>$text, //Mensaje, si se env�an m�s de 160 caracteres se enviar� en varios mensajes
		"Flash"=>0, // Formato Flash 
		"Report"=>0,  //Report de entrega al correo electr�nico por defecto
		"Descuento"=>0 // Si utiliza descuento o no
		);
		$res=$Mensatek->enviar($variables);
	}
}

?>