 <script>
     function abrir_modal_aspectos_intrinsecos() {

         var modal = new bootstrap.Modal(
             document.getElementById('modal_aspectos_intrinsecos'), {
                 backdrop: 'static',
                 keyboard: false
             }
         );



         modal.show();


     }
     $(document).ready(function() {
         function obtenerParametrosAspecto() {
             // Helper para obtener valor según checkbox
             const obtenerValor = (checkboxId, ddlId, inputId) => {
                 const checked = $(`#${checkboxId}`).is(':checked');

                 if (checked) {
                     // Si checkbox marcado, retornar null (el valor viene del ID)
                     return null;
                 } else {
                     // Si checkbox no marcado, tomar valor del input/textarea
                     const valor = $(`#${inputId}`).val();
                     return valor ? valor.trim() : null;
                 }
             };

             // Helper para obtener ID cuando checkbox está marcado
             const obtenerIdCargo = (checkboxId, ddlId) => {
                 const checked = $(`#${checkboxId}`).is(':checked');
                 if (checked) {
                     const valor = $(`#${ddlId}`).val();
                     return valor ? parseInt(valor) : null;
                 }
                 return null;
             };

             return {
                 // IDs básicos
                 '_id': $('#th_carasp_id').val() || '',
                 'th_car_id': $('#th_car_id').val() || '', // CORREGIDO: Ya no usa PHP inline

                 // Nivel del cargo
                 'th_carasp_nivel_cargo': $('#th_carasp_nivel_cargo').val() || '',

                 // SUBORDINACIÓN
                 'th_carasp_subordinacion': obtenerValor(
                     'chk_subordinacion_empresa',
                     'ddl_subordinacion',
                     'txt_subordinacion'
                 ),
                 'th_carasp_subordinacion_id': obtenerIdCargo(
                     'chk_subordinacion_empresa',
                     'ddl_subordinacion'
                 ),

                 // SUPERVISIÓN
                 'th_carasp_supervision': obtenerValor(
                     'chk_supervision_empresa',
                     'ddl_supervision',
                     'txt_supervision'
                 ),
                 'th_carasp_supervision_id': obtenerIdCargo(
                     'chk_supervision_empresa',
                     'ddl_supervision'
                 ),

                 // COMUNICACIONES COLATERALES
                 'th_carasp_comunicaciones_colaterales': obtenerValor(
                     'chk_comunicaciones_empresa',
                     'ddl_comunicaciones',
                     'txt_comunicaciones'
                 ),
                 'th_carasp_comunicaciones_id': obtenerIdCargo(
                     'chk_comunicaciones_empresa',
                     'ddl_comunicaciones'
                 ),

                 // Estado
                 'chk_th_carasp_estado': 1
             };
         }

         /**
          * Valida los parámetros antes de guardar
          */
         function validarAspectoParametros(parametros) {
             console.log('Validando parámetros:', parametros); // DEBUG

             // Validar que tenga cargo asociado
             if (!parametros.th_car_id) {
                 Swal.fire('', 'Falta el ID del cargo asociado.', 'warning');
                 return false;
             }

             // Validar nivel del cargo
             if (!parametros.th_carasp_nivel_cargo) {
                 Swal.fire('', 'Debe seleccionar el nivel del cargo.', 'warning');
                 $('#th_carasp_nivel_cargo').focus();
                 return false;
             }

             // Validar subordinación (debe tener texto O id)
             if (!parametros.th_carasp_subordinacion && !parametros.th_carasp_subordinacion_id) {
                 Swal.fire('', 'Debe completar la información de subordinación.', 'warning');
                 if ($('#chk_subordinacion_empresa').is(':checked')) {
                     $('#ddl_subordinacion').focus();
                 } else {
                     $('#txt_subordinacion').focus();
                 }
                 return false;
             }

             // Validar supervisión (debe tener texto O id)
             if (!parametros.th_carasp_supervision && !parametros.th_carasp_supervision_id) {
                 Swal.fire('', 'Debe completar la información de supervisión.', 'warning');
                 if ($('#chk_supervision_empresa').is(':checked')) {
                     $('#ddl_supervision').focus();
                 } else {
                     $('#txt_supervision').focus();
                 }
                 return false;
             }

             // Validar comunicaciones (debe tener texto O id)
             if (!parametros.th_carasp_comunicaciones_colaterales && !parametros.th_carasp_comunicaciones_id) {
                 Swal.fire('', 'Debe completar la información de comunicaciones colaterales.', 'warning');
                 if ($('#chk_comunicaciones_empresa').is(':checked')) {
                     $('#ddl_comunicaciones').focus();
                 } else {
                     $('#txt_comunicaciones').focus();
                 }
                 return false;
             }

             return true;
         }


         function guardar_o_actualizar_aspecto() {
             var parametros = obtenerParametrosAspecto();

             console.log('Parámetros a enviar:', parametros); // DEBUG

             if (!validarAspectoParametros(parametros)) return;

             $.ajax({
                 data: {
                     parametros: parametros
                 },
                 url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_cargo_aspectos_intrinsecosC.php?insertar_editar=true',
                 type: 'post',
                 dataType: 'json',
                 success: function(response) {
                     console.log('Respuesta del servidor:', response); // DEBUG

                     if (response == 1 || response === true) {

                         Swal.fire('', 'Plaza creada con éxito.', 'success').then(function() {
                             location.reload();
                         });
                     } else if (response == -2) {
                         Swal.fire('',
                             'Ya existe un aspecto intrínseco duplicado para este cargo/nivel.',
                             'warning');
                     } else {
                         var msg = (typeof response === 'object' && response.msg) ? response.msg :
                             'Error al guardar los aspectos.';
                         Swal.fire('', msg, 'error');
                     }
                 },
                 error: function(xhr, status, error) {
                     console.error('Error guardar_aspecto:', {
                         status: status,
                         error: error,
                         response: xhr.responseText
                     });
                     Swal.fire('',
                         'Error al conectar con el servidor. Revisa la consola para más detalles.',
                         'error');
                 }
             });
         }

         function listar_aspecto_cargo(id) {
             $.ajax({
                 data: {
                     id: id
                 },
                 url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_cargo_aspectos_intrinsecosC.php?listar_aspecto_cargo=true',
                 type: 'post',
                 dataType: 'json',
                 success: function(response) {
                     if (!response || response.length === 0) {
                         mostrarAspectosVacios();
                         return;
                     }

                     var rows = Array.isArray(response) ? response : [response];
                     cargar_aspecto_en_modal(rows[0]);
                     var subordinaciones = [];
                     var niveles = [];
                     var supervisiones = [];
                     var comunicacioness = [];
                     rows.forEach(function(r) {
                         var subText = '';
                         if (r.th_carasp_subordinacion_id && r.subordinacion_cargo_nombre) {
                             subText = r.subordinacion_cargo_nombre;

                         } else if (r.th_carasp_subordinacion) {
                             subText = r.th_carasp_subordinacion;
                         } else {
                             subText = 'Sin especificar';
                         }
                         subordinaciones.push(subText);
                         var supText = '';
                         if (r.th_carasp_supervision_id && r.supervision_cargo_nombre) {
                             supText = r.supervision_cargo_nombre;

                         } else if (r.th_carasp_supervision) {
                             supText = r.th_carasp_supervision;
                         } else {
                             supText = 'Sin especificar';
                         }
                         supervisiones.push(supText);
                         var comText = '';
                         if (r.th_carasp_comunicaciones_id && r.comunicaciones_cargo_nombre) {
                             comText = r.comunicaciones_cargo_nombre;

                         } else if (r.th_carasp_comunicaciones_colaterales) {
                             comText = r.th_carasp_comunicaciones_colaterales;
                         } else {
                             comText = 'Sin especificar';
                         }
                         comunicacioness.push(comText);
                     });
                     $('#info_subordinacion').html(subordinaciones.join('<br>'));
                     $('#info_nivel_cargo').text(niveles.join(' / '));
                     $('#info_supervision').html(supervisiones.join('<br>'));
                     $('#info_comunicaciones').html(comunicacioness.join('<br>'));
                     $('#badge_subordinacion').html('<i class="bi bi-arrow-up"></i> Reporta a: ' +
                         subordinaciones.join(', '));
                     $('#badge_nivel').html('<i class="bi bi-person-badge"></i> Nivel: ' +
                         ((rows.length === 1) ? niveles[0] : niveles.join(' / ')));
                     $('#badge_supervision').html('<i class="bi bi-arrow-down"></i> Supervisa: ' +
                         supervisiones.join(', '));
                     $('#badge_comunicaciones').html('<i class="bi bi-arrows"></i> Comunica con: ' +
                         comunicacioness.join(', '));
                 },
                 error: function(err) {
                     console.error('Error al listar aspectos:', err);
                     mostrarAspectosVacios();
                 }
             });
         }


         function cargar_aspecto_en_modal(r) {


             $('#th_carasp_id').val(r.th_carasp_id || '');
             $('#th_car_id').val(r.th_car_id || '');
             $('#th_carasp_nivel_cargo').val(r.th_carasp_nivel_cargo || '');


             if (r.th_carasp_subordinacion_id && r.th_carasp_subordinacion_id !== null) {
                 $('#chk_subordinacion_empresa').prop('checked', true).trigger('change');
                 $('#ddl_subordinacion').append($('<option>', {
                     value: r.th_carasp_subordinacion_id,
                     text: r.subordinacion_cargo_nombre,
                     selected: true
                 }));
             } else {
                 $('#chk_subordinacion_empresa').prop('checked', false).trigger('change');
                 $('#txt_subordinacion').val(r.th_carasp_subordinacion || '');
             }
             if (r.th_carasp_supervision_id && r.th_carasp_supervision_id !== null) {
                 $('#chk_supervision_empresa').prop('checked', true).trigger('change');
                 $('#ddl_supervision').append($('<option>', {
                     value: r.th_carasp_supervision_id,
                     text: r.supervision_cargo_nombre,
                     selected: true
                 }));
             } else {
                 $('#chk_supervision_empresa').prop('checked', false).trigger('change');
                 $('#txt_supervision').val(r.th_carasp_supervision || '');
             }
             if (r.th_carasp_comunicaciones_id && r.th_carasp_comunicaciones_id !== null) {
                 $('#chk_comunicaciones_empresa').prop('checked', true).trigger('change');
                 $('#ddl_comunicaciones').append($('<option>', {
                     value: r.th_carasp_comunicaciones_id,
                     text: r.comunicaciones_cargo_nombre,
                     selected: true
                 }));
             } else {
                 $('#chk_comunicaciones_empresa').prop('checked', false).trigger('change');
                 $('#txt_comunicaciones').val(r.th_carasp_comunicaciones_colaterales || '');
             }
             $('#pnl_crear_aspecto').hide();
             $('#pnl_actualizar_aspecto').show();
         }

         function mostrarAspectosVacios() {


             $('#th_car_id').val("<?= isset($_id) ? $_id : '' ?>");
             $('#info_subordinacion').html('<em class="text-muted">No registrado</em>');
             $('#info_nivel_cargo').text('No registrado');
             $('#info_supervision').html('<em class="text-muted">No registrado</em>');
             $('#info_comunicaciones').html('<em class="text-muted">No registrado</em>');

             $('#badge_subordinacion').html('<i class="bi bi-arrow-up"></i> Reporta a: -');
             $('#badge_nivel').html('<i class="bi bi-person-badge"></i> Nivel: -');
             $('#badge_supervision').html('<i class="bi bi-arrow-down"></i> Supervisa: -');
             $('#badge_comunicaciones').html('<i class="bi bi-arrows"></i> Comunica con: -');
         }

         function abrir_modal_nuevo_aspecto(id_cargo) {
             $('#form_aspectos_intrinsecos')[0].reset();
             $('#th_carasp_id').val('');
             $('#th_car_id').val(id_cargo);

             $('#chk_subordinacion_empresa').prop('checked', false).trigger('change');
             $('#chk_supervision_empresa').prop('checked', false).trigger('change');
             $('#chk_comunicaciones_empresa').prop('checked', false).trigger('change');

             $('#pnl_crear_aspecto').show();
             $('#pnl_actualizar_aspecto').hide();
             var modal = new bootstrap.Modal(document.getElementById('modal_aspectos_intrinsecos'));
             modal.show();
         }

         function abrir_modal_editar_aspecto(id_cargo) {
             listar_aspecto_cargo(id_cargo);
             var modal = new bootstrap.Modal(document.getElementById('modal_aspectos_intrinsecos'));
             modal.show();
         }

         /**
          * Carga los datos para edición en el modal
          */
         function cargar_datos_aspecto(datos) {
             // Cargar datos básicos
             $('#th_carasp_id').val(datos.th_carasp_id || '');
             $('#th_car_id').val(datos.th_car_id || '');
             $('#th_carasp_nivel_cargo').val(datos.th_carasp_nivel_cargo || '');

             // SUBORDINACIÓN
             if (datos.th_carasp_subordinacion_id) {
                 // Tiene ID de cargo, marcar checkbox y seleccionar en DDL
                 $('#chk_subordinacion_empresa').prop('checked', true).trigger('change');
                 setTimeout(() => {
                     $('#ddl_subordinacion').val(datos.th_carasp_subordinacion_id).trigger('change');
                 }, 100);
             } else {
                 // No tiene ID, desmarcar checkbox y poner texto
                 $('#chk_subordinacion_empresa').prop('checked', false).trigger('change');
                 $('#txt_subordinacion').val(datos.th_carasp_subordinacion || '');
             }

             // SUPERVISIÓN
             if (datos.th_carasp_supervision_id) {
                 $('#chk_supervision_empresa').prop('checked', true).trigger('change');
                 setTimeout(() => {
                     $('#ddl_supervision').val(datos.th_carasp_supervision_id).trigger('change');
                 }, 100);
             } else {
                 $('#chk_supervision_empresa').prop('checked', false).trigger('change');
                 $('#txt_supervision').val(datos.th_carasp_supervision || '');
             }

             // COMUNICACIONES COLATERALES
             if (datos.th_carasp_comunicaciones_id) {
                 $('#chk_comunicaciones_empresa').prop('checked', true).trigger('change');
                 setTimeout(() => {
                     $('#ddl_comunicaciones').val(datos.th_carasp_comunicaciones_id).trigger('change');
                 }, 100);
             } else {
                 $('#chk_comunicaciones_empresa').prop('checked', false).trigger('change');
                 $('#txt_comunicaciones').val(datos.th_carasp_comunicaciones_colaterales || '');
             }

             // Mostrar botones de edición
             $('#pnl_crear_aspecto').hide();
             $('#pnl_actualizar_aspecto').show();
         }

         $(function() {
             // Guardar nuevo
             $(document).on('click', '#pnl_crear_aspecto button', function(e) {
                 e.preventDefault();
                 guardar_o_actualizar_aspecto();
             });

             // Actualizar (botón del panel de editar)
             $(document).on('click', '#btn_editar_aspecto', function(e) {
                 e.preventDefault();
                 guardar_o_actualizar_aspecto();
             });

         });
     });
 </script>


 <div class="container-fluid">

     <!-- Encabezado -->
     <div class="row mb-4 align-items-center">
         <div class="col-md-8">
             <h5 class="fw-bold text-primary mb-1">
                 <i class="bx bx-sitemap me-2"></i>Aspectos Intrínsecos del
                 Cargo
             </h5>
             <small class="text-muted">
                 <i class="bi bi-info-circle-fill me-1"></i>
                 Estructura organizacional, jerarquía y relaciones del cargo
             </small>
         </div>
         <?php if (isset($_GET['_id'])) { ?>
             <div class="col-md-4 text-end">
                 <button type="button" class="btn btn-success btn-sm shadow-sm"
                     onclick="abrir_modal_aspectos_intrinsecos()">
                     <i class="bx bx-plus-circle me-1"></i> Registrar Aspectos
                 </button>
             </div>
         <?php } ?>
     </div>
     <?php if (isset($_GET['_id'])) { ?>
         <!-- Tarjeta Principal con Organigrama -->
         <div class="card border-0 shadow-sm">
             <div class="card-body p-4">

                 <!-- Organigrama Vertical Moderno -->
                 <div class="position-relative">

                     <!-- Nivel 1: REPORTA A -->
                     <div class="text-center mb-4 position-relative">
                         <div
                             class="badge bg-success bg-opacity-25 text-success px-4 py-3 rounded-pill fs-6 fw-semibold shadow-sm">
                             <i class="bi bi-arrow-up-circle-fill me-2"></i>
                             <span id="info_subordinacion" class="text-dark">Sin
                                 superior
                                 directo</span>
                         </div>
                         <!-- Línea conectora -->
                         <div class="position-absolute start-50 translate-middle-x"
                             style="top: 100%; width: 3px; height: 30px; background: linear-gradient(to bottom, #198754, #0d6efd);">
                         </div>
                     </div>

                     <!-- Nivel 2: CARGO ACTUAL (Principal) -->
                     <div class="text-center my-4 position-relative">
                         <div class="d-inline-block p-4 bg-gradient rounded-4 shadow border border-primary border-3"
                             style="background: linear-gradient(135deg, #0d6efd15 0%, #0d6efd30 100%);">
                             <div class="mb-3">
                                 <i class="bi bi-person-badge-fill text-primary"
                                     style="font-size: 3rem;"></i>
                             </div>
                             <h5 class="fw-bold text-primary mb-2">NIVEL DEL
                                 CARGO</h5>
                             <span
                                 class="badge bg-primary px-4 py-2 fs-6 shadow-sm"
                                 id="info_nivel_cargo">
                                 No definido
                             </span>
                         </div>
                         <!-- Líneas conectoras hacia abajo -->
                         <div class="d-flex justify-content-center gap-5 position-absolute start-50 translate-middle-x"
                             style="top: 100%; width: 300px;">
                             <div
                                 style="width: 3px; height: 30px; background: linear-gradient(to bottom, #0d6efd, #ffc107);">
                             </div>
                             <div
                                 style="width: 3px; height: 30px; background: linear-gradient(to bottom, #0d6efd, #0dcaf0);">
                             </div>
                         </div>
                     </div>

                     <!-- Nivel 3: SUPERVISA y COMUNICACIONES -->
                     <div class="row g-3 mt-4">

                         <!-- SUPERVISA -->
                         <div class="col-md-6">
                             <div
                                 class="card border-warning border-2 h-100 shadow-sm hover-lift">
                                 <div class="card-body text-center p-4">
                                     <div class="mb-3">
                                         <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center"
                                             style="width: 70px; height: 70px;">
                                             <i class="bi bi-people-fill text-warning"
                                                 style="font-size: 2rem;"></i>
                                         </div>
                                     </div>
                                     <h6 class="fw-bold text-warning mb-3">
                                         <i
                                             class="bi bi-arrow-down-circle me-1"></i>SUPERVISA
                                     </h6>
                                     <p class="text-dark mb-0 small lh-base"
                                         id="info_supervision">
                                         <em class="text-muted">Sin personal a
                                             cargo</em>
                                     </p>
                                 </div>
                             </div>
                         </div>

                         <!-- COMUNICACIONES COLATERALES -->
                         <div class="col-md-6">
                             <div
                                 class="card border-info border-2 h-100 shadow-sm hover-lift">
                                 <div class="card-body text-center p-4">
                                     <div class="mb-3">
                                         <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center"
                                             style="width: 70px; height: 70px;">
                                             <i class="bi bi-arrows-angle-expand text-info"
                                                 style="font-size: 2rem;"></i>
                                         </div>
                                     </div>
                                     <h6 class="fw-bold text-info mb-3">
                                         <i
                                             class="bi bi-diagram-3 me-1"></i>COMUNICACIONES
                                         COLATERALES
                                     </h6>
                                     <p class="text-dark mb-0 small lh-base"
                                         id="info_comunicaciones">
                                         <em class="text-muted">Sin interacciones
                                             definidas</em>
                                     </p>
                                 </div>
                             </div>
                         </div>

                     </div>

                 </div>

                 <!-- Resumen Compacto en Badges -->
                 <div class="mt-4 pt-4 border-top">
                     <h6 class="fw-bold text-muted mb-3 text-center">
                         <i class="bi bi-diagram-2-fill me-2"></i>Resumen de
                         Jerarquía
                     </h6>
                     <div
                         class="d-flex flex-wrap justify-content-center align-items-center gap-2">
                         <span
                             class="badge rounded-pill bg-success-subtle text-success px-3 py-2 shadow-sm"
                             id="badge_subordinacion">
                             <i class="bi bi-arrow-up-short"></i> Reporta: -
                         </span>
                         <i class="bi bi-chevron-right text-muted"></i>
                         <span
                             class="badge rounded-pill bg-primary text-white px-3 py-2 shadow-sm"
                             id="badge_nivel">
                             <i class="bi bi-hash"></i> Nivel: -
                         </span>
                         <i class="bi bi-chevron-right text-muted"></i>
                         <span
                             class="badge rounded-pill bg-warning-subtle text-dark px-3 py-2 shadow-sm"
                             id="badge_supervision">
                             <i class="bi bi-arrow-down-short"></i> Supervisa: -
                         </span>
                     </div>
                     <div class="text-center mt-2">
                         <span
                             class="badge rounded-pill bg-info-subtle text-info px-3 py-2 shadow-sm"
                             id="badge_comunicaciones">
                             <i class="bi bi-arrows-move"></i> Comunica: -
                         </span>
                     </div>
                 </div>

             </div>
         </div>
     <?php  } ?>

 </div>

 <div class="modal fade" id="modal_aspectos_intrinsecos" tabindex="-1" aria-labelledby="modalAspectosLabel"
     aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-modal="true">
     <div class="modal-dialog modal-dialog-centered modal-lg">
         <div class="modal-content">

             <!-- Modal Header -->
             <div class="modal-header bg-light">
                 <h5 class="modal-title" id="modalAspectosLabel">
                     <i class="bx bx-list-check me-2"></i> Registrar Aspectos Intrínsecos
                 </h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
             </div>

             <!-- Modal body -->
             <div class="modal-body p-4">
                 <form id="form_aspectos_intrinsecos">
                     <input type="hidden" id="th_carasp_id" name="th_carasp_id">
                     <input type="hidden" id="th_car_id" name="th_car_id">

                     <div class="row g-3">
                         <!-- Nivel del Cargo -->
                         <div class="col-md-12">
                             <label for="txt_nivel_cargo" class="form-label fw-bold">
                                 <i class="bx bx-layer me-2 text-primary"></i> Nivel del Cargo
                             </label>
                             <select class="form-select form-select-md shadow-sm" id="th_carasp_nivel_cargo">
                                 <option value="">Seleccione...</option>
                                 <option value="1">Nivel 1 - Alta Dirección</option>
                                 <option value="2">Nivel 2 - Gerencia</option>
                                 <option value="3">Nivel 3 - Jefatura/Coordinación</option>
                                 <option value="4">Nivel 4 - Supervisión</option>
                                 <option value="5">Nivel 5 - Operativo/Técnico</option>
                                 <option value="6">Nivel 6 - Auxiliar/Asistente</option>
                             </select>
                         </div>

                         <!-- Subordinación -->
                         <div class="col-md-12">
                             <label for="txt_subordinacion" class="form-label fw-bold">
                                 <i class="bx bx-sitemap me-2 text-info"></i> Subordinación
                             </label>

                             <div class="form-check mb-2">
                                 <input class="form-check-input" type="checkbox" id="chk_subordinacion_empresa">
                                 <label class="form-check-label" for="chk_subordinacion_empresa">
                                     Pertenece a la empresa
                                 </label>
                             </div>

                             <div id="div_subordinacion_select" style="display:none;">
                                 <select id="ddl_subordinacion" name="ddl_subordinacion"
                                     class="form-select select2-validation">
                                     <option value="" selected hidden>-- Seleccione el responsable --</option>
                                 </select>
                             </div>

                             <div id="div_subordinacion_input">
                                 <input type="text"
                                     class="form-control"
                                     id="txt_subordinacion"
                                     name="txt_subordinacion"
                                     value="Ninguno"
                                     disabled>
                             </div>
                         </div>

                         <!-- Supervisión -->
                         <div class="col-md-12">
                             <label for="txt_supervision" class="form-label fw-bold">
                                 <i class="bx bx-user-check me-2 text-success"></i> Supervisión
                             </label>

                             <div class="form-check mb-2">
                                 <input class="form-check-input" type="checkbox" id="chk_supervision_empresa">
                                 <label class="form-check-label" for="chk_supervision_empresa">
                                     Pertenece a la empresa
                                 </label>
                             </div>

                             <div id="div_supervision_select" style="display:none;">
                                 <select id="ddl_supervision" name="ddl_supervision"
                                     class="form-select select2-validation">
                                     <option value="" selected hidden>-- Seleccione el personal supervisado --</option>
                                 </select>
                             </div>

                             <div id="div_supervision_input">
                                 <input type="text"
                                     class="form-control"
                                     id="txt_supervision"
                                     name="txt_supervision"
                                     value="Ninguno"
                                     disabled>
                             </div>
                         </div>

                         <!-- Comunicaciones Colaterales -->
                         <div class="col-md-12">
                             <label for="txt_comunicaciones" class="form-label fw-bold">
                                 <i class="bx bx-conversation me-2 text-warning"></i> Comunicaciones Colaterales
                             </label>

                             <div class="form-check mb-2">
                                 <input class="form-check-input" type="checkbox" id="chk_comunicaciones_empresa">
                                 <label class="form-check-label" for="chk_comunicaciones_empresa">
                                     Pertenece a la empresa
                                 </label>
                             </div>

                             <div id="div_comunicaciones_select" style="display:none;">
                                 <select id="ddl_comunicaciones" name="ddl_comunicaciones"
                                     class="form-select select2-validation">
                                     <option value="" selected hidden>-- Seleccione las áreas/cargos --</option>
                                 </select>
                             </div>

                             <div id="div_comunicaciones_input">
                                 <input type="text"
                                     class="form-control"
                                     id="txt_comunicaciones"
                                     name="txt_comunicaciones"
                                     value="Ninguno"
                                     disabled>
                             </div>
                         </div>

                     </div>
                     <div class="d-flex justify-content-end gap-2 pt-3 mt-3 border-top">
                         <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                             <i class="bx bx-x me-1"></i> Cancelar
                         </button>

                         <div id="pnl_crear_aspecto">
                             <button type="button" class="btn btn-success">
                                 <i class="bx bx-save me-1"></i> Guardar Aspectos
                             </button>
                         </div>

                         <div id="pnl_actualizar_aspecto" style="display:none">
                             <button type="button" class="btn btn-danger" id="btn_eliminar_aspecto">
                                 <i class="bx bx-trash me-1"></i> Eliminar
                             </button>
                             <button type="button" class="btn btn-primary" id="btn_editar_aspecto">
                                 <i class="bx bx-check me-1"></i> Actualizar Aspectos
                             </button>
                         </div>
                     </div>

                 </form>
             </div>

         </div>
     </div>
 </div>


 <script>
     // JavaScript para manejar la funcionalidad de los checkboxes
     $(document).ready(function() {
         // Subordinación
         $('#chk_subordinacion_empresa').on('change', function() {
             if ($(this).is(':checked')) {
                 $('#div_subordinacion_select').show();
                 $('#div_subordinacion_input').hide();
                 $('#txt_subordinacion').removeAttr('required');
                 $('#ddl_subordinacion').attr('required', 'required');
             } else {
                 $('#div_subordinacion_select').hide();
                 $('#div_subordinacion_input').show();
                 $('#ddl_subordinacion').removeAttr('required');
                 $('#txt_subordinacion').attr('required', 'required');
             }
         });

         // Supervisión
         $('#chk_supervision_empresa').on('change', function() {
             if ($(this).is(':checked')) {
                 $('#div_supervision_select').show();
                 $('#div_supervision_input').hide();
                 $('#txt_supervision').removeAttr('required');
                 $('#ddl_supervision').attr('required', 'required');
             } else {
                 $('#div_supervision_select').hide();
                 $('#div_supervision_input').show();
                 $('#ddl_supervision').removeAttr('required');
                 $('#txt_supervision').attr('required', 'required');
             }
         });

         // Comunicaciones Colaterales
         $('#chk_comunicaciones_empresa').on('change', function() {
             if ($(this).is(':checked')) {
                 $('#div_comunicaciones_select').show();
                 $('#div_comunicaciones_input').hide();
                 $('#txt_comunicaciones').removeAttr('required');
                 $('#ddl_comunicaciones').attr('required', 'required');
             } else {
                 $('#div_comunicaciones_select').hide();
                 $('#div_comunicaciones_input').show();
                 $('#ddl_comunicaciones').removeAttr('required');
                 $('#txt_comunicaciones').attr('required', 'required');
             }
         });
     });
 </script>