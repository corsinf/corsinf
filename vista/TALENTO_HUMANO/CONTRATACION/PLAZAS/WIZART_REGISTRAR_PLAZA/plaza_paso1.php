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
                              id="cbx_cn_pla_prioridad_interna" name="cbx_cn_pla_prioridad_interna" />
                          <label class="form-check-label fs-7" for="cbx_cn_pla_prioridad_interna">Prioridad Interna</label>
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