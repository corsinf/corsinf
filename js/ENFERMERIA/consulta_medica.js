$(document).ready(function () {
    var inputAltura = $('#sa_conp_altura');

    inputAltura.on('input', function () {
        // Obtén el valor actual del campo y elimina caracteres no numéricos
        var valor = inputAltura.val().replace(/[^\d]/g, '');

        // Agrega un punto decimal después del primer dígito si no hay punto ya presente
        if (valor.length > 0 && valor.indexOf('.') === -1) {
            inputAltura.val(valor.charAt(0) + (valor.length > 1 ? '.' + valor.substring(1) : ''));
        }
    });

    // Asegura que solo se permitan números y el punto decimal
    inputAltura.on('keypress', function (event) {
        var charCode = (event.which) ? event.which : event.keyCode;
        // Permitir números (48-57) y el punto (46)
        if (charCode !== 46 && (charCode < 48 || charCode > 57)) {
            event.preventDefault();
        }
    });

    var validacion_numeros = $('.solo_numeros');

    validacion_numeros.on('keypress', function (event) {
        var charCode = (event.which) ? event.which : event.keyCode;
        // Permitir números (48-57) y el punto (46)
        if (charCode !== 46 && (charCode < 48 || charCode > 57)) {
            event.preventDefault();
        }
    });

    var validacion_numeros_slash = $('.solo_numeros_slash');

    validacion_numeros_slash.on('keypress', function (event) {
        var charCode = (event.which) ? event.which : event.keyCode;
        // Permitir números (48-57), el punto (46) y la barra (/)
        if (charCode !== 46 && charCode !== 47 && (charCode < 48 || charCode > 57)) {
            event.preventDefault();
        }
    });

    //Para el modal de examen fisico regional 

    modal_examen_fisico_regional();
});

function calcularIMC() {
    var peso = $('#sa_conp_peso').val();
    var altura = $('#sa_conp_altura').val();

    if (peso && altura) {
        var imc = peso / (altura * altura);
        $('#txt_imc').val(imc.toFixed(2));

        var nivelPeso = obtenerNivelPeso(imc);
        $('#txt_np').val(nivelPeso);
    } else {
        $('#txt_imc, #txt_np').val('');
    }
}

function obtenerNivelPeso(imc) {
    if (imc < 18.5) {
        return 'Bajo Peso';
    } else if (imc >= 18.5 && imc < 25) {
        return 'Peso Saludable';
    } else if (imc >= 25 && imc < 30) {
        return 'Sobrepeso';
    } else if (imc >= 30 && imc < 35) {
        return 'Obesidad Grado I';
    } else if (imc >= 35 && imc < 40) {
        return 'Obesidad Grado II';
    } else {
        return 'Obesidad Grado III';
    }
}

$('#sa_conp_peso, #sa_conp_altura').on('input', calcularIMC);

function abrir_ventana_emergente(sa_conp_id) {
    // URL de la página que quieres cargar en la ventana emergente
    var url = '../controlador/consultasC.php?pdf_consulta=true&id_consulta=' + sa_conp_id;

    // Configuración de la ventana emergente
    var ventana_emergente = window.open(url, '_blank', 'width=1000,height=1000');

    // Se puede personalizar la configuración según tus necesidades
    // window.open(url, nombreVentana, opciones);
    // Ejemplo de opciones: 'width=500,height=400,toolbar=no,location=no,menubar=no,scrollbars=yes,resizable=yes'
}

function show_historial() {
    $('#myModal_historial').modal('show');
}

