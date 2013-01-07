<?php
   	
//@session_start();


if( !isset( $_POST['peticion'] ) )	echo '<p>No existe peticion</p>';

else{
	
	switch( $_POST['peticion'] ){
		
		case 'cargarNotificaciones':	cargarNotificaciones(); 	break;
		case 'confirmar': 				confirmar();				break;
		case 'simular': 				simular(); 					break;
		
		default: echo '<p>Peticion no especificada</p>'; 			break;
	}	
}

function cargarNotificaciones(){
	
	$n = controladorDomini_cargaNotificacions();
	
	?>
	<table class="tablaNotificaciones" border="0" cellspacing="0">
		<tr>
			<th>Tipus</th>
			<th>Afectat</th>
			<th>Cuidador</th>
			<th>Data</th>
			<th>Periode</th>
			<th id="thConfirmar">Confirmar</th>
		</tr>
		<?php
		for ($i = 0; $i < sizeof($n); $i++){?>
		<tr <?php if( $i%2 == 0 ) echo "class=\"trNotifPar\""; else echo "class=\"trNotifImpar\""; ?> >
			<td class="tdImgTipo"><?php 
				if( $n[$i][0] == 'incendi' ) 		echo "<img title=\"Incendi\" src=\"img/ico/fire20.png\"></img>";
				else if( $n[$i][0] == 'caiguda' ) 	echo "<img title=\"Caiguda\" src=\"img/ico/caida20.png\"></img>";
				else if( $n[$i][0] == 'tardanca' )	echo "<img title=\"Tarança\" src=\"img/ico/clock20.png\"></img>"; 
			?></td>
			<td class="tdAfectat"><?php echo $n[$i][1]; ?></td>
			<td><?php echo $n[$i][2]; ?></td>
			<td><?php echo $n[$i][3]; ?></td>
			<td class="tdPeriode"><?php echo $n[$i][4]; ?></td>
			<td class="tdConfirmar" id="idTdConfirmar<?php echo $n[$i][6]?>">
				<?php 
					if ($n[$i][5]) 	echo "<img title=\"Notificación Confirmada\" src=\"img/ico/tick.png\"></img>"; 
					else 			echo "<img title=\"Notificación No Confirmada\" src=\"img/ico/cross.png\"></img>"; //echo "<a class=\"linkConfirmar\" href=\"javascript:peticion('confirmar',".$n[$i][6].");\">Confirmar</a>";	
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
			
			if ( controladorDomini_confirmaNotificacio() ) echo "OK";
			else echo "ERROR";
			
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
			
			if( controladorDomini_CreaNotificacio( $_POST['tipo'] ) ) echo "OK";
			else echo "ERROR";
			
			echo 	']]>';
		echo '</resultado>';
	echo '</xml>';	
		
	
}




/**********************************************************************************************************************
 *  Las siguientes funciones deberian "migrarse" al controlador de domini o el patró transacció o algo por el estilo
 **********************************************************************************************************************/
 
function controladorDomini_creaNotificacio( $tipus ){
	include_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "domini" . DIRECTORY_SEPARATOR . "TxCreaCaiguda.php");
	include_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "domini" . DIRECTORY_SEPARATOR . "TxCreaIncendi.php");
	include_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "domini" . DIRECTORY_SEPARATOR . "TxCreaTardanca.php");
	include_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "domini" . DIRECTORY_SEPARATOR . "TxNotifica.php");
			
	// El parametro $tipus es el tipo de notificación que se generará,
	// Los demás parámetros que se necesitan para generar una notificación se harán desde dominio,
	// Como por ejemplo calcular la hora en que se produce, etc...
	$success = true;
	switch ($tipus) {
		case "incendi":
			$tx = new TxCreaIncedi();
			// FIXME: obtenir usuari de la sessio o demanar-lo en un select camp
			//$tx->modificaUsuari();
			break;
		case "tardanca":
			$tx = new TxCreaTardanca();
			// FIXME: obtenir usuari
			break;
		case "caiguda":
			$tx = new TxCreaCaiguda();
			// FIXME: obtenir usuari
			break;
	}
	
	// Esta función debería devolver:
	// TRUE si se ha registrado la notificacion en la BD
	// FALSE si ha habido algún problema
	
	return $success;
}

function controladorDomini_confirmaNotificacio(){
	
	include_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "domini" . DIRECTORY_SEPARATOR . "TxConfirmaNotificacio.php");
	
	//FIXME: necesita el telefono del cuidador y el id de la notificacion
	
	// Esta función debería devolver:
	// TRUE si se ha realizado correctamente el UPDATE de la notificacion y su estado ha pasado a Confirmada
	// FALSE si ha habido algún problema
	
	return true;
}

