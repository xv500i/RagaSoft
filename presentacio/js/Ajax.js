/**
 * 
 * Ajax RagaSoft
 * 
 * @author RagaSoft
 * @since 04/01/2013
 * 
 */


function Ajax(){

	var objeto = false;
	
	if (window.XMLHttpRequest) {
		objeto = new XMLHttpRequest();
		if (objeto.overrideMimeType) {
			
			// Si queremos generar una respuesta en xml...
			// (aunque creo que no es necesario)
			//objeto.overrideMimeType('text/xml');
		}
	}
	else{
		if (window.ActiveXObject) { // IE
			try {
				objeto = new ActiveXObject("Msxml2.XMLHTTP");
			} 
			catch (e) {
				try {
					objeto = new ActiveXObject("Microsoft.XMLHTTP");
				} 
				catch (e) {
					objeto = false;
				}
			}
		}
	}
	
	if (!objeto) alert("No se puede crear la instancia XMLHTTP");

	return objeto;
}

