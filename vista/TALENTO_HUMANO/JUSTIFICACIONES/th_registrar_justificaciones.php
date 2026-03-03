<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);

$_id = '';
$tipo = $_GET['tipo'] ?? '';
if (isset($_GET['_id'])) {
    $_id = $_GET['_id'];
}
if (isset($_GET['tipo'])) {
    $tipo = $_GET['tipo'];
}

?>

<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {

    let tipo = "<?= $tipo ?>";

    if (tipo === "persona") {
        // mostrar panel personas
        document.getElementById("pnl_personas").style.display = "block";
        document.getElementById("pnl_departamentos").style.display = "none";

        // marcar radio
        document.getElementById("cbx_programar_persona").checked = true;

    }

    if (tipo === "departamento") {
        document.getElementById("pnl_personas").style.display = "none";
        document.getElementById("pnl_departamentos").style.display = "block";

        document.getElementById("cbx_programar_departamento").checked = true;

    }

});
</script>
<script type="text/javascript">

var listado = [];
$(document).ready(function() {
    <?php if (isset($_GET['_id'])) { ?>
    datos_col(<?= $_id ?>);
    <?php } ?>

    cargar_selects2();

});

function datos_col(id) {
    $.ajax({
        data: {
            id: id
        },
        url: '../controlador/TALENTO_HUMANO/th_justificacionesC.php?listar=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            console.log(response);
            $('#txt_fecha_inicio').val(fecha_input_datelocal(response[0].fecha_inicio));
            $('#txt_fecha_fin').val(fecha_input_datelocal(response[0].fecha_fin));
            $('#txt_motivo').val(response[0].motivo);

            if (response[0].id_persona == 0) {
                $('#pnl_departamentos').show();
                $('#txt_tipo_programar_departamento').prop('checked', true);
            }

            if (response[0].id_departamento == 0) {
                $('#pnl_personas').show();
                $('#txt_tipo_programar_persona').prop('checked', true);
            }

            //Tipo de horario - Con horario o sin horario

            //Selects
            $('#ddl_departamentos').append($('<option>', {
                value: response[0].id_departamento,
                text: response[0].nombre_departamento,
                selected: true
            }));

            $('#ddl_personas').append($('<option>', {
                value: response[0].id_persona,
                text: response[0].nombre_persona,
                selected: true
            }));

            $('#ddl_tipo_justificacion').append($('<option>', {
                value: response[0].id_tipo_justificacion,
                text: response[0].tipo_motivo,
                selected: true
            }));
            if (response[0].es_rango == 1) {
                $('#cbx_justificar_rango').prop('checked', true);
                $('#txt_fecha_inicio').attr('type', 'date').val(response[0].fecha_inicio.split(' ')[0]);
                $('#txt_fecha_fin').attr('type', 'date').val(response[0].fecha_fin.split(' ')[0]);
                $('#pnl_horas_totales').hide();
            } else {
                $('#cbx_justificar_rango').prop('checked', false);
                $('#pnl_horas_totales').show();

                let horaValidaInicio = response[0].fecha_inicio.split(' ')[1].substring(0, 5);
                let horaValidaFinal = response[0].fecha_fin.split(' ')[1].substring(0, 5);
                $('#txt_fecha_inicio').attr('type', 'time').val(horaValidaInicio);
                $('#txt_fecha_fin').attr('type', 'time').val(horaValidaFinal);
                calcular_Diferencia_Horas();
            }

        }
    });
}

