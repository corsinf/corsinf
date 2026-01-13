<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
    
    :root {
        --primary-color: #35A6DF;
        --primary-dark: #1B78B6;
        --secondary-color: #20c997;
        --accent-color: #6366f1;
        --text-dark: #1e293b;
        --text-medium: #475569;
        --text-light: #64748b;
        --bg-light: #f8fafc;
        --bg-card: #ffffff;
        --border-color: #e2e8f0;
        --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.08);
        --shadow-lg: 0 10px 30px rgba(0, 0, 0, 0.12);
    }

    .page-wrapper {
        padding: 3rem 0;
    }

    .container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    .page-header {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        border-radius: 20px;
        padding: 3rem;
        margin-bottom: 3rem;
        box-shadow: var(--shadow-lg);
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        transform: translate(30%, -30%);
    }

    .page-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 250px;
        height: 250px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 50%;
        transform: translate(-30%, 30%);
    }

    .breadcrumb-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: white;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .breadcrumb-title i {
        font-size: 2.5rem;
    }

    .breadcrumb-subtitle {
        color: rgba(255, 255, 255, 0.9);
        font-size: 1.1rem;
        position: relative;
        z-index: 1;
    }

    #indice {
        position: sticky;
        top: 20px;
        background: var(--bg-card);
        border-radius: 16px;
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
    }

    #indice:hover {
        box-shadow: var(--shadow-lg);
    }

    #indice .card-body {
        padding: 2rem;
    }

    #indice h5 {
        color: var(--primary-color);
        font-weight: 700;
        margin-bottom: 1.5rem;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    #indice h5 i {
        font-size: 1.5rem;
    }

    .list-group-item {
        border: none;
        padding: 0.75rem 1rem;
        background: transparent;
        transition: all 0.3s ease;
        border-radius: 8px;
        margin-bottom: 0.25rem;
        color: var(--text-medium);
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .list-group-item:hover {
        background: linear-gradient(90deg, rgba(53, 166, 223, 0.08) 0%, transparent 100%);
        color: var(--primary-color);
        transform: translateX(5px);
    }

    .list-group-item i {
        color: var(--primary-color);
        font-size: 1.2rem;
        transition: transform 0.3s ease;
    }

    .list-group-item:hover i {
        transform: translateX(3px);
    }

    .main-card {
        background: var(--bg-card);
        border-radius: 16px;
        box-shadow: var(--shadow-md);
        padding: 3rem;
        border: 1px solid var(--border-color);
    }

    .content-section {
        scroll-margin-top: 100px;
        margin-bottom: 3.5rem;
        animation: fadeInUp 0.6s ease;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .content-section h5 {
        color: var(--text-dark);
        font-weight: 700;
        margin-bottom: 1.5rem;
        font-size: 1.6rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding-bottom: 1rem;
        border-bottom: 3px solid var(--primary-color);
    }

    .content-section h5 i {
        color: var(--primary-color);
        font-size: 1.8rem;
    }

    .content-section p {
        line-height: 1.8;
        margin-bottom: 1.25rem;
        color: var(--text-medium);
        font-size: 1.05rem;
    }

    .content-section ul {
        padding-left: 0;
        list-style: none;
    }

    .content-section ul li {
        padding: 1rem;
        line-height: 1.8;
        background: var(--bg-light);
        border-radius: 10px;
        margin-bottom: 0.75rem;
        border-left: 4px solid var(--primary-color);
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        transition: all 0.3s ease;
    }

    .content-section ul li:hover {
        background: white;
        box-shadow: var(--shadow-sm);
        transform: translateX(5px);
    }

    .content-section ul li i {
        color: var(--primary-color);
        font-size: 1.3rem;
        margin-top: 0.2rem;
        flex-shrink: 0;
    }

    .content-section ul li strong {
        color: var(--text-dark);
        font-weight: 600;
    }

    .expandable-list {
        display: grid;
        gap: 1rem;
    }

    .expandable-list .list-group-item {
        background: linear-gradient(135deg, var(--bg-light) 0%, white 100%);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        padding: 1.25rem 1.5rem;
        box-shadow: var(--shadow-sm);
        position: relative;
        overflow: hidden;
    }

    .expandable-list .list-group-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--primary-color);
        transform: scaleY(0);
        transition: transform 0.3s ease;
    }

    .expandable-list .list-group-item:hover::before {
        transform: scaleY(1);
    }

    .expandable-list .list-group-item:hover {
        background: white;
        box-shadow: var(--shadow-md);
        transform: translateY(-3px);
        border-color: var(--primary-color);
    }

    .expandable-list .list-group-item i {
        font-size: 1.5rem;
        color: var(--primary-color);
        transition: transform 0.3s ease;
    }

    .expandable-list .list-group-item:hover i {
        transform: rotate(90deg);
    }

    .info-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        color: var(--primary-dark);
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
        margin-top: 1rem;
        border: 1px solid #93c5fd;
    }

    .info-badge i {
        font-size: 1.1rem;
    }

    .modal-content {
        border-radius: 20px;
        border: none;
        box-shadow: var(--shadow-lg);
        overflow: hidden;
    }

    .modal-header {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        padding: 2rem;
        border: none;
    }

    .modal-header h5 {
        color: white;
        margin: 0;
        font-weight: 700;
        font-size: 1.3rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .modal-header h5 i {
        font-size: 1.5rem;
    }

    .modal-header .btn-close {
        filter: brightness(0) invert(1);
        opacity: 0.9;
    }

    .modal-header .btn-close:hover {
        opacity: 1;
    }

    .modal-body {
        padding: 2rem;
        background: var(--bg-light);
    }

    .modal-body p {
        color: var(--text-medium);
        line-height: 1.8;
        margin-bottom: 1rem;
    }

    .modal-body .list-group-item {
        border: 1px solid var(--border-color);
        padding: 1.25rem;
        margin-bottom: 0.75rem;
        border-radius: 10px;
        background: white;
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        box-shadow: var(--shadow-sm);
    }

    .modal-body .list-group-item::before {
        content: '✓';
        display: flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        background: var(--primary-color);
        color: white;
        border-radius: 50%;
        font-weight: bold;
        font-size: 0.9rem;
        flex-shrink: 0;
    }

    .contact-card {
        background: linear-gradient(135deg, #f8fafc 0%, white 100%);
        border-radius: 12px;
        padding: 1.5rem;
        border: 2px solid var(--border-color);
        margin-top: 1rem;
    }

    .contact-card li {
        background: white !important;
        border-left: 4px solid var(--secondary-color) !important;
    }

    @media (max-width: 991px) {
        #indice {
            position: relative;
            margin-bottom: 2rem;
        }

        .page-header {
            padding: 2rem;
        }

        .breadcrumb-title {
            font-size: 2rem;
        }

        .main-card {
            padding: 2rem;
        }

        .content-section h5 {
            font-size: 1.4rem;
        }
    }

    @media (max-width: 576px) {
        .container {
            padding: 0 1rem;
        }

        .page-header {
            padding: 1.5rem;
        }

        .breadcrumb-title {
            font-size: 1.5rem;
        }

        .main-card {
            padding: 1.5rem;
        }
    }

    .scroll-top {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: var(--shadow-lg);
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        z-index: 1000;
    }

    .scroll-top.visible {
        opacity: 1;
        visibility: visible;
    }

    .scroll-top:hover {
        transform: translateY(-5px);
    }
</style>

<body>
    <div class="page-wrapper">
        <div class="container">
            <div class="page-header">
                <div class="breadcrumb-title">
                    <i class='bx bxs-shield-alt-2'></i>
                    Política de Privacidad
                </div>
                <div class="breadcrumb-subtitle">
                    Tu privacidad es nuestra prioridad. Conoce cómo protegemos tu información.
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-lg-3">
                    <div class="card" id="indice">
                        <div class="card-body">
                            <h5><i class='bx bx-list-ul'></i>Contenido</h5>
                            <div class="fm-menu">
                                <div class="list-group list-group-flush">
                                    <a href="#pnl_introduccion" class="list-group-item">
                                        <i class='bx bx-info-circle'></i><span>Introducción</span>
                                    </a>
                                    <a href="#pnl_informacion_recopilada" class="list-group-item">
                                        <i class='bx bx-data'></i><span>Información recopilada</span>
                                    </a>
                                    <a href="#pnl_uso_informacion" class="list-group-item">
                                        <i class='bx bx-cog'></i><span>Uso de información</span>
                                    </a>
                                    <a href="#pnl_tiempo_conservacion" class="list-group-item">
                                        <i class='bx bx-time-five'></i><span>Tiempos de conservación</span>
                                    </a>
                                    <a href="#pnl_derechos_usuarios" class="list-group-item">
                                        <i class='bx bx-user-check'></i><span>Derechos de usuarios</span>
                                    </a>
                                    <a href="#pnl_responsabilidad_usuarios" class="list-group-item">
                                        <i class='bx bx-check-shield'></i><span>Responsabilidades</span>
                                    </a>
                                    <a href="#pnl_seguridad_datos" class="list-group-item">
                                        <i class='bx bx-lock-alt'></i><span>Seguridad de datos</span>
                                    </a>
                                    <a href="#pnl_informacion_terceros" class="list-group-item">
                                        <i class='bx bx-share-alt'></i><span>Terceros</span>
                                    </a>
                                    <a href="#pnl_contacto" class="list-group-item">
                                        <i class='bx bx-envelope'></i><span>Contacto</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-9">
                    <div class="main-card">
                        <div id="pnl_introduccion" class="content-section">
                            <h5><i class='bx bx-book-open'></i>Introducción</h5>
                            <p>En Corsinf valoramos su privacidad y nos comprometemos a proteger sus datos personales. Esta política de privacidad describe cómo recopilamos, usamos, almacenamos y protegemos su información. También te informamos cuáles son tus derechos. Esta política de privacidad rige para toda la información personal que recibe Corsinf sobre personas que no son empleados de Corsinf.</p>
                            <div class="info-badge">
                                <i class='bx bx-shield-alt-2'></i>
                                Cumplimos con la Ley Orgánica de Protección de Datos Personales del Ecuador
                            </div>
                        </div>

                        <div id="pnl_informacion_recopilada" class="content-section">
                            <h5><i class='bx bx-collection'></i>Información que recopilamos</h5>
                            <p>Recopilamos los siguientes tipos de información:</p>
                            <ul class="list-unstyled">
                                <li>
                                    <i class='bx bx-id-card'></i>
                                    <div>
                                        <strong>Datos de identificación:</strong> nombre, dirección, número de teléfono.
                                    </div>
                                </li>
                                <li>
                                    <i class='bx bx-log-in'></i>
                                    <div>
                                        <strong>Datos de inicio de sesión:</strong> correo electrónico, contraseña, información de la computadora y la conexión.
                                    </div>
                                </li>
                                <li>
                                    <i class='bx bx-bar-chart-alt-2'></i>
                                    <div>
                                        <strong>Datos de uso:</strong> información sobre cómo utiliza nuestros servicios y productos.
                                    </div>
                                </li>
                                <li>
                                    <i class='bx bx-heart'></i>
                                    <div>
                                        <strong>Datos de preferencias:</strong> información sobre sus intereses y preferencias.
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <div id="pnl_uso_informacion" class="content-section">
                            <h5><i class='bx bx-wrench'></i>Uso de su información</h5>
                            <p>Usamos su información para los siguientes propósitos:</p>
                            <div class="expandable-list mb-3">
                                <a href="#" class="list-group-item" data-bs-toggle="modal" data-bs-target="#modal_servicios">
                                    <i class='bx bx-server'></i>Proveer y gestionar nuestros servicios
                                </a>
                                <a href="#" class="list-group-item" data-bs-toggle="modal" data-bs-target="#modal_comunicarnos">
                                    <i class='bx bx-message-square-dots'></i>Comunicarnos contigo
                                </a>
                            </div>
                            <p>En Corsinf estamos comprometidos en cumplir todas las obligaciones legales y contractuales con el objetivo de garantizar la seguridad de su información mientras nos mantenemos bajo el marco legal del país.</p>
                        </div>

                        <div id="pnl_tiempo_conservacion" class="content-section">
                            <h5><i class='bx bx-history'></i>Tiempos de conservación de los datos</h5>
                            <p>Retenemos sus datos personales el tiempo necesario para cumplir con los propósitos para los que se recopilaron, incluyendo el cumplimiento de obligaciones legales, contables o de informes. Los criterios utilizados para determinar los períodos de retención incluyen:</p>
                            <ul class="list-unstyled">
                                <li>
                                    <i class='bx bx-time'></i>
                                    <div>El tiempo durante el cual mantenemos una relación continua con usted y le proporcionamos nuestros servicios.</div>
                                </li>
                                <li>
                                    <i class='bx bx-file-blank'></i>
                                    <div>La existencia de una obligación legal a la que estemos sujetos.</div>
                                </li>
                                <li>
                                    <i class='bx bx-alarm'></i>
                                    <div>El período de tiempo en el que se pueda presentar una reclamación contra nosotros.</div>
                                </li>
                            </ul>
                        </div>

                        <div id="pnl_derechos_usuarios" class="content-section">
                            <h5><i class='bx bx-user-voice'></i>Derechos de los usuarios</h5>
                            <p>De acuerdo con la Ley Orgánica de Protección de Datos Personales, usted tiene los siguientes derechos:</p>
                            <ul class="list-unstyled">
                                <li>
                                    <i class='bx bx-search-alt'></i>
                                    <div><strong>Acceso:</strong> Puede solicitar una copia de sus datos personales que tenemos.</div>
                                </li>
                                <li>
                                    <i class='bx bx-edit'></i>
                                    <div><strong>Rectificación y actualización:</strong> Puede solicitar la corrección de cualquier dato incorrecto o incompleto.</div>
                                </li>
                                <li>
                                    <i class='bx bx-trash'></i>
                                    <div><strong>Eliminación:</strong> Puede solicitar la eliminación de sus datos personales cuando ya no sean necesarios.</div>
                                </li>
                                <li>
                                    <i class='bx bx-block'></i>
                                    <div><strong>Oposición:</strong> Puede oponerse al tratamiento de sus datos personales por motivos relacionados con su situación particular.</div>
                                </li>
                                <li>
                                    <i class='bx bx-transfer'></i>
                                    <div><strong>Portabilidad:</strong> Puede solicitar que transfiramos sus datos a otra organización o a usted en un formato estructurado.</div>
                                </li>
                                <li>
                                    <i class='bx bx-pause'></i>
                                    <div><strong>Suspensión:</strong> Puede solicitar la suspensión del tratamiento de sus datos.</div>
                                </li>
                            </ul>
                        </div>

                        <div id="pnl_responsabilidad_usuarios" class="content-section">
                            <h5><i class='bx bx-shield-quarter'></i>Responsabilidades del usuario</h5>
                            <p>En Corsinf respetamos y protegemos la privacidad de los datos personales de nuestros usuarios. Para garantizar una experiencia segura y conforme a la normativa, pedimos a nuestros usuarios cumplan con las siguientes responsabilidades:</p>
                            <div class="expandable-list">
                                <a href="#" class="list-group-item" data-bs-toggle="modal" data-bs-target="#modal_proveer">
                                    <i class='bx bx-check-circle'></i>Proveer información veraz y actualizada
                                </a>
                                <a href="#" class="list-group-item" data-bs-toggle="modal" data-bs-target="#modal_credenciales">
                                    <i class='bx bx-key'></i>Protección de sus credenciales
                                </a>
                                <a href="#" class="list-group-item" data-bs-toggle="modal" data-bs-target="#modal_normativa">
                                    <i class='bx bx-book'></i>Cumplimiento con la normativa de protección de datos
                                </a>
                                <a href="#" class="list-group-item" data-bs-toggle="modal" data-bs-target="#modal_incidentes">
                                    <i class='bx bx-error-alt'></i>Notificación de incidentes
                                </a>
                                <a href="#" class="list-group-item" data-bs-toggle="modal" data-bs-target="#modal_adecuado">
                                    <i class='bx bx-slider-alt'></i>Uso adecuado de los servicios
                                </a>
                                <a href="#" class="list-group-item" data-bs-toggle="modal" data-bs-target="#modal_derechos">
                                    <i class='bx bx-user-pin'></i>Derechos de los titulares de datos
                                </a>
                            </div>
                        </div>

                        <div id="pnl_seguridad_datos" class="content-section">
                            <h5><i class='bx bx-lock-open-alt'></i>Seguridad de los Datos</h5>
                            <p>Implementamos medidas razonables para garantizar la seguridad de sus datos personales y protegerlos de pérdidas, usos indebidos, el acceso no autorizado, la alteración, divulgación y destrucción. Corsinf ha implementado los procedimientos necesarios para asegurar y proteger su información.</p>
                            <div class="info-badge">
                                <i class='bx bx-check-double'></i>
                                Protocolos de seguridad certificados y actualizados constantemente
                            </div>
                        </div>

                        <div id="pnl_informacion_terceros" class="content-section">
                            <h5><i class='bx bx-group'></i>Compartir información con terceros</h5>
                            <p>Podemos compartir su información con socios y proveedores de servicios terceros que nos ayudan a operar nuestro negocio, tales como procesamiento de pagos y servicios de marketing. Estos proveedores están obligados a proteger su información y solo pueden usarla para los fines específicos para los cuales fue compartida.</p>
                        </div>

                        <div id="pnl_contacto" class="content-section">
                            <h5><i class='bx bx-phone-call'></i>Contacto</h5>
                            <p>Si tiene preguntas o inquietudes sobre nuestra política de privacidad o sobre el manejo de sus datos personales, puede contactarnos a través de:</p>
                            <div class="contact-card">
                                <ul class="list-unstyled">
                                    <li>
                                        <i class='bx bx-envelope'></i>
                                        <div><strong>Correo electrónico:</strong> contacto@corsinf.com</div>
                                    </li>
                                    <li>
                                        <i class='bx bx-phone'></i>
                                        <div><strong>Teléfono:</strong> (+593) 99-921-9738</div>
                                    </li>
                                    <li>
                                        <i class='bx bx-map'></i>
                                        <div><strong>Dirección:</strong> De los Motilones, N40-345. Quito, Ecuador</div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="scroll-top" id="scrollTop">
        <i class='bx bx-up-arrow-alt' style="font-size: 1.5rem;"></i>
    </div>

    <!-- Modales -->
    <div class="modal fade" id="modal_servicios" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5><i class='bx bx-server'></i>Proveer y gestionar nuestros servicios</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Utilizamos la información que tenemos para proporcionar y mejorar nuestros servicios. Esto incluye la personalización de funciones, contenido y recomendaciones dentro de nuestra plataforma, como dashboards, informes y notificaciones. Utilizamos la información que decides proporcionar para estos fines, pero no para mostrarte anuncios.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_comunicarnos" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5><i class='bx bx-message-square-dots'></i>Comunicarnos contigo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Nos comunicamos contigo de diferentes maneras para garantizar que recibas la mejor experiencia posible con nuestros servicios. Por ejemplo:</p>
                    <ul class="list-group">
                        <li class="list-group-item">Enviamos mensajes sobre los productos y servicios que usas a través del correo electrónico registrado en tu cuenta.</li>
                        <li class="list-group-item">Te informamos sobre nuestras políticas y condiciones del servicio para mantenerte actualizado sobre cualquier cambio.</li>
                        <li class="list-group-item">Cuando te pones en contacto con nosotros con preguntas o inquietudes, te respondemos por correo electrónico para brindarte la asistencia necesaria.</li>
                        <li class="list-group-item">Facilitamos la comunicación con nuestro servicio de atención al cliente cuando indicas que tienes preguntas o inquietudes sobre nuestros productos y servicios.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_proveer" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5><i class='bx bx-check-circle'></i>Proveer información veraz y actualizada</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Los usuarios deben asegurarse de que los datos personales proporcionados durante el registro y el uso de nuestros servicios sean precisos, completos y estén actualizados. La entrega de información incorrecta o desactualizada puede afectar la calidad del servicio y nuestras comunicaciones.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_credenciales" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5><i class='bx bx-key'></i>Protección de sus credenciales</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Es responsabilidad del usuario mantener la confidencialidad de su nombre, contraseña y cualquier otra credencial de acceso a nuestros servicios. Recomendamos no compartir estas credenciales con terceros y tomar las medidas necesarias para prevenir accesos no autorizados a su cuenta.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_normativa" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5><i class='bx bx-book'></i>Cumplimiento con la normativa de protección de datos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Los usuarios deben utilizar nuestros servicios de manera que cumplan con la Ley Orgánica de Protección de Datos Personales del Ecuador y otras normativas aplicables. Esto incluye abstenerse de utilizar nuestros servicios para recopilar, almacenar o procesar datos personales de manera ilegal o sin el consentimiento adecuado de los titulares de los datos.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_incidentes" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5><i class='bx bx-error-alt'></i>Notificación de incidentes</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>En caso de detectar cualquier acceso no autorizado, uso indebido o violación de la seguridad relacionada con su cuenta o los datos personales que manejamos, los usuarios deben notificar inmediatamente a nuestro equipo de atención al cliente. Esto nos permitirá tomar las acciones necesarias para mitigar los riesgos y proteger la información.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_adecuado" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5><i class='bx bx-slider-alt'></i>Uso adecuado de los servicios</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Los usuarios deben utilizar nuestros servicios según nuestros términos y condiciones, y abstenerse de dañar, sobrecargar o deteriorar nuestra infraestructura tecnológica o interferir con el uso y disfrute de los servicios de otros usuarios.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_derechos" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5><i class='bx bx-user-pin'></i>Derechos de los titulares de datos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Los usuarios que actúen como responsables del tratamiento de datos personales dentro de nuestros servicios deben respetar los derechos de los titulares de los datos, tales como el derecho a acceso, rectificación, actualización, eliminación, oposición y portabilidad de los datos personales, conforme a lo establecido en la Ley Orgánica de Protección de Datos Personales del Ecuador.</p>
                    <p>Cumpliendo con estas responsabilidades, los usuarios contribuyen a mantener un entorno seguro y confiable para todos los que utilizan nuestros servicios.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });

       
        const sections = document.querySelectorAll('.content-section');
        const menuItems = document.querySelectorAll('#indice .list-group-item');

        window.addEventListener('scroll', () => {
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                if (pageYOffset >= sectionTop - 100) {
                    current = section.getAttribute('id');
                }
            });

            menuItems.forEach(item => {
                item.classList.remove('active');
                if (item.getAttribute('href') === `#${current}`) {
                    item.classList.add('active');
                }
            });
        });
    </script>
</body>