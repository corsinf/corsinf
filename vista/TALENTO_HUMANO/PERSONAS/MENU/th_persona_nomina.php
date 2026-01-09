<?php

/**
 * @deprecated Archivo dado de baja el 09/01/2025.
 * @note Este archivo se mantiene como respaldo, pero ya no se utilizará en producción.
 * @warning No modificar este archivo. Para cambios, referirse a la nueva implementación.
 */


// Tabla
// CREATE TABLE [_talentoh].[th_per_nomina] (
//   [th_per_nom_id] int  IDENTITY(1,1) NOT NULL,
//   [id_nomina] int  NULL,
//   [th_per_id] int  NULL,
//   [th_per_nom_remuneracion] decimal(10,2)  NULL,
//   [th_per_nom_fecha_ini] date  NULL,
//   [th_per_nom_fecha_fin] date  NULL,
//   [th_per_nom_estado] smallint DEFAULT 1 NULL,
//   [th_per_nom_fecha_creacion] datetime2(7) DEFAULT getdate() NULL,
//   [th_per_nom_fecha_modificacion] datetime2(7)  NULL,
//   CONSTRAINT [PK__th_per_n__AFA1BE88E5D9FA20] PRIMARY KEY CLUSTERED ([th_per_nom_id])
// WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)  
// ON [PRIMARY]
// )  
// ON [PRIMARY]
// GO

// ALTER TABLE [_talentoh].[th_per_nomina] SET (LOCK_ESCALATION = TABLE)


?>



<!-- Colocar en la vista vista/TALENTO_HUMANO/PERSONAS/MENU/th_persona_nomina.php -->
<li class="nav-item" role="presentation">
    <a class="nav-link border border-primary rounded-3 shadow-sm px-3 py-2" data-bs-toggle="tab" href="#tab_nomina" role="tab">
        <div class="d-flex align-items-center gap-2">
            <i class="bx bxs-dollar-circle text-primary" style="font-size: 0.875rem;"></i>
            <span class="fw-semibold text-primary" style="font-size: 0.875rem;">Nómina</span>
        </div>
    </a>
</li>


