<?php
interface IControladorUsuari
{
    public function obte($id);
	public function existeix($id);
	public function tots();
}
?>