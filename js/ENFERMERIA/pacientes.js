//Aqui llega para que alguno de las tablas vea si existe o no el paciente 
function gestion_paciente_comunidad(sa_pac_id_comunidad, sa_pac_tabla, btn_regresar = '') {
    //Actualizacion de idukay
    if (sa_pac_tabla == 'estudiantes') {
        $.ajax({
            url: '../controlador/SALUD_INTEGRAL/cat_configuracionGC_IDUKAY.php?idukay_actualizar_estudiante=true',
            data: {
                id_estudiante: sa_pac_id_comunidad
            },
            type: 'post',
            dataType: 'json',
            success: function (response) {
                //console.log(response);
                if (response == 1) {
                    Swal.fire({
                        title: '',
                        text: 'Actualización con Idukay exitosa.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            ejecutar_gestion_paciente_comunidad(sa_pac_id_comunidad, sa_pac_tabla, btn_regresar);
                        }
                    });
                } else if (response == -10) {
                    Swal.fire({
                        title: '',
                        text: 'Error de Actualización.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            ejecutar_gestion_paciente_comunidad(sa_pac_id_comunidad, sa_pac_tabla, btn_regresar);
                        }
                    });
                } else if (response == -11) {
                    Swal.fire({
                        title: '',
                        text: 'Error al conectarse con la API de Idukay.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            ejecutar_gestion_paciente_comunidad(sa_pac_id_comunidad, sa_pac_tabla, btn_regresar);
                        }
                    });
                }
            },
            error: function () {
                $('#pnl_idukay').html('<p>Error al cargar los configs.</p>');
            }
        });
    } else {
        // Si no es estudiantes, ejecutar directamente el segundo ajax
        ejecutar_gestion_paciente_comunidad(sa_pac_id_comunidad, sa_pac_tabla, btn_regresar);
    }
}

function ejecutar_gestion_paciente_comunidad(sa_pac_id_comunidad, sa_pac_tabla, btn_regresar) {
    $.ajax({
        data: {
            sa_pac_id_comunidad: sa_pac_id_comunidad,
            sa_pac_tabla: sa_pac_tabla
        },
        url: '../controlador/SALUD_INTEGRAL/ficha_MedicaC.php?administrar_comunidad_ficha_medica=true',
        type: 'post',
        dataType: 'json',
        success: function (response) {
            // Crear un formulario dinámicamente
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
            agregarCampo('sa_pac_id', response.sa_pac_id);
            agregarCampo('sa_pac_tabla', response.sa_pac_tabla);
            agregarCampo('btn_regresar', btn_regresar);
            localStorage.setItem("sa_pac_id", response.sa_pac_id);
            localStorage.setItem("sa_pac_tabla", response.sa_pac_tabla);
            localStorage.setItem("btn_regresar", btn_regresar);

            if (btn_regresar != '') {
                agregarCampo('btn_regresar', btn_regresar);
            }

            // Agregar el formulario al cuerpo del documento
            document.body.appendChild(form);

            // Enviar el formulario
            form.submit();
        }
    });
}

function gestion_paciente_comunidad_pacientes(sa_pac_id_comunidad, sa_pac_tabla) {
    //alert(sa_pac_id_comunidad)
    $.ajax({
        data: {
            sa_pac_id_comunidad: sa_pac_id_comunidad,
            sa_pac_tabla: sa_pac_tabla
        },
        url: '../controlador/SALUD_INTEGRAL/ficha_MedicaC.php?administrar_comunidad_ficha_medica=true',
        type: 'post',
        dataType: 'json',

        success: function (response) {
            //location.href = '../vista/inicio.php?mod=7&acc=pacientes';
        }
    });
}