<div class="tab-pane fade" id="tab_nomina" role="tabpanel">
    <div class="card">
        <div class="d-flex flex-column mx-4">
            <div class="card-body">
                <div class="mb-2">
                    <div class="row">
                        <div class="col-6 d-flex align-items-center">
                            <h6 class="mb-0 fw-bold text-primary">Nómina:</h6>
                        </div>
                        <div class="col-6 d-flex justify-content-end">
                            <a href="#"
                                class="text-success icon-hover d-flex align-items-center"
                                data-bs-toggle="modal"
                                data-bs-target="#modal_agregar_nomina">
                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                <span>Agregar</span>
                            </a>
                        </div>
                    </div>
                </div>
                <hr>
                <?php include_once('../vista/TALENTO_HUMANO/PERSONAS/MENU/th_persona_nomina.php'); ?>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {

        cargar_datos_nomina(<?= $id_persona ?>);

        cargar_selects_nomina();

    });

    function cargar_selects_nomina() {
        url_nominaC = '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_nominaC.php?buscar=true';
        cargar_select2_url('ddl_nomina', url_nominaC, '', '#modal_agregar_nomina');
    }

    function cargar_datos_nomina(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/th_per_nominaC.php?listar=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#pnl_nomina').html(response);
            }
        });
    }

    function cargar_datos_modal_nomina(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/th_per_nominaC.php?listar_modal=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#ddl_nomina').append($('<option>', {
                    value: response[0].id_nomina,
                    text: response[0].nomina_nombre,
                    selected: true
                }));
                $('#txt_remuneracion').val(response[0].th_per_nom_remuneracion);
                $('#txt_fecha_ini').val(response[0].th_per_nom_fecha_ini);
                $('#txt_fecha_fin').val(response[0].th_per_nom_fecha_fin);
                $('#txt_nomina_id').val(response[0]._id);
            }
        });
    }


    function insertar_editar_nomina() {
        var ddl_nomina = $('#ddl_nomina').val();
        var txt_remuneracion = $('#txt_remuneracion').val();
        var txt_fecha_ini = $('#txt_fecha_ini').val();
        var txt_fecha_fin = $('#txt_fecha_fin').val();
        var per_id = '<?= $id_persona ?>';
        var txt_nomina_id = $('#txt_nomina_id').val();

        var parametros_nomina = {
            'per_id': per_id,
            'ddl_nomina': ddl_nomina,
            'txt_remuneracion': txt_remuneracion,
            'txt_fecha_ini': txt_fecha_ini,
            'txt_fecha_fin': txt_fecha_fin,
            '_id': txt_nomina_id,
        }

        if ($("#form_nomina").valid()) {
            insertar_nomina(parametros_nomina);
        }
    }

    function insertar_nomina(parametros) {
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/TALENTO_HUMANO/th_per_nominaC.php?insertar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operación realizada con éxito.', 'success');
                    $('#modal_agregar_nomina').modal('hide');
                    cargar_datos_nomina(<?= $id_persona ?>);
                    limpiar_campos_nomina_modal();
                } else {
                    Swal.fire('', 'Operación fallida', 'warning');
                }
            }
        });
    }

    function abrir_modal_nomina(id) {
        if (id === '') {
            limpiar_campos_nomina_modal();
            $('#modal_agregar_nomina').modal('show');
            $('#lbl_titulo_nomina').html('Agregar Nómina');
            $('#btn_guardar_nomina').html('<i class="bx bx-save"></i> Agregar');
            $('#btn_eliminar_nomina').hide();
        } else {
            cargar_datos_modal_nomina(id);
            $('#modal_agregar_nomina').modal('show');
            $('#lbl_titulo_nomina').html('Editar Nómina');
            $('#btn_guardar_nomina').html('<i class="bx bx-save"></i> Editar');
            $('#btn_eliminar_nomina').show();
        }
    }

    function delete_datos_nomina() {
        id = $('#txt_nomina_id').val();
        Swal.fire({
            title: '¿Eliminar Registro?',
            text: "¿Esta seguro de eliminar este registro?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value) {
                eliminar_nomina(id);
            }
        })
    }

    function eliminar_nomina(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/th_per_nominaC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success');
                    $('#modal_agregar_nomina').modal('hide');
                    cargar_datos_nomina(<?= $id_persona ?>);
                    limpiar_campos_nomina_modal();
                }
            }
        });
    }

    function limpiar_campos_nomina_modal() {
        $('#form_nomina').validate().resetForm();
        $('.form-control, .form-select').removeClass('is-valid is-invalid');
        $('#ddl_nomina').val('');
        $('#txt_remuneracion').val('');
        $('#txt_fecha_ini').val('');
        $('#txt_fecha_fin').val('');
        $('#txt_nomina_id').val('');
        $('#cbx_fecha_fin_nomina').prop('checked', false);
        $('#txt_fecha_fin').prop('disabled', false);
        $('#lbl_titulo_nomina').html('Agregar Nómina');
        $('#btn_guardar_nomina').html('<i class="bx bx-save"></i> Agregar');
        $('#btn_eliminar_nomina').hide();
    }

    function validar_fechas_nomina() {
        var fecha_inicio = $('#txt_fecha_ini').val();
        var fecha_final = $('#txt_fecha_fin').val();

        if (!fecha_inicio || !fecha_final) {
            return true;
        }
        if (Date.parse(fecha_final) < Date.parse(fecha_inicio)) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "La fecha de fin no puede ser menor a la fecha de inicio.",
            });
            $('.form-control').removeClass('is-valid is-invalid');
            $('#txt_fecha_fin').val('');
            $('#cbx_fecha_fin_nomina').prop('checked', false);
            return false;
        }

        return true;
    }

    function checkbox_actualidad_nomina() {
        if ($('#cbx_fecha_fin_nomina').is(':checked')) {
            var hoy = new Date();
            var dia = String(hoy.getDate()).padStart(2, '0');
            var mes = String(hoy.getMonth() + 1).padStart(2, '0');
            var year = hoy.getFullYear();

            var fecha_actual = year + '-' + mes + '-' + dia;
            $('#txt_fecha_fin').val(fecha_actual);
            $('#txt_fecha_fin').prop('disabled', true);
            $('#txt_fecha_fin').rules("remove", "required");
            $('#txt_fecha_fin').addClass('is-valid');
            $('#txt_fecha_fin').removeClass('is-invalid');

        } else {
            if ($('#txt_fecha_fin').prop('disabled')) {
                $('#txt_fecha_fin').val('');
            }

            $('#txt_fecha_fin').prop('disabled', false);
            $('#txt_fecha_fin').removeClass('is-valid');
        }

        validar_fechas_nomina();
    }
