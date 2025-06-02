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

<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

<style>
* {
    box-sizing: border-box;
    -webkit-tap-highlight-color: transparent;
}

body {
    margin: 0;
    padding: 0;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
    background: #ecf0f1;
    line-height: 1.4;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

.mobile-container {
    width: 100%;
    min-height: 100vh;
    background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
    padding: 0;
    overflow-x: hidden;
}

.mobile-header {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    color: white;
    padding: 20px 16px;
    text-align: center;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    position: sticky;
    top: 0;
    z-index: 100;
}

.mobile-title {
    font-size: 1.4rem;
    font-weight: 600;
    margin: 0;
    color: white;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    word-break: break-word;
    line-height: 1.3;
}

.mobile-content {
    background: #ecf0f1;
    border-radius: 24px 24px 0 0;
    margin-top: 10px;
    min-height: calc(100vh - 120px);
    padding: 24px 16px 40px;
    position: relative;
}

.mobile-content::before {
    content: '';
    position: absolute;
    top: 8px;
    left: 50%;
    transform: translateX(-50%);
    width: 36px;
    height: 4px;
    background: #95a5a6;
    border-radius: 2px;
}

/* Imagen del producto */
.image-container {
    background: white;
    border-radius: 16px;
    padding: 20px;
    margin-bottom: 20px;
    text-align: center;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    border: 1px solid #e2e8f0;
}

.product-image {
    max-width: 100%;
    max-height: 200px;
    width: auto;
    height: auto;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Descripción */
.description-card {
    background: white;
    border-radius: 16px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    border: 1px solid #e2e8f0;
    border-left: 4px solid #2c3e50;
}

.description-text {
    color: #4a5568;
    font-size: 1rem;
    line-height: 1.5;
    margin: 0;
    word-break: break-word;
}

/* RFID destacado */
.rfid-highlight {
    background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
    color: white;
    border-radius: 20px;
    padding: 20px;
    margin-bottom: 20px;
    text-align: center;
    box-shadow: 0 4px 12px rgba(72, 187, 120, 0.3);
    border: none;
}

.rfid-value {
    font-size: 1.6rem;
    font-weight: 700;
    margin-bottom: 8px;
    word-break: break-all;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

.rfid-label {
    font-size: 0.9rem;
    opacity: 0.9;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Identificadores */
.identifiers-container {
    margin-bottom: 20px;
}

.identifiers-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}

.identifier-card {
    background: white;
    border-radius: 16px;
    padding: 18px 12px;
    text-align: center;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    border: 1px solid #e2e8f0;
    transition: all 0.2s ease;
    touch-action: manipulation;
}

.identifier-card:active {
    transform: scale(0.98);
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
}

.identifier-value {
    font-size: 1.2rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 6px;
    word-break: break-all;
    line-height: 1.2;
}

.identifier-label {
    font-size: 0.8rem;
    color: #718096;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Detalles técnicos */
.details-section {
    margin-top: 24px;
}

.section-title {
    color: #2d3748;
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 16px;
    padding-left: 4px;
}

.detail-card {
    background: white;
    border-radius: 12px;
    padding: 16px;
    margin-bottom: 12px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
    border: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 12px;
    touch-action: manipulation;
}

.detail-card:active {
    background: #f7fafc;
}

.detail-label {
    color: #2c3e50;
    font-weight: 600;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    flex-shrink: 0;
    min-width: 80px;
}

.detail-value {
    color: #2d3748;
    font-size: 1rem;
    font-weight: 500;
    text-align: right;
    word-break: break-word;
    line-height: 1.3;
}

/* Separador */
.section-divider {
    height: 1px;
    background: linear-gradient(to right, transparent, #95a5a6, transparent);
    border: none;
    margin: 24px 0;
}

/* Redes sociales */
.social-section {
    background: white;
    border-radius: 16px;
    padding: 20px;
    margin-top: 24px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    border: 1px solid #e2e8f0;
}

.social-title {
    color: #4a5568;
    font-size: 1rem;
    font-weight: 600;
    text-align: center;
    margin-bottom: 16px;
}

.social-links {
    display: flex;
    justify-content: space-around;
    gap: 8px;
}

.social-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 48px;
    background: #f7fafc;
    border-radius: 12px;
    color: #718096;
    font-size: 1.4rem;
    transition: all 0.2s ease;
    text-decoration: none;
    border: 1px solid #e2e8f0;
    touch-action: manipulation;
}

.social-link:active {
    transform: scale(0.95);
    background: #edf2f7;
    color: #2c3e50;
}

/* Estados vacíos */
.empty-value {
    color: #a0aec0;
    font-style: italic;
}

/* Animaciones de carga */
.loading-shimmer {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite;
}

@keyframes shimmer {
    0% { background-position: -200% 0; }
    100% { background-position: 200% 0; }
}

/* Optimizaciones para pantallas muy pequeñas */
@media (max-width: 360px) {
    .mobile-content {
        padding: 20px 12px 40px;
    }
    
    .identifiers-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .rfid-value {
        font-size: 1.4rem;
    }
    
    .detail-card {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
    
    .detail-value {
        text-align: left;
    }
    
    .detail-label {
        min-width: auto;
    }
}

/* Optimizaciones para pantallas grandes en móvil */
@media (min-width: 768px) {
    .mobile-content {
        max-width: 600px;
        margin: 10px auto 0;
        border-radius: 24px;
    }
    
    .identifiers-grid {
        grid-template-columns: 1fr 1fr 1fr;
    }
}

/* Mejoras de accesibilidad táctil */
@media (hover: none) and (pointer: coarse) {
    .identifier-card,
    .detail-card,
    .social-link {
        min-height: 44px;
    }
}

/* Soporte para modo oscuro del sistema */
@media (prefers-color-scheme: dark) {
    .mobile-container {
        background: linear-gradient(135deg, #1a252f 0%, #2c3e50 100%);
    }
}
</style>

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

<div class="mobile-container">

	<?php if (isMobileDevice()) { ?>

	<!-- Header Móvil -->
	<div class="mobile-header">
		<h1 class="mobile-title" id="lbl_nombre">Cargando información...</h1>
	</div>

	<!-- Contenido Principal -->
	<div class="mobile-content">
		
		<!-- Imagen del Producto -->
		<div class="image-container">
			<img id="img_producto" src="../img/sin_imagen.gif" class="product-image" alt="Imagen del Activo">
		</div>

		<!-- Descripción/Características -->
		<div class="description-card">
			<p class="description-text" id="lbl_catacteristicas">Cargando descripción del activo...</p>
		</div>

		<!-- RFID Destacado -->
		<div class="rfid-highlight">
			<div class="rfid-value" id="lbl_rfid">-</div>
			<div class="rfid-label">RFID</div>
		</div>

		<!-- Otros Identificadores -->
		<div class="identifiers-container">
			<div class="identifiers-grid">
				<div class="identifier-card">
					<div class="identifier-value" id="lbl_sku">-</div>
					<div class="identifier-label">SKU</div>
				</div>
				<div class="identifier-card">
					<div class="identifier-value" id="lbl_antiguo">-</div>
					<div class="identifier-label">Tag Antiguo</div>
				</div>
			</div>
		</div>

		<div class="section-divider"></div>

		<!-- Información Técnica -->
		<div class="details-section">
			<h3 class="section-title">Información Técnica</h3>
			
			<div class="detail-card">
				<span class="detail-label">Modelo</span>
				<span class="detail-value" id="lbl_modelo">-</span>
			</div>
			
			<div class="detail-card">
				<span class="detail-label">Serie</span>
				<span class="detail-value" id="lbl_serie">-</span>
			</div>
			
			<div class="detail-card">
				<span class="detail-label">Marca</span>
				<span class="detail-value" id="lbl_marca">-</span>
			</div>
			
			<div class="detail-card">
				<span class="detail-label">Ubicación</span>
				<span class="detail-value" id="lbl_localizacion">-</span>
			</div>
			
			<div class="detail-card">
				<span class="detail-label">Color</span>
				<span class="detail-value" id="lbl_color">-</span>
			</div>
		</div>

	</div>

	<?php } ?>
</div>