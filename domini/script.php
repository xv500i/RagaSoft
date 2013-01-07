<?php
	sleep (3000);
	$url = "http://api.clickatell.com/http/sendmsg?user=bandamkid&password=cXKfGLLRHQLYfb&api_id=3406721&to=34652625084&text=Hola";
		$payload = file_get_contents($url);
?>