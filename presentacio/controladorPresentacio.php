<?php
   	
//@session_start();
require_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "domini" . DIRECTORY_SEPARATOR . "TxCreaCaiguda.php");
require_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "domini" . DIRECTORY_SEPARATOR . "TxCreaIncendi.php");
require_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "domini" . DIRECTORY_SEPARATOR . "TxCreaTardanca.php");
require_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "domini" . DIRECTORY_SEPARATOR . "TxNotifica.php");
require_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "domini" . DIRECTORY_SEPARATOR . "TxObteTotsUsuaris.php");
require_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "domini" . DIRECTORY_SEPARATOR . "TxObteTotsIdRfid.php");
require_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "domini" . DIRECTORY_SEPARATOR . "TxModificaTelefonTest.php");	


if( !isset( $_POST['peticion'] ) )	echo '<p>No existeix petició</p>';

else{
	
	switch( $_POST['peticion'] ){
		
		case 'cargarNotificaciones':	cargarNotificaciones(); 	break;
		case 'confirmar': 				confirmar();				break;
		case 'simular': 				simular(); 					break;
		
		default: echo '<p>Petició no especificada</p>'; 			break;
	}	
}

function cargarNotificaciones(){
	
	$n = controladorDomini_cargaNotificacions();
	
	?>
	<table class="tablaNotificaciones" border="0" cellspacing="0">
		<tr>
			<th>Tipus</th>
			<th>ID</th>
			<th>Afectat</th>
			<th>Cuidador</th>
			<th>Data</th>
			<th>Periode (minuts)</th>
			<th id="thConfirmar">Confirmada</th>
		</tr>
		<?php
		for ($i = 0; $i < sizeof($n); $i++){?>
		<tr <?php if( $i%2 == 0 ) echo "class=\"trNotifPar\""; else echo "class=\"trNotifImpar\""; ?> >
			<td class="tdImgTipo"><?php 
				if( $n[$i][0] == 'incendi' ) 		echo "<img title=\"Incendi\" src=\"img/ico/fire20.png\"></img>";
				else if( $n[$i][0] == 'caiguda' ) 	echo "<img title=\"Caiguda\" src=\"img/ico/caida20.png\"></img>";
				else if( $n[$i][0] == 'tardanca' )	echo "<img title=\"Tarança\" src=\"img/ico/clock20.png\"></img>"; 
			?></td>
			<td><?php echo $n[$i][6]; ?></td>
			<td class="tdAfectat"><?php echo $n[$i][1]; ?></td>
			<td><?php echo $n[$i][2]; ?></td>
			<td><?php echo $n[$i][3]; ?></td>
			<td class="tdPeriode"><?php echo $n[$i][4]; ?></td>
			<td class="tdConfirmar" id="idTdConfirmar<?php echo $n[$i][6]?>">
				<?php 
					if ($n[$i][5]) 			echo "<img title=\"Notificación Confirmada\" src=\"img/ico/tick.png\" />"; 
					else if( $n[$i][7] )  	echo "<a class=\"linkConfirmar\" href=\"javascript:void(0)\" onclick=\"confirmar(".$n[$i][6].");\">Confirmar</a>";
					else 					echo "<img title=\"Periode de confirmació vençut. S'avisà al servei d'emergències.\" src=\"img/ico/alert.png\" />"; 				
				?></td>
		</tr>
		<?php 
		}
		?>
	</table>
<?php
}

function confirmar(){
	
	// Devolvemos la respuesta en XML
	header ("Content-type: text/xml");
	echo '<?xml version="1.0" encoding="UTF-8"?>'."\n";
	echo '<xml>';

		echo '<resultado>';
			echo '<![CDATA[';
				echo controladorDomini_confirmaNotificacio($_POST['idNotificacion'], $_POST['telefono']);
			echo 	']]>';
		echo '</resultado>';
	echo '</xml>';
}

