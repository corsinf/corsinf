  <script>
      function abrir_modal_competencias() {
          var modal = new bootstrap.Modal(
              document.getElementById('modal_cargo_competencia'), {
                  backdrop: 'static',
                  keyboard: false
              }
          );

          modal.show();
      }

      function obtenerParametrosCargoCompetencia() {
          return {
              '_id': $('#txt_th_carcomp_id').val() || '',
              'th_car_id': $('#txt_th_car_id').val() || $('#txt_th_car_id').attr('value') || '',
              'th_comp_id': $('#ddl_competencias').val() || '',
              'th_carcomp_nivel_requerido': $('#txt_th_carcomp_nivel_requerido').val() || '',
              'nivel_utilizacion': $('#txt_th_carcomp_nivel_utilizacion').val() || '',
              'nivel_contribucion': $('#txt_th_carcomp_nivel_contribucion').val() || '',
              'nivel_habilidad': $('#txt_th_carcomp_nivel_habilidad').val() || '',
              'nivel_maestria': $('#txt_th_carcomp_nivel_maestria').val() || '',
              'disc_d': $('#txt_th_carcomp_disc_valor_d').val() || '',
              'disc_i': $('#txt_th_carcomp_disc_valor_i').val() || '',
              'disc_s': $('#txt_th_carcomp_disc_valor_s').val() || '',
              'disc_c': $('#txt_th_carcomp_disc_valor_c').val() || '',
              'grafica_json': null, // si generas gráfico poner aquí JSON
              'es_critica': $('#ddl_th_carcomp_es_critica').val() === '1' ? 1 : 0,
              'es_evaluable': $('#ddl_th_carcomp_es_evaluable').val() === '1' ? 1 : 0,
              'metodo': $('#txt_th_carcomp_metodo_evaluacion').val().trim() || null,
              'ponderacion': $('#txt_th_carcomp_ponderacion').val() || null,
              'observaciones': $('#txt_th_carcomp_observaciones').val() || null,
              'estado': 1
          };
      }

      // ---------- INSERTAR ----------
      function insertar_cargo_competencia() {
          var parametros = obtenerParametrosCargoCompetencia();

          $.ajax({
              data: {
                  parametros: parametros
              },
              url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_cargo_competenciasC.php?insertar_editar=true',
              type: 'post',
              dataType: 'json',
              success: function(response) {
                  if (response == 1 || response === true) {
                      Swal.fire('', 'Competencia creada con éxito.', 'success').then(function() {
                          var modalEl = document.getElementById('modal_cargo_competencia');
                          var modal = bootstrap.Modal.getInstance(modalEl);
                          if (modal) modal.hide();

                          // recargar la lista de competencias del cargo (debes tener una función listar)
                          if (typeof listar_competencias_cargo === 'function') {
                              listar_competencias_cargo(parametros.th_car_id);
                          } else {
                              // fallback: recarga la página si no existe la función
                              location.reload();
                          }
                      });
                  } else if (response == -2) {
                      Swal.fire('', 'Ya existe esa competencia para este cargo.', 'warning');
                  } else {
                      var msg = (typeof response === 'object' && response.msg) ? response.msg :
                          'Error al guardar competencia.';
                      Swal.fire('', msg, 'error');
                  }
              },
              error: function(xhr, status, error) {
                  console.error('Error insertar_cargo_competencia:', status, error, xhr.responseText);
                  Swal.fire('', 'Error al conectar con el servidor: ' + xhr.responseText, 'error');
              }
          });
      }

      function eliminar_cargo_competencia(id) {
          var id = id;
          if (!id) {
              Swal.fire('', 'Registro inválido.', 'warning');
              return;
          }

          Swal.fire({
              title: '¿Eliminar competencia?',
              text: "La competencia será desactivada (soft delete).",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonText: 'Sí, eliminar',
              cancelButtonText: 'Cancelar'
          }).then((result) => {
              if (result.isConfirmed) {
                  $.ajax({
                      data: {
                          id: id
                      },
                      url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_cargo_competenciasC.php?eliminar=true',
                      type: 'post',
                      dataType: 'json',
                      success: function(response) {
                          if (response == 1 || response === true) {
                              Swal.fire('', 'Competencia eliminada.', 'success').then(function() {
                                  var modalEl = document.getElementById(
                                      'modal_cargo_competencia');
                                  var modal = bootstrap.Modal.getInstance(modalEl);
                                  if (modal) modal.hide();

                                  var th_car_id = $('#txt_th_car_id').val();
                                  if (typeof listar_competencias_cargo === 'function') {
                                      listar_competencias_cargo(th_car_id);
                                  } else {
                                      location.reload();
                                  }
                              });
                          } else {
                              Swal.fire('', 'Error al eliminar.', 'error');
                          }
                      },
                      error: function(xhr, status, error) {
                          console.error('Error eliminar_cargo_competencia:', status, error, xhr
                              .responseText);
                          Swal.fire('', 'Error al conectar con el servidor: ' + xhr.responseText,
                              'error');
                      }
                  });
              }
          });
      }

      function abrir_modal_cargo_competencia_editar(competencia) {
          limpiar_form_cargo_competencia();

          $('#txt_th_carcomp_id').val(competencia.th_carcomp_id || competencia.id || '');
          $('#txt_th_car_id').val(competencia.th_car_id || '');

          if (competencia.th_comp_id && competencia.th_comp_nombre) {
              var option = new Option(competencia.th_comp_nombre, competencia.th_comp_id, true, true);
              $('#ddl_competencias').append(option).trigger('change');
          } else if (competencia.th_comp_id) {
              $('#ddl_competencias').val(competencia.th_comp_id).trigger('change');
          }

          $('#txt_th_carcomp_nivel_requerido').val(competencia.th_carcomp_nivel_requerido || '');
          $('#txt_th_carcomp_nivel_utilizacion').val(competencia.th_carcomp_nivel_utilizacion || '');
          $('#txt_th_carcomp_nivel_contribucion').val(competencia.th_carcomp_nivel_contribucion || '');
          $('#txt_th_carcomp_nivel_habilidad').val(competencia.th_carcomp_nivel_habilidad || '');
          $('#txt_th_carcomp_nivel_maestria').val(competencia.th_carcomp_nivel_maestria || '');

          $('#txt_th_carcomp_disc_valor_d').val(competencia.th_carcomp_disc_valor_d || '');
          $('#txt_th_carcomp_disc_valor_i').val(competencia.th_carcomp_disc_valor_i || '');
          $('#txt_th_carcomp_disc_valor_s').val(competencia.th_carcomp_disc_valor_s || '');
          $('#txt_th_carcomp_disc_valor_c').val(competencia.th_carcomp_disc_valor_c || '');

          $('#ddl_th_carcomp_es_critica').val(competencia.th_carcomp_es_critica ? '1' : '0');
          $('#ddl_th_carcomp_es_evaluable').val(competencia.th_carcomp_es_evaluable ? '1' : '0');
          $('#txt_th_carcomp_metodo_evaluacion').val(competencia.th_carcomp_metodo_evaluacion || '');
          $('#txt_th_carcomp_ponderacion').val(competencia.th_carcomp_ponderacion || '');
          $('#txt_th_carcomp_observaciones').val(competencia.th_carcomp_observaciones || '');

          $('#pnl_crear').hide();
          $('#pnl_actualizar').show();

          var modal = new bootstrap.Modal(document.getElementById('modal_cargo_competencia'));
          modal.show();
      }

      var tbl_competencias;

      function cargar_competencias(id_cargo) {

          id_cargo = id_cargo || '';

          tbl_competencias = $('#tbl_competencias').DataTable({
              destroy: true,
              responsive: true,
              language: {
                  url: '../assets/plugins/datatable/spanish.json'
              },
              ajax: {
                  url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_cargo_competenciasC.php?listar=true',
                  type: 'POST',
                  data: function(d) {
                      d.id = id_cargo;
                  },
                  dataSrc: ''
              },
              columns: [{
                      data: 'th_car_id'
                  }, // nombre th_comp.nombre
                  {
                      data: 'th_carcomp_nivel_requerido'
                  },
                  {
                      data: 'th_carcomp_ponderacion'
                  },
                  {
                      data: 'th_carcomp_es_critica',
                      render: function(v) {
                          return v == 1 ?
                              '<span class="badge bg-danger">Sí</span>' :
                              '<span class="badge bg-secondary">No</span>';
                      }
                  },
                  {
                      data: null,
                      orderable: false,
                      searchable: false,
                      className: 'text-center',
                      render: function(data, type, item) {
                          let id = item._id;
                          return `
                        <button class="btn btn-primary btn-sm" 
                                onclick="abrir_modal_competencias(${id})"
                                data-bs-toggle="tooltip" 
                                title="Editar">
                            <i class="bx bx-edit"></i>
                        </button>

                        <button class="btn btn-danger btn-sm" 
                                onclick="eliminar_cargo_competencia(${id})"
                                data-bs-toggle="tooltip" 
                                title="Eliminar">
                            <i class="bx bx-trash"></i>
                        </button>
                    `;
                      }
                  }
              ],
              order: [
                  [0, 'asc']
              ],
              drawCallback: function() {
                  $('[data-bs-toggle="tooltip"]').tooltip();
              }
          });
      }
  </script>



  <div class="container-fluid">

      <div class="row mb-3 align-items-center">

          <!-- Título -->
          <div class="col-md-8">
              <h5 class="fw-bold text-primary mb-1">
                  <i class="bx bx-check-shield me-2"></i> Competencias
              </h5>

              <small class="text-muted">
                  <i class="bi bi-clipboard-check me-1"></i>
                  Estado de cumplimiento de habilidades y competencias
              </small>
          </div>
          <?php if (isset($_GET['_id'])) { ?>
              <!-- Botonera -->
              <div class="col-md-4 d-flex justify-content-end gap-2"
                  id="pnl_competencias_botonera">
                  <button type="button" class="btn btn-success btn-sm shadow-sm"
                      id="btn_abrir_modal_competencias"
                      onclick="abrir_modal_competencias()">
                      <i class="bx bx-plus-circle me-1"></i> Competencias
                  </button>
              </div>
          <?php } ?>

      </div>
      <?php if (isset($_GET['_id'])) { ?>
          <!-- Aquí puedes colocar la tabla -->
          <div class="row">
              <div class="col-md-12">
                  <div class="table-responsive">
                      <table id="tbl_competencias"
                          class="table table-bordered table-striped w-100">
                          <thead>
                              <tr>
                                  <th>Competencia</th>
                                  <th>Nivel Requerido</th>
                                  <th>Ponderación</th>
                                  <th>Crítica</th>
                                  <th>Acciones</th>
                              </tr>
                          </thead>
                          <tbody></tbody>
                      </table>
                  </div>
              </div>
          </div>
      <?php } ?>

  </div>


  <div class="modal fade" id="modal_cargo_competencia" tabindex="-1" aria-labelledby="lbl_modal_cargo_competencia"
      aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-modal="true">

      <div class="modal-dialog modal-dialog-centered modal-xl">
          <div class="modal-content">

              <!-- Header -->
              <div class="modal-header bg-light">
                  <h5 class="modal-title" id="lbl_modal_cargo_competencia">
                      <i class="bx bx-brain me-2"></i> Registrar Competencia del Cargo
                  </h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>

              <!-- Body -->
              <div class="modal-body p-4">
                  <form id="form_cargo_competencia">

                      <!-- IDs ocultos -->
                      <input type="hidden" id="txt_th_carcomp_id" name="th_carcomp_id">
                      <input type="hidden" id="txt_th_car_id" name="th_car_id">

                      <!-- Competencia -->
                      <div class="col-md-12 mb-3">
                          <label class="form-label fw-bold">
                              <i class="bx bx-target-lock text-primary me-1"></i> Competencia
                          </label>
                          <select class="form-select select2-validation" id="ddl_competencias" name="ddl_competencias"
                              required>
                              <option value="" hidden selected>-- Seleccione la competencia --</option>
                          </select>
                      </div>

                      <!-- Niveles -->
                      <div class="row g-3 border rounded p-3 mb-3 bg-light">
                          <h6 class="fw-bold text-secondary">Niveles de competencia</h6>

                          <div class="col-md-3">
                              <label class="form-label">Nivel Requerido</label>
                              <input type="number" min="0" max="100" class="form-control"
                                  id="txt_th_carcomp_nivel_requerido" name="th_carcomp_nivel_requerido">
                          </div>

                          <div class="col-md-3">
                              <label class="form-label">Nivel Utilización</label>
                              <input type="number" min="0" max="100" class="form-control"
                                  id="txt_th_carcomp_nivel_utilizacion" name="th_carcomp_nivel_utilizacion">
                          </div>

                          <div class="col-md-3">
                              <label class="form-label">Nivel Contribución</label>
                              <input type="number" min="0" max="100" class="form-control"
                                  id="txt_th_carcomp_nivel_contribucion" name="th_carcomp_nivel_contribucion">
                          </div>

                          <div class="col-md-3">
                              <label class="form-label">Nivel Habilidad</label>
                              <input type="number" min="0" max="100" class="form-control"
                                  id="txt_th_carcomp_nivel_habilidad" name="th_carcomp_nivel_habilidad">
                          </div>

                          <div class="col-md-3">
                              <label class="form-label">Nivel Maestría</label>
                              <input type="number" min="0" max="100" class="form-control"
                                  id="txt_th_carcomp_nivel_maestria" name="th_carcomp_nivel_maestria">
                          </div>
                      </div>

                      <!-- DISC -->
                      <div class="row g-3 border rounded p-3 mb-3">
                          <h6 class="fw-bold text-secondary">Valores DISC</h6>

                          <div class="col-md-3">
                              <label class="form-label">Valor D</label>
                              <input type="number" class="form-control" id="txt_th_carcomp_disc_valor_d"
                                  name="th_carcomp_disc_valor_d">
                          </div>

                          <div class="col-md-3">
                              <label class="form-label">Valor I</label>
                              <input type="number" class="form-control" id="txt_th_carcomp_disc_valor_i"
                                  name="th_carcomp_disc_valor_i">
                          </div>

                          <div class="col-md-3">
                              <label class="form-label">Valor S</label>
                              <input type="number" class="form-control" id="txt_th_carcomp_disc_valor_s"
                                  name="th_carcomp_disc_valor_s">
                          </div>

                          <div class="col-md-3">
                              <label class="form-label">Valor C</label>
                              <input type="number" class="form-control" id="txt_th_carcomp_disc_valor_c"
                                  name="th_carcomp_disc_valor_c">
                          </div>
                      </div>

                      <!-- Evaluación -->
                      <div class="row g-3 border rounded p-3 mb-3">
                          <h6 class="fw-bold text-secondary">Evaluación</h6>

                          <div class="col-md-3">
                              <label class="form-label">Es Crítica</label>
                              <select id="ddl_th_carcomp_es_critica" name="th_carcomp_es_critica" class="form-select">
                                  <option value="0">No</option>
                                  <option value="1">Sí</option>
                              </select>
                          </div>

                          <div class="col-md-3">
                              <label class="form-label">Es Evaluable</label>
                              <select id="ddl_th_carcomp_es_evaluable" name="th_carcomp_es_evaluable" class="form-select">
                                  <option value="0">No</option>
                                  <option value="1">Sí</option>
                              </select>
                          </div>

                          <div class="col-md-6">
                              <label class="form-label">Método Evaluación</label>
                              <input type="text" class="form-control" id="txt_th_carcomp_metodo_evaluacion"
                                  name="th_carcomp_metodo_evaluacion" placeholder="Ej: entrevista, prueba técnica...">
                          </div>

                          <div class="col-md-3">
                              <label class="form-label">Ponderación (%)</label>
                              <input type="number" min="0" max="100" class="form-control" id="txt_th_carcomp_ponderacion"
                                  name="th_carcomp_ponderacion">
                          </div>

                          <div class="col-md-12">
                              <label class="form-label">Observaciones</label>
                              <textarea class="form-control" id="txt_th_carcomp_observaciones"
                                  name="th_carcomp_observaciones" rows="2"></textarea>
                          </div>
                      </div>

                  </form>
              </div>

              <!-- Footer -->
              <div class="modal-footer justify-content-end gap-2">

                  <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                      <i class="bx bx-x me-1"></i> Cerrar
                  </button>

                  <div id="pnl_crear">
                      <button type="button" class="btn btn-success" onclick="insertar_cargo_competencia()">
                          <i class="bx bx-save me-1"></i> Crear
                      </button>
                  </div>

                  <div id="pnl_actualizar" style="display:none;">
                      <button type="button" class="btn btn-danger" onclick="eliminar_cargo_competencia()">
                          <i class="bx bx-trash me-1"></i> Eliminar
                      </button>

                      <button type="button" class="btn btn-primary" onclick="actualizar_cargo_competencia()">
                          <i class="bx bx-check me-1"></i> Actualizar
                      </button>
                  </div>

              </div>

          </div>
      </div>
  </div>