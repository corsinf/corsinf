<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);

$_id = '';

if (isset($_GET['_id_per'])) {
    $_id = $_GET['_id_per'];
     $_id_postulante = $_GET['_id'];
}
?>
<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<script type="text/javascript">
$(document).ready(function() {
    dispositivos();

    <?php if (isset($_GET['_id_per'])) { ?>
    cargar_datos_persona(<?= $_id ?>);
    cargar_departamento(<?= $_id ?>);
    cargar_niveles_academicos(<?= $_id ?>);
    <?php } ?>

    cargar_selects2();
    inicializar_eventos_formulario();
});


var tbl_nivel_academico = null;

function cargar_niveles_academicos(id_persona) {

    if (!id_persona) {
        console.warn("⚠ ID_PERSONA está vacío, no se carga la tabla.");
        return;
    }

    // destruir si ya existe
    if ($.fn.dataTable.isDataTable('#tbl_nivel_academico')) {
        $('#tbl_nivel_academico').DataTable().clear().destroy();
        $('#tbl_nivel_academico').empty();
    }

    tbl_nivel_academico = $('#tbl_nivel_academico').DataTable($.extend({}, configuracion_datatable(
        'Tipo de nivel', 'Nivel Académico', 'Titulo'
    ), {
        responsive: true,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        },
        ajax: {
            url: '../controlador/TALENTO_HUMANO/th_persona_nivel_academicoC.php?listar_persona_nivel_academico=true',
            type: 'POST',
            data: function(d) {
                d.id = id_persona;
            },
            dataSrc: '' // ← IMPORTANTE: controlador debe devolver array JSON
        },
        columns: [{
                data: null,
                render: function(data, type, item) {

                    return `
            <a href="#" onclick="abrir_modal_nivel_academico(${item._id}); return false;">
                <u>${item.tipo_nivel}</u>
            </a>
        `;
                }
            }, {
                data: 'nivel_academico'
            },
            {
                data: 'titulo',
                className: 'text-center',
                render: d => d ? (d.length > 40 ? d.substring(0, 40) + '...' : d) : ''
            },
            {
                data: 'registro_senescyt',
                className: 'text-center'
            }
        ],
        order: [
            [0, 'asc']
        ]
    }));
}



/**
 * Inicializar eventos del formulario
 */
function inicializar_eventos_formulario() {
    // Calcular edad al cambiar fecha de nacimiento
    $('#txt_fecha_nacimiento').on('change', function() {
        calcular_edad('txt_edad', $(this).val());
    });


    // Cascada de select ubicación
    $('#ddl_provincias').on('change', function() {
        const provinciaId = $(this).val();
        if (provinciaId) {
            cargar_select2_con_filtro('ddl_ciudad', '../controlador/GENERAL/th_ciudadC.php?listar=true',
                'th_prov_id', provinciaId);
        }
        $('#ddl_parroquia').html('<option value="">-- Selecciona una Parroquia --</option>');
    });

    $('#ddl_ciudad').on('change', function() {
        const ciudadId = $(this).val();
        if (ciudadId) {
            cargar_select2_con_filtro('ddl_parroquia', '../controlador/GENERAL/th_parroquiasC.php?listar=true',
                'th_ciu_id', ciudadId);
        }
    });

    // Mostrar/ocultar campos de discapacidad
    $('#ddl_tipo_discapacidad').on('change', function() {
        if ($(this).val() === 'Ninguna' || $(this).val() === '') {
            $('#txt_porcentaje_discapacidad').val('').prop('disabled', true);
            $('#ddl_escala_discapacidad').val('').prop('disabled', true);
        } else {
            $('#txt_porcentaje_discapacidad').prop('disabled', false);
            $('#ddl_escala_discapacidad').prop('disabled', false);
        }
    });

    // Limpiar error de cédula al escribir
    $('#txt_cedula_persona').on('input', function() {
        $(this).removeClass('is-invalid');
        $('#error_txt_cedula').text('');
    });
}

/**
 * Cargar datos de persona desde la BD
 */
