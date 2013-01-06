<!DOCTYPE html>
<html lang="es-ES">
	<?php require_once 'cabecera.php'; ?>
	<body>
		<div class="superCaja" id="idSuperCaja">
			<div class="divCaja" id="idCaja">
				<div class="divFrnjaSuperior">
					<div class="divLogo"><img alt="RagaSoft.com" src="img/logo/logoTexto.png"></img></div>
					<!--<div class="divIndice">Tardança</div>-->
					<?php include_once 'menuSuperior.php'; ?>
				</div>
				<div class="divCentral">
					<div class="divContenido" id="idContenidoTardanza">
						<div class="divInfoSimulacion">
							<img title="info Incendi" src="img/clocks64.png" />
							<p>Aquest event simula la tardança d'un hipotètic usuari del sistema.<br />
								Una tardança es produeix cuan s'ha programat una hora en la que l'usuari ja es trobarà a la seva vivenda, però arriba aquesta hora i el sistema (mitjançant la tecnología RFID) no detecta la presència de l'usuari a la seva llar.</p>
						</div>
						<p class="pPasoSimulacion" id="idPaso1" >Tardança Detectada</p>
						<p class="pPasoSimulacion" id="idPaso2" >Sistema Activat</p>
						<p class="pPasoSimulacion" id="idPaso3" >Notificació Generada</p>
						<p class="pPasoSimulacion" id="idPaso4" >Simulació Finalitzada</p>
						<div class="divBotonesSimulacion">
							<a class="linkInicio" href="index.php">Inici</a>
							<a class="linkSimular" id="idLinkSimular" name="tardanza" href="javascript:simular();">Simular</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
