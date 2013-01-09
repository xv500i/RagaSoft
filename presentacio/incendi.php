<!DOCTYPE html>
<html lang="es-ES">
	<?php require_once 'cabecera.php'; ?>
	<body>
		<div class="superCaja" id="idSuperCaja">
			<div class="divCaja" id="idCaja">
				<div class="divFrnjaSuperior">
					<div class="divLogo"><img alt="RagaSoft.com" src="img/logo/logoTexto.png"></img></div>
					<!--<div class="divIndice">Incendi</div>-->
					<?php include_once 'menuSuperior.php'; ?>
				</div>
				<div class="divCentral">
					<div class="divContenido" id="idContenidoIncendio">
						<div class="divInfoSimulacion">
							<img title="info Incendi" src="img/fireHouse.png" />
							<p>Aquest esdeveniment simula un incendi a la vivenda d'un hipotètic usuari.<br />
								Un incendi es detectat pel corresponent detector instal·lat a la vivenda, aquest envía una senyal al sistema, que s'encarrega de gestionar la incidència.
								Els passos que realitzaría el sistema son els indicats a continuació.
							</p>
						</div>
						<p class="pPasoSimulacion" id="idPaso1" >Incendi Detectat</p>
						<p class="pPasoSimulacion" id="idPaso2" >Sistema Activat</p>
						<p class="pPasoSimulacion" id="idPaso3" >Notificació Generada</p>
						<p class="pPasoSimulacion" id="idPaso4" >Simulació Finalitzada</p>
						<div class="divBotonesSimulacion">
							<a class="linkInicio" href="index.php">Inici</a>
							<a class="linkSimular" id="idLinkSimular" name="incendi" href="javascript:simular();">Simular</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
