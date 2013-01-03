<?php

class ServeiSMS {
	function send($telf, $text) {
		$url = "http://api.clickatell.com/http/sendmsg?user=bandamkid&password=cXKfGLLRHQLYfb&api_id=3406721&to=34$telf&text=$text";
		$payload = file_get_contents($url);
	}
}

?>