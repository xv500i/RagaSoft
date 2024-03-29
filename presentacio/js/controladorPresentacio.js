/**
 * 
 * ControladorPresentacio.js
 * 
 * @author RagaSoft
 * @since 04/01/2013
 * 
 */

// Variables globales

var ajax 	= new Ajax();
var id;
var tlf		= '';

function simular( ){
	
	document.getElementById('idPaso1').style.backgroundPosition = "0 0";
	document.getElementById('idPaso2').style.backgroundPosition = "0 0";
	document.getElementById('idPaso3').style.backgroundPosition = "0 0";
	document.getElementById('idPaso4').style.backgroundPosition = "0 0";
	
	tlf = prompt('Introdueix el telèfon receptor del SMS d\'alerta.\nExemple: 612345678', tlf);
	if( tlf != null ){
		
		while( !validaTelefon() && tlf != null )
			tlf = prompt('Telèfon erroni.\nEl format es: 612345678', tlf);
	
		if( tlf != null ){
			
			document.getElementById('idPaso1').style.backgroundImage = "url(../presentacio/img/bolas.png)";
			document.getElementById('idPaso2').style.backgroundImage = "url(../presentacio/img/bolas.png)";
			document.getElementById('idPaso3').style.backgroundImage = "url(../presentacio/img/bolas.png)";
			document.getElementById('idPaso4').style.backgroundImage = "url(../presentacio/img/bolas.png)";
			
			document.getElementById('idLinkSimular').className="linkSimularBloqueado";
			document.getElementById('idLinkSimular').href="javascript:;";
			
			semaforo(0, 1, 2);
			setTimeout( "semaforo( 1, 2, 3)", 1000);
			setTimeout( "semaforo( 2, 3, 4)", 2000);
			setTimeout( "peticion('simular')", 3000 );
		}
	}
	
	
}

function semaforo( anterior, actual, siguiente ){
	
	if( anterior > 0 ){
		document.getElementById('idPaso'+anterior).style.backgroundImage 	= "url(../presentacio/img/bolas.png)";
		document.getElementById('idPaso'+anterior).style.backgroundPosition = "0 -64px";
	}
	document.getElementById('idPaso'+actual).style.backgroundPosition 		= "0 0";
	document.getElementById('idPaso'+actual).style.backgroundImage 			= "url(../presentacio/img/loading.gif)";
	document.getElementById('idPaso'+siguiente).style.backgroundPosition 	= "0 -32px";
}

function confirmar( idNotificacion ){
	
	tlf = prompt('Introdueix el telèfon relacionat amb aquesta notificació.\nExemple: 612345678', tlf);
	if( tlf != null ){
		
		while( !validaTelefon() && tlf != null )
			tlf = prompt('Telèfon erroni.\nEl format es: 612345678', tlf);
		
		if( tlf != null ) peticion('confirmar', idNotificacion );
	}
}

function validaTelefon(){
	
	var test 	= /^[6]\d{8}$/;
	var telReg 	= new RegExp(test);
	
	return telReg.test(tlf);
}
//_____________________________________________________________________________ PETICIONES AJAX

function peticion(peticion, linkInterno){

	if (navigator.appName == "Microsoft Internet Explorer")		ajax = new Ajax();
	
	// Compruebo si se recibe un link interno como segundo parámetro...
	subLink = (typeof linkInterno == 'undefined') ? null : linkInterno;
	id 		= subLink;
	metodo 	= 'POST';
	url 	= '../presentacio/controladorPresentacio.php';
	
	
	switch (peticion) {
	
		// Cargar index ****************************************************
		
		case 'cargarNotificaciones':
			
			variables = 'peticion=' + peticion;
			ajax.onreadystatechange = this.cargaNotificacion;
		break;
		
		case 'confirmar':
			
			variables = 'peticion=' + peticion + '&idNotificacion='+subLink + '&telefono='+tlf;
			ajax.onreadystatechange = this.cargaConfirmar;
			
		break;
		case 'simular':
			
			variables = 'peticion=' + peticion + '&tipo='+ document.getElementById('idLinkSimular').name + '&telefono='+tlf;
			ajax.onreadystatechange = this.cargaSimulacion;
			
		break;
		
		default:
			variables = null;
			return null;
			break;
			
	}
	ajax.open(metodo, url, true);
	ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajax.setRequestHeader("Content-length", variables.length);
	ajax.setRequestHeader("Connection", "close");
	
	ajax.send(variables);

}

