////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//Para la tabla medicamentos e insumos
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
var count_medicamento = 0;

function insertarMedicamentos(valor) {

    var valor_selecct = $("#tipo_farmacologia").val();
    var tabla = '';
    var prefijo = '';

    if (valor_selecct === 'medicamentos') {
        tabla = 'medicamentos';
        prefijo = 'sa_cmed';
    } else if (valor_selecct === 'insumos') {
        tabla = 'insumos';
        prefijo = 'sa_cins';
    }

    $.ajax({
        data: {
            id: valor
        },
        url: '../controlador/' + tabla + 'C.php?listar=true',
        type: 'post',
        dataType: 'json',
        success: function (response) {
            //console.log(response);

            $('#lista_medicamentos tr:last-child label[id^="sa_det_conp_nombre_temp_"]').text(response[0][prefijo + '_presentacion']);

            $('#lista_medicamentos tr:last-child input[id^="sa_det_conp_id_cmed_cins_"]').val(response[0][prefijo + '_id']);
            $('#lista_medicamentos tr:last-child input[id^="sa_det_conp_tipo_"]').val(response[0][prefijo + '_tipo']);
            $('#lista_medicamentos tr:last-child input[id^="sa_det_conp_nombre_"]').val(response[0][prefijo + '_presentacion']);

        }
    });
}

$(document).ready(function () {

    $(document).on('click', '#agregarFila_medicamentos', function () {

        var stock = $('#stock_farmacologia').val();
        var cant = $('#cantidad_farmacologia').val();
        //alert(parseFloat(stock))
        if (cant == '' || cant < 0) { Swal.fire('', 'Ingrese un valor valido', 'info'); return false; }

        //if (parseFloat(cant) > parseFloat(stock) && parseFloat(stock) === 0) { Swal.fire('', 'Valor supera al stock', 'info'); return false; }

        if ((parseFloat(cant) > parseFloat(stock)) || stock == 0) {
            Swal.fire('', 'Valor supera al stock', 'info');
            return false;
        }

        var farmaco = $("#tipo_farmacologia_presentacion option:selected").text();
        var farmaco = farmaco.split('-');
        // console.log(farmaco[0].trim())
        var existe = buscar_medicamento_existente(farmaco[0].trim())
        if (existe) {
            Swal.fire('Este farmaco ya esta registrado', 'Modifique el ya existente', 'error');
            return false;
        }

        // console.log(existe);


        if ($("#tipo_farmacologia_presentacion").val()) {
            count_medicamento++;

            var htmlFila = '<tr>';
            htmlFila += '<input type="hidden" name="sa_det_conp_id[]" id="sa_det_conp_id_' + count_medicamento + '">';

            htmlFila += '<td><input class="itemFila_Medicamento" type="checkbox"></td>';
            htmlFila += '<td><label id="sa_det_conp_nombre_temp_' + count_medicamento + '"></label></td>';
            htmlFila += '<td><input type="text" class="form-control form-control-sm" id="sa_det_conp_dosificacion_' + count_medicamento + '" name="sa_det_conp_dosificacion[]"></td>';
            htmlFila += '<td><input disabled type="number" class="form-control form-control-sm solo_numeros_int" min="1" id="sa_det_conp_cantidad_' + count_medicamento + '" name="sa_det_conp_cantidad[]" onblur="limitarMaximo(' + count_medicamento + ')" max="' + stock + '" value="' + cant + '"></td>';

            htmlFila += '<td><div class="form-check d-flex justify-content-center">';
            htmlFila += '<input class="form-check-input" type="checkbox" value="" id="sa_det_conp_estado_entrega_' + count_medicamento + '" name="sa_det_conp_estado_entrega_' + count_medicamento + '" checked>';
            htmlFila += '<label class="form-check-label" for="sa_det_conp_estado_entrega_' + count_medicamento + '"></label>';
            htmlFila += '</div></td>';

            htmlFila += '<input type="hidden" id="sa_det_conp_nombre_' + count_medicamento + '" name="sa_det_conp_nombre[]">';
            htmlFila += '<input type="hidden" id="sa_det_conp_id_cmed_cins_' + count_medicamento + '" name="sa_det_conp_id_cmed_cins[]">';
            htmlFila += '<input type="hidden" id="sa_det_conp_tipo_' + count_medicamento + '" name="sa_det_conp_tipo[]">';

            htmlFila += '<input type="hidden" name="medicamentos[]" id="medicamentos_' + count_medicamento + '">';

            htmlFila += '</tr>';

            $('#lista_medicamentos').append(htmlFila);

            var valor = $("#tipo_farmacologia_presentacion").val();
            insertarMedicamentos(valor);
            limpiar();
        } else {
            Swal.fire('', 'Campos vacíos', 'error');
        }
    });

    function buscar_medicamento_existente(texto) {
        var searchText = texto.toLowerCase();
        var encontrado = false;
        $('#lista_medicamentos tbody tr').each(function () {
            $(this).find('td label').each(function () {
                var cellText = $(this).text().toLowerCase();
                if (cellText.indexOf(searchText) !== -1) {
                    encontrado = true;
                    return false; // Sale del bucle each interno
                }
            });

            if (encontrado) {
                return false; // Sale del bucle each externo
            }
        });

        return encontrado;
    }


    $(document).on('click', '#checkAll_Medicamentos', function () {
        $(".itemFila_Medicamento").prop("checked", this.checked);
    });

    $(document).on('click', '.itemFila_Medicamento', function () {
        if ($('.itemFila_Medicamento:checked').length == $('.itemFila_Medicamento').length) {
            $('#checkAll_Medicamentos').prop('checked', true);
        } else {
            $('#checkAll_Medicamentos').prop('checked', false);
        }
    });

    $(document).on('click', '#eliminarFila_medicamentos', function () {
        $(".itemFila_Medicamento:checked").each(function () {
            var fila = $(this).closest('tr');
            var sa_det_conp_id_fila = fila.find('input[id^="sa_det_conp_id_"]').val();

            // Verificar si existe sa_det_conp_id antes de realizar la eliminación en la base de datos
            if (sa_det_conp_id_fila) {
                // Llamar a la función de eliminación en tu backend
                eliminar_det_consulta_item(sa_det_conp_id_fila);
            }

            for (let i = 1; i <= count_medicamento; i++) {
                let medicamento1 = document.getElementById("medicamentos_" + i);
                medicamento1.id = "medicamentos_0";
            }

            fila.remove();
            count_medicamento--;

            for (let i = 1; i <= count_medicamento; i++) {
                let medicamento2 = document.getElementById("medicamentos_0");
                medicamento2.id = "medicamentos_" + i;
            }
        });

        $('#checkAll_Medicamentos').prop('checked', false);
    });
});

