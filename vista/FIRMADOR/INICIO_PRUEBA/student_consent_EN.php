<script src="../lib/jquery_validation/jquery.validate.js"></script>

<head>
    <style>
        .placeholder-option {
            color: #6c757d;
            opacity: 0.5;
        }

        label.error {
            color: red;
            /* Cambia "red" por el color que desees */

        }
    </style>
</head>
<script>
    $(document).ready(function() {
        cargarDatos();
    });

    function cargarDatos() {
        $.ajax({
            url: '../controlador/FORMULARIOS/student_consentC.php?listar=true',
            type: 'post',
            data: {
                id: 2
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Manejo de errores
                console.error('Error al cargar los configs:', textStatus, errorThrown);
                $('#pnl_config_general').append('<p>Error al cargar las configuraciones. Por favor, inténtalo de nuevo más tarde.</p>');
            }
        });
    }

    function editar_insertar() {

        var txt_student = $('#txt_student').val();
        var txt_id_student = $('#txt_id_student').val();
        var txt_birth_date = $('#txt_birth_date').val();
        var txt_purpose_authorization = $('#txt_purpose_authorization').val();
        var txt_first_authorized_name = $('#txt_first_authorized_name').val();
        var txt_first_relationship = $('#txt_first_relationship').val();
        var txt_first_address = $('#txt_first_address').val();
        var txt_first_email = $('#txt_first_email').val();
        var txt_second_authorized_name = $('#txt_second_authorized_name').val();
        var txt_second_relationship = $('#txt_second_relationship').val();
        var txt_second_address = $('#txt_second_address').val();
        var txt_second_email = $('#txt_second_email').val();
        /*var edu_firma_estudiante = $('#').val();
        var edu_fecha_firma = $('#').val();
        var edu_nombre_registro = $('#').val();
        var edu_fecha_registro = $('#').val();
        var edu_estado = $('#').val();
        var edu_fecha_creacion = $('#').val();*/
        var cbx_academic_info = $('#cbx_academic_info').prop('checked');
        var cbx_admission = $('#cbx_admission').prop('checked');
        var cbx_registration = $('#cbx_registration').prop('checked');
        var cbx_grades = $('#cbx_grades').prop('checked');
        var cbx_gpa = $('#cbx_gpa').prop('checked');
        var cbx_standing = $('#cbx_standing').prop('checked');
        var cbx_graduation = $('#cbx_graduation').prop('checked');
        var cbx_financial_info = $('#cbx_financial_info').prop('checked');
        var cbx_fees = $('#cbx_fees').prop('checked');
        var cbx_charges = $('#cbx_charges').prop('checked');
        var cbx_payments = $('#cbx_payments').prop('checked');
        var cbx_aid_info = $('#cbx_aid_info').prop('checked');
        var cbx_housing_info = $('#cbx_housing_info').prop('checked');
        var cbx_location = $('#cbx_location').prop('checked');
        var cbx_room = $('#cbx_room').prop('checked');
        var cbx_judicial = $('#cbx_judicial').prop('checked');
        var cbx_remove_consent = $('#cbx_remove_consent').prop('checked');

        var parametros = {

            //'edu_id': edu_id,
            'txt_student': txt_student,
            'txt_id_student': txt_id_student,
            'txt_birth_date': txt_birth_date,
            'txt_purpose_authorization': txt_purpose_authorization,
            'txt_first_authorized_name': txt_first_authorized_name,
            'txt_first_relationship': txt_first_relationship,
            'txt_first_address': txt_first_address,
            'txt_first_email': txt_first_email,
            'txt_second_authorized_name': txt_second_authorized_name,
            'txt_second_relationship': txt_second_relationship,
            'txt_second_address': txt_second_address,
            'txt_second_email': txt_second_email,
            /*'edu_firma_estudiante': edu_firma_estudiante,
            'edu_fecha_firma': edu_fecha_firma,
            'edu_nombre_registro': edu_nombre_registro,
            'edu_fecha_registro': edu_fecha_registro,
            'edu_estado': edu_estado,
            'edu_fecha_creacion': edu_fecha_creacion,*/
            'cbx_academic_info': cbx_academic_info,
            'cbx_admission': cbx_admission,
            'cbx_registration': cbx_registration,
            'cbx_grades': cbx_grades,
            'cbx_gpa': cbx_gpa,
            'cbx_standing': cbx_standing,
            'cbx_graduation': cbx_graduation,
            'cbx_financial_info': cbx_financial_info,
            'cbx_fees': cbx_fees,
            'cbx_charges': cbx_charges,
            'cbx_payments': cbx_payments,
            'cbx_aid_info': cbx_aid_info,
            'cbx_housing_info': cbx_housing_info,
            'cbx_location': cbx_location,
            'cbx_room': cbx_room,
            'cbx_judicial': cbx_judicial,
            'cbx_remove_consent': cbx_remove_consent
        };

        if ($("#form_student_consent").valid()) {
            // Si es válido, puedes proceder a enviar los datos por AJAX
            console.log(parametros);
            insertar(parametros);
        }

    }

    function insertar(parametros) {
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/FORMULARIOS/student_consentC.php?insertar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                        //location.href = '../vista/inicio.php?mod=7&acc=estudiantes';
                    });
                } else if (response == -2) {
                    Swal.fire('', 'Operación fallida', 'warning');
                }
            }
        });
    }
