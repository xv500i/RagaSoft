<?php

	
	include_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "domini" . DIRECTORY_SEPARATOR . "TxCreaCaiguda.php");
	include_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "domini" . DIRECTORY_SEPARATOR . "TxCreaIncendi.php");
	include_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "domini" . DIRECTORY_SEPARATOR . "TxCreaTardanca.php");
	include_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "domini" . DIRECTORY_SEPARATOR . "TxNotifica.php");
	include_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "domini" . DIRECTORY_SEPARATOR . "TxObteTotsUsuaris.php");
	include_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "domini" . DIRECTORY_SEPARATOR . "TxObteTotsIdRfid.php");
		
	// El parametro $tipus es el tipo de notificación que se generará,
	// Los demás parámetros que se necesitan para generar una notificación se harán desde dominio,
	// Como por ejemplo calcular la hora en que se produce, etc...
	$success = true;
	$tipus = "tardanca";
	switch ($tipus) {
		case "incendi":
			$tx = new TxCreaIncedi();
			$tu = new TxObteTotsUsuaris();
			$tu->execu();
			$usuaris = $tu->obteResultat();
			$usuariAleatori = $usuaris[array_rand($usuaris)];
			$tx->modificaUsuari($usuariAleatori);
			$tx->execu();
			$e = $tx->obteResultat();
			break;
		case "tardanca":
			$tx = new TxCreaTardanca();
			$tu = new TxObteTotsIdRfid();
			$tu->execu();
			$ids = $tu->obteResultat();
			$idRfidAleatori = $ids[array_rand($ids)];
			$tx->modificaIdResident($idRfidAleatori);
			$tx->execu();
			$e = $tx->obteResultat();
			break;
		case "caiguda":
			$tx = new TxCreaCaiguda();
			$tu = new TxObteTotsIdRfid();
			$tu->execu();
			$ids = $tu->obteResultat();
			$idRfidAleatori = $ids[array_rand($ids)];
			$tx->modificaIdResident($idRfidAleatori);
			$tx->execu();
			$e = $tx->obteResultat();
			break;
	}
	var_dump($e);
	$tn = new TxNotifica();
	$tn->modificaEmergencia($e);
	$tn->execu();
	var_dump($tn->obteResultat());
	
	
?>