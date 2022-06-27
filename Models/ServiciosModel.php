<?php 

	class ServiciosModel extends Mysql
	{
		private $intIdServicio;
		private $strNombre;
		private $strDescripcion;
		private $intCodigo;
		private $intCategoriaId;
		private $intStatus;
		private $strRuta;
		private $strImagen;

		public function __construct()
		{
			parent::__construct();
		}

		public function selectServicios(){
			$sql = "SELECT p.idservicio,
							p.codigo,
							p.nombre,
							p.descripcion,
							p.categoriaid,
							c.nombre as categoria,
							p.status 
					FROM servicio p 
					INNER JOIN categoria c
					ON p.categoriaid = c.idcategoria
					WHERE p.status != 0 ";
					$request = $this->select_all($sql);
			return $request;
		}	

		public function insertServicio(string $nombre, string $descripcion, int $codigo, int $categoriaid, string $ruta, int $status){
			$this->strNombre = $nombre;
			$this->strDescripcion = $descripcion;
			$this->intCodigo = $codigo;
			$this->intCategoriaId = $categoriaid;
			$this->strRuta = $ruta;
			$this->intStatus = $status;
			$return = 0;
			$sql = "SELECT * FROM servicio WHERE codigo = '{$this->intCodigo}'";
			$request = $this->select_all($sql);
			if(empty($request))
			{
				$query_insert  = "INSERT INTO servicio(categoriaid,
														codigo,
														nombre,
														descripcion,
														ruta,
														status) 
								  VALUES(?,?,?,?,?,?)";
	        	$arrData = array($this->intCategoriaId,
        						$this->intCodigo,
        						$this->strNombre,
        						$this->strDescripcion,
        						$this->strRuta,
        						$this->intStatus);
	        	$request_insert = $this->insert($query_insert,$arrData);
	        	$return = $request_insert;
			}else{
				$return = "exist";
			}
	        return $return;
		}

		public function updateServicio(int $idservicio, string $nombre, string $descripcion, int $codigo, int $categoriaid, string $ruta, int $status){
			$this->intIdServicio = $idservicio;
			$this->strNombre = $nombre;
			$this->strDescripcion = $descripcion;
			$this->intCodigo = $codigo;
			$this->intCategoriaId = $categoriaid;
			$this->strRuta = $ruta;
			$this->intStatus = $status;
			$return = 0;
			$sql = "SELECT * FROM servicio WHERE codigo = '{$this->intCodigo}' AND idservicio != $this->intIdServicio ";
			$request = $this->select_all($sql);
			if(empty($request))
			{
				$sql = "UPDATE servicio 
						SET categoriaid=?,
							codigo=?,
							nombre=?,
							descripcion=?,
							ruta=?,
							status=? 
						WHERE idservicio = $this->intIdServicio ";
				$arrData = array($this->intCategoriaId,
        						$this->intCodigo,
        						$this->strNombre,
        						$this->strDescripcion,
        						$this->strRuta,
        						$this->intStatus);

	        	$request = $this->update($sql,$arrData);
	        	$return = $request;
			}else{
				$return = "exist";
			}
	        return $return;
		}

		public function selectServicio(int $idservicio){
			$this->intIdServicio = $idservicio;
			$sql = "SELECT p.idservicio,
							p.codigo,
							p.nombre,
							p.descripcion,
							p.categoriaid,
							c.nombre as categoria,
							p.status
					FROM servicio p
					INNER JOIN categoria c
					ON p.categoriaid = c.idcategoria
					WHERE idservicio = $this->intIdServicio";
			$request = $this->select($sql);
			return $request;

		}

		public function insertImage(int $idservicio, string $imagen){
			$this->intIdServicio = $idservicio;
			$this->strImagen = $imagen;
			$query_insert  = "INSERT INTO imagen(servicioid,img) VALUES(?,?)";
	        $arrData = array($this->intIdServicio,
        					$this->strImagen);
	        $request_insert = $this->insert($query_insert,$arrData);
	        return $request_insert;
		}

		public function selectImages(int $idservicio){
			$this->intIdServicio = $idservicio;
			$sql = "SELECT servicioid,img
					FROM imagen
					WHERE servicioid = $this->intIdServicio";
			$request = $this->select_all($sql);
			return $request;
		}

		public function deleteImage(int $idservicio, string $imagen){
			$this->intIdServicio = $idservicio;
			$this->strImagen = $imagen;
			$query  = "DELETE FROM imagen 
						WHERE servicioid = $this->intIdServicio
						AND img = '{$this->strImagen}'";
	        $request_delete = $this->delete($query);
	        return $request_delete;
		}

		public function deleteServicio(int $idservicio){
			$this->intIdServicio = $idservicio;
			$sql = "UPDATE servicio SET status = ? WHERE idservicio = $this->intIdServicio ";
			$arrData = array(0);
			$request = $this->update($sql,$arrData);
			return $request;
		}
	}
 ?>