</script>

<div id="pnl_nomina">
</div>

<div class="modal" id="modal_agregar_nomina" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5><small class="text-body-secondary fw-bold" id="lbl_titulo_nomina">Agregar Nómina</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_campos_nomina_modal()"></button>
            </div>

            <form id="form_nomina">
                <input type="hidden" name="txt_nomina_id" id="txt_nomina_id">
                <div class="modal-body">

                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="ddl_nomina" class="form-label form-label-sm">Nómina:</label>
                            <select class="form-select form-select-sm" id="ddl_nomina" name="ddl_nomina" required>
                                <option selected disabled value="">-- Seleccione una Nómina --</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_remuneracion" class="form-label form-label-sm">Remuneración:</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control form-control-sm" name="txt_remuneracion" id="txt_remuneracion" step="0.01" min="0" placeholder="0.00" required>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-col">
                        <div class="col-md-6">
                            <label for="txt_fecha_ini" class="form-label form-label-sm">Fecha de inicio:</label>
                            <input type="date" class="form-control form-control-sm" name="txt_fecha_ini" id="txt_fecha_ini" onchange="validar_fechas_nomina();" required>
                        </div>

                        <div class="col-md-6">
                            <label for="txt_fecha_fin" class="form-label form-label-sm">Fecha de fin:</label>
                            <div class="input-group input-group-sm">
                                <input type="date" class="form-control form-control-sm" name="txt_fecha_fin" id="txt_fecha_fin" onchange="validar_fechas_nomina();">
                                <div class="input-group-text">
                                    <input class="form-check-input mt-0" type="checkbox" id="cbx_fecha_fin_nomina" onchange="checkbox_actualidad_nomina();" title="Marcar si es nómina actual">
                                    <label class="form-check-label ms-1" for="cbx_fecha_fin_nomina" style="font-size: 0.8rem;">Actual</label>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-success btn-sm px-4 m-1" id="btn_guardar_nomina" onclick="if(validar_fechas_nomina()) { insertar_editar_nomina(); }"><i class="bx bx-save"></i> Agregar</button>
                    <button type="button" style="display: none;" class="btn btn-danger btn-sm px-4 m-1" id="btn_eliminar_nomina" onclick="delete_datos_nomina();"><i class="bx bx-trash"></i> Eliminar</button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        agregar_asterisco_campo_obligatorio('ddl_nomina');
        agregar_asterisco_campo_obligatorio('txt_remuneracion');
        agregar_asterisco_campo_obligatorio('txt_fecha_ini');

        //Validación Nómina
        $("#form_nomina").validate({
            rules: {
                ddl_nomina: {
                    required: true,
                },
                txt_remuneracion: {
                    required: true,
                    number: true,
                    min: 0
                },
                txt_fecha_ini: {
                    required: true,
                },
            },
            messages: {
                ddl_nomina: {
                    required: "Por favor seleccione una nómina",
                },
                txt_remuneracion: {
                    required: "Por favor ingrese la remuneración",
                    number: "Por favor ingrese un valor numérico válido",
                    min: "La remuneración debe ser mayor o igual a 0"
                },
                txt_fecha_ini: {
                    required: "Por favor ingrese la fecha de inicio",
                },
            },

            highlight: function(element) {
                $(element).addClass('is-invalid');
                $(element).removeClass('is-valid');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid');
                $(element).addClass('is-valid');
            }
        });
    })
</script>