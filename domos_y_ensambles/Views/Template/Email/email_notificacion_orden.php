<?php 

$orden = $data['contrato']['orden'];
$detalle = $data['contrato']['detalle'];
 ?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Orden</title>
	<style type="text/css">
		p{
			font-family: arial;letter-spacing: 1px;color: #7f7f7f;font-size: 12px;
		}
		hr{border:0; border-top: 1px solid #CCC;}
		h4{font-family: arial; margin: 0;}
		table{width: 100%; max-width: 600px; margin: 10px auto; border: 1px solid #CCC; border-spacing: 0;}
		table tr td, table tr th{padding: 5px 10px;font-family: arial; font-size: 12px;}
		#detalleOrden tr td{border: 1px solid #CCC;}
		.table-active{background-color: #CCC;}
		.text-center{text-align: center;}
		.text-right{text-align: right;}

		@media screen and (max-width: 470px) {
			.logo{width: 90px;}
			p, table tr td, table tr th{font-size: 9px;}
		}
	</style>
</head>
<body>
	<div>
		<br>
		<p class="text-center">Se ha generado una factura, a continuación encontrarás los datos.</p>
		<br>
		<hr>
		<br>
		<table>
			<tr>
				<td width="33.33%">
					<img class="logo" src="<?= media(); ?>/tienda/images/favicon.ico" alt="Logo">
				</td>
				<td width="33.33%">
					<div class="text-center">
						<h4><strong><?= NOMBRE_EMPESA ?></strong></h4>
						<p>
							<?= DIRECCION ?> <br>
							Teléfono: <?= TELEMPRESA ?> <br>
							Email: <?= EMAIL_EMPRESA ?>
						</p>
					</div>
				</td>
				<td width="33.33%">
					<div class="text-right">
						<p>
							No. Orden: <strong><?= $orden['idcontrato'] ?></strong><br>
                            Fecha: <?= $orden['fecha'] ?> <br>
                            <?php 
								if($orden['tipopagoid'] == 1){
							 ?>
                            Método Pago: <?= $orden['tipopago'] ?> <br>
                        <?php }else{ ?>
                        	Método Pago: Pago contra entrega <br>
							Tipo Pago: <?= $orden['tipopago'] ?>
                        <?php } ?>
						</p>
					</div>
				</td>				
			</tr>
		</table>
		<table>
			<tr>
		    	<td width="140">Nombre:</td>
		    	<td><?= $_SESSION['userData']['nombres'].' '.$_SESSION['userData']['apellidos'] ?></td>
		    </tr>
		    <tr>
		    	<td>Teléfono</td>
		    	<td><?= $_SESSION['userData']['telefono'] ?></td>
		    </tr>
		    <tr>
		    	<td>Dirección de envío:</td>
		    	<td><?= $orden['direccion_envio'] ?></td>
		    </tr>
		</table>
		<table>
		  <thead class="table-active">
		    <tr>
		      <th>Descripción</th>
		      <th class="text-center">Precio</th>
		    </tr>
		  </thead>
		  <tbody id="detalleOrden">
		  	<?php 
		  		if(count($detalle) > 0){
		  			foreach ($detalle as $servicio) {
		  	 ?>
		    <tr>
		      <td><?= $servicio['servicio'] ?></td>
			  <p>El oficial de obra encargado se pondrá en contacto o puedes mirar como va la cotización de tu contrato en nuestro sitio web.</p>
		    </tr>
			<tr>
				<td width="140">Materiales:</td>
				<td><?= $orden['materiales'] ?></td>
			</tr>
			<tr>
				<td width="140">Detalles agregados:</td>
				<td><?= $orden['detalles'] ?></td>
			</tr>
			<?php }
				} ?>
		  </tbody>
		  <!-- <tfoot>
		  		<tr>
		  			<th colspan="3" class="text-right">Total:</th>
		  			<td class="text-right"><?= SMONEY.' '.formatMoney($orden['monto']); ?></td>
		  		</tr>
		  </tfoot> -->
		</table>
		<div class="text-center">
			<p>Si tienes preguntas sobre tu contrato, <br>pongase en contacto con nombre, teléfono y Email</p>
			<h4>¡Gracias por adquirir nuestro servicio. Esperamos que tu experiencia con nosotros fuera extraordinaria</h4>			
		</div>
	</div>									
</body>
</html>