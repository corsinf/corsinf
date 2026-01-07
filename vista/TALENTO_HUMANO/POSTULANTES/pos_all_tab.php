 <?php
    $tab_postulante_activo = 'active';
    if (isset($_GET['_persona_nomina']) && $_GET['_persona_nomina'] == 'true') {
        $tab_postulante_activo = '';
    }
    ?>

 <li class="nav-item border" role="presentation">
     <a class="nav-link border-dark rounded-0 <?= $tab_postulante_activo ?>" data-bs-toggle="tab" href="#tab_experiencia" role="tab"
         aria-selected="true">
         <div class="d-flex align-items-center tab-personalizado">
             <div class="tab-icon"><i class="bx bxs-briefcase font-18 me-1"></i>
             </div>
             <div class="tab-title">Experiencia</div>
         </div>
     </a>
 </li>
 <li class="nav-item border" role="presentation">
     <a class="nav-link border-dark rounded-0" data-bs-toggle="tab" href="#successdocs" role="tab"
         aria-selected="true">
         <div class="d-flex align-items-center tab-personalizado">
             <div class="tab-icon"><i class="bx bxs-file-doc font-18 me-1"></i>
             </div>
             <div class="tab-title">Documentos</div>
         </div>
     </a>
 </li>
 <li class="nav-item border" role="presentation">
     <a class="nav-link border-dark rounded-0" data-bs-toggle="tab" href="#successprofile" role="tab"
         aria-selected="false" tabindex="-1">
         <div class="d-flex align-items-center tab-personalizado">
             <div class="tab-icon"><i class="bx bx-brain font-18 me-1"></i>
             </div>
             <div class="tab-title">Habilidades</div>
         </div>
     </a>
 </li>
 <li class="nav-item border" role="presentation">
     <a class="nav-link border-dark rounded-0" data-bs-toggle="tab" href="#tab_discapacidad" role="tab"
         aria-selected="false" tabindex="-1">
         <div class="d-flex align-items-center tab-personalizado">
             <div class="tab-icon"><i class="bx bx-brain font-18 me-1"></i>
             </div>
             <div class="tab-title">Discapacidad</div>
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