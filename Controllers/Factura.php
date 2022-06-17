<?php 
	require 'Libraries/html2pdf/vendor/autoload.php';
	use Spipu\Html2Pdf\Html2Pdf;

	class Factura extends Controllers{
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

		public function generarFactura($idcontrato)
		{
			if($_SESSION['permisosMod']['r']){
				if(is_numeric($idcontrato)){
					$idpersona = "";
					if($_SESSION['permisosMod']['r'] and $_SESSION['userData']['idrol'] == RCLIENTES){
						$idpersona = $_SESSION['userData']['idpersona'];
					}
					$data = $this->model->selectContrato($idcontrato,$idpersona);
					if(empty($data)){
						echo "Datos no encontrados";
					}else{
						$idcontrato = $data['orden']['idcontrato'];
						ob_end_clean();
						$html = getFile("Template/Modals/comprobantePDF",$data);
						$html2pdf = new Html2Pdf('p','A4','es','true','UTF-8');
						$html2pdf->writeHTML($html);
						$html2pdf->output('factura-'.$idcontrato.'.pdf');
					}
				}else{
					echo "Dato no válido";
				}
			}else{
				header('Location: '.base_url().'/login');
				die();
			}
		}

	}
 ?>
