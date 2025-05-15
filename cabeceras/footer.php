<div class="overlay toggle-icon"></div>
		<!--end overlay-->
		<!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
		<!--End Back To Top Button-->
		<footer class="page-footer">
			<p class="mb-0">Copyright © 2021. All right reserved.</p>
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

        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>       
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

	<script src='https://cdn.tiny.cloud/1/vdqx2klew412up5bcbpwivg1th6nrh3murc6maz8bukgos4v/tinymce/5/tinymce.min.js' referrerpolicy="origin">
	</script>
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

</body>

</html>