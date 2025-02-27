

function smartwizard_ficha_medica() {
    var btnSiguiente = $('<button></button>').text('Siguiente').addClass('btn btn-info').on('click', function () {
        if (valida_formulario()) {
            $('#smartwizard_fm').smartWizard("next");
        } else {
            Swal.fire('', 'Llene todo los campos', 'info')
        }
    });
    var btnAtras = $('<button></button>').text('Atras').addClass('btn btn-info').on('click', function () {
        $('#smartwizard_fm').smartWizard("prev");
        return true;
    });


    $("#smartwizard_fm").on("showStep", function (e, anchorObject, stepNumber, stepDirection, stepPosition) {
        $("#prev-btn").removeClass('disabled');
        $("#next-btn").removeClass('disabled');
        if (stepPosition === 'first') {
            $("#prev-btn").addClass('disabled');
        } else if (stepPosition === 'last') {
            $("#next-btn").addClass('disabled');
        } else {
            $("#prev-btn").removeClass('disabled');
            $("#next-btn").removeClass('disabled');
        }
    });
    // Smart Wizard
    $('#smartwizard_fm').smartWizard({
        selected: 0,
        theme: 'arrows',
        transition: {
            animation: 'slide-horizontal', // Effect on navigation, none/fade/slide-horizontal/slide-vertical/slide-swing
        },
        toolbarSettings: {
            toolbarPosition: '',
            toolbarExtraButtons: [btnAtras, btnSiguiente],
            showNextButton: false,  // Oculta el botón predeterminado "Next"
            showPreviousButton: false,
        },
    });
}

//Para que funcione los radio button para las preguntas en la ficha medica 
function preguntas_ficha_medica() {
    //Opciones para las preguntas de la ficha tecnica////////////////////////////////////////////////

    $('input[name=sa_fice_pregunta_1]').change(function () {
        if ($(this).val() === 'Si') {
            $('#sa_fice_pregunta_1_obs').show();
        } else if ($(this).val() === 'No') {
            $('#sa_fice_pregunta_1_obs').hide();
            $('#sa_fice_pregunta_1_obs').val('');
        }
    });

    $('input[name=sa_fice_pregunta_2]').change(function () {
        if ($(this).val() === 'Si') {
            $('#sa_fice_pregunta_2_obs').show();
        } else if ($(this).val() === 'No') {
            $('#sa_fice_pregunta_2_obs').hide();
            $('#sa_fice_pregunta_2_obs').val('');
        }
    });

    $('input[name=sa_fice_pregunta_3]').change(function () {
        if ($(this).val() === 'Si') {
            $('#sa_fice_pregunta_3_obs').show();
        } else if ($(this).val() === 'No') {
            $('#sa_fice_pregunta_3_obs').hide();
            $('#sa_fice_pregunta_3_obs').val('');
        }
    });

    $('input[name=sa_fice_pregunta_4]').change(function () {
        if ($(this).val() === 'Si') {
            $('#sa_fice_pregunta_4_obs').show();
        } else if ($(this).val() === 'No') {
            $('#sa_fice_pregunta_4_obs').hide();
            $('#sa_fice_pregunta_4_obs').val('');
        }
    });

    $('#sa_fice_est_seguro_medico').change(function () {
        if ($(this).val() === 'Si') {
            $('#sa_fice_est_nombre_seguro_div').show();
        } else if ($(this).val() === 'No') {
            $('#sa_fice_est_nombre_seguro_div').hide();
            $('#sa_fice_est_nombre_seguro').val('');
        }
    });

    $('input[name=sa_fice_medicamentos_alergia]').change(function () {
        if ($(this).val() === 'Si') {
            $('#pnl_farmacologia').show();
            ajustarAlturaContenedor();
            consultar_medicinas_insumos('medicamentos');

        } else if ($(this).val() === 'No') {
            $('#pnl_farmacologia').hide();
            ajustarAlturaContenedor();

            //$('#sa_fice_pregunta_4_obs').val('');
        }
    });

    //////////////////////////////////////////////////
}