function simular(){
		
	header ("Content-type: text/xml");
	echo '<?xml version="1.0" encoding="UTF-8"?>'."\n";
	echo '<xml>';

		echo '<resultado>';
			echo '<![CDATA[';
			
			if( controladorDomini_CreaNotificacio( $_POST['tipo'], $_POST['telefono'] ) ) echo "OK";
			else echo "ERROR";
			
			echo 	']]>';
		echo '</resultado>';
	echo '</xml>';	
		
	
}




/**********************************************************************************************************************
 *  Las siguientes funciones deberian "migrarse" al controlador de domini o el patró transacció o algo por el estilo
 **********************************************************************************************************************/

		 
  
function controladorDomini_creaNotificacio( $tipus, $telefon ){
	
	//FIXED: ja esta dispoible el telefon com a parametre
	$trans = new TxModificaTelefonTest();
	$trans->modificaTelefon($telefon);
	$trans->execu();
	// El parametro $tipus es el tipo de notificación que se generará,
	// Los demás parámetros que se necesitan para generar una notificación se harán desde dominio,
	// Como por ejemplo calcular la hora en que se produce, etc...
	$success = true;
	
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
	if ($e == NULL) return false;
	$tn = new TxNotifica();
	$tn->modificaEmergencia($e);
	$tn->execu();
	// Esta función debería devolver:
	// TRUE si se ha registrado la notificacion en la BD
	// FALSE si ha habido algún problema
	
	return $success;
}

function controladorDomini_confirmaNotificacio($id, $telefon){
	
	include_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "domini" . DIRECTORY_SEPARATOR . "TxConfirmaNotificacio.php");
	
	//FIXME: necesita el telefono del cuidador y el id de la notificacion
	//OVELLATOR_FIXME:
	//FIXME: Esta función debería devolver:
	//FIXME: OK si se ha realizado correctamente el UPDATE de la notificacion y su estado ha pasado a Confirmada
	//FIXME: ERROR si ha habido algún problema
	//FIXME: ERROR_TELEFONO si no coincide el telefono introducido con el que se registro en el momento de generar la notificacion
	
	include_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "domini" . DIRECTORY_SEPARATOR . "TxConfirmaNotificacio.php");

	$tx = new TxConfirmaNotificacio();
	$tx->modificaIdNotificacio($id);
	$tx->modificaTelefon($telefon);
	try {
		$tx->execu();
	}
	catch (Exception $e) {
		if ($e->getMessage() == "No és una notificació teva") {
			return "ERROR_TELEFONO";	
		}
		else {
			return "ERROR";			
		}
	}
	return "OK";
}

