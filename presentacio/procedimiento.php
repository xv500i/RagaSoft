<?php 

echo "holaoeu";
echo exec("php " . __DIR__ . DIRECTORY_SEPARATOR."enviasmsalos2minutos.php &", $r);
//include ("enviasmsalos2minutos.php");
echo "<br>" . var_dump($r);
//echo exec("whoami");
//$r = new HttpRequest('http://localhost/rfid/presentacio/enviasmsalos2minutos.php', HttpRequest);
//echo "blaa";
//try {
//    echo "<br>" . $r->send();
//} catch (HttpException $ex) {
//    echo $ex;
//}

echo "<br>terminado";
?>