<?php

abstract class Emergencia {
	
	protected $moment;

	abstract public function obteCuidador();
	abstract public function obteMissatge();
	abstract public function obtePeriodeDeConfirmacio();
}

?>