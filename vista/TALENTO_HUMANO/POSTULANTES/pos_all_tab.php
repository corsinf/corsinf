 <?php
    $tab_postulante_activo = 'active';
    if (isset($_GET['_persona_nomina']) && $_GET['_persona_nomina'] == 'true') {
        $tab_postulante_activo = '';
    }
    ?>

 <li class="nav-item me-2" role="presentation">
     <a class="nav-link border border-info rounded-3 shadow-sm px-4 py-3 <?= $tab_postulante_activo ?>"
         data-bs-toggle="tab" href="#tab_experiencia" role="tab" aria-selected="true">
         <div class="d-flex align-items-center gap-2">
             <i class="bx bxs-briefcase fs-5 text-info"></i>
             <span class="fw-semibold text-info">Experiencia</span>
         </div>
     </a>
 </li>

 <li class="nav-item me-2" role="presentation">
     <a class="nav-link border border-info rounded-3 shadow-sm px-4 py-3"
         data-bs-toggle="tab" href="#successdocs" role="tab">
         <div class="d-flex align-items-center gap-2">
             <i class="bx bxs-file-doc fs-5 text-info"></i>
             <span class="fw-semibold text-info">Documentos</span>
         </div>
     </a>
 </li>

 <li class="nav-item me-2" role="presentation">
     <a class="nav-link border border-info rounded-3 shadow-sm px-4 py-3"
         data-bs-toggle="tab" href="#successprofile" role="tab">
         <div class="d-flex align-items-center gap-2">
             <i class="bx bx-brain fs-5 text-info"></i>
             <span class="fw-semibold text-info">Habilidades</span>
         </div>
     </a>
 </li>

 <li class="nav-item me-2" role="presentation">
     <a class="nav-link border border-info rounded-3 shadow-sm px-4 py-3"
         data-bs-toggle="tab" href="#tab_discapacidad" role="tab">
         <div class="d-flex align-items-center gap-2">
             <i class="bx bx-accessibility fs-5 text-info"></i>
             <span class="fw-semibold text-info">Discapacidad</span>
         </div>
     </a>
 </li>

 <li class="nav-item me-2" role="presentation">
     <a class="nav-link border border-info rounded-3 shadow-sm px-4 py-3"
         data-bs-toggle="tab" href="#tab_contactos_emergencia" role="tab">
         <div class="d-flex align-items-center gap-2">
             <i class="bx bxs-phone fs-5 text-info"></i>
             <span class="fw-semibold text-info">Contactos de Emergencia</span>
         </div>
     </a>
 </li>


 <style>
     .tab-personalizado {
         color: #333333 !important;
         /* Cambia esto por tu color hexadecimal */
     }

     /* Opcional: Cambiar el color cuando el tab está activo */
     .nav-link.active .tab-personalizado {
         color: #28a745 !important;
     }
 </style>