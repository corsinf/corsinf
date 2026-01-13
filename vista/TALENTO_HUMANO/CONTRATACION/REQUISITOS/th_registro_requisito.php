<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);
$_id = '';

if (isset($_GET['_id'])) {
    $_id = $_GET['_id'];
}

?>
<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<script type="text/javascript">
$(document).ready(function() {

    // Si viene _id cargamos requisito para editar
    <?php if ($_id != '') { ?>
    cargar_requisito(<?= $_id ?>);
    <?php } ?>

    function boolVal(val) {
        // normalizamos 1/0 / true/false / 'true' / 'false'
        return (val === 1 || val === '1' || val === true || val === 'true') ? true : false;
    }


    function cargar_requisito(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_requisitosC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (!response || !response[0]) return;
                var r = response[0];

                $('#txt_th_req_id').val(r._id || '');
                if (typeof r.tipo !== 'undefined' && r.tipo !== null) {
                    $('#ddl_th_req_tipo').val(r.tipo).trigger('change');
                } else {
                    $('#ddl_th_req_tipo').val('').trigger('change');
                }

                $('#chk_th_req_obligatorio').prop('checked', boolVal(r.obligatorio));
                $('#txt_th_req_ponderacion').val(typeof r.ponderacion !== 'undefined' ? r
                    .ponderacion : 0);

                $('#txt_th_req_descripcion').val(r.descripcion || '');
            },
            error: function(err) {
                console.error(err);
                alert('Error al cargar el cargo (revisar consola).');
            }
        });
    }


    // Parametros que enviaremos al controlador
    function ParametrosReq() {
        return {
            '_id': $('#txt_th_req_id').val() || '',
            'th_pla_id': $('#txt_th_pla_id').val() || '',
            'th_req_tipo': $('#ddl_th_req_tipo').val(),
            'th_req_descripcion': $('#txt_th_req_descripcion').val(),
            'th_req_obligatorio': $('#chk_th_req_obligatorio').is(':checked') ? 1 : 0,
            'th_req_ponderacion': $('#txt_th_req_ponderacion').val() || 0
        };
    }

    // Insertar
    function insertar_req(parametros) {
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_requisitosC.php?insertar_editar=true',
            type: 'post',
            dataType: 'json',
            success: function(res) {
                if (res == 1) {
                    Swal.fire('', 'Requisito creado con éxito.', 'success').then(function() {
                        // volver a la lista de requisitos de la plaza (o recargar)
                        window.location.href =
                            '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_plazas';

                    });
                } else {
                    Swal.fire('', res.msg || 'Error al guardar requisito.', 'error');
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });
    }

    // Editar
    function editar_req(parametros) {
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_requisitosC.php?insertar_editar=true',
            type: 'post',
            dataType: 'json',
            success: function(res) {
                if (res == 1) {
                    Swal.fire('', 'Requisito actualizado con éxito.', 'success').then(function() {
                        window.location.href =
                            '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_plazas';
                    });
                } else {
                    Swal.fire('', res.msg || 'Error al actualizar requisito.', 'error');
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });
    }

    // Eliminar (soft delete)
    function delete_req() {
        var id = $('#txt_th_req_id').val() || '';
        if (!id) {
            Swal.fire('', 'ID no encontrado para eliminar', 'warning');
            return;
        }

        Swal.fire({
            title: 'Eliminar Requisito?',
            text: "¿Está seguro de eliminar este requisito?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    data: {
                        _id: id
                    },
                    url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_requisitosC.php?eliminar=true',
                    type: 'post',
                    dataType: 'json',
                    success: function(res) {
                        if (res == 1) {
                            Swal.fire('Eliminado!', 'Requisito eliminado.', 'success').then(
                                function() {
                                    window.location.href =
                                        '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_plazas';
                                });
                        } else {
                            Swal.fire('', res.msg || 'No se pudo eliminar.', 'error');
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        Swal.fire('', 'Error: ' + xhr.responseText, 'error');
                    }
                });
            }
        });
    }

    // Bind botones
    $('#btn_guardar_req').on('click', function() {
        if (!$("#form_requisito").valid()) return;
        var params = ParametrosReq();
        if (!params.th_req_tipo) {
            Swal.fire('', 'Seleccione el tipo de requisito.', 'warning');
            return;
        }
        if (!params.th_req_descripcion) {
            Swal.fire('', 'Ingrese la descripción del requisito.', 'warning');
            return;
        }
        insertar_req(params);
    });

    $('#btn_editar_req').on('click', function() {
        if (!$("#form_requisito").valid()) return;
        var params = ParametrosReq();
        editar_req(params);
    });

    $('#btn_eliminar_req').on('click', function() {
        delete_req();
    });

});

function abrir_modal_requisitos(id_requisito) {
    // Limpia los campos del formulario dentro del modal
    $('#modal_requisitos select').val('');
    $('#modal_requisitos textarea').val('');
    // Abre el modal manualmente
    var modal = new bootstrap.Modal(document.getElementById('modal_requisitos'), {
        backdrop: 'static',
        keyboard: false
    });

    modal.show();

    if (id_requisito) {
        cargar_requisito(id_requisito);
        $('#pnl_crear').hide();
        $('#pnl_actualizar').show();
    } else {
        $('#pnl_crear').show();
        $('#pnl_actualizar').hide();
    }

}