function editar_insertar() {

    var txt_fecha_inicio = $('#txt_fecha_inicio').val();
    var txt_fecha_fin = $('#txt_fecha_fin').val();
    var ddl_personas = $('#ddl_personas').val() ?? 0;
    var ddl_departamentos = $('#ddl_departamentos').val() ?? 0;
    var ddl_tipo_justificacion = $('#ddl_tipo_justificacion').val() ?? 0;
    var txt_motivo = $('#txt_motivo').val() ?? 0;
    var cbx_justificar_rango = 0;
    var txt_horas_totales = $('#txt_horas_totales').val();

    var parametros = {
        '_id': '<?= $_id ?>',
        'txt_fecha_inicio': txt_fecha_inicio,
        'txt_fecha_fin': txt_fecha_fin,
        'ddl_personas': ddl_personas,
        'ddl_departamentos': ddl_departamentos,
        'ddl_tipo_justificacion': ddl_tipo_justificacion,
        'txt_motivo': txt_motivo,
        'cbx_justificar_rango': cbx_justificar_rango,
        'txt_horas_totales': txt_horas_totales,
        'txt_tipo_just':$('#txt_tipo_just').val(),
        'txt_id_DepPer':$('#txt_id_DepPer').val(),
    };

    if ($("#form_justificaciones").valid()) {
        // Si es válido, puedes proceder a enviar los datos por AJAX
        if ($("#form_justificaciones_").valid()) {
            insertar(parametros);
        }
    }

    console.log(parametros);
}

function editar_insertar_rangos() {

    var txt_fecha_inicio = $('#txt_fecha_inicio').val();
    var txt_fecha_fin = $('#txt_fecha_fin').val();
    var ddl_personas = $('#ddl_personas').val() ?? 0;
    var ddl_departamentos = $('#ddl_departamentos').val() ?? 0;
    var ddl_tipo_justificacion = $('#ddl_tipo_justificacion').val() ?? 0;
    var txt_motivo = $('#txt_motivo').val() ?? 0;
    var cbx_justificar_rango = $('#cbx_justificar_rango').prop('checked') ? 1 : 0;
    var txt_horas_totales = $('#txt_horas_totales').val();

    console.log(listado);

    var parametros = {
        '_id': '<?= $_id ?>',
        'txt_fecha_inicio': txt_fecha_inicio,
        'txt_fecha_fin': txt_fecha_fin,
        'rango':listado,
        'ddl_personas': ddl_personas,
        'ddl_departamentos': ddl_departamentos,
        'ddl_tipo_justificacion': ddl_tipo_justificacion,
        'txt_motivo': txt_motivo,
        'cbx_justificar_rango': 1,
        'txt_horas_totales': txt_horas_totales,
        'txt_tipo_just':$('#txt_tipo_just').val(),
        'txt_id_DepPer':$('#txt_id_DepPer').val(),
    };

    if ($("#form_justificaciones").valid()) {
        // Si es válido, puedes proceder a enviar los datos por AJAX
        if ($("#form_justificaciones_").valid()) {
            insertar(parametros);
        }
    }

    console.log(parametros);
}

function insertar(parametros) {
    $.ajax({
        data: {
            parametros: parametros
        },
        url: '../controlador/TALENTO_HUMANO/th_justificacionesC.php?insertar=true',
        type: 'post',
        dataType: 'json',

        success: function(response) {
            if (response == 1) {
                Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                    location.href =
                        '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_justificaciones';
                });
            } else if (response == -2) {
                Swal.fire('', 'Error al guardar la información.', 'warning');
            }
            else if (response == -3) {
                Swal.fire('', 'Las fechas ya son utilizadas en otra justificación', 'warning');
            }
        },

        error: function(xhr, status, error) {
            console.log('Status: ' + status);
            console.log('Error: ' + error);
            console.log('XHR Response: ' + xhr.responseText);

            Swal.fire('', 'Error: ' + xhr.responseText, 'error');
        }
    });
}

function delete_datos() {
    var id = '<?= $_id ?>';
    Swal.fire({
        title: 'Eliminar Registro?',
        text: "Esta seguro de eliminar este registro?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si'
    }).then((result) => {
        if (result.value) {
            eliminar(id);
        }
    })
}

function eliminar(id) {
    $.ajax({
        data: {
            id: id
        },
        url: '../controlador/TALENTO_HUMANO/th_justificacionesC.php?eliminar=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            if (response == 1) {
                Swal.fire('Eliminado!', 'Registro Eliminado.', 'success').then(function() {
                    location.href =
                        '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_justificaciones';
                });
            }
        }
    });
}

