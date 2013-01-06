<?php

@session_start();

require_once 'ControladorPresentacion.php';
require_once 'Validador.php';
require_once 'Util.php';
require_once 'Fpdf.php';
require_once 'Pdf.php';

require_once '../dominio/Usuario.php';
require_once '../dominio/Empresa.php';
require_once '../dominio/EmpresaCliente.php';
require_once '../dominio/Contacto.php';
require_once '../dominio/AlbaranEmpresa.php';

require_once '../data/MySQL.php';
require_once '../data/dataUsuario.php';
require_once '../data/dataEmpresa.php';
require_once '../data/dataProvincia.php';
require_once '../data/dataMunicipio.php';
require_once '../data/dataFuncion.php';
require_once '../data/dataRelacion.php';
require_once '../data/dataEmpresaCliente.php';
require_once '../data/dataContacto.php';
require_once '../data/dataAlbaranEmpresa.php';


class Controlador
{
	
	// Paquete Controlador *************************************
	private $controladorPresentacion;
	private $validador;
	private $util;
	private $fpdf;
	private $pdf;

	
	// Paquete Data *************************************
	private $bd;
	private $dataRelacion;
	private $dataFuncion;
	private $dataUsuario;
	private $dataEmpresa;
	private $dataProvincia;
	private $dataMunicipio;
	private $dataEmpresaCliente;
	private $dataContacto;
	private $dataAlbaranEmpresa;
	
	
	function __construct(){
		
		$this->controladorPresentacion 	= new ControladorPresentacion();
		$this->validador				= new Validador();
		$this->util						= new Util();
		
		
		//$this->bd						= new MySQL("josdmr5f", "xvctgsgv", "localhost", "josdmr5f_kybo");
		$this->bd						= new MySQL("root", "rootroot", "localhost", "kybo");
		$this->dataRelacion				= new dataRelacion(  		$this->bd );
		$this->dataFuncion				= new dataFuncion( 	 		$this->bd );
		$this->dataUsuario	 			= new dataUsuario(   		$this->bd );
		$this->dataEmpresa 				= new dataEmpresa(   		$this->bd );
		$this->dataProvincia			= new dataProvincia( 		$this->bd );
		$this->dataMunicipio			= new dataMunicipio( 		$this->bd );
		$this->dataEmpresaCliente		= new dataEmpresaCliente( 	$this->bd );
		$this->dataContacto				= new dataContacto( 		$this->bd );
		$this->dataAlbaranEmpresa		= new dataAlbaranEmpresa(   $this->bd );
		
	}
	
	// Mostrar apartados (html) *************************************
	function pinta( $apartado ){
		
		$this->controladorPresentacion->quePinto( $apartado );
	}
	function pintaRegistro(){
		
		$provincias = $this->dataProvincia->getAll();
		$empresas	= $this->dataEmpresa->getAllAccesoAbiertoEstadoActivo();
		
		$this->controladorPresentacion->pintaRegistro( $provincias, $empresas );
	}
	function rellenaSelectMunicipio( $idProvincia ){
		
		$municipios = $this->getMunicipiosForIdProvincia( $idProvincia );
		
		$this->controladorPresentacion->pintaSelectMunicipio( $municipios );
	}


	// Sesion
	function acceso(){
		
		
		if (    !isset($_SESSION['nombre']) || !isset($_SESSION['userLogin']) || !isset($_SESSION['rol']) || !isset($_SESSION['idUsuario'])  ){
		
			echo 'acceso_denegado';
		
		}else{
			
			$this->generaXmlInicioSesion( $_SESSION['nombre'], $_SESSION['rol'], $_SESSION['idUsuario'] );
		}
		
	}
	function logout(){
		
		session_unset();
		session_destroy();
		
	}

	function generaXmlInicioSesion( $usuario, $rol, $idUsuario ){
		
		if( $rol == 'admin' ){ 	
			
			$funciones 	= $this->dataFuncion->getFuncionesAdministrador();
			$empresas 	= null;
		
		}
		else{
			
			$empresas 				= $this->getEmpresasForIdUsuario( $idUsuario );
			$funciones 				= $this->dataFuncion->getFuncionesUsuario();
			$funcionesRelacionadas 	= $this->dataRelacion->getIdFuncionesForIdUsuario_usuario_empresa_dispone_funcion( $idUsuario );
			
			if ( $funcionesRelacionadas ){
				
				foreach( $funcionesRelacionadas as $una ){
					
					$funcion = $this->dataFuncion->getForId( $una->idFuncion );
					
					if( $funcion->nivel == "eventual" || $funcion->nivel == "contrato"){
						 array_push( $funciones, $funcion );
					}
				}
			}
		}
		$this->controladorPresentacion->pintaInicioSesion( $usuario, $rol, $funciones, $empresas );
	}
	function accesoEmpresaXml( $idEmpresa ){
		
		$idUsuario 	= $_SESSION['idUsuario'];
		$rol 		= $_SESSION['rol'];
		$_SESSION['idEmpresa'] = $idEmpresa;
		
		
		$funciones 	= $this->getFuncionesForIdUsuarioEnEmpresa( $idUsuario, $idEmpresa );
		$empresa	= $this->dataEmpresa->getNameForId( $idEmpresa );
		
		$this->controladorPresentacion->pintaPanelEmpresa( $empresa->nombre, $funciones, $rol );
		
	}
	
	// Valicacion de formularios *************************************
	function valida( $apartado ){
		
		$valido = false;
		
		$valido = $this->validador->queValido( $apartado );
		
		if( $valido ){ 
		
			switch( $apartado ){
				
				case 'validaFormRegistro':
					
					if( $this->compruebaFormRegistro() ){
					
						$this->registraUsuario();
					
						$this->mailRegistro();
					}

				break;
				
				case 'updatePerfil':
			
					if( $this->compruebaPerfil() ){
						
						if( $_SESSION['rol'] == 'admin' ) 	$this->updateUsurioPorAdmin();
						else								$this->updateUsuarioPorUsuario();
					}

				break;
				
				case 'validaFormContacta':
				
					$this->mailContacta();

				break;
				
				case 'validaFormCrearEmpresa':
					
					if( $this->compruebaFormEmpresa() ){
					
						$this->registraEmpresa();
					
					}
					
				break;
				
				case 'validaFormActualizarEmpresa':

					if( $this->compruebaFormActualizaEmpresa() ){
					
						if( $_SESSION['rol'] == 'admin' )	$this->updateEmpresaPorAdmin();
						else								$this->updateEmpresa();
					}					

				break;
				
				case 'validaFormCrearEmpresaCliente':
					
					if( $this->compruebaFormCrearEmpresaCliente() ){
					
						$this->registraEmpresaCliente();
					
					}
					
				break;
				
				case 'validaFormActualizarEmpresaCliente':
					
					if( $this->compruebaFormActualizarEmpresaCliente() ){
					
						$this->updateEmpresaCliente();
					
					}
					
				break;
				
				case 'validaFormCrearContacto':
					
					$this->registrarContacto();
					
				break;
				
				case 'validaFormEditarContacto':
					
					$this->actualizarContacto();
					
				break;
				
				case 'validaFormCrearAlbaranEmpresa':
					
					if( $this->compruebaFormCrearAlbaranEmpresa() ){
					
						$this->registrarAlbaranEmpresa();
					}

				break;
				
				case 'validaFormEditarAlbaranEmpresa':
					
					if( $this->compruebaFormEditarAlbaranEmpresa() ){
					
						$this->updateAlbaranEmpresa();
					}

				break;
				
				default:

					echo '<p>Formulario no encontrado</p>';
					
				break;
			}
		}
	}


	// Envio email *************************************
	function mailRegistro(){
		
		$destinatario 	= $_POST['emailReg'];
		$nombre 		= $_POST['nombreReg'];
		$codigo			= $_POST['codigo'];
		$usuario 		= $_POST['usuarioReg'];
		$asunto 		= "Kybo.net: Active su cuenta."; 
		$cuerpo = ' 
			<html> 
				<head> 
			   		<title>Activa cuenta kybo</title> 
				</head> 
				<body>
					<p>Hola '.$nombre.',</p>
					<p>Recientemente se ha registrado en kybo. Para completar el registro haga click en el siguiente link:</p>
					<br /><a href="http://www.kelbert.es/cs/web/activacion/ActivaCuenta.php?codigo='.$codigo.'&loginUsuario='.$usuario.'">http://www.kelbert.es/cs/web/activacion/activa.php?codigo='.$codigo.'&loginUsuario='.$usuario.'</a>
					<br /><br /><p>Una vez unido a kybo, tendrá acceso a zonas restringidas y podrá ser dado de alta en alguna empresa del sistema.</p>
					<br /><p>Grácias,<br />El equipo de kybo</p>
				</body> 
			</html> 
			'; 

			//para el envío en formato HTML 
			$headers = "MIME-Version: 1.0\r\n"; 
			$headers .= "Content-type: text/html; charset=utf-8\r\n"; 
			
			//dirección del remitente 
			$headers .= "From: <".$destinatario.">\r\n"; 
			
			//dirección de respuesta, si queremos que sea distinta que la del remitente 
			//$headers .= "Reply-To: mariano@desarrolloweb.com\r\n"; 
			
			//ruta del mensaje desde origen a destino 
			//$headers .= "Return-path: holahola@desarrolloweb.com\r\n"; 
			
			//direcciones que recibián copia 
			//$headers .= "Cc: maria@desarrolloweb.com\r\n"; 
			
			//direcciones que recibirán copia oculta 
			//$headers .= "Bcc: pepe@pepe.com,juan@juan.com\r\n"; 
			
			mail($destinatario,$asunto,$cuerpo,$headers) ;
		
	}
	function mailContacta(){
		
		$destinatario 	= 'aspersan@gmail.com'; // info@kybo.es
		$nombre 		= $_POST['nombreMail'];
		$apellidos		= $_POST['apellidosMail'];
		$correo 		= $_POST['correoMail'];
		$asunto 		= $_POST['asuntoMail']; 
		$mensaje		= $_POST['comentarioMail'];
		$cuerpo = "\n
					Nombre: ".$nombre."\n
					Apellidos: ".$apellidos."\n
					Email: ".$correo."\n
					Mensaje: \n
					".$mensaje; 

			//para el envío en formato HTML 
			//$headers = "MIME-Version: 1.0\r\n"; 
			//$headers .= "Content-type: text/html; charset=utf-8\r\n"; 
			
			//dirección del remitente 
			$headers .= "From: <".$destinatario.">\r\n"; 
			
			//dirección de respuesta, si queremos que sea distinta que la del remitente 
			//$headers .= "Reply-To: mariano@desarrolloweb.com\r\n"; 
			
			//ruta del mensaje desde origen a destino 
			//$headers .= "Return-path: holahola@desarrolloweb.com\r\n"; 
			
			//direcciones que recibián copia 
			//$headers .= "Cc: maria@desarrolloweb.com\r\n"; 
			
			//direcciones que recibirán copia oculta 
			//$headers .= "Bcc: pepe@pepe.com,juan@juan.com\r\n"; 
			
			mail($destinatario,$asunto,$cuerpo,$headers) ;
		
		
		
	}
	