function cargar_requisito(id) {
    $.ajax({
        data: {
            id: id
        },
        url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_requisitosC.php?listar_requisito=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            if (!response || !response[0]) return;
            var r = response[0];

            // ID del requisito
            $('#txt_th_req_id').val(r._id || r.th_req_id || '');
            $('#txt_th_pla_id').val(r.th_pla_id || r.th_pla_id || '');
            // Tipo
            if (r.tipo) {
                if ($('#ddl_th_req_tipo').find("option[value='" + r.tipo + "']").length) {
                    $('#ddl_th_req_tipo').val(r.tipo);
                } else {
                    $('#ddl_th_req_tipo').append(
                        $('<option>', {
                            value: r.tipo,
                            text: r.tipo
                        })
                    );
                    $('#ddl_th_req_tipo').val(r.tipo);
                }
            }

            // Descripción
            $('#txt_th_req_descripcion').val(r.descripcion || '');
            // Obligatorio
            $('#chk_th_req_obligatorio').prop(
                'checked',
                r.obligatorio == 1 || r.obligatorio === true || r.obligatorio == '1'
            );
            // Ponderación
            $('#txt_th_req_ponderacion').val(r.ponderacion || 0);

        },
        error: function(err) {
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ocurrió un error al cargar el requisito. Revisa la consola.'
            });
        }
    });
}
</script>
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Cargos</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Registros</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body p-5">
                        <div class="card-title d-flex align-items-center">
                            <div><i class="bx bxs-briefcase me-1 font-22 text-primary"></i></div>
                            <h5 class="mb-0 text-primary">
                                <?php
                                if ($_id == '') {
                                    echo 'Registrar Cargo';
                                } else {
                                    echo 'Modificar Cargo';
                                }
                                ?>
                            </h5>

                            <div class="row m-2">
                                <div class="col-sm-12">
                                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_plazas"
                                        class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i>
                                        Regresar</a>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <form id="form_requisito">

                            <input type="hidden" id="txt_th_req_id" name="txt_th_req_id" value="" />
                            <!-- Sección: Tipo de Requisito -->
                            <div class="mb-4">
                                <label for="ddl_th_req_tipo" class="form-label fw-bold">
                                    <i class="bx bx-category me-1"></i>Tipo de Requisito
                                </label>

                                <select id="ddl_th_req_tipo" class="form-select select2-validation"
                                    name="ddl_th_req_tipo" required>
                                    <option value="" selected hidden>-- Seleccione el tipo de requisito --</option>

                                    <option value="identificacion">Identificación</option>
                                    <option value="certificado_domicilio">Certificado de Domicilio</option>
                                    <option value="referencias_personales">Referencias Personales</option>

                                    <option value="titulo_academico">Título Académico</option>
                                    <option value="certificado_estudios">Certificado de Estudios</option>
                                    <option value="cursos_aprobados">Cursos Aprobados</option>

                                    <option value="experiencia_laboral">Experiencia Laboral</option>
                                    <option value="referencias_laborales">Referencias Laborales</option>
                                    <option value="certificado_trabajo">Certificado de Trabajo</option>

                                    <option value="antecedentes_penales">Antecedentes Penales</option>
                                    <option value="antecedentes_policia">Antecedentes de Policía</option>
                                    <option value="certificado_medico">Certificado Médico</option>

                                    <option value="hoja_vida">Hoja de Vida</option>
                                    <option value="documentos_varios">Documentos Varios</option>
                                    <option value="otro">Otro</option>
                                </select>
                            </div>


                            <!-- Sección: Configuración -->
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <div class="card border h-100">
                                        <div class="card-body">
                                            <label class="form-label fw-bold d-block mb-3">
                                                <i class="bx bx-check-circle me-1"></i>Estado del Requisito
                                            </label>
                                            <div class="form-check form-switch">
                                                <input type="checkbox" id="chk_th_req_obligatorio"
                                                    class="form-check-input" style="width: 3em; height: 1.5em;" />
                                                <label class="form-check-label ms-2" for="chk_th_req_obligatorio">
                                                    <strong>Es obligatorio</strong>
                                                    <small class="text-muted d-block">El candidato debe cumplir este
                                                        requisito</small>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card border h-100">
                                        <div class="card-body">
                                            <label for="txt_th_req_ponderacion" class="form-label fw-bold">
                                                <i class="bx bx-stats me-1"></i>Ponderación
                                            </label>
                                            <div class="input-group">
                                                <input type="number" id="txt_th_req_ponderacion" class="form-control"
                                                    min="0" max="100" value="0" />
                                                <span class="input-group-text">%</span>
                                            </div>
                                            <small class="text-muted">Peso del requisito en la evaluación
                                                (0-100)</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sección: Descripción -->
                            <div class="mb-4">
                                <label for="txt_th_req_descripcion" class="form-label fw-bold">
                                    <i class="bx bx-detail me-1"></i>Descripción del Requisito
                                </label>
                                <textarea id="txt_th_req_descripcion" class="form-control" rows="5"
                                    placeholder="Describa detalladamente el requisito..." required></textarea>
                                <small class="text-muted">Especifique claramente qué debe cumplir el candidato</small>
                            </div>
                            <div class="d-flex justify-content-end gap-2">
                                <?php if ($_id == '') { ?>
                                <button type="button" class="btn btn-success" id="btn_guardar_req">
                                    <i class="bx bx-save me-1"></i> Guardar Cargo
                                </button>
                                <?php } else { ?>
                                <button type="button" class="btn btn-primary" id="btn_editar_req">
                                    <i class="bx bx-edit me-1"></i> Actualizar Cargo
                                </button>
                                <button type="button" class="btn btn-danger" id="btn_eliminar_req">
                                    <i class="bx bx-trash me-1"></i> Eliminar Cargo
                                </button>
                                <?php } ?>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>