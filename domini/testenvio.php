<?
require_once("mensatek.inc");
// Crear instancia Clase
$Mensatek=new cMensatek("su correo registrado en MENSATEK.COM","Su contraseña");
$variables=array(
"Remitente"=>"Remitente",  //Remitente que aparece, puede ser número de móvil o texto (hasta 11 caracteres)
"Destinatarios"=>"34600000000", // Destinatarios del mensaje, si es más de 1 sepárelos por punto y coma
"Mensaje"=>"Su mensaje de prueba.", //Mensaje, si se envían más de 160 caracteres se enviará en varios mensajes
"Flash"=>0, // Formato Flash 
"Report"=>1,  //Report de entrega al correo electrónico por defecto
"Descuento"=>0 // Si utiliza descuento o no
);


// Ejemplo de envío
$res=$Mensatek->enviar($variables);
echo "<br>Se enviaron ".$res["Res"]." mensajes y le restan ".$res["Cred"]." cr&eacute;ditos";

// Ejemplo de obtendión directa de créditos restantes en su cuenta
echo "<br>Le restan ".$Mensatek->creditos()." cr&eacute;ditos";


// Ejemplo de obtención de reports de envío
/*
echo "<br>N&uacute;mero de reports en el mensaje:".$Mensatek->report($res["Msgid"]);
foreach ($Mensatek->Res as $res) echo "<br>Mensaje enviado en ".$res["Fecha"]." al tel&eacute;fono ".$res["Movil"]." lleg&oacute; en ".$res["Tiempo"]." segundos";
*/


?>