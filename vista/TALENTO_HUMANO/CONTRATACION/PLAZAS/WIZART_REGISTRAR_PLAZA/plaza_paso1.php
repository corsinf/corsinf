  <script>
      $(document).ready(function() {
          <?php if (isset($_GET['_id_plaza'])) { ?>
              var id_plaza = $('#txt_cn_pla_id').val();
              cargar_plaza(id_plaza);
          <?php } ?>

          smartwizard_cargar_plaza();
          cargar_selects2_plaza();

          $('#ddl_cargo').on('change', function() {
              let id_cargo = $(this).val();
              $('#txt_id_cargo').val(id_cargo);

              cargar_propiedades_cargo(id_cargo);
              cargar_requisitos_cargo(id_cargo);

          });
          $('#ddl_id_tipo_seleccion').on('change', function() {
              var id_tipo = $(this).val();

              if (id_tipo == 3) {
                  $('#pnl_prioridad_interna').show();
              } else {
                  $('#pnl_prioridad_interna').hide();
                  $('#cbx_cn_pla_prioridad_interna').prop('checked', false); // limpiar al ocultar
              }
          });

          (function() {
              var id_tipo = $('#ddl_id_tipo_seleccion').val();
              if (id_tipo == 3) {
                  $('#pnl_prioridad_interna').show();
              } else {
                  $('#pnl_prioridad_interna').hide();
              }
          })();
      });

      function cargar_selects2_plaza() {
          cargar_select2_url('ddl_id_tipo_seleccion', '../controlador/TALENTO_HUMANO/CATALOGOS/cn_cat_tipo_seleccionC.php?buscar=true');
          cargar_select2_url('ddl_cargo', '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_cargoC.php?buscar=true');
          cargar_select2_url('ddl_th_dep_id', '../controlador/TALENTO_HUMANO/th_departamentosC.php?buscar=true');
          cargar_select2_url('ddl_id_nomina', '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_nominaC.php?buscar=true');
          cargar_select2_url('ddl_cn_pla_responsable', '../controlador/TALENTO_HUMANO/th_personasC.php?busca_persona_nomina=true');
      }

      //Todo lo que tiene que ver con el cargo
      function cargar_requisitos_cargo(id_cargo) {
          //Requisitos intelectuales
          cargar_aptitudes(id_cargo, false);
          cargar_experiencias_necesarias(id_cargo, false);
          cargar_idiomas(id_cargo, false);
          cargar_iniciativas(id_cargo, false);
          cargar_instrucciones_basicas(id_cargo, false);
          cargar_area_estudios(id_cargo, false);
          //Requisitos fisicos
          cargar_reqf_fisicos(id_cargo, false);
          //Responsabilidades Implícitas
          cargar_responsabilidades(id_cargo, false);
          //Ambiente de Trabajo
          cargar_trabajo(id_cargo, false);
          cargar_riesgos(id_cargo, false);

      }

      function cargar_propiedades_cargo(id_cargo) {
          $.ajax({
              data: {
                  id: id_cargo
              },
              url: '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_cargoC.php?listar=true',
              type: 'post',
              dataType: 'json',
              success: function(response) {
                  if (response && response.length > 0) {
                      $('#txt_cn_pla_descripcion').val(response[0].descripcion);
                  }
              }
          });
      }

      // ─── Utilidades ────────────────────────────────────────────────────────────
      function formatDateToInput(dateStr) {
          if (!dateStr) return '';
          dateStr = dateStr.replace('.000', '').trim();
          if (dateStr.indexOf('T') !== -1) return dateStr.slice(0, 10);
          if (dateStr.indexOf(' ') !== -1) return dateStr.slice(0, 10);
          return dateStr.slice(0, 10);
      }

      function boolVal(val) {
          return (val === 1 || val === '1' || val === true || val === 'true');
      }

      function ParametrosPlaza() {
          return {
              '_id': $('#txt_cn_pla_id').val() || '',
              'txt_cn_pla_titulo': $('#txt_cn_pla_titulo').val(),
              'txt_cn_pla_descripcion': $('#txt_cn_pla_descripcion').val(),
              'ddl_cargo': $('#ddl_cargo').val(),
              'ddl_th_dep_id': $('#ddl_th_dep_id').val(),
              'ddl_id_tipo_seleccion': $('#ddl_id_tipo_seleccion').val(),
              'txt_cn_pla_num_vacantes': $('#txt_cn_pla_num_vacantes').val(),
              'ddl_id_nomina': $('#ddl_id_nomina').val(),
              'txt_cn_pla_fecha_publicacion': $('#txt_cn_pla_fecha_publicacion').val(),
              'txt_cn_pla_fecha_cierre': $('#txt_cn_pla_fecha_cierre').val(),
              'txt_cn_pla_salario_min': $('#txt_cn_pla_salario_min').val(),
              'txt_cn_pla_salario_max': $('#txt_cn_pla_salario_max').val(),
              'ddl_cn_pla_responsable': $('#ddl_cn_pla_responsable').val(),
              'cbx_cn_pla_req_disponibilidad': $('#cbx_cn_pla_req_disponibilidad').is(':checked') ? 1 : 0,
              'cbx_cn_pla_prioridad_interna': $('#cbx_cn_pla_prioridad_interna').is(':checked') ? 1 : 0,
              'cbx_cn_pla_req_documentos': $('#cbx_cn_pla_req_documentos').is(':checked') ? 1 : 0,
              'txt_cn_pla_observaciones': $('#txt_cn_pla_observaciones').val()
          };
      }

      // ─── Wizard: navegación entre pasos ────────────────────────────────────────
      function smartwizard_cargar_plaza() {

          var btnSiguiente = $('<button></button>')
              .text('Siguiente')
              .addClass('btn btn-info')
              .on('click', function() {

                  var wizard = $('#smartwizard_plaza');
                  var pasoActual = wizard.smartWizard("getStepIndex");
                  var form = $('#form_plaza');

                  var step = wizard.find('.tab-pane').eq(pasoActual);
                  var inputs = step.find(':input');

                  var valido = true;

                  inputs.each(function() {
                      if (!form.validate().element(this)) {
                          valido = false;
                      }
                  });

                  if (!valido) {
                      //Swal.fire('', 'Complete los campos obligatorios', 'info');
                      return;
                  }

                  // Insertar en el primer paso
                  if (pasoActual === 0) {

                      if (!validarFechas() || !validarSalarios()) return;
                      insertar_plaza();
                      wizard.smartWizard("next");

                      return; // IMPORTANTE para que no siga ejecutando nada más
                  } else {
                      wizard.smartWizard("next");
                  }

              });

          var btnAtras = $('<button></button>').text('Atras').addClass('btn btn-info').on('click', function() {
              $('#smartwizard_plaza').smartWizard("prev");
              return true;
          });

          $("#smartwizard_plaza").on("showStep", function(e, anchorObject, stepNumber, stepDirection, stepPosition) {
              $("#prev-btn").removeClass('disabled');
              $("#next-btn").removeClass('disabled');
              if (stepPosition === 'first') {
                  $("#prev-btn").addClass('disabled');
              } else if (stepPosition === 'last') {
                  $("#next-btn").addClass('disabled');
              } else {
                  $("#prev-btn").removeClass('disabled');
                  $("#next-btn").removeClass('disabled');
              }
          });

          // Smart Wizard
          $('#smartwizard_plaza').smartWizard({
              selected: 0,
              theme: 'arrows',
              transition: {
                  animation: 'slide-horizontal', // Effect on navigation, none/fade/slide-horizontal/slide-vertical/slide-swing
              },
              toolbarSettings: {
                  toolbarPosition: '',
                  toolbarExtraButtons: [btnAtras, btnSiguiente],
                  showNextButton: false, // Oculta el botón predeterminado "Next"
                  showPreviousButton: false,
              },
          });
      }

      // ─── AJAX ──────────────────────────────────────────────────────────────────
      function insertar_plaza(parametros) {
          parametros = ParametrosPlaza();

          $.ajax({
              data: {
                  parametros: parametros
              },
              url: '../controlador/TALENTO_HUMANO/CONTRATACION/cn_plazaC.php?insertar_editar=true',
              type: 'post',
              dataType: 'json',
              success: function(res) {
                  if (res > 0) {
                      Swal.fire('', 'Plaza guardada con éxito.', 'success').then(function() {
                          $('#txt_cn_pla_id').val(res);

                          <?php if ($_id_plaza == '') { ?>
                              let nueva_Url = `../vista/inicio.php?mod=1011&acc=cn_registrar_plaza&_id_plaza=${res}#step-2`;
                              // Cambia la URL sin recargar
                              window.history.replaceState(null, '', nueva_Url);

                              // Recarga real solo una vez
                              location.reload();
                              return;
                          <?php } ?>

                      });
                  } else if (res == -2) {
                      Swal.fire('', 'Ya existe una plaza con ese título.', 'warning');
                  } else {
                      Swal.fire('', res.msg || 'Error al guardar plaza.', 'error');
                  }
              },
              error: function(xhr) {
                  Swal.fire('', 'Error: ' + xhr.responseText, 'error');
              }
          });
      }

      function cargar_plaza(id) {
          $.ajax({
              data: {
                  id: id
              },
              url: '../controlador/TALENTO_HUMANO/CONTRATACION/cn_plazaC.php?listar=true',
              type: 'post',
              dataType: 'json',
              success: function(response) {
                  if (!response || !response[0]) return;
                  var r = response[0];

                  $('#txt_cn_pla_id').val(r._id);
                  $('input[name="txt_id_cargo"]').val(r.id_cargo);
                  $('#txt_cn_pla_titulo').val(r.cn_pla_titulo);
                  $('#txt_cn_pla_descripcion').val(r.cn_pla_descripcion);
                  $('#txt_cn_pla_num_vacantes').val(r.cn_pla_num_vacantes);
                  $('#txt_cn_pla_fecha_publicacion').val(formatDateToInput(r.cn_pla_fecha_publicacion));
                  $('#txt_cn_pla_fecha_cierre').val(formatDateToInput(r.cn_pla_fecha_cierre));
                  $('#txt_cn_pla_salario_min').val(r.cn_pla_salario_min);
                  $('#txt_cn_pla_salario_max').val(r.cn_pla_salario_max);
                  $('#txt_cn_pla_observaciones').val(r.cn_pla_observaciones);
                  $('#cbx_cn_pla_req_disponibilidad').prop('checked', boolVal(r.cn_pla_req_disponibilidad));
                  $('#cbx_cn_pla_prioridad_interna').prop('checked', boolVal(r.cn_pla_req_prioridad_interna));
                  $('#cbx_cn_pla_req_documentos').prop('checked', boolVal(r.cn_pla_req_documentos));


                  $('#ddl_cargo').append($('<option>', {
                      value: r.id_cargo,
                      text: r.descripcion_cargo,
                      selected: true
                  }));

                  // ── Para cargar las propiedades del cargo ───────────────
                  cargar_requisitos_cargo(r.id_cargo);
                  // ────────────────────────────────────────────────────────

                  $('#ddl_th_dep_id').append($('<option>', {
                      value: r.th_dep_id,
                      text: r.descripcion_departamento,
                      selected: true
                  }));

                  $('#ddl_id_nomina').append($('<option>', {
                      value: r.id_nomina,
                      text: r.descripcion_nomina,
                      selected: true
                  }));

                  $('#ddl_id_tipo_seleccion').append($('<option>', {
                      value: r.id_tipo_seleccion,
                      text: r.descripcion_tipo_seleccion,
                      selected: true
                  }));

                  $('#ddl_cn_pla_responsable').append($('<option>', {
                      value: r.th_per_id_responsable,
                      text: r.per_cedula + ' - ' + r.per_nombre_completo,
                      selected: true
                  }));
              },
              error: function() {
                  Swal.fire('', 'Error al cargar la plaza.', 'error');
              }
          });
      }

      // ─── Fechas ────────────────────────────────────────────────────────────────
      function validarFechaPublicacion() {

          if ($('#txt_cn_pla_id').val()) return true;
          const $pub = $('#txt_cn_pla_fecha_publicacion');
          if (!$pub.val()) return true;
          const hoy = new Date();
          hoy.setHours(0, 0, 0, 0);
          const fechaPub = new Date($pub.val() + 'T00:00:00');
          if (fechaPub < hoy) {
              $pub.addClass('is-invalid').removeClass('is-valid').val('');
              Swal.fire({
                  icon: 'warning',
                  title: 'Fecha inválida',
                  text: 'La fecha de publicación no puede ser anterior a hoy.',
                  confirmButtonText: 'Entendido'
              }).then(() => $pub.focus());
              return false;
          }
          $pub.removeClass('is-invalid').addClass('is-valid');
          validarFechaCierre();
          return true;
      }

      function validarFechaCierre() {
          if ($('#txt_cn_pla_id').val()) return true;
          const $pub = $('#txt_cn_pla_fecha_publicacion');
          const $cierre = $('#txt_cn_pla_fecha_cierre');
          if (!$cierre.val()) return true;
          const hoy = new Date();
          hoy.setHours(0, 0, 0, 0);
          const fechaCierre = new Date($cierre.val() + 'T00:00:00');
          if (fechaCierre < hoy) {
              $cierre.addClass('is-invalid').removeClass('is-valid').val('');
              Swal.fire({
                  icon: 'warning',
                  title: 'Fecha inválida',
                  text: 'La fecha de cierre no puede ser anterior a hoy.',
                  confirmButtonText: 'Entendido'
              }).then(() => $cierre.focus());
              return false;
          }
          if ($pub.val()) {
              const fechaPub = new Date($pub.val() + 'T00:00:00');
              if (fechaCierre < fechaPub) {
                  $cierre.addClass('is-invalid').removeClass('is-valid').val('');
                  Swal.fire({
                      icon: 'error',
                      title: 'Rango incorrecto',
                      text: 'La fecha de cierre no puede ser menor que la de publicación.',
                      confirmButtonText: 'Corregir'
                  }).then(() => $cierre.focus());
                  return false;
              }
          }
          $cierre.removeClass('is-invalid').addClass('is-valid');
          return true;
      }

      function validarFechas() {
          return validarFechaPublicacion() && validarFechaCierre();
      }

      // ─── Salarios ──────────────────────────────────────────────────────────────
      function validarSalarioMin() {
          const $min = $('#txt_cn_pla_salario_min'),
              $max = $('#txt_cn_pla_salario_max');
          if ($min.val() === '') return true;
          const min = parseFloat($min.val());
          if (min < 0) {
              $min.addClass('is-invalid').removeClass('is-valid').val('');
              Swal.fire({
                  icon: 'warning',
                  title: 'Valor inválido',
                  text: 'El salario mínimo no puede ser negativo.',
                  confirmButtonText: 'Entendido'
              }).then(() => $min.focus());
              return false;
          }
          if ($max.val() !== '' && min > parseFloat($max.val())) {
              $min.addClass('is-invalid').removeClass('is-valid').val('');
              Swal.fire({
                  icon: 'error',
                  title: 'Rango incorrecto',
                  text: 'El salario mínimo no puede ser mayor que el máximo.',
                  confirmButtonText: 'Corregir'
              }).then(() => $min.focus());
              return false;
          }
          $min.removeClass('is-invalid').addClass('is-valid');
          return true;
      }

      function validarSalarioMax() {
          const $min = $('#txt_cn_pla_salario_min'),
              $max = $('#txt_cn_pla_salario_max');
          if ($max.val() === '') return true;
          const max = parseFloat($max.val());
          if (max < 0) {
              $max.addClass('is-invalid').removeClass('is-valid').val('');
              Swal.fire({
                  icon: 'warning',
                  title: 'Valor inválido',
                  text: 'El salario máximo no puede ser negativo.',
                  confirmButtonText: 'Entendido'
              }).then(() => $max.focus());
              return false;
          }
          if ($min.val() !== '' && max < parseFloat($min.val())) {
              $max.addClass('is-invalid').removeClass('is-valid').val('');
              Swal.fire({
                  icon: 'error',
                  title: 'Rango incorrecto',
                  text: 'El salario máximo no puede ser menor que el mínimo.',
                  confirmButtonText: 'Corregir'
              }).then(() => $max.focus());
              return false;
          }
          $max.removeClass('is-invalid').addClass('is-valid');
          return true;
      }

      function validarSalarios() {
          return validarSalarioMin() && validarSalarioMax();
      }
  </script>

  <div class="container-fluid">
      <form id="form_plaza">
          <input type="hidden" id="txt_cn_pla_id" name="txt_cn_pla_id" value="<?= $_id_plaza ?>" />

          <div class="row pt-3 mb-2">
              <div class="col-md-4">
                  <label for="txt_cn_pla_titulo" class="form-label">Título de la Plaza </label>
                  <input type="text" class="form-control form-control-sm"
                      id="txt_cn_pla_titulo" name="txt_cn_pla_titulo"
                      maxlength="150" autocomplete="off" required />
              </div>
              <div class="col-md-4">
                  <label for="ddl_cargo" class="form-label">Cargo </label>
                  <select class="form-select form-select-sm select2-validation" id="ddl_cargo" name="ddl_cargo" required>
                      <option value="" selected hidden>-- Seleccione --</option>
                  </select>
              </div>
              <div class="col-md-4">
                  <label for="ddl_th_dep_id" class="form-label">Departamento </label>
                  <select class="form-select form-select-sm select2-validation" id="ddl_th_dep_id" name="ddl_th_dep_id" required>
                      <option value="" selected hidden>-- Seleccione --</option>
                  </select>
              </div>
          </div>

          <div class="row mb-2">
              <div class="col-md-12">
                  <label for="txt_cn_pla_descripcion" class="form-label">Descripción del Puesto </label>
                  <textarea class="form-control form-control-sm"
                      id="txt_cn_pla_descripcion" name="txt_cn_pla_descripcion"
                      rows="3" placeholder="Describa responsabilidades y funciones..." required></textarea>
                  <small class="text-muted">Visible para postulantes</small>
              </div>
          </div>

          <div class="row mb-2">
              <div class="col-md-4">
                  <label for="ddl_id_tipo_seleccion" class="form-label">Tipo de Selección </label>
                  <select class="form-select form-select-sm select2-validation" id="ddl_id_tipo_seleccion" name="ddl_id_tipo_seleccion">
                      <option value="" selected hidden>-- Seleccione --</option>
                  </select>
              </div>
              <div class="col-md-4">
                  <label for="ddl_id_nomina" class="form-label">Figura Legal </label>
                  <select class="form-select form-select-sm select2-validation" id="ddl_id_nomina" name="ddl_id_nomina">
                      <option value="" selected hidden>-- Seleccione --</option>
                  </select>
              </div>
              <div class="col-md-4">
                  <label for="txt_cn_pla_num_vacantes" class="form-label">Número de Vacantes </label>
                  <input type="number" min="1" class="form-control form-control-sm"
                      id="txt_cn_pla_num_vacantes" name="txt_cn_pla_num_vacantes" placeholder="Ej: 1" required />
              </div>
          </div>

          <div id="pnl_prioridad_interna" class="row mb-3" style="display: none;">
              <div class="col-md-12">
                  <label class="form-label fw-semibold fs-7 mb-2 text-muted text-uppercase ls-1">Requerimientos</label>
                  <div class="d-flex flex-wrap gap-4 p-2 border rounded bg-white">
                      <div class="form-check">
                          <input class="form-check-input" type="checkbox"
                              id="cbx_cn_pla_prioridad_interna" name="cbx_cn_pla_prioridad_interna" />
                          <label class="form-check-label fs-7" for="cbx_cn_pla_prioridad_interna">Prioridad Interna</label>
                      </div>
                  </div>
              </div>
          </div>

          <div class="p-3 bg-light rounded-3 border border-dashed mb-3">
              <h6 class="text-muted fs-7 mb-2 fw-bold text-uppercase ls-1">Periodo de Publicación</h6>
              <div class="row g-3">
                  <div class="col-md-6">
                      <label for="txt_cn_pla_fecha_publicacion" class="form-label fs-7 mb-1 fw-bold">Fecha de Publicación </label>
                      <input type="date" class="form-control form-control-sm"
                          id="txt_cn_pla_fecha_publicacion" name="txt_cn_pla_fecha_publicacion" />
                  </div>
                  <div class="col-md-6">
                      <label for="txt_cn_pla_fecha_cierre" class="form-label fs-7 mb-1 fw-bold">Fecha de Cierre </label>
                      <input type="date" class="form-control form-control-sm"
                          id="txt_cn_pla_fecha_cierre" name="txt_cn_pla_fecha_cierre" />
                  </div>
              </div>
          </div>

          <div class="row mb-2">
              <div class="col-md-6">
                  <label for="txt_cn_pla_salario_min" class="form-label">Salario Mínimo </label>
                  <input type="number" step="0.01" min="0" class="form-control form-control-sm"
                      id="txt_cn_pla_salario_min" name="txt_cn_pla_salario_min" placeholder="0.00" />
              </div>
              <div class="col-md-6">
                  <label for="txt_cn_pla_salario_max" class="form-label">Salario Máximo </label>
                  <input type="number" step="0.01" min="0" class="form-control form-control-sm"
                      id="txt_cn_pla_salario_max" name="txt_cn_pla_salario_max" placeholder="0.00" />
              </div>
          </div>

          <div class="row mb-2">
              <div class="col-md-6">
                  <label for="ddl_cn_pla_responsable" class="form-label">Persona Responsable </label>
                  <select class="form-select form-select-sm select2-validation" id="ddl_cn_pla_responsable" name="ddl_cn_pla_responsable">
                      <option value="" selected hidden>-- Seleccione --</option>
                  </select>
                  <small class="text-muted">Solo personas activas en nómina</small>
              </div>
          </div>

          <div class="row mb-3">
              <div class="col-md-12">
                  <label class="form-label fw-semibold fs-7 mb-2 text-muted text-uppercase ls-1">Requerimientos Adicionales</label>
                  <div class="d-flex flex-wrap gap-4 p-2 border rounded bg-white">
                      <div class="form-check">
                          <input class="form-check-input" type="checkbox"
                              id="cbx_cn_pla_req_disponibilidad" name="cbx_cn_pla_req_disponibilidad" />
                          <label class="form-check-label fs-7" for="cbx_cn_pla_req_disponibilidad">Disponibilidad Tiempo Completo</label>
                      </div>
                      <div class="form-check">
                          <input class="form-check-input" type="checkbox"
                              id="cbx_cn_pla_req_documentos" name="cbx_cn_pla_req_documentos" />
                          <label class="form-check-label fs-7" for="cbx_cn_pla_req_documentos">Requiere Documentos</label>
                      </div>
                  </div>
              </div>
          </div>

          <div class="row mb-2">
              <div class="col-md-12">
                  <label for="txt_cn_pla_observaciones" class="form-label">Observaciones</label>
                  <textarea class="form-control form-control-sm"
                      id="txt_cn_pla_observaciones" name="txt_cn_pla_observaciones"
                      rows="2" placeholder="Notas internas..."></textarea>
                  <small class="text-muted">Solo visible internamente</small>
              </div>
          </div>

      </form>
  </div>




  <script>
      $(document).ready(function() {

          // Fechas
          $('#txt_cn_pla_fecha_publicacion').on('change', function() {
              validarFechaPublicacion();
          });
          $('#txt_cn_pla_fecha_cierre').on('change', function() {
              validarFechaCierre();
          });

          // Salarios
          $('#txt_cn_pla_salario_min').on('change', function() {
              validarSalarioMin();
          });
          $('#txt_cn_pla_salario_max').on('change', function() {
              validarSalarioMax();
          });
          $('#txt_cn_pla_salario_min, #txt_cn_pla_salario_max').on('input', function() {
              $(this).removeClass('is-invalid');
          });

          // Asteriscos campos obligatorios
          ['txt_cn_pla_titulo', 'txt_cn_pla_descripcion', 'ddl_cargo', 'ddl_th_dep_id',
              'ddl_id_tipo_seleccion', 'txt_cn_pla_num_vacantes', 'ddl_id_nomina',
              'txt_cn_pla_fecha_publicacion', 'txt_cn_pla_fecha_cierre',
              'txt_cn_pla_salario_min', 'txt_cn_pla_salario_max', 'ddl_cn_pla_responsable'
          ].forEach(function(id) {
              agregar_asterisco_campo_obligatorio(id);
          });

          // Validación jQuery Validate
          $('#form_plaza').validate({
              ignore: ':hidden:not(.select2-hidden-accessible)',
              rules: {
                  txt_cn_pla_titulo: {
                      required: true,
                      maxlength: 150
                  },
                  txt_cn_pla_descripcion: {
                      required: true
                  },
                  ddl_cargo: {
                      required: true
                  },
                  ddl_th_dep_id: {
                      required: true
                  },
                  ddl_id_tipo_seleccion: {
                      required: true
                  },
                  txt_cn_pla_num_vacantes: {
                      required: true,
                      min: 1,
                      digits: true
                  },
                  ddl_id_nomina: {
                      required: true
                  },
                  txt_cn_pla_fecha_publicacion: {
                      required: true
                  },
                  txt_cn_pla_fecha_cierre: {
                      required: true
                  },
                  txt_cn_pla_salario_min: {
                      required: true,
                      number: true,
                      min: 0
                  },
                  txt_cn_pla_salario_max: {
                      required: true,
                      number: true,
                      min: 0
                  },
                  ddl_cn_pla_responsable: {
                      required: true
                  }
              },
              messages: {
                  txt_cn_pla_titulo: {
                      required: 'Ingrese el título de la plaza',
                      maxlength: 'Máximo 150 caracteres'
                  },
                  txt_cn_pla_descripcion: {
                      required: 'Ingrese una descripción'
                  },
                  ddl_cargo: {
                      required: 'Seleccione un cargo'
                  },
                  ddl_th_dep_id: {
                      required: 'Seleccione un departamento'
                  },
                  ddl_id_tipo_seleccion: {
                      required: 'Seleccione el tipo'
                  },
                  txt_cn_pla_num_vacantes: {
                      required: 'Ingrese el número de vacantes',
                      min: 'Mínimo 1',
                      digits: 'Solo números enteros'
                  },
                  ddl_id_nomina: {
                      required: 'Seleccione la figura legal / nómina'
                  },
                  txt_cn_pla_fecha_publicacion: {
                      required: 'Seleccione la fecha de publicación'
                  },
                  txt_cn_pla_fecha_cierre: {
                      required: 'Seleccione la fecha de cierre'
                  },
                  txt_cn_pla_salario_min: {
                      required: 'Ingrese el salario mínimo',
                      number: 'Valor numérico válido',
                      min: 'No puede ser negativo'
                  },
                  txt_cn_pla_salario_max: {
                      required: 'Ingrese el salario máximo',
                      number: 'Valor numérico válido',
                      min: 'No puede ser negativo'
                  },
                  ddl_cn_pla_responsable: {
                      required: 'Seleccione el responsable'
                  }
              },
              errorClass: 'text-danger',
              errorElement: 'div',
              highlight: function(element) {
                  var $el = $(element);
                  $el.addClass('is-invalid').removeClass('is-valid');
                  if ($el.hasClass('select2-hidden-accessible'))
                      $el.next('.select2-container').find('.select2-selection').addClass('is-invalid').removeClass('is-valid');
              },
              unhighlight: function(element) {
                  var $el = $(element);
                  $el.removeClass('is-invalid').addClass('is-valid');
                  if ($el.hasClass('select2-hidden-accessible'))
                      $el.next('.select2-container').find('.select2-selection').removeClass('is-invalid').addClass('is-valid');
              },
              errorPlacement: function(error, element) {
                  if (element.hasClass('select2-hidden-accessible'))
                      error.insertAfter(element.next('.select2-container'));
                  else
                      error.insertAfter(element);
              },
              submitHandler: function() {
                  return false;
              }
          });

          $('.select2-validation').on('change.select2Validation', function() {
              $(this).valid();
          });

      });
  </script>