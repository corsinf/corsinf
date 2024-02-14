

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

        console.log(sa_pac_id);
        console.log(sa_pac_tabla);

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
        console.log(this)
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


