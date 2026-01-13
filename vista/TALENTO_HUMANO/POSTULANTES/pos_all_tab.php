 <?php
    $tab_postulante_activo = 'active';
    if (isset($_GET['_persona_nomina']) && $_GET['_persona_nomina'] == 'true') {
        $tab_postulante_activo = '';
    }
    ?>

 <!-- <li class="nav-item me-2" role="presentation">
     <a class="nav-link border border-info rounded-3 shadow-sm px-3 py-2 <?= $tab_postulante_activo ?>"
         data-bs-toggle="tab" href="#tab_experiencia" role="tab" aria-selected="true">
         <div class="d-flex align-items-center gap-2">
             <i class="bx bxs-briefcase text-info" style="font-size: 0.875rem;"></i>
             <span class="fw-semibold text-info" style="font-size: 0.875rem;">Experiencia</span>
         </div>
     </a>
 </li>

 <li class="nav-item me-2" role="presentation">
     <a class="nav-link border border-info rounded-3 shadow-sm px-3 py-2"
         data-bs-toggle="tab" href="#successdocs" role="tab">
         <div class="d-flex align-items-center gap-2">
             <i class="bx bxs-file-doc text-info" style="font-size: 0.875rem;"></i>
             <span class="fw-semibold text-info" style="font-size: 0.875rem;">Documentos</span>
         </div>
     </a>
 </li>

 <li class="nav-item me-2" role="presentation">
     <a class="nav-link border border-info rounded-3 shadow-sm px-3 py-2"
         data-bs-toggle="tab" href="#successprofile" role="tab">
         <div class="d-flex align-items-center gap-2">
             <i class="bx bx-brain text-info" style="font-size: 0.875rem;"></i>
             <span class="fw-semibold text-info" style="font-size: 0.875rem;">Habilidades</span>
         </div>
     </a>
 </li>

 <li class="nav-item me-2" role="presentation">
     <a class="nav-link border border-info rounded-3 shadow-sm px-3 py-2"
         data-bs-toggle="tab" href="#tab_discapacidad" role="tab">
         <div class="d-flex align-items-center gap-2">
             <i class="bx bx-accessibility text-info" style="font-size: 0.875rem;"></i>
             <span class="fw-semibold text-info" style="font-size: 0.875rem;">Discapacidad</span>
         </div>
     </a>
 </li> -->


 <?php if (isset($_GET['id_postulante']) && $_GET['id_postulante'] != null) { ?>

     <button class="nav-link py-2 px-3 border border-info shadow-sm mb-2 <?= $tab_postulante_activo ?>" data-bs-toggle="pill" data-bs-target="#tab_experiencia" type="button" role="tab">
         <div class="d-flex align-items-center">
             <i class="bx bxs-briefcase me-3 fs-5"></i>
             <span>Experiencia</span>
         </div>
     </button>

     <button class="nav-link py-2 px-3 border border-info shadow-sm mb-2" data-bs-toggle="pill" data-bs-target="#successdocs" type="button" role="tab">
         <div class="d-flex align-items-center">
             <i class="bx bxs-file-doc me-3 fs-5"></i>
             <span>Documentos</span>
         </div>
     </button>

     <button class="nav-link py-2 px-3 border border-info shadow-sm mb-2" data-bs-toggle="pill" data-bs-target="#successprofile" type="button" role="tab">
         <div class="d-flex align-items-center">
             <i class="bx bx-brain me-3 fs-5"></i>
             <span>Habilidades</span>
         </div>
     </button>

     <button class="nav-link py-2 px-3 border border-info shadow-sm mb-2" data-bs-toggle="pill" data-bs-target="#tab_discapacidad" type="button" role="tab">
         <div class="d-flex align-items-center">
             <i class="bx bx-accessibility me-3 fs-5"></i>
             <span>Discapacidad</span>
         </div>
     </button>

 <?php } ?>


 <!-- <style>
     .tab-personalizado {
         color: #333333 !important;
         /* Cambia esto por tu color hexadecimal */
     }

     /* Opcional: Cambiar el color cuando el tab está activo */
     .nav-link.active .tab-personalizado {
         color: #28a745 !important;
     }
 </style> -->