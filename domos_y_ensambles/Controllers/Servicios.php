<?php 
	class Servicios extends Controllers{
		public function __construct()
		{
			parent::__construct();
			session_start();
			if(empty($_SESSION['login']))
			{
				header('Location: '.base_url().'/login');
				die();
			}
			getPermisos(MSERVICIOS);
		}

		public function Servicios()
		{
			if(empty($_SESSION['permisosMod']['r'])){
				header("Location:".base_url().'/dashboard');
			}
			$data['page_tag'] = "Servicios";
			$data['page_title'] = "SERVICIOS <small> Domos y Ensambles</small>";
			$data['page_name'] = "Servicios";
			$data['page_functions_js'] = "functions_servicios.js";
			$this->views->getView($this,"servicios",$data);
		}

		public function getServicios()
		{
			if($_SESSION['permisosMod']['r']){
				$arrData = $this->model->selectServicios();
				for ($i=0; $i < count($arrData); $i++) {
					$btnView = '';
					$btnEdit = '';
					$btnDelete = '';

					if($arrData[$i]['status'] == 1)
					{
						$arrData[$i]['status'] = '<span class="badge badge-success">Activo</span>';
					}else{
						$arrData[$i]['status'] = '<span class="badge badge-danger">Inactivo</span>';
					}

					if($_SESSION['permisosMod']['r']){
						$btnView = '<button class="btn btn-info btn-sm" onClick="fntViewInfo('.$arrData[$i]['idservicio'].')" title="Ver servicio"><i class="far fa-eye"></i></button>';
					}
					if($_SESSION['permisosMod']['u']){
						$btnEdit = '<button class="btn btn-primary  btn-sm" onClick="fntEditInfo(this,'.$arrData[$i]['idservicio'].')" title="Editar servicio"><i class="fas fa-pencil-alt"></i></button>';
					}
					if($_SESSION['permisosMod']['d']){	
						$btnDelete = '<button class="btn btn-danger btn-sm" onClick="fntDelInfo('.$arrData[$i]['idservicio'].')" title="Eliminar servicio"><i class="far fa-trash-alt"></i></button>';
					}
					$arrData[$i]['options'] = '<div class="text-center">'.$btnView.' '.$btnEdit.' '.$btnDelete.'</div>';
				}
				echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
			}
			die();
		}

		public function setServicio(){
			if($_POST){
				if(empty($_POST['txtNombre']) || empty($_POST['txtCodigo']) || empty($_POST['listCategoria']) || empty($_POST['listStatus']) )
				{
					$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
				}else{
					
					$idServicio = intval($_POST['idServicio']);
					$strNombre = strClean($_POST['txtNombre']);
					$strDescripcion = strClean($_POST['txtDescripcion']);
					$strCodigo = strClean($_POST['txtCodigo']);
					$intCategoriaId = intval($_POST['listCategoria']);
					$intStatus = intval($_POST['listStatus']);
					$request_servicio = "";

					$ruta = strtolower(clear_cadena($strNombre));
					$ruta = str_replace(" ","-",$ruta);

					if($idServicio == 0)
					{
						$option = 1;
						if($_SESSION['permisosMod']['w']){
							$request_servicio = $this->model->insertServicio($strNombre, 
																		$strDescripcion, 
																		$strCodigo, 
																		$intCategoriaId,
																		$ruta,
																		$intStatus );
						}
					}else{
						$option = 2;
						if($_SESSION['permisosMod']['u']){
							$request_servicio = $this->model->updateServicio($idServicio,
																		$strNombre,
																		$strDescripcion, 
																		$strCodigo, 
																		$intCategoriaId,
																		$ruta,
																		$intStatus);
						}
					}
					if($request_servicio > 0 )
					{
						if($option == 1){
							$arrResponse = array('status' => true, 'idservicio' => $request_servicio, 'msg' => 'Datos guardados correctamente.');
						}else{
							$arrResponse = array('status' => true, 'idservicio' => $idServicio, 'msg' => 'Datos Actualizados correctamente.');
						}
					}else if($request_servicio == 'exist'){
						$arrResponse = array('status' => false, 'msg' => '¡Atención! ya existe un servicio con el Código Ingresado.');		
					}else{
						$arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
					}
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}
			die();
		}

		public function getServicio($idservicio){
			if($_SESSION['permisosMod']['r']){
				$idservicio = intval($idservicio);
				if($idservicio > 0){
					$arrData = $this->model->selectServicio($idservicio);
					if(empty($arrData)){
						$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
					}else{
						$arrImg = $this->model->selectImages($idservicio);
						if(count($arrImg) > 0){
							for ($i=0; $i < count($arrImg); $i++) { 
								$arrImg[$i]['url_image'] = media().'/images/uploads/'.$arrImg[$i]['img'];
							}
						}
						$arrData['images'] = $arrImg;
						$arrResponse = array('status' => true, 'data' => $arrData);
					}
					echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
				}
			}
			die();
		}

		public function setImage(){
			if($_POST){
				if(empty($_POST['idservicio'])){
					$arrResponse = array('status' => false, 'msg' => 'Error de dato.');
				}else{
					$idServicio = intval($_POST['idservicio']);
					$foto      = $_FILES['foto'];
					$imgNombre = 'pro_'.md5(date('d-m-Y H:i:s')).'.jpg';
					$request_image = $this->model->insertImage($idServicio,$imgNombre);
					if($request_image){
						$uploadImage = uploadImage($foto,$imgNombre);
						$arrResponse = array('status' => true, 'imgname' => $imgNombre, 'msg' => 'Archivo cargado.');
					}else{
						$arrResponse = array('status' => false, 'msg' => 'Error de carga.');
					}
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}
			die();
		}

		public function delFile(){
			if($_POST){
				if(empty($_POST['idservicio']) || empty($_POST['file'])){
					$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
				}else{
					//Eliminar de la DB
					$idServicio = intval($_POST['idservicio']);
					$imgNombre  = strClean($_POST['file']);
					$request_image = $this->model->deleteImage($idServicio,$imgNombre);

					if($request_image){
						$deleteFile =  deleteFile($imgNombre);
						$arrResponse = array('status' => true, 'msg' => 'Archivo eliminado');
					}else{
						$arrResponse = array('status' => false, 'msg' => 'Error al eliminar');
					}
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}
			die();
		}

		public function delServicio(){
			if($_POST){
				if($_SESSION['permisosMod']['d']){
					$intIdservicio = intval($_POST['idServicio']);
					$requestDelete = $this->model->deleteServicio($intIdservicio);
					if($requestDelete)
					{
						$arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el servicio');
					}else{
						$arrResponse = array('status' => false, 'msg' => 'Error al eliminar el servicio.');
					}
					echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
				}
			}
			die();
		}
	}

 ?>