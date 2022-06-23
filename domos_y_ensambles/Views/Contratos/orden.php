<?php headerAdmin($data); ?>
<main class="app-content">
  <div class="app-title">
    <div>
      <h1><i class="fa fa-file-text-o"></i> <?= $data['page_title'] ?></h1>
    </div>
    <ul class="app-breadcrumb breadcrumb">
      <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
      <li class="breadcrumb-item"><a href="<?= base_url(); ?>/contratos"> Contratos</a></li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="tile">
        <?php
          if(empty($data['arrContrato'])){
        ?>
        <p>Datos no encontrados</p>
        <?php }else{
            $cliente = $data['arrContrato']['cliente']; 
            $orden = $data['arrContrato']['orden'];
            $detalle = $data['arrContrato']['detalle'];
         ?>
        <section id="sContrato" class="invoice">
          <div class="row mb-4">
            <div class="col-6">
              <h2 class="page-header">Domos y Ensambles</h2>
            </div>
            <div class="col-6">
              <h5 class="text-right">Fecha: <?= $orden['fecha'] ?></h5>
            </div>
          </div>
          <div class="row invoice-info">
            <div class="col-4">
              <address><strong><?= NOMBRE_EMPESA; ?></strong><br>
                <?= DIRECCION ?><br>
                <?= TELEMPRESA ?><br>
                <?= EMAIL_EMPRESA ?><br>
                <?= WEB_EMPRESA ?>
              </address>
            </div>
            <div class="col-4">
              <address><strong><?= $cliente['nombres'].' '.$cliente['apellidos'] ?></strong><br>
                Envío: <?= $orden['direccion_envio']; ?><br>
                Tel: <?= $cliente['telefono'] ?><br>
                Email: <?= $cliente['email_user'] ?>
               </address>
            </div>
            <div class="col-4"><b>Orden #<?= $orden['idcontrato'] ?></b><br> 
                <b>Pago: </b><?= $orden['tipopago'] ?><br>
                <b>Estado:</b> <?= $orden['status'] ?> <br>
                <b>Monto:</b> <?= SMONEY.' '. formatMoney($orden['monto']) ?>
            </div>
          </div>
          <div class="row invoice-info">
            <div class="col-6 ">
              <address><strong>Detalle</strong><br>
                <?= $orden['detalles'] ?>
              </address>
            </div>
            <div class="col-6">
              <address><strong>Materiales</strong><br>
                <?= $orden['materiales'] ?>
              </address>
            </div>
          </div>
    
          <!-- <div class="row">
            <div class="col-12 table-responsive">
              <b>Materiales:</b><?= $orden['materiales'] ?>
            </div>
          </div>
          <div class="row">
            <div class="col-12 table-responsive">
              <b>Detalle:</b><?= $orden['detalles'] ?>
            </div>
          </div> -->
          <div class="row">
            <div class="col-12 table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Descripción</th>
                    <th class="text-right">Monto</th>
                    <th class="text-center">Cantidad</th>
                    <th class="text-right">Importe</th>
                  </tr>
                </thead>
                <tbody>
                    <?php 
                        $subtotal = 0;
                        if(count($detalle) > 0){
                            foreach ($detalle as $servicio) {
                                $subtotal += $servicio['cantidad'] * $orden['monto'] ;
                     ?>
                  <tr>
                    <td><?= $servicio['servicio'] ?></td>
                    <td class="text-right"><?= SMONEY.' '. formatMoney($orden['monto']) ?></td>
                    <td class="text-center"><?= $servicio['cantidad'] ?></td>
                    <td class="text-right"><?= SMONEY.' '. formatMoney($servicio['cantidad'] * $orden['monto']) ?></td>
                  </tr>
                  <?php 
                            }
                        }
                   ?>
                </tbody>
                <tfoot>
                    <tr>
                        
                    </tr>
                    <tr>
                      
                    </tr>
                    <tr>
                        <th colspan="3" class="text-right">Total:</th>
                        <td class="text-right"><?= SMONEY.' '. formatMoney($orden['monto']) ?></td>
                    </tr>
                </tfoot>
              </table>
            </div>
          </div>
          <div class="row d-print-none mt-2">
            <div class="col-12 text-right"><a class="btn btn-primary" href="javascript:window.print('#sContrato');" ><i class="fa fa-print"></i> Imprimir</a></div>
          </div>
        </section>
        <?php } ?>
      </div>
    </div>
  </div>
</main>
<?php footerAdmin($data); ?>