<?php
include(dirname(__DIR__, 3) . '/cabeceras/header4.php');

// Configuración y validación de parámetros
if (isset($_GET['tipo'])) {
    $config['tipo'] = in_array($_GET['tipo'], ['auditoria', 'articulos_cliente_bodega']) ? $_GET['tipo'] : 'articulos_cliente_bodega';
}

if (isset($_GET['id_persona'])) {
    $config['id_persona'] = ($_GET['id_persona']) ?: '';
}

if (isset($_GET['id_localizacion'])) {
    $config['id_localizacion'] = ($_GET['id_localizacion']) ?? '';
}

if (isset($_GET['id_empresa'])) {
    $config['id_empresa'] = ($_GET['id_empresa']) ?? '';
}

/**
 * Genera la URL del PDF según el tipo
 */
function generate_Pdf_Config(array $config): array
{
    $baseUrl = 'controlador/ACTIVOS_FIJOS/REPORTES/';

    $params = [
        'id_persona' => $config['id_persona'] ?? null,
        'id_localizacion' => $config['id_localizacion'] ?? null,
        'id_empresa' => $config['id_empresa'] ?? null,
    ];

    // Construir la descripción dinámica
    $hasCustodio = !empty($params['id_persona']);
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

    $queryParams = http_build_query(array_filter($params));

    if ($config['tipo'] === 'auditoria') {
        return [
            'url' => $baseUrl . '?reporte_auditoria_articulos=true&' . $queryParams,
            'title' => 'Reporte de Auditoría',
            'description' => $description,
            'icon' => 'fas fa-clipboard-check'
        ];
    }

    return [
        'url' => $baseUrl . '?reporte_articulos_custodio_localizacion=true&' . $queryParams,
        'title' => 'Reporte de Artículos',
        'description' => $description,
        'icon' => 'fas fa-boxes'
    ];
}

