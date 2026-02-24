    <!-- SECCIÓN 1: INSTRUCCIÓN BÁSICA -->
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-end mb-3">
            <div class="d-flex align-items-center">
                <h5 class="mb-0 fw-bold text-dark">
                    <i class="bx bx-book-open me-2 text-primary"></i>
                    Instrucción Básica Necesaria
                </h5>
            </div>
            <div>
                <a href="#" class="btn btn-outline-success btn-xs d-flex align-items-center fw-semibold px-3"
                    data-bs-toggle="modal" data-bs-target="#modal_instruccion_basica">
                    <i class='bx bx-plus-circle me-1'></i>
                    Agregar
                </a>
            </div>
        </div>

        <div class="px-1">
            <?php include_once('../vista/TALENTO_HUMANO/CARGOS/MENU_ASPECTOS_EXTRINSECOS/REQUISITOS_INTELECTUALES/instruccion_basica.php'); ?>
        </div>
    </div>

    <hr class="my-4" style="opacity: 0.6; background-color: #2563eb; height: 2px; border: none;">

    <!-- SECCIÓN 5: ÁREA DE ESTUDIO -->
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-end mb-3">
            <div class="d-flex align-items-center">
                <h5 class="mb-0 fw-bold text-dark">
                    <i class="bx bx-book-open me-2 text-primary"></i>
                    Área de Estudio
                </h5>
            </div>
            <div>
                <a href="#" class="btn btn-outline-success btn-xs d-flex align-items-center fw-semibold px-3"
                    data-bs-toggle="modal" data-bs-target="#modal_area_estudios">
                    <i class='bx bx-plus-circle me-1'></i>
                    Agregar
                </a>
            </div>
        </div>

        <div class="px-1">
            <?php include_once('../vista/TALENTO_HUMANO/CARGOS/MENU_ASPECTOS_EXTRINSECOS/REQUISITOS_INTELECTUALES/area_estudio.php'); ?>
        </div>
    </div>

    <hr class="my-4" style="opacity: 0.6; background-color: #2563eb; height: 2px; border: none;">

    <!-- SECCIÓN 2: EXPERIENCIA NECESARIA -->
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-end mb-3">
            <div class="d-flex align-items-center">
                <h5 class="mb-0 fw-bold text-dark">
                    <i class="bx bx-briefcase me-2 text-primary"></i>
                    Experiencia Necesaria
                </h5>
            </div>
            <div>
                <a href="#" class="btn btn-outline-success btn-xs d-flex align-items-center fw-semibold px-3"
                    data-bs-toggle="modal" data-bs-target="#modal_experiencia_necesaria">
                    <i class='bx bx-plus-circle me-1'></i>
                    Agregar
                </a>
            </div>
        </div>

        <div class="px-1">
            <?php include_once('../vista/TALENTO_HUMANO/CARGOS/MENU_ASPECTOS_EXTRINSECOS/REQUISITOS_INTELECTUALES/experiencia_necesaria.php'); ?>
        </div>
    </div>

    <hr class="my-4" style="opacity: 0.6; background-color: #2563eb; height: 2px; border: none;">

    <!-- SECCIÓN 3: Idiomas -->
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-end mb-3">
            <div class="d-flex align-items-center">
                <h5 class="mb-0 fw-bold text-dark">
                    <i class="bx bx-bulb me-2 text-primary"></i>
                    Idiomas
                </h5>
            </div>
            <div>
                <a href="#" class="btn btn-outline-success btn-xs d-flex align-items-center fw-semibold px-3"
                    data-bs-toggle="modal" data-bs-target="#modal_agregar_idioma">
                    <i class='bx bx-plus-circle me-1'></i>
                    Agregar
                </a>
            </div>
        </div>

        <div class="px-1">
            <?php include_once('../vista/TALENTO_HUMANO/CARGOS/MENU_ASPECTOS_EXTRINSECOS/REQUISITOS_INTELECTUALES/idiomas.php'); ?>
        </div>
    </div>

    <hr class="my-4" style="opacity: 0.6; background-color: #2563eb; height: 2px; border: none;">

    <!-- SECCIÓN 4: APTITUDES NECESARIAS -->
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-end mb-3">
            <div class="d-flex align-items-center">
                <h5 class="mb-0 fw-bold text-dark">
                    <i class="bx bx-star me-2 text-primary"></i>
                    Aptitudes Necesarias
                </h5>
            </div>
            <div>
                <a href="#" class="btn btn-outline-success btn-xs d-flex align-items-center fw-semibold px-3"
                    data-bs-toggle="modal" data-bs-target="#modal_agregar_aptitud">
                    <i class='bx bx-plus-circle me-1'></i>
                    Agregar
                </a>
            </div>
        </div>

        <div class="px-1">
            <?php include_once('../vista/TALENTO_HUMANO/CARGOS/MENU_ASPECTOS_EXTRINSECOS/REQUISITOS_INTELECTUALES/aptitudes_necesarias.php'); ?>
        </div>
    </div>

    <hr class="my-4" style="opacity: 0.6; background-color: #2563eb; height: 2px; border: none;">

    <!-- SECCIÓN 4: Iniciativa Necesaria -->
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-end mb-3">
            <div class="d-flex align-items-center">
                <h5 class="mb-0 fw-bold text-dark">
                    <i class="bx bx-bulb me-2 text-primary"></i>
                    Iniciativa Necesaria
                </h5>
            </div>
            <?php if (!$es_plaza) { ?>
                <div id="pnl_iniciativa_necesaria" class="d-none">
                    <a href="#" class="btn btn-outline-success btn-xs d-flex align-items-center fw-semibold px-3"
                        data-bs-toggle="modal" data-bs-target="#modal_agregar_iniciativa">
                        <i class='bx bx-plus-circle me-1'></i>
                        Agregar
                    </a>
                </div>
            <?php } ?>
        </div>

        <div class="px-1">
            <?php include_once('../vista/TALENTO_HUMANO/CARGOS/MENU_ASPECTOS_EXTRINSECOS/REQUISITOS_INTELECTUALES/iniciativa_necesaria.php'); ?>
        </div>
    </div>