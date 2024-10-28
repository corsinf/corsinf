<?php

if ($_SERVER['HTTP_HOST'] == 'corsinf.com:447') {
	require_once('login/login_general.php');
	sleep(1);
?>
	<script>
		$('#pnl_loading').hide();
		$('#pnl_login').show();
	</script>
	<style>
		section {
			display: flex;
			justify-content: center;
			align-items: center;
			width: 100%;
			min-height: 100vh;
			background: url(img/inicio/login1.webp);
			background-position: center;
			background-size: cover;
		}
	</style>

<?php
} else if ($_SERVER['HTTP_HOST'] == 'medico.saintdominic.edu.ec:448') {
	require_once('login/login_general.php');
	sleep(1);
?>
	<script>
		$('#pnl_loading').hide();
		$('#pnl_login').show();
	</script>
	<style>
		section {
			display: flex;
			justify-content: center;
			align-items: center;
			width: 100%;
			min-height: 100vh;
			background: url(img/inicio/login6.webp);
			background-position: center;
			background-size: cover;
		}
	</style>
<?php
} else {
	require_once('login/login_general.php');
	sleep(1);
?>
	<script>
		$('#pnl_loading').hide();
		$('#pnl_login').show();
	</script>
	<style>
		section {
			display: flex;
			justify-content: center;
			align-items: center;
			width: 100%;
			min-height: 100vh;
			background: url(img/inicio/login_apudata.svg);
			background-position: center;
			background-size: cover;
		}
	</style>
<?php
}
?>