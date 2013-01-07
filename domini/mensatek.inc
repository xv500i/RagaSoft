<?php
////////////////////////////////////////////////////
// CONEXI�N A MENSATEK POR HTTP/S DESDE PHP
// versi�n PHP 
// versi�n API 4.0                       
// �ltima modificaci�n 3 Septiembre 2006
////////////////////////////////////////////////////


// El puerto por defecto es 3377, se usa para evitar influencia de proxies 
// si no puede utilizar el 3377 por problemas de firewall utilice el puerto 80

/////////////////////////////////////////////////////////////////
// Definiciones necesarias
/////////////////////////////////////////////////////////////////
/* define('G_PUERTO',80); Si tiene un firewall que no deja comunicaciones en el 3377, puede utilizar el puerto 80 */
define('G_PUERTO',3377);

/////////////////////////////////////////////////////////////////
// Si desea comunicaciones seguras SSL debe tener activada la extensi�n SSL
// y quitar los comentarios de las dos siguientes sentencias
/////////////////////////////////////////////////////////////////
/* define('G_PUERTO',443);  Si tiene un firewall que no deja comunicaciones en el 3378, puede utilizar el puerto 443*/
// define('G_PUERTO',3378);
// define('G_DIR','ssl://atgroup1.securesites.net');

 
class cMensatek
{
    var $_correo;
    var $_pass;
    var $Res=array();
    var $Creditos=0;
    var $Resultado=0;
    var $idMensaje=0;
    
    // Constructor
    function cMensatek($correo,$pass)
    {
        $this->_correo=$correo;
        $this->_pass=$pass;
    }
    

    //////////////////////////////////////////////////////
    // OBTIENE EL N�MERO DE CR�DITOS RESTANTES DEL USUARIO
    // DEVUELVE:
    //  Float en $this->Creditos correspondiente al n�mero de cr�ditos en la cuenta.
    ///////////////////////////////////////////////////////

    function creditos()
    {
       $res=$this->_conecta("","/v4/creditos.php","Cred");
       $this->Creditos=$res["Cred"];
       return $this->Creditos;
    }

    //////////////////////////////////////////////////////
    // ENV�A MENSAJES A M�VILES
    // - Valores: Array con todas o alguna de las siguientes variables
    //      Destinatarios: M�vil/M�viles al/a los que se env�a el mensaje, de la forma PrefijoTelefono (Ej:346000000 � para varios destinatarios
    //           346000000;3519760000;443450000) separados por punto y coma ';'
    //      Mensaje: Mensaje que se env�a
    //      Remitente: (Por defecto "") Es el tel�fono, nombre de la empresa o persona que env�a. S�lo v�lido si se env�a mensaje profesional
    //            Si se deja en blanco y se selecciona mensaje profesional, se enviar� desde el tel�fono m�vil registrado por el usuario qu env�a en Mensatek
    //            ATENCI�N: Si es alfanum�rico el M�ximo es de 11 caracteres.
    //      Fecha: Fecha en la que queda progrmado el env�o, el mensaje se enviar� en esa fecha. Por defecto "" que significa enviar ahora. Formato: A�o-Mes-dia hora:minuto
    //          La referencia horaria es GMT+1 (Zona horaria de Espa�a)
    //      Flash: 0=No, 1=S�
    //      Report: 0=No, 1=S�  (1=se env�a report de entrega al correo electr�nico)
    //      Descuento: 0=No, 1=S� 
    //      EmailReport: Correo electr�nico que recibir� el report. Si no se utiliza y se ha seleccionado Report=1, se enviar� al correo registrado como usuario en MENSATEK.(ATENCI�N: Debe ser un correo v�lido). 
    //                   ::Atenci�n::Si desea que se env�e un correo de report personalizado con su nombre de dominio contacte con el Departamento de Soporte
    //      Descuento: Se har� un 10% de descuento (en cr�ditos) si incluye en el mensaje [MENSATEK.com]
    // DEVUELVE: Un array
    //  Res: Int
    //      >0 correspondiente al n�mero de mensajes enviados.
    //      -1 Error de autenticaci�n
    //      -2 no hay cr�ditos suficientes.
    //      -3 Error en los datos de la llamada
    //  Msgid: Int
    //      identificador del mensaje enviado para utilizar en el report
    //  Cred: Float
    //      n�mero de cr�ditos que le restan.
    ///////////////////////////////////////////////////////

    function enviar($Valores)
    {
       $string="";
       foreach ($Valores as $var=>$valor) $string.="&".$var."=".urlencode($valor);
       $res=$this->_conecta($string,"/v4/enviar.php","Res;Msgid;Cred");
       $this->Creditos=$res["Cred"];
       $this->idMensaje=$res["Msgid"];
       return $res;
    }
    