	// operaciones con BD *************************************
	function queCompruebo( $apartado ){
		
		switch( $apartado ){
			
			case 'formLogin':		$this->compruebaFormLogin(); 			break;
			
			default: echo '<p>Apartado a comprobar no encontrado</p>'; 		break;
			
		}
		
	}
	function compruebaFormLogin(){
		
		$login 	= $_POST['usuarioLogin'];
		$clave 	= md5( $_POST['passLogin'] );
		
		$usuario  = $this->dataUsuario->getForLogin( $login );
		
		if ( !$usuario ) echo 'usuario_erroneo';
		else{
			
			if( $usuario->clave != $clave ) echo 'usuario_erroneo';
			else{
				
				if( $usuario->activo != 'true' ){ 
					
					if( strpos( $usuario->activo, 'eliminado_' ) !== false ) 	echo 'cuenta_bloqueada';
					else											echo 'cuenta_inactiva';
				
				}	
				else{
					
					$_SESSION['userLogin']	= $login;
					$_SESSION['idUsuario']	= $usuario->id;
					$_SESSION['rol']		= $usuario->rol;
					$_SESSION['nombre'] 	= $usuario->nombre.' '.$usuario->apellido1.' '.$usuario->apellido2;
					
					//$this->controladorPresentacion->pintaSesion( $_SESSION['nombre'] );
					$this->generaXmlInicioSesion( $_SESSION['nombre'], $_SESSION['rol'], $_SESSION['idUsuario'] );
				}
			}
		}
	}
	function compruebaFormRegistro(){
		
		$valido 	= true;
		$email 		= $_POST['emailReg'];
		$login  	= $_POST['usuarioReg'];
		
		if( $this->dataUsuario->existeMail( $email ) ){
			
			echo '<campoErroneo>emailReg</campoErroneo>'."\n";
			$valido = false;
		
		}else{
			echo '<campoCorrecto>emailReg</campoCorrecto>'."\n";
		}
		if( $this->dataUsuario->existeLogin( $login )){
			
			echo '<campoErroneo>usuarioReg</campoErroneo>'."\n";
			$valido = false;
		
		}else{
			echo '<campoCorrecto>usuarioReg</campoCorrecto>'."\n";
		}
		
		echo '</xml>';
		
		return $valido;
	}
	function compruebaPerfil(){
		
		$valido 	= true;
		$email 		= $_POST['emailReg'];
		$login  	= $_POST['usuarioReg'];
		$idUsuario 	= $_POST['idUsuario'];
		
		if( $this->dataUsuario->existeMail( $email ) ){
			
			if( !$this->dataUsuario->correspondeMail( $email, $idUsuario ) ){
			
				echo '<campoErroneo>emailReg</campoErroneo>'."\n";
				$valido = false;
			}
		}
		if( $this->dataUsuario->existeLogin( $login ) ){
			
			if( !$this->dataUsuario->correspondeLogin( $login, $idUsuario ) ){
			
				echo '<campoErroneo>usuarioReg</campoErroneo>'."\n";
				$valido = false;
			
			}
		}
		
		if( $_SESSION['rol'] != 'admin'){
			
			if( !$this->dataUsuario->correspondeClave( $idUsuario, md5($_POST['pass0Reg']) ) ){
				echo '<campoErroneo>pass0Reg</campoErroneo>'."\n";
				$valido=false;
			}	
		
		}
		
		echo '</xml>';
		
		return $valido;
		
	}
	function compruebaInscripcion( $idEmpresa ){ 
		
		$idUsuario 	= $_SESSION['idUsuario'];
		$clave 		= $_POST['clave'];
		
		if( !$this->dataEmpresa->checkClave( $idEmpresa, md5($clave) ) ){
			
			echo 'clave_erronea|'.$idEmpresa;
			
		}else{
		
			$relacionIdUsuarioIdEmpresa = $this->dataRelacion->getRelacionIdUsuario_IdEmpresa( $idUsuario, $idEmpresa );
			
			if( !$relacionIdUsuarioIdEmpresa ){
				
				$condicion = 'espera|'.$idEmpresa;
				$this->creaInscripcion_usuario_empresa( $idUsuario, $idEmpresa );
				echo 'inscrito_correctamente|'.$idEmpresa;	
			
			}else{
				
				switch( $relacionIdUsuarioIdEmpresa->condicion ){
					
					case 'denegado': 	echo 'denegado|'.$idEmpresa;				break;
					case 'espera':		echo 'en_espera|'.$idEmpresa;				break;
					default:			echo 'ya_inscrito|'.$idEmpresa;				break;
				}
			}
		}
		
	}
	function compruebaFormEmpresa(){
		
		$valido 	= true;
		$nombre		= $_POST['nombreReg'];
		$nif	  	= $_POST['nifReg'];
		
		if( $this->dataEmpresa->existeNombre( $nombre ) ){
			
			echo '<campoErroneo>nombreReg</campoErroneo>'."\n";
			$valido = false;
		
		}
		if( $this->dataEmpresa->existeNif( $nif )){
			
			echo '<campoErroneo>nifReg</campoErroneo>'."\n";
			$valido = false;
		
		}
		echo '</xml>';
		
		return $valido;
	}
	function compruebaFormActualizaEmpresa(){
		
		$valido 	= true;
		$nombre		= $_POST['nombreReg'];
		$nif	  	= $_POST['nifReg'];
		$id			= $_POST['idEmpresa'];
		
		if( $_SESSION['rol'] != 'admin' )	$clave = md5( $_POST['pass0Reg'] );
		
		
		if( $this->dataEmpresa->existeNombre( $nombre ) ){
			
			if( !$this->dataEmpresa->checkNombre( $id, $nombre ) ){
			
				echo '<campoErroneo>nombreReg</campoErroneo>'."\n";
				$valido = false;
			}
		
		}
		if( $this->dataEmpresa->existeNif( $nif )){
			
			if( !$this->dataEmpresa->checkNif( $id, $nif )){
			
				echo '<campoErroneo>nifReg</campoErroneo>'."\n";
				$valido = false;
			}
		
		}
		
		if ( ($_SESSION['rol'] != 'admin') && (!$this->dataEmpresa->checkClave( $id, $clave ))  ){
			echo '<campoErroneo>pass0Reg</campoErroneo>'."\n";
			$valido = false;
		}
		
		echo '</xml>';
		
		return $valido;
	}
	function updatePermisoAccesoEmpresa( $permiso ){
		
		$idUsuario = $_POST['idUsuario'];
		$idEmpresa = $_POST['idEmpresa'];
		
		$this->dataRelacion->updateConcretCondicion($idUsuario, $idEmpresa, $permiso);				
	
	}
	function compruebaFormCrearEmpresaCliente(){
		
		$valido 		= true;
		$nombre			= $_POST['nombreReg'];
		$nif	  		= $_POST['nifReg'];
		$idUsrCreador 	= $_SESSION['idUsuario'];
		
		if( $this->dataEmpresaCliente->existeNombre( $idUsrCreador, $nombre ) ){
			
			echo '<campoErroneo>nombreReg</campoErroneo>'."\n";
			$valido = false;
		
		}
		if( $this->dataEmpresaCliente->existeNif( $idUsrCreador, $nif )){
			
			echo '<campoErroneo>nifReg</campoErroneo>'."\n";
			$valido = false;
		
		}
		echo '</xml>';
		
		return $valido;
	}
	function compruebaFormActualizarEmpresaCliente(){
		
		$valido 			= true;
		$nombre				= $_POST['nombreReg'];
		$nif	  			= $_POST['nifReg'];
		$idUsrCreador 		= $_SESSION['idUsuario'];
		$idEmpresaCliente 	= $_POST['idEmpresaCliente'];
		
		if( $this->dataEmpresaCliente->existeNombre( $idUsrCreador, $nombre ) ){
			
			if( !$this->dataEmpresaCliente->checkNombre( $idEmpresaCliente, $nombre ) ){
			
				echo '<campoErroneo>nombreReg</campoErroneo>'."\n";
				$valido = false;
			}
		
		}
		if( $this->dataEmpresaCliente->existeNif( $idUsrCreador, $nif )){
			
			if( !$this->dataEmpresaCliente->checkNif( $idEmpresaCliente, $nif )){
			
				echo '<campoErroneo>nifReg</campoErroneo>'."\n";
				$valido = false;
			}
		
		}
		
		echo '</xml>';
		
		return $valido;
	}
	function compruebaFormCrearAlbaranEmpresa(){
		
		$valido 	= true;
		$numero 	= $_POST['numeroReg'];
		$idEmpresa 	= $_SESSION['idEmpresa'];
		
		if( $this->dataAlbaranEmpresa->existeNumeroAlbaran( $numero, $idEmpresa ) ) {
			
			echo '<campoErroneo>numeroReg</campoErroneo>'."\n";
			$valido = false;
		
		}else{
			echo '<campoCorrecto>numeroReg</campoCorrecto>'."\n";
		}
		
		echo '</xml>';
		
		return $valido;
	}
	function compruebaFormEditarAlbaranEmpresa(){
		
		$valido 	= true;
		$numero 	= $_POST['numeroReg'];
		$idEmpresa 	= $_SESSION['idEmpresa'];
		
		if( $this->dataAlbaranEmpresa->checkNumeroAlbaran( $numero, $idEmpresa, $_POST['idAlbaranEmpresa'] ) ) {
			
			echo '<campoErroneo>numeroReg</campoErroneo>'."\n";
			$valido = false;
		}
		
		echo '</xml>';
		
		return $valido;
	}
	
	// Usuario *************************************
	
	function creaUsuario( $id, $idEmpresa, $dni, $rol, $fechaAlta, $nombre, $apellido1, $apellido2, $nacimiento, $telefono, $movil, $provincia, $municipio, $direccion, $cp, $cuenta, $sueldo, $email, $login, $clave, $imagen, $activo ){
		
		return $usuario = new Usuario( $id, $idEmpresa, $dni, $rol, $fechaAlta, $nombre, $apellido1, $apellido2, $nacimiento, $telefono, $movil, $provincia, $municipio, $direccion, $cp, $cuenta, $sueldo, $email, $login, $clave, $imagen, $activo );
	}
	function registraUsuario(){

		$_POST['codigo'] 	= $this->util->aleatorio();
		
		$id 			= $this->getTotalIdMasUno( 'id', 'usuario' );
		$idEmpresa 		= $_POST['idEmpresaReg'];
		$nombre 		= $_POST['nombreReg'];
		$apellido1 		= $_POST['apellido1Reg'];
		$apellido2 		= $_POST['apellido2Reg'];
		$nacimiento 	= $this->util->formatoFechaMySQL( $_POST['nacimientoReg'] );
		$dni 			= null;
		$cuenta 		= null;
		$sueldo 		= null;
		$fechaAlta 		= date( 'Y-m-d H:i:s' );
		$rol 			= 'default';
		$idProvincia	= $_POST['provinciaReg'];
		$idMunicipio 	= $_POST['municipioReg'];
		$direccion 		= null;
		$cp 			= null;
		$email 			= $_POST['emailReg'];
		$telefono		= null;
		$movil 			= null;
		$login 			= $_POST['usuarioReg'];
		$clave 			= md5( $_POST['pass1Reg']);
		$imagen			= "default.png";
		$activo			= $_POST['codigo'];
		
		$usuario = $this->creaUsuario($id, $idEmpresa, $dni, $rol, $fechaAlta, $nombre, $apellido1, $apellido2, $nacimiento, $telefono, $movil, $idProvincia, $idMunicipio, $direccion, $cp, $cuenta, $sueldo, $email, $login, $clave, $imagen, $activo);
		
		$this->insertaUsuario( $usuario );
		
		if( $idEmpresa != 0 ){
			
			$condicion = "espera";
			
			$this->insertUsuario_empresa( $id, $idEmpresa, $condicion );
		}
		
	}
	
	function updateUsurioPorAdmin(){

		$id 			= $_POST['idUsuario'];
		$nombre 		= $_POST['nombreReg'];
		$apellido1 		= $_POST['apellido1Reg'];
		$apellido2 		= $_POST['apellido2Reg'];
		$nacimiento 	= $this->util->formatoFechaMySQL( $_POST['nacimientoReg'] );
		$dni 			= $_POST['dniReg'];
		$cuenta 		= $_POST['cuentaReg'];
		$sueldo 		= str_replace( ",", ".", $_POST['sueldoReg'] );
		$rol 			= $_POST['rolReg'];
		$idProvincia	= $_POST['provinciaReg'];
		$idMunicipio 	= $_POST['municipioReg'];
		$direccion 		= $_POST['direccionReg'];
		$cp 			= $_POST['cpReg'];
		$email 			= $_POST['emailReg'];
		$telefono		= $_POST['telefonoReg'];
		$movil 			= $_POST['movilReg'];
		$login 			= $_POST['usuarioReg'];
		if( $_POST['pass1Reg'] == "" ) 			$clave = "";
		else									$clave = md5( $_POST['pass1Reg']);
		$imagen			= null;
		$activo			= $_POST['activoReg'];
		
		$idEmpresa 		= null;
		$fechaAlta 		= null;
		
		$usuario = $this->creaUsuario($id, $idEmpresa, $dni, $rol, $fechaAlta, $nombre, $apellido1, $apellido2, $nacimiento, $telefono, $movil, $idProvincia, $idMunicipio, $direccion, $cp, $cuenta, $sueldo, $email, $login, $clave, $imagen, $activo);
		
		$this->dataUsuario->updatePorAdmin( $usuario );
		
	}
	function updateUsuarioPorUsuario(){

		$id 			= $_POST['idUsuario'];
		$nombre 		= $_POST['nombreReg'];
		$apellido1 		= $_POST['apellido1Reg'];
		$apellido2 		= $_POST['apellido2Reg'];
		$nacimiento 	= $this->util->formatoFechaMySQL( $_POST['nacimientoReg'] );
		$dni 			= $_POST['dniReg'];
		$cuenta 		= $_POST['cuentaReg'];
		$sueldo 		= str_replace( ",", ".", $_POST['sueldoReg'] );
		$rol 			= $_POST['rolReg'];
		$idProvincia	= $_POST['provinciaReg'];
		$idMunicipio 	= $_POST['municipioReg'];
		$direccion 		= $_POST['direccionReg'];
		$cp 			= $_POST['cpReg'];
		$email 			= $_POST['emailReg'];
		$telefono		= $_POST['telefonoReg'];
		$movil 			= $_POST['movilReg'];
		$login 			= $_POST['usuarioReg'];
		$clave 			= md5( $_POST['pass1Reg'] );
		$imagen			= null;
		$activo			= $_POST['activoReg'];
		
		$idEmpresa 		= null;
		$fechaAlta 		= null;
		
		$usuario = $this->creaUsuario($id, $idEmpresa, $dni, $rol, $fechaAlta, $nombre, $apellido1, $apellido2, $nacimiento, $telefono, $movil, $idProvincia, $idMunicipio, $direccion, $cp, $cuenta, $sueldo, $email, $login, $clave, $imagen, $activo);
		
		$this->dataUsuario->updatePorUsuario( $usuario );
		
		$_SESSION['nombre'] = $usuario->getNombre().' '.$usuario->getApellido1().' '.$usuario->getApellido2();
		
	}
	
