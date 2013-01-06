<!DOCTYPE html>
<html lang="es-ES">
	<?php require_once 'cabecera.php'; ?>
	<body>
		<div class="superCaja" id="idSuperCaja">
			<div class="divCaja" id="idCaja">
				<div class="divFrnjaSuperior">
					<div class="divLogo"><img alt="RagaSoft.com" src="img/logo/logoTexto.png"></img></div>
					<!--<div class="divIndice">Caiguda</div>-->
					<?php include_once 'menuSuperior.php'; ?>
				</div>
				<div class="divCentral">
					<div class="divContenido" id="idContenidoCaida">
						<div class="divInfoSimulacion">
							<img title="info Incendi" src="img/caidas64.png" />
							<p>Aquest event simula la caiguda d'un hipotètic usuari.<br />
								Una caiguda es detecta mitjançant un sensor al dispositiu que porta penjat l'usuari. Aquest sensor s'activa cuan es detecta un moviment brusc, i aixó fa que s'enviï una senyal al sistema, i aquet realizarà els corresponents passos.</p>
						</div>
						<p class="pPasoSimulacion" id="idPaso1" >Caiguda Detectada</p>
						<p class="pPasoSimulacion" id="idPaso2" >Sistema Activat</p>
						<p class="pPasoSimulacion" id="idPaso3" >Notificació Generada</p>
						<p class="pPasoSimulacion" id="idPaso4" >Simulació Finalitzada</p>
						<div class="divBotonesSimulacion">
							<a class="linkInicio" href="index.php">Inici</a>
							<a class="linkSimular" id="idLinkSimular" name="caida" href="javascript:simular();">Simular</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
