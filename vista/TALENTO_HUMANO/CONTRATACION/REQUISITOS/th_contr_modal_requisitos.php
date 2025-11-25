<script>
$(document).ready(function() {

    let idPlaza = Number(localStorage.getItem('plaza_id'));
    if (idPlaza > 0) {
        cargar_requisitos(idPlaza);
    }

});

function cargar_requisitos(idPlaza) {
    // Si select2 ya está inicializado, destruirlo
    if ($('#ddl_requisitos').hasClass("select2-hidden-accessible")) {
        $('#ddl_requisitos').select2('destroy');
    }

    $('#ddl_requisitos').select2({
        dropdownParent: $('#modal_requisito'),
        ajax: {
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_plaza_requisitosC.php?buscar=true',
            dataType: 'json',
            data: function(params) {
                return {
                    q: params.term, // texto buscado
                    pla_id: idPlaza // ID de la plaza
                };
            },
            processResults: function(data) {
                return {
                    results: data
                };
            }
        },
        minimumInputLength: 0,
        placeholder: "Seleccione un requisito",
        language: {
            noResults: function() {
                return "No hay requisitos disponibles para asignar";
            },
            searching: function() {
                return "Buscando...";
            }
        }
    });
}

function Parametros() {
    let idPlaza = Number(localStorage.getItem('plaza_id'));
    return {
        'th_pla_id': idPlaza,
        'th_req_id': $('#ddl_requisitos').val()
    };
}


function insertar_plaza_requerimiento() {


    $.ajax({
        data: {
            parametros: Parametros()
        },
        url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_plaza_requisitosC.php?insertar_editar=true',
        type: 'post',
        dataType: 'json',
        success: function(res) {
            if (res > 0) {
                Swal.fire('', 'Plaza creada con éxito.', 'success').then(function() {
                    $('#modal_requisito').modal('hide');
                    $('#tbl_requisitos').DataTable().ajax.reload(null, false);
                });
            } else if (res == -2) {
                Swal.fire('', res.msg || 'Error al guardar plaza.', 'error');
            } else {
                Swal.fire('', res.msg || 'Error al guardar plaza.', 'error');
            }
        },
        error: function(xhr) {
            console.error(xhr.responseText);
            Swal.fire('', 'Error: ' + xhr.responseText, 'error');
        }
    });

    $('#txt_th_pla_titulo').on('input', function() {
        $(this).removeClass('is-invalid');
        $('#error_txt_th_pla_titulo').text('');
    });
}
</script>

<div class="modal fade" id="modal_requisito" tabindex="-1" aria-labelledby="modalRequisitoLabel" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-modal="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="modalRequisitoLabel">
                    <i class="bx bx-list-check me-2"></i> Requisito
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body p-4">
                <form id="form_requisito">
                    <div class="col-md-12">
                        <label for="ddl_requisitos" class="form-label fw-bold">
                            <i class="bx bx-briefcase me-2 text-primary"></i> Requisito
                        </label>
                        <select class="form-select select2-validation" id="ddl_requisitos" name="ddl_requisitos"
                            required>
                            <option value="" selected hidden>-- Seleccione el requisito --</option>
                        </select>
                    </div>
                    <!-- Botones de acción -->
                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            <i class="bx bx-x me-1"></i> Cancelar
                        </button>

                        <div id="pnl_crear">
                            <button type="button" class="btn btn-success" onclick="insertar_plaza_requerimiento()">
                                <i class="bx bx-save me-1"></i> Crear Requisito
                            </button>
                        </div>

                        <div id="pnl_actualizar" style="display:none">
                            <button type="button" class="btn btn-danger" id="btn_eliminar_req">
                                <i class="bx bx-trash me-1"></i> Eliminar
                            </button>
                            <button type="button" class="btn btn-primary" id="btn_editar_req">
                                <i class="bx bx-check me-1"></i> Actualizar Requisito
                            </button>
                        </div>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>