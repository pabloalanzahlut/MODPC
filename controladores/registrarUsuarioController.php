<?php 
require_once('../clases/ConexionBDClass.php');
require_once('../clases/ClienteClass.php');

error_reporting('E_ALL ^ E_NOTICE');

//toma de datos desde llamado ajax
$data = json_decode(file_get_contents('php://input'));

$id = Null;
$nombre = strip_tags($data->nombre);
$usuario = strip_tags($data->usuario);
$apellido = strip_tags($data->apellido);
$telefono = strip_tags($data->telefono);
$fechaNacimiento = strip_tags($data->fechaNacimiento);
$email = strip_tags($data->email);
$domicilio = strip_tags($data->direccion);
$codPostal = (int)strip_tags($data->codPostal);
$localidad = (int)strip_tags($data->localidad);
$pass = md5($data->pass);
$admin = 0;


//inicializacion de conexion BD
$objetoConexion = new ConexionBD();
$conexion = $objetoConexion->getConexion();

//inicializacion de Cliente
$usuario = new Cliente($id,
						$nombre,
						$usuario,
						$apellido,
						$telefono,
						$email,
						$fechaNacimiento,
						$pass,
						$codPostal,
						$domicilio,
						$admin,
						$localidad);

//comprobacion de que no existe el email en la BD
$listaEmail = Cliente::listarEmail($conexion);
$emailSinRegistrar = true;
foreach ($listaEmail as $emailBD) {
	if($emailBD == $email){
		$emailSinRegistrar = false;
		break;
	}
}

//si no existe el email se realiza la persistencia de datos
$mensaje = array();
if($emailSinRegistrar){
	$idUsuario = $usuario->persistirse($conexion);
    ini_set('session.cookie_lifetime', "600");
    ini_set('session.hash_bits_per_character','4');
    ini_set('session.hash_function', 'sha256');
	session_start();
	$_SESSION['usuario'] = $usuario->getArraySession($conexion, $idUsuario);
	$mensaje = ['respuesta' => 1,];
	echo json_encode($mensaje);
}else{
	
	$mensaje = ['respuesta' => 0,];
	echo json_encode($mensaje);
}

?>