	function insertaUsuario( $usuario ){
		
		$this->dataUsuario->insert( $usuario );
	}
	function deleteUsrGestorUsr( $id ){
		
		$condicion  = "eliminado_";
		$condicion .= $this->dataUsuario->getActivoForId( $id );
		
		$this->dataUsuario->updateActivoConValor( $id, $condicion );
		
	}
	function reactivarUsrGestorUsr( $id ){
		
		$activo 	= $this->dataUsuario->getActivoForId( $id );
		$reactivado = ereg_replace("eliminado_", "", $activo);
		
		$this->dataUsuario->updateActivoConValor( $id, $reactivado );
		
	}
	function editUsrGestorUsr( $id ){
		
		$usuario 				= $this->dataUsuario->getForId( $id );
		$usuario->nacimiento 	= $this->util->formatoFechaNormal( $usuario->nacimiento );
		$provincias 			= $this->dataProvincia->getAll();
		$municipio				= $this->dataMunicipio->getForId( $usuario->idMunicipio );
		$idProvincia 			= $municipio->idProvincia;
		$municipios 			= $this->dataMunicipio->getForIdProvincia( $idProvincia );
		
		
		$this->controladorPresentacion->pintaEditaPerfil( $usuario, $provincias, $municipios, $idProvincia );
	}
	function lookUsrGestorUsr( $id ){
		
		$usuario				= $this->dataUsuario->getForId( $id );
		$usuario->nacimiento 	= $this->util->formatoFechaNormal( $usuario->nacimiento );
		$usuario->fechaAlta 	= $this->util->formatoFechaNormalLarga( $usuario->fechaAlta );
		$municipio				= $this->dataMunicipio->getForId( $usuario->idMunicipio );
		$provincia				= $this->dataProvincia->getForId( $municipio->idProvincia );
		$empresas				= $this->getEmpresasForIdUsuario( $id );
		$empresaString 			= $this->empresasRelacionadasToString( $empresas );
		
		$this->controladorPresentacion->pintaLookPerfilPorAdmin( $usuario, $empresaString, $provincia->nombre, $municipio->nombre );
	}
	function editaMiPerfil( $id ){
		
		$usuario 				= $this->dataUsuario->getForId( $id );
		$usuario->nacimiento 	= $this->util->formatoFechaNormal( $usuario->nacimiento );
		$provincias 			= $this->dataProvincia->getAll();
		$municipio				= $this->dataMunicipio->getForId( $usuario->idMunicipio );
		$idProvincia 			= $municipio->idProvincia;
		$municipios 			= $this->dataMunicipio->getForIdProvincia( $idProvincia );
		
		
		$this->controladorPresentacion->pintaEditaPerfil( $usuario, $provincias, $municipios, $idProvincia );
	}
	
	// Empresa *************************************
	
	function creaEmpresa( $id, $nif, $nombre, $provincia, $municipio, $direccion, $cp, $telefono, $cuenta, $sector, $fechaAlta, $acceso, $clave, $imagen, $estado ){
		
		return $empresa = new Empresa( $id, $nif, $nombre, $provincia, $municipio, $direccion, $cp, $telefono, $cuenta, $sector, $fechaAlta, $acceso, $clave, $imagen, $estado );
	}
	function insertaEmpresa( $empresa ){
		
		$this->dataEmpresa->insert( $empresa );
		
	}
	function registraEmpresa(){
		
		$idUsuario		= $_SESSION['idUsuario'];
		$idEmpresa 		= $this->getTotalIdMasUno( 'id', 'empresa' );
		$nif 			= $_POST['nifReg'];
		$nombre 		= $_POST['nombreReg'];
		$provincia		= $_POST['provinciaReg'];
		$municipio	 	= $_POST['municipioReg'];
		$direccion 		= $_POST['direccionReg'];
		$cp 			= $_POST['cpReg'];
		$telefono		= $_POST['telefonoReg'];
		$cuenta 		= $_POST['cuentaReg'];
		$sector			= $_POST['sectorReg'];
		$fechaAlta 		= date( 'Y-m-d H:i:s' );
		$acceso			= $_POST['accesoReg'];
		$clave 			= md5( $_POST['pass1Reg']);
		$imagen			= 'default.png';
		$condicion 		= 'super';
		$estado			= 'activo';
		
		$empresa = $this->creaEmpresa( $idEmpresa, $nif, $nombre, $provincia, $municipio, $direccion, $cp, $telefono, $cuenta, $sector, $fechaAlta, $acceso, $clave, $imagen, $estado );
		
		$this->insertaEmpresa( $empresa );

		$this->insertUsuario_empresa( $idUsuario, $idEmpresa, $condicion );
		
		$idEmpresaKybo 			= 0;
		$idFuncionCreaEmpresa 	= 20;
		$this->dataRelacion->deleteConcret_usuario_empresa_dispone_funcion( $idUsuario, $idEmpresaKybo, $idFuncionCreaEmpresa );
		
		$this->asignaFuncionesBasicasEmpresaSuper( $idEmpresa, $idUsuario );
		
		// Si el usuario dispone de la función "Empresa Cliente"
		// Se asígnará automáticamente a la nueva empresa las funciones de "Albaran" y "Recuento"
		$idFuncionEmpresaCliente = 23;

		if( $this->dataRelacion->existeRelacion_usuario_empresa_dispone_funcion( $idUsuario, $idEmpresaKybo, $idFuncionEmpresaCliente ) ){
			
			$idFuncionAlbaran 	= 24;
			$idFuncionRecuento 	= 25;
			
			$this->dataRelacion->insert_empresa_dispone_funcion( $idEmpresa, $idFuncionAlbaran );
			$this->dataRelacion->insert_empresa_dispone_funcion( $idEmpresa, $idFuncionRecuento );
			
		}
		
	}
	function updateEmpresa(){
		
		$idEmpresa 		= $_SESSION['idEmpresa'];
		$nif 			= $_POST['nifReg'];
		$nombre 		= $_POST['nombreReg'];
		$provincia		= $_POST['provinciaReg'];
		$municipio	 	= $_POST['municipioReg'];
		$direccion 		= $_POST['direccionReg'];
		$cp 			= $_POST['cpReg'];
		$telefono		= $_POST['telefonoReg'];
		$cuenta 		= $_POST['cuentaReg'];
		$sector			= $_POST['sectorReg'];
		$fechaAlta 		= null;
		$acceso			= $_POST['accesoReg'];
		$clave 			= md5( $_POST['pass1Reg']);
		$imagen			= null;
		$estado 		= 'activo';
		
		$empresa = $this->creaEmpresa( $idEmpresa, $nif, $nombre, $provincia, $municipio, $direccion, $cp, $telefono, $cuenta, $sector, $fechaAlta, $acceso, $clave, $imagen, $estado );
		
		$this->dataEmpresa->update( $empresa );
		
	}
	function updateEmpresaPorAdmin(){
		
		$idEmpresa 		= $_POST['idEmpresa'];
		$nif 			= $_POST['nifReg'];
		$nombre 		= $_POST['nombreReg'];
		$provincia		= $_POST['provinciaReg'];
		$municipio	 	= $_POST['municipioReg'];
		$direccion 		= $_POST['direccionReg'];
		$cp 			= $_POST['cpReg'];
		$telefono		= $_POST['telefonoReg'];
		$cuenta 		= $_POST['cuentaReg'];
		$sector			= $_POST['sectorReg'];
		$fechaAlta 		= null;
		$acceso			= $_POST['accesoReg'];
		if( $_POST['pass1Reg'] == "" ) 	$clave	= "";
		else							$clave	= md5( $_POST['pass1Reg']);
		$imagen			= null;
		$estado 		= 'activo';
		
		$empresa = $this->creaEmpresa( $idEmpresa, $nif, $nombre, $provincia, $municipio, $direccion, $cp, $telefono, $cuenta, $sector, $fechaAlta, $acceso, $clave, $imagen, $estado );
		
		$this->dataEmpresa->updatePorAdmin( $empresa );
		
	}
	function getEmpresasForLogin( $login ){
		
		$usuario = $this->dataUsuario->getForLogin( $login );
		
		if( $usuario != false){
			
				return $this->getEmpresasForIdUsuario( $usuario->id );
		}
		
	}
	function empresasRelacionadasToString( $empresas ){
		
		$empresaString = "";
		
		if( $empresas != null ){
					
			foreach( $empresas as $una ){
				
				$empresaString .= $una->nombre.', ';	
			}
			
			$empresaString = substr ($empresaString, 0, -2);	
		}
		return $empresaString;
		
	}
	function formularioCrearEmpresa(){
		
		$provincias = $this->dataProvincia->getAll();
		$this->controladorPresentacion->pintaFormularioCrearEmpresa( $provincias );
	}
	function editaPerfilEmpresa( $id ){
		
		$_SESSION['idEmpresa']  = $id; // si es admin quien modifica...
		
		$empresa				= $this->dataEmpresa->getForId( $id );
		$municipio				= $this->dataMunicipio->getForId( $empresa->municipio );
		$provincias 			= $this->dataProvincia->getAll();
		$idProvincia 			= $municipio->idProvincia;
		$municipios 			= $this->dataMunicipio->getForIdProvincia( $idProvincia );
		
		
		$this->controladorPresentacion->pintaEditaPerfilEmpresa( $empresa, $provincias, $municipios, $idProvincia );
	}
	function deleteEmpresaGestorEmpresa( $id ){
		
		$estado  = "bloqueado";
		$this->dataEmpresa->updateEstadoEmpresa( $id, $estado );
		
	}
	function reactivarEmpresaGestorEmpresa( $id ){
		
		$estado  = "activo";
		$this->dataEmpresa->updateEstadoEmpresa( $id, $estado );
		
	}
	function lookEmpresaGestorEmpresa( $id ){
		
		$_SESSION['idEmpresa'] 	= $id; // si es admin quien modifica...
		
		$empresa				= $this->dataEmpresa->getForId( $id );
		$empresa->fechaAlta 	= $this->util->formatoFechaNormalLarga( $empresa->fechaAlta );
		$municipio				= $this->dataMunicipio->getForId( $empresa->municipio );
		$provincia				= $this->dataProvincia->getForId( $municipio->idProvincia );
		$super					= $this->getNombreSuperDeEmpresa( $id );
		$numEmpleados			= $this->getNumeroEmpleadosEmpresa( $id );
		
		$this->controladorPresentacion->pintaPerfilEmpresa( $empresa, $provincia, $municipio, $super, $numEmpleados );
	}
	
	
	
	// Empresa Cliente *************************************
	
