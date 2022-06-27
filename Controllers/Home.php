<?php 
	require_once("Models/TCategoria.php");
	require_once("Models/TServicio.php");
	class Home extends Controllers{
		use TCategoria, TServicio;
		public function __construct()
		{
			parent::__construct();
			session_start();
		}

		public function home()
		{
			$pageContent = getPageRout('inicio');
			$data['page_tag'] = NOMBRE_EMPESA;
			$data['page_title'] = NOMBRE_EMPESA;
			$data['page_name'] = "domos_y_ensambles";
			$data['page'] = $pageContent;
			$data['slider'] = $this->getCategoriasT(CAT_SLIDER);
			$data['banner'] = $this->getCategoriasT(CAT_BANNER);
			$data['servicios'] = $this->getServiciosT();
			$this->views->getView($this,"home",$data); 
		}

	}
 ?>
