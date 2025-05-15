</div>
		<footer class="bg-white shadow-sm border-top p-2 text-center fixed-bottom">
			<p class="mb-0">Copyright © 2021. All right reserved.</p>
		</footer>
	</div>
<!-- Bootstrap JS -->
	<script src="../../assets/js/bootstrap.bundle.min.js"></script>
	<!--plugins-->
	<script src="../../assets/js/jquery.min.js"></script>
	<script src="../../assets/plugins/simplebar/js/simplebar.min.js"></script>
	<script src="../../assets/plugins/metismenu/js/metisMenu.min.js"></script>
	<script src="../../assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
	<script src="../../assets/plugins/select2/js/select2.min.js"></script>
	<script src="../../assets/js/form-select2.js"></script>	

	<!-- <script src='https://cdn.tiny.cloud/1/vdqx2klew412up5bcbpwivg1th6nrh3murc6maz8bukgos4v/tinymce/5/tinymce.min.js' referrerpolicy="origin"></script> -->
	<!-- <script src="../../assets/js/form-text-editor.js"></script> -->
	<script src="../../assets/plugins/summernote/summernote-bs4.min.js"></script>
	<!--app JS-->
	<script src="../../assets/js/app.js"></script>

	<script src="../../assets/plugins/chartjs/js/Chart.min.js"></script>
	<script src="../../assets/js/popover-tooltip.js"></script>

	<script src="../../assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
	<script src="../../assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
	<script src="../../assets/plugins/smart-wizard/js/jquery.smartWizard.min.js"></script>
	<script src="../../assets/js/form-wizard.js"></script>
	
	<script>
		$(document).ready(function () {
			$("#show_hide_password a").on('click', function (event) {
				event.preventDefault();
				if ($('#show_hide_password input').attr("type") == "text") {
					$('#show_hide_password input').attr('type', 'password');
					$('#show_hide_password i').addClass("bx-hide");
					$('#show_hide_password i').removeClass("bx-show");
				} else if ($('#show_hide_password input').attr("type") == "password") {
					$('#show_hide_password input').attr('type', 'text');
					$('#show_hide_password i').removeClass("bx-hide");
					$('#show_hide_password i').addClass("bx-show");
				}
			});
		});
	</script>
	
	<!--app JS-->
	<script src="../../assets/js/app.js"></script>
</body>

</html>