function cargar_datos_persona(id) {
    $.ajax({
        url: '../controlador/GENERAL/th_personasC.php?listar=true',
        type: 'post',
        data: {
            id: id
        },
        dataType: 'json',
        beforeSend: function() {
            Swal.fire({
                title: 'Cargando...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        },
        success: function(response) {
            Swal.close();

            if (!response || response.length === 0) {
                Swal.fire('Error', 'No se encontraron datos para la persona', 'error');
                return;
            }

            const persona = response[0];

            // Cargar datos en el formulario
            cargar_formulario_persona(persona);

            // Cargar datos de visualización
            cargar_vista_persona(persona);


            // Cargar cargo
            if (persona.th_car_id && persona.th_car_id != 0) {
                $('#ddl_cargo').append($('<option>', {
                    value: persona.th_car_id,
                    text: persona.th_car_nombre || 'Cargo',
                    selected: true
                }));
            }

            // Datos para compatibilidad con postulantes
            $('input[name="txt_postulante_id"]').val(<?=$_id_postulante ?? 0?>);
            $('input[name="txt_postulante_cedula"]').val(persona.th_per_cedula);

            if (persona.th_per_id_comunidad) {
                window.idPostulanteAsociado = persona.th_per_id_comunidad;
            }
        },
        error: function(xhr, status, error) {
            Swal.close();
            console.error('Error cargando datos:', error);
            Swal.fire('Error', 'No se pudieron cargar los datos de la persona', 'error');
        }
    });
}

/**
 * Cargar datos en formulario
 */
function cargar_formulario_persona(persona) {
    $('#txt_primer_nombre').val(persona.primer_nombre || persona.th_per_primer_nombre || '');
    $('#txt_segundo_nombre').val(persona.segundo_nombre || persona.th_per_segundo_nombre || '');
    $('#txt_primer_apellido').val(persona.primer_apellido || persona.th_per_primer_apellido || '');
    $('#txt_segundo_apellido').val(persona.segundo_apellido || persona.th_per_segundo_apellido || '');
    $('#txt_fecha_nacimiento').val(persona.fecha_nacimiento || persona.th_per_fecha_nacimiento || '');
    $('#ddl_nacionalidad').val(persona.nacionalidad || persona.th_per_nacionalidad || '');
    $('#txt_cedula_persona').val(persona.cedula || persona.th_per_cedula || '');
    $('#ddl_estado_civil').val(persona.estado_civil || persona.th_per_estado_civil || '');
    $('#ddl_sexo').val(persona.sexo || persona.th_per_sexo || '');
    $('#txt_telefono_1').val(persona.telefono_1 || persona.th_per_telefono_1 || '');
    $('#txt_telefono_2').val(persona.telefono_2 || persona.th_per_telefono_2 || '');
    $('#txt_correo').val(persona.correo || persona.th_per_correo || '');
    $('#txt_codigo_postal').val(persona.postal || persona.th_per_postal || '');
    $('#txt_direccion').val(persona.direccion || persona.th_per_direccion || '');
    $('#txt_observaciones').val(persona.observaciones || persona.th_per_observaciones || '');
    $('#ddl_tipo_sangre').val(persona.tipo_sangre || persona.th_per_tipo_sangre || '');

    // campos nuevos (usa el alias sin prefijo si existe, sino con prefijo)
    $('#ddl_etnia').val(persona.etnia || persona.th_per_etnia || '');
    $('#ddl_orientacion').val(persona.orientacion || persona.th_per_orientacion || '');
    $('#ddl_religion').val(persona.religion || persona.th_per_religion || '');
    $('#ddl_tipo_discapacidad').val(persona.tipo_discapacidad || persona.th_per_tipo_discapacidad || '');
    $('#txt_porcentaje_discapacidad').val(persona.porcentaje_discapacidad || persona.th_per_porcentaje_discapacidad ||
        '');
    $('#ddl_escala_discapacidad').val(persona.escala_discapacidad || persona.th_per_escala_discapacidad || '');

    $('#txt_nivel_academico_general').val(persona.nivel_academico_general || persona.th_per_nivel_academico_general ||
        '');
    $('#txt_titulo_maximo').val(persona.titulo_maximo || persona.th_per_titulo_maximo || '');
    $('#txt_senescyt_maximo').val(persona.numero_registro_senescyt_maximo || persona.th_per_senescyt_maximo || '');

    $('#ddl_clase_auto').val(persona.clase_auto || persona.th_per_clase_auto || '');
    $('#txt_placa_original').val(persona.placa_original || persona.th_per_placa_original || '');
    $('#txt_placa_sintesis').val(persona.placa_sintesis || persona.th_per_placa_sintesis || '');

    $('#txt_comision_asuntos_sociales').val(persona.comision_asuntos_sociales || persona
        .th_per_comision_asuntos_sociales || '');
    $('#txt_remuneracion').val(persona.remuneracion || persona.th_per_remuneracion || '');
    // fecha_ingreso: si viene con timestamp, tomar solo la parte fecha para el input date
    var fechaIngreso = persona.fecha_ingreso || persona.th_per_fecha_ingreso || '';
    $('#txt_fecha_ingreso').val(fechaIngreso ? (fechaIngreso.split(' ')[0] || fechaIngreso) : '');
    $('#txt_anios_trabajo').val(persona.anios_trabajo || persona.th_per_anios_trabajo || '');
    $('#txt_seccion').val(persona.seccion || persona.th_per_seccion || '');


    $('#img_persona_inf').attr('src', persona.foto_url + '?' + Math.random());
    $('#img_persona_form').attr('src', persona.foto_url + '?' + Math.random());

    // cargo: carga select con nombre si viene (usa id para seleccionar)
    if (persona.th_car_id || persona.th_car_id === 0) {
        $('#ddl_cargo').append($('<option>', {
            value: persona.th_car_id,
            text: persona.th_car_nombre || '',
            selected: true
        }));
    }

    url_provinciaC = '../controlador/GENERAL/th_provinciasC.php?listar=true';
    cargar_select2_con_id('ddl_provincias', url_provinciaC, persona.id_provincia, 'th_prov_nombre');

    url_ciudadC = '../controlador/GENERAL/th_ciudadC.php?listar=true';
    cargar_select2_con_id('ddl_ciudad', url_ciudadC, persona.id_ciudad, 'th_ciu_nombre');

    url_parroquiaC = '../controlador/GENERAL/th_parroquiasC.php?listar=true';
    cargar_select2_con_id('ddl_parroquia', url_parroquiaC, persona.id_parroquia, 'th_parr_nombre');


    // Si tienes nombres y quieres mostrarlos en la vista
    var nombres = persona.nombres_completos || persona.th_per_nombres_completos ||
        `${persona.primer_apellido || ''} ${persona.segundo_apellido || ''} ${persona.primer_nombre || ''} ${persona.segundo_nombre || ''}`
        .trim();
    $('#txt_nombres_completos_v').html(nombres);

    // calcular edad si hay fecha
    var fechaNac = persona.fecha_nacimiento || persona.th_per_fecha_nacimiento || '';
    if (fechaNac) {
        calcular_edad('txt_edad', fechaNac);
    }

}


/**
 * Cargar datos de visualización (panel izquierdo)
 */
function cargar_vista_persona(persona) {
    const nombres_completos =
        `${persona.th_per_primer_apellido || ''} ${persona.th_per_segundo_apellido || ''} ${persona.th_per_primer_nombre || ''} ${persona.th_per_segundo_nombre || ''}`
        .trim();

    $('#txt_nombres_completos_v').html(persona.nombres_completos);
    $('#txt_fecha_nacimiento_v').html(persona.fecha_nacimiento || 'N/A');
    $('#txt_nacionalidad_v').html(persona.nacionalidad || 'N/A');
    $('#txt_estado_civil_v').html(persona.estado_civil || 'N/A');
    $('#txt_numero_cedula_v').html(persona.cedula || 'N/A');
    $('#txt_telefono_1_v').html(persona.telefono_1 || 'N/A');
    $('#txt_correo_v').html(persona.correo || 'N/A');
}


/**
 * Obtener parámetros del formulario
 */
function parametros_persona() {
    return {
        // ID para edición
        '_id': <?= $_id ?>,

        // Datos personales
        'txt_primer_nombre': $('#txt_primer_nombre').val().trim(),
        'txt_segundo_nombre': $('#txt_segundo_nombre').val().trim(),
        'txt_primer_apellido': $('#txt_primer_apellido').val().trim(),
        'txt_segundo_apellido': $('#txt_segundo_apellido').val().trim(),
        'txt_cedula_persona': $('#txt_cedula_persona').val().trim(),
        'ddl_sexo': $('#ddl_sexo').val(),
        'txt_fecha_nacimiento': $('#txt_fecha_nacimiento').val(),
        'ddl_nacionalidad': $('#ddl_nacionalidad').val(),
        'ddl_estado_civil': $('#ddl_estado_civil').val(),

        // Datos de contacto
        'txt_telefono_1': $('#txt_telefono_1').val().trim(),
        'txt_telefono_2': $('#txt_telefono_2').val().trim(),
        'txt_correo': $('#txt_correo').val().trim(),

        // Ubicación
        'txt_direccion': $('#txt_direccion').val().trim(),
        'txt_codigo_postal': $('#txt_codigo_postal').val().trim(),
        'ddl_provincias': $('#ddl_provincias').val(),
        'ddl_ciudad': $('#ddl_ciudad').val(),
        'ddl_parroquia': $('#ddl_parroquia').val(),

        // Información adicional
        'ddl_tipo_sangre': $('#ddl_tipo_sangre').val(),
        'ddl_etnia': $('#ddl_etnia').val(),
        'ddl_religion': $('#ddl_religion').val(),
        'ddl_orientacion': $('#ddl_orientacion').val(),
        'ddl_cargo': $('#ddl_cargo').val(),

        // Información laboral
        'txt_fecha_ingreso': $('#txt_fecha_ingreso').val(),
        'txt_anios_trabajo': $('#txt_anios_trabajo').val(),
        'txt_seccion': $('#txt_seccion').val(),
        'txt_remuneracion': $('#txt_remuneracion').val(),
        'txt_comision_asuntos_sociales': $('#txt_comision_asuntos_sociales').val().trim(),

        // Información sobre discapacidad
        'ddl_tipo_discapacidad': $('#ddl_tipo_discapacidad').val(),
        'txt_porcentaje_discapacidad': $('#txt_porcentaje_discapacidad').val(),
        'ddl_escala_discapacidad': $('#ddl_escala_discapacidad').val(),

        // Información de vehículo
        'ddl_clase_auto': $('#ddl_clase_auto').val(),
        'txt_placa_original': $('#txt_placa_original').val().trim(),
        'txt_placa_sintesis': $('#txt_placa_sintesis').val().trim(),

        // Observaciones
        'txt_observaciones': $('#txt_observaciones').val().trim()
    };
}
/**
 * Insertar o editar información personal
 */
function insertar_editar_informacion_personal() {
    if (!$("#form_informacion_personal").valid()) {
        Swal.fire('Advertencia', 'Por favor complete todos los campos obligatorios', 'warning');
        return;
    }

    const parametros = parametros_persona();

    if (parametros.txt_correo && !validar_email(parametros.txt_correo)) {
        Swal.fire('Advertencia', 'El correo electrónico no es válido', 'warning');
        return;
    }

    $.ajax({
        data: {
            parametros: parametros
        },
        url: '../controlador/GENERAL/th_personasC.php?insertar=true',
        type: 'post',
        dataType: 'json',
        beforeSend: function() {
            Swal.fire({
                title: 'Guardando...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        },
        success: function(response) {
            if (response == 1) {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: 'Operación realizada con éxito',
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    $('#modal_informacion_personal').modal('hide');
                    // Recargar datos
                    <?php if (isset($_GET['_id_per'])) { ?>
                    cargar_datos_persona(<?= $_id ?>);
                    <?php } ?>
                });
            } else if (response == -2) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Advertencia',
                    text: 'Ya existe una persona registrada con esta cédula'
                });
                $('#txt_cedula_persona').addClass('is-invalid');
                $('#error_txt_cedula').text('La cédula ya está en uso.');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Operación fallida. Intente nuevamente.'
                });
            }
        },
        error: function(xhr, status, error) {
            Swal.fire('Error', 'Error en la conexión: ' + error, 'error');
        }
    });
}