function limpiar() {
    $('#stock_farmacologia').val('');
    $('#cantidad_farmacologia').val('');
    $('#tipo_farmacologia').val('');
    var select = $('#tipo_farmacologia_presentacion');

    // Verificar si el select está usando Select2 antes de destruir
    if (select.hasClass('select2-hidden-accessible')) {
        // Destruir la instancia de Select2
        select.select2('destroy');
    }

    // Agrega la opción predeterminada
    select.html('<option selected disabled> -- Selecciona una opción -- </option>');

    $('#txt_indicaciones_jarabe').text('');
    $('#txt_indicaciones_jarabe').hide();
}

function eliminar_det_consulta_item(id_item) {

    $.ajax({
        data: {
            id: id_item
        },
        url: '../controlador/det_consultaC.php?eliminar=true',
        type: 'post',
        dataType: 'json',
        success: function (response) {
            //console.log(response);

        }
    });
}

function consultar_medicinas_insumos(entrada) {
    //console.log(entrada);

    var selectElement = $('#tipo_farmacologia_presentacion');

    // Intenta destruir la instancia de Select2 si ya existe
    if (selectElement.hasClass('select2-hidden-accessible')) {
        selectElement.select2('destroy');
    }

    if (entrada === 'medicamentos') {
        $('#tipo_farmacologia_presentacion').select2({
            placeholder: '-- Selecciona una opción --',
            language: {
                inputTooShort: function () {
                    return "Por favor ingresa 1 o más caracteres";
                },
                noResults: function () {
                    return "No se encontraron resultados";
                },
                searching: function () {
                    return "Buscando...";
                },
                errorLoading: function () {
                    return "No se encontraron resultados";
                }
            },
            minimumInputLength: 1,
            ajax: {
                url: '../controlador/medicamentosC.php?listar_todo=true',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term // Envía el término de búsqueda al servidor
                    };
                },
                processResults: function (data, params) { // Agrega 'params' como parámetro
                    var searchTerm = params.term.toLowerCase();

                    var options = data.reduce(function (filtered, item) {

                        var fullName = item['sa_cmed_presentacion'] + " - " + item['sa_cmed_nombre_comercial'];

                        if (fullName.toLowerCase().includes(searchTerm)) {
                            filtered.push({
                                id: item['sa_cmed_id'],
                                text: fullName,
                                data: item,
                            });
                        }

                        return filtered;
                    }, []);

                    return {
                        results: options
                    };
                },
                cache: true
            }
        })
            .off('select2:select')
            .on('select2:select', function (e) {
                $('#txt_indicaciones_jarabe').text('');
                $('#txt_indicaciones_jarabe').hide();

                var data = e.params.data.data;

                $('#sa_det_fice_id_cmed_cins').val(data.sa_cmed_id);
                $('#sa_det_fice_tipo').val(data.sa_cmed_tipo);
                $('#stock_farmacologia').val(data.sa_cmed_stock);

                listar_farmacos_alergia();

                if (data.sa_cmed_es_jarabe == '1') {
                    let resultado = '';
                    if (data.sa_cmed_formula == 'formula_ibuprofeno_paracetamol') {
                        let peso = $('#sa_conp_peso').val();
                        if (peso != '') {
                            let sa_cmed_mg = data.sa_cmed_mg;
                            let sa_cmed_dosis = data.sa_cmed_dosis;
                            let sa_cmed_concentracion = data.sa_cmed_concentracion;

                            resultado = formula_ibuprofeno_paracetamol(peso, sa_cmed_concentracion, sa_cmed_dosis, sa_cmed_mg);
                            $('#txt_indicaciones_jarabe').text('Debe tomar: ' + resultado + ' mg');
                            $('#txt_indicaciones_jarabe').show();
                        } else {
                            Swal.fire('', 'No hay peso para realizar el cálculo.', 'error');
                        }

                    } else if (data.sa_cmed_formula == 'formula_loratadina_levocetirizina') {
                        let peso = $('#sa_conp_peso').val();
                        if (peso != '') {
                            resultado = formula_loratadina_levocetirizina(peso);
                            $('#txt_indicaciones_jarabe').text('Debe tomar: ' + resultado + ' mg');
                            $('#txt_indicaciones_jarabe').show();
                        } else {
                            Swal.fire('', 'No hay peso para realizar el cálculo.', 'error');
                        }
                    }

                }


                //console.log(data);
            });

    } else if (entrada === 'insumos') {
        $('#tipo_farmacologia_presentacion').select2({
            placeholder: '-- Selecciona una opción --',
            language: {
                inputTooShort: function () {
                    return "Por favor ingresa 1 o más caracteres";
                },
                noResults: function () {
                    return "No se encontraron resultados";
                },
                searching: function () {
                    return "Buscando...";
                },
                errorLoading: function () {
                    return "No se encontraron resultados";
                }
            },
            minimumInputLength: 1,
            ajax: {
                url: '../controlador/insumosC.php?listar_todo=true',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term // Envía el término de búsqueda al servidor
                    };
                },
                processResults: function (data, params) { // Agrega 'params' como parámetro
                    var searchTerm = params.term.toLowerCase();

                    var options = data.reduce(function (filtered, item) {

                        var fullName = item['sa_cins_presentacion'] + " - " + item['sa_cins_nombre_comercial'];

                        if (fullName.toLowerCase().includes(searchTerm)) {
                            filtered.push({
                                id: item['sa_cins_id'],
                                text: fullName,
                                data: item,
                            });
                        }

                        return filtered;
                    }, []);

                    return {
                        results: options
                    };
                },
                cache: true
            }
        })
            .off('select2:select')
            .on('select2:select', function (e) {
                var data = e.params.data.data;

                $('#sa_det_fice_id_cmed_cins').val(data.sa_cins_id);
                $('#sa_det_fice_tipo').val(data.sa_cins_tipo);
                $('#stock_farmacologia').val(data.sa_cins_stock);

                listar_farmacos_alergia()


                //console.log(data);
            });
    }

    $('#tipo_farmacologia_presentacion').val(null).trigger('change');
}

