<?php
interface IControladorContactes
{
    public function obte($telefon);
	public function existeix($telefon);
	public function tots();
}
?>