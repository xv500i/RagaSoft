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
						<img src="img/logo/logoTextoMini.png"/><img class="imgAbuelos" title="Nostres Avis" src="img/abuelosContentos.png" /><br /><br />
						<!-- <p><big><b>Resum</b></big></p><br /> -->
						<p class="textoTab">El projecte <b><i>RAGASOFT SIMULACIONS</i></b> es un petit mòdul d'un gran projecte que involucra persones i tecnologia.</p><br />
						<p>El principal <b><i>objectiu</i></b> es realitzar simulacions d'esdeveniments que les persones monitoritzades amb RFID poden originar, i enregistrar així les corresponents notificacions.
							La manera en que es simulen els esdeveniments es mitjançant la interacció amb una aplicació web que permet executar aquestes simulacions. <br />L’execució de les simulacions es comunicarà amb serveis externs d’enviament de missatges que proporcionaran la comunicació entre el resident, el sistema i el cuidador.<br />El prototipus també ofereix la possibilitat de consultar totes les notificacions del sistema i confirmar aquelles que l’usuari (representant un cuidador) en tingui permís. En aquest cas, el permís es donarà demanant el telèfon del cuidador responsable d’aquella notificació, que haurà de ser el mateix que es va introduir en el moment d’executar la simulació.<br /><br />
							En aquest mòdul bàsicament ens centrem en 3 tipus d'esdeveniments:
						</p>
						<ul>
							<li>Simulacions d'incendi</li>
							<li>Simulacions de caigudes</li>
							<li>Simulacions de tardançes</li>
						</ul><br />
						<p class="tituloApartado"><big><b>Pas a pas</b></big></p><br />
						<p>A continuació expliquem el funcionament de l'aplicació amb un exemple pràctic.</p><br />
						 
						<p>Imaginemnos que el sistema està en funcionament al 100% i es una nit tranquil·la en la vida dels usuaris. Però de sobte, a casa del señor Joquim, un dels clients, es detecta un <b style="color:#990000;">incendi</b> mentre ell dorm.<br /><br />
						<b>¿Com simularem aquest esdeveniment?</b>
						<ol>
							<li>Entrarem a la pàgina web i clicarem o bé a l'opció <i>"Incendi"</i> del menú superior o al botó <i>"Accedir"</i> de la capsa corresponent.</li>
							<li>Per executar la simulació clicarem al botó <i>"Simular"</i> i això iniciarà els mecanismes establerts.</li>
							<li>Seguidament haurem d'introduïr el teléfon al cual rebrem l'SMS amb la notificació generarada pel sistema.</li>
							<li>Si no es produeix cap problema, els indicadors dels processos pasaràn a color verd indicant que tot ha anat correctament.</li>
						</ol>
						<img title="Pantalles Simulació" src="img/pantallas1.png"/><br /><br />
						<b>¿Com consultarem les notificacions generades?</b>
						<ol>
							<li>Farem clic a l'opció <i>"Notificacions"</i> del menú superior o al botó <i>"Accedir"</i> de la capsa corresponent a la pantalla d'inici.</li>
							<li>Un cop aquí podrem veure totes les notificacions i el seu estat: confirmat, periode vençut, o pendent de confirmar.</li>
						</ol>
						<img title="Pantalles Simulació" src="img/pantallas2.png"/><br /><br />
						<b>¿I com sabrà el sistema que m'he assabentat d'una emergència?</b>
						<ol>
							<li>Per comunicar al sistema que som conscients d'una notificació, clicarem el botó <i>"Confirmar"</i> de la notificació corresponent.</li>
							<li>Llavors haurem d'introduïr el teléfon que vam introduir per generar-la. Amb aquesta condició es vol representar que solament els cuidadors responsables seràn els que tindràn permís per confirmar les notificacions dels seus residents.</li>
							<li><span style="color:#990000; font-weight: bold;">En cas de no confirmar una notificació en menys de 2 minuts</span> el sistema ens enviarà un SMS per avisarnos de que el periode de la notificació en questió ha vençut, i en un cas real s'hagués avisat al servei d'emergències</li>
						</ol>
						<img title="Pantalles Simulació" src="img/pantallas3.png"/><br /><br />
						</p><br /><br />
						 
						<!-- <p><b><i>1. </i></b><b><i>Generar Simulació</i></b><br />
							El que es pretén amb l'execució de simulacions, es representar un possible esdeveniment (incendi, caiguda o tardança) succeït a un usuari seleccionat alatzar.<br />Per a generar la simulació haurem d'accedir a l'opció dessitjada del menú superior o de la pantalla inicial, i un cop adintre, fer clic al botó <i>"Simular"</i></p><br />
						<p><b><i>2. </i></b>Per <b><i>consultar</i></b> les notificaciones del sistema, has d'accedir a l'apartat <i>"notificacions"</i>.</p><br />
						<p><b><i>3. </i></b>Per <b><i>confirmar</i></b> les notificaciones generades, has d'accedir a l'apartat <i>"notificacions"</i> i pitjar el botó <b><i>confirmar</i></b> de la notificació desitjada. El sitema demanarà el telèfon que es va introduir en el moment de simular l'esdeveniment ( representa el telèfon del cuidador ) i si es correcte, la notificació es confirmarà èxit.</p><br />
						-->
						<p class="tituloApartado"><big><b>Tecnologia</b></big></p><br />
						<p>La tecnologia utilitzada per aquest prototipus ha estat la corresponent a una aplicació web: HTML, XML i CSS per al contingut de la presentació. JavaScript en combinació amb AJAX per dinamitzar la web. PHP a la banda del servidor par a la lògica de l’aplicació y SQL amb el sistema gestor MySQL per a la base de dades del projecte.</p><br />
						<p class="tituloApartado" style="text-align: center;"><big><b>Llicència</b></big></p><br />
						<p style="text-align: center;">Open Source</p><br />
						<p class="tituloApartado" style="text-align: center;"><big><b>Versió</b></big></p><br />
						<p style="text-align: center;">1.3</p><br />
						<p class="tituloApartado" style="text-align: center;"><big><b>Equip</b></big></p><br />
						<img title="Pantalles Simulació" src="img/Equipo.png"/><br /><br />
						<!--<p>Natjo Raga Llorenç - <i>Gestió del Projecte</i></p>
						<p>Alex Soms Batalla - <i>Gestió Base de Dades</i></p>
						<p>Rubén Ferre Baiges - <i>Lògica de l'Aplicació</i></p>
						<p>David  Gràcia Llobert - <i>Lògica de l'Aplicació</i></p>
						<p>Daniel Martínez Cruz - <i>Intefície Gràfica</i></p><br /><br />-->
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