function consultar_datos_h(id_paciente = '', nombres = '') {
    $('#title_nombre').html(nombres);
    var consulta = '';
    var cont = 1;
    $.ajax({
        data: {
            id_ficha: id_paciente
        },
        url: '../controlador/consultasC.php?listar_consulta_ficha=true',
        type: 'post',
        dataType: 'json',
        success: function (response) {
            console.log(response);

            $('#tbl_consultas_pac').DataTable({
                destroy: true, // Destruir la tabla existente antes de recrearla
                data: response,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                },
                responsive: true, // Datos de las consultas médicas
                columns: [
                    // Definir las columnas
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            // Usar el contador autoincremental proporcionado por DataTables
                            return meta.row + 1;
                        }
                    },
                    {
                        data: null,
                        render: function (data, type, item) {
                            if (item.sa_conp_desde_hora == null || item.sa_conp_fecha_ingreso == null) {
                                return '';
                            } else {
                                return fecha_nacimiento_formateada(item.sa_conp_fecha_creacion) + ' / ' + obtener_hora_formateada_arr(item.sa_conp_fecha_creacion);
                            }
                        }
                    },
                    {
                        data: null,
                        render: function (data, type, item) {
                            if (item.sa_conp_desde_hora == null || item.sa_conp_hasta_hora == null) {
                                return '';
                            } else {
                                return (item.sa_conp_fecha_ingreso) + ' / [' + obtener_hora_formateada(item.sa_conp_desde_hora) + ' / ' + obtener_hora_formateada(item.sa_conp_hasta_hora) + ']';
                            }
                        }
                    },
                    {
                        data: null,
                        render: function (data, type, item) {
                            if (item.sa_conp_tipo_consulta == 'consulta') {
                                return '<div class="badge rounded-pill text-success bg-light-success p-2 text-uppercase px-3">' + ('Atención médica') + '</div>';
                            } else {
                                return '<div class="badge rounded-pill text-info bg-light-info p-2 text-uppercase px-3">' + item.sa_conp_tipo_consulta + '</div>';
                            }
                        }
                    },
                    {
                        data: null,
                        render: function (data, type, item) {
                            //return '<a class="btn btn-primary btn-sm" target="_blank"  title="Enviar Mensaje" href="../controlador/consultasC.php?pdf_consulta=true&id_consulta=' + item.sa_conp_id + '">' + '<i class="bx bx-show-alt me-0"></i>' + '</a>';
                            return '<a class="btn btn-primary btn-sm" target="_blank"  title="Enviar Mensaje" href="#" onclick="abrir_ventana_emergente(' + item.sa_conp_id + ');">' + '<i class="bx bx-show-alt me-0"></i>' + '</a>';
                        }
                    },

                ],
                order: [
                    [1, 'desc'] // Ordenar por la segunda columna (índice 1) en orden ascendente
                ]
            });

            show_historial();
        }
    });
}


function agregarCampo(objeto, clave, idCheckbox, dato) {
    var valor = $('#' + idCheckbox).prop('checked');
    if (valor) {
        objeto[clave] = dato;
    }
}

function generarJSON() {
    var datos = {};

    var sa_examen_fisico_regional_obs = $('#sa_examen_fisico_regional_obs').val();



    //1 Piel
    agregarCampo(datos.Piel = {}, 'a', 'chx_Cicatrices', 'Cicatrices');
    agregarCampo(datos.Piel, 'b', 'chx_Tatuajes', 'Tatuajes');
    agregarCampo(datos.Piel, 'c', 'chx_Piel_Faneras', 'Piel y Faneras');

    //2 Ojos
    agregarCampo(datos.Ojos = {}, 'a', 'chx_Parpados', 'Párpados');
    agregarCampo(datos.Ojos, 'b', 'chx_Conjuntivas', 'Conjuntivas');
    agregarCampo(datos.Ojos, 'c', 'chx_Pupilas', 'Pupilas');
    agregarCampo(datos.Ojos, 'd', 'chx_Cornea', 'Córnea');
    agregarCampo(datos.Ojos, 'e', 'chx_Motilidad', 'Motilidad');

    //3 Oído
    agregarCampo(datos.Oído = {}, 'a', 'chx_C_auditivo_externo', 'C.auditivo externo');
    agregarCampo(datos.Oído, 'b', 'chx_Pabellon', 'Pabellón');
    agregarCampo(datos.Oído, 'c', 'chx_Timpanos', 'Timpanos');

    //4 Oro_faringe
    agregarCampo(datos.Oro_faringe = {}, 'a', 'chx_Labios', 'Labios');
    agregarCampo(datos.Oro_faringe, 'b', 'chx_Lengua', 'Lengua');
    agregarCampo(datos.Oro_faringe, 'c', 'chx_Faringe', 'Faringe');
    agregarCampo(datos.Oro_faringe, 'd', 'chx_Amigdalas', 'Amígdalas');
    agregarCampo(datos.Oro_faringe, 'e', 'chx_Dentadura', 'Dentadura');

    //5 Nariz
    agregarCampo(datos.Nariz = {}, 'a', 'chx_Tabique', 'Tabique');
    agregarCampo(datos.Nariz, 'b', 'chx_Cornetes', 'Cornetes');
    agregarCampo(datos.Nariz, 'c', 'chx_Mucosas', 'Mucosas');
    agregarCampo(datos.Nariz, 'd', 'chx_Senos_paranasales', 'Senos paranasales');

    //6 Cuello
    agregarCampo(datos.Cuello = {}, 'a', 'chx_Tiroides', 'Tiroides');
    agregarCampo(datos.Cuello, 'b', 'chx_Movilidad', 'Movilidad');

    //7 Tórax
    agregarCampo(datos.Torax_1 = {}, 'a', 'chx_Mamas', 'Mamas');
    agregarCampo(datos.Torax_1, 'b', 'chx_Corazon', 'Corazón');

    //8 Tórax
    agregarCampo(datos.Torax_2 = {}, 'a', 'chx_Pulmones', 'Pulmones');
    agregarCampo(datos.Torax_2, 'b', 'chx_Parrilla_Costal', 'Parrilla Costal');

    //9 Abdomen
    agregarCampo(datos.Abdomen = {}, 'a', 'chx_Visceras', 'Vísceras');
    agregarCampo(datos.Abdomen, 'b', 'chx_Pared_Abdominal', 'Pared abdominal');

    //10 Columna
    agregarCampo(datos.Columna = {}, 'a', 'chx_Flexibilidad', 'Flexibilidad');
    agregarCampo(datos.Columna, 'b', 'chx_Desviacion', 'Desviación');
    agregarCampo(datos.Columna, 'c', 'chx_Dolor', 'Dolor');

    //11 Pelvis
    agregarCampo(datos.Pelvis = {}, 'a', 'chx_Pelvis', 'Pelvis');
    agregarCampo(datos.Pelvis, 'b', 'chx_Genitales', 'Genitales');

    //12 Extremidades
    agregarCampo(datos.Extremidades = {}, 'a', 'chx_Vascular', 'Vascular');
    agregarCampo(datos.Extremidades, 'b', 'chx_Miembros_superiores', 'Miembros superiores');
    agregarCampo(datos.Extremidades, 'c', 'chx_Miembros_inferiores', 'Miembros inferiores');

    //13 Neurológico
    agregarCampo(datos.Neurológico = {}, 'a', 'chx_Fuerza', 'Fuerza');
    agregarCampo(datos.Neurológico, 'b', 'chx_Sensibilidad', 'Sensibilidad');
    agregarCampo(datos.Neurológico, 'c', 'chx_Marcha', 'Marcha');
    agregarCampo(datos.Neurológico, 'd', 'chx_Reflejos', 'Reflejos');

    datos.Observaciones =
    {
        'sa_examen_fisico_regional_obs': sa_examen_fisico_regional_obs,
    };

    var jsonString = JSON.stringify(datos);

    return jsonString;

}

