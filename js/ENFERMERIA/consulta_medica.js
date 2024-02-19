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
                            if (item.sa_conp_desde_hora.date == null || item.sa_conp_fecha_ingreso.date == null) {
                                return '';
                            } else {
                                return fecha_nacimiento_formateada(item.sa_conp_fecha_creacion.date) + ' / ' + obtener_hora_formateada(item.sa_conp_fecha_creacion.date);
                            }
                        }
                    },
                    {
                        data: null,
                        render: function (data, type, item) {
                            if (item.sa_conp_desde_hora.date == null || item.sa_conp_hasta_hora.date == null) {
                                return '';
                            } else {
                                return fecha_nacimiento_formateada(item.sa_conp_fecha_ingreso.date) + ' / [' + obtener_hora_formateada(item.sa_conp_desde_hora.date) + ' / ' + obtener_hora_formateada(item.sa_conp_hasta_hora.date) + ']';
                            }
                        }
                    },
                    {
                        data: null,
                        render: function (data, type, item) {
                            if (item.sa_conp_tipo_consulta == 'consulta') {
                                return '<div class="badge rounded-pill text-success bg-light-success p-2 text-uppercase px-3">' + item.sa_conp_tipo_consulta + '</div>';
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
