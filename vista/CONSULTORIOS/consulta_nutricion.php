<?php



?>

<script type="text/javascript">
    $(document).ready(function() {
        smartwizardFormularios('smartwizard_con_nut');
        tablas();

        formula_estimar_peso_femenino(18, 40, 37, 'Masculino');
    });

    function smartwizardFormularios(formulario) {
        var btnSiguiente = $('<button></button>').text('Siguiente').addClass('btn btn-info').on('click', function() {
            $('#' + formulario).smartWizard("next");
            return true;
        });
        var btnAtras = $('<button></button>').text('Atras').addClass('btn btn-info').on('click', function() {
            $('#' + formulario).smartWizard("prev");
            return true;
        });

        $('#' + formulario).on("showStep", function(e, anchorObject, stepNumber, stepDirection, stepPosition) {
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
        $('#' + formulario).smartWizard({

            selected: 0,
            theme: 'dots',
            transition: {
                animation: 'slide-horizontal', // Effect on navigation, none/fade/slide-horizontal/slide-vertical/slide-swing
            },
            toolbarSettings: {
                toolbarPosition: '',
                toolbarExtraButtons: [btnAtras, btnSiguiente],
                showNextButton: false, // Oculta el botón predeterminado "Next"
                showPreviousButton: false,
            },
        });
    }

    function crearTablaAlimentos(tabla, array_Alimentos, tipo_Alimento, input_ATP) {
        var alimentos = array_Alimentos;

        var cont_title = 1;
        // Iterar sobre el vector de nombres de alimentos y crear una fila para cada uno
        $.each(alimentos, function(index, nombre_Alimento) {
            var fila = $('<tr>'); // Crear una nueva fila

            // Agregar la celda del nombre del alimentos a la fila
            var celda_Nombre = $('<th class="vertical-text table-primary">').text(cont_title + '. ' + nombre_Alimento);
            fila.append(celda_Nombre);

            // Crear una variable para almacenar el valor seleccionado del radio button
            var valorSeleccionado = "";

            // Agregar las celdas de radio a la fila
            for (var i = 0; i < 5; i++) {
                var radioButton = $('<input type="radio" class="form-check-input">')
                    .attr('name', 'rd_' + tipo_Alimento + '_' + (index + 1))
                    .attr('id', 'rd_' + tipo_Alimento + '_' + (index + 1) + '_' + (i + 1))
                    .attr('value', i)
                    .on('change', function() {
                        // Cuando se selecciona un radio button, actualizar el valor seleccionado
                        valorSeleccionado = $(this).val();
                        // Actualizar el contenido del label con el valor seleccionado
                        $('#lbl_' + tipo_Alimento + '_' + (index + 1) + '_resultado').text(valorSeleccionado);
                        calcularTotalResultados();
                    });

                var label = $('<label class="form-check-label">').attr('for', 'rd_' + tipo_Alimento + '_' + (index + 1) + '_' + (i + 1)).html("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");

                var celda_rb = $('<td>').append(radioButton).append(label);

                fila.append(celda_rb);
            }

            // Agregar la celda del campo de texto de resultado a la fila
            var celda_Resultado = $('<th>').append('<label id="lbl_' + tipo_Alimento + '_' + (index + 1) + '_resultado">');
            fila.append(celda_Resultado);

            // Agregar la fila al cuerpo de la tabla
            $('#' + tabla + ' tbody').append(fila);

            cont_title++;
        });

        //Para poner el resultado de todas las filas
        var fila = $('<tr>');
        var celda_Resultado_1 = $('<th colspan = 6>').append();
        fila.append(celda_Resultado_1);
        var celda_Resultado_2 = $('<th>').append('<label id="lbl_resultado' + tipo_Alimento + '">');
        fila.append(celda_Resultado_2);
        $('#' + tabla + ' tbody').append(fila);

        // Función para calcular la suma total de los resultados
        function calcularTotalResultados() {
            var total = 0;
            $('[id^="lbl_' + tipo_Alimento + '"]').each(function() {
                total += parseInt($(this).text()) || 0;
            });
            //return total;
            $('#lbl_resultado' + tipo_Alimento).html(total);
            $('#' + input_ATP).val(total);
        }
    }

    function tablas() {
        var alimentos = [
            "Hamburguesas",
            "Carnes rojas",
            "Pollo frito",
            "Hot dogs",
            "Embutidos",
            "Mayonesa",
            "Margarina/Mantequilla",
            "Huevos",
            "Tocino/ Chorizo",
            "Quesos cremosos",
            "Leche entera",
            "Papas fritas",
            "Snacks",
            "Helados de crema",
            "Donas, pasteles, galletas"
        ];

        var tipo_Alimento = 'grasas';
        var tabla = 'tbl_grasas';
        var input_ATP = 'txt_puntaje_grasa';
        crearTablaAlimentos(tabla, alimentos, tipo_Alimento, input_ATP);

        var alimentos_2 = [
            "Jugos de fruta",
            "Frutas enteras",
            "Ensalada verde",
            "Otros vegetales",
            "Papas (no fritas)",
            "Leguminosas (granos)",
            "Cereal integral o Salvado",
            "Pan integral",
            "Fideos, Pastas",
        ];

        var tipo_Alimento_2 = 'fibras';
        var tabla_2 = 'tbl_fibras';
        var input_ATP_2 = 'txt_puntaje_fibra';
        crearTablaAlimentos(tabla_2, alimentos_2, tipo_Alimento_2, input_ATP_2);
    }

    function insertar_datos() {

        var ddl_paciente = $('#ddl_paciente').val();
        var txt_fecha_atencion = $('#txt_fecha_atencion').val();
        var txt_sexo = $('#txt_sexo').val();
        var txt_ocupacion = $('#txt_ocupacion').val();
        var rb_actividad_fisica = $('input[name="rb_actividad_fisica"]:checked').val();
        var ddl_tipo_ejercicio = $('#ddl_tipo_ejercicio').val();
        var txt_tiempo_ejercicio = $('#txt_tiempo_ejercicio').val();
        var txt_dias_xsemana = $('#txt_dias_xsemana').val();
        var txt_peso_actual = $('#txt_peso_actual').val();
        var txt_talla = $('#txt_talla').val();
        var txt_edad = $('#txt_edad').val();
        var txt_peso_usual = $('#txt_peso_usual').val();
        var txt_C_munieca = $('#txt_C_munieca').val();
        var txt_C_abdominal = $('#txt_C_abdominal').val();
        var txt_pliegue_tricep = $('#txt_pliegue_tricep').val();
        var txt_C_mediaBrazo = $('#txt_C_mediaBrazo').val();
        var txt_linfocitos = $('#txt_linfocitos').val();
        var txt_bun = $('#txt_bun').val();
        var txt_glucosa = $('#txt_glucosa').val();
        var txt_colesterol_tot = $('#txt_colesterol_tot').val();
        var txt_cLDL = $('#txt_cLDL').val();
        var txt_cHDL = $('#txt_cHDL').val();
        var txt_trigliceridos = $('#txt_trigliceridos').val();
        var txt_acido_urico = $('#txt_acido_urico').val();
        var rb_alergia_alimentos = $('input[name="rb_alergia_alimentos"]:checked').val();
        var ddl_consecuencias_alergia = $('#ddl_consecuencias_alergia').val();
        var rb_uso_medicamentos = $('input[name="rb_uso_medicamentos"]:checked').val();
        var rb_uso_multivitaminas = $('input[name="rb_uso_multivitaminas"]:checked').val();
        var rb_usu_suple_nutricional = $('input[name="rb_usu_suple_nutricional"]:checked').val();
        var txt_resultado_endoscopia = $('#txt_resultado_endoscopia').val();
        var txt_puntaje_grasa = $('#txt_puntaje_grasa').val();
        var txt_puntaje_fibra = $('#txt_puntaje_fibra').val();


        var parametros = {
            'ddl_paciente': ddl_paciente,
            'txt_fecha_atencion': txt_fecha_atencion,
            'txt_sexo': txt_sexo,
            'txt_ocupacion': txt_ocupacion,
            'rb_actividad_fisica': rb_actividad_fisica,
            'ddl_tipo_ejercicio': ddl_tipo_ejercicio,
            'txt_tiempo_ejercicio': txt_tiempo_ejercicio,
            'txt_dias_xsemana': txt_dias_xsemana,
            'txt_peso_actual': txt_peso_actual,
            'txt_talla': txt_talla,
            'txt_edad': txt_edad,
            'txt_peso_usual': txt_peso_usual,
            'txt_C_munieca': txt_C_munieca,
            'txt_C_abdominal': txt_C_abdominal,
            'txt_pliegue_tricep': txt_pliegue_tricep,
            'txt_C_mediaBrazo': txt_C_mediaBrazo,
            'txt_linfocitos': txt_linfocitos,
            'txt_bun': txt_bun,
            'txt_glucosa': txt_glucosa,
            'txt_colesterol_tot': txt_colesterol_tot,
            'txt_cLDL': txt_cLDL,
            'txt_cHDL': txt_cHDL,
            'txt_trigliceridos': txt_trigliceridos,
            'txt_acido_urico': txt_acido_urico,
            'rb_alergia_alimentos': rb_alergia_alimentos,
            'ddl_consecuencias_alergia': ddl_consecuencias_alergia,
            'rb_uso_medicamentos': rb_uso_medicamentos,
            'rb_uso_multivitaminas': rb_uso_multivitaminas,
            'rb_usu_suple_nutricional': rb_usu_suple_nutricional,
            'txt_resultado_endoscopia': txt_resultado_endoscopia,
            'txt_puntaje_grasa': txt_puntaje_grasa,
            'txt_puntaje_fibra': txt_puntaje_fibra,

        };
        console.log(parametros);
    }

    function insertar() {

    }

    /******************************************************************/
    /*                 FORMULAS                                       */
    /******************************************************************/

    function formula_estimar_peso_femenino(edad, ar, cmb, sexo) {
        //AR: altura rodilla cm; 
        //CMB: circunferencia media del brazo cm

        let peso;

        if (sexo == 'Femenino') {
            if (edad >= 6 && edad <= 18) {
                peso = (ar * 0.77) + (cmb * 2.47) - 50.16;
            } else if (edad >= 19 && edad <= 59) {
                peso = (ar * 1.01) + (cmb * 2.81) - 66.04;
            } else if (edad >= 60 && edad <= 80) {
                peso = (ar * 1.09) + (cmb * 2.68) - 65.51;
            } else {
                peso = "Edad fuera de rango para calcular el peso";
            }
        } else if (sexo == 'Masculino') {
            if (edad >= 6 && edad <= 18) {
                peso = (ar * 0.68) + (cmb * 2.64) - 50.08;
            } else if (edad >= 19 && edad <= 59) {
                peso = (ar * 1.19) + (cmb * 3.21) - 86.82;
            } else if (edad >= 60 && edad <= 80) {
                peso = (ar * 1.10) + (cmb * 3.07) - 75.81;
            } else {
                peso = "Edad fuera de rango para calcular el peso";
            }
        }

        // Redondear el peso a dos decimales
        if (typeof peso === 'number') {
            peso = peso.toFixed(2);
        }

        return peso;
    }

    


</script>

<div class="page-wrapper">
    <div class="page-content">

        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Enfermería</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>

            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Consulta
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
                        <div class="row">

                            <div class="col-6">
                                <div class="card-title d-flex align-items-center">
                                    <div><i class="bx bxs-user me-1 font-22 text-primary"></i>
                                    </div>
                                    <h5 class="mb-0 text-primary">
                                        Ruben Prueba
                                    </h5>
                                </div>
                            </div>

                            <div class="col-6 text-end">
                                <button class="btn btn-outline-primary" onclick="consultar_datos_h(1, $('#nombre_modal').val())"><i class='bx bx-list-ol'></i> Historial</button>
                            </div>


                        </div>

                        <br>

                        <div id="smartwizard_con_nut">
                            <ul class="nav">
                                <li class="nav-item">
                                    <a class="nav-link" href="#con-1-nut"> <strong>Paso 1</strong>
                                        <br>Datos Generales</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#con-2-nut"> <strong>Parámetros 2</strong>
                                        <br>a. Antropometría</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#con-3-nut"> <strong>Parámetros 3</strong>
                                        <br>b. Bioquímicos</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#con-4-nut"> <strong>Parámetros 4</strong>
                                        <br>c. Clínico</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#con-5-nut"> <strong>Paso 5</strong>
                                        <br>d. Exámen</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#con-6-nut"> <strong>Paso 6</strong>
                                        <br>e. Ananmesis</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#con-7-nut"> <strong>Paso 7</strong>
                                        <br>Análisis</a>
                                </li>
                            </ul>


                            <div class="tab-content" id="tab_content_smart">
                                <div id="con-1-nut" class="tab-pane" role="tabpanel" aria-labelledby="con-1-nut">

                                    <!-- Datos Generales -->
                                    <div>
                                        <section>

                                            <div class="row mb-2">
                                                <label for="ddl_paciente" class="col-sm-2 col-form-label text-end fw-bold">Nombre <label class="text-danger">*</label></label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control form-control-sm" name="ddl_paciente" id="ddl_paciente" value="por defecto">
                                                </div>
                                            </div>

                                            <div class="row mb-2">
                                                <label for="txt_sexo" class="col-sm-2 col-form-label text-end fw-bold">Sexo <label class="text-danger">*</label></label>
                                                <div class="col-sm-4">
                                                    <input type="text" class="form-control form-control-sm" name="txt_sexo" id="txt_sexo" value="por defecto">
                                                </div>

                                                <label for="txt_fecha_atencion" class="col-sm-2 col-form-label text-end fw-bold">Fecha <label class="text-danger">*</label></label>
                                                <div class="col-sm-3">
                                                    <input type="text" class="form-control form-control-sm" name="txt_fecha_atencion" id="txt_fecha_atencion" value="por defecto">
                                                </div>

                                            </div>

                                            <div class="row mb-2">
                                                <label for="txt_ocupacion" class="col-sm-2 col-form-label text-end fw-bold">Ocupación <label class="text-danger">*</label></label>
                                                <div class="col-sm-4">
                                                    <input type="text" class="form-control form-control-sm" name="txt_ocupacion" id="txt_ocupacion" value="por defecto">
                                                </div>
                                            </div>


                                            <div class="row mb-2">
                                                <label for="rb_actividad_fisica" class="col-sm-2 col-form-label text-end fw-bold">Actividad Física <label class="text-danger">*</label></label>
                                                <div class="col-sm-9 pt-1">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="rb_actividad_fisica" id="rb_actividad_fisica" value="SI">
                                                        <label class="form-check-label" for="rb_actividad_fisica">Si</label>
                                                    </div>

                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="rb_actividad_fisica" id="rb_actividad_fisica_2" value="NO" checked>
                                                        <label class="form-check-label" for="rb_actividad_fisica_2">No</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </section>

                                        <section class="" id="pnl_actividad_fisica">

                                            <div class="row mb-2">
                                                <label for="ddl_tipo_ejercicio" class="col-sm-2 col-form-label text-end fw-bold">Tipo De Ejercicio <label class="text-danger">*</label></label>
                                                <div class="col-sm-4">
                                                    <select class="form-select form-select-sm" name="ddl_tipo_ejercicio" id="ddl_tipo_ejercicio">
                                                        <option selected disabled>-- Seleccione --</option>
                                                        <option value="Fútbol">Fútbol</option>
                                                        <option value="Basket">Basket</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row mb-2">
                                                <label for="txt_tiempo_ejercicio" class="col-sm-2 col-form-label text-end fw-bold">Tiempo Min. <label class="text-danger">*</label></label>
                                                <div class="col-sm-2">
                                                    <input type="number" class="form-control form-control-sm" name="txt_tiempo_ejercicio" id="txt_tiempo_ejercicio">
                                                </div>
                                            </div>

                                            <div class="row mb-2">
                                                <label for="txt_dias_xsemana" class="col-sm-2 col-form-label text-end fw-bold">Días Por Semana <label class="text-danger">*</label></label>
                                                <div class="col-sm-2">
                                                    <input type="number" class="form-control form-control-sm" name="txt_dias_xsemana" id="txt_dias_xsemana">
                                                </div>
                                            </div>
                                        </section>
                                    </div>

                                </div>

                                <div id="con-2-nut" class="tab-pane" role="tabpanel" aria-labelledby="con-2-nut">
                                    <h3>Antropometría</h3>

                                    <!-- Antropometría -->
                                    <div>

                                        <div class="row mb-2">
                                            <label for="txt_peso_actual" class="col-sm-3 col-form-label text-end fw-bold">Peso Actual <label class="text-danger">*</label></label>
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control form-control-sm" name="txt_peso_actual" id="txt_peso_actual">
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <label for="txt_talla" class="col-sm-3 col-form-label text-end fw-bold">Talla <label class="text-danger">*</label></label>
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control form-control-sm" name="txt_talla" id="txt_talla">
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <label for="txt_edad" class="col-sm-3 col-form-label text-end fw-bold">Edad <label class="text-danger">*</label></label>
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control form-control-sm" name="txt_edad" id="txt_edad">
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <label for="txt_peso_usual" class="col-sm-3 col-form-label text-end fw-bold">Peso Usual <label class="text-danger">*</label></label>
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control form-control-sm" name="txt_peso_usual" id="txt_peso_usual">
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <label for="txt_C_munieca" class="col-sm-3 col-form-label text-end fw-bold">C. Muñeca <label class="text-danger">*</label></label>
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control form-control-sm" name="txt_C_munieca" id="txt_C_munieca">
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <label for="txt_C_abdominal" class="col-sm-3 col-form-label text-end fw-bold">C. Abdominal <label class="text-danger">*</label></label>
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control form-control-sm" name="txt_C_abdominal" id="txt_C_abdominal">
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <label for="txt_pliegue_tricep" class="col-sm-3 col-form-label text-end fw-bold">Pliegue Tríceps <label class="text-danger">*</label></label>
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control form-control-sm" name="txt_pliegue_tricep" id="txt_pliegue_tricep">
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <label for="txt_C_mediaBrazo" class="col-sm-3 col-form-label text-end fw-bold">C. Media Brazo <label class="text-danger">*</label></label>
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control form-control-sm" name="txt_C_mediaBrazo" id="txt_C_mediaBrazo">
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div id="con-3-nut" class="tab-pane" role="tabpanel" aria-labelledby="con-3-nut">
                                    <h3>Bioquímicos</h3>

                                    <!-- Bioquímicos -->
                                    <div>

                                        <div class="row mb-2">
                                            <label for="txt_linfocitos" class="col-sm-3 col-form-label text-end fw-bold">Linfocitos <label class="text-danger">*</label></label>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" name="txt_linfocitos" id="txt_linfocitos">
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" name="txt_linfocitos_vr" id="txt_linfocitos_vr">
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <label for="txt_bun" class="col-sm-3 col-form-label text-end fw-bold">BUN <label class="text-danger">*</label></label>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" name="txt_bun" id="txt_bun">
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" name="txt_bun_vr" id="txt_bun_vr">
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <label for="txt_glucosa" class="col-sm-3 col-form-label text-end fw-bold">Glucosa <label class="text-danger">*</label></label>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" name="txt_glucosa" id="txt_glucosa">
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" name="txt_glucosa_vr" id="txt_glucosa_vr">
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <label for="txt_colesterol_tot" class="col-sm-3 col-form-label text-end fw-bold">Colesterol Total <label class="text-danger">*</label></label>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" name="txt_colesterol_tot" id="txt_colesterol_tot">
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" name="txt_colesterol_tot_vr" id="txt_colesterol_tot_vr">
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <label for="txt_cLDL" class="col-sm-3 col-form-label text-end fw-bold">cLDL <label class="text-danger">*</label></label>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" name="txt_cLDL" id="txt_cLDL">
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" name="txt_cLDL_vr" id="txt_cLDL_vr">
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <label for="txt_cHDL" class="col-sm-3 col-form-label text-end fw-bold">cHDL <label class="text-danger">*</label></label>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" name="txt_cHDL" id="txt_cHDL">
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" name="txt_cHDL_vr" id="txt_cHDL_vr">
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <label for="txt_trigliceridos" class="col-sm-3 col-form-label text-end fw-bold">Triglicéridos <label class="text-danger">*</label></label>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" name="txt_trigliceridos" id="txt_trigliceridos">
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" name="txt_trigliceridos_vr" id="txt_trigliceridos_vr">
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <label for="txt_acido_urico" class="col-sm-3 col-form-label text-end fw-bold">Acido Úrico <label class="text-danger">*</label></label>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" name="txt_acido_urico" id="txt_acido_urico">
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" name="txt_acido_urico_vr" id="txt_acido_urico_vr">
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div id="con-4-nut" class="tab-pane" role="tabpanel" aria-labelledby="con-4-nut">
                                    <h3>Clínico</h3>

                                    <p class="text-primary">Problemas Relacionados Con Nutrición</p>

                                    <!-- Clínico -->
                                    <div class="">

                                        <div class="row mb-2">
                                            <label for="inputEnterYourName" class="col-sm-3 col-form-label text-end fw-bold">Alergía a Alimentos <label class="text-danger">*</label></label>
                                            <div class="col-sm-9 pt-1">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="rb_alergia_alimentos" id="rb_alergia_alimentos" value="SI">
                                                    <label class="form-check-label" for="rb_alergia_alimentos">Si</label>
                                                </div>

                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="rb_alergia_alimentos" id="rb_alergia_alimentos_2" value="NO" checked>
                                                    <label class="form-check-label" for="rb_alergia_alimentos_2">No</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-2" id="pnl_alergia_alimentos">
                                            <label for="ddl_consecuencias_alergia" class="col-sm-3 col-form-label text-end fw-bold">Cuáles? <label class="text-danger">*</label></label>
                                            <div class="col-sm-9">
                                                <select class="multiple-select" data-placeholder="-- Seleccione --" multiple="multiple" name="ddl_consecuencias_alergia" id="ddl_consecuencias_alergia">
                                                    <option value="Diarrea">Diarrea</option>
                                                    <option value="Vomito">Vomito</option>
                                                    <option value="Acidez">Acidez</option>
                                                    <option value="Estreñimiento">Estreñimiento</option>
                                                    <option value="Nausea">Nausea</option>
                                                    <option value="Flatulencia">Flatulencia</option>
                                                    <option value="Problemas Al Masticar">Problemas Al Masticar</option>
                                                    <option value="Cambio De Sabores">Cambio De Sabores</option>
                                                </select>
                                            </div>
                                        </div>

                                        <hr>

                                        <div class="row mb-2">
                                            <label for="rb_uso_medicamentos" class="col-sm-3 col-form-label text-end fw-bold">Uso De Medicamentos <label class="text-danger">*</label></label>
                                            <div class="col-sm-9 pt-1">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="rb_uso_medicamentos" id="rb_uso_medicamentos" value="SI">
                                                    <label class="form-check-label" for="rb_uso_medicamentos">Si</label>
                                                </div>

                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="rb_uso_medicamentos" id="rb_uso_medicamentos_2" value="NO" checked>
                                                    <label class="form-check-label" for="rb_uso_medicamentos_2">No</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <label for="rb_uso_multivitaminas" class="col-sm-3 col-form-label text-end fw-bold">Multivitaminas <label class="text-danger">*</label></label>
                                            <div class="col-sm-9 pt-1">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="rb_uso_multivitaminas" id="rb_uso_multivitaminas" value="SI">
                                                    <label class="form-check-label" for="rb_uso_multivitaminas">Si</label>
                                                </div>

                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="rb_uso_multivitaminas" id="rb_uso_multivitaminas_2" value="NO" checked>
                                                    <label class="form-check-label" for="rb_uso_multivitaminas_2">No</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <label for="rb_usu_suple_nutricional" class="col-sm-3 col-form-label text-end fw-bold">Suplementos Nutricionales <label class="text-danger">*</label></label>
                                            <div class="col-sm-9 pt-1">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="rb_usu_suple_nutricional" id="rb_usu_suple_nutricional" value="SI">
                                                    <label class="form-check-label" for="rb_usu_suple_nutricional">Si</label>
                                                </div>

                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="rb_usu_suple_nutricional" id="rb_usu_suple_nutricional_2" value="NO" checked>
                                                    <label class="form-check-label" for="rb_usu_suple_nutricional_2">No</label>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div id="con-5-nut" class="tab-pane" role="tabpanel" aria-labelledby="con-4-nut">
                                    <h3>Resultados De Endoscopia/Colonoscopia</h3>

                                    <!-- Exámen -->
                                    <div>
                                        <div class="row mb-2">
                                            <label for="txt_resultado_endoscopia" class="col-sm-4 col-form-label text-end fw-bold">Observación (preguntar) <label class="text-danger">*</label></label>
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control form-control-sm" name="txt_resultado_endoscopia" id="txt_resultado_endoscopia">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="con-6-nut" class="tab-pane" role="tabpanel" aria-labelledby="con-4-nut">
                                    <h3>Anamnesis Alimentaria</h3>

                                    <!-- Exámen -->
                                    <div class="">

                                        <div class="row mb-2">
                                            <label for="txt_puntaje_grasa" class="col-sm-4 col-form-label text-end fw-bold">Puntaje De Grasas (ATP III) <label class="text-danger">*</label></label>
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control form-control-sm" name="txt_puntaje_grasa" id="txt_puntaje_grasa">
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <label for="txt_puntaje_fibra" class="col-sm-4 col-form-label text-end fw-bold">Puntaje De Fibra (ATP III) <label class="text-danger">*</label></label>
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control form-control-sm" name="txt_puntaje_fibra" id="txt_puntaje_fibra">
                                            </div>
                                        </div>

                                        <div>
                                            <style>
                                                .table-bordered th,
                                                .table-bordered td {
                                                    border: 2px solid black;
                                                }
                                            </style>

                                            <table class="table table-bordered mb-0 text-center" style="width:90%" id="tbl_grasas">
                                                <thead>
                                                    <tr>
                                                        <th rowspan="2">ALIMENTOS</th>
                                                        <th>0</th>
                                                        <th>1</th>
                                                        <th>2</th>
                                                        <th>3</th>
                                                        <th>4</th>
                                                        <th rowspan="2">RESULTADOS</th>
                                                    </tr>

                                                    <tr>
                                                        <th>
                                                            < 1 vez/mes</th>
                                                        <th>2-3 veces/mes</th>
                                                        <th>1-2 veces/sem</th>
                                                        <th>3-4 veces/sem</th>
                                                        <th>> 5 veces/sem</th>
                                                    </tr>

                                                </thead>

                                                <tbody class="small">

                                                </tbody>
                                            </table>


                                            <br>
                                            <br>

                                            <table class="table table-bordered mb-0 text-center" style="width:90%" id="tbl_fibras">
                                                <thead>
                                                    <tr>
                                                        <th rowspan="2">ALIMENTOS</th>
                                                        <th>0</th>
                                                        <th>1</th>
                                                        <th>2</th>
                                                        <th>3</th>
                                                        <th>4</th>
                                                        <th rowspan="2">RESULTADOS</th>
                                                    </tr>

                                                    <tr>
                                                        <th>
                                                            < 1 vez/mes</th>
                                                        <th>2-3 veces/mes</th>
                                                        <th>1-2 veces/sem</th>
                                                        <th>3-4 veces/sem</th>
                                                        <th>> 5 veces/sem</th>
                                                    </tr>

                                                </thead>

                                                <tbody class="small">

                                                </tbody>
                                            </table>


                                        </div>

                                    </div>
                                </div>

                                <div id="con-7-nut" class="tab-pane" role="tabpanel" aria-labelledby="con-3-nut">
                                    <h3>Análisis</h3>

                                    <p>Antropometría Y Requerimientos Energéticos</p>

                                    <!-- Analisis -->
                                    <div class="">

                                        <div class="row mb-2">
                                            <label for="inputEnterYourName" class="col-sm-4 col-form-label text-end fw-bold">Rango De Peso Ideal Kg <label class="text-danger">*</label></label>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" id="inputEnterYourName">
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" id="inputEnterYourName">
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <label for="inputEnterYourName" class="col-sm-4 col-form-label text-end fw-bold">IMC para la edad Kg/m² <label class="text-danger">*</label></label>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" id="inputEnterYourName">
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" id="inputEnterYourName">
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <label for="inputEnterYourName" class="col-sm-4 col-form-label text-end fw-bold">Estado Nutricional <label class="text-danger">*</label></label>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" id="inputEnterYourName">
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" id="inputEnterYourName">
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <label for="inputEnterYourName" class="col-sm-4 col-form-label text-end fw-bold">% De Grasa Corporal <label class="text-danger">*</label></label>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" id="inputEnterYourName">
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" id="inputEnterYourName">
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <label for="inputEnterYourName" class="col-sm-4 col-form-label text-end fw-bold">Circunferencia Abdominal <label class="text-danger">*</label></label>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" id="inputEnterYourName">
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" id="inputEnterYourName">
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <label for="inputEnterYourName" class="col-sm-4 col-form-label text-end fw-bold">% Pérdida De Peso <label class="text-danger">*</label></label>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" id="inputEnterYourName">
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" id="inputEnterYourName">
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <label for="inputEnterYourName" class="col-sm-4 col-form-label text-end fw-bold">Gasto Energético Basal <label class="text-danger">*</label></label>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" id="inputEnterYourName">
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" id="inputEnterYourName">
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <label for="inputEnterYourName" class="col-sm-4 col-form-label text-end fw-bold">Gasto Energético Total <label class="text-danger">*</label></label>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" id="inputEnterYourName">
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" id="inputEnterYourName">
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <label for="inputEnterYourName" class="col-sm-4 col-form-label text-end fw-bold">G. E. Para Reducción De Peso <label class="text-danger">*</label></label>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" id="inputEnterYourName">
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" id="inputEnterYourName">
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <label for="inputEnterYourName" class="col-sm-4 col-form-label text-end fw-bold">G. E. Para Incremento De Peso <label class="text-danger">*</label></label>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" id="inputEnterYourName">
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" id="inputEnterYourName">
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <label for="inputEnterYourName" class="col-sm-4 col-form-label text-end fw-bold">Proteína gr/kg <label class="text-danger">*</label></label>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" id="inputEnterYourName">
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" id="inputEnterYourName">
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <label for="inputEnterYourName" class="col-sm-4 col-form-label text-end fw-bold">Contextura <label class="text-danger">*</label></label>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" id="inputEnterYourName">
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" id="inputEnterYourName">
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <label for="inputEnterYourName" class="col-sm-4 col-form-label text-end fw-bold">Área Muscular Del Brazo (pC) <label class="text-danger">*</label></label>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" id="inputEnterYourName">
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" id="inputEnterYourName">
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <label for="inputEnterYourName" class="col-sm-4 col-form-label text-end fw-bold">Área Grasa Del Brazo (pC) <label class="text-danger">*</label></label>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" id="inputEnterYourName">
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" id="inputEnterYourName">
                                            </div>
                                        </div>

                                    </div>

                                    <div class="modal-footer pt-4" id="seccion_boton_consulta">

                                        <button class="btn btn-primary btn-sm px-2 m-1" onclick="insertar_datos()" type="button"><i class='bx bx-pause-circle'></i> Guardar</button>

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



<script>
    $(document).ready(function() {

        //Para la seccion 1
        $('#pnl_actividad_fisica').hide();
        $('input[name="rb_actividad_fisica"]').change(function() {
            if ($(this).val() == 'SI') {
                $('#pnl_actividad_fisica').show();
                ajustarAlturaContenedor();
            } else {
                $('#pnl_actividad_fisica').hide();
            }
        });


        //Para la seccion 4
        $('#pnl_alergia_alimentos').hide();
        $('input[name="rb_alergia_alimentos"]').change(function() {
            if ($(this).val() == 'SI') {
                $('#pnl_alergia_alimentos').show();
                ajustarAlturaContenedor();
            } else {
                $('#pnl_alergia_alimentos').hide();
            }
        });
    });

    // Función para ajustar la altura del contenedor del wizard
    function ajustarAlturaContenedor() {
        $('#tab_content_smart').css('height', 'auto');
    }
</script>