/**
 * Eliminar persona
 */
function delete_datos_persona() {
    var id = '<?= $_id; ?>';
    Swal.fire({
        title: '¿Eliminar Registro?',
        text: "Esta acción no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                data: {
                    id: id
                },
                url: '../controlador/GENERAL/th_personasC.php?eliminar=true',
                type: 'post',
                dataType: 'json',
                success: function(response) {
                    if (response == 1) {
                        Swal.fire('Eliminado', 'Registro eliminado correctamente', 'success').then(
                            function() {
                                window.location.href =
                                    '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_personas';
                            });
                    }
                }
            });
        }
    });
}

/**
 * Calcular edad
 */
function calcular_edad(campo_destino, fecha_nacimiento) {
    if (!fecha_nacimiento) {
        $('#' + campo_destino).val('');
        return;
    }

    const hoy = new Date();
    const nacimiento = new Date(fecha_nacimiento);
    let edad = hoy.getFullYear() - nacimiento.getFullYear();
    const mes = hoy.getMonth() - nacimiento.getMonth();

    if (mes < 0 || (mes === 0 && hoy.getDate() < nacimiento.getDate())) {
        edad--;
    }

    $('#' + campo_destino).val(edad + ' años');
}

/**
 * Validar cédula ecuatoriana
 */


/**
 * Validar email
 */
function validar_email(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

/**
 * Cargar select2 con filtro
 */
function cargar_select2_con_filtro(id_select, url, campo_filtro, valor_filtro) {
    $.ajax({
        url: url,
        type: 'post',
        data: {
            [campo_filtro]: valor_filtro
        },
        dataType: 'json',
        success: function(response) {
            $('#' + id_select).html('<option value="">-- Selecciona una opción --</option>');
            $.each(response, function(index, item) {
                $('#' + id_select).append($('<option>', {
                    value: item.id,
                    text: item.nombre
                }));
            });
        }
    });
}

/**
 * Cargar selects2 iniciales
 */
function cargar_selects2() {
    const url_departamentosC = '../controlador/TALENTO_HUMANO/th_departamentosC.php?buscar=true';
    cargar_select2_url('ddl_departamentos', url_departamentosC);

    const url_cargos = '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_cargosC.php?buscar=true';
    cargar_select2_url('ddl_cargo', url_cargos, '', '#modal_informacion_personal');

    const url_provincias = '../controlador/GENERAL/th_provinciasC.php?listar=true';
    cargar_select2_url('ddl_provincias', url_provincias, '', '#modal_informacion_personal');

    const url_ciudad = '../controlador/GENERAL/th_ciudadC.php?listar=true';
    cargar_select2_url('ddl_ciudad', url_ciudad, '', '#modal_informacion_personal');

    const url_parroquias = '../controlador/GENERAL/th_parroquiasC.php?listar=true';
    cargar_select2_url('ddl_parroquia', url_parroquias, '', '#modal_informacion_personal');

}

/**
 * Recargar imagen de perfil
 */
function recargar_imagen_persona(id) {
    $.ajax({
        url: '../controlador/GENERAL/th_personasC.php?listar=true',
        type: 'post',
        data: {
            id: id
        },
        dataType: 'json',
        success: function(response) {
            if (response && response[0]) {
                $('#img_persona_inf').attr('src', response[0].foto_url + '?' + Math.random());
                $('#img_persona_inf_modal').attr('src', response[0].foto_url + '?' + Math.random());
                $('#img_persona_form').attr('src', response[0].foto_url + '?' + Math.random());
            }
        }
    });
}

// Funciones auxiliares de departamento y dispositivos
function dispositivos() {
    $.ajax({
        url: '../controlador/TALENTO_HUMANO/th_dispositivosC.php?listar=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            let op = '<option value="">-- Seleccione --</option>';
            response.forEach(function(item) {
                op += '<option value="' + item._id + '">' + item.nombre + '</option>';
            });
            $('#ddl_dispositivos').html(op);
        },
        error: function(xhr, status, error) {
            console.error('Error cargando dispositivos:', error);
        }
    });
}

function cargar_departamento(id) {
    $.ajax({
        data: {
            id: id
        },
        url: '../controlador/TALENTO_HUMANO/th_personasC.php?listar_persona_departamento=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            if (response && response.length > 0) {
                $('#id_perdep').val(response[0]._id_perdep);

                $('#ddl_departamentos').append($('<option>', {
                    value: response[0].id_departamento,
                    text: response[0].nombre_departamento,
                    selected: true
                }));

                if (response[0].id_departamento == 0) {
                    $('#pnl_horarios_persona').hide();
                } else {
                    cargar_persona_horario(response[0].id_persona);
                    $('#pnl_horarios_persona').show();
                }
            }
        },
        error: function(xhr, status, error) {
            console.error('Error al cargar departamento:', error);
        }
    });
}

function insertar_persona_departamento() {
    const deptId = $('#ddl_departamentos').val();
    const perdepId = $('#id_perdep').val();

    if (!deptId) {
        Swal.fire('', 'Seleccione un departamento', 'warning');
        return;
    }

    const parametros = {
        '_id': perdepId || '',
        'id_persona': '<?= $_id ?>',
        'id_departamento': deptId,
        'txt_visitor': $('#txt_visitor').val() || ''
    };

    $.ajax({
        url: '../controlador/TALENTO_HUMANO/th_personas_departamentosC.php?insertar_editar_persona=true',
        type: 'post',
        dataType: 'json',
        data: {
            parametros: parametros
        },
        success: function(response) {
            if (response == 1) {
                Swal.fire('', 'Operación realizada con éxito.', 'success').then(() => {
                    location.reload();
                });
            } else if (response == -2) {
                Swal.fire('', 'Esta persona ya está asignada a este departamento', 'warning');
            } else {
                Swal.fire('', 'Error en la operación', 'error');
            }
        }
    });
}
</script>


<script>
$(document).ready(function() {
    function cerrarModalNivelAcademico() {
        let modal = document.getElementById('modal_nivel_academico');
        let instance = bootstrap.Modal.getInstance(modal);
        if (!instance) {
            instance = new bootstrap.Modal(modal);
        }
        instance.hide();
    }

});

function abrir_modal_nivel_academico() {

    var modal = new bootstrap.Modal(document.getElementById('modal_nivel_academico'));
    modal.show();
}


function insertar_editar_nivel_academico() {
    // Recolectar parámetros desde el formulario
    let parametros = {
        _id: $('#_id').val(),
        th_per_id: <?= isset($_id) ? $_id : '' ?>,
        pna_tipo_nivel: $('#pna_tipo_nivel').val(),
        pna_nivel_academico: $('#pna_nivel_academico').val(),
        pna_titulo: $('#pna_titulo').val(),
        pna_registro_senescyt: $('#pna_registro_senescyt').val(),
        chk_estado: $('#chk_estado').is(':checked') ? 1 : 0
    };

    // Validaciones simples
    if (!parametros.th_per_id) {
        Swal.fire('Atención', 'Ingresa el ID de la persona', 'warning');
        return;
    }
    if (!parametros.pna_titulo || !parametros.pna_nivel_academico) {
        Swal.fire('Atención', 'Completa el nivel y el título', 'warning');
        return;
    }

    $.ajax({
        url: '../controlador/TALENTO_HUMANO/th_persona_nivel_academicoC.php?insertar_editar=true',
        type: 'POST',
        dataType: 'json',
        data: {
            parametros: parametros
        },
        success: function(resp) {
            // El controlador devuelve 1, -1, -2 o el resultado de editar
            if (resp == 1) {
                Swal.fire('Éxito', 'Registro guardado', 'success');
                cerrarModalNivelAcademico();
                listar_niveles_academicos(parametros.th_per_id);
            } else if (resp == -2) {
                Swal.fire('Atención', 'Registro duplicado', 'warning');
            } else {
                Swal.fire('Error', 'No se pudo guardar', 'error');
                console.log('Resp server:', resp);
            }
        },
        error: function(xhr) {
            console.error(xhr.responseText);
            Swal.fire('Error', 'Error en el servidor', 'error');
        }
    });
}
</script>

