<?php 

echo "holaoeu";
//echo exec("php " . __DIR__ . DIRECTORY_SEPARATOR."enviasmsalos2minutos.php &", $r);
//include ("enviasmsalos2minutos.php");
//echo "<br>" . var_dump($r);
//echo exec("whoami");
$r = new HttpRequest('http://kelbert.es/RagaSoft/presentacio/enviasmsalos2minutos.php', HttpRequest::METH_GET);
echo "blaa";
try {
    echo "<br>" . $r->send();
} catch (HttpException $ex) {
    echo $ex;
}

echo "<br>terminado";
?>