<?php include('./header3.php');  $id = ''; if(isset($_GET['id'])){$id = $_GET['id'];} 
function isMobileDevice() {
    return (
        isset($_SERVER['HTTP_USER_AGENT']) &&
        preg_match('/(android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini)/i', $_SERVER['HTTP_USER_AGENT'])
    );
}

?>
<script src="../js/detalle_activo.js"></script>
<script type="text/javascript">
	 $( document ).ready(function() {
    var art = '<?php echo $id;?>';
    if(art!='')
    {
    	cargar_detalle_activo(art);
    }
  });
</script>

<section class="content">

<?php if (isMobileDevice()) { ?>

<div class="card card-solid">
	<div class="card-body">
		<div class="row">
			<div class="col-12 col-sm-6">
				<h3 class="d-inline-block d-sm-none" id="lbl_nombre"></h3>
				<div class="col-12">
					<img id="img_producto" src="../img/sin_imagen.gif" class="product-image" alt="Product Image">
				</div>
				<div class="col-12 col-sm-6">
					<h3 class="my-3" id="lbl_nombre2" ></h3>
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
							<span class="text-xl" id="lbl_asset"></span>
							<br>
							Asset
						</label>
						<label class="btn btn-default text-center">
							<span class="text-xl" id="lbl_antiguo"></span>
							<br>
							Tag antiguo
						</label>						
					</div>
					<hr>

					<h4>Modelo</h4>
					<li id="lbl_modelo"></li>
					<h4>Serie</h4>
					<li id="lbl_serie"></li>
					<h4>Marca</h4>
					<li id="lbl_marca"></li>
					<h4>Emplazamiento</h4>
					<li id="lbl_emplazamiento"></li>
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
