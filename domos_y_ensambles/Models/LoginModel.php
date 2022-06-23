<?php 

	class LoginModel extends Mysql
	{
		private $intIdUsuario;//ID DEL USUARIO
		private $strUsuario;//USUARIO
		private $strPassword;//PASSWORD
		private $strToken;//TOKEN DE RESETEO DE CONTRASEÑA

		public function __construct()
		{
			parent::__construct();
		}	

		public function loginUser(string $usuario, string $password)//
		{
			$this->strUsuario = $usuario;
			$this->strPassword = $password;
			$sql = "SELECT idpersona,status FROM persona WHERE 
					email_user = '$this->strUsuario' and 
					password = '$this->strPassword' and 
					status != 0 ";//USUARIO CON STATUS DIFERENTE DE 0 ESTAN ACTIVOS - LAS COMILLAS ES PORQUE LOS DATOS QUE INGRESAMOS SON VARCHAR
			$request = $this->select($sql);
			return $request;
		}

		public function sessionLogin(int $iduser){
			$this->intIdUsuario = $iduser;//ASIGNAMOS EL VALOR QUE ESTAMOS RECIBIENDO
			//BUSCAR ROLE  CON INNER JOIN
			//CAMBIAR VALIABLES DE LA TABLA PERSONA POR LAS QUE NECESITAMOS-------------------------------------
			$sql = "SELECT p.idpersona,
							p.identificacion,
							p.nombres,
							p.apellidos,
							p.telefono,
							p.email_user,
							p.nit,
							p.nombrefiscal,
							p.direccionfiscal,
							r.idrol,r.nombrerol,
							p.status 
					FROM persona p
					INNER JOIN rol r
					ON p.rolid = r.idrol
					WHERE p.idpersona = $this->intIdUsuario";//IGUAL A LA PROPIEDAD QUE ESTAMOS RECIBIENDO
			$request = $this->select($sql);
			$_SESSION['userData'] = $request;
			return $request;//Y RETORNAMOS EL VALOR AL CONTROLADOR
		}

		public function getUserEmail(string $strEmail){
			$this->strUsuario = $strEmail;
			$sql = "SELECT idpersona,nombres,apellidos,status FROM persona WHERE 
					email_user = '$this->strUsuario' and  
					status = 1 ";//USUARIOS ACTIVOS 
			$request = $this->select($sql);
			return $request;
		}

		public function setTokenUser(int $idpersona, string $token){
			$this->intIdUsuario = $idpersona;//ASIGNAMOS EL VALOR QUE ESTAMOS RECIBIENDO COMO PARAMETRO
			$this->strToken = $token;
			$sql = "UPDATE persona SET token = ? WHERE idpersona = $this->intIdUsuario ";//QUE EL idpersona SEA IGUAL A LA PROPIEDAD QUE ESTAMOS RECIBIENDO
			$arrData = array($this->strToken);
			$request = $this->update($sql,$arrData);
			return $request;//Y RETORNAMOS EL VALOR AL CONTROLADOR 
		}

		public function getUsuario(string $email, string $token){
			$this->strUsuario = $email;
			$this->strToken = $token;
			$sql = "SELECT idpersona FROM persona WHERE 
					email_user = '$this->strUsuario' and 
					token = '$this->strToken' and 					
					status = 1 ";
			$request = $this->select($sql);
			return $request;
		}

		public function insertPassword(int $idPersona, string $password){
			$this->intIdUsuario = $idPersona;
			$this->strPassword = $password;
			$sql = "UPDATE persona SET password = ?, token = ? WHERE idpersona = $this->intIdUsuario ";
			$arrData = array($this->strPassword,"");
			$request = $this->update($sql,$arrData);
			return $request;
		}
	}
 ?>