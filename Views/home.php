<?php 
	headerTienda($data);
	$arrSlider = $data['slider'];
	$arrBanner = $data['banner'];
	$arrServicios = $data['servicios'];

	$contentPage = "";
	if(!empty($data['page'])){
		$contentPage = $data['page']['contenido'];
	}

 ?>
	<!-- Slider -->
	<section class="section-slide">
		<div class="wrap-slick1">
			<div class="slick1">
			<?php 
			for ($i=0; $i < count($arrSlider) ; $i++) { 
				$ruta = $arrSlider[$i]['ruta'];
			 ?>
				<div class="item-slick1" style="background-image: url(<?= $arrSlider[$i]['portada'] ?>);">
					<div class="container h-full">
						<div class="flex-col-l-m h-full p-t-100 p-b-30 respon5">
							<div class="layer-slick1 animated visible-false" data-appear="fadeInUp" data-delay="0">
								<span class="ltext-201 cl5 respon2">
									<?= $arrSlider[$i]['nombre'] ?>
								</span>
							</div>
								
							<div class="layer-slick1 animated visible-false" data-appear="fadeInDown" data-delay="800">
								<h2 class="ltext-101 cl5 p-t-19 p-b-43 respon1">
									<?= $arrSlider[$i]['descripcion'] ?>
								</h2>
							</div>
								
							<div class="layer-slick1 animated visible-false" data-appear="zoomIn" data-delay="1600">
								<a href="<?= base_url().'/tienda/categoria/'.$arrSlider[$i]['idcategoria'].'/'.$ruta; ?>" class="flex-c-m stext-101 cl5 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04">
									Ver servicios
								</a>
							</div>
						</div>
					</div>
				</div>
			<?php 
			}
			?>
			</div>
		</div>
	</section>
	<br>
	<br>
	<br>
	
	<section>
	<div class="features">
      <div class="container">
        <div class="row">
          <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="feature-box media">
              <div class="feature-text media-body">
                <svg class="col" width="35" height="35" style="color:black;" ><use xlink:href="<?= media() ?>/tienda/vendor/bootstrap/css/bootstrap-icons.svg#book"/></svg> 
                <h4 class="ltext-70 cl2 text-center">Contratación</h4>
                <p class="feature-detail text-center">Contrata y date la oportunidad de probar nuestro sistema de contratación online.</p>
              </div>
            </div>
          </div>
          <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="feature-box media pull-left">
              <div class="feature-text media-body ">
                <svg class="col"  width="35" height="35" style="color:black;" ><use xlink:href="<?= media() ?>/tienda/vendor/bootstrap/css/bootstrap-icons.svg#search"/></svg> 
                <h4 class="ltext-70 cl2 text-center">Explorar servicios</h4>
                <p class="feature-detail text-center">Explora los servicios que ofrece Domos y Ensambles.</p>
              </div>
            </div>
          </div>
          <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="feature-box media pull-left">
              <div class="feature-text media-body">
                <svg class="col"  width="35" height="35" style="color:black;"><use xlink:href="<?= media() ?>/tienda/vendor/bootstrap/css/bootstrap-icons.svg#bricks"/></svg> 
                <h4 class="ltext-70 cl2 text-center">Nuestras obras</h4>
                <p class="feature-detail text-center">Mira nuestras obras y decide cual quieres desde la comodidad de tu hogar.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>            
	</section>
	<br>
	<br>
	<br>
	<!-- Product -->
	<section class="bg0 p-t-23 p-b-140">
		<div class="container">
			<div class="p-b-10">
				<h3 class="ltext-103 cl2">
					Servicios
				</h3>
			</div>
			<hr>
			<div class="row isotope-grid">
			<?php 
				for ($p=0; $p < count($arrServicios) ; $p++) {
					$rutaServicio = $arrServicios[$p]['ruta']; 
					if(count($arrServicios[$p]['images']) > 0 ){
						$portada = $arrServicios[$p]['images'][0]['url_image'];
					}else{
						$portada = media().'/images/uploads/product.png';
					}
			 ?>
				<div class="col-sm-6 col-md-4 col-lg-3 p-b-35 isotope-item women">
					<!-- Block2 -->
					<div class="block2">
						<div class="block2-pic hov-img0">
							<img src="<?= $portada ?>" alt="<?= $arrServicios[$p]['nombre'] ?>">
							<a href="<?= base_url().'/tienda/servicio/'.$arrServicios[$p]['idservicio'].'/'.$rutaServicio; ?>" class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04">
								Ver servicio
							</a>
						</div>

						<div class="block2-txt flex-w flex-t p-t-14">
							<div class="block2-txt-child1 flex-col-l ">
								<a href="<?= base_url().'/tienda/servicio/'.$arrServicios[$p]['idservicio'].'/'.$rutaServicio; ?>" class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
									<?= $arrServicios[$p]['nombre'] ?>
								</a>
							</div>

							<div class="block2-txt-child2 flex-r p-t-3">
								<a href="#"
								 id="<?= openssl_encrypt($arrServicios[$p]['idservicio'],METHODENCRIPT,KEY); ?>"
								 class="btn-addwish-b2 dis-block pos-relative js-addwish-b2 js-addcart-detail
								 icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11
								 ">
									<i class="zmdi zmdi-plus"></i>
								</a>
							</div>
						</div>
					</div>
				</div>
			<?php } ?>
			</div>
			<!-- Load more -->
			<div class="flex-c-m flex-w w-full p-t-45">
				<a href="<?= base_url() ?>/tienda" class="flex-c-m stext-101 cl5 size-103 bg3 bor1 hov-btn1 p-lr-15 trans-04">
					Ver más
				</a>
			</div>
		</div>		
	</section>
<?php 
	footerTienda($data);
 ?>

