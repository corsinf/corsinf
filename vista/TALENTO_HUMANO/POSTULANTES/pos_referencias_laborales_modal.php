<!-- Modal para agregar referencias laborales-->
<div class="modal fade" id="modal_agregar_referencia_laboral" tabindex="-1" aria-hidden="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header bg-dark bg-opacity-10">
                <div>
                    <h5 class="modal-title fw-bold text-primary" id="lbl_titulo_referencia_laboral">
                        <i class='bx bx-briefcase me-2'></i>Referencias Laborales
                    </h5>
                    <small class="text-muted">Ingresa contactos de empleadores previos que puedan validar tu experiencia.</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_parametros_referencias_laborales()"></button>
            </div>

            <form id="form_referencias_laborales" enctype="multipart/form-data" class="needs-validation">
                <div class="modal-body">

                    <input type="hidden" name="txt_referencias_laborales_id" id="txt_referencias_laborales_id">
                    <input type="hidden" name="txt_postulante_cedula" id="txt_postulante_cedula">
                    <input type="hidden" name="txt_postulante_id" id="txt_postulante_id">
                    <input type="hidden" name="txt_referencia_experiencia_id" id="txt_referencia_experiencia_id">

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="txt_nombre_referencia" class="form-label fw-semibold fs-7">Nombre del Jefe o Contacto </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white text-muted"><i class='bx bx-user'></i></span>
                                <input type="text" class="form-control no_caracteres" name="txt_nombre_referencia" id="txt_nombre_referencia" maxlength="50" placeholder="Ej: Ing. Juan Pérez">
                            </div>
                            <label class="error" style="display: none;" for="txt_nombre_referencia"></label>
                        </div>
                    </div>

                    <div id="pnl_referencia_empresa" class="row mb-3" style="display: none;">
                        <div class="col-md-12">
                            <label for="txt_referencia_nombre_empresa" class="form-label fw-semibold fs-7">Empresa / Institución </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white text-muted"><i class='bx bx-buildings'></i></span>
                                <input type="text" class="form-control no_caracteres" name="txt_referencia_nombre_empresa" id="txt_referencia_nombre_empresa" maxlength="100" placeholder="Nombre de la organización">
                            </div>
                            <label class="error" style="display: none;" for="txt_referencia_nombre_empresa"></label>
                        </div>
                    </div>

                    <div class="row mb-3 g-3">
                        <div class="col-md-6">
                            <label for="txt_telefono_referencia" class="form-label fw-semibold fs-7">Teléfono de Contacto </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white text-muted"><i class='bx bx-phone'></i></span>
                                <input type="text" class="form-control solo_numeros_int" name="txt_telefono_referencia" id="txt_telefono_referencia" maxlength="15" placeholder="Ej: 0987654321">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="txt_referencia_correo" class="form-label fw-semibold fs-7">Correo Electrónico </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white text-muted"><i class='bx bx-envelope'></i></span>
                                <input type="email" class="form-control" name="txt_referencia_correo" id="txt_referencia_correo" maxlength="100" placeholder="ejemplo@correo.com">
                            </div>
                        </div>
                    </div>

                    <div class="p-3 bg-light rounded-3 border border-dashed">
                        <label for="txt_copia_carta_recomendacion" class="form-label fw-semibold">Carta de Recomendación (PDF) </label>
                        <input type="file" class="form-control form-control-sm" name="txt_copia_carta_recomendacion" id="txt_copia_carta_recomendacion" accept=".pdf">
                        <input type="hidden" name="txt_ruta_guardada_carta_recomendacion" id="txt_ruta_guardada_carta_recomendacion">
                        <div class="form-text text-xs"><i class='bx bx-info-circle'></i> Adjunta el documento escaneado firmado. Máximo 5MB.</div>
                    </div>

                </div>

                <div class="modal-footer bg-light border-top-0 d-flex justify-content-between">
                    <button type="button" style="display: none;" class="btn btn-outline-danger btn-sm" id="btn_eliminar_referencia_laboral" onclick="delete_datos_referencias_laborales();">
                        <i class="bx bx-trash"></i> Eliminar
                    </button>

                    <div class="ms-auto">
                        <button type="button" class="btn btn-secondary btn-sm me-2" data-bs-dismiss="modal" onclick="limpiar_parametros_referencias_laborales()">Cancelar</button>
                        <button type="button" class="btn btn-primary btn-sm px-4" id="btn_guardar_referencia_laboral" onclick="insertar_editar_referencias_laborales();">
                            <i class="bx bx-save"></i> Guardar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>