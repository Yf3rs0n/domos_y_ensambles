<?php 

	class Login extends Controllers{
		public function __construct()
		{
			session_start();
			if(isset($_SESSION['login']))
			{
				header('Location: '.base_url().'/dashboard');
				die();
			}
			parent::__construct();
		}

		public function login()
		{
			$data['page_tag'] = "Login - Domos y Ensambles";
			$data['page_title'] = "Domos y Ensambles";
			$data['page_name'] = "login";
			$data['page_functions_js'] = "functions_login.js";
			$this->views->getView($this,"login",$data);
		}

		public function loginUser(){
			//dep($_POST);
			if($_POST){
				if(empty($_POST['txtEmail']) || empty($_POST['txtPassword'])){//VALIDAMOS QUE NO ESTEN VACIOS
					$arrResponse = array('status' => false, 'msg' => 'Error de datos' );
				}else{
					$strUsuario  =  strtolower(strClean($_POST['txtEmail']));//CONVERTIMOS EL EMAIL A MINUSCULAS Y LIMPIAMOS PARA TENER UNA CADENA PURA
					$strPassword = hash("SHA256",$_POST['txtPassword']);//ENCRIPTAMOS LA CONTRASEÑA CON EL ALGORITMO SHA256 Y EN LA BD SE GUARDA EN SHA256
					$requestUser = $this->model->loginUser($strUsuario, $strPassword);//BUSCAMOS EL USUARIO EN LA BD POR MEDERIO DE EL DEL METODO loginUser
					if(empty($requestUser)){
						$arrResponse = array('status' => false, 'msg' => 'El usuario o la contraseña es incorrecto.' ); 
					}else{
						$arrData = $requestUser;//GUARDAMOS EL USUARIO EN UN ARRAY
						if($arrData['status'] == 1){// SI EL STATUS ES 1 ES QUE ESTA ACTIVO
							$_SESSION['idUser'] = $arrData['idpersona'];//GUARDAMOS EL ID DEL USUARIO EN UNA SESION
							$_SESSION['login'] = true;

							$arrData = $this->model->sessionLogin($_SESSION['idUser']);
							sessionUser($_SESSION['idUser']);							
							$arrResponse = array('status' => true, 'msg' => 'ok');//SI TODO ESTA BIEN RETORNAMOS UN ARRAY CON EL STATUS Y EL MENSAJE QUE INICIO CORRECTAMENTE
						}else{
							$arrResponse = array('status' => false, 'msg' => 'Usuario inactivo.');
						}
					}
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);//CONVERTIMOS EL FORMATO DE JSON EL ARRAY QUE ESTAMOS CRREANDO PARA RETORNALO HACIA EL ARCHIVO DE FUNCIONES JS 
			}
			die();
		}

		public function resetPass(){
			if($_POST){//SI EXISTE UN POST
				error_reporting(0);

				if(empty($_POST['txtEmailReset'])){
					$arrResponse = array('status' => false, 'msg' => 'Error de datos' );
				}else{
					$token = token();//GENERAMOS UN TOKEN POR MEDIO DE LA FUNCION token() EN HELPERS
					$strEmail  =  strtolower(strClean($_POST['txtEmailReset']));//CONVERTIMOS EL EMAIL A MINUSCULAS Y LIMPIAMOS PARA TENER UNA CADENA PURA
					$arrData = $this->model->getUserEmail($strEmail);//BUSCAMOS EL USUARIO EN LA BD POR MEDERIO DE EL DEL METODO getUserEmail

					if(empty($arrData)){
						$arrResponse = array('status' => false, 'msg' => 'Usuario no existente.' ); 
					}else{
						$idpersona = $arrData['idpersona'];
						$nombreUsuario = $arrData['nombres'].' '.$arrData['apellidos'];//GUARDAMOS EN UNA VARIABLE EL NOMBRE DE USUARIO CONCATEANDO LOS NOMBRES Y APELLIDOS

						$url_recovery = base_url().'/login/confirmUser/'.$strEmail.'/'.$token;//GENERAMOS LA URL PARA RECUPERAR LA CONTRASEÑA
						$requestUpdate = $this->model->setTokenUser($idpersona,$token);//VALIDAMOS QUE EL TOKEN NO EXISTA Y LO GUARDAMOS EN LA BD

						$dataUsuario = array('nombreUsuario' => $nombreUsuario,//GUARDAMOS EN UN ARRAY LOS DATOS QUE NECESITAMOS PARA ENVIAR EL EMAIL
											 'email' => $strEmail,
											 'asunto' => 'Recuperar cuenta - '.NOMBRE_REMITENTE,//VARIABLE 
											 'url_recovery' => $url_recovery);
						if($requestUpdate){
							$sendEmail = sendEmail($dataUsuario,'email_cambioPassword');//ENVIAMOS EL ARRAY A LA FUNCION DE ENVIO DE EMAIL EN EL HELPER Y LE INDICAMOS LA PLATILLA QUE UTILIZAREMOS

							if($sendEmail){
								$arrResponse = array('status' => true, 
												 'msg' => 'Se ha enviado un email a tu cuenta de correo para cambiar tu contraseña.');
							}else{
								$arrResponse = array('status' => false, 
												 'msg' => 'No es posible realizar el proceso, intenta más tarde.' );
							}
						}else{
							$arrResponse = array('status' => false, 
												 'msg' => 'No es posible realizar el proceso, intenta más tarde.' );
						}
					}
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}
			die();
		}

		public function confirmUser(string $params){

			if(empty($params)){
				header('Location: '.base_url());
			}else{
				$arrParams = explode(',',$params);
				$strEmail = strClean($arrParams[0]);
				$strToken = strClean($arrParams[1]);
				$arrResponse = $this->model->getUsuario($strEmail,$strToken);
				if(empty($arrResponse)){
					header("Location: ".base_url());
				}else{
					$data['page_tag'] = "Cambiar contraseña";
					$data['page_name'] = "cambiar_contrasenia";
					$data['page_title'] = "Cambiar Contraseña";
					$data['email'] = $strEmail;
					$data['token'] = $strToken;
					$data['idpersona'] = $arrResponse['idpersona'];
					$data['page_functions_js'] = "functions_login.js";
					$this->views->getView($this,"cambiar_password",$data);
				}
			}
			die();
		}

		public function setPassword(){

			if(empty($_POST['idUsuario']) || empty($_POST['txtEmail']) || empty($_POST['txtToken']) || empty($_POST['txtPassword']) || empty($_POST['txtPasswordConfirm'])){

					$arrResponse = array('status' => false, 
										 'msg' => 'Error de datos' );
				}else{
					$intIdpersona = intval($_POST['idUsuario']);
					$strPassword = $_POST['txtPassword'];
					$strPasswordConfirm = $_POST['txtPasswordConfirm'];
					$strEmail = strClean($_POST['txtEmail']);
					$strToken = strClean($_POST['txtToken']);

					if($strPassword != $strPasswordConfirm){
						$arrResponse = array('status' => false, 
											 'msg' => 'Las contraseñas no son iguales.' );
					}else{
						$arrResponseUser = $this->model->getUsuario($strEmail,$strToken);
						if(empty($arrResponseUser)){
							$arrResponse = array('status' => false, 
											 'msg' => 'Erro de datos.' );
						}else{
							$strPassword = hash("SHA256",$strPassword);
							$requestPass = $this->model->insertPassword($intIdpersona,$strPassword);

							if($requestPass){
								$arrResponse = array('status' => true, 
													 'msg' => 'Contraseña actualizada con éxito.');
							}else{
								$arrResponse = array('status' => false, 
													 'msg' => 'No es posible realizar el proceso, intente más tarde.');
							}
						}
					}
				}
			echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			die();
		}

	}
 ?>