	function creaEmpresaCliente( $id, $idUsrCreador, $nombre, $nif, $sector, $comentario, $provincia, $municipio, $direccion, $cp, $tlf, $fax, $email, $cuenta, $condicion, $diaPago, $fechaAlta, $estado ){
		
		return $empresa = new EmpresaCliente( $id, $idUsrCreador, $nombre, $nif, $sector, $comentario, $provincia, $municipio, $direccion, $cp, $tlf, $fax, $email, $cuenta, $condicion, $diaPago, $fechaAlta, $estado );
	}
	function registraEmpresaCliente(){
		
		$id 			= $this->getTotalIdMasUno( 'id', 'empresacliente' );
		$idUsrCreador	= $_SESSION['idUsuario'];
		
		$nombre 		= $_POST['nombreReg'];
		$nif 			= $_POST['nifReg'];
		$sector			= $_POST['sectorReg'];
		$comentario		= $_POST['comentarioReg'];
		
		$provincia		= $_POST['provinciaReg'];
		$municipio	 	= $_POST['municipioReg'];
		$direccion 		= $_POST['direccionReg'];
		$cp 			= $_POST['cpReg'];
		$tlf			= $_POST['telefonoReg'];
		$fax			= $_POST['faxReg'];
		$email			= $_POST['emailReg'];
		
		$cuenta 		= $_POST['cuentaReg'];
		$condicion		= $_POST['condicionReg'];
		$diaPago		= $_POST['diaPagoReg'];
		
		
		$fechaAlta 		= date( 'Y-m-d' );
		$estado			= 'activo';
		
		$empresaCliente = $this->creaEmpresaCliente( $id, $idUsrCreador, $nombre, $nif, $sector, $comentario, $provincia, $municipio, $direccion, $cp, $tlf, $fax, $email, $cuenta, $condicion, $diaPago, $fechaAlta, $estado );
		
		$this->insertaEmpresaCliente( $empresaCliente );
		
	}
	function lookEmpresaCliente( $id ){
		
		$empresa				= $this->dataEmpresaCliente->getForId( $id );
		$empresa->fechaAlta 	= $this->util->formatoFechaNormal( $empresa->fechaAlta );
		$municipio				= $this->dataMunicipio->getForId( $empresa->idMunicipio );
		$provincia				= $this->dataProvincia->getForId( $municipio->idProvincia );
		$contactos				= $this->dataContacto->getAllForIdEmpresaCliente( $id );
		
		$this->controladorPresentacion->pintaPerfilEmpresaCliente( $empresa, $provincia, $municipio, $contactos );
	}
	function editaPerfilEmpresaCliente( $id ){
		
		
		$empresaCliente			= $this->dataEmpresaCliente->getForId( $id );
		$municipio				= $this->dataMunicipio->getForId( $empresaCliente->idMunicipio );
		$provincias 			= $this->dataProvincia->getAll();
		$idProvincia 			= $municipio->idProvincia;
		$municipios 			= $this->dataMunicipio->getForIdProvincia( $idProvincia );
		
		
		$this->controladorPresentacion->pintaEditaPerfilEmpresaCliente( $empresaCliente, $provincias, $municipios, $idProvincia );
	}
	function updateEmpresaCliente(){
		
		$id 			= $_POST['idEmpresaCliente'];
		$idUsrCreador	= $_SESSION['idUsuario'];
		
		$nombre 		= $_POST['nombreReg'];
		$nif 			= $_POST['nifReg'];
		$sector			= $_POST['sectorReg'];
		$comentario		= $_POST['comentarioReg'];
		
		$provincia		= $_POST['provinciaReg'];
		$municipio	 	= $_POST['municipioReg'];
		$direccion 		= $_POST['direccionReg'];
		$cp 			= $_POST['cpReg'];
		$tlf			= $_POST['telefonoReg'];
		$fax			= $_POST['faxReg'];
		$email			= $_POST['emailReg'];
		
		$cuenta 		= $_POST['cuentaReg'];
		$condicion		= $_POST['condicionReg'];
		$diaPago		= $_POST['diaPagoReg'];
		
		
		$fechaAlta 		= null;
		$estado			= null;
		
		$empresaCliente = $this->creaEmpresaCliente( $id, $idUsrCreador, $nombre, $nif, $sector, $comentario, $provincia, $municipio, $direccion, $cp, $tlf, $fax, $email, $cuenta, $condicion, $diaPago, $fechaAlta, $estado );
		
		$this->dataEmpresaCliente->update( $empresaCliente );
		
		$this->updateVencimientoAlbaranesEmpresaPendientes( $id, $condicion, $diaPago );
		
	}
	function formCrearEmpresaCliente(){
		
		$provincias = $this->dataProvincia->getAll();
		$this->controladorPresentacion->pintaFormCrearEmpresaCliente( $provincias );
	}
	function formCrearContacto( $idEmpresaCliente ){
		
		$apartado = "formCrearContacto";
		$this->controladorPresentacion->quePinto( $apartado, $idEmpresaCliente );
	}
	function formEditarContacto( $idEmpresaCliente, $idContacto ){
		
		$contactoCargado = $this->dataContacto->getForId( $idContacto );
		
		$id 				= $contactoCargado->id;
		$idEmpresaCliente 	= $contactoCargado->idEmpresaCliente;
		$nombre 			= $contactoCargado->nombre;
		$apellido			= $contactoCargado->apellido;
		$tlf				= $contactoCargado->telefono;
		$email				= $contactoCargado->email;
		
		$contacto = $this->creaContacto( $id, $idEmpresaCliente, $nombre, $apellido, $tlf, $email );
		
		$apartado = "formEditarContacto";
		$this->controladorPresentacion->quePinto( $apartado, $contacto );
	}
	function registrarContacto(){
		
		$id 				= $this->getTotalIdMasUno( 'id', 'contacto' );
		$idEmpresaCliente	= $_POST['idEmpresaCliente'];
		$nombre 			= $_POST['nombreReg'.$idEmpresaCliente];
		$apellido 			= $_POST['apellidoReg'.$idEmpresaCliente];
		$tlf	 			= $_POST['telefonoReg'.$idEmpresaCliente];
		$email	 			= $_POST['emailReg'.$idEmpresaCliente];
		
		echo '<idContacto>'.$id.'</idContacto>'."\n";
		echo '</xml>';
				
		$contacto = $this->creaContacto( $id, $idEmpresaCliente, $nombre, $apellido, $tlf, $email );
		
		$this->dataContacto->insert( $contacto );
	}
	function actualizarContacto(){
		
		$id 				= $_POST['idContacto'];
		$idEmpresaCliente	= $_POST['idEmpresaCliente'];
		$nombre 			= $_POST['nombreReg'.$idEmpresaCliente];
		$apellido 			= $_POST['apellidoReg'.$idEmpresaCliente];
		$tlf	 			= $_POST['telefonoReg'.$idEmpresaCliente];
		$email	 			= $_POST['emailReg'.$idEmpresaCliente];
		
		echo '<idContacto>'.$id.'</idContacto>'."\n";
		echo '</xml>';
		
		$contacto = $this->creaContacto( $id, $idEmpresaCliente, $nombre, $apellido, $tlf, $email );
		
		$this->dataContacto->update( $contacto );
	}
	function creaContacto( $id, $idEmpresaCliente, $nombre, $apellido, $tlf, $email ){
		
		return $contacto = new Contacto( $id, $idEmpresaCliente, $nombre, $apellido, $tlf, $email );
	}
	function eliminarContacto( $idContacto ){
		
		$this->dataContacto->deleteForId( $idContacto );
	}
	function insertaEmpresaCliente( $empresaCliente ){
		
		$this->dataEmpresaCliente->insert( $empresaCliente );
		
	}
	function organizarEmpresasCliente(){
		
		$idUsuario = $_SESSION['idUsuario'];
		
		$idEmpresas = $this->dataRelacion->getIdEmpresasForIdUsuarioSuper_usuario_pertenece_empresa( $idUsuario );
		$empresaTemp;
		$empresas = Array();
		
		$relacionesTemp;
		$relaciones = Array();
		
		if ( $idEmpresas != false ){
		
			foreach( $idEmpresas as $idEmpresa ){
				
				$empresaTemp = $this->dataEmpresa->getIdNombreEstadoEmpresaForId( $idEmpresa->idEmpresa );
				
				if ( $empresaTemp->estado == 'activo'){
					
					array_push( $empresas, $empresaTemp );
					$relacionesTemp = $this->dataRelacion->getRelacionesForIdEmpresa_empresaCliente_pertenece_empresa( $empresaTemp->id );
					
					if ( $relacionesTemp != false ){
						
						foreach( $relacionesTemp as $una ){
							
							array_push( $relaciones, $una );
						}
					}
				}
			}
			
		}else $empresas = false;
		
		$empresasCliente = $this->dataEmpresaCliente->getIdNameForIdUsuarioCreador( $idUsuario ); 
		
		
		$this->controladorPresentacion->pintaOrganizarEmpresasCliente( $empresas, $relaciones, $empresasCliente );
		
	}
	function relacionEmpresaCliente( $accion ){
		
		$idEmpresa = $_POST['idEmpresa'];
		$idEmpresaCliente = $_POST['idEmpresaCliente'];
		
		if( $accion == 'asociar' ){
		
			$this->dataRelacion->insert_empresaCliente_pertenece_empresa( $idEmpresa, $idEmpresaCliente );
		
		}elseif( $accion == 'separar' ){
			
			$this->dataRelacion->deleteConcret_empresaCliente_pertenece_empresa( $idEmpresa, $idEmpresaCliente );
		}
	}
	function blockEmpresaCliente( $id ){
		
		$estado  = "bloqueado";
		$this->dataEmpresaCliente->updateEstadoEmpresaCliente( $id, $estado );
		
	}
	function reactivarEmpresaCliente( $id ){
		
		$estado  = "activo";
		$this->dataEmpresaCliente->updateEstadoEmpresaCliente( $id, $estado );
		
	}
	
	
	// Albaranes empresa cliente ***************************
	
