<?php 
require_once("Libraries/Core/Mysql.php");
trait TCliente{
	private $con;
	private $intIdUsuario;
	private $strNombre;
	private $strApellido;
	private $intTelefono;
	private $strEmail;
	private $strPassword;
	private $strToken;
	private $intTipoId;
	private $intIdTransaccion;

	public function insertCliente(string $nombre, string $apellido, int $telefono, string $email, string $password, int $tipoid){
		$this->con = new Mysql();
		$this->strNombre = $nombre;
		$this->strApellido = $apellido;
		$this->intTelefono = $telefono;
		$this->strEmail = $email;
		$this->strPassword = $password;
		$this->intTipoId = $tipoid;

		$return = 0;
		$sql = "SELECT * FROM persona WHERE 
				email_user = '{$this->strEmail}' ";
		$request = $this->con->select_all($sql);

		if(empty($request))
		{
			$query_insert  = "INSERT INTO persona(nombres,apellidos,telefono,email_user,password,rolid) 
							  VALUES(?,?,?,?,?,?)";
        	$arrData = array($this->strNombre,
    						$this->strApellido,
    						$this->intTelefono,
    						$this->strEmail,
    						$this->strPassword,
    						$this->intTipoId);
        	$request_insert = $this->con->insert($query_insert,$arrData);
        	$return = $request_insert;
		}else{
			$return = "exist";
		}
        return $return;
	}

	public function insertContrato(int $personaid, float $costo_envio, string $monto, int $tipopagoid, string $direccionenvio,string $materiales, string $detalles, string $status){
		$this->con = new Mysql();
		$query_insert  = "INSERT INTO contrato(personaid,costo_envio,monto,tipopagoid,direccion_envio,materiales,detalles,status) 
							  VALUES(?,?,?,?,?,?,?,?)";
		$arrData = array($personaid,
    						$costo_envio,
    						$monto,
    						$tipopagoid,
    						$direccionenvio,
							$materiales,
							$detalles,
    						$status
    					);
		$request_insert = $this->con->insert($query_insert,$arrData);
	    $return = $request_insert;
	    return $return;
	}

	public function insertDetalle(int $idcontrato, int $servicioid, int $cantidad){
		$this->con = new Mysql();
		$query_insert  = "INSERT INTO detalle_contrato(contratoid,servicioid,cantidad) 
							  VALUES(?,?,?)";
		$arrData = array($idcontrato,
    					$servicioid,
						$cantidad
					);
		$request_insert = $this->con->insert($query_insert,$arrData);
	    $return = $request_insert;
	    return $return;
	}

	public function insertDetalleTemp(array $contrato){
		$this->intIdUsuario = $contrato['idcliente'];
		$this->intIdTransaccion = $contrato['idtransaccion'];
		$servicios = $contrato['servicios'];

		$this->con = new Mysql();
		$sql = "SELECT * FROM detalle_temp WHERE 
					transaccionid = '{$this->intIdTransaccion}' AND 
					personaid = $this->intIdUsuario";
		$request = $this->con->select_all($sql);

		if(empty($request)){
			foreach ($servicios as $servicio) {
				$query_insert  = "INSERT INTO detalle_temp(personaid,servicioid,precio,cantidad,transaccionid) 
								  VALUES(?,?,?,?,?)";
	        	$arrData = array($this->intIdUsuario,
	        					$servicio['idservicio'],
	    						$servicio['precio'],
	    						$servicio['cantidad'],
	    						$this->intIdTransaccion
	    					);
	        	$request_insert = $this->con->insert($query_insert,$arrData);
			}
		}else{
			$sqlDel = "DELETE FROM detalle_temp WHERE 
				transaccionid = '{$this->intIdTransaccion}' AND 
				personaid = $this->intIdUsuario";
			$request = $this->con->delete($sqlDel);
			foreach ($servicios as $servicio) {
				$query_insert  = "INSERT INTO detalle_temp(personaid,servicioid,precio,cantidad,transaccionid) 
								  VALUES(?,?,?,?,?)";
	        	$arrData = array($this->intIdUsuario,
	        					$servicio['idservicio'],
	    						$servicio['precio'],
	    						$servicio['cantidad'],
	    						$this->intIdTransaccion
	    					);
	        	$request_insert = $this->con->insert($query_insert,$arrData);
			}
		}
	}

	public function getContrato(int $idcontrato){
		$this->con = new Mysql();
		$request = array();
		$sql = "SELECT p.idcontrato,
							p.personaid,
							p.fecha,
							p.costo_envio,
							p.monto,
							p.tipopagoid,
							t.tipopago,
							p.direccion_envio,
							p.materiales,
							p.detalles,
							p.status
					FROM contrato as p
					INNER JOIN tipopago t
					ON p.tipopagoid = t.idtipopago
					WHERE p.idcontrato =  $idcontrato";
		$requestContrato = $this->con->select($sql);
		if(count($requestContrato) > 0){
			$sql_detalle = "SELECT p.idservicio,
											p.nombre as servicio,
											d.cantidad
									FROM detalle_contrato d
									INNER JOIN servicio p
									ON d.servicioid = p.idservicio
									WHERE d.contratoid = $idcontrato
									";
			$requestServicios = $this->con->select_all($sql_detalle);
			$request = array('orden' => $requestContrato,
							'detalle' => $requestServicios
							);
		}
		return $request;
	}

	public function setSuscripcion(string $nombre, string $email){
		$this->con = new Mysql();
		$sql = 	"SELECT * FROM suscripciones WHERE email = '{$email}'";
		$request = $this->con->select_all($sql);
		if(empty($request)){
			$query_insert  = "INSERT INTO suscripciones(nombre,email) 
							  VALUES(?,?)";
			$arrData = array($nombre,$email);
			$request_insert = $this->con->insert($query_insert,$arrData);
			$return = $request_insert;
		}else{
			$return = false;
		}
		return $return;
	}

	public function setContacto(string $nombre, string $email, string $mensaje, string $ip, string $dispositivo, string $useragent){
		$this->con = new Mysql();
		$nombre  	 = $nombre != "" ? $nombre : ""; 
		$email 		 = $email != "" ? $email : ""; 
		$mensaje	 = $mensaje != "" ? $mensaje : ""; 
		$ip 		 = $ip != "" ? $ip : ""; 
		$dispositivo = $dispositivo != "" ? $dispositivo : ""; 
		$useragent 	 = $useragent != "" ? $useragent : ""; 
		$query_insert  = "INSERT INTO contacto(nombre,email,mensaje,ip,dispositivo,useragent) 
						  VALUES(?,?,?,?,?,?)";
		$arrData = array($nombre,$email,$mensaje,$ip,$dispositivo,$useragent);
		$request_insert = $this->con->insert($query_insert,$arrData);
		return $request_insert;
	}
}

 ?>