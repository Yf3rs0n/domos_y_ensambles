<?php 
	class FacturaModel extends Mysql
	{
		public function __construct()
		{
			parent::__construct();
		}

		public function selectContrato(int $idcontrato, $idpersona = NULL){
			$busqueda = "";
			if($idpersona != NULL){
				$busqueda = " AND p.personaid =".$idpersona;
			}
			$request = array();
			$sql = "SELECT p.personaid,
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
				$sql_detalle = "SELECT p.idproducto,
											p.nombre as producto,
											d.cantidad
									FROM detalle_contrato d
									INNER JOIN producto p
									ON d.productoid = p.idproducto
									WHERE d.contratoid = $idcontrato";
				$requestProductos = $this->select_all($sql_detalle);
				$request = array('cliente' => $requestcliente,
								'orden' => $requestContrato,
								'detalle' => $requestProductos
								 );
			}
			return $request;
		}

		

	}
 ?>