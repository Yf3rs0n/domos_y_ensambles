<?php 
	class DashboardModel extends Mysql
	{
		public function __construct()
		{
			parent::__construct();
		}

		public function cantUsuarios(){
			$sql = "SELECT COUNT(*) as total FROM persona WHERE status != 0";
			$request = $this->select($sql);
			$total = $request['total']; 
			return $total;
		}
		public function cantClientes(){
			$sql = "SELECT COUNT(*) as total FROM persona WHERE status != 0 AND rolid = ".RCLIENTES;
			$request = $this->select($sql);
			$total = $request['total']; 
			return $total;
		}
		public function cantServicios(){
			$sql = "SELECT COUNT(*) as total FROM servicio WHERE status != 0 ";
			$request = $this->select($sql);
			$total = $request['total']; 
			return $total;
		}
		public function cantContratos(){
			$rolid = $_SESSION['userData']['idrol'];
			$idUser = $_SESSION['userData']['idpersona'];
			$where = "";
			if($rolid == RCLIENTES ){
				$where = " WHERE personaid = ".$idUser;
			}

			$sql = "SELECT COUNT(*) as total FROM contrato ".$where;
			$request = $this->select($sql);
			$total = $request['total']; 
			return $total;
		}
		public function lastOrders(){
			$rolid = $_SESSION['userData']['idrol'];
			$idUser = $_SESSION['userData']['idpersona'];
			$where = "";
			if($rolid == RCLIENTES ){
				$where = " WHERE p.personaid = ".$idUser;
			}

			$sql = "SELECT p.idcontrato, CONCAT(pr.nombres,' ',pr.apellidos) as nombre, p.monto, p.status 
					FROM contrato p
					INNER JOIN persona pr
					ON p.personaid = pr.idpersona
					$where
					ORDER BY p.idcontrato DESC LIMIT 10 ";
			$request = $this->select_all($sql);
			return $request;
		}	
		public function selectPagosMes(int $anio, int $mes){

			$sql = "SELECT p.tipopagoid, tp.tipopago, COUNT(p.tipopagoid) as cantidad, SUM(p.monto) as total 
					FROM contrato p 
					INNER JOIN tipopago tp 
					ON p.tipopagoid = tp.idtipopago 
					WHERE MONTH(p.fecha) = $mes AND YEAR(p.fecha) = $anio GROUP BY tipopagoid";
			$pagos = $this->select_all($sql);
			$meses = Meses();
			$arrData = array('anio' => $anio, 'mes' => $meses[intval($mes-1)], 'tipospago' => $pagos );
			return $arrData;
		}
		public function selectVentasMes(int $anio, int $mes){
			$rolid = $_SESSION['userData']['idrol'];
			$idUser = $_SESSION['userData']['idpersona'];
			$where = "";
			if($rolid == RCLIENTES ){
				$where = " AND personaid = ".$idUser;
			}

			$totalVentasMes = 0;
			$arrVentaDias = array();
			$dias = cal_days_in_month(CAL_GREGORIAN,$mes, $anio);
			$n_dia = 1;
			for ($i=0; $i < $dias ; $i++) { 
				$date = date_create($anio."-".$mes."-".$n_dia);
				$fechaVenta = date_format($date,"Y-m-d");
				$sql = "SELECT DAY(fecha) AS dia, COUNT(idcontrato) AS cantidad, SUM(monto) AS total 
						FROM contrato 
						WHERE DATE(fecha) = '$fechaVenta' AND status = 'Completo' ".$where;
				$ventaDia = $this->select($sql);
				$ventaDia['dia'] = $n_dia;
				$ventaDia['total'] = $ventaDia['total'] == "" ? 0 : $ventaDia['total'];
				$totalVentasMes += $ventaDia['total'];
				array_push($arrVentaDias, $ventaDia);
				$n_dia++;
			}
			$meses = Meses();
			$arrData = array('anio' => $anio, 'mes' => $meses[intval($mes-1)], 'total' => $totalVentasMes,'ventas' => $arrVentaDias );
			return $arrData;
		}
		public function selectVentasAnio(int $anio){
			$arrMVentas = array();
			$arrMeses = Meses();
			for ($i=1; $i <= 12; $i++) { 
				$arrData = array('anio'=>'','no_mes'=>'','mes'=>'','venta'=>'');
				$sql = "SELECT $anio AS anio, $i AS mes, SUM(monto) AS venta 
						FROM contrato 
						WHERE MONTH(fecha)= $i AND YEAR(fecha) = $anio AND status = 'Completo' 
						GROUP BY MONTH(fecha) ";
				$ventaMes = $this->select($sql);
				$arrData['mes'] = $arrMeses[$i-1];
				if(empty($ventaMes)){
					$arrData['anio'] = $anio;
					$arrData['no_mes'] = $i;
					$arrData['venta'] = 0;
				}else{
					$arrData['anio'] = $ventaMes['anio'];
					$arrData['no_mes'] = $ventaMes['mes'];
					$arrData['venta'] = $ventaMes['venta'];
				}
				array_push($arrMVentas, $arrData);
				# code...
			}
			$arrVentas = array('anio' => $anio, 'meses' => $arrMVentas);
			return $arrVentas;
		}
		public function serviciosTen(){
			$sql = "SELECT * FROM servicio WHERE status = 1 ORDER BY idservicio DESC LIMIT 10 ";
			$request = $this->select_all($sql);
			return $request;
		}
	}
 ?>