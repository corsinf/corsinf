<div class="tab-content" id="subMenuIntelectualesContent">
    <!-- SIN NAVEGACIÓN DE TABS, TODO VISIBLE -->

    <div class="container-fluid">

        <!-- SECCIÓN 1: INSTRUCCIÓN BÁSICA -->
        <div class="mb-5">
            <div class="row mb-3">
                <div class="col-6 d-flex align-items-center">
                    <h4><i class="bx bx-book-open me-2"></i>Instrucción Básica Necesaria</h4>
                </div>
                <div class="col-6 d-flex justify-content-end">
                    <a href="#" class="text-success icon-hover d-flex align-items-center"
                        data-bs-toggle="modal" data-bs-target="#modal_instruccion_basica">
                        <i class='bx bx-plus-circle bx-sm me-1'></i>
                        <span>Agregar</span>
                    </a>
                </div>
            </div>
            <?php include_once('../vista/TALENTO_HUMANO/CARGOS/MENU_ASPECTOS_EXTRINSECOS/REQUISITOS_INTELECTUALES/instruccion_basica.php'); ?>
        </div>

        <hr class="my-4">

        <!-- SECCIÓN 2: EXPERIENCIA NECESARIA -->
        <div class="mb-5">
            <div class="row mb-3">
                <div class="col-6 d-flex align-items-center">
                    <h4><i class="bx bx-briefcase me-2"></i>Experiencia Necesaria</h4>
                </div>
                <div class="col-6 d-flex justify-content-end">
                    <a href="#" class="text-success icon-hover d-flex align-items-center"
                        data-bs-toggle="modal" data-bs-target="#modal_experiencia_necesaria">
                        <i class='bx bx-plus-circle bx-sm me-1'></i>
                        <span>Agregar</span>
                    </a>
                </div>
            </div>
            <?php include_once('../vista/TALENTO_HUMANO/CARGOS/MENU_ASPECTOS_EXTRINSECOS/REQUISITOS_INTELECTUALES/experiencia_necesaria.php'); ?>
        </div>

        <hr class="my-4">

        <!-- SECCIÓN 3: Idiomas -->
        <div class="mb-5">
            <div class="row mb-3">
                <div class="col-6 d-flex align-items-center">
                    <h4><i class="bx bx-bulb me-2"></i>Idiomas</h4>
                </div>
                <div class="col-6 d-flex justify-content-end">
                    <a href="#" class="text-success icon-hover d-flex align-items-center"
                        data-bs-toggle="modal" data-bs-target="#modal_agregar_idioma">
                        <i class='bx bx-plus-circle bx-sm me-1'></i>
                        <span>Agregar</span>
                    </a>
                </div>
            </div>
            <?php include_once('../vista/TALENTO_HUMANO/CARGOS/MENU_ASPECTOS_EXTRINSECOS/REQUISITOS_INTELECTUALES/idiomas.php'); ?>
        </div>

        <hr class="my-4">

        <!-- SECCIÓN 4: APTITUDES NECESARIAS -->
        <div class="mb-5">
            <div class="row mb-3">
                <div class="col-6 d-flex align-items-center">
                    <h4><i class="bx bx-star me-2"></i>Aptitudes Necesarias</h4>
                </div>
                <div class="col-6 d-flex justify-content-end">
                    <a href="#" class="text-success icon-hover d-flex align-items-center"
                        data-bs-toggle="modal" data-bs-target="#modal_agregar_aptitud">
                        <i class='bx bx-plus-circle bx-sm me-1'></i>
                        <span>Agregar</span>
                    </a>
                </div>
            </div>
            <?php include_once('../vista/TALENTO_HUMANO/CARGOS/MENU_ASPECTOS_EXTRINSECOS/REQUISITOS_INTELECTUALES/aptitudes_necesarias.php'); ?>
        </div>
        <hr class="my-4">

        <!-- SECCIÓN 4: Iniciativa Necesaria -->
        <div class="mb-5">
            <div class="row mb-3">
                <div class="col-6 d-flex align-items-center">
                    <h4><i class="bx bx-star me-2"></i>Iniciativa Necesaria</h4>
                </div>
                <div id="pnl_iniciativa_necesaria" class="col-6 justify-content-end d-none">
                    <a href="#" class="text-success icon-hover d-flex align-items-center"
                        data-bs-toggle="modal" data-bs-target="#modal_agregar_iniciativa">
                        <i class='bx bx-plus-circle bx-sm me-1'></i>
                        <span>Agregar</span>
                    </a>
                </div>
            </div>
            <?php include_once('../vista/TALENTO_HUMANO/CARGOS/MENU_ASPECTOS_EXTRINSECOS/REQUISITOS_INTELECTUALES/iniciativa_necesaria.php'); ?>
        </div>

    </div>
</div>