<!-- Vista de la página -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Persona</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Información personal</li>
                    </ol>
                </nav>
            </div>
            <div class="row m-2">
                <div class="col-sm-12">
                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_personas"
                        class="btn btn-outline-dark btn-sm d-flex align-items-center"><i class="bx bx-arrow-back"></i>
                        Personas</a>
                </div>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="container-fluid">
            <div class="main-body">
                <div class="row d-flex justify-content-center">
                    <!-- Cards de la izquierda -->
                    <div class="col-xs-5 col-sm-5 col-md-5 col-lg-4 col-xxl-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="align-items-center">
                                    <!-- Cambiar Foto -->
                                    <div class="text-center">
                                        <?php include_once('../vista/TALENTO_HUMANO/PERSONAS/th_per_cambiar_foto.php'); ?>

                                        <div class="position-relative">

                                            <div class="widget-user-image text-center">
                                                <img class="rounded-circle p-1 bg-primary" src="" class="img-fluid"
                                                    id="img_persona_inf" alt="Imagen Perfil Persona" width="110"
                                                    height="110" />
                                            </div>

                                            <div>
                                                <a href="#" class="d-flex justify-content-center" data-bs-toggle="modal"
                                                    data-bs-target="#modal_agregar_cambiar_foto_persona"
                                                    onclick="abrir_modal_cambiar_foto_persona('<?= $_id ?>');">
                                                    <i class='bx bxs-camera bx-sm'></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Información Personal -->
                                    <div class="mt-3 bg-light rounded-3 p-3 shadow-sm">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div class="d-flex align-items-center">
                                                <i class="bx bx-info-circle fs-5 text-primary me-2"></i>
                                                <h6 class="fw-bold mb-0 text-primary">Información Personal</h6>
                                            </div>
                                            <a href="#" class="text-secondary" data-bs-toggle="modal"
                                                data-bs-target="#modal_informacion_personal">
                                                <i class="bx bx-pencil bx-sm"></i>
                                            </a>
                                        </div>

                                        <div class="d-flex flex-column gap-3">

                                            <div class="d-flex align-items-center border-bottom pb-2">
                                                <i class="bx bx-id-card text-primary fs-5 me-3"></i>
                                                <span id="txt_nombres_completos_v" class="fw-semibold text-dark"></span>
                                            </div>

                                            <div class="d-flex align-items-center border-bottom pb-2">
                                                <i class="bx bx-calendar text-primary fs-5 me-3"></i>
                                                <span id="txt_fecha_nacimiento_v" class="text-dark"></span>
                                            </div>

                                            <div class="d-flex align-items-center border-bottom pb-2">
                                                <i class="bx bx-flag text-primary fs-5 me-3"></i>
                                                <span id="txt_nacionalidad_v" class="text-dark"></span>
                                            </div>

                                            <div class="d-flex align-items-center border-bottom pb-2">
                                                <i class="bx bx-id-card text-primary fs-5 me-3"></i>
                                                <span id="txt_numero_cedula_v" class="text-dark"></span>
                                            </div>

                                            <div class="d-flex align-items-center border-bottom pb-2">
                                                <i class="bx bx-heart text-primary fs-5 me-3"></i>
                                                <span id="txt_estado_civil_v" class="text-dark"></span>
                                            </div>

                                            <div class="d-flex align-items-center border-bottom pb-2">
                                                <i class="bx bx-phone text-primary fs-5 me-3"></i>
                                                <span id="txt_telefono_1_v" class="text-dark"></span>
                                            </div>

                                            <div class="d-flex align-items-center">
                                                <i class="bx bx-envelope text-primary fs-5 me-3"></i>
                                                <span id="txt_correo_v" class="text-dark text-break"></span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <!-- Contacto de Emergencia -->
                            <div class="card-body">
                                <div class="align-items-center">
                                    <div class="mt-3">
                                        <div class="row">
                                            <div class="col-10">
                                                <h5 class="fw-bold text-primary">Contacto de Emergencia</h5>
                                            </div>
                                            <div class="col-2">
                                                <a href="#" class="text-dark icon-hover" data-bs-toggle="modal"
                                                    data-bs-target="#modal_contacto_emergencia">
                                                    <i class='bx bx-show bx-sm'></i></a>
                                            </div>
                                        </div>

                                        <!--  <?php include_once('../vista/GENERAL/per_contacto_emergencia.php'); ?>-->

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cards de la derecha -->
                    <div class="col-xs-7 col-sm-7 col-md-7 col-lg-8 col-xxl-8">
                        <div class="card-body">
                            <!-- Nav Cards -->
                            <ul class="nav nav-tabs nav-success" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#tab_experiencia" role="tab"
                                        aria-selected="true">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class="bx bxs-briefcase font-18 me-1"></i>
                                            </div>
                                            <div class="tab-title">Experiencia</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#successdocs" role="tab"
                                        aria-selected="true">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class="bx bxs-file-doc font-18 me-1"></i>
                                            </div>
                                            <div class="tab-title">Documentos</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#successprofile" role="tab"
                                        aria-selected="false" tabindex="-1">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class="bx bx-brain font-18 me-1"></i>
                                            </div>
                                            <div class="tab-title">Habilidades</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#successcontact" role="tab"
                                        aria-selected="false" tabindex="-1">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class="bx bxs-user-check font-18 me-1"></i>
                                            </div>
                                            <div class="tab-title">Estado del Empleado</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#tab_nivel_academico" role="tab"
                                        aria-selected="false" tabindex="-1">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class="bx bxs-school font-18 me-1"></i>
                                            </div>
                                            <div class="tab-title">Nivel Académico</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#tab_departamento" role="tab"
                                        aria-selected="false" tabindex="-1">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class="bx bxs-school font-18 me-1"></i>
                                            </div>
                                            <div class="tab-title">Departamento</div>
                                        </div>
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content pt-3">
                                <!-- Primera Sección, Historial Laboral -->
                                <div class="tab-pane fade show active" id="tab_experiencia" role="tabpanel">
                                    <div class="card">
                                        <div class="d-flex flex-column mx-4">
                                            <!-- Experiencia Previa -->
                                            <div class="card-body">
                                                <div class="mb-2">
                                                    <div class="row">
                                                        <div class="col-9 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Experiencia Previa:
                                                            </h6>
                                                        </div>

                                                        <div class="col-3 d-flex justify-content-end">
                                                            <a href="#"
                                                                class="text-success icon-hover d-flex align-items-center"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modal_agregar_experiencia">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span>Agregar</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>

                                                <?php include_once('../vista/TALENTO_HUMANO/POSTULANTES/pos_experiencia_previa.php'); ?>

                                            </div>
                                            <!-- Formación Académica -->
                                            <div class="card-body">
                                                <div class="mb-2">
                                                    <div class="row">
                                                        <div class="col-9 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Formación Académica:
                                                            </h6>
                                                        </div>
                                                        <div class="col-3 d-flex justify-content-end">
                                                            <a href="#"
                                                                class="text-success icon-hover d-flex align-items-center"
                                                                id="btn_modal_agregar_formacion_academica"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modal_agregar_formacion">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span>Agregar</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>

                                                <?php include_once('../vista/TALENTO_HUMANO/POSTULANTES/pos_formacion_academica.php'); ?>

                                            </div>
                                            <!-- Certificaciones y capacitación -->
                                            <div class="card-body">
                                                <div class="mb-2">
                                                    <div class="row">
                                                        <div class="col-9 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Certificación y/o
                                                                Capacitación:</h6>
                                                        </div>
                                                        <div class="col-3 d-flex justify-content-end">
                                                            <a href="#"
                                                                class="text-success icon-hover d-flex align-items-center"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modal_agregar_certificaciones">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span>Agregar</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>

                                                <?php include_once('../vista/TALENTO_HUMANO/POSTULANTES/pos_certificaciones_capacitaciones.php'); ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Segunda Sección, Documentos relevantes -->
                                <div class="tab-pane fade" id="successdocs" role="tabpanel">
                                    <div class="card">
                                        <div class="d-flex flex-column mx-4">
                                            <!-- Documento de Identidad -->
                                            <div class="card-body">
                                                <div class="mb-2">
                                                    <div class="row">
                                                        <div class="col-7 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Documento de
                                                                Identidad:</h6>
                                                        </div>
                                                        <div
                                                            class="col-5 d-flex justify-content-end align-items-center">
                                                            <a href="#"
                                                                class="text-success icon-hover d-flex align-items-center"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modal_agregar_documentos_identidad">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span class="">Agregar</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class="my-0 mb-3">

                                                <?php include_once('../vista/TALENTO_HUMANO/POSTULANTES/pos_documento_identidad.php'); ?>

                                            </div>
                                            <!-- Contratos de Trabajo -->
                                            <div class="card-body">
                                                <div class="mb-2">
                                                    <div class="row">
                                                        <div class="col-7 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Contratos de Trabajo:
                                                            </h6>
                                                        </div>
                                                        <div
                                                            class="col-5 d-flex justify-content-end align-items-center">
                                                            <a href="#"
                                                                class="text-success icon-hover d-flex align-items-center"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modal_agregar_contratos">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span class="">Agregar</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class="my-0 mb-3">

                                                <?php include_once('../vista/TALENTO_HUMANO/POSTULANTES/pos_contratos_trabajo.php'); ?>

                                            </div>
                                            <!-- Certificado Médicos -->
                                            <div class="card-body my-0">
                                                <div class="mb-2">
                                                    <div class="row">
                                                        <div class="col-7 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Certificados Médicos:
                                                            </h6>
                                                        </div>
                                                        <div
                                                            class="col-5 d-flex justify-content-end align-items-center">
                                                            <a href="#"
                                                                class="text-success icon-hover d-flex align-items-center"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modal_agregar_certificados_medicos">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span class="">Agregar</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class="my-0 mb-3">

                                                <?php include_once('../vista/TALENTO_HUMANO/POSTULANTES/pos_certificado_medico.php'); ?>

                                            </div>
                                            <!-- Referencias Laborales -->
                                            <div class="card-body">
                                                <div class="mb-2">
                                                    <div class="row">
                                                        <div class="col-7 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Referencias laborales:
                                                            </h6>
                                                        </div>
                                                        <div
                                                            class="col-5 d-flex justify-content-end align-items-center">
                                                            <a href="#"
                                                                class="text-success icon-hover d-flex align-items-center"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modal_agregar_referencia_laboral">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span class="">Agregar</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class="my-0 mb-3">

                                                <?php include_once('../vista/TALENTO_HUMANO/POSTULANTES/pos_referencias_laborales.php'); ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Tercera Sección, Idiomas y aptitudes -->
                                <div class="tab-pane fade" id="successprofile" role="tabpanel">
                                    <div class="card">
                                        <div class="d-flex flex-column mx-4">
                                            <!-- Idiomas -->
                                            <div class="card-body">
                                                <div class="mb-1">
                                                    <div class="row">
                                                        <div class="col-6 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Idiomas</h6>
                                                        </div>
                                                        <div class="col-6 d-flex justify-content-end">
                                                            <a href="#"
                                                                class="text-success icon-hover d-flex align-items-center"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modal_agregar_idioma">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span>Agregar</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class="my-0">

                                                <?php include_once('../vista/TALENTO_HUMANO/POSTULANTES/pos_idiomas.php'); ?>

                                            </div>
                                            <!-- Aptitudes -->
                                            <div class="card-body">
                                                <div class="mb-1">
                                                    <div class="row">
                                                        <div class="col-6 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Aptitudes</h6>
                                                        </div>
                                                        <div class="col-6 d-flex justify-content-end">
                                                            <a href="#"
                                                                class="text-success icon-hover d-flex align-items-center"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modal_agregar_aptitudes"
                                                                onclick="activar_select2();">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span>Agregar</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class="my-0">

                                                <?php include_once('../vista/TALENTO_HUMANO/POSTULANTES/pos_aptitudes.php'); ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Cuarta Sección, Estado del Empleado -->
                                <div class="tab-pane fade" id="successcontact" role="tabpanel">
                                    <div class="card">
                                        <div class="d-flex flex-column mx-4">
                                            <div class="card-body">
                                                <div class="mb-2">
                                                    <div class="row">
                                                        <div class="col-6 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Estado laboral:</h6>
                                                        </div>
                                                        <div class="col-6 d-flex justify-content-end">
                                                            <a href="#"
                                                                class="text-success icon-hover d-flex align-items-center"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modal_estado_laboral">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span>Agregar</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>

                                                <?php include_once('../vista/TALENTO_HUMANO/POSTULANTES/pos_estado_laboral.php'); ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="tab_nivel_academico" role="tabpanel">
                                    <div class="card">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-end mb-3">
                                                <button class="btn btn-primary btn-sm px-4 d-flex align-items-center"
                                                    onclick="abrir_modal_nivel_academico()" type="button">
                                                    <i class="bx bx-save me-1"></i> Nivel Académico
                                                </button>
                                            </div>

                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered"
                                                    id="tbl_nivel_academico" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th>Tipo de nivel</th>
                                                            <th>Nivel Académico</th>
                                                            <th class="text-center">Título</th>
                                                            <th class="text-center">No° Registro</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>

                                        </div>
                                    </div>
                                </div>



                                <!-- Cuarta Sección, Departamento -->
                                <div class="tab-pane fade" id="tab_departamento" role="tabpanel">
                                    <div class="card">
                                        <div class="d-flex flex-column mx-4">
                                            <div class="card-body">
                                                <?php include_once('../vista/TALENTO_HUMANO/PERSONAS/th_persona_departamento_horario.php'); ?>
                                                <div class="d-flex justify-content-end pt-2">
                                                    <button
                                                        class="btn btn-primary btn-sm px-4 m-1 d-flex align-items-center"
                                                        onclick="insertar_persona_departamento();" type="button"><i
                                                            class="bx bx-save"></i> Guardar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para la informacion personal -->
