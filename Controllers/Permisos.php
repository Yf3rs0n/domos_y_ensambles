<?php 

	class Permisos extends Controllers{
		public function __construct()
		{
			parent::__construct();
		}

		public function getPermisosRol(int $idrol)
		{
			$rolid = intval($idrol);//intval convierte el valor a entero sin decimales
			if($rolid > 0)//VALIDAMOS QUE EL ID SEA MAYOR A 0 
			{
				$arrModulos = $this->model->selectModulos();//ACEMOS LA PETICION AL MODELO PARA OBTENER LOS MODULOS
				$arrPermisosRol = $this->model->selectPermisosRol($rolid);
				$arrRol = $this->model->getRol($rolid);
				$arrPermisos = array('r' => 0, 'w' => 0, 'u' => 0, 'd' => 0);//ARRAY VER LOS PERMISOS 
				$arrPermisoRol = array('idrol' => $rolid, 'rol' => $arrRol['nombrerol']);//ARRAY CON LOS DATOS DEL ROL

				if(empty($arrPermisosRol))
				{
					for ($i=0; $i < count($arrModulos) ; $i++) { //RECORREMOS EL ARRAY HASTA LA CANTIDAD DE ELEMENTOS QUE VA A TENER EL arrModulos

						$arrModulos[$i]['permisos'] = $arrPermisos;//ASIGNAMOS EL ARRAY DE PERMISOS A CADA MODULO
					}
				}else{
					for ($i=0; $i < count($arrModulos); $i++) {
						$arrPermisos = array('r' => 0, 'w' => 0, 'u' => 0, 'd' => 0);
						if(isset($arrPermisosRol[$i])){
							$arrPermisos = array('r' => $arrPermisosRol[$i]['r'],//A CADA ELEMENTO DEL ARRAY DE PERMISOS COMO VALOR LO QUE VA A TENER
												 'w' => $arrPermisosRol[$i]['w'],//EN EL ARRAY DE PERMISOS EN SU POSICION DEL CICLO 
												 'u' => $arrPermisosRol[$i]['u'],//QUE ESTA RECORRIENDO CON LA VARIABLE $i
												 'd' => $arrPermisosRol[$i]['d'] //Y LUEGO ESTAMOS ACCEDIENDO A LA POSICION DEL ARRAY DE PERMISOS 
												);
						}
						$arrModulos[$i]['permisos'] = $arrPermisos;//SETEAMOS EL ARRAY DE PERMISOS A CADA MODULO
					}
				}
				$arrPermisoRol['modulos'] = $arrModulos;
				//RESPUESTA QUE SERA ENVIADA AL FRONTEND Y QUE VA A RECIBIR EL AJAX 
				$html = getModal("modalPermisos",$arrPermisoRol);//ENVIAMOS EL NOMBRE DEL MODAL Y EL ARRAY AL METODO PARA QUE LO GENERE
			}
			die();
		}

		public function setPermisos()
		{
			if($_POST)
			{
				$intIdrol = intval($_POST['idrol']);
				$modulos = $_POST['modulos'];

				$this->model->deletePermisos($intIdrol);
				foreach ($modulos as $modulo) {
					$idModulo = $modulo['idmodulo'];
					$r = empty($modulo['r']) ? 0 : 1;
					$w = empty($modulo['w']) ? 0 : 1;
					$u = empty($modulo['u']) ? 0 : 1;
					$d = empty($modulo['d']) ? 0 : 1;
					$requestPermiso = $this->model->insertPermisos($intIdrol, $idModulo, $r, $w, $u, $d);
				}
				if($requestPermiso > 0)
				{
					$arrResponse = array('status' => true, 'msg' => 'Permisos asignados correctamente.');
				}else{
					$arrResponse = array("status" => false, "msg" => 'No es posible asignar los permisos.');
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}
			die();
		}

	}
 ?>