function recargar_pag() {

    var sa_pac_id = '';
    var sa_pac_tabla = '';

    if (sa_pac_id == '' && sa_pac_tabla == '') {
        if (localStorage.getItem("sa_pac_id") !== null) {
            sa_pac_id = localStorage.getItem("sa_pac_id");
        }
        if (localStorage.getItem("sa_pac_tabla") !== null) {
            sa_pac_tabla = localStorage.getItem("sa_pac_tabla");
        }
        if (localStorage.getItem("btn_regresar") !== null) {
            btn_regresar = localStorage.getItem("btn_regresar");
        }

        //console.log(sa_pac_id);
        // console.log(sa_pac_tabla);

        if (sa_pac_id != '' && sa_pac_tabla != '') {
            var form = document.createElement('form');
            form.method = 'post';
            form.action = '../vista/inicio.php?mod=7&acc=ficha_medica_pacientes';
            // Función para agregar un campo oculto al formulario
            function agregarCampo(nombre, valor) {
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = nombre;
                input.value = valor;
                form.appendChild(input);
            }

            // Agregar campos al formulario
            agregarCampo('sa_pac_id', sa_pac_id);
            agregarCampo('sa_pac_tabla', sa_pac_tabla);
            agregarCampo('btn_regresar', btn_regresar);
            document.body.appendChild(form);
            form.submit();

        } else {
            Swal.fire('', 'Pagina no encontrada', 'error')
        }
    }
}

function valida_formulario() {
    var pasoActual = $('#smartwizard_fm').smartWizard('getStepIndex');
    var pasoValido = true;
    // Verificar campos requeridos en el paso actual

    $('#smartwizard_fm [data-step="' + pasoActual + '"] [required]').each(function () {
        //console.log(this)
        if (!this.checkValidity()) {
            pasoValido = false;
            num_form = pasoActual + 1;
            $('#form-step-' + num_form).addClass('was-validated');
            console.log()
            return false; // Salir del bucle si se encuentra un campo no válido
        }
    });

    return pasoValido;
}


/**********************************************************************************************
 *  Tabla para generar una lista de medicamentos que es alergico el paciente
 * 
 * 
 */

//insertar medicamentos e insumos

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
        url: '../controlador/SALUD_INTEGRAL/' + tabla + 'C.php?listar=true',
        type: 'post',
        dataType: 'json',
        success: function (response) {
            var sa_det_fice_id_cmed_cins = $('#sa_det_fice_id_cmed_cins').val();
            var sa_det_fice_nombre = $('#sa_det_fice_nombre').val();
            var sa_det_fice_tipo = $('#sa_det_fice_tipo').val();

            // Llamar a la función insertar_detalle_fm
            var parametros = {

                'sa_fice_id': $('#sa_fice_id').val(),
                'sa_det_fice_nombre': sa_det_fice_nombre,
                'sa_det_fice_id_cmed_cins': sa_det_fice_id_cmed_cins,
                'sa_det_fice_tipo': sa_det_fice_tipo,

            };

            insertar_detalle_fm(parametros);
        }
    });
}

function insertar_detalle_fm(parametros) {

    $.ajax({
        data: {
            parametros: parametros
        },
        url: '../controlador/SALUD_INTEGRAL/detalle_fm_med_insC.php?insertar=true',
        type: 'post',
        dataType: 'json',

        success: function (response) {
            if (response == 1) {
                //Swal.fire('', 'Operacion realizada con exito.', 'success');
                cargar_farmacologia_fm($('#sa_fice_id').val());
            } else if (response == -2) {
                Swal.fire('', 'Algo salió mal, repite el proceso.', 'error');
            }
            //console.log(response);
        }
    });
}