<!-- Modal Información Personal -->
<div class="modal fade" id="modal_informacion_personal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalLabel">
                    <i class="fas fa-user-edit"></i> Información Personal
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form_informacion_personal">
                    <input type="hidden" id="_id" name="_id" value="">

                    <!-- Fotografía -->
                    <div class="row mb-4">
                        <div class="col-md-12 text-center">
                            <div class="form-group">
                                <label class="font-weight-bold">Fotografía</label>
                                <div class="mt-2">
                                    <img class="rounded-circle p-1 bg-primary" src="" class="img-fluid"
                                        id="img_persona_form" alt="Imagen Perfil Persona" width="110" height="110" />
                                    <div class="mt-2">
                                        <small class="text-muted">Click en la imagen para subir foto</small>
                                    </div>
                                    <input type="file" id="file_foto" name="file_foto" accept="image/*"
                                        style="display: none;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Datos Personales -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-user"></i> Datos Personales</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="txt_primer_apellido">Primer Apellido <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="txt_primer_apellido"
                                            name="txt_primer_apellido" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="txt_segundo_apellido">Segundo Apellido</label>
                                        <input type="text" class="form-control" id="txt_segundo_apellido"
                                            name="txt_segundo_apellido">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="txt_primer_nombre">Primer Nombre <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="txt_primer_nombre"
                                            name="txt_primer_nombre" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="txt_segundo_nombre">Segundo Nombre</label>
                                        <input type="text" class="form-control" id="txt_segundo_nombre"
                                            name="txt_segundo_nombre">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="txt_cedula_persona">N° de Cédula <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="txt_cedula_persona"
                                            name="txt_cedula_persona" required maxlength="10">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="ddl_sexo">Sexo <span class="text-danger">*</span></label>
                                        <select class="form-control" id="ddl_sexo" name="ddl_sexo" required>
                                            <option value="">-- Selecciona una opción --</option>
                                            <option value="M">Masculino</option>
                                            <option value="F">Femenino</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="txt_fecha_nacimiento">Fecha de Nacimiento <span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="txt_fecha_nacimiento"
                                            name="txt_fecha_nacimiento" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="txt_edad">Edad</label>
                                        <input type="text" class="form-control" id="txt_edad" name="txt_edad" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="ddl_nacionalidad">Nacionalidad <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control" id="ddl_nacionalidad" name="ddl_nacionalidad"
                                            required>
                                            <option value="">-- Selecciona una Nacionalidad --</option>
                                            <option value="Ecuatoriano">Ecuatoriano</option>
                                            <option value="Colombiano">Colombiano</option>
                                            <option value="Peruano">Peruano</option>
                                            <option value="Venezolano">Venezolano</option>
                                            <option value="Paraguayo">Paraguayo</option>
                                            <option value="Otro">Otro</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="ddl_estado_civil">Estado Civil <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control" id="ddl_estado_civil" name="ddl_estado_civil"
                                            required>
                                            <option value="">-- Selecciona un Estado Civil --</option>
                                            <option value="Soltero/a">Soltero/a</option>
                                            <option value="Casado/a">Casado/a</option>
                                            <option value="Divorciado/a">Divorciado/a</option>
                                            <option value="Viudo/a">Viudo/a</option>
                                            <option value="Unión de hecho">Unión de hecho</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Datos de Contacto -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-phone"></i> Datos de Contacto</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="txt_telefono_1">Teléfono 1 <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="txt_telefono_1"
                                            name="txt_telefono_1" required maxlength="10">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="txt_telefono_2">Teléfono 2</label>
                                        <input type="text" class="form-control" id="txt_telefono_2"
                                            name="txt_telefono_2" maxlength="10">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="txt_correo">Correo Electrónico <span
                                                class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="txt_correo" name="txt_correo"
                                            required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ubicación -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-map-marker-alt"></i> Ubicación</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="txt_direccion">Dirección</label>
                                        <textarea class="form-control" id="txt_direccion" name="txt_direccion"
                                            rows="2"></textarea>
                                    </div>
                                </div>
                                <?php include_once('../vista/TALENTO_HUMANO/PERSONAS/provincias_ciudades_parroquias.php'); ?>
                            </div>
                        </div>
                    </div>


                    <!-- Información Adicional -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-info-circle"></i> Información Adicional</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="ddl_tipo_sangre">Tipo de Sangre</label>
                                        <select class="form-control" id="ddl_tipo_sangre" name="ddl_tipo_sangre">
                                            <option value="">-- Selecciona una opción --</option>
                                            <option value="A+">A+</option>
                                            <option value="A-">A-</option>
                                            <option value="B+">B+</option>
                                            <option value="B-">B-</option>
                                            <option value="O+">O+</option>
                                            <option value="O-">O-</option>
                                            <option value="AB+">AB+</option>
                                            <option value="AB-">AB-</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="ddl_etnia">Etnia</label>
                                        <select class="form-control" id="ddl_etnia" name="ddl_etnia">
                                            <option value="">-- Selecciona una opción --</option>
                                            <option value="Mestizo">Mestizo</option>
                                            <option value="Indígena">Indígena</option>
                                            <option value="Afroecuatoriano">Afroecuatoriano</option>
                                            <option value="Montubio">Montubio</option>
                                            <option value="Blanco">Blanco</option>
                                            <option value="Otro">Otro</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="ddl_religion">Religión</label>
                                        <select class="form-control" id="ddl_religion" name="ddl_religion">
                                            <option value="">-- Selecciona una opción --</option>
                                            <option value="Católico">Católico</option>
                                            <option value="Cristiano">Cristiano</option>
                                            <option value="Evangélico">Evangélico</option>
                                            <option value="Testigo de Jehová">Testigo de Jehová</option>
                                            <option value="Ateo">Ateo</option>
                                            <option value="Agnóstico">Agnóstico</option>
                                            <option value="Otro">Otro</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="ddl_orientacion">Orientación Sexual</label>
                                        <select class="form-control" id="ddl_orientacion" name="ddl_orientacion">
                                            <option value="">-- Selecciona una opción --</option>
                                            <option value="Heterosexual">Heterosexual</option>
                                            <option value="Homosexual">Homosexual</option>
                                            <option value="Bisexual">Bisexual</option>
                                            <option value="Prefiero no decir">Prefiero no decir</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="ddl_cargo">Cargo</label>
                                        <select class="form-control select2" id="ddl_cargo" name="ddl_cargo">
                                            <option value="">-- Selecciona un cargo --</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Agregar DESPUÉS de la sección "Información Adicional" y ANTES de "Información sobre Discapacidad" -->

                    <!-- Información Laboral -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-briefcase"></i> Información Laboral</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="txt_fecha_ingreso">Fecha de Ingreso</label>
                                        <input type="date" class="form-control" id="txt_fecha_ingreso"
                                            name="txt_fecha_ingreso">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="txt_anios_trabajo">Años de Trabajo</label>
                                        <input type="number" class="form-control" id="txt_anios_trabajo"
                                            name="txt_anios_trabajo" min="0" step="0.1" placeholder="Ej: 5.5">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="txt_seccion">Sección/Departamento</label>
                                        <input type="text" class="form-control" id="txt_seccion" name="txt_seccion"
                                            placeholder="Ej: Recursos Humanos">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="txt_remuneracion">Remuneración</label>
                                        <input type="number" class="form-control" id="txt_remuneracion"
                                            name="txt_remuneracion" min="0" step="0.01" placeholder="Ej: 1500.00">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="txt_comision_asuntos_sociales">Comisión Asuntos Sociales</label>
                                        <input type="text" class="form-control" id="txt_comision_asuntos_sociales"
                                            name="txt_comision_asuntos_sociales" placeholder="Ej: Comité de eventos">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información de Vehículo -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-car"></i> Información de Vehículo</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="ddl_clase_auto">Clase de Vehículo</label>
                                        <select class="form-control" id="ddl_clase_auto" name="ddl_clase_auto">
                                            <option value="">-- Selecciona una opción --</option>
                                            <option value="Automóvil">Automóvil</option>
                                            <option value="Camioneta">Camioneta</option>
                                            <option value="SUV">SUV</option>
                                            <option value="Motocicleta">Motocicleta</option>
                                            <option value="Ninguno">Ninguno</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="txt_placa_original">Placa Original</label>
                                        <input type="text" class="form-control" id="txt_placa_original"
                                            name="txt_placa_original" placeholder="Ej: ABC-1234" maxlength="10">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="txt_placa_sintesis">Placa Síntesis</label>
                                        <input type="text" class="form-control" id="txt_placa_sintesis"
                                            name="txt_placa_sintesis" placeholder="Ej: XYZ-5678" maxlength="10">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información sobre Discapacidad -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-wheelchair"></i> Información sobre Discapacidad</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="ddl_tipo_discapacidad">Tipo de Discapacidad</label>
                                        <select class="form-control" id="ddl_tipo_discapacidad"
                                            name="ddl_tipo_discapacidad">
                                            <option value="">-- Selecciona una opción --</option>
                                            <option value="Ninguna">Ninguna</option>
                                            <option value="Física">Física</option>
                                            <option value="Visual">Visual</option>
                                            <option value="Auditiva">Auditiva</option>
                                            <option value="Intelectual">Intelectual</option>
                                            <option value="Psicosocial">Psicosocial</option>
                                            <option value="Múltiple">Múltiple</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="txt_porcentaje_discapacidad">Porcentaje de Discapacidad (%)</label>
                                        <input type="number" class="form-control" id="txt_porcentaje_discapacidad"
                                            name="txt_porcentaje_discapacidad" min="0" max="100">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="ddl_escala_discapacidad">Escala de Discapacidad</label>
                                        <select class="form-control" id="ddl_escala_discapacidad"
                                            name="ddl_escala_discapacidad">
                                            <option value="">-- Selecciona una opción --</option>
                                            <option value="Leve">Leve (1-24%)</option>
                                            <option value="Moderada">Moderada (25-49%)</option>
                                            <option value="Grave">Grave (50-74%)</option>
                                            <option value="Muy Grave">Muy Grave (75-100%)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Observaciones -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-comment"></i> Observaciones</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <textarea class="form-control" id="txt_observaciones" name="txt_observaciones" rows="3"
                                    placeholder="Ingrese observaciones adicionales"></textarea>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="cerrarModalInformacionPersonal()">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <button type="button" class="btn btn-primary" onclick="insertar_editar_informacion_personal()">
                    <i class="fas fa-save"></i> Guardar
                </button>
            </div>
        </div>
    </div>
