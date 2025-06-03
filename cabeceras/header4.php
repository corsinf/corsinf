<?php 
@session_start();
// if (!isset($_SESSION['INICIO'])) {
//   header('Location: ../login.php');
// }

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<base href="/corsinf/">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--favicon-->
	<link rel="icon" href="assets/images/favicon-32x32.png" type="image/png" />
	<!--plugins-->
	<link href="assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
	<link href="assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
	<link href="assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />

	<link href="assets/plugins/select2/css/select2.min.css" rel="stylesheet" />
	<link href="assets/plugins/select2/css/select2-bootstrap4.css" rel="stylesheet" />
	<!-- loader-->
	<link href="assets/css/pace.min.css" rel="stylesheet" />
	<script src="assets/js/pace.min.js"></script>
	<script src="assets/js/jquery.min.js"></script>
	<!-- Bootstrap CSS -->
	<link href="assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="assets/css/bootstrap-extended.css" rel="stylesheet">
	<link href="assets/css/app.css" rel="stylesheet">
	<link href="assets/css/icons.css" rel="stylesheet">
	<!-- Theme Style CSS -->
	<link rel="stylesheet" href="assets/css/dark-theme.css" />
	<link rel="stylesheet" href="assets/css/semi-dark.css" />
	<link rel="stylesheet" href="assets/css/header-colors.css" />

	<link rel="stylesheet" href="assets/plugins/summernote/summernote-lite.css">
	<!-- <link rel="stylesheet" href="assets/plugins/summernote/css/styles_summernote.css"> -->
	<link rel="stylesheet" href="assets/plugins/summernote/summernote-bs5.min.css">
	<!-- <link rel="stylesheet" href="assets/plugins/summernote/css/font-awesome.min.css"> -->

	<script src="js/sweetalert2.all.min.js"></script>

</head>

<style>
	.input-group>.select2-container--bootstrap {
		width: auto;
		flex: 1 1 auto;
	}

	.input-group-sm>.btn {
		padding: 1px;
	}
</style>

<body class="hold-transition sidebar-mini layout-fixed">