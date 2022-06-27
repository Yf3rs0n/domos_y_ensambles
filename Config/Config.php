<?php 
	const BASE_URL = "http://localhost/domos_y_ensambles";

	//Zona horaria
	date_default_timezone_set('America/Bogota');

	//Datos de conexión a Base de Datos
	const DB_HOST = "localhost";
	const DB_NAME = "bd_domos_y_ensambles";
	const DB_USER = "root";
	const DB_PASSWORD = "";
	const DB_CHARSET = "utf8";

	//Para envío de correo
	const ENVIRONMENT = 0; // Local: 0, Produccón: 1;

	//Deliminadores decimal y millar Ej. 24,1989.00
	const SPD = ".";
	const SPM = ",";

	//Simbolo de moneda
	const SMONEY = "$";
	const CURRENCY = "USD";

	//Api PayPal
	//SANDBOX PAYPAL
	const URLPAYPAL = "https://api-m.sandbox.paypal.com";
	const IDCLIENTE = "";
	const SECRET = "";
	//LIVE PAYPAL
	//const URLPAYPAL = "https://api-m.paypal.com";
	//const IDCLIENTE = "";
	//const SECRET = "";

	//Datos envio de correo
	const NOMBRE_REMITENTE = "Domos y Ensambles";
	const EMAIL_REMITENTE = "no-reply@DomosyEnsambles.com";
	const NOMBRE_EMPESA = "Domos y Ensambles";
	const WEB_EMPRESA = "www.DomosyEnsambles.com";

	const DESCRIPCION = "El mejor sitio de contrataciónes online.";
	const SHAREDHASH = "Domos y Ensambles";

	//Datos Empresa
	const DIRECCION = "Medellín, Antioquia";
	const TELEMPRESA = "+(57)33322211";
	const WHATSAPP = "+573114299500";
	const EMAIL_EMPRESA = "domosyensambles01@gmail.com";
	const EMAIL_PEDIDOS = "domosyensambles01@gmail.com"; 
	const EMAIL_SUSCRIPCION = "domosyensambles01@gmail.com";
	const EMAIL_CONTACTO = "domosyensambles01@gmail.com";

	const CAT_SLIDER = "1,2";//nuemero que categorias que se muetran en el slider
	const CAT_BANNER = "4,5,6";
	const CAT_FOOTER = "1,2,3,4,5";

	//Datos para Encriptar / Desencriptar
	const KEY = 'abelosh';
	const METHODENCRIPT = "AES-128-ECB";

	//Envío
	const COSTOENVIO = 0;

	//Módulos
	const MDASHBOARD = 1;
	const MUSUARIOS = 2;
	const MCLIENTES = 3;
	const MSERVICIOS = 4;
	const MCONTRATOS = 5;
	const MCATEGORIAS = 6;
	const MDCONTACTOS = 7;
	const MDPAGINAS = 8;

	//Páginas
	const PINICIO = 1;
	const PTIENDA = 2;
	const PCARRITO = 3;
	const PNOSOTROS = 4;
	const PCONTACTO = 5;
	const PPREGUNTAS = 6;
	const PTERMINOS = 7;
	const PERROR = 8;

	//Roles
	const RADMINISTRADOR = 1;
	const RSUPERVISOR = 2;
	const RCLIENTES = 3;

	const STATUS = array('Iniciado','Completado','Aprobado','Cancelado','Cotización','Visita','Revision');

	//Servicios por página
	const CANTPORDHOME = 8;
	const PROPORPAGINA = 4;
	const PROCATEGORIA = 4;
	const PROBUSCAR = 4;

	//REDES SOCIALES
	const FACEBOOK = "https://www.facebook.com/";
	const INSTAGRAM = "https://www.instagram.com/";
	

 ?>