</div>


<!-- MODAL NIVEL ACADÉMICO -->
<div class="modal fade" id="modal_nivel_academico" tabindex="-1" aria-labelledby="modal_nivel_academico_label"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modal_nivel_academico_label">
                    Nivel Académico
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="form_nivel_academico">

                    <!-- ID OCULTO PARA EDITAR -->
                    <input type="hidden" id="_per_id" name="_per_id">

                    <!-- ID PERSONA -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label">Tipo de Nivel *</label>
                            <select id="pna_tipo_nivel" name="pna_tipo_nivel" class="form-select" required>
                                <option value="">Seleccione...</option>
                                <option value="TERCER NIVEL">Tercer Nivel</option>
                                <option value="CUARTO NIVEL">Cuarto Nivel</option>
                                <option value="GENERAL">General</option>
                            </select>
                        </div>
                    </div>

                    <!-- NIVEL ACADEMICO Y TITULO -->
                    <div class="row mb-3">

                        <div class="col-md-6">
                            <label class="form-label">Nivel Académico *</label>
                            <input type="text" id="pna_nivel_academico" name="pna_nivel_academico" class="form-control"
                                placeholder="Ej: Ingeniería, Maestría..." required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Título *</label>
                            <input type="text" id="pna_titulo" name="pna_titulo" class="form-control"
                                placeholder="Ej: Ingeniero en Sistemas" required>
                        </div>
                    </div>

                    <!-- REGISTRO SENESCYT -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Registro SENESCYT</label>
                            <input type="text" id="pna_registro_senescyt" name="pna_registro_senescyt"
                                class="form-control" placeholder="Ej: 1234-ABCD">
                        </div>
                    </div>

                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="cerrarModalNivelAcademico()">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <button type="button" class="btn btn-primary" onclick="insertar_editar_nivel_academico()">
                    <i class="fas fa-save"></i> Guardar
                </button>
            </div>

        </div>
    </div>
