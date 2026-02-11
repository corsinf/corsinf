<div class="tab-pane fade show active" id="tab_requisitos_intelectuales" role="tabpanel">
    <ul class="nav nav-tabs nav-tabs-custom mb-4" id="subMenuIntelectuales" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="tab-instruccion-btn" data-bs-toggle="tab"
                data-bs-target="#tab_instruccion_basica" type="button" role="tab">
                <i class="bx bx-book-open me-1"></i>
                Instrucción Básica Necesaria
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-experiencia-btn" data-bs-toggle="tab"
                data-bs-target="#tab_experiencia_necesaria" type="button" role="tab">
                <i class="bx bx-briefcase me-1"></i>
                Experiencia Necesaria
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-iniciativa-btn" data-bs-toggle="tab"
                data-bs-target="#tab_iniciativa_necesaria" type="button" role="tab">
                <i class="bx bx-bulb me-1"></i>
                Iniciativa Necesaria
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-aptitudes-btn" data-bs-toggle="tab"
                data-bs-target="#tab_aptitudes_necesarias" type="button" role="tab">
                <i class="bx bx-star me-1"></i>
                Aptitudes Necesarias
            </button>
        </li>
    </ul>

    <!-- Contenido de los sub-tabs -->
    <div class="tab-content" id="subMenuIntelectualesContent">
        <!-- Tab: Instrucción Básica -->
        <div class="tab-pane fade show active" id="tab_instruccion_basica" role="tabpanel">
            <?php include_once('../vista/TALENTO_HUMANO/CARGOS/MENU_ASPECTOS_EXTRINSECOS/REQUISITOS_INTELECTUALES/instruccion_basica.php'); ?>
        </div>

        <!-- Tab: Experiencia Necesaria -->
        <div class="tab-pane fade" id="tab_experiencia_necesaria" role="tabpanel">
            <?php include_once('../vista/TALENTO_HUMANO/CARGOS/MENU_ASPECTOS_EXTRINSECOS/REQUISITOS_INTELECTUALES/experiencia_necesaria.php'); ?>
        </div>

        <!-- Tab: Iniciativa Necesaria -->
        <div class="tab-pane fade" id="tab_iniciativa_necesaria" role="tabpanel">
            <?php include_once('../vista/TALENTO_HUMANO/CARGOS/MENU_ASPECTOS_EXTRINSECOS/REQUISITOS_INTELECTUALES/iniciativa_necesaria.php'); ?>
        </div>

        <!-- Tab: Aptitudes Necesarias -->
        <div class="tab-pane fade" id="tab_aptitudes_necesarias" role="tabpanel">
            <?php include_once('../vista/TALENTO_HUMANO/CARGOS/MENU_ASPECTOS_EXTRINSECOS/REQUISITOS_INTELECTUALES/aptitudes_necesarias.php'); ?>
        </div>
    </div>
</div>