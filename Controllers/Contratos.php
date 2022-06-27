<?php 
require_once("Models/TTipoPago.php"); 
class Contratos extends Controllers{
	use TTipoPago;
	public function __construct()
	{
		parent::__construct();
		session_start();
		if(empty($_SESSION['login']))
		{
			header('Location: '.base_url().'/login');
			die();
		}
		getPermisos(MCONTRATOS);
	}

	public function Contratos()
	{
		if(empty($_SESSION['permisosMod']['r'])){
			header("Location:".base_url().'/dashboard');
		}
		$data['page_tag'] = "Contratos";
		$data['page_title'] = "CONTRATOS <small> Domos y Ensambles</small>";
		$data['page_name'] = "Contratos";
		$data['page_functions_js'] = "functions_contratos.js";
		$this->views->getView($this,"contratos",$data);
	}

	public function getContratos(){
		if($_SESSION['permisosMod']['r']){
			$idpersona = "";
			if( $_SESSION['userData']['idrol'] == RCLIENTES ){
				$idpersona = $_SESSION['userData']['idpersona'];
			}
			$arrData = $this->model->selectContratos($idpersona);
			//dep($arrData);
			for ($i=0; $i < count($arrData); $i++) {
				$btnView = '';
				$btnEdit = '';
				$btnDelete = '';

				$arrData[$i]['monto'] = SMONEY.formatMoney($arrData[$i]['monto']);

				
				if($_SESSION['permisosMod']['r']){
					
					$btnView .= ' <a title="Ver Detalle" href="'.base_url().'/contratos/orden/'.$arrData[$i]['idcontrato'].'" target="_blanck" class="btn btn-info btn-sm"> <i class="far fa-eye"></i> </a>

						<a title="Generar PDF" href="'.base_url().'/factura/generarFactura/'.$arrData[$i]['idcontrato'].'" target="_blanck" class="btn btn-danger btn-sm"> <i class="fas fa-file-pdf"></i> </a> ';

				}
				if($_SESSION['permisosMod']['u']){
					$btnEdit = '<button class="btn btn-primary  btn-sm" onClick="fntEditInfo(this,'.$arrData[$i]['idcontrato'].')" title="Editar contrato"><i class="fas fa-pencil-alt"></i></button>';
				}
				$arrData[$i]['options'] = '<div class="text-center">'.$btnView.' '.$btnEdit.' '.$btnDelete.'</div>';
			}
			echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	public function orden($idcontrato){
		if(!is_numeric($idcontrato)){
			header("Location:".base_url().'/contratos');
		}
		if(empty($_SESSION['permisosMod']['r'])){
			header("Location:".base_url().'/dashboard');
		}
		$idpersona = "";
		if( $_SESSION['userData']['idrol'] == RCLIENTES ){
			$idpersona = $_SESSION['userData']['idpersona'];
		}
		
		$data['page_tag'] = "Factura";
		$data['page_title'] = "CONTRATO <small> Domos y Ensambles</small>";
		$data['page_name'] = "Factura";
		$data['arrContrato'] = $this->model->selectContrato($idcontrato,$idpersona);
		$this->views->getView($this,"orden",$data);
	}


	public function getContrato(string $contrato){
		if($_SESSION['permisosMod']['u'] and $_SESSION['userData']['idrol'] != RCLIENTES){
			if($contrato == ""){
				$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
			}else{
				$requestContrato = $this->model->selectContrato($contrato,"");
				if(empty($requestContrato)){
					$arrResponse = array("status" => false, "msg" => "Datos no disponibles.");
				}else{
					$requestContrato['tipospago'] = $this->getTiposPagoT();
					$htmlModal = getFile("Template/Modals/modalContrato",$requestContrato);
					$arrResponse = array("status" => true, "html" => $htmlModal);
				}
			}
			echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	public function setContrato(){
		if($_POST){
			if($_SESSION['permisosMod']['u'] and $_SESSION['userData']['idrol'] != RCLIENTES){

				$idcontrato = !empty($_POST['idcontrato']) ? intval($_POST['idcontrato']) : "";
				$monto= !empty($_POST['monto']) ? intval($_POST['monto']) : "";
				$estado = !empty($_POST['listEstado']) ? strClean($_POST['listEstado']) : "";
				$idtipopago =  !empty($_POST['listTipopago']) ? intval($_POST['listTipopago']) : "";


				if($idcontrato == ""){
					$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
				}else{
					if($idtipopago == ""){
						if($estado == ""){
							$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
						}else{
							$requestContrato = $this->model->updateContrato($idcontrato,"","",$estado);
							if($requestContrato){
								$arrResponse = array("status" => true, "msg" => "Datos actualizados correctamente");
							}else{
								$arrResponse = array("status" => false, "msg" => "No es posible actualizar la información.");
							}
						}
					}else{
						if($idtipopago =="" or $estado == ""){
							$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
						}else{
							$requestContrato = $this->model->updateContrato($idcontrato,$monto,$idtipopago,$estado);
							if($requestContrato){
								$arrResponse = array("status" => true, "msg" => "Datos actualizados correctamente");
							}else{
								$arrResponse = array("status" => false, "msg" => "No es posible actualizar la información.");
							}
						}
					}
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}
		}
		die();
	}
}
 ?>