// Función para confirmar la desmarcación del checkbox usando SweetAlert
function delete_datos(valor, checkbox) {
    Swal.fire({
        title: 'Desmarcar opción.',
        text: "¿Está seguro/a que desea desmarcar el Check " + valor + "?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si'
    }).then((result) => {
        if (result.value) {
            //alert('El checkbox se desmarca');
            eliminar_ef_observaciones(valor, checkbox);
        } else {
            // Si el usuario cancela, vuelve a marcar el checkbox específico
            $(checkbox).prop('checked', true);
        }
    })
}

function modal_examen_fisico_regional() {
    $('.chx_ef').click(function () {
        currentCheckbox = $(this);
        var isChecked = $(this).prop('checked');
        var valor = $(this).val();
        $('#id_valor').val(valor);

        if (!isChecked) {
            delete_datos(valor, this);
        } else {
            $('#title_ef').html(valor);
            $('#modal_ef').modal('show');
        }
    });

    $('#btn_ef_modal').click(function () {
        if (currentCheckbox) {
            currentCheckbox.prop('checked', false); // Solo desmarcar el checkbox actual
            currentCheckbox = null; // Reinicia la referencia al checkbox actual
        }
    });
}

var ef_observaciones = [];

function agregar_obs_ef() {
    var id_valor = $('#id_valor').val();
    var texto = $('#observaciones_temp').val();

    if (id_valor && texto) {
        ef_observaciones.push({ valor: id_valor, texto: texto });
        console.log('Observaciones:', ef_observaciones);
    } else {
        Swal.fire('Error', 'Campo vacío Observación.', 'error');
        return;
    }

    mostrar_ef_observaciones();

    $('#observaciones_temp').val('');
    $('#modal_ef').modal('hide');
}

function mostrar_ef_observaciones() {
    var cadenaTexto = "";
    ef_observaciones.forEach(function (observacion) {
        cadenaTexto += observacion.valor + ": " + observacion.texto + "\n";
    });

    $('#sa_examen_fisico_regional_obs').val(cadenaTexto);
}

function eliminar_ef_observaciones(valor, checkbox) {
    ef_observaciones = ef_observaciones.filter(function (observacion) {
        return observacion.valor !== valor; // Retorna true si el valor no es igual al valor proporcionado
    });

    mostrar_ef_observaciones();

    $(checkbox).prop('checked', false);
}