    //////////////////////////////////////////////////////
    // REPORT DE ENV�O
    // MsgId: Identificador de mensaje devuelto por la funci�n de env�o.
    // DEVUELVE:
    //  - Entero con el N�mero de reports
    //  - Carga Array en $this->Res con n valores (tantos como tel�fonos de destino) del tipo 
    //         $this->Res[n]["Fecha"] Fecha/Hora de env�o
    //         $this->Res[n]["Movil"] M�vil destino
    //         $this->Res[n]["Tiempo"] Tiempo (en segundos) que tard� en entregarse el mensaje al m�vil (normalmente entre 2 s 20 segundos si el m�vil est� encendido).
    //         $this->Res[n]["Resultado"] String con el resultado del env�o (entregado, m�vil err�neo, etc...). Se compone de:
    //          1.- Resultado String(Mensaje entregado, esperando entrega, etc�)
    //          2.- Link a imagen que var�a en funci�n del resultado, sirve para utilizarlas como ayuda r�pida al cliente (ver ejemplo en reports en Mensatek).
    //          El formato es: <img src="pix/bverde.gif" hspace="2" border="0">
    //              Posibles colores:
    //              bverde.gif (entregado),
    //              broja.gif (cualquier error),
    //              bnaranja.gif (entregado a red),
    //              bazul.gif (programado o esperando entrega).
    ///////////////////////////////////////////////////////

    function report($MsgId)
    {
        $res=$this->_conecta("&idM=".urlencode($MsgId),"/v4/report.php","");
        $n=0;
        $informes=explode("<Informe>",$res[0]);
        if (isset($informes[0])) foreach ($informes as $informe)
        {
           $resultado=explode("+",$informe);
           if (isset($resultado[2]))
           {
               $this->Res[$n]["Fecha"]=$resultado[0];
               $this->Res[$n]["Movil"]=$resultado[1];
               $this->Res[$n]["Tiempo"]=$resultado[2];
               $this->Res[$n]["Resultado"]=str_replace("</Informe>","",$resultado[3]);
               $n++;
           }  
        }

       return $n;
    }
    
    //////////////////////////////////////////////////////
    // SUBVENCIONAR CR�DITOS A OTRA CUENTA DE USUARIO
    // CorreoDestino: Correo del usuario destino de los cr�ditos.
    // Creditos: N�mero de cr�dtos a a�adir al usuario  
    // DEVUELVE:
    //  - Si >0 Entero con el N�mero de cr�ditos efectivamente a�adidos al usuario o error
    //  - Si <0  
    //   -1 Errror de usuario
    //   -2 No hay suficientes cr�ditos
    //   -3 Correo de destino no existe
    //   -4 Cr�ditos <0
    ///////////////////////////////////////////////////////

    function subvencionar($CorreoDestino,$Creditos)       
    {
        $res=$this->_conecta("&CorreoDest=".urlencode($CorreoDestino)."&Creditos=".urlencode($Creditos),"/v4/subvencionar.php","Res");
        
        return $res["Res"];
    }
    

    // Funciones internas
    function _conecta($args,$dir,$regs)
    {
        $args="Correo=".urlencode($this->_correo)."&Passwd=".urlencode($this->_pass).$args;
        if (function_exists("curl_init"))
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,"http://api.mensatek.com".$dir."?".$args);
            curl_setopt($ch, CURLOPT_VERBOSE, 1);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
            curl_setopt($ch,CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
            curl_setopt($ch, CURLOPT_PORT, G_PUERTO); 

            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);

            curl_setopt($ch, CURLOPT_TIMEOUT, 50); 

            $sub=curl_exec ($ch);
           
            curl_close ($ch);
            $return=$this->_respuesta($sub,$regs);
            
        }
        else
        {
            $fp = @fsockopen ("api.mensatek.com", G_PUERTO, $errno, $errstr, 30);
            if (!$fp) echo "Su sistema no permite trabajar con sockets, active la funcionalidad de sockets en PHP para utilizar la librer�a";
            else
            {
               $string="GET ".$dir."?".$args;
               fputs($fp, $string."  HTTP/1.1\r\n");
               fputs($fp, "Host: mensatek.com\r\n");
               fputs($fp, "Connection: close\r\n\r\n");
               $sub="";
               while (!feof($fp)) $sub.=fgets($fp, 128);
                                   
               fclose($fp);
               $return=$this->_respuesta($sub,$regs);
               
            }
        } 
        return $return;
    }
    
    function _respuesta($sub,$regs)
    {        
        $return=@array();
        if ($regs=="") $return[0]=$sub;
        else    
        {
            $reg=explode(";",$regs);
            foreach ($reg as $r) if ($r!=""&&($sub=strstr($sub,$r.":"))!==false)
            {
                if (($pos=strpos($sub,"\r\n"))!==false) $return[$r]=substr($sub,strlen($r)+1,$pos-strlen($r));
                else $return[$r]=substr($sub,strlen($r)+1);
            }
        }
        return $return;
        
    }
}
?>