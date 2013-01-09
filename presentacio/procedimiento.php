<?php 

echo "holaoeu";
echo exec("php " . __DIR__ . "enviasmsalos2minutos.php &", $r);
//include ("enviasmsalos2minutos.php");
echo "<br>" . var_dump($r);
//echo exec("whoami");
echo "<br>terminado";
?>