</div>




<script>
function cerrarModalInformacionPersonal() {
    $('#modal_informacion_personal').modal('hide');
}

//Validacion de formulario
$(document).ready(function() {
    agregar_asterisco_campo_obligatorio('txt_primer_apellido');
    agregar_asterisco_campo_obligatorio('txt_segundo_apellido');
    agregar_asterisco_campo_obligatorio('txt_primer_nombre');
    agregar_asterisco_campo_obligatorio('txt_segundo_nombre');
    agregar_asterisco_campo_obligatorio('txt_numero_cedula');
    agregar_asterisco_campo_obligatorio('ddl_sexo');
    agregar_asterisco_campo_obligatorio('txt_fecha_nacimiento');
    agregar_asterisco_campo_obligatorio('txt_edad');
    agregar_asterisco_campo_obligatorio('txt_telefono_1');
    agregar_asterisco_campo_obligatorio('txt_telefono_2');
    agregar_asterisco_campo_obligatorio('txt_correo');
    agregar_asterisco_campo_obligatorio('ddl_nacionalidad');
    agregar_asterisco_campo_obligatorio('ddl_estado_civil');
    agregar_asterisco_campo_obligatorio('ddl_provincias');
    agregar_asterisco_campo_obligatorio('ddl_ciudad');
    agregar_asterisco_campo_obligatorio('ddl_parroquia');
    agregar_asterisco_campo_obligatorio('txt_codigo_postal');
    agregar_asterisco_campo_obligatorio('txt_direccion');

    //Validación Información Personal
    $("#form_informacion_personal").validate({
        rules: {
            txt_primer_apellido: {
                required: true,
            },
            txt_segundo_apellido: {
                required: true,
            },
            txt_primer_nombre: {
                required: true,
            },
            txt_segundo_nombre: {
                required: true,
            },
            txt_numero_cedula: {
                required: true,
            },
            ddl_sexo: {
                required: true,
            },
            txt_fecha_nacimiento: {
                required: true,
            },
            txt_edad: {
                required: true,
            },
            txt_telefono_1: {
                required: true,
            },
            txt_telefono_2: {
                required: true,
            },
            txt_correo: {
                required: true,
            },
            ddl_nacionalidad: {
                required: true,
            },
            ddl_estado_civil: {
                required: true,
            },
        },
        messages: {
            txt_primer_apellido: {
                required: "Por favor ingrese el primer apellido",
            },
            txt_segundo_apellido: {
                required: "Por favor ingrese el segundo apellido",
            },
            txt_primer_nombre: {
                required: "Por favor ingrese el primer nombre",
            },
            txt_segundo_nombre: {
                required: "Por favor ingrese el segundo nombre",
            },
            txt_numero_cedula: {
                required: "Por favor ingresa un número de cédula",
            },
            ddl_sexo: {
                required: "Por favor seleccione el sexo",
            },
            txt_fecha_nacimiento: {
                required: "Por favor ingrese la fecha de nacimiento",
            },
            txt_edad: {
                required: "Por favor ingrese la edad (fecha de nacimiento)",
            },
            txt_telefono_1: {
                required: "Por favor ingrese el primero teléfono",
            },
            txt_telefono_2: {
                required: "Por favor ingrese el segundo teléfono",
            },
            txt_correo: {
                required: "Por favor ingrese un correo",
            },
            ddl_nacionalidad: {
                required: "Por favor seleccione su nacionalidad",
            },
            ddl_estado_civil: {
                required: "Por favor seleccione su estado civil",
            },
        },

        highlight: function(element) {
            // Agrega la clase 'is-invalid' al input que falla la validación
            $(element).addClass('is-invalid');
            $(element).removeClass('is-valid');
        },
        unhighlight: function(element) {
            // Elimina la clase 'is-invalid' si la validación pasa
            $(element).removeClass('is-invalid');
            $(element).addClass('is-valid');

        }
    });

});

function convertir_postulante_a_persona(id_postulante) {
    Swal.fire({
        title: '¿Convertir a Persona?',
        text: "Se creará un registro de persona con todos los datos del postulante",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, convertir',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.value) {
            procesar_conversion_postulante(id_postulante);
        }
    });
}

/**
 * Procesa la conversión del postulante a persona
 */