	function crearAlbaranEmpresa( $id, $idEmpresa, $idEmpresaCliente, $numAlbaran, $importe, $comision, $resultado, $estado, $fechaEntrega, $fechaFacturacion, $fechaVencimiento ){
		
		return $albaranEmpresa = new AlbaranEmpresa( $id, $idEmpresa, $idEmpresaCliente, $numAlbaran, $importe, $comision, $resultado, $estado, $fechaEntrega, $fechaFacturacion, $fechaVencimiento );
	}
	function formCrearAlbaranEmpresa(){
		
		$empresa 			= $this->dataEmpresa->getIdNombreEmpresaForId( $_SESSION['idEmpresa'] );
		$relaciones 		= $this->dataRelacion->getRelacionesForIdEmpresa_empresaCliente_pertenece_empresa( $empresa->id );
		$empresasCliente 	= Array();
		
		if( $relaciones != false ){
			
			foreach( $relaciones as $una ){
				
				array_push( $empresasCliente, $this->dataEmpresaCliente->getIdNameForId( $una->idEmpresaCliente));
			}
		}else $empresasCliente = false;
		
		$fechaEntrega = date( 'd-m-Y' );
		
		
		$this->controladorPresentacion->pintaFormCrearAlbaranEmpresa( $empresa, $empresasCliente, $fechaEntrega );
	}
	function registrarAlbaranEmpresa(){
		
		$id 				= $this->getTotalIdMasUno( 'id', 'albaran_empresacliente' );
		$idEmpresa 			= $_SESSION['idEmpresa'];
		$idEmpresaCliente   = $_POST['clienteReg'];
		$numeroAlbaran		= $_POST['numeroReg'];
		$importe			= str_replace( ",", ".", $_POST['importeReg'] );
		$comision			= str_replace( ",", ".", $_POST['comisionReg'] );
		$fechaEntrega		= $_POST['entregaReg'];
		$ganancia 			= round(  (  (  (float)$importe * (float)$comision   ) / (float)100  ), 2  );
		$fechaFacturacion 	= $this->util->fechaUltimoDiaMes( $fechaEntrega );
		$estado				= "";
		
		$condiciones 		= $this->dataEmpresaCliente->getCondicionPagoDiaPagoForId( $idEmpresaCliente );
		$fechaVencimiento 	= $this->calcularFechaVencimiento( $fechaFacturacion, $condiciones->condicionPago, $condiciones->diaPago);
		
     	$fechaEntrega		= $this->util->formatoFechaMySQL( $fechaEntrega );
     	$fechaFacturacion	= $this->util->formatoFechaMySQL( $fechaFacturacion );
     	$fechaVencimiento	= $this->util->formatoFechaMySQL( $fechaVencimiento );
		
		//$hoy 				= $this->util->formatoFechaMySQL( date("d/m/Y") );
		$hoy 				= date("Ymd");
     	
     	if( $hoy < $fechaVencimiento )	$estado = 'pendiente';
     	else							$estado = 'facturado';
		
		$albaranEmpresa = new AlbaranEmpresa( $id, $idEmpresa, $idEmpresaCliente, $numeroAlbaran, $importe, $comision, $ganancia, $estado, $fechaEntrega, $fechaFacturacion, $fechaVencimiento );
		
		$this->dataAlbaranEmpresa->insert( $albaranEmpresa );
	}
	function calcularFechaVencimiento( $fechaFacturacion, $condicionPago, $diaPago ){
	
		$fechaArray = explode('/', $fechaFacturacion, 3);
			
		$diaVencimiento		= $fechaArray[0];
		$mesVencimiento 	= $fechaArray[1];
		$yearVencimiento 	= $fechaArray[2];
		
		
		for( $i = 0; $i < $condicionPago; $i++){
			
			if( $mesVencimiento == 12 ){
				
				$mesVencimiento = 1;
				$yearVencimiento++;
			}
			else $mesVencimiento++;
		}
		
		$diaVencimiento = $ultimoDia = date('t', strtotime(date($mesVencimiento .'/01/'. $yearVencimiento)));
		
		$yearVencimiento = (int)$yearVencimiento;
		
		
		if( $diaPago != $diaVencimiento ){
			
			if( $mesVencimiento == 12){ 
					
					$mesVencimiento = 1;
					$yearVencimiento++;
			}
			else	$mesVencimiento++;
			
			$diaVencimiento = $diaPago;
			
		}
		
		return $diaVencimiento.'/'.$mesVencimiento.'/'.$yearVencimiento;
		
	}
	function updateVencimientoAlbaranesEmpresaPendientes( $idEmpresaCliente, $condicion, $diaPago ){
		
		$albaranesEmpresaPendientes = $this->dataAlbaranEmpresa->getAllIdFechaFacturacionVencimientoForIdEmpresaClienteEstado( $idEmpresaCliente, 'pendiente');
		
		if( $albaranesEmpresaPendientes != false){

			$hoy = date("Ymd");
			
			foreach ( $albaranesEmpresaPendientes as $albaran ){
			
				$fechaFacturacion = str_replace(  "-",  "/",   $this->util->formatoFechaNormal( $albaran->fechaFacturacion )    );
				$nuevaVencimiento = $this->calcularFechaVencimiento( $fechaFacturacion, $condicion, $diaPago );
				$nuevaVencimiento = $this->util->formatoFechaMySQL( $nuevaVencimiento );
				
				if( $nuevaVencimiento > $hoy )	$this->dataAlbaranEmpresa->updateFechaVencimiento( $albaran->id, $nuevaVencimiento );
				else							$this->dataAlbaranEmpresa->updateFechaVencimientoEstado( $albaran->id, $nuevaVencimiento, "facturado" );
			}	
		}
		
	}
	function editarAlbaranEmpresa( $idAlbaranEmpresa ){
		
		$empresa 			= $this->dataEmpresa->getIdNombreEmpresaForId( $_SESSION['idEmpresa'] );
		$albaranEmpresa 	= $this->dataAlbaranEmpresa->getForId( $idAlbaranEmpresa );
		$relaciones 		= $this->dataRelacion->getRelacionesForIdEmpresa_empresaCliente_pertenece_empresa( $empresa->id );
		$empresasCliente 	= Array();
		
		$albaranEmpresa->fechaEntrega 		= $this->util->formatoFechaNormal( $albaranEmpresa->fechaEntrega );
		$albaranEmpresa->fechaFacturacion 	= $this->util->formatoFechaNormal( $albaranEmpresa->fechaFacturacion );
		$albaranEmpresa->fechaVencimiento 	= $this->util->formatoFechaNormal( $albaranEmpresa->fechaVencimiento );
				
		if( $relaciones != false ){
			
			foreach( $relaciones as $una ){
				
				array_push( $empresasCliente, $this->dataEmpresaCliente->getIdNameForId( $una->idEmpresaCliente));
			}
		}else $empresasCliente = false;
		
		
		$this->controladorPresentacion->pintaEditarAlbaranEmpresa( $empresa, $albaranEmpresa, $empresasCliente );
	}
	function updateAlbaranEmpresa(){
		
		$id 				= $_POST['idAlbaranEmpresa'];
		$idEmpresa 			= $_SESSION['idEmpresa'];
		$idEmpresaCliente   = $_POST['clienteReg'];
		$numeroAlbaran		= $_POST['numeroReg'];
		$importe			= str_replace( ",", ".", $_POST['importeReg'] );
		$comision			= str_replace( ",", ".", $_POST['comisionReg'] );
		$ganancia 			= round(  (  (  (float)$importe * (float)$comision   ) / (float)100  ), 2  );
		$fechaEntrega		= $this->util->formatoFechaMySQL( $_POST['entregaReg'] );
		$fechaFacturacion	= $this->util->formatoFechaMySQL( $_POST['facturacionReg'] );
		$fechaVencimiento	= $this->util->formatoFechaMySQL( $_POST['vencimientoReg'] );
		$estado				= "";
		
		$hoy 				= $this->util->formatoFechaMySQL( date("d/m/Y") );
     	
     	if( $hoy < $fechaVencimiento )	$estado = 'pendiente';
     	else							$estado = 'facturado';
		

		$albaranEmpresa = new AlbaranEmpresa( $id, $idEmpresa, $idEmpresaCliente, $numeroAlbaran, $importe, $comision, $ganancia, $estado, $fechaEntrega, $fechaFacturacion, $fechaVencimiento );
		
		$this->dataAlbaranEmpresa->update( $albaranEmpresa );
	}
	function lookAlbaranEmpresa( $idAlbaran ){
		
		//TODO pedir únicamente elementos que voy a utilizar ¿o no?
		
		$albaran					= $this->dataAlbaranEmpresa->getForId( $idAlbaran );
		$albaran->fechaEntrega 		= $this->util->formatoFechaNormal( $albaran->fechaEntrega );
		$albaran->fechaFacturacion 	= $this->util->formatoFechaNormal( $albaran->fechaFacturacion );
		$albaran->fechaVencimiento 	= $this->util->formatoFechaNormal( $albaran->fechaVencimiento );
		
		$empresaCliente 			= $this->dataEmpresaCliente->getForId( $albaran->idEmpresaCliente );
		$empresa					= $this->dataEmpresa->getForId( $_SESSION['idEmpresa'] );
		
		
		$this->controladorPresentacion->pintaLookAlbaranEmpresa( $empresa, $albaran, $empresaCliente );
	}
	function eliminarAlbaranEmpresa( $idAlbaranEmpresa ){
		
		$this->dataAlbaranEmpresa->deleteForId( $idAlbaranEmpresa );
	}
	function actualizaAlbaranesPendientes( $idEmpresa ){
		
		$albaranesPendientes = $this->dataAlbaranEmpresa->getAllIdFechaVencimientoForIdEmpresaEstado( $idEmpresa, "pendiente" );
		
		if( $albaranesPendientes != false ){
			
			//$hoy = $this->util->formatoFechaMySQL( date("d/m/Y") );
			$hoy = date("Ymd");
			
			foreach( $albaranesPendientes as $pendiente ){

				$fechaVencimiento = str_replace('-', '', $pendiente->fechaVencimiento );
				if( $fechaVencimiento < $hoy )		$this->dataAlbaranEmpresa->updateEstado( $pendiente->id, "facturado" );
			}
		}
		
	}
	function albaranesEmpresaPendientes(){
		
		$idEmpresa 	= $_SESSION['idEmpresa'];
		$empresa 	= $this->dataEmpresa->getIdNombreEmpresaForId( $idEmpresa );
		$albaranes 	= $this->obtenerAlbararnesEmpresaPendientesDelMes( $idEmpresa );
				
		if( $albaranes != false ){
			
			foreach ( $albaranes as $albaran ){
			
				$albaran->fechaEntrega 		= $this->util->formatoFechaNormal( $albaran->fechaEntrega );
				$albaran->fechaFacturacion 	= $this->util->formatoFechaNormal( $albaran->fechaFacturacion );
				$albaran->fechaVencimiento 	= $this->util->formatoFechaNormal( $albaran->fechaVencimiento );
			}
		}
		
		$this->controladorPresentacion->pintaAlbaranesPendientes( $empresa, $albaranes );
		
	}
	function obtenerAlbararnesEmpresaPendientesDelMes( $idEmpresa ){
		
		if( date('m') == '09' ){
		
				$fecha08 	= date('Y-').'08';
				$fecha09 	= date('Y-m');
			
				return  $this->dataAlbaranEmpresa->getAllPendientesSeptiembreMasClienteForIdEmpresa( $idEmpresa, $fecha08, $fecha09 );
		
		}else	return  $this->dataAlbaranEmpresa->getAllPendientesMesMasClienteForIdEmpresa( $idEmpresa, date('Y-m') );
	}
	function generarPdfAlbaranesEmpresaPendientes(){
		
		$idEmpresa 	= $_SESSION['idEmpresa'];
		$empresa 	= $this->dataEmpresa->getIdNombreImgEmpresaForId( $idEmpresa );
		$albaranes 	= $this->obtenerAlbararnesEmpresaPendientesDelMes( $idEmpresa );
		
		foreach ($albaranes as $albaran ){
			
			$albaran->fechaEntrega 		= $this->util->formatoFechaNormal( $albaran->fechaEntrega );
			$albaran->fechaFacturacion 	= $this->util->formatoFechaNormal( $albaran->fechaFacturacion );
			$albaran->fechaVencimiento 	= $this->util->formatoFechaNormal( $albaran->fechaVencimiento );
		}
		
		// Generamos el pdf.
		$this->pdf=new PDF( $orientation='L', $unit='mm', $format='A4', 'albaranesEmpresaPendientes', $empresa );
		$this->pdf->albaranesEmpresaPendientes( $albaranes );
		
	}
	
	
	// Municipio *************************************
	function getAllProvincias(){
		
		return $this->dataProvincia->getAll();
	}
	function getMunicipiosForIdProvincia( $id ){
		
		return $this->dataMunicipio->getForIdProvincia( $id );
	}

	// Funciones *************************************
	
	function getFuncionesForId( $id ){
		
		$funciones = $this->dataFuncion->getForId( $id );
	}
	function getFuncionesForIdUsuarioEnEmpresa( $idUsuario, $idEmpresa ){
		
		$idsfuncionesEmpresa	= $this->dataRelacion->getIdFuncionesForIdEmpresa_empresa_dispone_funcion( $idEmpresa );
		$idFuncionesUsuario		= $this->getIdFuncionesUsuarioEnEmpresa( $idUsuario, $idEmpresa );
		
		$idsFunciones = Array();
		
		if( $idsfuncionesEmpresa != false && $idFuncionesUsuario != false ){
		
			foreach( $idsfuncionesEmpresa as $fem ){
				
				foreach( $idFuncionesUsuario as $fus ){
					
					if( $fem->idFuncion == $fus->idFuncion ){
						
						array_push( $idsFunciones, $fus->idFuncion);
						
					}
					
				}
				
			}
			
			if( $idsFunciones != false ){
				
				$funciones = Array();
				
				for( $i=0; $i< sizeof($idsFunciones); $i++ ){
					
					array_push( $funciones, $this->dataFuncion->getForId( $idsFunciones[$i] ) );
				}
				return $funciones;
			}
		}
		return false;
	}
	function cargaFuncion( $funcion ){
		
		switch( $funcion ){
			
			case 0:		$this->gestorUsuarios();			break;
			
			case 1:		$this->gestorServicios();			break;
			
			case 5:		$this->perfilUsuario();				break;
			
			case 6:		$this->empresasSistema();			break;
			
			case 9: 	$this->gestorFuncionalidades();		break;
			
			case 13:	$this->gestorEmpleados();			break;
			
			case 20:	$this->formularioCrearEmpresa();	break;
			
			case 21:	$this->gestorEmpresas();			break;
			
			case 22:	$this->perfilEmpresa();				break;
			
			case 23:	$this->gestorEmpresasCliente();		break;
			
			case 24:	$this->gestorAlbaranesEmpresa();	break;
			
			case 25:	$this->preEstadisticas1();			break;
			
			case 26:	$this->preEstadisticas2();			break;
			
			default:	echo '<p>Funcion desconocida</p>';	break;
			
		}
	}