</script>
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Nueva Vista</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Student Consent</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-10 mx-auto">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body p-5">
                        <div class="card-title d-flex align-items-center">
                            <div>
                                <h3>CONSENT FOR RELEASE</h3>
                                <h5>of Student Information</h5>
                                <form id="form_student_consent">
                                    <div class="py-3">
                                        <div class="row g-2 align-items-center mb-2">
                                            <div class="col-4 col-lg-1 col-sm-2">
                                                <label for="txt_student" class="col-auto col-form-label fw-bold">Student: <label style="color: red;">*</label></label>
                                            </div>
                                            <div class="col-7 col-sm-7 has-validation">
                                                <input type="text" class="form-control form-control-sm" name="txt_student" id="txt_student" placeholder="First, Middle & Last Name" maxlength="200">
                                            </div>
                                        </div>
                                        <div class="row g-2 align-items-center mb-2">
                                            <div class="col-4 col-lg-1 col-sm-2">
                                                <label for="txt_id_student" class="col-auto col-form-label fw-bold">Student ID: <label style="color: red;">*</label></label>
                                            </div>
                                            <div class="col-7 col-sm-7 has-validation">
                                                <input type="text" class="form-control form-control-sm" name="txt_id_student" id="txt_id_student" maxlength="200">
                                            </div>
                                        </div>
                                        <div class="row g-2 align-items-center mb-3">
                                            <div class="col-4 col-lg-1 col-sm-2">
                                                <label for="txt_birth_date" class="col-auto col-form-label fw-bold">Birth Date: <label style="color: red;">*</label></label>
                                            </div>
                                            <div class="col-7 col-sm-7 has-validation">
                                                <input type="date" class="form-control form-control-sm" name="txt_birth_date" id="txt_birth_date">
                                            </div>
                                        </div>

                                        <p class="mb-3 fw-bold">I hereby authorize the University of Idaho to discuss and verbally release the following information: </p>

                                        <div class="form-check mb-3">
                                            <input type="checkbox" class="form-check-input" name="cbx_academic_info" id="cbx_academic_info">
                                            <label for="cbx_academic_info" class="form-check-label mb-2"><strong>ALL</strong> academic information <strong>OR</strong> these individual items: <label style="color: red;">*</label></label>
                                            <div class="row mb-4 input-group">
                                                <div class="col-12 col-lg-4 col-md-4 col-sm-12">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input cbx_items_academic_info" name="cbx_admission" id="cbx_admission">
                                                        <label for="cbx_admission" class="form-check-label">Admission</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input cbx_items_academic_info" name="cbx_gpa" id="cbx_gpa">
                                                        <label for="cbx_gpa" class="form-check-label">GPA</label>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-4 col-md-4 col-sm-12">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input cbx_items_academic_info" name="cbx_registration" id="cbx_registration">
                                                        <label for="cbx_registration" class="form-check-label">Registration / Enrollment</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input cbx_items_academic_info" name="cbx_standing" id="cbx_standing">
                                                        <label for="cbx_standing" class="form-check-label">Academic Standing</label>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-4 col-md-4 col-sm-12">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input cbx_items_academic_info" name="cbx_grades" id="cbx_grades">
                                                        <label for="cbx_grades" class="form-check-label">Grades</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input cbx_items_academic_info" name="cbx_graduation" id="cbx_graduation">
                                                        <label for="cbx_graduation" class="form-check-label">Graduation</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="checkbox" class="form-check-input" name="cbx_financial_info" id="cbx_financial_info">
                                            <label for="cbx_financial_info" class="form-check-label mb-2"><strong>ALL</strong> financial account information <strong>OR</strong> these individual items: <label style="color: red;">*</label></label>
                                            <div class="row mb-4 ms-2 input-group">
                                                <div class="col-12 col-lg-4 col-md-4 col-sm-12">
                                                    <div>
                                                        <input type="checkbox" class="form-check-input cbx_items_financial_info" name="cbx_fees" id="cbx_fees">
                                                        <label for="cbx_fees" class="form-check-label">Fees</label>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-4">
                                                    <div>
                                                        <input type="checkbox" class="form-check-input cbx_items_financial_info" name="cbx_charges" id="cbx_charges">
                                                        <label for="cbx_charges" class="form-check-label">Charges</label>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-4">
                                                    <div>
                                                        <input type="checkbox" class="form-check-input cbx_items_financial_info" name="cbx_payments" id="cbx_payments">
                                                        <label for="cbx_payments" class="form-check-label">Payments</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-4">
                                                <input type="checkbox" class="form-check-input" name="cbx_aid_info" id="cbx_aid_info">
                                                <label for="cbx_aid_info" class="form-check-label"><strong>ALL</strong> financial aid information <label style="color: red;">*</label></label>
                                            </div>
                                            <input type="checkbox" class="form-check-input" name="cbx_housing_info" id="cbx_housing_info">
                                            <label for="cbx_housing_info" class="form-check-label mb-2"><strong>ALL</strong> university housing information <strong>OR</strong> these individual items: <label style="color: red;">*</label></label>
                                            <div class="row mb-4 ms-2 input-group">
                                                <div class="col-12 col-lg-4">
                                                    <div>
                                                        <input type="checkbox" class="form-check-input cbx_items_housing_info" name="cbx_location" id="cbx_location">
                                                        <label for="cbx_location" class="form-check-label">Location</label>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-4">
                                                    <div>
                                                        <input type="checkbox" class="form-check-input cbx_items_housing_info" name="cbx_room" id="cbx_room">
                                                        <label for="cbx_room" class="form-check-label">Room Assignment</label>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-4">
                                                    <div>
                                                        <input type="checkbox" class="form-check-input cbx_items_housing_info" name="cbx_judicial" id="cbx_judicial">
                                                        <label for="cbx_judicial" class="form-check-label">Judicial Matters</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class=" mb-2">
                                            <div class="col-12 col-lg-12">
                                                <div class="mb-3 row">
                                                    <div class="col-12 col-lg-4">
                                                        <label for="txt_purpose_authorization" class="col-auto col-form-label fw-bold">My authorization is for the following purpose: <label style="color: red;">*</label></label>
                                                    </div>
                                                    <div class="col-12 col-lg-8">
                                                        <textarea class="form-control form-control-sm w-100" name="txt_purpose_authorization" id="txt_purpose_authorization" maxlength="500"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-lg-12">
                                        <div class="card bg-secondary bg-opacity-10 border border-dark border-opacity-10 mb-4">
                                            <div class="card-body">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" name="cbx_remove_consent" id="cbx_remove_consent">
                                                    <label for="cbx_remove_consent" class="form-check-label">I request to <strong>REMOVE</strong> my consent allowing UI to discuss and verbally release information to all currently designated individuals. <label style="color: red;">*</label></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <p class="fw-bold">I give consent for the following individual(s) to obtain the authorized information on request</p>
                                    <p>(all information required): <label style="color: red;">*</label></p>
                                    <label for="txt_first_authorized_name" class="col-auto col-form-label fw-bold">1.</label>
                                    <div class="row pb-4">
                                        <div class="col-12 col-lg-6">
                                            <div class="mb-3 row">
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control form-control-sm" name="txt_first_authorized_name" id="txt_first_authorized_name" placeholder="Printed Name" maxlength="200">
                                                </div>
                                            </div>
                                            <input type="text" class="form-control form-control-sm mb-3" name="txt_first_address" id="txt_first_address" placeholder="Complete Address" maxlength="500">
                                        </div>
                                        <div class="col-12 col-lg-6">
                                            <select class="form-select form-select-sm" id="txt_first_relationship" name="txt_first_relationship">
                                                <option selected disabled value="">-- Select a relationship --</option>
                                                <option value="Padre">Padre</option>
                                                <option value="Madre">Madre</option>
                                                <option value="Hermano">Hermano/a</option>
                                                <option value="Tio">Tío/a</option>
                                                <option value="Primo">Primo/a</option>
                                                <option value="Abuelo/a">Abuelo/a</option>
                                                <option value="Otro">Otro/a</option>
                                            </select>
                                            <input type="email" class="form-control form-control-sm mt-3" name="txt_first_email" id="txt_first_email" placeholder="Email" maxlength="200">
                                        </div>
                                    </div>
                                    <label for="txt_second_authorized_name" class="col-auto col-form-label fw-bold">2.</label>
                                    <div class="row pb-4">
                                        <div class="col-12 col-lg-6">
                                            <div class="mb-3 row">
                                                <div class="col-lg-12">
                                                    <input type="text" class="form-control form-control-sm" name="txt_second_authorized_name" id="txt_second_authorized_name" placeholder="Printed Name" maxlength="200">
                                                </div>
                                            </div>
                                            <input type="text" class="form-control form-control-sm mb-3" name="txt_second_address" id="txt_second_address" placeholder="Complete Address" maxlength="500">
                                        </div>
                                        <div class="col-12 col-lg-6">
                                            <select class="form-select form-select-sm" id="txt_second_relationship" name="txt_second_relationship">
                                                <option selected disabled value="">-- Select a relationship --</option>
                                                <option value="Padre">Padre</option>
                                                <option value="Madre">Madre</option>
                                                <option value="Hermano">Hermano/a</option>
                                                <option value="Tio">Tío/a</option>
                                                <option value="Primo">Primo/a</option>
                                                <option value="Abuelo/a">Abuelo/a</option>
                                                <option value="Otro">Otro/a</option>
                                            </select>
                                            <input type="email" class="form-control form-control-sm mt-3" name="txt_second_email" id="txt_second_email" placeholder="Email" maxlength="200">
                                        </div>
                                    </div>
                                    <p class="py-4">I understand that this information is considered a student education, financial, and/or housing record. Further, I understand that by
                                        signing this release, I am waiving my right to keep this information confidential under the Family Educational Rights and Privacy Act
                                        (FERPA). I certify that my consent for disclosure of this information is entirely voluntary. I understand this consent for disclosure of
                                        information can be revoked by me in writing at any time, but will not affect the information released under my previous consent. If I wish
                                        to make any changes to my consent for release, I understand I will need to complete and file a new form. <strong>The authorization on this
                                            form will supersede all prior authorizations for release of my information.</strong></p>
                                    <div class="modal-footer pt-4">
                                        <button class="btn btn-primary btn-sm px-4 m-1" onclick="editar_insertar();" type="button"><i class="bx bx-save" id="btn_guardar"></i> Guardar</button>
                                        <?php ?>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    //Validacion de formulario
    $(document).ready(function() {
        $("#form_student_consent").validate({
            rules: {
                txt_student: {
                    required: true,
                },
                txt_id_student: {
                    required: true,
                },
                txt_birth_date: {
                    required: true,
                },
                cbx_academic_info: {
                    required: true,
                },
                cbx_financial_info: {
                    required: true,
                },
                cbx_aid_info: {
                    required: true,
                },
                cbx_housing_info: {
                    required: true,
                },
                txt_purpose_authorization: {
                    required: true,
                },
                cbx_remove_consent: {
                    required: true,
                },
                txt_first_authorized_name: {
                    required: true,
                },
                txt_first_address: {
                    required: true,
                },
                txt_first_relationship: {
                    required: true,
                },
                txt_first_email: {
                    required: true,
                },
                txt_second_authorized_name: {
                    required: true,
                },
                txt_second_address: {
                    required: true,
                },
                txt_second_relationship: {
                    required: true,
                },
                txt_second_email: {
                    required: true,
                },
            },
            messages: {
                txt_student: {
                    required: "Please provide a valid name.",
                },
                txt_id_student: {
                    required: "Please provide a valid ID.",
                },
                txt_birth_date: {
                    required: "Please provide a valid birth date.",
                },
                cbx_academic_info: {
                    required: "You must select at least one.",
                },
                cbx_financial_info: {
                    required: "You must select at least one.",
                },
                cbx_aid_info: {
                    required: "You must select this.",
                },
                cbx_housing_info: {
                    required: "You must select at least one.",
                },
                txt_purpose_authorization: {
                    required: "Please provide a valid purpose.",
                },
                cbx_remove_consent: {
                    required: "You must select this.",
                },
                txt_first_authorized_name: {
                    required: "Please provide a valid name.",
                },
                txt_first_address: {
                    required: "Please provide a valid address.",
                },
                txt_first_relationship: {
                    required: "Please provide a valid relationship.",
                },
                txt_first_email: {
                    required: "Please provide a valid email.",
                },
                txt_second_authorized_name: {
                    required: "Please provide a valid name.",
                },
                txt_second_address: {
                    required: "Please provide a valid address.",
                },
                txt_second_relationship: {
                    required: "Please provide a valid relationship.",
                },
                txt_second_email: {
                    required: "Please provide a valid email.",
                },
            },

            highlight: function(element) {
                // Agrega la clase 'is-invalid' al input que falla la validación
                $(element).addClass('is-invalid');
                $(element).removeClass('is-valid');
            },
            unhighlight: function(element) {
                // Elimina la clase 'is-invalid' si la validación pasa
                $(element).removeClass('is-invalid');
                $(element).addClass('is-valid');

            }
        });
    });