function cargar_selects2() {
    url_personasC = '../controlador/TALENTO_HUMANO/th_personasC.php?buscar=true';
    cargar_select2_url('ddl_personas', url_personasC);
    url_departamentosC = '../controlador/TALENTO_HUMANO/th_departamentosC.php?buscar=true';
    cargar_select2_url('ddl_departamentos', url_departamentosC);
    url_tipo_justificacionC = '../controlador/TALENTO_HUMANO/th_cat_tipo_justificacionC.php?buscar=true';
    cargar_select2_url('ddl_tipo_justificacion', url_tipo_justificacionC,'-- Seleccione --','#myModal_justificar');
}
</script>



<script>
//Funciones adicionales 
$(document).ready(function() {

    // $('#txt_fecha_inicio').attr('type', 'time');
    // $('#txt_fecha_fin').attr('type', 'time');
    $('#pnl_horas_totales').show();

    $('input[name="txt_tipo_programar"]').on('change', function() {
        $('#pnl_personas, #pnl_departamentos').hide();

        // Reiniciar valores y remover required
        $('#ddl_personas').val(null).trigger('change').removeAttr('required');
        $('#ddl_departamentos').val(null).trigger('change').removeAttr('required');

        if ($(this).attr('id') === 'txt_tipo_programar_persona') {
            $('#pnl_personas').show();
            $('#ddl_personas').attr('required', true); // Agregar required dinámicamente
        } else if ($(this).attr('id') === 'txt_tipo_programar_departamento') {
            $('#pnl_departamentos').show();
            $('#ddl_departamentos').attr('required',
                true); // Opcional si quieres validar departamentos también
        }

        limpiar_parametros_validate();

    });

    //Validacion para las fechas
    $("input[name='txt_fecha_fin']").on("blur", function() {
        if (!verificar_fecha_inicio_fecha_fin('txt_fecha_inicio', 'txt_fecha_fin')) return;
    });
    $("input[name='txt_fecha_inicio']").on("blur", function() {
        if (!verificar_fecha_inicio_fecha_fin('txt_fecha_inicio', 'txt_fecha_fin')) return;
    });


    $('#cbx_justificar_rango').on('change', function() {
        if ($(this).is(':checked')) {
            $('#txt_fecha_inicio').attr('type', 'date');
            $('#txt_fecha_fin').attr('type', 'date');
            $('#pnl_horas_totales').hide();
        } else {
            $('#txt_fecha_inicio').attr('type', 'time');
            $('#txt_fecha_fin').attr('type', 'time');
            $('#pnl_horas_totales').show();
        }
        calcular_Diferencia_Horas();
    });

    $('#txt_fecha_inicio, #txt_fecha_fin').on('change', calcular_Diferencia_Horas);


});

function calcular_Diferencia_Horas() {
    let tipo = $('#txt_fecha_inicio').attr('type');
    let inicio = $('#txt_fecha_inicio').val();
    let fin = $('#txt_fecha_fin').val();

    if (!inicio || !fin) return;

    if (tipo === 'time') {
        let [h1, m1] = inicio.split(':').map(Number);
        let [h2, m2] = fin.split(':').map(Number);

        let minInicio = h1 * 60 + m1;
        let minFin = h2 * 60 + m2;

        if (minFin < minInicio) {
            Swal.fire({
                icon: 'error',
                title: '¡Error!',
                text: 'La hora final no puede ser menor que la hora inicial',
                confirmButtonColor: '#d33',
            });
            $('#txt_fecha_fin').val('');
            $('#txt_horas_totales').val('');
            return;
        }

        let diferencia = minFin - minInicio;
        let horas = Math.floor(diferencia / 60).toString().padStart(2, '0');
        let minutos = (diferencia % 60).toString().padStart(2, '0');

        $('#txt_horas_totales').val(`${horas}:${minutos}`);
    } else if (tipo === 'date') {
        let fechaInicio = new Date(inicio);
        let fechaFin = new Date(fin);

        if (fechaFin < fechaInicio) {
            Swal.fire({
                icon: 'error',
                title: '¡Error!',
                text: 'La hora final no puede ser menor que la hora inicial',
                confirmButtonColor: '#d33',
            });
            $('#txt_fecha_fin').val('');
        }
    }
}



