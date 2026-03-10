<div class="overlay toggle-icon"></div>
		<!--end overlay-->
		<!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
		<!--End Back To Top Button-->
		<footer class="page-footer">
			<p class="mb-0"><?php echo $_SESSION['INICIO']['TITULO_PESTANIA'] ?? '' ?> | Copyright © 2021. All right reserved. </p>
		</footer>
	</div>
	
	<div class="modal fade" id="myModal_espera" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
	  <div class="modal-dialog modal-sm modal-dialog-centered">
	    <div class="modal-content">
	      <div class="modal-header">
	      </div>
	      <div class="modal-body">
	         <div class="text-center">
	         	<?php if (file_exists($_SESSION['INICIO']['LOGO'])) { ?>					
					<img src="<?php echo $_SESSION['INICIO']['LOGO']; ?>" style="width: 35%;" alt="logo icon">
				<?php } ?>ESPERE...
	         </div>
	         <div class="text-center">
	         	<label id="lbl_msj_espera"></label>
	         </div>
	         <div class="text-center">
					<div class="spinner-grow text-primary spinner-grow-sm" role="status"> <span class="visually-hidden">Loading...</span>
					</div>
					<div class="spinner-grow text-secondary spinner-grow-sm" role="status"> <span class="visually-hidden">Loading...</span>
					</div>
					<div class="spinner-grow text-success spinner-grow-sm" role="status"> <span class="visually-hidden">Loading...</span>
					</div>
					<div class="spinner-grow text-danger spinner-grow-sm" role="status"> <span class="visually-hidden">Loading...</span>
					</div>
					<div class="spinner-grow text-warning spinner-grow-sm" role="status"> <span class="visually-hidden">Loading...</span>
					</div>
					<div class="spinner-grow text-info spinner-grow-sm" role="status"> <span class="visually-hidden">Loading...</span>
					</div>
				</div>
	      </div>
	      <div class="modal-footer">        
	      </div>
	    </div>
	  </div>
	</div>

	<div class="modal fade" id="myModal_detalles" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-12">
              <table class="table table-sm table-hover" id="tbl_detalles">
          
              </table>
            </div>            
          </div>
        </div>
        <div class="modal-footer"> 
        <a href="https://corsinf.com/" target="_blank" class="btn btn-primary"><i class="bx bx-store me-0"></i>Comprar una licencia</a>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>       
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="myModal_sri_error" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <div class="row">
          <div class="col-xs-2"><b>Estado</b> </div>
          <div class="col-xs-10" id="sri_estado"></div>          
        </div>
        <div class="row">
          <div class="col-xs-6"><b>Codigo de error</b> </div>
          <div class="col-xs-6" id="sri_codigo"></div>          
        </div>
        <div class="row">
          <div class="col-xs-2"><b>Fecha</b></div>
          <div class="col-xs-10" id="sri_fecha"></div>          
        </div>
        <div class="row">
          <div class="col-xs-12"><b>Mensaje</b></div>
          <div class="col-xs-12" id="sri_mensaje"></div>          
        </div>
        <div class="row">
          <div class="col-xs-12"><b>Info Adicional</b></div>
          <div class="col-xs-12" id="sri_adicional"></div>          
        </div>
      </div>
      <input type="hidden" id="txtclave" name="">

      <div class="modal-footer p-1">
        <!-- <a type="button" class="btn btn-primary" href="#" id="doc_xml">Descargar xml</button>         -->
        <button type="button" class="btn btn-outline-secondary" onclick="location.reload();">Cerrar</button>
      </div>
    </div>
  </div>
</div>


<div class="modal" id="alertas" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content modal-dialog-centered">  
      <!-- Modal body -->
      <div class="modal-body text-center">
        <img src="../img/facturando.gif" id="img_alerta" style="width: 30%;">
        <label id="tipo_alerta">Facturando..</label>
      </div>  
    </div>
  </div>
</div>

	<?php include_once('../cabeceras/acercaDe.php'); ?>

	




	<!-- Bootstrap JS -->
	<script src="../assets/js/bootstrap.bundle.min.js"></script>
	<!--plugins-->
	<!-- <script src="../assets/js/jquery.min.js"></script> -->
	<script src="../assets/plugins/simplebar/js/simplebar.min.js"></script>
	<script src="../assets/plugins/metismenu/js/metisMenu.min.js"></script>
	<script src="../assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
	<script src="../assets/plugins/select2/js/select2.min.js"></script>
	<script src="../assets/js/form-select2.js"></script>	

	<!-- <script src='https://cdn.tiny.cloud/1/vdqx2klew412up5bcbpwivg1th6nrh3murc6maz8bukgos4v/tinymce/5/tinymce.min.js' referrerpolicy="origin"></script> -->
	<!-- <script src="../../assets/js/form-text-editor.js"></script> -->
	<script src="../assets/plugins/summernote/summernote-bs4.min.js"></script>


	<script src="../assets/plugins/OwlCarousel/js/owl.carousel.min.js"></script>
	<script src="../assets/plugins/OwlCarousel/js/owl.carousel2.thumbs.min.js"></script>
	<script src="../assets/js/product-details.js"></script>


	<script src="../assets/plugins/Drag-And-Drop/dist/imageuploadify.min.js"></script>
	<script src="../assets/js/add-new-product-image-upload.js"></script>
	<!--app JS-->
	<script src="../assets/js/app.js"></script>

	<script src="../assets/plugins/chartjs/js/Chart.min.js"></script>
	<script src="../assets/js/popover-tooltip.js"></script>

	<script src="../assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
	<script src="../assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
	<script src="../assets/plugins/smart-wizard/js/jquery.smartWizard.min.js"></script>
	<script src="../assets/js/form-wizard.js"></script>
	<!-- <script src="../../assets/plugins/chartjs/js/chartjs-custom.js"></script> -->
	<script>
		 function modal_error_seri(auto,carpeta)
          {
            var parametros = 
            {
                'clave':auto,
                'carpeta':carpeta,
            }
            $.ajax({
                data: {parametros:parametros},
                url:   '../controlador/FACTURACION/lista_facturaC.php?error_sri=true',
                type:  'post',
                dataType: 'json',
                success:  function (data) { 
                $('#myModal_sri_error').modal('show');
                $('#sri_estado').text(data.estado[0]);
                $('#sri_codigo').text(data.codigo[0]);
                $('#sri_fecha').text(data.fecha[0]);
                $('#sri_mensaje').text(data.mensaje[0]);
                $('#sri_adicional').text(data.adicional[0]);
                        // $('#doc_xml').attr('href','')
                 console.log(data);
                 
                }
              });
          }
	</script>

</body>

</html>