$pdfConfig = generate_Pdf_Config($config);
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
        background: linear-gradient(45deg, transparent 0%, rgba(255, 255, 255, 0.05) 50%, transparent 100%);
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
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
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

    /* Mejoras para móviles */
    .mobile-pdf-notice {
        display: none;
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        color: #856404;
        padding: 20px;
        text-align: center;
        border-radius: 5px;
        margin: 20px;
    }

    .mobile-pdf-notice i {
        font-size: 48px;
        margin-bottom: 15px;
        display: block;
        color: #f39c12;
    }

    .mobile-pdf-notice h4 {
        margin: 0 0 10px 0;
        color: #856404;
    }

    .mobile-pdf-actions {
        display: flex;
        gap: 10px;
        justify-content: center;
        margin-top: 20px;
        flex-wrap: wrap;
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
            height: 70vh;
            min-height: 400px;
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

        /* Mostrar aviso móvil en dispositivos pequeños */
        .mobile-pdf-notice {
            display: block;
        }

        .pdf-iframe {
            display: none;
        }
    }

    /* Detectar dispositivos iOS específicamente */
    @supports (-webkit-touch-callout: none) {
        @media (max-width: 768px) {
            .mobile-pdf-notice {
                display: block;
            }
            .pdf-iframe {
                display: none;
            }
        }
    }

    @media (max-width: 480px) {
        .pdf-viewer-container {
            height: 60vh;
            min-height: 300px;
        }

        .pdf-title {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }

        .pdf-description {
            margin-left: 0;
        }

        .mobile-pdf-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .mobile-pdf-actions .btn-corporate {
            width: 100%;
            margin-bottom: 10px;
        }
    }

    /* Print Styles */
    @media print {
        .pdf-header,
        .pdf-info-bar,
        .pdf-actions,
        .mobile-pdf-notice {
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
                
                <!-- Aviso para dispositivos móviles -->
                <div class="mobile-pdf-notice" id="mobile-notice">
                    <i class="fas fa-mobile-alt"></i>
                    <h4>Visualización en dispositivo móvil</h4>
                    <p>Los navegadores móviles tienen limitaciones para mostrar PDFs integrados. Utiliza una de estas opciones:</p>
                    <div class="mobile-pdf-actions">
                        <a href="<?= $pdfConfig['url'] ?>" target="_blank" class="btn-corporate">
                            <i class="fas fa-external-link-alt"></i>
                            Abrir en nueva pestaña
                        </a>
                        <button onclick="downloadPdf()" class="btn-corporate btn-outline">
                            <i class="fas fa-download"></i>
                            Descargar PDF
                        </button>
                        <button onclick="tryEmbedView()" class="btn-corporate" style="background: #27ae60;">
                            <i class="fas fa-eye"></i>
                            Intentar visualizar aquí
                        </button>
                    </div>
                </div>

                <div class="pdf-loading" id="pdf-loading" style="display: none;">
                    <div class="loading-spinner"></div>
                    <p class="loading-text">Cargando documento</p>
                    <p class="loading-subtext">Por favor espere...</p>
                </div>
                
                <iframe id="pdf-iframe"
                    class="pdf-iframe"
                    src=""
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
                        Usuario: <?= $config['id_persona'] ?>
                    </span>
                    <?php if ($config['tipo'] === 'articulos_cliente_bodega' && !empty($config['id_localizacion'])): ?>
                        <span>
                            <i class="fas fa-map-marker-alt"></i>
                            Localización: <?= $config['id_localizacion'] ?>
                        </span>
                    <?php endif; ?>
                </div>

                <button onclick="refresh_Pdf()" class="refresh-btn" title="Actualizar documento">
                    <i class="fas fa-sync-alt"></i>
                    Actualizar
                </button>
            </div>

        </div>

    </div>

</section>

<script type="text/javascript">
    $(document).ready(function() {
        
        // Detectar si es dispositivo móvil
        const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) || 
                         window.innerWidth <= 768;
        
        // Detectar específicamente iOS
        const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent);
        
        // En desktop, inicializar normalmente
        if (!isMobile) {
            $('#mobile-notice').hide();
            initializePdfViewer();
        } else {
            // En móvil, mostrar opciones
            $('#mobile-notice').show();
            $('#pdf-iframe').hide();
        }

        // Ajustar altura en móviles
        if (isMobile) {
            adjustMobileHeight();
            $(window).on('resize orientationchange', adjustMobileHeight);
        }

        /**
         * Ajusta la altura en dispositivos móviles
         */
        function adjustMobileHeight() {
            const windowHeight = $(window).height();
            const headerHeight = $('.pdf-header').outerHeight() || 0;
            const infoBarHeight = $('.pdf-info-bar').outerHeight() || 0;
            const availableHeight = windowHeight - headerHeight - infoBarHeight - 100; // 100px para márgenes
            
            if (availableHeight > 300) {
                $('.pdf-viewer-container').css('height', availableHeight + 'px');
            }
        }

        /**
         * Inicializa el visualizador de PDF
         */
        function initializePdfViewer() {
            const $pdfIframe = $('#pdf-iframe');
            const $pdfLoading = $('#pdf-loading');

            $pdfLoading.show();
            $pdfIframe.attr('src', '<?= $pdfConfig['url'] ?>');

            // Manejar carga exitosa del PDF
            $pdfIframe.on('load', function() {
                setTimeout(() => {
                    $pdfLoading.hide();
                    $pdfIframe.show();
                }, 500);
            });

            // Manejar error de carga
            $pdfIframe.on('error', function() {
                showPdfError();
            });

            // Timeout para detectar problemas de carga
            setTimeout(() => {
                if ($pdfLoading.is(':visible')) {
                    checkPdfLoad();
                }
            }, 10000);
        }

        /**
         * Intenta mostrar el PDF en móvil (función para el botón)
         */
        window.tryEmbedView = function() {
            $('#mobile-notice').hide();
            $('#pdf-loading').show();
            
            // Intentar diferentes métodos para móviles
            const pdfUrl = '<?= $pdfConfig['url'] ?>';
            const $iframe = $('#pdf-iframe');
            
            // Método 1: URL directa
            $iframe.attr('src', pdfUrl).show();
            
            // Si no funciona, probar con Google Docs Viewer
            setTimeout(() => {
                if ($('#pdf-loading').is(':visible')) {
                    const googleViewerUrl = `https://docs.google.com/viewer?url=${encodeURIComponent(pdfUrl)}&embedded=true`;
                    $iframe.attr('src', googleViewerUrl);
                }
            }, 3000);
            
            // Si tampoco funciona, mostrar error personalizado
            setTimeout(() => {
                if ($('#pdf-loading').is(':visible')) {
                    showMobileError();
                }
            }, 8000);
            
            // Manejar carga exitosa
            $iframe.off('load').on('load', function() {
                $('#pdf-loading').hide();
                $(this).show();
            });
        };

        /**
         * Muestra error específico para móviles
         */
        function showMobileError() {
            $('#pdf-loading').html(`
                <div class="pdf-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <h4>No se puede visualizar en este dispositivo</h4>
                    <p>Tu navegador móvil no soporta la visualización de PDFs integrados.</p>
                    <div style="margin-top: 20px;">
                        <a href="<?= $pdfConfig['url'] ?>" target="_blank" class="btn-corporate">
                            <i class="fas fa-external-link-alt"></i>
                            Abrir en nueva pestaña
                        </a>
                        <button onclick="downloadPdf()" class="btn-corporate btn-outline" style="margin-left: 10px;">
                            <i class="fas fa-download"></i>
                            Descargar
                        </button>
                    </div>
                </div>
            `);
        }

        /**
         * Verifica si el PDF se cargó correctamente
         */
        function checkPdfLoad() {
            const $pdfIframe = $('#pdf-iframe');
            const $pdfLoading = $('#pdf-loading');

            try {
                const iframeDoc = $pdfIframe[0].contentDocument || $pdfIframe[0].contentWindow;
                if (iframeDoc) {
                    $pdfLoading.hide();
                    $pdfIframe.show();
                } else {
                    showPdfError();
                }
            } catch (e) {
                $pdfLoading.hide();
                $pdfIframe.show();
            }
        }

        /**
         * Muestra mensaje de error al cargar PDF
         */
        function showPdfError() {
            $('#pdf-loading').html(`
                <div class="pdf-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <h4>Error al cargar el documento</h4>
                    <p>No se pudo cargar el archivo PDF. Por favor:</p>
                    <ul style="text-align: left; display: inline-block;">
                        <li>Verifique su conexión a internet</li>
                        <li>Compruebe que el servidor esté disponible</li>
                        <li>Intente actualizar la página</li>
                    </ul>
                    <button onclick="refresh_Pdf()" class="btn-corporate" style="margin-top: 15px;">
                        <i class="fas fa-sync-alt"></i>
                        Reintentar
                    </button>
                </div>
            `).show();
        }

        /**
         * Refresca el PDF
         */
        window.refresh_Pdf = function() {
            const $pdfIframe = $('#pdf-iframe');
            const $pdfLoading = $('#pdf-loading');

            $pdfLoading.html(`
                <div class="loading-spinner"></div>
                <p class="loading-text">Actualizando documento</p>
                <p class="loading-subtext">Por favor espere...</p>
            `).css('display', 'flex').show();

            $pdfIframe.hide();

            const currentSrc = '<?= $pdfConfig['url'] ?>';
            $pdfIframe.attr('src', '');
            setTimeout(() => {
                $pdfIframe.attr('src', currentSrc + '&refresh=' + Date.now());
            }, 100);
        };

        /**
         * Descargar PDF
         */
         window.downloadPdf = function() {
            const pdfUrl = '<?= $pdfConfig['url'] ?>';
            const link = $('<a>')
                .attr('href', pdfUrl)
                .attr('download', '<?= $pdfConfig['description'] ?>_<?= date('Y-m-d') ?>.pdf')
                .appendTo('body');

            link[0].click();
            link.remove();
        };

    });
</script>