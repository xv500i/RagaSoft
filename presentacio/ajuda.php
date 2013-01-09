<!DOCTYPE html>
<html lang="es-ES">
	<?php require_once 'cabecera.php'; ?>
	<body>
		<div class="superCaja" id="idSuperCaja">
			<div class="divCaja" id="idCaja">
				<div class="divFrnjaSuperior">
					<div class="divLogo"><img alt="RagaSoft.com" src="img/logo/logoTexto.png"></img></div>
					<!--<div class="divIndice">Ajuda</div>-->
					<?php include_once 'menuSuperior.php'; ?>
				</div>
				<div class="divCentral">
					<div class="divContenido" id="idContenidoAyuda">
						<img src="img/logo/logoTextoMini.png"/><br /><br />
						<p><big><b>Resum</b></big></p><br />
						<p>El projecte <b><i>RAGASOFT SIMULACIONS</i></b> es un petit módul d'un gran projecte que involucra persones i tecnología.</p><br />
						<p>El principal <b><i>objectiu</i></b> es realitzar simulacions d'esdeveniments que les persones monitoritzades amb RFID poden originar, i enregistrar així les corresponents notificacions. En aquest mòdul ens centrem en 3 tipus d'esdeveniments:</p>
						<ul>
							<li>Simulacions d'incendi</li>
							<li>Simulacions de caigudes</li>
							<li>Simulacions de tardançes</li>
						</ul>
						<p><big><b>Instruccions</b></big></p><br />
						<p><b><i>1. </i></b>Per <b><i>executar</i></b> aquestes simulacions has de seleccionar l'opció desitjada al <i>menú superior</i> de l'aplicació o bé des de la pàgina d'inici. Un cop pitjat el botó <b><i>Simular</i></b> s'ha dintroduir el telèfon al qual es vol rebre la notificació de l'esdeveniment, que representarà el telèfon del cuidador de guàrdia.</p><br />
						<p><b><i>2. </i></b>Per <b><i>consultar</i></b> les notificaciones del sistema, has d'accedir a l'apartat <i>"notificacions"</i>.</p><br />
						<p><b><i>3. </i></b>Per <b><i>confirmar</i></b> les notificaciones generades, has d'accedir a l'apartat <i>"notificacions"</i> i pitjar el botó <b><i>confirmar</i></b> de la notificació desitjada. El sitema demanarà el telèfon que es va introduir en el moment de simular l'esdeveniment ( representa el telèfon del cuidador ) i si es correcte, la notificació es confirmarà èxit.</p><br />
						<p><big><b>Llicència</b></big></p><br />
						<p>Open Source</p><br />
						<p><big><b>Versió</b></big></p><br />
						<p>1.0</p><br />
						<p><big><b>Equip</b></big></p><br />
						<p>Natjo Raga Llorenç - <i>Gestió del Projecte</i></p>
						<p>Alex Soms Batalla - <i>Gestió Base de Dades</i></p>
						<p>Rubén Ferre Baiges - <i>Lògica de l'Aplicació</i></p>
						<p>David  Gràcia Llobert - <i>Lògica de l'Aplicació</i></p>
						<p>Daniel Martínez Cruz - <i>Intefície Gràfica</i></p><br /><br />
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
