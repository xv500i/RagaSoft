<!DOCTYPE html>
<html lang="es-ES">
	<?php require_once 'cabecera.php'; ?>
	<body>
		<div class="superCaja" id="idSuperCaja">
			<div class="divCaja" id="idCaja">
				<div class="divFrnjaSuperior">
					<div class="divLogo"><img alt="RagaSoft.com" src="img/logo/logoTexto.png"></img></div>
					<!--<div class="divSubLogo"><img alt="" src=""></img></div>-->
					<!--<div class="divIndice">Inici</div>-->
					<?php include_once 'menuSuperior.php'; ?>
				</div>
				<div class="divCentral">
					<div class="divContenido" id="idContenidoInicio">
						<div class="divOpcionContenido" id="divOpcionNotificaciones">
							<img alt="" src="img/notificacio64.png"></img>
							<p class="miniTituloOpcion">Consultar</p>
							<p class="tituloOpcion">Notificacions</p>
							<p class="descripcionOpcion">En aquest apartat es podrà confirmar totes les notificaciones pendents i consultar la seva informació.</p>
							<a href="notificacions.php">Accedir</a>
						</div>
						<div class="divOpcionContenido" id="divOpcionIncendio">
							<img alt="" src="img/fire64.png"></img>
							<p class="miniTituloOpcion">Simular</p>
							<p class="tituloOpcion">Incendi</p>
							<p class="descripcionOpcion">Aquesta opció simularà un incenci a una vivenda i farà que el sistema prengui les mesures programades.</p>
							<a href="incendi.php">Accedir</a>
						</div>
						<div class="divOpcionContenido" id="divOpcionCaida">
							<img alt="" src="img/caida64.png"></img>
							<p class="miniTituloOpcion">Simular</p>
							<p class="tituloOpcion">Caiguda</p>
							<p class="descripcionOpcion">Aquesta simulació activarà els mecanismes del sistema encarregats de gestionar les caigudes dels usuaris.</p>
							<a href="caiguda.php">Accedir</a>
						</div>
						<div class="divOpcionContenido" id="divOpcionTardanza">
							<img alt="" src="img/clock64.png"></img>
							<p class="miniTituloOpcion">Simular</p>
							<p class="tituloOpcion">Tardança</p>
							<p class="descripcionOpcion">Aquesta simulació generará una notificació de tardança que activarà el sistema per a resoldre aquest esdeveniment.</p>
							<a href="tardanca.php">Accedir</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