this.cargaNotificacion = function(){
	
	if (ajax.readyState < 4) { // Mientras se va procesando la peticion...
		
	}
	else {
		if (ajax.readyState == 4) {
			if (ajax.status == 200) {
				
				document.getElementById('idContenidoNotificaciones').innerHTML = ajax.responseText;
								
				if (navigator.appName == "Microsoft Internet Explorer") ajax.abort();
				
			}
			else {
				alert('Hubo algún problema con la petición. ( Estatus_error: ' + ajax.status + ' )');
			}
		}
	}
}

this.cargaSimulacion = function(){
	
	if (ajax.readyState < 4) { // Mientras se va procesando la peticion...
		document.getElementById('idPaso4').style.backgroundImage 	= "url(../presentacio/img/loading.gif)";
		document.getElementById('idPaso3').style.backgroundImage 	= "url(../presentacio/img/bolas.png)";
		document.getElementById('idPaso3').style.backgroundPosition = "0 -64px";
	}
	else {
		if (ajax.readyState == 4) {
			if (ajax.status == 200) {
				
				// La respuesta es en XML...
				var xml 		= ajax.responseXML;
				var docXml 		= xml.documentElement;
				var resultado	= docXml.getElementsByTagName('resultado')[0];
				
				if( resultado.firstChild.data == 'OK') 	
						document.getElementById('idPaso4').style.backgroundPosition = "0 -64px";
				else{
						document.getElementById('idPaso3').style.backgroundPosition = "0 -32px";
						document.getElementById('idPaso4').style.backgroundPosition = "0 -96px";
						alert("Error al generar la notifiación. Vuélvalo a intentar.");
				}
					
				document.getElementById('idPaso4').style.backgroundImage 	= "url(../presentacio/img/bolas.png)";
				document.getElementById('idLinkSimular').className="linkSimular";
				document.getElementById('idLinkSimular').href="javascript:simular();";
						
				if (navigator.appName == "Microsoft Internet Explorer") ajax.abort();
				
			}
			else {
				alert('Hubo algún problema con la petición. ( Estatus_error: ' + ajax.status + ' )');
			}
		}
	}
}

this.cargaConfirmar = function(){
	
	if (ajax.readyState < 4) { // Mientras se va procesando la peticion...
		
		document.getElementById('idTdConfirmar'+ id).innerHTML = "<img class=\"imgLoadingConfirmar\" title=\"Confirmant...\" src=\"img/loading.gif\">";
	}
	else {
		if (ajax.readyState == 4) {
			if (ajax.status == 200) {
				
				// La respuesta es en XML...
				var xml 		= ajax.responseXML;
				var docXml 		= xml.documentElement;
				var resultado	= docXml.getElementsByTagName('resultado')[0];
				
				if( typeof resultado !== "undefined" && resultado.firstChild.data == 'OK') 	
						document.getElementById('idTdConfirmar'+ id).innerHTML = "<img title=\"Notificació Confirmada\" src=\"img/ico/tick.png\" />";
				else if( typeof resultado !== "undefined" && resultado.firstChild.data == 'ERROR_TELEFONO'){ 
						document.getElementById('idTdConfirmar'+ id).innerHTML = "<a class=\"linkConfirmar\" href=\"javascript:void(0)\" onclick=\"confirmar("+id+");\">Confirmar</a>";
						alert('El telèfon introduit no està relacionat amb la notificació seleccionada.');
				}
				else if( typeof resultado !== "undefined" && resultado.firstChild.data == 'FORA_TEMPS'){ 
						document.getElementById('idTdConfirmar'+ id).innerHTML = "<img title=\"Periode de confirmació vençut.\" src=\"img/ico/error.png\" />";
				}
				else 	document.getElementById('idTdConfirmar'+ id).innerHTML = "<img title=\"Error al corfirmar. Actualitza la pàgina i torna-ho a intentar.\" src=\"img/ico/error.png\" />";
				
				if (navigator.appName == "Microsoft Internet Explorer") ajax.abort();
				
			}
			else {
				alert('Hubo algún problema con la petición. ( Estatus_error: ' + ajax.status + ' )');
			}
		}
	}
}