function cargar_farmacologia(id_consulta) {
    $.ajax({
        data: {
            id_consulta: id_consulta
        },
        url: '../controlador/det_consultaC.php?listar_consulta=true',
        type: 'post',
        dataType: 'json',
        success: function (response) {
            //console.log(response);

            // Verificar si la respuesta contiene datos
            if (response && response.length > 0) {
                response.forEach(function (medicamento) {

                    //console.log(medicamento);
                    count_medicamento++;

                    var htmlFila = '<tr>';
                    htmlFila += '<input type="hidden" value="' + medicamento.sa_det_conp_id + '" name="sa_det_conp_id[]" id="sa_det_conp_id_' + count_medicamento + '">';

                    htmlFila += '<td><input class="itemFila_Medicamento" type="checkbox"></td>';
                    htmlFila += '<td><label id="sa_det_conp_nombre_temp_' + count_medicamento + '">' + medicamento.sa_det_conp_nombre + '</label></td>';
                    htmlFila += '<td><input type="text" class="form-control form-control-sm" id="sa_det_conp_dosificacion_' + count_medicamento + '" name="sa_det_conp_dosificacion[]" value="' + medicamento.sa_det_conp_dosificacion + '"></td>';
                    htmlFila += '<td><input disabled type="number" class="form-control form-control-sm solo_numeros_int" min="1" id="sa_det_conp_cantidad_' + count_medicamento + '" name="sa_det_conp_cantidad[]" value="' + medicamento.sa_det_conp_cantidad + '"></td>';

                    var checked = medicamento.sa_det_conp_estado_entrega === 1 ? 'checked' : '';
                    htmlFila += '<td>';
                    htmlFila += '<div class="form-check d-flex justify-content-center">';
                    htmlFila += '<input class="form-check-input" type="checkbox" name="sa_det_conp_estado_entrega_' + count_medicamento + '" id="sa_det_conp_estado_entrega_' + count_medicamento + '" ' + checked + '>';
                    htmlFila += '<label class="form-check-label" for="sa_det_conp_estado_entrega_' + count_medicamento + '"></label>';
                    htmlFila += '</div>';
                    htmlFila += '</td>';

                    htmlFila += '<input type="hidden" value="' + medicamento.sa_det_conp_nombre + '" name="sa_det_conp_nombre[]" id="sa_det_conp_nombre_' + count_medicamento + '">';
                    htmlFila += '<input type="hidden" value="' + medicamento.sa_det_conp_id_cmed_cins + '" name="sa_det_conp_id_cmed_cins[]" id="sa_det_conp_id_cmed_cins_' + count_medicamento + '">';
                    htmlFila += '<input type="hidden" value="' + medicamento.sa_det_conp_tipo + '" name="sa_det_conp_tipo[]" id="sa_det_conp_tipo_' + count_medicamento + '">';

                    htmlFila += '<input type="hidden" name="medicamentos[]" id="medicamentos_' + count_medicamento + '">';

                    htmlFila += '</tr>';

                    $('#lista_medicamentos').append(htmlFila);
                });
            }

        }
    });
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//Fin medicamentos
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


//Funciones para la consulta
function consulta_hora_desde() {
    // Obtener la hora actual
    var ahora = new Date();

    // Formatear la hora y minutos con ceros a la izquierda si es necesario
    var horas = ahora.getHours().toString().padStart(2, '0');
    var minutos = ahora.getMinutes().toString().padStart(2, '0');

    $('#sa_conp_desde_hora').val(horas + ':' + minutos);
    // $('#sa_conp_hora_permiso_salida').val(horas + ':' + minutos);

}

function calcular_diferencia_fecha() {
    var fechaDesde = new Date($('#sa_conp_fecha_inicio_falta_certificado').val());
    var fechaHasta = new Date($('#sa_conp_fecha_fin_alta_certificado').val());

    // Calcular la diferencia en milisegundos
    var diferenciaEnMs = fechaHasta - fechaDesde;

    // Validar que la fecha de inicio sea menor o igual a la fecha de fin
    if (diferenciaEnMs >= 0) {
        // Calcular la diferencia en días
        var diferenciaEnDias = Math.floor(diferenciaEnMs / (1000 * 60 * 60 * 24));

        // Mostrar la diferencia en el campo de texto
        $('#sa_conp_dias_permiso_certificado').val(diferenciaEnDias + 1);
    } else {
        $('#sa_conp_dias_permiso_certificado').val('NaN');
    }
}

function calcular_diferencia_hora() {
    var horaDesde = $('#sa_conp_desde_hora').val();
    var horaHasta = $('#sa_conp_hasta_hora').val();

    var fechaBase = new Date('2000-01-01');
    var fechaDesde = new Date(fechaBase.toDateString() + ' ' + horaDesde);
    var fechaHasta = new Date(fechaBase.toDateString() + ' ' + horaHasta);

    var diferenciaEnMs = fechaHasta - fechaDesde;

    if (diferenciaEnMs >= 0) {
        var diferenciaEnMinutos = Math.floor(diferenciaEnMs / (1000 * 60));
        $('#sa_conp_tiempo_aten').val(diferenciaEnMinutos);
    } else {
        //Swal.fire('', 'La hora Hasta de la consulta no puede ser menor', 'info');
        $('#sa_conp_hasta_hora').val('');
        $('#sa_conp_tiempo_aten').val('NaN');
    }
}

function calcular_diferencia_hora_retorno() {
    var horaDesde = $('#sa_conp_desde_hora').val();
    var horaHasta = $('#sa_conp_hasta_hora').val();

    var fechaBase = new Date('2000-01-01');
    var fechaDesde = new Date(fechaBase.toDateString() + ' ' + horaDesde);
    var fechaHasta = new Date(fechaBase.toDateString() + ' ' + horaHasta);

    var diferenciaEnMs = fechaHasta - fechaDesde;

    if (diferenciaEnMs >= 0) {
        var diferenciaEnMinutos = Math.floor(diferenciaEnMs / (1000 * 60));
        $('#sa_conp_tiempo_aten').val(diferenciaEnMinutos);
        return diferenciaEnMinutos;
    }
}

function obtener_hora_hasta() {
    ahora = new Date();
    horas = ahora.getHours().toString().padStart(2, '0');
    minutos = ahora.getMinutes().toString().padStart(2, '0');
    return `${horas}:${minutos}`;
}

//Consultar alergia farmacos
function listar_farmacos_alergia() {

    var sa_det_fice_id_cmed_cins = $('#sa_det_fice_id_cmed_cins').val();
    var sa_det_fice_tipo = $('#sa_det_fice_tipo').val();

    // Llamar a la función insertar_detalle_fm
    var parametros = {
        'sa_fice_id': $('#sa_fice_id').val(),
        'sa_det_fice_id_cmed_cins': sa_det_fice_id_cmed_cins,
        'sa_det_fice_tipo': sa_det_fice_tipo,
    };

    //console.log(parametros);


    $.ajax({
        data: {
            parametros: parametros
        },
        url: '../controlador/detalle_fm_med_insC.php?fm_farmaco_a=true',
        type: 'post',
        dataType: 'json',
        success: function (response) {

            //console.log(response);
            if (response && response.length > 0) {
                Swal.fire('', 'Tiene alergia a este fármaco.', 'error');
                limpiar();
            }
        }
    });
}

/**
 * 
 * 
 * Formulas para cuando son jarabes y toca calcular
 * 
 */

function formula_ibuprofeno_paracetamol(peso, concentracion, dosis, mg) {
    peso = parseFloat(peso);
    concentracion = parseFloat(concentracion);
    dosis = parseFloat(dosis);
    mg = parseFloat(mg);

    let formula = (peso * dosis * concentracion) / mg;
    return parseFloat(formula.toFixed(2));
}


function formula_loratadina_levocetirizina(peso) {
    peso = parseFloat(peso);

    let formula = peso * 0.2
    return parseFloat(formula.toFixed(2));
}


