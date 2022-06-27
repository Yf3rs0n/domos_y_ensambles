<?php 
	class ContratosModel extends Mysql
	{
		private $objCategoria;
		public function __construct()
		{
			parent::__construct();
		}

		public function selectContratos($idpersona = null){
			$where = "";
			if($idpersona != null){
				$where = " WHERE p.personaid = ".$idpersona;
			}
			$sql = "SELECT p.idcontrato,
							DATE_FORMAT(p.fecha, '%d/%m/%Y') as fecha,
							p.monto,
							tp.tipopago,
							tp.idtipopago,
							p.materiales,
							p.detalles,
							p.status 
					FROM contrato p 
					INNER JOIN tipopago tp
					ON p.tipopagoid = tp.idtipopago $where ";
			$request = $this->select_all($sql);
			return $request;

		}	

		public function selectContrato(int $idcontrato, $idpersona = NULL){
			$busqueda = "";
			if($idpersona != NULL){
				$busqueda = " AND p.personaid =".$idpersona;
			}
			$request = array();
			$sql = "SELECT p.idcontrato,
							p.personaid,
							DATE_FORMAT(p.fecha, '%d/%m/%Y') as fecha,
							p.costo_envio,
							p.monto,
							p.tipopagoid,
							t.tipopago,
							p.direccion_envio,
							p.materiales,
							P.detalles,
							p.status
					FROM contrato as p
					INNER JOIN tipopago t
					ON p.tipopagoid = t.idtipopago
					WHERE p.idcontrato =  $idcontrato ".$busqueda;
			$requestContrato = $this->select($sql);
			if(!empty($requestContrato)){
				$idpersona = $requestContrato['personaid'];
				$sql_cliente = "SELECT idpersona,
										nombres,
										apellidos,
										telefono,
										email_user,
										nit,
										nombrefiscal,
										direccionfiscal 
								FROM persona WHERE idpersona = $idpersona ";
				$requestcliente = $this->select($sql_cliente);
				$sql_detalle = "SELECT p.idservicio,
											p.nombre as servicio,
											d.cantidad
									FROM detalle_contrato d
									INNER JOIN servicio p
									ON d.servicioid = p.idservicio
									WHERE d.contratoid = $idcontrato";
				$requestServicios = $this->select_all($sql_detalle);
				$request = array('cliente' => $requestcliente,
								'orden' => $requestContrato,
								'detalle' => $requestServicios
								 );
			}
			return $request;
		}

		public function updateContrato(int $idcontrato, int $monto, $idtipopago = NULL, string $estado){
			if($idtipopago == NULL){
				$query_insert  = "UPDATE contrato SET status = ?  WHERE idcontrato = $idcontrato ";
	        	$arrData = array($estado);
			}else{
				$query_insert  = "UPDATE contrato SET monto=?,tipopagoid = ?,status = ? WHERE idcontrato = $idcontrato";
	        	$arrData = array($monto,
	        					$idtipopago,
	    						$estado
	    					);
			}
			$request_insert = $this->update($query_insert,$arrData);
        	return $request_insert;
		}
	}
 ?>