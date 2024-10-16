<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);

$_id = '';

if (isset($_GET['_id'])) {
    $_id = $_GET['_id'];
}

?>

<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<script>
    $(document).ready(function() {
        cargar_datos_persona(<?= $_id ?>)
        datos_col(<?= $_id ?>);
    });

    function cargar_datos_persona(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/INNOVERS/in_personasC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                console.log(response);

                nombres = `${response[0]['primer_apellido']} ${response[0]['segundo_apellido']} ${response[0]['primer_nombre']} ${response[0]['segundo_nombre']}`;

                $('#txt_student').val(nombres);
                $('#txt_id_student').val(response[0]['_id']);
                $('#txt_birth_date').val(response[0]['fecha_nacimiento']);
                $('#id_persona').val(response[0]['_id']);


                // $('#txt_primer_apellido').val(response[0]['primer_apellido']);
                // $('#txt_segundo_apellido').val(response[0]['segundo_apellido']);
                // $('#txt_primer_nombre').val(response[0]['primer_nombre']);
                // $('#txt_segundo_nombre').val(response[0]['segundo_nombre']);
                $('#txt_cedula').val(response[0]['cedula']);
                $('#ddl_sexo').val(response[0]['sexo']);
                $('#txt_fecha_nacimiento').val(response[0]['fecha_nacimiento']);
                $('#txt_correo').val(response[0]['correo']);
                $('#txt_telefono_1').val(response[0]['telefono_1']);
                $('#txt_telefono_2').val(response[0]['telefono_2']);
                $('#ddl_estado_civil').val(response[0]['estado_civil']);
                $('#txt_postal').val(response[0]['postal']);
                $('#txt_direccion').val(response[0]['direccion']);
                $('#txt_observaciones').val(response[0]['observaciones']);

                calcular_edad('txt_edad', response[0]['fecha_nacimiento']);
            }
        });
    }

    function datos_col(id) {
        $.ajax({
            url: '../controlador/INNOVERS/in_student_consentC.php?listar=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#txt_purpose_authorization').val(response[0]['proposito_autorizacion']);
                $('#txt_first_authorized_name').val(response[0]['primer_nombre_autorizado']);
                $('#txt_first_relationship').val(response[0]['primer_relacion_autorizada']);
                $('#txt_first_address').val(response[0]['primera_direccion_autorizada']);
                $('#txt_first_email').val(response[0]['primer_email_autorizado']);
                $('#txt_second_authorized_name').val(response[0]['segundo_nombre_autorizado']);
                $('#txt_second_relationship').val(response[0]['segunda_relacion_autorizada']);
                $('#txt_second_address').val(response[0]['segunda_direccion_autorizada']);
                $('#txt_second_email').val(response[0]['segundo_email_autorizado']);
                $('#firma_estudiante').val(response[0]['firma_estudiante']);
                $('#fecha_firma').val(response[0]['fecha_firma']);
                $('#nombre_registro').val(response[0]['nombre_registro']);
                $('#fecha_registro').val(response[0]['fecha_registro']);
                $('#cbx_academic_info').prop('checked', response[0]['cbx_academic_all'] == 1);
                $('#cbx_admission').prop('checked', response[0]['cbx_academic_1'] == 1);
                $('#cbx_registration').prop('checked', response[0]['cbx_academic_2'] == 1);
                $('#cbx_grades').prop('checked', response[0]['cbx_academic_3'] == 1);
                $('#cbx_gpa').prop('checked', response[0]['cbx_academic_4'] == 1);
                $('#cbx_standing').prop('checked', response[0]['cbx_academic_5'] == 1);
                $('#cbx_graduation').prop('checked', response[0]['cbx_academic_6'] == 1);
                $('#cbx_financial_info').prop('checked', response[0]['cbx_financial_all'] == 1);
                $('#cbx_fees').prop('checked', response[0]['cbx_financial_1'] == 1);
                $('#cbx_charges').prop('checked', response[0]['cbx_financial_2'] == 1);
                $('#cbx_payments').prop('checked', response[0]['cbx_financial_3'] == 1);
                $('#cbx_aid_info').prop('checked', response[0]['cbx_aid_financial'] == 1);
                $('#cbx_housing_info').prop('checked', response[0]['cbx_housing_all'] == 1);
                $('#cbx_location').prop('checked', response[0]['cbx_housing_1'] == 1);
                $('#cbx_room').prop('checked', response[0]['cbx_housing_2'] == 1);
                $('#cbx_judicial').prop('checked', response[0]['cbx_housing_3'] == 1);
                $('#cbx_remove_consent').prop('checked', response[0]['cbx_remove_consent'] == 1);

                console.log(response);

            },
            error: function(xhr, status, error) {
                console.log('Status: ' + status);
                console.log('Error: ' + error);
                console.log('XHR Response: ' + xhr.responseText);

                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });
    }

    function editar_insertar() {

        var id_persona = $('#id_persona').val();
        var txt_student = $('#txt_student').val();
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
            'id_persona': id_persona,
            'txt_student': txt_student,
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
            url: '../controlador/INNOVERS/in_student_consentC.php?insertar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                        location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=in_personas';
                    });
                } else if (response == -2) {
                    Swal.fire('', 'Operación fallida', 'warning');
                }
            },

            error: function(xhr, status, error) {
                console.log('Status: ' + status);
                console.log('Error: ' + error);
                console.log('XHR Response: ' + xhr.responseText);

                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });
    }
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">INNOVERS</div>
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
                                <div class="d-flex justify-content-center">
                                    <h3>CONSENT FOR RELEASE</h3>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <h5> of Student Information</h5>
                                </div>

                                <form id="form_student_consent">

                                    <input type="hidden" name="id_persona" id="id_persona">

                                    <div class="row pt-3 mb-col">
                                        <div class="col-md-8">
                                            <label for="txt_student" class="form-label fw-bold">Student: <label style="color: red;">*</label></label>
                                            <input type="text" class="form-control form-control-sm" name="txt_student" id="txt_student" placeholder="First, Middle & Last Name" maxlength="200" readonly>
                                        </div>

                                        <div class="col-md-2">
                                            <label for="txt_birth_date" class="form-label fw-bold">Birth Date: <label style="color: red;">*</label></label>
                                            <input type="date" class="form-control form-control-sm" name="txt_birth_date" id="txt_birth_date" readonly>
                                        </div>

                                        <div class="col-md-2">
                                            <label for="txt_id_student" class="form-label fw-bold">Student ID: <label style="color: red;">*</label></label>
                                            <input type="text" class="form-control form-control-sm" name="txt_id_student" id="txt_id_student" maxlength="200" readonly>
                                        </div>
                                    </div>

                                    <div class="">

                                        <p class="mb-1 py-3 fw-bold">I hereby authorize the University of Idaho to discuss and verbally release the following information: </p>

                                        <div class="form-check mb-3">
                                            <input type="checkbox" class="form-check-input" name="cbx_academic_info" id="cbx_academic_info">
                                            <label for="cbx_academic_info" class="form-check-label mb-2"><strong>ALL</strong> academic information <strong>OR</strong> these individual items: <label style="color: red;">*</label></label>

                                            <div class="row mb-3 input-group">
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

                                            <div class="row mb-3 ms-2 input-group">
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

                                            <div class="mb-3">
                                                <input type="checkbox" class="form-check-input" name="cbx_aid_info" id="cbx_aid_info">
                                                <label for="cbx_aid_info" class="form-check-label"><strong>ALL</strong> financial aid information <label style="color: red;">*</label></label>
                                            </div>

                                            <input type="checkbox" class="form-check-input" name="cbx_housing_info" id="cbx_housing_info">
                                            <label for="cbx_housing_info" class="form-check-label mb-2"><strong>ALL</strong> university housing information <strong>OR</strong> these individual items: <label style="color: red;">*</label></label>
                                            <div class="row ms-2 input-group">
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


                                        <div class="row pt-3">
                                            <div class="col-12">
                                                <label for="txt_purpose_authorization" class="form-label fw-bold">My authorization is for the following purpose: <label style="color: red;">*</label></label>
                                                <textarea class="form-control form-control-sm w-100" name="txt_purpose_authorization" id="txt_purpose_authorization" maxlength="500"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-lg-12 pt-4">
                                        <div class="card bg-secondary bg-opacity-10 border border-1 border-opacity-10 mb-4">
                                            <div class="card-body">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" name="cbx_remove_consent" id="cbx_remove_consent">
                                                    <label for="cbx_remove_consent" class="form-check-label">I request to <strong>REMOVE</strong> my consent allowing UI to discuss and verbally release information to all currently designated individuals. <label style="color: red;">*</label></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <p class="fw-bold">I give consent for the following individual(s) to obtain the authorized information on request</p>
                                    <p>(All information required)</p>

                                    <label for="txt_first_authorized_name" class="form-label fw-bold">1.</label>

                                    <div>
                                        <div class="row pt-1 mb-col">
                                            <div class="col-md-6">
                                                <label for="txt_first_authorized_name" class="form-label fw-bold fs-7">First authorized name </label>
                                                <input type="text" class="form-control form-control-sm" name="txt_first_authorized_name" id="txt_first_authorized_name" placeholder="Printed Name" maxlength="200">
                                            </div>

                                            <div class="col-md-6">
                                                <label for="txt_first_relationship" class="form-label fw-bold fs-7">Relationship </label>
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
                                            </div>
                                        </div>

                                        <div class="row mb-col">
                                            <div class="col-md-6">
                                                <label for="txt_first_address" class="form-label fw-bold fs-7">First Address </label>
                                                <input type="text" class="form-control form-control-sm" name="txt_first_address" id="txt_first_address" placeholder="Complete Address" maxlength="500">
                                            </div>

                                            <div class="col-md-6">
                                                <label for="txt_first_email" class="form-label fw-bold fs-7">First Email </label>
                                                <input type="email" class="form-control form-control-sm" name="txt_first_email" id="txt_first_email" placeholder="Email" maxlength="200">
                                            </div>
                                        </div>
                                    </div>

                                    <label for="txt_second_authorized_name" class="form-label fw-bold">2.</label>
                                    
                                    <div>
                                        <div class="row pt-1 mb-col">
                                            <div class="col-md-6">
                                                <label for="txt_second_authorized_name" class="form-label fw-bold fs-7">Second authorized name </label>
                                                <input type="text" class="form-control form-control-sm" name="txt_second_authorized_name" id="txt_second_authorized_name" placeholder="Printed Name" maxlength="200">
                                            </div>

                                            <div class="col-md-6">
                                                <label for="txt_second_relationship" class="form-label fw-bold fs-7">Relationship </label>
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
                                            </div>
                                        </div>

                                        <div class="row mb-col">
                                            <div class="col-md-6">
                                                <label for="txt_second_address" class="form-label fw-bold fs-7">Second Address </label>
                                                <input type="text" class="form-control form-control-sm" name="txt_second_address" id="txt_second_address" placeholder="Complete Address" maxlength="500">
                                            </div>

                                            <div class="col-md-6">
                                                <label for="txt_second_email" class="form-label fw-bold fs-7">Second Email </label>
                                                <input type="email" class="form-control form-control-sm" name="txt_second_email" id="txt_second_email" placeholder="Email" maxlength="200">
                                            </div>
                                        </div>
                                    </div>


                                    <p class="py-4">I understand that this information is considered a student education, financial, and/or housing record. Further, I understand that by
                                        signing this release, I am waiving my right to keep this information confidential under the Family Educational Rights and Privacy Act
                                        (FERPA). I certify that my consent for disclosure of this information is entirely voluntary. I understand this consent for disclosure of
                                        information can be revoked by me in writing at any time, but will not affect the information released under my previous consent. If I wish
                                        to make any changes to my consent for release, I understand I will need to complete and file a new form. <strong>The authorization on this
                                            form will supersede all prior authorizations for release of my information.</strong></p>

                                    <div class="modal-footer pt-4">
                                        <button class="btn btn-primary btn-sm px-4 m-1" onclick="editar_insertar();" type="button"><i class="bx bx-save" id=""></i> Guardar</button>
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
        agregar_asterisco_campo_obligatorio('txt_first_authorized_name');
        agregar_asterisco_campo_obligatorio('txt_first_relationship');
        agregar_asterisco_campo_obligatorio('txt_first_address');
        agregar_asterisco_campo_obligatorio('txt_first_email');
        agregar_asterisco_campo_obligatorio('txt_second_authorized_name');
        agregar_asterisco_campo_obligatorio('txt_second_relationship');
        agregar_asterisco_campo_obligatorio('txt_second_address');
        agregar_asterisco_campo_obligatorio('txt_second_email');

        $("#form_student_consent").validate({
            rules: {
                txt_student: {
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