function limpiar_parametros_validate() {
    //Limpiar validaciones
    //$("#form_articulo").validate().resetForm();
    $('.select2-selection').removeClass('is-valid is-invalid');
    $('.select2-validation').each(function() {
        $('label.error[for="' + this.id + '"]').hide();
    });
}

function obtenerDiaEnEspanol(fecha) {
    const fechaObj = new Date(fecha);
    
    return fechaObj.toLocaleDateString('es-ES', {
        weekday: 'long' // 'long' para nombre completo, 'short' para abreviado
    });
}

function cargar_faltas()
{
    var parametros = 
    {
         'persona': $('#ddl_personas').val(),
         'desdeReg':$('#txt_desde_reg').val(),
         'hastaReg':$('#txt_hasta_reg').val(),
    }
     $.ajax({
        data: {parametros,parametros},
        url: '../controlador/TALENTO_HUMANO/th_justificacionesC.php?cargar_faltas=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            var atrasos = '';
            var all = '';
            var faltas = '';

            $('#pnl_registros_justificables').removeClass("d-none");           

            response.faltas.forEach(function(item,i){
                var dia = obtenerDiaEnEspanol(item.asi_faltas_fecha_inicio);
                faltas+=`<tr>
                <td  class="text-center"><input type="checkbox" class="rbl_masivo" id="cbx_registro_`+item._id+`_F" value="`+item._id+`_F_`+item.asi_faltas_fecha_inicio.substring(0,10)+`_`+item.asi_faltas_total_min+`" name="cbx_registro_`+item._id+`_F" onclick="justificar_grupo('F','`+item._id+`')"> </td>
                    <td><button type="button" id="btn_justi_`+item._id+`_F" title="Justificar falta" class="btn btn-sm btn-primary" 
                            onclick="justificar_registro('`+item.asi_faltas_fecha_inicio.substring(0,10)+`',
                                                         '`+item.asi_faltas_total_min+`',
                                                         'F',
                                                         '`+item._id+`')"><i class="bx bx-calendar-edit me-0"></i></button></td>
                    <td>`+dia+`</td>
                    <td>`+item.asi_faltas_fecha_inicio.substring(0,10)+`</td>
                    <td>`+minutosAHora(item.asi_faltas_total_min)+`</td>
                    <td>Faltas</td>
                </tr>`
            })

            response.atrasos.forEach(function(item,i){                
                var dia = obtenerDiaEnEspanol(item.asi_atrasos_fecha_marcacion);
                atrasos+=`<tr>
                    <td class="text-center"><input type="checkbox" class="rbl_masivo" id="cbx_registro_`+item._id+`_A" value="`+item._id+`_A_`+item.asi_fecha_parametrizada+`_`+item.asi_atrasos_total_min+`" name="cbx_registro_`+item._id+`_A" onclick="justificar_grupo('A','`+item._id+`')"> </td>
                    <td><button type="button" id="btn_justi_`+item._id+`_A" title="Justificar falta" class="btn btn-sm btn-primary" 
                            onclick="justificar_registro('`+item.asi_fecha_parametrizada+`',
                                                         '`+item.asi_atrasos_total_min+`',
                                                         'A',
                                                         '`+item._id+`')"><i class="bx bx-calendar-edit me-0"></i></button></td>
                    <td>`+dia+`</td>
                    <td>`+item.asi_fecha_parametrizada+`</td>
                    <td>`+minutosAHora(item.asi_atrasos_total_min)+`</td>
                    <td>Atrasos</td>
                </tr>`
            })

            all = faltas+atrasos;

            $('#tbl_all').html(all);
            $('#tbl_atrasos').html(atrasos);
            $('#tbl_faltas').html(faltas);




           console.log(response);
        }
    });
}

