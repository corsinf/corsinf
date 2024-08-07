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

        var edu_nombre_estudiante = $('#txt_student').val();
        var edu_id_estudiante = $('#txt_id_student').val();
        var edu_fecha_nacimiento = $('#txt_birth_date').val();
        var edu_proposito_autorizacion = $('#txt_purpose_authorization').val();
        var edu_primer_nombre_autorizado = $('#txt_first_authorized_name').val();
        var edu_primer_relacion_autorizada = $('#txt_first_relationship').val();
        var edu_primera_direccion_autorizada = $('#txt_first_address').val();
        var edu_primer_email_autorizado = $('#txt_first_email').val();
        var edu_segundo_nombre_autorizado = $('#txt_second_authorized_name').val();
        var edu_segunda_relacion_autorizada = $('#txt_second_relationship').val();
        var edu_segunda_direccion_autorizada = $('#txt_second_address').val();
        var edu_segundo_email_autorizado = $('#txt_second_email').val();
        /*var edu_firma_estudiante = $('#').val();
        var edu_fecha_firma = $('#').val();
        var edu_nombre_registro = $('#').val();
        var edu_fecha_registro = $('#').val();
        var edu_estado = $('#').val();
        var edu_fecha_creacion = $('#').val();*/
        var edu_cbx_academic_all = $('#cbx_academic_info').prop('checked');
        var edu_cbx_academic_1 = $('#cbx_admission').prop('checked');
        var edu_cbx_academic_2 = $('#cbx_registration').prop('checked');
        var edu_cbx_academic_3 = $('#cbx_grades').prop('checked');
        var edu_cbx_academic_4 = $('#cbx_gpa').prop('checked');
        var edu_cbx_academic_5 = $('#cbx_standing').prop('checked');
        var edu_cbx_academic_6 = $('#cbx_graduation').prop('checked');
        var edu_cbx_financial_all = $('#cbx_financial_info').prop('checked');
        var edu_cbx_financial_1 = $('#cbx_fees').prop('checked');
        var edu_cbx_financial_2 = $('#cbx_charges').prop('checked');
        var edu_cbx_financial_3 = $('#cbx_payments').prop('checked');
        var edu_cbx_aid_financial = $('#cbx_aid_info').prop('checked');
        var edu_cbx_housing_all = $('#cbx_housing_info').prop('checked');
        var edu_cbx_housing_1 = $('#cbx_location').prop('checked');
        var edu_cbx_housing_2 = $('#cbx_room').prop('checked');
        var edu_cbx_housing_3 = $('#cbx_judicial').prop('checked');
        var edu_cbx_remove_consent = $('#cbx_remove_consent').prop('checked');



        var parametros = {

            //'edu_id': edu_id,
            'edu_nombre_estudiante': edu_nombre_estudiante,
            'edu_id_estudiante': edu_id_estudiante,
            'edu_fecha_nacimiento': edu_fecha_nacimiento,
            'edu_proposito_autorizacion': edu_proposito_autorizacion,
            'edu_primer_nombre_autorizado': edu_primer_nombre_autorizado,
            'edu_primer_relacion_autorizada': edu_primer_relacion_autorizada,
            'edu_primera_direccion_autorizada': edu_primera_direccion_autorizada,
            'edu_primer_email_autorizado': edu_primer_email_autorizado,
            'edu_segundo_nombre_autorizado': edu_segundo_nombre_autorizado,
            'edu_segunda_relacion_autorizada': edu_segunda_relacion_autorizada,
            'edu_segunda_direccion_autorizada': edu_segunda_direccion_autorizada,
            'edu_segundo_email_autorizado': edu_segundo_email_autorizado,
            /*'edu_firma_estudiante': edu_firma_estudiante,
            'edu_fecha_firma': edu_fecha_firma,
            'edu_nombre_registro': edu_nombre_registro,
            'edu_fecha_registro': edu_fecha_registro,
            'edu_estado': edu_estado,
            'edu_fecha_creacion': edu_fecha_creacion,*/
            'edu_cbx_academic_all': edu_cbx_academic_all,
            'edu_cbx_academic_1': edu_cbx_academic_1,
            'edu_cbx_academic_2': edu_cbx_academic_2,
            'edu_cbx_academic_3': edu_cbx_academic_3,
            'edu_cbx_academic_4': edu_cbx_academic_4,
            'edu_cbx_academic_5': edu_cbx_academic_5,
            'edu_cbx_academic_6': edu_cbx_academic_6,
            'edu_cbx_financial_all': edu_cbx_financial_all,
            'edu_cbx_financial_1': edu_cbx_financial_1,
            'edu_cbx_financial_2': edu_cbx_financial_2,
            'edu_cbx_financial_3': edu_cbx_financial_3,
            'edu_cbx_aid_financial': edu_cbx_aid_financial,
            'edu_cbx_housing_all': edu_cbx_housing_all,
            'edu_cbx_housing_1': edu_cbx_housing_1,
            'edu_cbx_housing_2': edu_cbx_housing_2,
            'edu_cbx_housing_3': edu_cbx_housing_3,
            'edu_cbx_remove_consent': edu_cbx_remove_consent
        };

        //alert(validar_email(sa_est_correo));
        console.log(parametros);

        if (sa_est_id == '') {
            if (
                sa_est_primer_apellido === '' ||
                sa_est_segundo_apellido === '' ||
                sa_est_primer_nombre === '' ||
                sa_est_segundo_nombre === '' ||
                sa_est_cedula === '' ||
                sa_est_sexo == null ||
                sa_est_fecha_nacimiento === '' ||
                sa_id_seccion == null ||
                sa_id_grado == null ||
                sa_id_paralelo == null ||
                validar_email(sa_est_correo) == false ||
                sa_id_representante == null ||
                sa_est_rep_parentesco == null

            ) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Asegurese de llenar todos los campos',
                })

            } else {
                //console.log(parametros);
                insertar(parametros)
            }
        } else {
            if (
                sa_est_primer_apellido === '' ||
                sa_est_segundo_apellido === '' ||
                sa_est_primer_nombre === '' ||
                sa_est_segundo_nombre === '' ||
                sa_est_cedula === '' ||
                sa_est_sexo == null ||
                sa_est_fecha_nacimiento === '' ||
                sa_id_seccion == null ||
                sa_id_grado == null ||
                sa_id_paralelo == null ||
                validar_email(sa_est_correo) == false ||
                sa_id_representante == null ||
                sa_est_rep_parentesco == null
            ) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Asegurese de llenar todos los campos',
                })
            } else {
                //console.log(parametros);
                insertar(parametros);
            }
        }
    }

    function insertar(parametros) {
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/estudiantesC.php?insertar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                        //location.href = '../vista/inicio.php?mod=7&acc=estudiantes';
                    });
                } else if (response == -2) {
                    Swal.fire('', 'Cédula ya registrada.', 'warning');
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
            <div class="col-xl-12 mx-auto">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body p-5">
                        <div class="card-title d-flex align-items-center">
                            <div>
                                <h3>CONSENT FOR RELEASE</h3>
                                <h5>of Student Information</h5>
                                <div>
                                    <div class="row py-3">
                                        <div class="col-12 col-lg-8">
                                            <form action="">
                                                <div class="mb-3 row">
                                                    <label for="txt_student" class="col-sm-1 col-form-label"><strong>Student:</strong></label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" name="txt_student" id="txt_student">
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <form action="">
                                                <div class="row g-3 align-items-center mb-3">
                                                    <div class="col-auto">
                                                        <label for="txt_id_student" class="col-form-label"><strong>Student ID:</strong></label>
                                                    </div>
                                                    <div class="col-auto">
                                                        <input type="text" class="form-control" name="txt_id_student" id="txt_id_student">
                                                    </div>
                                                </div>
                                                <div class="row g-3 align-items-center">
                                                    <div class="col-auto">
                                                        <label for="txt_birth_date" class="col-form-label"><strong>Birth Date:</strong></label>
                                                    </div>
                                                    <div class="col-auto">
                                                        <input type="date" class="form-control" name="txt_birth_date" id="txt_birth_date">
                                                    </div>
                                            </form>
                                        </div>
                                    </div>

                                    <p class="py-3"><strong>I hereby authorize the University of Idaho to discuss and verbally release the following information: </strong></p>

                                    <div class="form-check">
                                        <form action="">
                                            <input type="checkbox" class="form-check-input" name="cbx_academic_info" id="cbx_academic_info">
                                            <label for="cbx_academic_info" class="form-check-label"><strong>ALL</strong> academic information <strong>OR</strong> these individual items:</label>
                                            <div class="row p-4">
                                                <div class="col-12 col-lg-2">
                                                    <div>
                                                        <input type="checkbox" class="form-check-input cbx_items_academic_info" name="cbx_admission" id="cbx_admission">
                                                        <label for="cbx_admission" class="form-check-label">Admission</label>
                                                    </div>
                                                    <div>
                                                        <input type="checkbox" class="form-check-input cbx_items_academic_info" name="cbx_gpa" id="cbx_gpa">
                                                        <label for="cbx_gpa" class="form-check-label">GPA</label>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-2">
                                                    <div>
                                                        <input type="checkbox" class="form-check-input cbx_items_academic_info" name="cbx_registration" id="cbx_registration">
                                                        <label for="cbx_registration" class="form-check-label">Registration/Enrollment</label>
                                                    </div>
                                                    <div>
                                                        <input type="checkbox" class="form-check-input cbx_items_academic_info" name="cbx_standing" id="cbx_standing">
                                                        <label for="cbx_standing" class="form-check-label">Academic Standing</label>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-2">
                                                    <div>
                                                        <input type="checkbox" class="form-check-input cbx_items_academic_info" name="cbx_grades" id="cbx_grades">
                                                        <label for="cbx_grades" class="form-check-label">Grades</label>
                                                    </div>
                                                    <div>
                                                        <input type="checkbox" class="form-check-input cbx_items_academic_info" name="cbx_graduation" id="cbx_graduation">
                                                        <label for="cbx_graduation" class="form-check-label">Graduation</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <form action="">
                                            <input type="checkbox" class="form-check-input" name="cbx_financial_info" id="cbx_financial_info">
                                            <label for="cbx_financial_info" class="form-check-label"><strong>ALL</strong> financial account information <strong>OR</strong> these individual items:</label>
                                            <div class="row p-4">
                                                <div class="col-12 col-lg-2">
                                                    <div>
                                                        <input type="checkbox" class="form-check-input cbx_items_financial_info" name="cbx_fees" id="cbx_fees">
                                                        <label for="cbx_fees" class="form-check-label">Fees</label>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-2">
                                                    <div>
                                                        <input type="checkbox" class="form-check-input cbx_items_financial_info" name="cbx_charges" id="cbx_charges">
                                                        <label for="cbx_charges" class="form-check-label">Charges</label>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-2">
                                                    <div>
                                                        <input type="checkbox" class="form-check-input cbx_items_financial_info" name="cbx_payments" id="cbx_payments">
                                                        <label for="cbx_payments" class="form-check-label">Payments</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <form action="">
                                            <div class="py-4">
                                                <input type="checkbox" class="form-check-input" name="cbx_aid_info" id="cbx_aid_info">
                                                <label for="cbx_aid_info" class="form-check-label"><strong>ALL</strong> financial aid information</label>
                                            </div>
                                        </form>
                                        <form action="">
                                            <input type="checkbox" class="form-check-input" name="cbx_housing_info" id="cbx_housing_info">
                                            <label for="cbx_housing_info" class="form-check-label"><strong>ALL</strong> university housing information <strong>OR</strong> these individual items:</label>
                                            <div class="row p-4">
                                                <div class="col-12 col-lg-2">
                                                    <div>
                                                        <input type="checkbox" class="form-check-input cbx_items_housing_info" name="cbx_location" id="cbx_location">
                                                        <label for="cbx_location" class="form-check-label">Location</label>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-2">
                                                    <div>
                                                        <input type="checkbox" class="form-check-input cbx_items_housing_info" name="cbx_room" id="cbx_room">
                                                        <label for="cbx_room" class="form-check-label">Room Assignment</label>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-2">
                                                    <div>
                                                        <input type="checkbox" class="form-check-input cbx_items_housing_info" name="cbx_judicial" id="cbx_judicial">
                                                        <label for="cbx_judicial" class="form-check-label">Judicial Matters</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="row">
                                        <div class="col-12 col-lg-12">
                                            <div class="mb-3 row">
                                                <label for="txt_purpose_authorization" class="col-sm-2 col-form-label"><strong>My authorization is for the following purpose:</strong></label>
                                                <div class="col-sm-8">
                                                    <textarea class="form-control" name="txt_purpose_authorization" id="txt_purpose_authorization"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-9">
                                    <div class="card bg-secondary bg-opacity-10 border border-dark mb-4">
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" name="cbx_remove_consent" id="cbx_remove_consent">
                                                <label for="cbx_remove_consent" class="form-check-label">I request to <strong>REMOVE</strong> my consent allowing UI to discuss and verbally release information to all currently designated individuals.</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <p><strong>I give consent for the following individual(s) to obtain the authorized information on request</strong></p>
                                <p>(all information required):</p>
                                <form action="">
                                    <div class="row py-4">
                                        <div class="col-12 col-lg-5">
                                            <div class="mb-3 row">
                                                <label for="txt_first_authorized_name" class="col-sm-1 col-form-label"><strong>1.</strong></label>
                                                <div class="col-sm-11">
                                                    <input type="text" class="form-control" name="txt_first_authorized_name" id="txt_first_authorized_name" placeholder="Printed Name">
                                                </div>
                                            </div>
                                            <input type="text" class="form-control" name="txt_first_address" id="txt_first_address" placeholder="Complete Address">
                                        </div>
                                        <div class="col-12 col-lg-5">
                                            <input type="text" class="form-control mb-3" name="txt_first_relationship" id="txt_first_relationship" placeholder="Relationship to Student">
                                            <input type="email" class="form-control" name="txt_first_email" id="txt_first_email" placeholder="Email">
                                        </div>
                                    </div>
                                    <div class="row py-4">
                                        <div class="col-12 col-lg-5">
                                            <div class="mb-3 row">
                                                <label for="txt_second_authorized_name" class="col-sm-1 col-form-label"><strong>2.</strong></label>
                                                <div class="col-sm-11">
                                                    <input type="text" class="form-control" name="txt_second_authorized_name" id="txt_second_authorized_name" placeholder="Printed Name">
                                                </div>
                                            </div>
                                            <input type="text" class="form-control" name="txt_second_address" id="txt_second_address" placeholder="Complete Address">
                                        </div>
                                        <div class="col-12 col-lg-5">
                                            <input type="text" class="form-control mb-3" name="txt_second_relationship" id="txt_second_relationship" placeholder="Relationship to Student">
                                            <input type="email" class="form-control" name="txt_second_email" id="txt_second_email" placeholder="Email">
                                        </div>
                                    </div>
                                </form>
                                <p class="py-4">I understand that this information is considered a student education, financial, and/or housing record. Further, I understand that by
                                    signing this release, I am waiving my right to keep this information confidential under the Family Educational Rights and Privacy Act
                                    (FERPA). I certify that my consent for disclosure of this information is entirely voluntary. I understand this consent for disclosure of
                                    information can be revoked by me in writing at any time, but will not affect the information released under my previous consent. If I wish
                                    to make any changes to my consent for release, I understand I will need to complete and file a new form. <strong>The authorization on this
                                        form will supersede all prior authorizations for release of my information.</strong></p>
                            </div>
                            <div class="modal-footer pt-4">
                                <button class="btn btn-primary btn-sm px-4 m-1" onclick="editar_insertar()" type="button"><i class="bx bx-save"></i> Guardar</button>
                                <?php ?>
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
        function cambiar_checkboxes_dependientes(checkbox_principal, checkboxes_dependientes) {
            $(document).on('change', checkbox_principal, function() {
                if ($(this).is(':checked')) {
                    $(checkboxes_dependientes).prop('disabled', true).prop('checked', false);
                } else {
                    $(checkboxes_dependientes).prop('disabled', false);
                }
            });
        }

        cambiar_checkboxes_dependientes('#cbx_academic_info', '.cbx_items_academic_info');
        cambiar_checkboxes_dependientes('#cbx_financial_info', '.cbx_items_financial_info');
        cambiar_checkboxes_dependientes('#cbx_housing_info', '.cbx_items_housing_info');
    });
</script>