	function gestorUsuarios(){
		
		$usuarios 	= $this->dataUsuario->getAll();
		$idEmpresa 	= 0;
		$user 		= Array();
		
		if( $usuarios != null ){
			
			foreach( $usuarios as $uno ){
				
				$empresas 		= $this->dataEmpresa->getAllNombreEmpresaOkForIdUsuario( $uno->id );
				$empresaString 	= $this->empresasRelacionadasToString( $empresas );
				$municipio 		= $this->dataMunicipio->getForId( $uno->idMunicipio );
				$provincia 		= $this->dataProvincia->getForId( $municipio->idProvincia );
				
				array_push( $user, $this->creaUsuario( $uno->id, $empresaString, $uno->dni, $uno->rol, $this->util->formatoFechaNormalLarga( $uno->fechaAlta ), $uno->nombre, $uno->apellido1, $uno->apellido2, $this->util->formatoFechaNormal( $uno->nacimiento ), $uno->telefono, $uno->movil, $provincia->nombre, $municipio->nombre, $uno->direccion, $uno->cp, $uno->cuenta, $uno->sueldo, $uno->email, $uno->login, $uno->clave, $uno->imagen, $uno->activo) );
			}
		}else $usar = false;
		
		$this->controladorPresentacion->pintaGestorUsuarios( $user );
		
	}
	function gestorEmpresas(){
		
		$empresasCargadas	= $this->dataEmpresa->getAll();
		$relacionesSuper 	= $this->dataRelacion->getRelacionesForCondicion_usuario_pertenece_empresa('super');
		$empresas 			= Array();
		$relaciones 		= Array();
		
		
		if( $empresasCargadas != false ){
			
			foreach( $empresasCargadas as $empresa ){
				
				$municipio 		= $this->dataMunicipio->getForId( $empresa->municipio );
				$provincia 		= $this->dataProvincia->getNombreForId( $municipio->idProvincia );
				$municipio 		= $municipio->nombre;
				
				array_push( $empresas, $this->creaEmpresa( $empresa->id, $empresa->nif, $empresa->nombre, $provincia, $municipio, $empresa->direccion, $empresa->cp, $empresa->telefono, $empresa->cuenta, $empresa->sector, $this->util->formatoFechaNormalLarga( $empresa->fechaAlta ), $empresa->acceso, $empresa->clave, $empresa->imagen, $empresa->estado) );
				
			}
			
			foreach( $relacionesSuper as $relacion ){
				
				$relaciones[$relacion->idEmpresa]['idUsuario'] 		= $relacion->idUsuario;
				$relaciones[$relacion->idEmpresa]['nombreUsuario'] 	= $this->dataUsuario->getNombreForId( $relacion->idUsuario );
				$relaciones[$relacion->idEmpresa]['apellido1'] 		= $this->dataUsuario->getApellido1ForId( $relacion->idUsuario );
				$relaciones[$relacion->idEmpresa]['apellido2'] 		= $this->dataUsuario->getApellido2ForId( $relacion->idUsuario );
				$relaciones[$relacion->idEmpresa]['numEmpleados']	= $this->getNumeroEmpleadosEmpresa( $relacion->idEmpresa );
				
			}
			
		}else{
			$empresas 	= false;
			$relaciones = false;
		}
		
		$this->controladorPresentacion->pintaGestorEmpresas( $empresas, $relaciones );
	}	
	function perfilUsuario(){
		
		$id 					= $_SESSION['idUsuario'];
		$usuario				= $this->dataUsuario->getForId( $id );
		$usuario->nacimiento 	= $this->util->formatoFechaNormal( $usuario->nacimiento );
		$usuario->fechaAlta 	= $this->util->formatoFechaNormalLarga( $usuario->fechaAlta );
		$municipio				= $this->dataMunicipio->getForId( $usuario->idMunicipio );
		$provincia				= $this->dataProvincia->getForId( $municipio->idProvincia );
		$empresas				= $this->getEmpresasForIdUsuario( $id );
		$empresaString 			= $this->empresasRelacionadasToString( $empresas );
		
		$this->controladorPresentacion->pintaLookPerfilPorUsuario( $usuario, $empresaString, $provincia->nombre, $municipio->nombre );
		
	}
	function perfilEmpresa(){
		
		$idEmpresa				= $_SESSION['idEmpresa'];
		$empresa				= $this->dataEmpresa->getForId( $idEmpresa );
		$empresa->fechaAlta 	= $this->util->formatoFechaNormalLarga( $empresa->fechaAlta );
		$municipio				= $this->dataMunicipio->getForId( $empresa->municipio );
		$provincia				= $this->dataProvincia->getForId( $municipio->idProvincia );
		$super					= $this->getNombreSuperDeEmpresa( $idEmpresa );
		$numEmpleados			= $this->getNumeroEmpleadosEmpresa( $idEmpresa );
		
		$this->controladorPresentacion->pintaPerfilEmpresa( $empresa, $provincia, $municipio, $super, $numEmpleados );
		
	}
	function empresasSistema(){
		
		$empresas = $this->dataEmpresa->getAll();
		
		if( $empresas != false ){
			foreach( $empresas as $una){
				
				$municipio = $this->dataMunicipio->getForId( $una->municipio );
				$provincia = $this->dataProvincia->getForId( $municipio->idProvincia );
				
				$una->provincia = $provincia->nombre;
				$una->municipio = $municipio->nombre;
			}
		}
		
		$this->controladorPresentacion->pintaApartadoEmpresas( $empresas );
		
	}
	function gestorFuncionalidades(){
		
		$idEmpresa = $_SESSION['idEmpresa'];
		
		$idsFuncionesAsignadas 		= $this->dataRelacion->getRelacionesForIdEmpresa_usuario_empresa_dispone_funcion( $idEmpresa );
		$idsfuncionesDisponibles	= $this->dataRelacion->getIdFuncionesForIdEmpresa_empresa_dispone_funcion( $idEmpresa );
		
		$funcionesDisponibles = Array();
		
		if ( $idsfuncionesDisponibles != false ){
			foreach ( $idsfuncionesDisponibles as $una ){
				
				$funcionDisponible = $this->dataFuncion->getForId( $una->idFuncion );
				array_push( $funcionesDisponibles, $funcionDisponible );
				
			}
		}
		
		$usuarios = Array();
		
		$idsUsuariosEmpresa 	= $this->dataRelacion->getIdUsuariosOkForIdEmpresa_usuario_pertenece_empresa( $idEmpresa );
		
		foreach( $idsUsuariosEmpresa as $una ){
			
			$usuario = $this->dataUsuario->getIdNombreApellido1Apellido2ForId( $una->idUsuario );
			array_push( $usuarios, $usuario );
			
		}
		
		$this->controladorPresentacion->pintaGestorFuncionalidades( $idEmpresa, $funcionesDisponibles, $idsFuncionesAsignadas, $usuarios );
		
	}
	function gestorServicios(){
		
		$funciones 	= $this->dataFuncion->getAllIdNombreDescripcionFuncionesEmpresa();
		$empresas 	= $this->dataEmpresa->getAllIdNombre();
		$relaciones = $this->dataRelacion->getAllRelaciones_empresa_dispone_funcion();
		
		$this->controladorPresentacion->pintaGestorServicios( $funciones, $empresas, $relaciones );
		
	}
	function gestorServiciosUsuario(){
		
		$idEmpresaKybo	= 0;
		$funciones 		= $this->dataFuncion->getAllIdNombreDescripcionFuncionesUsuario();
		$usuarios 		= $this->dataUsuario->getAllIdLogin();
		$relaciones 	= $this->dataRelacion->getAllRelacionesForIdEmpresa_usuario_empresa_dispone_funcion( $idEmpresaKybo );
		
		$this->controladorPresentacion->pintaGestorServiciosUsuario( $funciones, $usuarios, $relaciones );
		
	}
	function permisoFuncion( $permiso ){
		
		$idUsuario = $_POST['idUsuario'];
		$idFuncion = $_POST['idFuncion'];
		$idEmpresa = $_POST['idEmpresa'];
		
		if( $permiso == 'permitir' ){
		
			$this->dataRelacion->insert_usuario_empresa_dispone_funcion($idUsuario, $idEmpresa, $idFuncion);
		
		}elseif( $permiso == 'denegar' ){
			
			$this->dataRelacion->deleteConcret_usuario_empresa_dispone_funcion($idUsuario, $idEmpresa, $idFuncion);
		}
	}
	function permisoFuncionEmpresa( $permiso ){
		
		$idFuncion = $_POST['idFuncion'];
		$idEmpresa = $_POST['idEmpresa'];
		
		if( $permiso == 'permitir' ){
		
			$this->dataRelacion->insert_empresa_dispone_funcion( $idEmpresa, $idFuncion);
		
		}elseif( $permiso == 'denegar' ){
			
			$this->dataRelacion->deleteForIdEmpresaIdFuncion_usuario_empresa_dispone_funcion( $idEmpresa, $idFuncion );
			$this->dataRelacion->deleteConcret_empresa_dispone_funcion( $idEmpresa, $idFuncion );
		}
	}
	function permisoFuncionUsuario( $permiso ){
		
		$idFuncion = $_POST['idFuncion'];
		$idUsuario = $_POST['idUsuario'];
		$idEmpresa = 0;	// empresa kybo
		
		if( $permiso == 'permitir' ){
		
			$this->dataRelacion->insert_usuario_empresa_dispone_funcion( $idUsuario, $idEmpresa, $idFuncion );
			
			// Si la funcion es crear Empresas cliente, se asigna automáticamente 
			// las opciónes Albaran empresa y Recuento a todas las empresas en las que el usuario es "super".
			if( $idFuncion == 23 ){
				
				$this->asignarFuncionEmpresasUsuarioSuper( $idUsuario, 24);
				$this->asignarFuncionEmpresasUsuarioSuper( $idUsuario, 25);
			}
		
		}elseif( $permiso == 'denegar' ){
			
			$this->dataRelacion->deleteConcret_usuario_empresa_dispone_funcion( $idUsuario, $idEmpresa, $idFuncion );
			
			// Si la funcion tiene funcionesEmpresa relacionadas, también se cancelarán los permisos.
			if( $idFuncion == 23 ){
				
				$this->eliminarFuncionEmpresasUsuarioSuper( $idUsuario, 24);
				$this->eliminarFuncionEmpresasUsuarioSuper( $idUsuario, 25);
			}
		}
	}
	function gestorEmpleados(){
		
		$idEmpresa = $_SESSION['idEmpresa'];
		$idUsuario = $_SESSION['idUsuario'];
		
		$usuarios = Array();
		
		$relacionesUsuarioEmpresa 	= $this->dataRelacion->getRelacionesForIdEmpresa_usuario_pertenece_empresa( $idEmpresa );
		foreach( $relacionesUsuarioEmpresa as $una ){
			
			$usuario = $this->dataUsuario->getForId( $una->idUsuario );
			array_push( $usuarios, $usuario );
			
		}
		
		$this->controladorPresentacion->pintaGestorEmpleados( $idEmpresa, $idUsuario, $usuarios, $relacionesUsuarioEmpresa );
		
	}
	function gestorEmpresasCliente(){

		$idUsuario = $_SESSION['idUsuario'];
		
		$emprsasCargadas;
		$empresasClienteCargadas	= $this->dataEmpresaCliente->getAllForIdUsuarioCreador( $idUsuario );
		$contactosCargados			= $this->dataContacto->getAll();
		$empresasCliente			= Array();
		$contactos					= Array();
		
		if( $empresasClienteCargadas != false){
		
			foreach( $empresasClienteCargadas as $empresa ){
					
				$municipio 		= $this->dataMunicipio->getForId( $empresa->idMunicipio );
				$provincia 		= $this->dataProvincia->getNombreForId( $municipio->idProvincia );
				$municipio 		= $municipio->nombre;
				
				array_push( $empresasCliente, $this->creaEmpresaCliente( $empresa->id, $empresa->idUsuarioCreador, $empresa->nombre, $empresa->nif, $empresa->sector, $empresa->comentario, $provincia, $municipio, $empresa->direccion, $empresa->cp, $empresa->telefono, $empresa->fax, $empresa->email, $empresa->cuenta, $empresa->condicionPago, $empresa->diaPago, $this->util->formatoFechaNormal( $empresa->fechaAlta ), $empresa->estado ) );
					
				if( $contactosCargados != false ){
				
					foreach( $contactosCargados as $contacto){
						
						if( $empresa->id == $contacto->idEmpresaCliente){
							array_push( $contactos, $this->creaContacto( $contacto->id, $contacto->idEmpresaCliente, $contacto->nombre, $contacto->apellido, $contacto->telefono, $contacto->email) );
							unset($contacto);
						}
						
					}
				}		
				else $contactos = false;	
					
			}
		}
		else	$empresasCliente = false;
		
		$this->controladorPresentacion->pintaGestorEmpresasCliente( $empresasCliente, $contactos );
		
	}
	function gestorAlbaranesEmpresa(){
		
		$idEmpresa 					= $_SESSION['idEmpresa'];
		
		// Actualizamos los albaranes pendientes que vencieron ayer.
		$this->actualizaAlbaranesPendientes( $idEmpresa );
		
		$empresa 					= $this->dataEmpresa->getIdNombreEmpresaForId( $idEmpresa );
		$albaranesCargados 			= $this->dataAlbaranEmpresa->getAllForIdEmpresa( $idEmpresa );
		$albaranes 					= Array();
		
		$relacionesEmpresaCliente 	= $this->dataRelacion->getRelacionesForIdEmpresa_empresaCliente_pertenece_empresa( $idEmpresa );
		$empresasCliente 			= Array();
		
		if ( $albaranesCargados != false ){
			
			foreach( $albaranesCargados as $albaran ){
				
				array_push( $albaranes, $this->crearAlbaranEmpresa( $albaran->id, $albaran->idEmpresa, $albaran->idEmpresaCliente, $albaran->numeroAlbaran, $albaran->importe, $albaran->comision, $albaran->ganancia, $albaran->estado, $this->util->formatoFechaNormal( $albaran->fechaEntrega ), $this->util->formatoFechaNormal( $albaran->fechaFacturacion ), $this->util->formatoFechaNormal( $albaran->fechaVencimiento ) ));
			}
			
		}else $albaranes = false;
		
		
		if ( $relacionesEmpresaCliente != false ){

			foreach( $relacionesEmpresaCliente as $una ){
			
				array_push( $empresasCliente, $this->dataEmpresaCliente->getIdNameForId( $una->idEmpresaCliente ) );
				
			}
			
		}else $empresasCliente = false;
		
		
		
		
		
		$this->controladorPresentacion->pintaGestorAlbaranesEmpresa( $empresa, $albaranes, $empresasCliente );
		
	}
	function asignaFuncionesBasicasEmpresaSuper( $idEmpresa, $idUsuario){
		
		$basicas = $this->dataFuncion->getIdFuncionesBasicas();
		foreach ( $basicas as $basica ){
			
			$this->dataRelacion->insert_empresa_dispone_funcion( $idEmpresa, $basica->id );
			$this->dataRelacion->insert_usuario_empresa_dispone_funcion( $idUsuario, $idEmpresa, $basica->id );
		}
		
		
	}
	
	
	
	// Relaciones **********************************
	