function  justificar_grupo(tipo,id)
{
    var check = 0;
    $('.rbl_masivo').each(function() {
        const checkbox = $(this);
        const isChecked = checkbox.prop('checked'); 
        if (isChecked) {
            check = 1;
        }
    });


    if($("#cbx_registro_"+id+"_"+tipo).prop('checked'))
    {
        $('#btn_justi_'+id+"_"+tipo).attr('disabled',true);
    }else
    {
        $('#btn_justi_'+id+"_"+tipo).attr('disabled',false);
    }

    if(check) { $('#btn_masivo').removeClass("d-none"); }else{$('#btn_masivo').addClass("d-none"); }
}

function justificar_registro(fecha,minutos,tipo,id)
{

    $('#btn_guardar_normal').removeClass('d-none');
    $('#btn_guardar_rangos').addClass('d-none');

    titulo = "faltas";
    if(tipo=='A'){titulo = 'atrasos';}
    $('#lbl_titulo').text(titulo);
    $('#txt_fecha_inicio').val(fecha);
    $('#txt_tipo_just').val(tipo);
    $('#txt_id_DepPer').val(id);
    $('#txt_fecha_fin').val(fecha);
    $('#txt_horas_totales').val(minutosAHora(minutos));
    $('#myModal_justificar').modal('show');
}


