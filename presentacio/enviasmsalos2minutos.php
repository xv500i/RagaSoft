<?php
require_once(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "domini" . DIRECTORY_SEPARATOR . "mensatek.inc");
// Crear instancia Clase
$Mensatek=new cMensatek("xv500i@gmail.com","ragasoft");

$variables=array(
"Remitente"=>"Ragasoft",  //Remitente que aparece, puede ser n�mero de m�vil o texto (hasta 11 caracteres)
"Destinatarios"=>"34696396699", // Destinatarios del mensaje, si es m�s de 1 sep�relos por punto y coma
"Mensaje"=>"[MISSATGE FALS]S'ha detectat un incendi a CasaPuig(Carrer Gerenal Tapioca n60 2n 2a)", //Mensaje, si se env�an m�s de 160 caracteres se enviar� en varios mensajes
"Flash"=>0, // Formato Flash 
"Report"=>0,  //Report de entrega al correo electr�nico por defecto
"Descuento"=>0 // Si utiliza descuento o no
);

echo "kdaskdsa";
// Ejemplo de env�o
sleep(10);
$res=$Mensatek->enviar($variables);

//echo "<br>Se enviaron ".$res["Res"]." mensajes y le restan ".$res["Cred"]." cr&eacute;ditos";

// Ejemplo de obtendi�n directa de cr�ditos restantes en su cuenta
//echo "<br>Le restan ".$Mensatek->creditos()." cr&eacute;ditos";
//var_dump($Mensatek->creditos());

// Ejemplo de obtenci�n de reports de env�o
/*
echo "<br>N&uacute;mero de reports en el mensaje:".$Mensatek->report($res["Msgid"]);
foreach ($Mensatek->Res as $res) echo "<br>Mensaje enviado en ".$res["Fecha"]." al tel&eacute;fono ".$res["Movil"]." lleg&oacute; en ".$res["Tiempo"]." segundos";
*/


?>