function procesar_conversion_postulante(id_postulante) {
    $.ajax({
        data: {
            id_postulante: id_postulante
        },
        url: '../controlador/GENERAL/th_personasC.php?convertir_postulante=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            if (response.status == 1) {
                Swal.fire({
                    title: 'Éxito',
                    text: 'El postulante ha sido convertido a persona exitosamente',
                    icon: 'success',
                    confirmButtonText: 'Ver Persona'
                }).then(() => {
                    // Redirigir a la vista de persona creada
                    window.location.href = '../vista/inicio.php?mod=' + <?= $modulo_sistema ?> +
                        '&acc=th_persona_informacion&_id=' + response.id_persona;
                });
            } else if (response.status == -1) {
                Swal.fire('Error', 'Ya existe una persona con esta cédula', 'error');
            } else if (response.status == -2) {
                Swal.fire('Error', 'El postulante no existe', 'warning');
            } else {
                Swal.fire('Error', 'No se pudo realizar la conversión', 'error');
            }
        },
        error: function(xhr, status, error) {
            Swal.fire('Error', 'Error en el servidor: ' + error, 'error');
        }
    });
}

/**
 * Función para verificar si un postulante ya fue convertido a persona
 */
function verificar_postulante_convertido(id_postulante) {
    $.ajax({
        data: {
            id_postulante: id_postulante
        },
        url: '../controlador/GENERAL/th_personasC.php?verificar_conversion=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            if (response.convertido) {
                // Mostrar mensaje y botón para ir a la persona
                $('#btn_convertir_persona').hide();
                $('#lbl_ya_convertido').show();
                $('#btn_ir_persona').attr('onclick', 'ir_a_persona("' + response.id_persona + '")').show();
            } else {
                $('#btn_convertir_persona').show();
                $('#lbl_ya_convertido').hide();
                $('#btn_ir_persona').hide();
            }
        }
    });
}

/**
 * Función para ir a la vista de persona
 */
function ir_a_persona(id_persona) {
    window.location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_persona_informacion&_id=' +
        id_persona;
}

/**
 * Función para copiar datos específicos de postulante a persona existente
 * Útil cuando ya existe la persona pero queremos actualizar con datos del postulante
 */
function sincronizar_datos_postulante_persona(id_postulante, id_persona) {
    Swal.fire({
        title: '¿Sincronizar datos?',
        text: "Se actualizarán los datos de la persona con la información del postulante",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, sincronizar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                data: {
                    id_postulante: id_postulante,
                    id_persona: id_persona
                },
                url: '../controlador/GENERAL/th_personasC.php?sincronizar_postulante_persona=true',
                type: 'post',
                dataType: 'json',
                success: function(response) {
                    if (response == 1) {
                        Swal.fire('Éxito', 'Datos sincronizados correctamente', 'success');
                        cargarDatos_informacion_personal(id_persona);
                    } else {
                        Swal.fire('Error', 'No se pudieron sincronizar los datos', 'error');
                    }
                }
            });
        }
    });
}

/**
 * Función para listar datos del postulante y compararlos con persona
 */
function ver_comparacion_postulante_persona(id_postulante, id_persona) {
    $.ajax({
        data: {
            id_postulante: id_postulante,
            id_persona: id_persona
        },
        url: '../controlador/GENERAL/th_personasC.php?comparar_datos=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            // Mostrar modal con comparación
            mostrar_modal_comparacion(response);
        }
    });
}

/**
 * Función para cargar experiencia previa del postulante a persona
 */
function copiar_experiencia_postulante(id_postulante, id_persona) {
    $.ajax({
        data: {
            id_postulante: id_postulante,
            id_persona: id_persona
        },
        url: '../controlador/GENERAL/th_personasC.php?copiar_experiencia=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            if (response == 1) {
                Swal.fire('Éxito', 'Experiencia copiada correctamente', 'success');
                // Recargar tabla de experiencia
                if (typeof tbl_experiencia !== 'undefined') {
                    tbl_experiencia.ajax.reload(null, false);
                }
            }
        }
    });
}

/**
 * Función para copiar formación académica del postulante a persona
 */
function copiar_formacion_postulante(id_postulante, id_persona) {
    $.ajax({
        data: {
            id_postulante: id_postulante,
            id_persona: id_persona
        },
        url: '../controlador/GENERAL/th_personasC.php?copiar_formacion=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            if (response == 1) {
                Swal.fire('Éxito', 'Formación académica copiada correctamente', 'success');
                // Recargar tabla de formación
                if (typeof tbl_formacion !== 'undefined') {
                    tbl_formacion.ajax.reload(null, false);
                }
            }
        }
    });
}

/**
 * Función para copiar certificaciones del postulante a persona
 */
function copiar_certificaciones_postulante(id_postulante, id_persona) {
    $.ajax({
        data: {
            id_postulante: id_postulante,
            id_persona: id_persona
        },
        url: '../controlador/GENERAL/th_personasC.php?copiar_certificaciones=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            if (response == 1) {
                Swal.fire('Éxito', 'Certificaciones copiadas correctamente', 'success');
                // Recargar tabla de certificaciones
                if (typeof tbl_certificaciones !== 'undefined') {
                    tbl_certificaciones.ajax.reload(null, false);
                }
            }
        }
    });
}

/**
 * Función para copiar documentos del postulante a persona
 */
function copiar_documentos_postulante(id_postulante, id_persona) {
    $.ajax({
        data: {
            id_postulante: id_postulante,
            id_persona: id_persona
        },
        url: '../controlador/GENERAL/th_personasC.php?copiar_documentos=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            if (response == 1) {
                Swal.fire('Éxito', 'Documentos copiados correctamente', 'success');
            }
        }
    });
}

/**
 * Función para copiar idiomas del postulante a persona
 */
function copiar_idiomas_postulante(id_postulante, id_persona) {
    $.ajax({
        data: {
            id_postulante: id_postulante,
            id_persona: id_persona
        },
        url: '../controlador/GENERAL/th_personasC.php?copiar_idiomas=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            if (response == 1) {
                Swal.fire('Éxito', 'Idiomas copiados correctamente', 'success');
                // Recargar tabla de idiomas
                if (typeof tbl_idiomas !== 'undefined') {
                    tbl_idiomas.ajax.reload(null, false);
                }
            }
        }
    });
}

/**
 * Función para copiar aptitudes del postulante a persona
 */
function copiar_aptitudes_postulante(id_postulante, id_persona) {
    $.ajax({
        data: {
            id_postulante: id_postulante,
            id_persona: id_persona
        },
        url: '../controlador/GENERAL/th_personasC.php?copiar_aptitudes=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            if (response == 1) {
                Swal.fire('Éxito', 'Aptitudes copiadas correctamente', 'success');
                // Recargar tabla de aptitudes
                if (typeof tbl_aptitudes !== 'undefined') {
                    tbl_aptitudes.ajax.reload(null, false);
                }
            }
        }
    });
}

/**
 * Función para copiar TODOS los datos del postulante (completo)
 */
function copiar_todos_datos_postulante(id_postulante, id_persona) {
    Swal.fire({
        title: '¿Copiar todos los datos?',
        text: "Se copiarán todos los registros del postulante a la persona",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, copiar todo',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.value) {
            // Mostrar loading
            Swal.fire({
                title: 'Copiando datos...',
                text: 'Por favor espere',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                data: {
                    id_postulante: id_postulante,
                    id_persona: id_persona
                },
                url: '../controlador/GENERAL/th_personasC.php?copiar_todos_datos=true',
                type: 'post',
                dataType: 'json',
                success: function(response) {
                    Swal.close();
                    if (response.status == 1) {
                        Swal.fire({
                            title: 'Éxito',
                            html: 'Datos copiados correctamente:<br>' +
                                'Experiencias: ' + response.experiencias + '<br>' +
                                'Formaciones: ' + response.formaciones + '<br>' +
                                'Certificaciones: ' + response.certificaciones + '<br>' +
                                'Documentos: ' + response.documentos + '<br>' +
                                'Idiomas: ' + response.idiomas + '<br>' +
                                'Aptitudes: ' + response.aptitudes,
                            icon: 'success'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error', 'No se pudieron copiar todos los datos', 'error');
                    }
                },
                error: function() {
                    Swal.close();
                    Swal.fire('Error', 'Error al procesar la solicitud', 'error');
                }
            });
        }
    });
}
</script>