	function insertUsuario_empresa( $idUsuario, $idEmpresa, $condicion = 'espera' ){
		
		$this->dataRelacion->insert_usuario_pertenece_empresa( $idUsuario, $idEmpresa, $condicion );
	}
	function getIdEmpresasOkForIdUsuario( $id ){
		
		$idEmpresas = $this->dataRelacion->getIdEmpresasOkForIdUsuario_usuario_pertenece_empresa( $id );
		
		return $idEmpresas;
	}
	function getIdFuncionesUsuarioEnEmpresa( $idUsuario, $idEmpresa){
		
		$idFunciones = $this->dataRelacion->getIdFuncionForIdUsuarioIdEmpresa_usuario_empresa_dispone_funcion($idUsuario, $idEmpresa);
		
		return $idFunciones;
	}
	function creaInscripcion_usuario_empresa( $idUsuario, $idEmpresa ){
		
		$this->dataRelacion->insert_usuario_pertenece_empresa( $idUsuario, $idEmpresa );
		
	}
	function getNombreSuperDeEmpresa( $idEmpresa ){
	
		$idSuper				= $this->dataRelacion->getIdUsuarioForIdEmpresaCondicion_usuario_pertenece_empresa( $idEmpresa, 'super');
		$nombre 				= $this->dataUsuario->getNombreForId( $idSuper );
		$apellido1				= $this->dataUsuario->getApellido1ForId( $idSuper );
		$apellido2				= $this->dataUsuario->getApellido2ForId( $idSuper );
		
		return( $nombre." ".$apellido1." ".$apellido2 );
		
	}
	function getNumeroEmpleadosEmpresa( $idEmpresa ){
		
		return $this->dataRelacion->getNumeroEmpleadosEmpresa_usuario_pertenece_empresa( $idEmpresa );
	}
	function asignarFuncionEmpresasUsuarioSuper( $idUsuario, $idFuncion ){
		
		$relacionesSuper = $this->dataRelacion->getAllRelacionesForIdUsuarioCondicion_usuario_pertenece_empresa( $idUsuario, "super" );
		
		if( $relacionesSuper != false ){
			
			foreach( $relacionesSuper as $relacion ){
				
				$this->dataRelacion->insert_empresa_dispone_funcion( $relacion->idEmpresa, $idFuncion );
				
				// Si queremos que el servicio se active también automaticamente...
				//$this->dataRelacion->insert_usuario_empresa_dispone_funcion( $idUsuario, $relacion->idEmpresa, $idFuncion);
			}
		}
	}
	function eliminarFuncionEmpresasUsuarioSuper( $idUsuario, $idFuncion ){
		
		$relacionesSuper = $this->dataRelacion->getAllRelacionesForIdUsuarioCondicion_usuario_pertenece_empresa( $idUsuario, "super" );
		
		if( $relacionesSuper != false ){
			
			foreach( $relacionesSuper as $relacion ){
				
				$this->dataRelacion->deleteConcret_empresa_dispone_funcion( $relacion->idEmpresa, $idFuncion );
				$this->dataRelacion->deleteForIdEmpresaIdFuncion_usuario_empresa_dispone_funcion( $relacion->idEmpresa, $idFuncion );
			}
		}
	}
	
	
	// Estadisticas ********************************
	
	// Estadisticas 1
	
	function preEstadisticas1(){
		
		$this->estadisticas1( date('Y') );
		
	}
	function estadisticas1( $year ){
		
		$idEmpresa			= $_SESSION['idEmpresa'];
		$empresa			= $this->dataEmpresa->getIdNombreEmpresaForId( $idEmpresa );
		$empresasCliente 	= $this->dataEmpresaCliente->getAllIdNombreForIdEmpresa( $idEmpresa );
		$albaranes			= $this->dataAlbaranEmpresa->getIdNumeroGananciaFechaVencimientoForIdEmpresaYear( $idEmpresa, $year );
		
		
		$this->controladorPresentacion->pintaEstadisticas1( $empresasCliente, $albaranes, $year );
	}
	function resumenEstadisticas1(){
		
		$idEmpresa				= $_SESSION['idEmpresa'];
		$empresa				= $this->dataEmpresa->getIdNameFechaAltaForId( $idEmpresa );
		$empresa->fechaAlta 	= $this->util->formatoFechaLargaToNormal( $empresa->fechaAlta );
		$datos 					= Array();
		
		
		// Datos Resumen para mostrar
		
		$datos['Nº empleados'] 					= $this->dataRelacion->countEmpleadosEnEmpresa( $idEmpresa );
		$datos['Nº Empresas Cliente'] 			= $this->dataRelacion->countEmpresasClienteEnEmpresa( $idEmpresa );
		$datos['Mejor cliente']					= $this->resumen1ClienteProporcionaMasGanancias( $idEmpresa );
		$datos['Peor cliente']					= $this->resumen1ClienteProporcionaMenosGanancias( $idEmpresa );
		$datos['Cliente con más albaranes']		= $this->resumen1ClienteConMasAlbaranes( $idEmpresa );
		$datos['Cliente con menos albaranes']	= $this->resumen1ClienteConMenosAlbaranes( $idEmpresa );
		$datos['Albarán con más ganancia']		= $this->resumen1AlbaranConMasGanancia( $idEmpresa );
		$datos['Albarán con menos ganancia']	= $this->resumen1AlbaranConMenosGanancia( $idEmpresa );
		$datos['Albarán más antiguo']			= $this->resumen1AlbaraMasAntiguo( $idEmpresa );
		$datos['Albarán más reciente']			= $this->resumen1AlbaraMasReciente( $idEmpresa );
		$datos['Nº Albaranes']					= $this->dataAlbaranEmpresa->countForIdEmpresa( $idEmpresa );
		$datos['Ganancias totales']				= $this->resumen1GananciasTotales( $idEmpresa );
		
		$this->controladorPresentacion->pintaResumenEstadisticas1( $empresa, $datos );
	}
	function resumen1ClienteProporcionaMasGanancias( $idEmpresa ){
		
		$empresaCliente = $this->dataEmpresaCliente->getNombreMaxGananciaForIdEmpresa( $idEmpresa );
		
		if( $empresaCliente != false && $empresaCliente->nombre != NULL && $empresaCliente->ganancia != NULL ){
				
				return $empresaCliente->nombre." &#126; ".$empresaCliente->ganancia." &euro;";
		}
		else	return "&#126;";
	}
	function resumen1ClienteProporcionaMenosGanancias( $idEmpresa ){
		
		$empresaCliente = $this->dataEmpresaCliente->getNombreMinGananciaForIdEmpresa( $idEmpresa );
		
		if( $empresaCliente != false && $empresaCliente->nombre != NULL && $empresaCliente->ganancia != NULL){
			
				return $empresaCliente->nombre." &#126; ".$empresaCliente->ganancia." &euro;";
		}
		else	return "&#126;";
	}
	function resumen1ClienteConMasAlbaranes( $idEmpresa ){
		
		$empresaCliente = $this->dataEmpresaCliente->getNombreMaxNumAlbaranesForIdEmpresa( $idEmpresa );
		
		if( $empresaCliente != false && $empresaCliente->nombre != NULL & $empresaCliente->numMaxAlbaranes != NULL){
			
				return $empresaCliente->nombre." ( ".$empresaCliente->numMaxAlbaranes." )";
		}
		else	return "&#126;";
	}
	function resumen1ClienteConMenosAlbaranes( $idEmpresa ){
		
		$empresaCliente = $this->dataEmpresaCliente->getNombreMinNumAlbaranesForIdEmpresa( $idEmpresa );
		
		if( $empresaCliente != false && $empresaCliente->nombre != NULL & $empresaCliente->numMinAlbaranes != NULL){
			
				return $empresaCliente->nombre." ( ".$empresaCliente->numMinAlbaranes." )";
		}
		else	return "&#126;";
	}
	function resumen1AlbaranConMasGanancia( $idEmpresa ){
		
		$albaran = $this->dataAlbaranEmpresa->getNumeroGananciaMaxGananciaForIdEmpresaCliente( $idEmpresa );
		
		if( $albaran != false ) return $albaran->numeroAlbaran." &#126; ".$albaran->ganancia." &euro;";
		else					return "&#126;";
	}
	function resumen1AlbaranConMenosGanancia( $idEmpresa ){
		
		$albaran = $this->dataAlbaranEmpresa->getNumeroGananciaMinGananciaForIdEmpresaCliente( $idEmpresa );
		
		if( $albaran != false ) return $albaran->numeroAlbaran." &#126; ".$albaran->ganancia." &euro;";
		else					return "&#126;";
	}
	function resumen1AlbaraMasAntiguo( $idEmpresa ){
		
		$albaran = $this->dataAlbaranEmpresa->getNumeroFechaVencimientoMasAntiguoForIdEmpresa( $idEmpresa );
		
		if( $albaran != false ){
			
			$albaran->fechaVencimiento 		= $this->util->formatoFechaNormal( $albaran->fechaVencimiento );
			return $albaran->numeroAlbaran." &#126; ".$albaran->fechaVencimiento;
		
		}else return "&#126;";
	}
	function resumen1AlbaraMasReciente( $idEmpresa ){
		
		$albaran = $this->dataAlbaranEmpresa->getNumeroFechaVencimientoMasRecienteForIdEmpresa( $idEmpresa );
		
		if( $albaran != false ){
		
			$albaran->fechaVencimiento 		= $this->util->formatoFechaNormal( $albaran->fechaVencimiento );
			return $albaran->numeroAlbaran." &#126; ".$albaran->fechaVencimiento;
			
		}else return "&#126;";
		
	}
	function resumen1GananciasTotales( $idEmpresa ){
		
		$ganancia = $this->dataAlbaranEmpresa->SumaGananciaForIdEmpresa( $idEmpresa );
		
		if( $ganancia != false ) 	return $ganancia." &euro;";
		else						return "&#126;";
	}
	function generarPdfTablaRecuentoEmpresa( $year ){
		
		$idEmpresa			= $_SESSION['idEmpresa'];
		$empresa			= $this->dataEmpresa->getIdNombreEmpresaForId( $idEmpresa );
		$empresasCliente 	= $this->dataEmpresaCliente->getAllIdNombreForIdEmpresa( $idEmpresa );
		$albaranes			= $this->dataAlbaranEmpresa->getIdNumeroGananciaFechaVencimientoForIdEmpresaYear( $idEmpresa, $year );
		
		// Generamos el pdf.
		$this->pdf=new PDF( $orientation='L', $unit='mm', $format='A4', 'tablaRecuentoEmpresa', $empresa, $year );
		$this->pdf->tablaRecuento( $empresasCliente, $albaranes, $year );
	}
	
	// Estadisticas 2
	
