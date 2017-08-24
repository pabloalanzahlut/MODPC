<?php


Class Cliente{
	private $id;
	private $nombre;
	private $usuario;
	private $apellido;
	private $telefono;
	private $email;
	private $fechaNacimiento;
	private $pass;
	private $codPostal;
	private $domicilio;
	private $admin;
	private $localidad;

	function __construct($id, 
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
						$localidad){
		$this->id = $id;
		$this->nombre = $nombre;
		$this->usuario = $usuario;
		$this->apellido = $apellido;
		$this->telefono = $telefono;
		$this->email = $email;
		$this->fechaNacimiento = $fechaNacimiento;
		$this->pass = $pass;
		$this->codPostal = $codPostal;
		$this->domicilio = $domicilio;
		$this->admin = $admin;
		$this->localidad = $localidad;

	}

	public static function obtenerClientePorId($id){

	}
	
	public static function cargarLocalidades($conexion, $idProvincia){
		//se prepara consulta para la base de datos
		$consulta = "SELECT id_localidad,
						localidad
						FROM localidad
						WHERE id_provincia = ?";
		//SE REALIZA EL PREPARE DE LA CONSULTA CON LA CONEXION
		//$stmt = $con->prepare($consulta);
		$stmt = mysqli_prepare($conexion, $consulta);
		//BINDEO DE DATOS
		mysqli_stmt_bind_param($stmt, "i", $idProvincia);
		//EJECUCION DE LA CONSULTA
		mysqli_stmt_execute($stmt);
		//CAPTURA DEL RESULTADO
		$resultado = mysqli_stmt_get_result($stmt);

		$output = array();
		//ARMADO DEL ARRAY PARA RETORNO DE LA FUNCION
		while ($fila = mysqli_fetch_assoc($resultado)) {
			$fila['id_localidad'] = (int)$fila['id_localidad'];
			$fila['localidad'] = utf8_encode($fila['localidad']);
			$output[] = $fila;
		}

		return $output;
	}

	public static function listarEmail($conexion){
		$consulta = "SELECT email
					FROM cliente";

		$resultado = mysqli_query($conexion, $consulta);
		$output = array();
		while($fila = mysqli_fetch_assoc($resultado)){
			$output[] = $fila;
		}
		return $output;
	}

	function getEmail(){
		return $this->email;
	}

	function persistirse($conexion){
		$consulta = "INSERT INTO cliente 
					(username,
					email,
					pass,
					telefono,
					nombre,
					apellido,
					cod_postal,
					domicilio,
					admin,
					fecha_nacimiento,
					id_localidad) VALUES 
					(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

		$stmt = mysqli_prepare($conexion, $consulta);

		//ssssssisidi
		mysqli_stmt_bind_param($stmt, "ssssssisisi", 
								$this->usuario,
								$this->email,
								$this->pass,
								$this->telefono,
								$this->nombre,
								$this->apellido,
								$this->codPostal,
								$this->domicilio,
								$this->admin,
								$this->fechaNacimiento, 
								$this->localidad);

		mysqli_stmt_execute($stmt);

		//para obtener el ultimo id autogenerado
		$id = mysqli_insert_id($conexion);
		return $id;
	}

	function getArraySession($conexion, $id){
		$sql = "SELECT id,
						username usuario,
						email,
						pass,
						telefono,
						nombre,
						apellido,
						cod_postal codPostal,
						domicilio,
						admin,
						fecha_nacimiento fechaNacimiento,
						id_localidad idLocalidad
				FROM cliente
				WHERE id = '". $id ."';";

		$resultado = mysqli_query($conexion, $sql);
		$fila = mysqli_fetch_assoc($resultado);
		return $fila;
	}

	public static function consultarCliente($conexion, $email, $pass){
		$consulta = "SELECT id,
							username,
							email,
							pass,
							telefono,
							nombre,
							apellido,
							cod_postal codPostal,
							domicilio,
							admin,
							fecha_nacimiento fechaNacimiento,
							id_localidad idLocalidad
					FROM cliente
					WHERE email = ? and pass = ?";

		$stmt = mysqli_prepare($conexion, $consulta);
		mysqli_stmt_bind_param($stmt, "ss", $email,
											$pass);
		mysqli_stmt_execute($stmt);

		$respuesta = mysqli_stmt_fetch($stmt);
		return $respuesta;
	}

	public static function obtenerCliente($conexion, $email, $pass){
		$consulta = "SELECT id,
							username,
							email,
							pass,
							telefono,
							nombre,
							apellido,
							cod_postal codPostal,
							domicilio,
							admin,
							fecha_nacimiento fechaNacimiento,
							id_localidad idLocalidad
					FROM cliente
					WHERE email = ? and pass = ?";

		$stmt = mysqli_prepare($conexion, $consulta);
		mysqli_stmt_bind_param($stmt, "ss", $email,
											$pass);
		mysqli_stmt_execute($stmt);
		$resultado = mysqli_stmt_get_result($stmt);
		$respuesta = mysqli_fetch_assoc($resultado);
		return $respuesta;
	}
}


?>