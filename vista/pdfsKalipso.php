<?php 
include(dirname(__DIR__, 1) . '/cabeceras/header3.php'); 

// Configuración y validación de parámetros
$config = [
    'tipo' => 'articulos_cliente_bodega', // 'auditoria' o 'articulos_cliente_bodega'
    'th_per_id' => 1,
    'id_localizacion' => ''
];

// Sanitización de entrada
if (isset($_GET['tipo'])) {
    $config['tipo'] = in_array($_GET['tipo'], ['auditoria', 'articulos_cliente_bodega']) ? $_GET['tipo'] : 'articulos_cliente_bodega';
}

if (isset($_GET['th_per_id'])) {
    $config['th_per_id'] = filter_var($_GET['th_per_id'], FILTER_VALIDATE_INT) ?: 1;
}

if (isset($_GET['id_localizacion'])) {
    $config['id_localizacion'] = filter_var($_GET['id_localizacion'], FILTER_VALIDATE_INT);
}

/**
 * Genera la URL del PDF según el tipo
 * @param array $config
 * @return array
 */
function generatePdfConfig(array $config): array {
    $baseUrl = 'https://localhost/corsinf/controlador/ACTIVOS_FIJOS/ac_reporte_acticulos_KalipsoC.php';

    $params = [
        'th_per_id' => $config['th_per_id'] ?? null,
        'id_localizacion' => $config['id_localizacion'] ?? null,
    ];

    // Construir la descripción dinámica
    $hasCustodio = !empty($params['th_per_id']);
    $hasLocalizacion = !empty($params['id_localizacion']);

    if ($hasCustodio && $hasLocalizacion) {
        $description = 'Listado de Artículos por Cliente y Ubicación';
    } elseif ($hasCustodio) {
        $description = 'Listado de Artículos por Cliente';
    } elseif ($hasLocalizacion) {
        $description = 'Listado de Artículos por Ubicación';
    } else {
        $description = 'Listado General de Artículos';
    }

    $queryParams = http_build_query(array_filter($params)); // Limpia valores nulos

    if ($config['tipo'] === 'auditoria') {
        return [
            'url' => $baseUrl . '?imprimirAuditoria=true&' . $queryParams,
            'title' => 'Reporte de Auditoría',
            'description' => $description,
            'icon' => 'fas fa-clipboard-check'
        ];
    }

    return [
        'url' => $baseUrl . '?imprimirPDF=true&' . $queryParams,
        'title' => 'Reporte de Artículos',
        'description' => $description,
        'icon' => 'fas fa-boxes'
    ];
}


$pdfConfig = generatePdfConfig($config);
?>

<!-- Estilos CSS Empresariales -->
<style>
:root {
    --primary-color: #2c3e50;
    --secondary-color: #34495e;
    --accent-color: #3498db;
    --light-gray: #ecf0f1;
    --dark-gray: #7f8c8d;
    --white: #ffffff;
    --shadow: 0 2px 10px rgba(44, 62, 80, 0.1);
    --border-radius: 8px;
}

body {
    background-color: #f8f9fa;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.pdf-viewer-wrapper {
    max-width: 1200px;
    margin: 20px auto;
    padding: 0 15px;
}

.pdf-container {
    background: var(--white);
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
    overflow: hidden;
    margin-bottom: 20px;
}

.pdf-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    color: var(--white);
    padding: 25px 30px;
    border-bottom: 3px solid var(--accent-color);
    position: relative;
    overflow: hidden;
}

.pdf-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, transparent 0%, rgba(255,255,255,0.05) 50%, transparent 100%);
    pointer-events: none;
}

.pdf-header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
    min-height: 60px;
}

.pdf-title {
    display: flex;
    align-items: center;
    gap: 15px;
    margin: 0;
    flex: 1;
}

.pdf-title i {
    font-size: 28px;
    color: var(--accent-color);
    min-width: 32px;
}

.pdf-title h2 {
    margin: 0;
    font-size: 22px;
    font-weight: 600;
    line-height: 1.3;
    color: var(--white);
    text-shadow: 0 1px 2px rgba(0,0,0,0.1);
}

.pdf-description {
    margin: 5px 0 0 36px;
    font-size: 14px;
    opacity: 0.9;
}

.pdf-actions {
    display: flex;
    gap: 10px;
    align-items: center;
}

.btn-corporate {
    background: var(--accent-color);
    color: var(--white);
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    cursor: pointer;
}

.btn-corporate:hover {
    background: #2980b9;
    color: var(--white);
    text-decoration: none;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
}

.btn-corporate.btn-outline {
    background: transparent;
    border: 2px solid var(--accent-color);
    color: var(--accent-color);
}

.btn-corporate.btn-outline:hover {
    background: var(--accent-color);
    color: var(--white);
}

.pdf-viewer-container {
    position: relative;
    width: 100%;
    height: 800px;
    background: var(--light-gray);
}