function controladorDomini_cargaNotificacions(){
	
	// Este método devuelve todas las notificaciones, primero las PENDIENTES y luego las CONFIRMADAS (en este orden)
	// Para eso se podrían hacer dos consultas, diferenciando por el booleano de CONFIRMADA.
	// En el array de ejemplo $nPendents hay algunos valores true, pero deberían ser todos false.
	
	//Alex -> el controlador de presentacion se las tiene que manijar para poner los datos como quiera
	// así que lo haré aquí mismo
	
	// Los campos deben ser: ( tipus, idRfid, nomResidentAfectat, moment, periodeConfirmacio, confirmada, id, esPotConfirmar )
	
	
	/*$nPendents= array( array("incendi", 123 , 'Leo Messi',			'3-1-2013 16:56', '10', false, 1, false ),
				array("caiguda", 123 , 'Cristiano Ronaldo', 		'14-2-2013 16:56', '60', false, 2, true ),
				array("tardanca", 123 , 'Pepe', 					'3-3-2013 00:00', '5', false, 3, true ),
				array("tardanca", 123 , 'Felipe VII', 				'1-4-2013 16:56', '15', false, 4, false ),
				array("incendi", 123 , 'Juan Carlos Rey', 			'3-5-2013 16:56', '20', false, 5, true ),
				array("tardanca", 123 , 'Arguiñano', 				'54-10-2013 16:56', '2', true, 6, false ),
				array("caiguda", 123 , 'Natxo Raga Llorente', 		'3-12-2013 16:56', '10', true, 7, false ),
				array("caiguda", 123 , 'Ruben Ferrero VaYBiene',	'3-11-2013 16:56', '1', false, 8, true ),
				array("incendi", 123 , 'Alejando Somero Batallero',	'13-10-2013 16:56', '100', true, 9, false ),
				array("incendi", 123 , 'David Ovellato Celeste', 	'4-9-2013 16:56', '20', false, 10, true ),
				array("incendi", 123 , 'Comandante MartÁnez', 		'3-8-2013 16:56', '1', false, 11, true ),
				array("tardanca", 123 , 'Romerales', 				'37-5-2013 16:56', '120', true, 12, false ),
				array("caiguda", 123 , 'PitBull', 					'3-3-2013 16:56', '200', true, 13, false ),
				array("tardanca", 123 , 'Pastor Aleman', 			'21-8-2013 16:56', '30', true, 14, false ),
				array("incendi", 123 , 'Chiguagua', 				'11-9-2013 16:56', '57', false, 15, true ),
				array("caiguda", 123 , 'Pollo de David', 			'3-3-2013 16:56', '61', true, 16, false ),
				array("caiguda", 123 , 'Jaimito Tito', 				'33-10-2013 16:56', '10', true, 17, false )
			);
	
	$nConfirmades = 
				array( array("incendi", 123 , 'Leo Messi', 				'3-1-2013 16:56', '10', true, 18, false ),
				array("caiguda", 123 , 'Cristiano Ronaldo', 		'14-2-2013 16:56', '60', true, 19, false ),
				array("tardanca", 123 , 'Pepe', 					'3-3-2013 00:00', '5', true, 20, false ),
				array("tardanca", 123 , 'Felipe VII', 				'1-4-2013 16:56', '15', true, 21, false ),
				array("incendi", 123 , 'Juan Carlos Rey', 			'3-5-2013 16:56', '20', true, 22, false ),
				array("tardanca", 123 , 'Arguiñano', 				'54-10-2013 16:56', '2', true, 23, false ),
				array("caiguda", 123 , 'Natxo Raga Llorente', 		'3-12-2013 16:56', '10', true, 24, false ),
				array("caiguda", 123 , 'Ruben Ferrero VaYBiene',	'3-11-2013 16:56', '1', true, 25, false ),
				array("incendi", 123 , 'Alejando Somero Batallero',	'13-10-2013 16:56', '100', true, 26, false ),
				array("incendi", 123 , 'David Ovellato Celeste', 	'4-9-2013 16:56', '20', true, 27, false ),
				array("incendi", 123 , 'Comandante MartÁnez', 		'3-8-2013 16:56', '1', true, 28, false ),
				array("tardanca", 123 , 'Romerales', 				'37-5-2013 16:56', '120', true, 29, false ),
				array("caiguda", 123 , 'PitBull', 					'3-3-2013 16:56', '200', true, 30, false ),
				array("tardanca", 123 , 'Pastor Aleman', 			'21-8-2013 16:56', '30', true, 31, false ),
				array("incendi", 123 , 'Chiguagua', 				'11-9-2013 16:56', '57', true, 32, false ),
				array("caiguda", 123 , 'Pollo de David', 			'3-3-2013 16:56', '61', true, 33, false),
				array("caiguda", 123 , 'Jaimito Tito', 				'33-10-2013 16:56', '10', true, 34, false )
			);
	
	return array_merge($nPendents, $nConfirmades);*/
	include_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "domini" . DIRECTORY_SEPARATOR . "TxCarregaNotis.php");

	$tx = new TxCarregaNotis();
	$tx->execu();
	$res = $tx->obteResultat();
	
	return $res;
}







?>