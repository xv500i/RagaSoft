<!DOCTYPE html>
<html lang="es-ES">
	<?php require_once 'cabecera.php';?>
	<body onload="javascript:peticion('cargarNotificaciones');">
		<div class="superCaja" id="idSuperCaja">
			<div class="divCaja" id="idCaja">
				<div class="divFrnjaSuperior">
					<div class="divLogo"><img alt="RagaSoft.com" src="img/logo/logoTexto.png"></img></div>
					<!--<div class="divIndice">Notificacions</div>-->
					<?php include_once 'menuSuperior.php'; ?>
				</div>
				<div class="divCentral">
					<div class="divContenido" id="idContenidoNotificaciones">
					<!--<?php 
						// Not ( tipus, idRfid, nomResidentAfectat, moment, periodeConfirmacio, confirmada )
						$n = array( array("incendi", 123 , 'Leo Messi', 				'3-1-2013 16:56', '10', false, 1 ),
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
						?>
						<table class="tablaNotificaciones" border="0" cellspacing="0">
							<tr>
								<th>Tipus</th>
								<th>idRFID</th>
								<th>Nom</th>
								<th>Data</th>
								<th>Periode (minuts)</th>
								<th id="thConfirmar">Confirmar</th>
							</tr>
							<?php for ($i = 0; $i < sizeof($n); $i++){?>
							<tr <?php if( $i%2 == 0 ) echo "class=\"trNotifPar\""; else echo "class=\"trNotifImpar\""; ?> >
								<td class="tdImgTipo"><?php 
									if( $n[$i][0] == 'incendi' ) 		echo "<img title=\"Incendi\" src=\"img/ico/fire20.png\"></img>";
									else if( $n[$i][0] == 'caiguda' ) 	echo "<img title=\"Caiguda\" src=\"img/ico/caida20.png\"></img>";
									else if( $n[$i][0] == 'tardanca' )	echo "<img title=\"Tarança\" src=\"img/ico/clock20.png\"></img>"; 
								?></td>
								<td class="tdIdRFID"><?php echo $n[$i][1]; ?></td>
								<td><?php echo $n[$i][2]; ?></td>
								<td><?php echo $n[$i][3]; ?></td>
								<td class="tdPeriode"><?php echo $n[$i][4]; ?></td>
								<td class="tdConfirmar" id="idTdConfirmar<?php echo $n[$i][6]; ?>">
									<?php 
										if ($n[$i][5]) 	echo "<img title=\"Confirmada\" src=\"img/ico/tick.png\"></img>"; 
										else 			echo "<a class=\"linkConfirmar\" href=\"javascript:peticion('confirmar',".$n[$i][6].");\">Confirmar</a>";	
									?></td>
							</tr>
							<?php 
							} 
							?>
						</table>-->
						<img class="imgLoadNotificaciones" alt="Cargando notificaciones" title"Cargando Notificaciones" src="img/loading.gif"/>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
