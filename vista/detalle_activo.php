<?php

include(dirname(__DIR__, 1) . '/cabeceras/header3.php');

$id = '';
$_token = '';

if (isset($_GET['id'])) {
	$id = $_GET['id'];
}

if (isset($_GET['_token'])) {
	$_token = $_GET['_token'];
	$_token = str_replace(' ', '+', $_GET['_token']);
}

// print_r($_token); exit(); die();

function isMobileDevice()
{
	// return (  
	//     isset($_SERVER['HTTP_USER_AGENT']) &&
	//     preg_match('/(android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini)/i', $_SERVER['HTTP_USER_AGENT'])
	// );

	return true;
}

?>
<script src="../js/detalle_activo.js?v=<?= rand() ?>"></script>
<script type="text/javascript">
	$(document).ready(function() {
		var art = '<?php echo $id; ?>';
		var token = '<?= $_token ?>';
		if (art != '' && token != '') {
			cargar_detalle_activo(art, token);
		}
	});
</script>

<section class="content">

	<?php if (isMobileDevice()) { ?>

		<div class="card card-solid">
			<div class="card-body">
				<div class="row">
					<div class="col-12 col-sm-6">
						<h3 class="d-inline-block" id="lbl_nombre"></h3>
						<div class="col-12">
							<img id="img_producto" src="../img/sin_imagen.gif" width="50%" class="product-image" alt="Product Image">
						</div>
						<div class="col-12 col-sm-6">
							<h3 class="my-3" id="lbl_nombre2"></h3>
							<p id="lbl_catacteristicas"></p>
							<hr>
							<div class="row">
								<div class="col-sm-12 text-center">
									<label class="btn btn-default ">
										<span class="text-lg" id="lbl_rfid"></span>
										<br>
										RFID
									</label>

								</div>
							</div>
							<div class="btn-group btn-group-toggle" style="width: 100%;" data-toggle="buttons">
								<label class="btn btn-default text-center">
									<span class="text-xl" id="lbl_sku"></span>
									<br>
									SKU
								</label>
								<label class="btn btn-default text-center">
									<span class="text-xl" id="lbl_antiguo"></span>
									<br>
									Tag Antiguo
								</label>
							</div>
							<hr>

							<h4>Modelo</h4>
							<li id="lbl_modelo"></li>
							<h4>Serie</h4>
							<li id="lbl_serie"></li>
							<h4>Marca</h4>
							<li id="lbl_marca"></li>
							<h4>Localización</h4>
							<li id="lbl_localizacion"></li>
							<h4>Color</h4>
							<li id="lbl_color"></li>


							<div class="mt-4 product-share">
								<a href="#" class="text-gray">
									<i class="fab fa-facebook-square fa-2x"></i>
								</a>
								<a href="#" class="text-gray">
									<i class="fab fa-twitter-square fa-2x"></i>
								</a>
								<a href="#" class="text-gray">
									<i class="fas fa-envelope-square fa-2x"></i>
								</a>
								<a href="#" class="text-gray">
									<i class="fas fa-rss-square fa-2x"></i>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
</section>