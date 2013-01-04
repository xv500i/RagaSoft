<?php
interface IAdaptadorServeiEmergencies
{
    public function enviaSMS($telefon, $text);
}
?>