function justificar_masivo()
{
    $('#btn_guardar_normal').addClass('d-none');
    $('#btn_guardar_rangos').removeClass('d-none');
    // listado = [];
    var total_min = 0;
     $('.rbl_masivo').each(function() {
            const checkbox = $(this);
            const isChecked = checkbox.prop('checked'); 
            if (isChecked) {
            // console.log(checkbox[0].id)
            // console.log(checkbox[0].value);
            // tipo = checkbox[0].value.replace("cbx_registro_","");
            justificacion = checkbox[0].value.split("_");
            listado.push(justificacion);
            total_min+= parseInt(justificacion[3]);
        }
       
    });
    console.log(total_min);
     var total_h = minutosAHora(total_min);
     var i = listado.length;
     $('#txt_fecha_inicio').val(listado[0][2]);
     $('#txt_fecha_fin').val(listado[i-1][2]);
     $('#txt_horas_totales').val(total_h);


     $('#myModal_justificar').modal("show");
     console.log(listado);

}
function limpiar_listado()
{
    listado = [];
}
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Justificaciones</div>
            <?php
            //print_r($_SESSION['INICIO']);die(); 

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Registro
                        </li>
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

                            <div><i class="bx bxs-user me-1 font-22 text-primary"></i>
                            </div>
                            <h5 class="mb-0 text-primary">
                                <?php
                                if ($_id == '') {
                                    echo 'Registrar Justificaciones';
                                } else {
                                    echo 'Modificar Justificaciones';
                                }
                                ?>
                            </h5>

                            <div class="row m-2">
                                <div class="col-sm-12">
                                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_justificaciones"
                                        class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i>
                                        Regresar</a>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <form id="form_justificaciones">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-12">
                                        <label class="form-label" for="lbl_programar">Programar Horario </label>
                                        <div class="mb-col mt-2">
                                            <label for="txt_tipo_programar" class="form-label">Tipo de programación</label>
                                            <input type="text" id="txt_tipo_programar" class="form-control form-control-sm" readonly
                                                value="<?= ucfirst($tipo) ?>">
                                        </div>
                                        <label class="error" style="display: none;" for="txt_tipo_programar"></label>
                                    </div>
                                    <div class="row pt-3 mb-col" id="pnl_personas" style="display: none;">
                                        <div class="col-md-12">
                                            <label for="ddl_personas" class="form-label">Personas </label>
                                            <select class="form-select form-select-sm select2-validation" id="ddl_personas"
                                                name="ddl_personas" onchange="cargar_faltas()">
                                                <option selected disabled>-- Seleccione --</option>
                                            </select>
                                            <label class="error" style="display: none;" for="ddl_personas"></label>
                                        </div>
                                    </div>
                                    <div class="row pt-3 mb-col" id="pnl_departamentos" style="display: none;">
                                        <div class="col-md-12">
                                            <label for="ddl_departamentos" class="form-label">Departamentos </label>
                                            <select class="form-select form-select-sm select2-validation" id="ddl_departamentos"
                                                name="ddl_departamentos">
                                                <option selected disabled>-- Seleccione --</option>
                                            </select>
                                            <label class="error" style="display: none;" for="ddl_departamentos"></label>
                                        </div>
                                    </div>

                                    <hr class="w-100">
                                   
                                </div>              
                                <div class="col-md-6" id="pnl_registros_justificables">
                                    <div class="row mb-2">
                                        <label>Registro de faltas y atrasos</label>   
                                        <br>                                     
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <input type="date" name="txt_desde_reg" id="txt_desde_reg" value="<?php echo date('Y-m-d');?>" class="form-control form-control-sm">
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="date" name="txt_hasta_reg" id="txt_hasta_reg" value="<?php echo date('Y-m-d');?>" class="form-control form-control-sm">
                                        </div>
                                        <div class="col-sm-4 text-end">
                                           <button type="button" class="btn btn-primary btn-sm" onclick="cargar_faltas()" >Buscar</button>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="card-body">
                                            <ul class="nav nav-tabs nav-primary" role="tablist">
                                                <li class="nav-item" role="presentation">
                                                    <a class="nav-link active" data-bs-toggle="pill" href="#primary-pills-home" role="tab" aria-selected="false" tabindex="-1">
                                                        <div class="d-flex align-items-center">
                                                            <div class="tab-icon"><i class="bx bx-list-ul font-18 me-1"></i>
                                                            </div>
                                                            <div class="tab-title">Todos</div>
                                                        </div>
                                                    </a>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <a class="nav-link" data-bs-toggle="pill" href="#primary-pills-profile" role="tab" aria-selected="false" tabindex="-1">
                                                        <div class="d-flex align-items-center">
                                                            <div class="tab-icon"><i class="bx bx-time font-18 me-1"></i>
                                                            </div>
                                                            <div class="tab-title">Atrasos</div>
                                                        </div>
                                                    </a>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <a class="nav-link" data-bs-toggle="pill" href="#primary-pills-contact" role="tab" aria-selected="true">
                                                        <div class="d-flex align-items-center">
                                                            <div class="tab-icon"><i class="bx bx-user-x font-18 me-1"></i>
                                                            </div>
                                                            <div class="tab-title">Faltas</div>
                                                        </div>
                                                    </a>
                                                </li>
                                            </ul>
                                            <div class="tab-content" id="pills-tabContent">
                                                <div class="tab-pane fade  active show" id="primary-pills-home" role="tabpanel">
                                                    <div class="table-responsive">
                                                        <table class="table table-hover">
                                                            <thead>
                                                                <th class="text-center"><button class="btn btn-primary m-0 p-1 d-none" type="button" onclick="justificar_masivo()" id="btn_masivo"><i class="bx bx-calendar-edit me-0"></i></button></th>
                                                                <th></th>
                                                                <th>Dia</th>
                                                                <th>Fecha</th>
                                                                <th>Tiempo</th>
                                                                <th>Tipo</th>     
                                                            </thead>
                                                            <tbody id="tbl_all"></tbody>                                                       
                                                        </table>                                                        
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="primary-pills-profile" role="tabpanel">
                                                    <div class="table-responsive">
                                                        <table class="table table-hover">
                                                            <thead>
                                                                <th></th>
                                                                <th></th>
                                                                <th>Dia</th>
                                                                <th>Fecha</th>
                                                                <th>Tiempo</th>
                                                                <th>Tipo</th>     
                                                            </thead>
                                                            <tbody id="tbl_atrasos"></tbody>                                                       
                                                        </table>                                                        
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="primary-pills-contact" role="tabpanel">
                                                    <div class="table-responsive">
                                                        <table class="table table-hover">
                                                            <thead>
                                                                <th></th>
                                                                <th>Dia</th>
                                                                <th>Fecha</th>
                                                                <th>Tiempo</th>
                                                                <th>Tipo</th>     
                                                            </thead>
                                                            <tbody id="tbl_faltas"></tbody>                                                       
                                                        </table>                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>                  
                            </div>

                            

                            

                            

                            

                            

                            <div class="d-flex justify-content-end pt-2">

                                <?php if ($_id == '') { ?>
                                <button class="btn btn-success btn-sm px-4 m-0" onclick="editar_insertar()"
                                    type="button"><i class="bx bx-save"></i> Guardar</button>
                                <?php } else { ?>
                                <button class="btn btn-success btn-sm px-4 m-1" onclick="editar_insertar()"
                                    type="button"><i class="bx bx-save"></i> Editar</button>
                                <button class="btn btn-danger btn-sm px-4 m-1" onclick="delete_datos()" type="button"><i
                                        class="bx bx-trash"></i> Eliminar</button>
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


<div class="modal fade" id="myModal_justificar" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
            <h5>Justificar <span id="lbl_titulo"></span></h5>
        </div>
        <div class="modal-body">

            <form id="form_justificaciones_">
                <div class="row">
                    <input type="hidden" name="txt_tipo_just" id="txt_tipo_just">
                    <input type="hidden" name="txt_id_DepPer" id="txt_id_DepPer">
                    <div class="col-md-4">
                        <label for="txt_fecha_inicio" class="form-label">Fecha Inicial </label>
                        <input type="date" class="form-control form-control-sm"
                            id="txt_fecha_inicio" name="txt_fecha_inicio" maxlength="50" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="txt_fecha_fin" class="form-label">Fecha Final </label>
                        <input type="date" class="form-control form-control-sm" id="txt_fecha_fin"
                            name="txt_fecha_fin" maxlength="50" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="txt_horas_totales" class="form-label">Horas Totales</label>
                        <input type="time" class="form-control form-control-sm" id="txt_horas_totales"
                            name="txt_horas_totales" disabled>
                    </div>
                </div>

                <div class="row mb-col">
                    <div class="col-sm-12">
                        <label for="ddl_tipo_justificacion" class="form-label">Tipo de Horario </label>
                        <select class="form-control form-control-sm select2-validation"
                            name="ddl_tipo_justificacion" id="ddl_tipo_justificacion">
                            <option value="">Seleccione</option>
                        </select>
                        <label class="error" style="display: none;" for="ddl_tipo_justificacion"></label>
                    </div>
                </div>

                <div class="row mb-col">
                    <div class="col-md-12">
                        <label for="txt_motivo" class="form-label">Motivo </label>
                        <textarea class="form-control form-control-sm no_caracteres" name="txt_motivo"
                            id="txt_motivo" rows="3" maxlength="200"></textarea>
                    </div>
                </div>    
            </form>            
        </div>
        <div class="modal-footer"> 
            <?php if ($_id == '') { ?>
            <button class="btn btn-success btn-sm px-4 m-0" id="btn_guardar_normal" onclick="editar_insertar()" type="button">
                <i class="bx bx-save"></i> Guardar
            </button>

            <button class="btn btn-success btn-sm px-4 m-0 d-none" id="btn_guardar_rangos" onclick="editar_insertar_rangos();" type="button">
                    <i class="bx bx-save"></i> Guardar
            </button>
            <?php } else { ?>
            <button class="btn btn-success btn-sm px-4 m-1" onclick="editar_insertar()"
                type="button"><i class="bx bx-save"></i> Editar</button>
            <button class="btn btn-danger btn-sm px-4 m-1" onclick="delete_datos()" type="button"><i
                    class="bx bx-trash"></i> Eliminar</button>
            <?php } ?>
                            
            <button type="button" class="btn btn-secondary  btn-sm px-4 m-1" data-bs-dismiss="modal" onclick="limpiar_listado()">Cerrar</button>       
        </div>
      </div>
    </div>
</div>

<script>
//Validacion de formulario
$(document).ready(function() {
    // Selecciona el label existente y añade el nuevo label

    agregar_asterisco_campo_obligatorio('txt_fecha_inicio');
    agregar_asterisco_campo_obligatorio('txt_fecha_fin');
    agregar_asterisco_campo_obligatorio('ddl_personas');
    agregar_asterisco_campo_obligatorio('ddl_departamentos');
    agregar_asterisco_campo_obligatorio('ddl_tipo_justificacion');
    agregar_asterisco_campo_obligatorio('lbl_programar');

    //Para validar los select2
    $(".select2-validation").on("select2:select", function(e) {
        unhighlight_select(this);
    });

    $("#form_justificaciones").validate({
        rules: {
            txt_fecha_inicio: {
                required: true,
            },
            txt_fecha_fin: {
                required: true,
            },
            ddl_tipo_justificacion: {
                required: true,
            },
            txt_tipo_programar: {
                required: true,
            },
            txt_motivo: {
                required: true,
            }
        },
        messages: {
            ddl_personas: {
                required: "El campo 'Persona' es obligatorio",
            },
            ddl_departamentos: {
                required: "El campo 'Departamento' es obligatorio",
            },
        },

        highlight: function(element) {
            let $element = $(element);

            if ($element.hasClass("select2-hidden-accessible")) {
                // Elimina la clase 'is-invalid' y agrega 'is-valid' al contenedor correcto de select2
                $element.next(".select2-container").find(".select2-selection").removeClass(
                    "is-valid").addClass("is-invalid");
            } else if ($element.is(':radio')) {
                // Si es un radio button, aplicar la clase al grupo de radios (al contenedor padre si existe)
                $('input[name="' + $element.attr("name") + '"]').addClass("is-invalid").removeClass(
                    "is-valid");
            } else {
                // Elimina la clase 'is-invalid' y agrega 'is-valid' al input normal
                $element.removeClass("is-valid").addClass("is-invalid");
            }
        },

        unhighlight: function(element) {
            let $element = $(element);

            if ($element.hasClass("select2-hidden-accessible")) {
                // Para Select2, elimina 'is-invalid' y agrega 'is-valid' en el contenedor adecuado
                $element.next(".select2-container").find(".select2-selection").removeClass(
                    "is-invalid").addClass("is-valid");
            } else if ($element.is(':radio')) {
                // Si es un radio button, marcar todo el grupo como válido
                $('input[name="' + $element.attr("name") + '"]').removeClass("is-invalid").addClass(
                    "is-valid");
            } else {
                // Para otros elementos normales
                $element.removeClass("is-invalid").addClass("is-valid");
            }
        }
    });


    $("#form_justificaciones_").validate({
        rules: {
            txt_fecha_inicio: {
                required: true,
            },
            txt_fecha_fin: {
                required: true,
            },
            ddl_tipo_justificacion: {
                required: true,
            },
            txt_tipo_programar: {
                required: true,
            },
            txt_motivo: {
                required: true,
            }
        },
        messages: {
            ddl_personas: {
                required: "El campo 'Persona' es obligatorio",
            },
            ddl_departamentos: {
                required: "El campo 'Departamento' es obligatorio",
            },
        },

        highlight: function(element) {
            let $element = $(element);

            if ($element.hasClass("select2-hidden-accessible")) {
                // Elimina la clase 'is-invalid' y agrega 'is-valid' al contenedor correcto de select2
                $element.next(".select2-container").find(".select2-selection").removeClass(
                    "is-valid").addClass("is-invalid");
            } else if ($element.is(':radio')) {
                // Si es un radio button, aplicar la clase al grupo de radios (al contenedor padre si existe)
                $('input[name="' + $element.attr("name") + '"]').addClass("is-invalid").removeClass(
                    "is-valid");
            } else {
                // Elimina la clase 'is-invalid' y agrega 'is-valid' al input normal
                $element.removeClass("is-valid").addClass("is-invalid");
            }
        },

        unhighlight: function(element) {
            let $element = $(element);

            if ($element.hasClass("select2-hidden-accessible")) {
                // Para Select2, elimina 'is-invalid' y agrega 'is-valid' en el contenedor adecuado
                $element.next(".select2-container").find(".select2-selection").removeClass(
                    "is-invalid").addClass("is-valid");
            } else if ($element.is(':radio')) {
                // Si es un radio button, marcar todo el grupo como válido
                $('input[name="' + $element.attr("name") + '"]').removeClass("is-invalid").addClass(
                    "is-valid");
            } else {
                // Para otros elementos normales
                $element.removeClass("is-invalid").addClass("is-valid");
            }
        }
    });
});
</script>