.pdf-iframe {
    width: 100%;
    height: 100%;
    border: none;
    display: block;
}

.pdf-loading {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: var(--white);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    color: var(--dark-gray);
}

.loading-spinner {
    width: 50px;
    height: 50px;
    border: 4px solid var(--light-gray);
    border-top: 4px solid var(--accent-color);
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-bottom: 20px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.loading-text {
    font-size: 16px;
    font-weight: 500;
    margin: 0;
}

.loading-subtext {
    font-size: 14px;
    opacity: 0.7;
    margin: 5px 0 0 0;
}

.pdf-error {
    background: #fff5f5;
    border: 1px solid #feb2b2;
    color: #c53030;
    padding: 20px;
    text-align: center;
    border-radius: 5px;
    margin: 20px;
}

.pdf-error i {
    font-size: 48px;
    margin-bottom: 15px;
    display: block;
}

.pdf-info-bar {
    background: var(--light-gray);
    padding: 12px 25px;
    border-top: 1px solid #dee2e6;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 14px;
    color: var(--dark-gray);
}

.pdf-metadata {
    display: flex;
    gap: 20px;
    align-items: center;
}

.pdf-metadata span {
    display: flex;
    align-items: center;
    gap: 5px;
}

.refresh-btn {
    background: transparent;
    border: 1px solid var(--dark-gray);
    color: var(--dark-gray);
    padding: 5px 12px;
    border-radius: 3px;
    font-size: 12px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.refresh-btn:hover {
    background: var(--dark-gray);
    color: var(--white);
}

/* Responsive Design */
@media (max-width: 768px) {
    .pdf-viewer-wrapper {
        margin: 10px;
        padding: 0;
    }
    
    .pdf-header {
        padding: 20px;
    }
    
    .pdf-header-content {
        flex-direction: column;
        align-items: stretch;
        gap: 15px;
        text-align: center;
    }
    
    .pdf-title {
        justify-content: center;
        text-align: center;
    }
    
    .pdf-title h2 {
        font-size: 18px;
        text-align: center;
    }
    
    .pdf-viewer-container {
        height: 600px;
    }
    
    .pdf-actions {
        width: 100%;
        justify-content: stretch;
    }
    
    .btn-corporate {
        flex: 1;
        justify-content: center;
    }
    
    .pdf-info-bar {
        flex-direction: column;
        gap: 10px;
        text-align: center;
    }
    
    .pdf-metadata {
        flex-direction: column;
        gap: 10px;
    }
}

@media (max-width: 480px) {
    .pdf-viewer-container {
        height: 500px;
    }
    
    .pdf-title {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
    
    .pdf-description {
        margin-left: 0;
    }
}

/* Print Styles */
@media print {
    .pdf-header,
    .pdf-info-bar,
    .pdf-actions {
        display: none;
    }
    
    .pdf-container {
        box-shadow: none;
        border: none;
    }
    
    .pdf-viewer-container {
        height: auto;
        min-height: 800px;
    }
}
</style>

<section class="content">
    <div class="pdf-viewer-wrapper">
        
        <!-- Contenedor Principal del PDF -->
        <div class="pdf-container">
            
            <!-- Header del PDF -->
            <div class="pdf-header">
                <div class="pdf-header-content">
                    <div class="pdf-title">
                        <i class="<?= $pdfConfig['icon'] ?>"></i>
                        <div>
                            <h2><?= $pdfConfig['description'] ?></h2>
                        </div>
                    </div>
                    
                    <div class="pdf-actions">
                        <a href="<?= $pdfConfig['url'] ?>" target="_blank" class="btn-corporate">
                            <i class="fas fa-external-link-alt"></i>
                            Nueva pestaña
                        </a>
                        <button onclick="downloadPdf()" class="btn-corporate btn-outline">
                            <i class="fas fa-download"></i>
                            Descargar
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Visualizador del PDF -->
            <div class="pdf-viewer-container">
                <div class="pdf-loading" id="pdf-loading">
                    <div class="loading-spinner"></div>
                    <p class="loading-text">Cargando documento</p>
                    <p class="loading-subtext">Por favor espere...</p>
                </div>
                <iframe id="pdf-iframe" 
                        class="pdf-iframe" 
                        src="<?= $pdfConfig['url'] ?>" 
                        style="display: none;"
                        title="<?= $pdfConfig['description'] ?>">
                </iframe>
            </div>
            
            <!-- Barra de información -->
            <div class="pdf-info-bar">
                <div class="pdf-metadata">
                    <span>
                        <i class="fas fa-calendar"></i>
                        <?= date('d/m/Y H:i') ?>
                    </span>
                    <span>
                        <i class="fas fa-user"></i>
                        Usuario: <?= $config['th_per_id'] ?>
                    </span>
                    <?php if ($config['tipo'] === 'articulos_cliente_bodega' && !empty($config['id_localizacion'])): ?>
                    <span>
                        <i class="fas fa-map-marker-alt"></i>
                        Localización: <?= $config['id_localizacion'] ?>
                    </span>
                    <?php endif; ?>
                </div>
                
                <button onclick="refreshPdf()" class="refresh-btn" title="Actualizar documento">
                    <i class="fas fa-sync-alt"></i>
                    Actualizar
                </button>
            </div>
            
        </div>
        
    </div>
</section>

<script type="text/javascript">
$(document).ready(function() {
    initializePdfViewer();
});

/**
 * Inicializa el visualizador de PDF
 */
function initializePdfViewer() {
    const pdfIframe = document.getElementById('pdf-iframe');
    const pdfLoading = document.getElementById('pdf-loading');
    
    // Manejar carga exitosa del PDF
    pdfIframe.onload = function() {
        setTimeout(() => {
            pdfLoading.style.display = 'none';
            pdfIframe.style.display = 'block';
        }, 500); // Pequeño delay para mejor UX
    };
    
    // Manejar error de carga
    pdfIframe.onerror = function() {
        showPdfError();
    };
    
    // Timeout para detectar problemas de carga
    setTimeout(() => {
        if (pdfLoading.style.display !== 'none') {
            checkPdfLoad();
        }
    }, 10000); // 10 segundos timeout
}

/**
 * Verifica si el PDF se cargó correctamente
 */
function checkPdfLoad() {
    const pdfIframe = document.getElementById('pdf-iframe');
    const pdfLoading = document.getElementById('pdf-loading');
    
    try {
        // Intentar acceder al contenido del iframe
        if (pdfIframe.contentDocument || pdfIframe.contentWindow) {
            pdfLoading.style.display = 'none';
            pdfIframe.style.display = 'block';
        } else {
            showPdfError();
        }
    } catch (e) {
        // Si hay error de acceso, asumir que el PDF se cargó (cross-origin)
        pdfLoading.style.display = 'none';
        pdfIframe.style.display = 'block';
    }
}

/**
 * Muestra mensaje de error al cargar PDF
 */
function showPdfError() {
    const pdfLoading = document.getElementById('pdf-loading');
    pdfLoading.innerHTML = `
        <div class="pdf-error">
            <i class="fas fa-exclamation-triangle"></i>
            <h4>Error al cargar el documento</h4>
            <p>No se pudo cargar el archivo PDF. Por favor:</p>
            <ul style="text-align: left; display: inline-block;">
                <li>Verifique su conexión a internet</li>
                <li>Compruebe que el servidor esté disponible</li>
                <li>Intente actualizar la página</li>
            </ul>
            <button onclick="refreshPdf()" class="btn-corporate" style="margin-top: 15px;">
                <i class="fas fa-sync-alt"></i>
                Reintentar
            </button>
        </div>
    `;
}

/**
 * Refresca el PDF
 */
function refreshPdf() {
    const pdfIframe = document.getElementById('pdf-iframe');
    const pdfLoading = document.getElementById('pdf-loading');
    
    // Mostrar loading
    pdfLoading.style.display = 'flex';
    pdfLoading.innerHTML = `
        <div class="loading-spinner"></div>
        <p class="loading-text">Actualizando documento</p>
        <p class="loading-subtext">Por favor espere...</p>
    `;
    
    pdfIframe.style.display = 'none';
    
    // Recargar iframe
    const currentSrc = pdfIframe.src;
    pdfIframe.src = '';
    setTimeout(() => {
        pdfIframe.src = currentSrc + '&refresh=' + Date.now();
    }, 100);
}

/**
 * Descargar PDF
 */
function downloadPdf() {
    const pdfUrl = '<?= $pdfConfig['url'] ?>';
    const link = document.createElement('a');
    link.href = pdfUrl;
    link.download = '<?= $pdfConfig['description'] ?>_<?= date('Y-m-d') ?>.pdf';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

/**
 * Cambiar tipo de reporte (si se necesita en el futuro)
 */
function changeReportType(tipo) {
    const currentUrl = new URL(window.location.href);
    currentUrl.searchParams.set('tipo', tipo);
    window.location.href = currentUrl.toString();
}

// Optimizaciones para dispositivos móviles
if (window.innerWidth <= 768) {
    document.addEventListener('DOMContentLoaded', function() {
        // Ajustar altura en móviles
        const pdfContainer = document.querySelector('.pdf-viewer-container');
        if (pdfContainer && window.innerHeight < 600) {
            pdfContainer.style.height = (window.innerHeight - 200) + 'px';
        }
    });
}

// Manejo de redimensionado de ventana
window.addEventListener('resize', function() {
    if (window.innerWidth <= 768) {
        const pdfContainer = document.querySelector('.pdf-viewer-container');
        if (pdfContainer && window.innerHeight < 600) {
            pdfContainer.style.height = (window.innerHeight - 200) + 'px';
        }
    }
});
</script>

<?php include(dirname(__DIR__, 1) . '/cabeceras/footer.php'); ?>