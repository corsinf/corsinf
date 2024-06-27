/**********************************************************************************************
 *  Tabla para generar una lista de avaluos a la tabla PLANILLA_MASIVA
 * 
 * 
 */

//insertar AVALUOS

function insertarAvaluos(txt_valor_art, txt_obs_art) {

    var parametros = {
        'txt_id_art_avaluo': $('#txt_id_art_avaluo').val(),
        'txt_valor_art': txt_valor_art,
        'txt_obs_art': txt_obs_art,
    };

    insertarDetalleAvaluo(parametros);
}

function insertarDetalleAvaluo(parametros) {

    $.ajax({
        data: {
            parametros: parametros
        },
        url: '../controlador/avaluo_articuloC.php?insertar=true',
        type: 'post',
        dataType: 'json',

        success: function (response) {
            if (response == 1) {
                //Swal.fire('', 'Operacion realizada con exito.', 'success');
                cargarAvaluo($('#txt_id_art_avaluo').val());
            } else if (response == -2) {
                Swal.fire('', 'Algo salió mal, repita el proceso.', 'error');
            }
            //console.log(response);
        }
    });
}


$(document).ready(function () {

    $(document).on('click', '#agregarFila', function () {

        let txt_valor_art = $("#txt_valor_art").val();
        let txt_obs_art = $("#txt_obs_art").val();

        if (txt_valor_art) {

            insertarAvaluos(txt_valor_art, txt_obs_art);
            limpiar();
        } else {
            Swal.fire('', 'Campo valor vacío', 'error');
        }

    });

});

function eliminarAvaluo(id_item) {

    $.ajax({
        data: {
            id: id_item
        },
        url: '../controlador/avaluo_articuloC.php?eliminar=true',
        type: 'post',
        dataType: 'json',
        success: function (response) {
            console.log(response);

        }
    });
}

function cargarAvaluo(id) {
    console.log('aqui estoy');
    $.ajax({
        data: {
            id: id
        },
        url: '../controlador/avaluo_articuloC.php?listarTabla=true',
        type: 'post',
        dataType: 'json',
        success: function (response) {

            $('#lista_avaluos tbody').empty();

            $('#lista_avaluos tbody').append(response);

        }
    });
}


function limpiar() {
    $("#txt_valor_art").val('');
    $("#txt_obs_art").val('');
}