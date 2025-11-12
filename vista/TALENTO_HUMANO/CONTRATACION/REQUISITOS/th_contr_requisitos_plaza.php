<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);
$_id = '';

if (isset($_GET['_id'])) {
    $_id = $_GET['_id'];
}

?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<script type="text/javascript">
$(document).ready(function () {

    // Si viene _id cargamos requisito para editar
    <?php if ($_id != '') { ?>
        cargar_plaza(<?= $_id ?>);
        cargar_tabla_requisitos(<?= $_id ?>);
    <?php } ?>


   function cargar_tabla_requisitos(id_plaza) {
    // Asegurarnos de que id_plaza no sea undefined
    id_plaza = id_plaza || '';

    // Si ya existe el DataTable, lo destruimos para evitar duplicados
    if ($.fn.dataTable.isDataTable('#tbl_requisitos')) {
        $('#tbl_requisitos').DataTable().clear().destroy();
        $('#tbl_requisitos').empty(); // opcional: limpia el tbody
    }

    tbl_requisitos = $('#tbl_requisitos').DataTable($.extend({}, configuracion_datatable('Tipo', 'Descripcion'), {
        responsive: true,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        },
        ajax: {
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_requisitosC.php?listar=true',
            type: 'POST',               // importante: usar POST si tu backend espera POST
            data: function (d) {
                d.id = id_plaza;       // enviamos el id de la plaza al servidor
            },
            dataSrc: ''                 // una sola vez
        },
        columns: [
           {
                data: 'tipo',
                render: function(data, type, item) {
                    return `
                        <a href="#" onclick="abrir_modal_requisitos(${item._id}); return false;">
                            <u>${data}</u>
                        </a>
                    `;
                }
            },
            { data: 'descripcion' },
            { data: 'ponderacion' },
            {
                data: 'obligatorio',
                render: function(data, type, item) {
                    var val = (data === 1 || data === '1' || data === true || data === 'true');
                    return val
                        ? '<i class="bx bx-check-circle text-danger" title="Obligatorio"></i>'
                        : '<i class="bx bx-circle text-secondary" title="Opcional"></i>';
                },
                className: 'text-center'
            }
        ],
        order: [
            [1, 'asc']
        ]
    }));
}


    // helper: cargar select2 de tipos (estático)
    function cargar_tipos_req() {
        var tipos = ['Formación', 'Experiencia', 'Certificado', 'Habilidad', 'Otro'];
        tipos.forEach(function(t) {
            $('#ddl_th_req_tipo').append($('<option>', { value: t, text: t }));
        });
    }
    cargar_tipos_req();

    // Cargar requisito para editar
    


    function cargar_plaza(id) {
            $.ajax({
                data: { id: id },
                // <-- Cambia esta URL por la de tu controlador
                url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_plazasC.php?listar=true',
                type: 'post',
                dataType: 'json',
                success: function(response) {
                    if (!response || !response[0]) return;
                    var r = response[0];
                    $('#txt_th_pla_titulo').val(r.th_pla_titulo);
                    $('#txt_th_pla_id').val(r._id);
                    
                },
                error: function(err) {
                    console.error(err);
                    alert('Error al cargar la plaza (revisar consola).');
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
            data: { parametros: parametros },
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_requisitosC.php?insertar_editar=true',
            type: 'post',
            dataType: 'json',
            success: function(res) {
                if (res == 1) {
                    Swal.fire('', 'Requisito creado con éxito.', 'success').then(function() {
                        // volver a la lista de requisitos de la plaza (o recargar)
                        window.location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_requisitos_plaza&_id=' + (parametros.th_pla_id || '');
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
            data: { parametros: parametros },
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_requisitosC.php?insertar_editar=true',
            type: 'post',
            dataType: 'json',
            success: function(res) {
                if (res == 1) {
                    Swal.fire('', 'Requisito actualizado con éxito.', 'success').then(function() {
                        window.location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_requisitos_plaza&_id=' + (parametros.th_pla_id || '');
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
        if (!id) { Swal.fire('', 'ID no encontrado para eliminar', 'warning'); return; }

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
                    data: { _id: id },
                    url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_requisitosC.php?eliminar=true',
                    type: 'post',
                    dataType: 'json',
                    success: function(res) {
                        if (res == 1) {
                            Swal.fire('Eliminado!', 'Requisito eliminado.', 'success').then(function() {
                                // volver a la lista de requisitos de la plaza
                                var plaza = $('#txt_th_pla_id').val() || $('#ddl_th_pla_id').val() || '';
                                window.location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_requisitos_plaza&_id=' + id;
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
        if (!params.th_req_tipo) { Swal.fire('', 'Seleccione el tipo de requisito.', 'warning'); return; }
        if (!params.th_req_descripcion) { Swal.fire('', 'Ingrese la descripción del requisito.', 'warning'); return; }
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

        if(id_requisito){
            cargar_requisito(id_requisito);
            $('#pnl_crear').hide();
            $('#pnl_actualizar').show();
        }else{
            $('#pnl_crear').show();
            $('#pnl_actualizar').hide();
        }
       
    }
    function cargar_requisito(id) {
    $.ajax({
        data: { id: id },
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
                        $('<option>', { value: r.tipo, text: r.tipo })
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

<!-- HTML -->
<div class="page-wrapper">
    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Requisitos</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Registrar Requisito</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-10 mx-auto">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body p-4">
                       <div class="card-title d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bx bx-check-circle font-22 text-primary"></i>
                                <h5 class="mb-0 text-primary">Requisitos</h5>
                            </div>

                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="btn btn-success btn-sm" onclick="abrir_modal_requisitos()">
                                    <i class="bx bx-plus me-1"></i> Nuevo
                                </button>
                                <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_plazas" class="btn btn-outline-dark btn-sm">
                                    <i class="bx bx-arrow-back"></i> Regresar
                                </a>
                            </div>
                        </div>

                        <hr>
                         <!-- Si viene plaza por defecto, mostrar título readonly -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Plaza asociada</label>
                                <input type="text" id="txt_th_pla_titulo" class="form-control" readonly value="" placeholder="Plaza asociada..." />
                                <div class="form-text">Si desea cambiar la plaza, recargue la página sin plaza_id en la URL.</div>
                            </div>

                         <section class="content pt-2">
                            <div class="container-fluid">
                                <div class="table-responsive">
                                    <table class="table table-striped responsive " id="tbl_requisitos" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Tipo</th>
                                                <th>Descripción</th>
                                                <th>Ponderación</th>
                                                <th>Obligatorio</th>
                                            </tr>
                                        </thead>
                                        <tbody class="">

                                        </tbody>
                                    </table>
                                </div>
                            </div><!-- /.container-fluid -->
                        </section>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>


<div class="modal" id="modal_requisitos" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header bg-light">
                <h5 class="modal-title">
                    <i class="bx bx-list-check me-2"></i>
                    <?= $_id == '' ? 'Agregar Requisito' : 'Editar Requisito' ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body p-4">

               <form id="form_requisito">

                    <input type="hidden" id="txt_th_req_id" name="txt_th_req_id" value="" />
                    <input type="hidden" id="txt_th_pla_id" name="txt_th_pla_id" value="" />
                    
                    <!-- Sección: Tipo de Requisito -->
                    <div class="mb-4">
                        <label for="ddl_th_req_tipo" class="form-label fw-bold">
                            <i class="bx bx-category me-1"></i>Tipo de Requisito
                        </label>
                        <select id="ddl_th_req_tipo" class="form-select select2-validation" name="ddl_th_req_tipo" required>
                            <option value="" selected hidden>-- Seleccione el tipo de requisito --</option>
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
                                        <input type="checkbox" id="chk_th_req_obligatorio" class="form-check-input" style="width: 3em; height: 1.5em;" />
                                        <label class="form-check-label ms-2" for="chk_th_req_obligatorio">
                                            <strong>Es obligatorio</strong>
                                            <small class="text-muted d-block">El candidato debe cumplir este requisito</small>
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
                                        <input type="number" id="txt_th_req_ponderacion" class="form-control" min="0" max="100" value="0" />
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <small class="text-muted">Peso del requisito en la evaluación (0-100)</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sección: Descripción -->
                    <div class="mb-4">
                        <label for="txt_th_req_descripcion" class="form-label fw-bold">
                            <i class="bx bx-detail me-1"></i>Descripción del Requisito
                        </label>
                        <textarea id="txt_th_req_descripcion" class="form-control" rows="5" placeholder="Describa detalladamente el requisito..." required></textarea>
                        <small class="text-muted">Especifique claramente qué debe cumplir el candidato</small>
                    </div>

                    <!-- Botones de acción -->
                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            <i class="bx bx-x me-1"></i>Cancelar
                        </button>
                        <div id="pnl_crear" style ="display:none">   
                            <button type="button" class="btn btn-success" id="btn_guardar_req">
                                <i class="bx bx-save me-1"></i>Crear Requisito
                            </button>
                        </div>
                         <div id="pnl_actualizar" style ="display:none">   
                            <button type="button" class="btn btn-danger" id="btn_eliminar_req">
                                <i class="bx bx-trash me-1"></i>Eliminar
                            </button>
                            <button type="button" class="btn btn-primary" id="btn_editar_req">
                                <i class="bx bx-check me-1"></i>Actualizar Requisito
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
<script>
// Solución para el error de aria-hidden con Bootstrap modals
$(document).ready(function() {
    
    // ... tu código existente ...

    // 🔧 FIX: Solución para el error aria-hidden
    $('#modal_requisitos').on('show.bs.modal', function() {
        // Remover aria-hidden del wrapper antes de abrir el modal
        $('.wrapper').removeAttr('aria-hidden');
    });

    $('#modal_requisitos').on('shown.bs.modal', function() {
        // Después de mostrar el modal, enfocar el primer campo
        setTimeout(function() {
            $('#ddl_th_req_tipo').focus();
        }, 100);
    });

    $('#modal_requisitos').on('hidden.bs.modal', function() {
        // Limpiar el formulario al cerrar
        $('#form_requisito')[0].reset();
        $('#txt_th_req_id').val('');
        $('#ddl_th_req_tipo').val('').trigger('change');
    });

    // Prevenir que el wrapper obtenga aria-hidden
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'aria-hidden') {
                const wrapper = document.querySelector('.wrapper');
                if (wrapper && wrapper.getAttribute('aria-hidden') === 'true') {
                    // Si hay un modal abierto, remover aria-hidden del wrapper
                    if (document.querySelector('.modal.show')) {
                        wrapper.removeAttribute('aria-hidden');
                    }
                }
            }
        });
    });

    // Observar cambios en el wrapper
    const wrapper = document.querySelector('.wrapper');
    if (wrapper) {
        observer.observe(wrapper, {
            attributes: true,
            attributeFilter: ['aria-hidden']
        });
    }

}); // Fin document.ready
</script>