	function preEstadisticas2(){
		
		$this->estadisticas2( date('Y') );
	}
	function estadisticas2( $year ){
		
		$idUsuario 			= $_SESSION['idUsuario'];
		$condicion 			= 'super';
		$empresasCliente 	= $this->dataEmpresaCliente->getIdNombreForIdUsuarioCondicion( $idUsuario, $condicion );
		$albaranes			= $this->dataAlbaranEmpresa->getIdNumeroGananciaFechaVencimientoForIdUsuarioCondicionYear( $idUsuario, $condicion, $year );
				
		$this->controladorPresentacion->pintaEstadisticas2( $empresasCliente, $albaranes, $year );
	}
	function resumenEstadisticas2(){
		
		$idUsuario				= $_SESSION['idUsuario'];
		$usuario				= $this->dataUsuario->getIdNombreApellido1Apellido2FechaAltaForId( $idUsuario );
		$usuario->fechaAlta 	= $this->util->formatoFechaLargaToNormal( $usuario->fechaAlta );
		$datos 					= Array();
		
		
		// Datos Resumen para mostrar
		
		$datos['Nº Total empresas']				= $this->dataEmpresa->countEmpresasForIdUsuario( $idUsuario );
		$datos['Nº Total empleados']			= $this->dataUsuario->countEmpleadosForIdUsuario( $idUsuario );
		$datos['Nº Empresas Cliente'] 			= $this->dataEmpresaCliente->countForIdUsuario( $idUsuario );
		$datos['Nº Albaranes']					= $this->dataAlbaranEmpresa->countForIdUsuario( $idUsuario );
		$datos['Empresa con más beneficio']		= $this->resumen2EmpresaMaxGanancia( $idUsuario );
		$datos['Empresa con menos beneficio']	= $this->resumen2EmpresaMinGanancia( $idUsuario );
		$datos['Mejor Empresa cliente']			= $this->resumen2ClienteProporcionaMasGanancias( $idUsuario );
		$datos['Peor Empresa cliente']			= $this->resumen2ClienteProporcionaMenosGanancias( $idUsuario );
		$datos['Cliente con más albaranes']		= $this->resumen2ClienteConMasAlbaranes( $idUsuario );
		$datos['Cliente con menos albaranes']	= $this->resumen2ClienteConMenosAlbaranes( $idUsuario );
		$datos['Albarán con más ganancia']		= $this->resumen2AlbaranConMasGanancia( $idUsuario );
		$datos['Albarán con menos ganancia']	= $this->resumen2AlbaranConMenosGanancia( $idUsuario );
		$datos['Ganancia total']				= $this->resumen2GananciaTotal( $idUsuario );
		
		$this->controladorPresentacion->pintaResumenEstadisticas2( $usuario, $datos );
	}
	function resumen2EmpresaMaxGanancia( $idUsuario ){
		
		$empresa = $this->dataEmpresa->getNombreMaxGananciaForIdUsuario( $idUsuario );
		
		if( $empresa != false && $empresa->nombre != NULL && $empresa->ganancia != NULL ){
				
				return $empresa->nombre." &#126; ".$empresa->ganancia." &euro;";
		}
		else	return "&#126;";
	}
	function resumen2EmpresaMinGanancia( $idUsuario ){
		
		$empresa = $this->dataEmpresa->getNombreMinGananciaForIdUsuario( $idUsuario );
		
		if( $empresa != false && $empresa->nombre != NULL ){
				
				if( $empresa->ganancia == NULL) $empresa->ganancia = "00.00";
			
				return $empresa->nombre." &#126; ".$empresa->ganancia." &euro;";
		}
		else	return "&#126;";
	}
	function resumen2GananciaTotal( $idUsuario ){
		
		$ganancia = $this->dataAlbaranEmpresa->SumaGananciaForIdUsuario( $idUsuario );
		
		if( $ganancia != false ) 	return $ganancia." &euro;";
		else						return "&#126;";
	}
	function resumen2AlbaranConMasGanancia( $idUsuario ){
		
		$albaran = $this->dataAlbaranEmpresa->getNumeroMaxGananciaForIdUsuario( $idUsuario );
		
		if( $albaran != false && $albaran->numeroAlbaran != NULL && $albaran->ganancia != NULL ) return $albaran->numeroAlbaran." &#126; ".$albaran->ganancia." &euro;";
		else	return "&#126;";
	}
	function resumen2AlbaranConMenosGanancia( $idUsuario ){
		
		$albaran = $this->dataAlbaranEmpresa->getNumeroMinGananciaForIdUsuario( $idUsuario );
		
		if( $albaran != false && $albaran->numeroAlbaran != NULL && $albaran->ganancia != NULL ) return $albaran->numeroAlbaran." &#126; ".$albaran->ganancia." &euro;";
		else	return "&#126;";
	}
	function resumen2ClienteConMasAlbaranes( $idUsuario ){
		
		$empresaCliente = $this->dataEmpresaCliente->getNombreMaxNumAlbaranesForIdUsuario( $idUsuario );
		
		if( $empresaCliente != false && $empresaCliente->nombre != NULL & $empresaCliente->numAlbaranes != NULL){
			
				return $empresaCliente->nombre." ( ".$empresaCliente->numAlbaranes." )";
		}
		else	return "&#126;";
	}
	function resumen2ClienteConMenosAlbaranes( $idUsuario ){
		
		$empresaCliente = $this->dataEmpresaCliente->getNombreMinNumAlbaranesForIdUsuario( $idUsuario );
		
		if( $empresaCliente != false && $empresaCliente->nombre != NULL & $empresaCliente->numAlbaranes != NULL){
			
				return $empresaCliente->nombre." ( ".$empresaCliente->numAlbaranes." )";
		}
		else	return "&#126;";
	}
	function resumen2ClienteProporcionaMasGanancias( $idUsuario ){
		
		$empresaCliente = $this->dataEmpresaCliente->getNombreMaxGananciaForIdUsuario( $idUsuario );
		
		if( $empresaCliente != false && $empresaCliente->nombre != NULL && $empresaCliente->ganancia != NULL ){
				
				return $empresaCliente->nombre." &#126; ".$empresaCliente->ganancia." &euro;";
		}
		else	return "&#126;";
	}
	function resumen2ClienteProporcionaMenosGanancias( $idUsuario ){
		
		$empresaCliente = $this->dataEmpresaCliente->getNombreMinGananciaForIdUsuario( $idUsuario );
		
		if( $empresaCliente != false && $empresaCliente->nombre != NULL && $empresaCliente->ganancia != NULL ){
				
				return $empresaCliente->nombre." &#126; ".$empresaCliente->ganancia." &euro;";
		}
		else	return "&#126;";
	}
	function generarPdfTablaRecuentoGeneral( $year ){
		
		$idUsuario 			= $_SESSION['idUsuario'];
		$condicion 			= 'super';
		$empresasCliente 	= $this->dataEmpresaCliente->getIdNombreForIdUsuarioCondicion( $idUsuario, $condicion );
		$albaranes			= $this->dataAlbaranEmpresa->getIdNumeroGananciaFechaVencimientoForIdUsuarioCondicionYear( $idUsuario, $condicion, $year );
		
		// Generamos el pdf.
		$this->pdf=new PDF( $orientation='L', $unit='mm', $format='A4', 'tablaRecuentoGeneral', $year );
		$this->pdf->tablaRecuento( $empresasCliente, $albaranes, $year );
	}
	
	// Paneles *************************************
	
	function pintaPanelControl(){
		
		if( $_SESSION['rol'] == 'admin' ){
			
			$this->pinta( 'panelAdmin' );
			
		}else{
			
			$this->pinta( 'panelUsuario' );
		}
		
	}
	
	
	// Imagen **************************************
	
	function imagenServidor(){
        $dir        = "../img/avatar/";
        $aleatorio  = $this->util->aleatorio();

        if ( is_uploaded_file ($_FILES['imgNueva']['tmp_name']) )
        {
            $tipo = substr($_FILES['imgNueva']['type'], 0, 5);
			$size = $_FILES['imgNueva']['size'];
			
			if( $size > 102400 ){
				
				echo '<div id="mensaje">error_peso</div>';
				return false;
			}
            
			if( $tipo == 'image' ){
				
				$ficheroTmp = $_FILES['imgNueva']['tmp_name'];
	            $fichero    = $_FILES['imgNueva']['name'];
	
	            while ( file_exists($dir.$aleatorio) ) { $aleatorio = $this->util->aleatorio() ; }
	            
	            $extension 	= end(explode(".", $fichero));
	            $fichero 	= $aleatorio.".".$extension;
	            
	            move_uploaded_file ($ficheroTmp , $dir.$fichero);
	            
	            //echo $fichero;
	            return $fichero;
			}
			else{
				echo '<div id="mensaje">error_tipo</div>';
				return false;
			}

        }else{
        	echo '<div id="mensaje">error_carga</div>';
            return false;
        }

    }
	function imagenEmpresaServidor(){
        $dir        = "../img/empresa/";
        $aleatorio  = $this->util->aleatorio();

        if ( is_uploaded_file ($_FILES['imgNueva']['tmp_name']) )
        {
            $tipo = substr($_FILES['imgNueva']['type'], 0, 5);
        	$size = $_FILES['imgNueva']['size'];
			
			if( $size > 102400 ){
				
				echo '<div id="mensaje">error_peso</div>';
				return false;
			}
            
			if( $tipo == 'image' ){
				
				$ficheroTmp = $_FILES['imgNueva']['tmp_name'];
	            $fichero    = $_FILES['imgNueva']['name'];
	
	            while ( file_exists($dir.$aleatorio) ) { $aleatorio = $this->util->aleatorio() ; }
	            
	            $extension 	= end(explode(".", $fichero));
	            $fichero 	= $aleatorio.".".$extension;
	            
	            move_uploaded_file ($ficheroTmp , $dir.$fichero);
	            
	            //echo $fichero;
	            return $fichero;
			}
			else{
				echo '<div id="mensaje" name="mensaje">error_tipo</div>';
				return false;
			}

        }else{
        	echo '<div id="mensaje">error_carga</div>';
            return false;
        }

    }
	function imagenServidorIndice(){
        $dir        = "../img/avatar/";
        $aux        = 0;

        if ( is_uploaded_file ($_FILES['imgNueva']['tmp_name']) )
        {
            $tipo = substr($_FILES['imgNueva']['type'], 0, 5);
			
			if( $tipo == 'image' ){
				
				$ficheroTmp = $_FILES['imgNueva']['tmp_name'];
	            $fichero    = "_".$_FILES['imgNueva']['name'];
	
	            while ( file_exists($dir.$aux.$fichero) ) { $aux ++; }
	            $fichero = $aux . $fichero;
	
	            move_uploaded_file ($ficheroTmp , $dir.$fichero);
	            
	            return $fichero;
			}
			else{
				echo '<div id="mensaje">error_tipo</div>';
				return false;
			}

        }else{
        	echo '<div id="mensaje">error_carga</div>';
            return false;
        }

    }
	function imagenEmpresaServidorIndice(){
        $dir        = "../img/empresa/";
        $aux        = 0;

        if ( is_uploaded_file ($_FILES['imgNueva']['tmp_name']) )
        {
            $tipo = substr($_FILES['imgNueva']['type'], 0, 5);
			
			if( $tipo == 'image' ){
				
				$ficheroTmp = $_FILES['imgNueva']['tmp_name'];
	            $fichero    = "_".$_FILES['imgNueva']['name'];
	
	            while ( file_exists($dir.$aux.$fichero) ) { $aux ++; }
	            $fichero = $aux . $fichero;
	
	            move_uploaded_file ($ficheroTmp , $dir.$fichero);
	            
	            return $fichero;
			}
			else{
				echo '<div id="mensaje">error_tipo</div>';
				return false;
			}

        }else{
        	echo '<div id="mensaje">error_carga</div>';
            return false;
        }

    }
	function actualizaImagen(){
				
        $usuario = $this->dataUsuario->getForId(  $_POST['idUsuario']  );
        
        // Si se modifica o no la imagen...
        if ( is_uploaded_file ($_FILES['imgNueva']['tmp_name'])  )
        {  
        	$fichero = $this->imagenServidor();
						
			if( $fichero != false ){
				
				$this->eliminarImagen( $usuario->imagen );
				
				$this->dataUsuario->updateImagen( $usuario->id, $fichero );
				
				$this->muestraImgActual( $usuario->id );
			}
		}
        else
        {
            echo $_POST['imagenActual'];
			return $_POST['imagenActual'];
        }
        
    }
	function actualizaImagenEmpresa(){

        $empresa = $this->dataEmpresa->getForId(  $_POST['idEmpresa']  );

        // Si se modifica o no la imagen...
        if ( is_uploaded_file ($_FILES['imgNueva']['tmp_name'])  )
        {  	
			$fichero = $this->imagenEmpresaServidor();
			
			if( $fichero != false ){
				
				$this->eliminarImagenEmpresa( $empresa->imagen );
				
				$this->dataEmpresa->updateImagen( $empresa->id, $fichero );
				
				$this->muestraImgActualEmpresa( $empresa->id );
			}
		}
        else
        {
            echo $_POST['imagenActual'];
			return $_POST['imagenActual'];
        }
        
    }
    function eliminarImagen( $img ){

        if( $img != "" && $img != "default.png") @unlink('../img/avatar/'.$img);

    }
	function eliminarImagenEmpresa( $img ){

        if( $img != "" && $img != "default.png") @unlink('../img/empresa/'.$img);

    }
	function muestraImgActual( $idUsuario ){
		
		$imagen = $this->dataUsuario->getImgForId( $idUsuario );
		echo '<body><html><div id="mensaje">'.$imagen.'</div></html></body>';

	}
	function muestraImgActualEmpresa( $idEmpresa ){
		
		$imagen = $this->dataEmpresa->getImgForId( $idEmpresa );
		echo '<body><html><div id="mensaje">'.$imagen.'</div></html></body>';

	}
	function resizejpg($imgsrc,$imgnew,$newx=130,$newy=100,$quality=50){  
		
		if( file_exists($imgsrc) ){  
			list($srcx,$srcy,$ext) = getimagesize($imgsrc);
		   	switch( $ext){	
				case 1 : 	
					$old = imagecreatefromgif($imgsrc); 		//gif
					$img = imagecreate($srcx,$srcy);			//Crea una imagen 									
					imagecolorallocate($img, 255, 255, 255); 	//Fondo blanco
					imagecopy($img,$old,0,0,0,0,$srcx,$srcy); 
					break;
					
		   		case 2 :
					$img = imagecreatefromjpeg($imgsrc); //jpg 
					break;		   		
					
				case 3 : 	
					$img = imagecreatefrompng($imgsrc); //png
					
					break;	
				//case 6:     
					$img = imagecreatefromwbmp($imgsrc);  
					break;
					
		   		default: 
					print_r(getimagesize($imgsrc)); 
					return false;
		   	} 	   
	 
		   	$tamx=$srcx;  	//Tamaño original X
		   	$tamy=$srcy; 	//Tamaño original Y
		   	if($srcx>$newx)$pv=($srcx>$srcy)?$srcx/$newx:$srcy/$newy; 
		   	elseif($srcy>$newy)$pv=($srcy>$srcx)?$srcy/$newy:$srcx/$newx; 
		   	if(isset($pv)){	$srcx=ceil($srcx/$pv); 	$srcy=ceil($srcy/$pv); 	}	   
	 
		   	$new = imagecreatetruecolor ($srcx, $srcy); 	 
		   	imagecopyresampled ($new, $img, 0, 0, 0, 0, $srcx, $srcy, $tamx, $tamy);  	        
		   	imagejpeg($new,(substr($imgnew,0,strrpos($imgnew,"."))).".jpg",$quality); 
		   	imagedestroy($img);	   
		   	return true;
		}
		else return false;
	}
	
	// Obtener id para asignar *************************************
	function getTotalIdMasUno($campo, $tabla){
        
		$consulta = new Consulta(  'SELECT MAX('.$campo.') AS maximo FROM '.$tabla,   $this->bd );
		
        if( $consulta->numResultados  )
        {
            return $consulta->tablaResultados[0]->maximo+1;
        }
        
    }
	
}

?>