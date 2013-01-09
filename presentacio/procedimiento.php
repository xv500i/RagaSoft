<?php 

echo "hola";
echo exec("php enviasmsalos2minutos.php", $r);
//include ("enviasmsalos2minutos.php");
echo "<br>" . var_dump($r);
echo "<br>terminado";
?>