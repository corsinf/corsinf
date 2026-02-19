<section class="py-1">
    <div class="row g-0 shadow-sm border rounded-3">
        <div class="col-md-3 bg-light border-end">
            <div class="nav flex-column nav-pills gap-2 p-2" id="v-pills-tab" role="tablist" aria-orientation="vertical">

                <button class="nav-link active py-3 px-3 border shadow-sm transition-all" data-bs-toggle="pill" data-bs-target="#tab_requisitos_intelectuales" type="button" role="tab">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-brain me-3 fs-5"></i>
                        <span>Requisitos Intelectuales</span>
                    </div>
                </button>

                <button class="nav-link py-3 px-3 border shadow-sm transition-all" data-bs-toggle="pill" data-bs-target="#tab_requisitos_fisicos" type="button" role="tab">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-body me-3 fs-5"></i>
                        <span>Requisitos Físicos</span>
                    </div>
                </button>

                <button class="nav-link py-3 px-3 border shadow-sm transition-all" data-bs-toggle="pill" data-bs-target="#tab_responsabilidades_implicitas" type="button" role="tab">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-list-check me-3 fs-5"></i>
                        <span>Responsabilidades</span>
                    </div>
                </button>

                <button class="nav-link py-3 px-3 border shadow-sm transition-all" data-bs-toggle="pill" data-bs-target="#tab_condiciones_trabajo" type="button" role="tab">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-briefcase-alt me-3 fs-5"></i>
                        <span>Condiciones de Trabajo</span>
                    </div>
                </button>
            </div>
        </div>

        <div class="col-md-9 bg-white">
            <div class="tab-content p-3" id="v-pills-tabContent" style="min-height: 400px;">

                <div class="tab-pane fade show active" id="tab_requisitos_intelectuales" role="tabpanel">
                    <?php include_once('../vista/TALENTO_HUMANO/CARGOS/MENU_ASPECTOS_EXTRINSECOS/requisitos_intelectuales.php'); ?>
                </div>

                <div class="tab-pane fade" id="tab_requisitos_fisicos" role="tabpanel">
                    <?php include_once('../vista/TALENTO_HUMANO/CARGOS/MENU_ASPECTOS_EXTRINSECOS/requisitos_fisicos.php'); ?>
                </div>

                <div class="tab-pane fade" id="tab_responsabilidades_implicitas" role="tabpanel">
                    <?php include_once('../vista/TALENTO_HUMANO/CARGOS/MENU_ASPECTOS_EXTRINSECOS/responsabilidades_implicitas.php'); ?>
                </div>

                <div class="tab-pane fade" id="tab_condiciones_trabajo" role="tabpanel">
                    <?php include_once('../vista/TALENTO_HUMANO/CARGOS/MENU_ASPECTOS_EXTRINSECOS/condiciones_trabajo.php'); ?>
                </div>

            </div>
        </div>
    </div>
</section>