$(document).ready(function () {

    $(document).on('click', '#agregarFila_medicamentos', function () {


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

            var valor = $("#tipo_farmacologia_presentacion").val();
            insertarMedicamentos(valor);
            limpiar();
        } else {
            Swal.fire('', 'Campos vacíos', 'error');
        }

        ajustarAlturaContenedor();
    });


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
            var count_medicamento = 0;

            // Verificar si existe sa_det_conp_id antes de realizar la eliminación en la base de datos
            if (sa_det_conp_id_fila) {
                // Llamar a la función de eliminación en tu backend
                eliminar_det_fm_item(sa_det_conp_id_fila);
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

function eliminar_det_fm_item(id_item) {

    $.ajax({
        data: {
            id: id_item
        },
        url: '../controlador/SALUD_INTEGRAL/detalle_fm_med_insC.php?eliminar=true',
        type: 'post',
        dataType: 'json',
        success: function (response) {
            console.log(response);

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
            //minimumInputLength: 1,
            ajax: {
                url: '../controlador/SALUD_INTEGRAL/medicamentosC.php?listar_todo=true',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term || '' // Envía el término de búsqueda al servidor
                    };
                },
                processResults: function (data, params) { // Agrega 'params' como parámetro
                    var searchTerm = (params.term || '').toLowerCase();

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
        }).on('select2:select', function (e) {
            var data = e.params.data.data;

            $('#sa_det_fice_id_cmed_cins').val(data.sa_cmed_id);
            $('#sa_det_fice_nombre').val(data.sa_cmed_presentacion);
            $('#sa_det_fice_tipo').val(data.sa_cmed_tipo);

            //console.log();
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
                url: '../controlador/SALUD_INTEGRAL/insumosC.php?listar_todo=true',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term || ''// Envía el término de búsqueda al servidor
                    };
                },
                processResults: function (data, params) { // Agrega 'params' como parámetro
                    var searchTerm = (params.term || '').toLowerCase();

                    var options = data.reduce(function (filtered, item) {

                        var fullName = item['sa_cins_presentacion'] + " - " + item['sa_cins_codigo'] + " - " + item['sa_cins_localizacion'];

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
        }).on('select2:select', function (e) {
            var data = e.params.data.data;

            $('#sa_det_fice_id_cmed_cins').val(data.sa_cins_id);
            $('#sa_det_fice_nombre').val(data.sa_cins_presentacion);
            $('#sa_det_fice_tipo').val(data.sa_cins_tipo);

            //console.log();
        });
    }

    $('#tipo_farmacologia_presentacion').val(null).trigger('change');
}

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

function cargar_farmacologia_fm(id) {
    var count_medicamento = 0;
    $.ajax({
        data: {
            id: id
        },
        url: '../controlador/SALUD_INTEGRAL/detalle_fm_med_insC.php?listar=true',
        type: 'post',
        dataType: 'json',
        success: function (response) {
            //console.log(response);

            // Verificar si la respuesta contiene datos
            if (response && response.length > 0) {

                //$('#lista_medicamentos').empty();
                $('#lista_medicamentos tbody').empty();

                response.forEach(function (medicamento) {

                    console.log(medicamento);
                    count_medicamento++;

                    var htmlFila = '<tr>';
                    htmlFila += '<input type="hidden" value="' + medicamento.sa_det_fm_id + '" name="sa_det_conp_id[]" id="sa_det_conp_id_' + count_medicamento + '">';

                    htmlFila += '<td width="2%"><input class="itemFila_Medicamento" type="checkbox"></td>';
                    htmlFila += '<td width="98%"><label id="sa_det_conp_nombre_temp_' + count_medicamento + '">' + medicamento.sa_det_fice_nombre + '</label></td>';

                    htmlFila += '<input type="hidden" value="' + medicamento.sa_det_fice_nombre + '" name="sa_det_conp_nombre[]" id="sa_det_conp_nombre_' + count_medicamento + '">';
                    htmlFila += '<input type="hidden" value="' + medicamento.sa_det_fice_id_cmed_cins + '" name="sa_det_conp_id_cmed_cins[]" id="sa_det_conp_id_cmed_cins_' + count_medicamento + '">';
                    htmlFila += '<input type="hidden" value="' + medicamento.sa_det_fice_tipo + '" name="sa_det_conp_tipo[]" id="sa_det_conp_tipo_' + count_medicamento + '">';

                    htmlFila += '<input type="hidden" name="medicamentos[]" id="medicamentos_' + count_medicamento + '">';

                    htmlFila += '</tr>';

                    $('#lista_medicamentos').append(htmlFila);
                });
            }

        }
    });
}

function limpiar() {
    //$('#tipo_farmacologia').val('');
    //$('#sa_det_fice_id_cmed_cins').val('');
    //$('#sa_det_fice_nombre').val('');
    //$('#sa_det_fice_tipo').val('');

   /*  var select = $('#tipo_farmacologia_presentacion');
    // Destruir la instancia de Select2
    select.select2('destroy');

    // Agrega la opción predeterminada
    select.html('<option selected disabled> -- Selecciona una opción -- </option>'); */

    $('#tipo_farmacologia_presentacion').val(null).trigger('change');

}

function ajustarAlturaContenedor() {
    $('#tab_content_smart').css('height', 'auto');
}