</script>
<script>
    $(document).ready(function() {
        cambiar_checkboxes_dependientes('#cbx_academic_info', '.cbx_items_academic_info');
        cambiar_checkboxes_dependientes('#cbx_financial_info', '.cbx_items_financial_info');
        cambiar_checkboxes_dependientes('#cbx_housing_info', '.cbx_items_housing_info');

        require_checkboxes_dependientes('#cbx_academic_info', '.cbx_items_academic_info');
        require_checkboxes_dependientes('#cbx_financial_info', '.cbx_items_financial_info');
        require_checkboxes_dependientes('#cbx_housing_info', '.cbx_items_housing_info');
    });

    function cambiar_checkboxes_dependientes(checkbox_principal, checkboxes_dependientes) {
        $(document).on('change', checkbox_principal, function() {
            if ($(this).is(':checked')) {
                $(checkboxes_dependientes).prop('disabled', true).prop('checked', false);
            } else {
                $(checkboxes_dependientes).prop('disabled', false);
            }
        });
    }

    function require_checkboxes_dependientes(checkbox_principal, checkboxes_dependientes) {
        $(document).on('change', checkboxes_dependientes, function() {
            var cualquier_checkbox = $(checkboxes_dependientes + ':checked').length > 0;
            $(checkbox_principal).prop('required', !cualquier_checkbox);
            if (cualquier_checkbox) {
                $(checkbox_principal).rules("remove", "required");
            } else {
                $(checkbox_principal).rules("add", {
                    required: true
                });
            }
            $("#form_student_consent").validate().element(checkbox_principal);
        });
    }
</script>