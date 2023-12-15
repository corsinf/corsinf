function smartwizard_ficha_medica() {
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
            toolbarPosition: '', // both bottom
        },
        lang: {
            next: 'Siguiente',
            previous: 'Anterior'
        }
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



