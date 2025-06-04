<?php
include(dirname(__DIR__, 3) . '/cabeceras/header4.php');

// Configuración y validación de parámetros
// $config = [
//     'tipo' => 'articulos_cliente_bodega', // 'auditoria' o 'articulos_cliente_bodega'
//     'id_persona' => '1',
//     'id_localizacion' => '1'
// ];

// Sanitización de entrada
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
 * @param array $config
 * @return array
 */
function generate_Pdf_Config(array $config): array
{
    // $baseUrl = 'controlador/ACTIVOS_FIJOS/ac_reporte_acticulos_KalipsoC.php';
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

    $queryParams = http_build_query(array_filter($params)); // Limpia valores nulos

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
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
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

        initialize_Pdf_Viewer();

        // Ajustar altura en móviles al cargar
        if ($(window).width() <= 768 && $(window).height() < 600) {
            $('.pdf-viewer-container').css('height', ($(window).height() - 200) + 'px');
        }

        // Ajustar altura en móviles al redimensionar
        $(window).on('resize', function() {
            if ($(window).width() <= 768 && $(window).height() < 600) {
                $('.pdf-viewer-container').css('height', ($(window).height() - 200) + 'px');
            }
        });

        /**
         * Inicializa el visualizador de PDF
         */
        function initialize_Pdf_Viewer() {
            const $pdfIframe = $('#pdf-iframe');
            const $pdfLoading = $('#pdf-loading');

            // Manejar carga exitosa del PDF
            $pdfIframe.on('load', function() {
                setTimeout(() => {
                    $pdfLoading.hide();
                    $pdfIframe.show();
                }, 500);
            });

            // Manejar error de carga
            $pdfIframe.on('error', function() {
                show_Pdf_Error();
            });

            // Timeout para detectar problemas de carga
            setTimeout(() => {
                if ($pdfLoading.css('display') !== 'none') {
                    check_Pdf_Load();
                }
            }, 10000);
        }

        /**
         * Verifica si el PDF se cargó correctamente
         */
        function check_Pdf_Load() {
            const $pdfIframe = $('#pdf-iframe');
            const $pdfLoading = $('#pdf-loading');

            try {
                const iframeDoc = $pdfIframe[0].contentDocument || $pdfIframe[0].contentWindow;
                if (iframeDoc) {
                    $pdfLoading.hide();
                    $pdfIframe.show();
                } else {
                    show_Pdf_Error();
                }
            } catch (e) {
                $pdfLoading.hide();
                $pdfIframe.show();
            }
        }

        /**
         * Muestra mensaje de error al cargar PDF
         */
        function show_Pdf_Error() {
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
                <button id="btn-retry" class="btn-corporate" style="margin-top: 15px;">
                    <i class="fas fa-sync-alt"></i>
                    Reintentar
                </button>
            </div>
        `).show();

            $('#btn-retry').on('click', refresh_Pdf);
        }

        /**
         * Refresca el PDF
         */
        function refresh_Pdf() {
            const $pdfIframe = $('#pdf-iframe');
            const $pdfLoading = $('#pdf-loading');

            $pdfLoading.html(`
            <div class="loading-spinner"></div>
            <p class="loading-text">Actualizando documento</p>
            <p class="loading-subtext">Por favor espere...</p>
        `).css('display', 'flex');

            $pdfIframe.hide();

            const currentSrc = $pdfIframe.attr('src');
            $pdfIframe.attr('src', '');
            setTimeout(() => {
                $pdfIframe.attr('src', currentSrc + '&refresh=' + Date.now());
            }, 100);
        }

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

        /**
         * Cambiar tipo de reporte
         */
        window.changeReportType = function(tipo) {
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('tipo', tipo);
            window.location.href = currentUrl.toString();
        };

    });
</script>