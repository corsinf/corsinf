 <?php
    $tab_pane_postulante_activo = 'show active';
    if (isset($_GET['_persona_nomina']) && $_GET['_persona_nomina'] == 'true') {
        $tab_pane_postulante_activo = '';
    }
    ?>

 <!-- Primera Sección, Historial Laboral -->
 <div class="tab-pane fade <?= $tab_pane_postulante_activo ?>" id="tab_experiencia" role="tabpanel">
     <div class="card">
         <div class="d-flex flex-column mx-4">
             <!-- Experiencia Previa -->
             <div class="card-body">
                 <div class="mb-2">
                     <div class="row">
                         <div class="col-9 d-flex align-items-center">
                             <h6 class="mb-0 fw-bold text-primary">Experiencia Previa:
                             </h6>
                         </div>

                         <div class="col-3 d-flex justify-content-end">
                             <a href="#"
                                 class="text-success icon-hover d-flex align-items-center"
                                 data-bs-toggle="modal"
                                 data-bs-target="#modal_agregar_experiencia">
                                 <i class='bx bx-plus-circle bx-sm me-1'></i>
                                 <span>Agregar</span>
                             </a>
                         </div>
                     </div>
                 </div>
                 <hr>

                 <?php include_once('../vista/TALENTO_HUMANO/POSTULANTES/pos_experiencia_previa.php'); ?>

             </div>
             <!-- Formación Académica -->
             <div class="card-body">
                 <div class="mb-2">
                     <div class="row">
                         <div class="col-9 d-flex align-items-center">
                             <h6 class="mb-0 fw-bold text-primary">Formación Académica:
                             </h6>
                         </div>
                         <div class="col-3 d-flex justify-content-end">
                             <a href="#"
                                 class="text-success icon-hover d-flex align-items-center"
                                 id="btn_modal_agregar_formacion_academica"
                                 data-bs-toggle="modal"
                                 data-bs-target="#modal_agregar_formacion">
                                 <i class='bx bx-plus-circle bx-sm me-1'></i>
                                 <span>Agregar</span>
                             </a>
                         </div>
                     </div>
                 </div>
                 <hr>

                 <?php include_once('../vista/TALENTO_HUMANO/POSTULANTES/pos_formacion_academica.php'); ?>

             </div>
             <!-- Certificaciones y capacitación -->
             <div class="card-body">
                 <div class="mb-2">
                     <div class="row">
                         <div class="col-9 d-flex align-items-center">
                             <h6 class="mb-0 fw-bold text-primary">Certificación y/o
                                 Capacitación:</h6>
                         </div>
                         <div class="col-3 d-flex justify-content-end">
                             <a href="#"
                                 class="text-success icon-hover d-flex align-items-center"
                                 data-bs-toggle="modal"
                                 data-bs-target="#modal_agregar_certificaciones">
                                 <i class='bx bx-plus-circle bx-sm me-1'></i>
                                 <span>Agregar</span>
                             </a>
                         </div>
                     </div>
                 </div>
                 <hr>

                 <?php include_once('../vista/TALENTO_HUMANO/POSTULANTES/pos_certificaciones_capacitaciones.php'); ?>

             </div>
         </div>
     </div>
 </div>
 <!-- Segunda Sección, Documentos relevantes -->
 <div class="tab-pane fade" id="successdocs" role="tabpanel">
     <div class="card">
         <div class="d-flex flex-column mx-4">
             <!-- Documento de Identidad -->
             <div class="card-body">
                 <div class="mb-2">
                     <div class="row">
                         <div class="col-7 d-flex align-items-center">
                             <h6 class="mb-0 fw-bold text-primary">Documento de
                                 Identidad:</h6>
                         </div>
                         <div
                             class="col-5 d-flex justify-content-end align-items-center">
                             <a href="#"
                                 class="text-success icon-hover d-flex align-items-center"
                                 data-bs-toggle="modal"
                                 data-bs-target="#modal_agregar_documentos_identidad">
                                 <i class='bx bx-plus-circle bx-sm me-1'></i>
                                 <span class="">Agregar</span>
                             </a>
                         </div>
                     </div>
                 </div>
                 <hr class="my-0 mb-3">

                 <?php include_once('../vista/TALENTO_HUMANO/POSTULANTES/pos_documento_identidad.php'); ?>

             </div>
             <!-- Contratos de Trabajo -->
             <div class="card-body">
                 <div class="mb-2">
                     <div class="row">
                         <div class="col-7 d-flex align-items-center">
                             <h6 class="mb-0 fw-bold text-primary">Contratos de Trabajo:
                             </h6>
                         </div>
                         <div
                             class="col-5 d-flex justify-content-end align-items-center">
                             <a href="#"
                                 class="text-success icon-hover d-flex align-items-center"
                                 data-bs-toggle="modal"
                                 data-bs-target="#modal_agregar_contratos">
                                 <i class='bx bx-plus-circle bx-sm me-1'></i>
                                 <span class="">Agregar</span>
                             </a>
                         </div>
                     </div>
                 </div>
                 <hr class="my-0 mb-3">

                 <?php include_once('../vista/TALENTO_HUMANO/POSTULANTES/pos_contratos_trabajo.php'); ?>

             </div>
             <!-- Certificado Médicos -->
             <div class="card-body my-0">
                 <div class="mb-2">
                     <div class="row">
                         <div class="col-7 d-flex align-items-center">
                             <h6 class="mb-0 fw-bold text-primary">Certificados Médicos:
                             </h6>
                         </div>
                         <div
                             class="col-5 d-flex justify-content-end align-items-center">
                             <a href="#"
                                 class="text-success icon-hover d-flex align-items-center"
                                 data-bs-toggle="modal"
                                 data-bs-target="#modal_agregar_certificados_medicos">
                                 <i class='bx bx-plus-circle bx-sm me-1'></i>
                                 <span class="">Agregar</span>
                             </a>
                         </div>
                     </div>
                 </div>
                 <hr class="my-0 mb-3">

                 <?php include_once('../vista/TALENTO_HUMANO/POSTULANTES/pos_certificado_medico.php'); ?>

             </div>
             <!-- Referencias Laborales -->
             <div class="card-body">
                 <div class="mb-2">
                     <div class="row">
                         <div class="col-7 d-flex align-items-center">
                             <h6 class="mb-0 fw-bold text-primary">Referencias laborales:
                             </h6>
                         </div>
                         <div
                             class="col-5 d-flex justify-content-end align-items-center">
                             <a href="#"
                                 class="text-success icon-hover d-flex align-items-center"
                                 data-bs-toggle="modal"
                                 data-bs-target="#modal_agregar_referencia_laboral">
                                 <i class='bx bx-plus-circle bx-sm me-1'></i>
                                 <span class="">Agregar</span>
                             </a>
                         </div>
                     </div>
                 </div>
                 <hr class="my-0 mb-3">

                 <?php include_once('../vista/TALENTO_HUMANO/POSTULANTES/pos_referencias_laborales.php'); ?>

             </div>
         </div>
     </div>
 </div>
 <!-- Tercera Sección, Idiomas y aptitudes -->
 <div class="tab-pane fade" id="successprofile" role="tabpanel">
     <div class="card">
         <div class="d-flex flex-column mx-4">
             <!-- Idiomas -->
             <div class="card-body">
                 <div class="mb-1">
                     <div class="row">
                         <div class="col-6 d-flex align-items-center">
                             <h6 class="mb-0 fw-bold text-primary">Idiomas</h6>
                         </div>
                         <div class="col-6 d-flex justify-content-end">
                             <a href="#"
                                 class="text-success icon-hover d-flex align-items-center"
                                 data-bs-toggle="modal"
                                 data-bs-target="#modal_agregar_idioma">
                                 <i class='bx bx-plus-circle bx-sm me-1'></i>
                                 <span>Agregar</span>
                             </a>
                         </div>
                     </div>
                 </div>
                 <hr class="my-0">

                 <?php include_once('../vista/TALENTO_HUMANO/POSTULANTES/pos_idiomas.php'); ?>

             </div>
             <!-- Aptitudes -->
             <div class="card-body">
                 <div class="mb-1">
                     <div class="row">
                         <div class="col-6 d-flex align-items-center">
                             <h6 class="mb-0 fw-bold text-primary">Aptitudes</h6>
                         </div>
                         <div class="col-6 d-flex justify-content-end">
                             <a href="#"
                                 class="text-success icon-hover d-flex align-items-center"
                                 data-bs-toggle="modal"
                                 data-bs-target="#modal_agregar_aptitudes"
                                 onclick="activar_select2();">
                                 <i class='bx bx-plus-circle bx-sm me-1'></i>
                                 <span>Agregar</span>
                             </a>
                         </div>
                     </div>
                 </div>
                 <hr class="my-0">

                 <?php include_once('../vista/TALENTO_HUMANO/POSTULANTES/pos_aptitudes.php'); ?>

             </div>
         </div>
     </div>
 </div>
 <!-- Cuarta Sección, discapacidad -->
 <div class="tab-pane fade" id="tab_discapacidad" role="tabpanel">
     <div class="card">
         <div class="d-flex flex-column mx-4">
             <div class="card-body">

                 <div class="mb-2">
                     <div class="row">
                         <div class="col-6 d-flex align-items-center">
                             <h6 class="mb-0 fw-bold text-primary">Discapacidad:</h6>
                         </div>

                         <div class="col-6 d-flex justify-content-end">
                             <a href="#"
                                 class="text-success icon-hover d-flex align-items-center"
                                 onclick="abrir_modal_discapacidad('');">
                                 <i class='bx bx-plus-circle bx-sm me-1'></i>
                                 <span>Agregar</span>
                             </a>
                         </div>
                     </div>
                 </div>

                 <hr>

                 <?php include_once('../vista/TALENTO_HUMANO/POSTULANTES/pos_discapacidad.php'); ?>

             </div>
         </div>
     </div>
 </div>