function controladorDomini_cargaNotificacions(){
	
	// Este método devuelve todas las notificaciones, primero las PENDIENTES y luego las CONFIRMADAS (en este orden)
	// Para eso se podrían hacer dos consultas, diferenciando por el booleano de CONFIRMADA.
	// En el array de ejemplo $nPendents hay algunos valores true, pero deberían ser todos false.
	
	//Alex -> el controlador de presentacion se las tiene que manijar para poner los datos como quiera
	// así que lo haré aquí mismo
	
	// Los campos deben ser: ( tipus, idRfid, nomResidentAfectat, moment, periodeConfirmacio, confirmada, id )
	
	
	$nPendents= array( array("incendi", 123 , 'Leo Messi',			'3-1-2013 16:56', '10', false, 1 ),
				array("caiguda", 123 , 'Cristiano Ronaldo', 		'14-2-2013 16:56', '60', false, 2 ),
				array("tardanca", 123 , 'Pepe', 					'3-3-2013 00:00', '5', false, 3 ),
				array("tardanca", 123 , 'Felipe VII', 				'1-4-2013 16:56', '15', false, 4 ),
				array("incendi", 123 , 'Juan Carlos Rey', 			'3-5-2013 16:56', '20', false, 5 ),
				array("tardanca", 123 , 'Arguiñano', 				'54-10-2013 16:56', '2', true, 6 ),
				array("caiguda", 123 , 'Natxo Raga Llorente', 		'3-12-2013 16:56', '10', true, 7 ),
				array("caiguda", 123 , 'Ruben Ferrero VaYBiene',	'3-11-2013 16:56', '1', false, 8 ),
				array("incendi", 123 , 'Alejando Somero Batallero',	'13-10-2013 16:56', '100', true, 9 ),
				array("incendi", 123 , 'David Ovellato Celeste', 	'4-9-2013 16:56', '20', false, 10 ),
				array("incendi", 123 , 'Comandante MartÁnez', 		'3-8-2013 16:56', '1', false, 11 ),
				array("tardanca", 123 , 'Romerales', 				'37-5-2013 16:56', '120', true, 12 ),
				array("caiguda", 123 , 'PitBull', 					'3-3-2013 16:56', '200', true, 13 ),
				array("tardanca", 123 , 'Pastor Aleman', 			'21-8-2013 16:56', '30', true, 14 ),
				array("incendi", 123 , 'Chiguagua', 				'11-9-2013 16:56', '57', false, 15 ),
				array("caiguda", 123 , 'Pollo de David', 			'3-3-2013 16:56', '61', true, 16 ),
				array("caiguda", 123 , 'Jaimito Tito', 				'33-10-2013 16:56', '10', true, 17 )
			);
	
	$nConfirmades = 
				array( array("incendi", 123 , 'Leo Messi', 				'3-1-2013 16:56', '10', true, 18 ),
				array("caiguda", 123 , 'Cristiano Ronaldo', 		'14-2-2013 16:56', '60', true, 19 ),
				array("tardanca", 123 , 'Pepe', 					'3-3-2013 00:00', '5', true, 20 ),
				array("tardanca", 123 , 'Felipe VII', 				'1-4-2013 16:56', '15', true, 21 ),
				array("incendi", 123 , 'Juan Carlos Rey', 			'3-5-2013 16:56', '20', true, 22 ),
				array("tardanca", 123 , 'Arguiñano', 				'54-10-2013 16:56', '2', true, 23 ),
				array("caiguda", 123 , 'Natxo Raga Llorente', 		'3-12-2013 16:56', '10', true, 24 ),
				array("caiguda", 123 , 'Ruben Ferrero VaYBiene',	'3-11-2013 16:56', '1', true, 25 ),
				array("incendi", 123 , 'Alejando Somero Batallero',	'13-10-2013 16:56', '100', true, 26 ),
				array("incendi", 123 , 'David Ovellato Celeste', 	'4-9-2013 16:56', '20', true, 27 ),
				array("incendi", 123 , 'Comandante MartÁnez', 		'3-8-2013 16:56', '1', true, 28 ),
				array("tardanca", 123 , 'Romerales', 				'37-5-2013 16:56', '120', true, 29 ),
				array("caiguda", 123 , 'PitBull', 					'3-3-2013 16:56', '200', true, 30 ),
				array("tardanca", 123 , 'Pastor Aleman', 			'21-8-2013 16:56', '30', true, 31 ),
				array("incendi", 123 , 'Chiguagua', 				'11-9-2013 16:56', '57', true, 32 ),
				array("caiguda", 123 , 'Pollo de David', 			'3-3-2013 16:56', '61', true, 33 ),
				array("caiguda", 123 , 'Jaimito Tito', 				'33-10-2013 16:56', '10', true, 34 )
			);
	
	return array_merge($nPendents, $nConfirmades);
}







?>