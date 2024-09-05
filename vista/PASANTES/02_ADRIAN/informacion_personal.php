<script>
    $(document).ready(function() {

        $('#ddl_estado_laboral').change(function() {
            ocultar_opciones_estado();
        });
        $('#ddl_tipo_aptitudes').change(function() {
            mostrar_tipo_aptitudes();
        });

        agregar_contacto_emergencia()
        
    });

    function insertar_editar_foto() {
        var txt_elegir_foto = $('#txt_elegir_foto').val();

        var parametro_foto = {
            'txt_elegir_foto': txt_elegir_foto
        }

        console.log(parametro_foto)
    }


    function insertar_editar_informacion_personal() {

        var txt_primer_nombre = $('#txt_primer_nombre').val();
        var txt_primer_apellido = $('#txt_primer_apellido').val();
        var txt_segundo_apellido = $('#txt_segundo_apellido').val();
        var txt_fecha_nacimiento = $('#txt_fecha_nacimiento').val();
        var ddl_nacionalidad = $('#ddl_nacionalidad').val();
        var ddl_estado_civil = $('#ddl_estado_civil').val();

        var parametros_informacion_personal = {
            'txt_primer_nombre': txt_primer_nombre,
            'txt_primer_apellido': txt_primer_apellido,
            'txt_segundo_apellido': txt_segundo_apellido,
            'txt_fecha_nacimiento': txt_fecha_nacimiento,
            'ddl_nacionalidad': ddl_nacionalidad,
            'ddl_estado_civil': ddl_estado_civil,
        }

        console.log(parametros_informacion_personal)

    }


    function insertar_editar_informacion_contacto() {
        var txt_direccion_calle = $('#txt_direccion_calle').val();
        var txt_direccion_numero = $('#txt_direccion_numero').val();
        var txt_direccion_ciudad = $('#txt_direccion_ciudad').val();
        var txt_direccion_estado = $('#txt_direccion_estado').val();
        var txt_direccion_postal = $('#txt_direccion_postal').val();
        var txt_telefono_1 = $('#txt_telefono_1').val();
        var txt_telefono_2 = $('#txt_telefono_2').val();
        var txt_correo = $('#txt_correo').val();
        var txt_nombre_contacto_emergencia = [];
        $('.txt_nombre_contacto_emergencia').each(function() {
            txt_nombre_contacto_emergencia.push($(this).val());
        });
        var txt_telefono_contacto_emergencia = [];
        $('.txt_telefono_contacto_emergencia').each(function() {
            txt_telefono_contacto_emergencia.push($(this).val());
        });

        var parametros_informacion_contacto = {
            'txt_direccion_calle': txt_direccion_calle,
            'txt_direccion_numero': txt_direccion_numero,
            'txt_direccion_ciudad': txt_direccion_ciudad,
            'txt_direccion_estado': txt_direccion_estado,
            'txt_direccion_postal': txt_direccion_postal,
            'txt_telefono_1': txt_telefono_1,
            'txt_telefono_2': txt_telefono_2,
            'txt_correo': txt_correo,
            'txt_nombre_contacto_emergencia': txt_nombre_contacto_emergencia,
            'txt_telefono_contacto_emergencia': txt_telefono_contacto_emergencia,
        }

        console.log(parametros_informacion_contacto)
    }

    function insertar_editar_experiencia_laboral() {
        var txt_nombre_empresa = $('#txt_nombre_empresa').val();
        var txt_cargos_ocupados = $('#txt_cargos_ocupados').val();
        var txt_fecha_inicio_laboral = $('#txt_fecha_inicio_laboral').val();
        var txt_fecha_final_laboral = $('#txt_fecha_final_laboral').val();
        var cbx_fecha_final_laboral = $('#cbx_fecha_final_laboral').prop('checked');
        var txt_responsabilidades_logros = $('#txt_responsabilidades_logros').val();

        var parametros_experiencia_laboral = {
            'txt_nombre_empresa': txt_nombre_empresa,
            'txt_cargos_ocupados': txt_cargos_ocupados,
            'txt_fecha_inicio_laboral': txt_fecha_inicio_laboral,
            'txt_fecha_final_laboral': txt_fecha_final_laboral,
            'cbx_fecha_final_laboral': cbx_fecha_final_laboral,
            'txt_responsabilidades_logros': txt_responsabilidades_logros,
        }

        console.log(parametros_experiencia_laboral)
    }

    function insertar_editar_formacion_academica() {
        var txt_titulo_obtenido = $('#txt_titulo_obtenido').val();
        var txt_institucion = $('#txt_institucion').val();
        var txt_fecha_inicio_academico = $('#txt_fecha_inicio_academico').val();
        var txt_fecha_final_academico = $('#txt_fecha_final_academico').val();

        var parametros_formacion_academica = {
            'txt_titulo_obtenido': txt_titulo_obtenido,
            'txt_institucion': txt_institucion,
            'txt_fecha_inicio_academico': txt_fecha_inicio_academico,
            'txt_fecha_final_academico': txt_fecha_final_academico,
        }

        console.log(parametros_formacion_academica)
    }

    function insertar_editar_certificaciones_capacitaciones() {
        var txt_nombre_certificacion = $('#txt_nombre_certificacion').val();
        var txt_enlace_certificado = $('#txt_enlace_certificado').val();
        var txt_pdf_certificado = $('#txt_pdf_certificado').val();

        var parametros_certificaciones_capacitaciones = {
            'txt_nombre_certificacion': txt_nombre_certificacion,
            'txt_enlace_certificado': txt_enlace_certificado,
            'txt_pdf_certificado': txt_pdf_certificado,
        }

        console.log(parametros_certificaciones_capacitaciones)
    }

    function insertar_editar_certificado_medico() {
        var txt_nombre_certificado_medico = $('#txt_nombre_certificado_medico').val();
        var txt_respaldo_medico = $('#txt_respaldo_medico').val();

        var parametros_certificado_medico = {
            'txt_nombre_certificado_medico': txt_nombre_certificado_medico,
            'txt_respaldo_medico': txt_respaldo_medico,
        }

        console.log(parametros_certificado_medico)
    }

    function insertar_editar_referencias() {
        var txt_nombre_referencia = $('#txt_nombre_referencia').val();
        var txt_telefono_referencia = $('#txt_telefono_referencia').val();
        var txt_copia_carta_recomendacion = $('#txt_copia_carta_recomendacion').val();

        var parametros_referencias = {
            'txt_nombre_referencia': txt_nombre_referencia,
            'txt_telefono_referencia': txt_telefono_referencia,
            'txt_copia_carta_recomendacion': txt_copia_carta_recomendacion,
        }

        console.log(parametros_referencias)
    }

    function insertar_editar_contrato_laboral() {
        var txt_nombre_empresa_contrato = $('#txt_nombre_empresa_contrato').val();
        var txt_copia_contrato = $('#txt_copia_contrato').val();

        var parametros_contrato_laboral = {
            'txt_nombre_empresa_contrato': txt_nombre_empresa_contrato,
            'txt_copia_contrato': txt_copia_contrato,
        }

        console.log(parametros_contrato_laboral)
    }

    function insertar_editar_estado_laboral() {
        var ddl_estado_laboral = $('#ddl_estado_laboral').val();
        var txt_fecha_contratacion_estado = $('#txt_fecha_contratacion_estado').val();
        var txt_fecha_salida_estado = $('#txt_fecha_salida_estado').val();

        var parametros_estado_laboral = {
            'ddl_estado_laboral': ddl_estado_laboral,
            'txt_fecha_contratacion_estado': txt_fecha_contratacion_estado,
            'txt_fecha_salida_estado': txt_fecha_salida_estado,
        }

        console.log(parametros_estado_laboral)

    }

    function insertar_editar_idiomas() {
        var ddl_seleccionar_idioma = $('#ddl_seleccionar_idioma').val();
        var ddl_dominio_idioma = $('#ddl_dominio_idioma').val();

        var parametros_idiomas = {
            'ddl_seleccionar_idioma': ddl_seleccionar_idioma,
            'ddl_dominio_idioma': ddl_dominio_idioma,
        }

        console.log(parametros_idiomas)
    }

    function insertar_editar_aptitudes() {
        var ddl_tipo_aptitudes = $('#ddl_tipo_aptitudes').val();
        var ddl_seleccionar_aptitud_blanda = [];
        $('.ddl_seleccionar_aptitud_blanda').each(function() {
            ddl_seleccionar_aptitud_blanda.push($(this).val());
        });

        var ddl_seleccionar_aptitud_tecnica = [];
        $('.ddl_seleccionar_aptitud_tecnica').each(function() {
            ddl_seleccionar_aptitud_tecnica.push($(this).val());
        });

        var select_tipo_aptitudes = $('#ddl_tipo_aptitudes');
        if (select_tipo_aptitudes.val() == 'Blandas') {
            var parametros_aptitudes = {
                'ddl_tipo_aptitudes': ddl_tipo_aptitudes,
                'ddl_seleccionar_aptitud_blanda': ddl_seleccionar_aptitud_blanda.flat(),
            }
        } else if (select_tipo_aptitudes.val() == 'Tecnicas') {
            var parametros_aptitudes = {
                'ddl_tipo_aptitudes': ddl_tipo_aptitudes,
                'ddl_seleccionar_aptitud_tecnica': ddl_seleccionar_aptitud_tecnica.flat(),
            }
        }

        console.log(parametros_aptitudes)
    }

    function insertar_editar_documento_identidad() {
        var ddl_tipo_documento_identidad = $('#ddl_tipo_documento_identidad').val();
        var txt_agregar_documento_identidad = $('#txt_agregar_documento_identidad').val();

        var parametros_documento_identidad = {
            'ddl_tipo_documento_identidad': ddl_tipo_documento_identidad,
            'txt_agregar_documento_identidad': txt_agregar_documento_identidad,
        }

        console.log(parametros_documento_identidad)
    }

    // function mostrar_parametros() {
    //     //Limpiar parámetros

    //     $('#txt_agregar_documento_identidad').val('');
    //     $('#txt_nombre_completo').val('');
    //     $('#txt_fecha_nacimiento').val('');
    //     $('#ddl_nacionalidad').val('');
    //     $('#ddl_estado_civil').val('');
    //     $('#txt_direccion_calle').val('');
    //     $('#txt_direccion_numero').val('');
    //     $('#txt_direccion_ciudad').val('');
    //     $('#txt_direccion_estado').val('');
    //     $('#txt_direccion_postal').val('');
    //     $('#txt_telefono_1').val('');
    //     $('#txt_telefono_2').val('');
    //     $('#txt_correo').val('');
    //     $('#txt_nombre_contacto_emergencia').val('');
    //     $('#txt_telefono_contacto_emergencia').val('');
    //     $('#txt_nombre_empresa').val('');
    //     $('#txt_cargos_ocupados').val('');
    //     $('#txt_fecha_inicio_laboral').val('');
    //     $('#txt_fecha_final_laboral').val('');
    //     $('#cbx_fecha_final_laboral').val('');
    //     $('#txt_responsabilidades_logros').val('');
    //     $('#txt_titulo_obtenido').val('');
    //     $('#txt_institucion').val('');
    //     $('#txt_fecha_inicio_academico').val('');
    //     $('#txt_fecha_final_academico').val('');
    //     $('#txt_nombre_certificacion').val('');
    //     $('#txt_enlace_certificado').val('');
    //     $('#txt_pdf_certificado').val('');
    //     $('#txt_nombre_certificado_medico').val('');
    //     $('#txt_respaldo_medico').val('');
    //     $('#ddl_tipo_referencia').val('');
    //     $('#txt_nombre_referencia').val('');
    //     $('#txt_telefono_referencia').val('');
    //     $('#txt_copia_carta_recomendacion').val('');
    //     $('#txt_nombre_empresa_contrato').val('');
    //     $('#txt_copia_contrato').val('');
    //     $('#ddl_estado_laboral').val('');
    //     $('#txt_fecha_contratacion_estado').val('');
    //     $('#txt_fecha_salida_estado').val('');
    //     $('#ddl_seleccionar_idioma').val('');
    //     $('#ddl_dominio_idioma').val('');
    //     $('#ddl_tipo_aptitudes').val('');
    //     $('.ddl_seleccionar_aptitud_blanda').val('');
    //     $('.ddl_seleccionar_aptitud_tecnica').val('');

    // }

    function cambiar_foto() {
        var btn_elegir_foto = $('#btn_elegir_foto')
        var input_elegir_foto = $('#txt_elegir_foto')

        btn_elegir_foto.click(function() {
            input_elegir_foto.click();
        });
    }

    function ocultar_opciones_estado() {
        var select_opciones_estado = $('#ddl_estado_laboral');
        var valor_seleccionado = select_opciones_estado.val();

        $('#txt_fecha_contratacion_estado').prop('disabled', false);
        $('#txt_fecha_salida_estado').prop('disabled', false);

        if (valor_seleccionado === "Freelancer" || valor_seleccionado === "Autonomo") {
            $('#txt_fecha_contratacion_estado').prop('disabled', true);
            $('#txt_fecha_salida_estado').prop('disabled', true);
        }
    }

    function mostrar_tipo_aptitudes() {
        var select_tipo_aptitudes = $('#ddl_tipo_aptitudes');
        var div_aptitudes_blandas = $('#sec_blandas')
        var div_aptitudes_tecnicas = $('#sec_tecnicas')

        div_aptitudes_blandas.hide();
        div_aptitudes_tecnicas.hide();


        if (select_tipo_aptitudes.val() == 'Blandas') {
            div_aptitudes_blandas.show()
            $('#ddl_seleccionar_aptitud_blanda').select2({
                placeholder: 'Selecciona una opción',
                dropdownParent: $('#modal_agregar_aptitudes'),
                language: {
                    inputTooShort: function() {
                        return "Por favor ingresa 1 o más caracteres";
                    },
                    noResults: function() {
                        return "No se encontraron resultados";
                    },
                    searching: function() {
                        return "Buscando...";
                    },
                    errorLoading: function() {
                        return "No se encontraron resultados";
                    }
                },
                minimumInputLength: 1,
            });
        } else if (select_tipo_aptitudes.val() == 'Tecnicas') {
            div_aptitudes_tecnicas.show()
            $('#ddl_seleccionar_aptitud_tecnica').select2({
                placeholder: 'Selecciona una opción',
                dropdownParent: $('#modal_agregar_aptitudes'),
                language: {
                    inputTooShort: function() {
                        return "Por favor ingresa 1 o más caracteres";
                    },
                    noResults: function() {
                        return "No se encontraron resultados";
                    },
                    searching: function() {
                        return "Buscando...";
                    },
                    errorLoading: function() {
                        return "No se encontraron resultados";
                    }
                },
                minimumInputLength: 1,
            });
        }
    }

    function agregar_contacto_emergencia() {
        $('#btn_agregar_contacto_emergencia').on('click', function() {
            var nueva_aptitud = $('.sec_contacto_emergencia .row').first().clone();

            nueva_aptitud.find('input').val('');

            $('.sec_contacto_emergencia').append(nueva_aptitud);
        });
    }

</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Adrian</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Información personal</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="container-fluid">
            <div class="main-body">
                <div class="row d-flex justify-content-center">
                    <div class="col-xs-5 col-sm-5 col-md-5 col-lg-4 col-xxl-3">
                        <!-- Cards de la izquierda -->
                        <div class="card">
                            <div class="card-body">
                                <div class="align-items-center">
                                    <div class="text-center">
                                        <img src="../img\usuarios\2043.jpeg" alt="Admin" class="rounded-circle p-1 bg-primary mb-2" width="110">
                                        <div>
                                            <a href="#" class="d-flex justify-content-center" data-bs-toggle="modal" data-bs-target="#modal_cambiar_foto" onclick="cambiar_foto();">
                                                <i class='bx bxs-camera bx-sm'></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <div class="row">
                                            <div class="col-10">
                                                <h5 class="fw-bold text-primary">Información Personal</h5>
                                            </div>
                                            <div class="col-2">
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#modal_informacion_personal">
                                                    <i class='text-dark bx bx-pencil bx-sm'></i>
                                                </a>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="row">
                                            <div class="col-6">
                                                <h6 class="fw-bold">Nombre Completo</h6>
                                            </div>
                                            <div class="col-6 d-flex align-items-center">
                                                <p>Adrian Acuña Estrada</p>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-6">
                                                <h6 class="fw-bold">Fecha de Nacimiento</h6>
                                            </div>
                                            <div class="col-6 d-flex align-items-center">
                                                <p>2002-10-07</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <h6 class="fw-bold">Nacionalidad</h6>
                                            </div>
                                            <div class="col-6 d-flex align-items-center">
                                                <p>Ecuatoriano</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <h6 class="fw-bold">Estado Civil</h6>
                                            </div>
                                            <div class="col-6 d-flex align-items-center">
                                                <p>Soltero</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="align-items-center">
                                    <div class="mt-3">
                                        <div class="row">
                                            <div class="col-10">
                                                <h5 class="fw-bold text-primary">Información de Contacto</h5>
                                            </div>
                                            <div class="col-2">
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#modal_informacion_contacto">
                                                    <i class='text-dark bx bx-pencil bx-sm'></i></a>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="row">
                                            <div class="col-6 d-flex align-items-center">
                                                <h6 class="fw-bold">Dirección</h6>
                                            </div>
                                            <div class="col-6 d-flex align-items-center">
                                                <p>Profeta Miqueas, OE11A, Quito, Pichincha, 07173</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6 d-flex align-items-center">
                                                <h6 class="fw-bold">Teléfono</h6>
                                            </div>
                                            <div class="col-6 d-flex align-items-center">
                                                <p>2002-10-07</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <h6 class="fw-bold">Correo Electrónico</h6>
                                            </div>
                                            <div class="col-6 d-flex align-items-center">
                                                <p>Ecuatoriano</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <h6 class="fw-bold">Contacto de Emergencia</h6>
                                            </div>
                                            <div class="col-6">
                                                <p class="my-0">Adrian Acuña</p>
                                                <p>09914654645</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-7 col-sm-7 col-md-7 col-lg-8 col-xxl-8">
                        <!-- Cards de la derecha -->
                        <div class="card-body">
                            <ul class="nav nav-tabs nav-success" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#tab_experiencia" role="tab" aria-selected="true">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class="bx bxs-briefcase font-18 me-1"></i>
                                            </div>
                                            <div class="tab-title">Experiencia</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#successdocs" role="tab" aria-selected="true">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class="bx bxs-file-doc font-18 me-1"></i>
                                            </div>
                                            <div class="tab-title">Documentos</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#successprofile" role="tab" aria-selected="false" tabindex="-1">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class="bx bx-brain font-18 me-1"></i>
                                            </div>
                                            <div class="tab-title">Habilidades</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#successcontact" role="tab" aria-selected="false" tabindex="-1">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class="bx bxs-user-check font-18 me-1"></i>
                                            </div>
                                            <div class="tab-title">Estado del Empleado</div>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content pt-3">
                                <!-- Primera Sección, Historial Laboral -->
                                <div class="tab-pane fade show active" id="tab_experiencia" role="tabpanel">
                                    <div class="card">
                                        <div class="d-flex flex-column mx-4">
                                            <div class="card-body">
                                                <div class="mb-2">
                                                    <div class="row">
                                                        <div class="col-9 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Experiencia Previa:</h6>
                                                        </div>
                                                        <div class="col-3 d-flex justify-content-end">
                                                            <a href="#" class="text-success d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modal_agregar_experiencia">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span>Agregar</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-10">
                                                        <h6 class="fw-bold">Corsinf</h6>
                                                        <p>Desarrollador de Software</p>
                                                        <p>2024-06-25 - 2024-09-25</p>
                                                        <p>Diseñar, codificar, probar y mantener aplicaciones y sistemas de software de alta calidad.</p>
                                                    </div>
                                                    <div class="col-2">
                                                        <a href="#" class="d-flex justify-content-end"><i class='text-dark bx bx-pencil bx-sm'></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-2">
                                                    <div class="row">
                                                        <div class="col-6 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Formación Académica:</h6>
                                                        </div>
                                                        <div class="col-6 d-flex justify-content-end">
                                                            <a href="#" class="text-success d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modal_agregar_formacion">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span>Agregar</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-8">
                                                        <h6 class="fw-bold">Tecnólogo Superior en Desarrollo de Software</h6>
                                                        <p>Pontificia Universidad Católica del Ecuador</p>
                                                        <p>2023-10-15 - Actualidad</p>
                                                        <p>Diseñar, codificar, probar y mantener aplicaciones y sistemas de software de alta calidad.</p>
                                                    </div>
                                                    <div class="col-4">
                                                        <a href="#" class="d-flex justify-content-end"><i class='text-dark bx bx-pencil bx-sm'></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-2">
                                                    <div class="row">
                                                        <div class="col-8 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Certificaciones y Capacitación:</h6>
                                                        </div>
                                                        <div class="col-4 d-flex justify-content-end">
                                                            <a href="#" class="text-success d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modal_agregar_certificaciones">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span>Agregar</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-8">
                                                        <h6 class="fw-bold">CS50: Introduction to Computer Science</h6>
                                                        <a href="#" class="fw-bold">Ver Certificado</a>
                                                    </div>
                                                    <div class="col-4">
                                                        <a href="#" class="d-flex justify-content-end"><i class='text-dark bx bx-pencil bx-sm'></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Segunda Sección, Documentos relevantes -->
                                <div class="tab-pane fade" id="successdocs" role="tabpanel">
                                    <div class="card">
                                        <div class="d-flex flex-column mx-4">
                                            <div class="card-body">
                                                <div class="mb-2">
                                                    <div class="row">
                                                        <div class="col-7 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Documento de Identidad:</h6>
                                                        </div>
                                                        <div class="col-5 d-flex justify-content-end align-items-center">
                                                            <a href="#" class="text-success d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modal_agregar_documento_identidad">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span class="">Subir</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class="my-0 mb-3">
                                                <div class="row mb-2">
                                                    <div class="col-10 d-flex align-items-center">
                                                        <p class="fw-bold">Cédula de Identidad</p>
                                                    </div>
                                                    <div class="col-2 d-flex justify-content-end">
                                                        <a href="#" class=""><i class='text-info bx bx-download me-2' style="font-size: 20px;"></i></a>
                                                        <a href="#" class=""><i class='text-danger bx bx-trash me-0' style="font-size: 20px;"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-2">
                                                    <div class="row">
                                                        <div class="col-7 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Contratos de Trabajo:</h6>
                                                        </div>
                                                        <div class="col-5 d-flex justify-content-end align-items-center">
                                                            <a href="#" class="text-success d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modal_agregar_contratos">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span class="">Subir</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class="my-0 mb-3">
                                                <div class="row mb-2">
                                                    <div class="col-10 d-flex align-items-center">
                                                        <p class="fw-bold">Contrato de trabajo - Sambitours</p>
                                                    </div>
                                                    <div class="col-2 d-flex justify-content-end">
                                                        <a href="#" class=""><i class='text-info bx bx-download me-2' style="font-size: 20px;"></i></a>
                                                        <a href="#" class=""><i class='text-danger bx bx-trash me-0' style="font-size: 20px;"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body my-0">
                                                <div class="mb-2">
                                                    <div class="row">
                                                        <div class="col-7 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Certificados Médicos:</h6>
                                                        </div>
                                                        <div class="col-5 d-flex justify-content-end align-items-center">
                                                            <a href="#" class="text-success d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modal_agregar_certificado_medico">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span class="">Subir</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class="my-0 mb-3">
                                                <div class="row mb-2">
                                                    <div class="col-10 d-flex align-items-center">
                                                        <p class="fw-bold">Certificado médico de enfermedad cualquiera</p>
                                                    </div>
                                                    <div class="col-2 d-flex justify-content-end">
                                                        <a href="#" class=""><i class='text-info bx bx-download me-2' style="font-size: 20px;"></i></a>
                                                        <a href="#" class=""><i class='text-danger bx bx-trash me-0' style="font-size: 20px;"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-2">
                                                    <div class="row">
                                                        <div class="col-7 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Referencias laborales:</h6>
                                                        </div>
                                                        <div class="col-5 d-flex justify-content-end align-items-center">
                                                            <a href="#" class="text-success d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modal_agregar_referencia_laboral">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span class="">Agregar</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class="my-0 mb-3">
                                                <div class="row mb-2">
                                                    <div class="col-10">
                                                        <p class="fw-bold my-0 d-flex align-items-center">Ing. Roberto Carapaz</p>
                                                        <p class="my-0 d-flex align-items-center">+593 994645643</p>
                                                        <a href="#">Carta de Recomendación</a>
                                                    </div>
                                                    <div class="col-2 d-flex justify-content-end align-items-center">
                                                        <a href="#" class=""><i class='text-info bx bx-pencil me-2' style="font-size: 20px;"></i></a>
                                                        <a href="#" class=""><i class='text-danger bx bx-trash me-0' style="font-size: 20px;"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Tercera Sección, Idiomas y aptitudes -->
                                <div class="tab-pane fade" id="successprofile" role="tabpanel">
                                    <div class="card">
                                        <div class="d-flex flex-column mx-4">
                                            <div class="card-body">
                                                <div class="mb-1">
                                                    <div class="row">
                                                        <div class="col-6 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Idiomas</h6>
                                                        </div>
                                                        <div class="col-6 d-flex justify-content-end">
                                                            <a href="#" class="text-success d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modal_agregar_idioma">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span>Agregar</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class="my-0">
                                                <div class="row mt-3">
                                                    <div class="col-8">
                                                        <h6 class="fw-bold">Inglés</h6>
                                                        <p>B1</p>
                                                    </div>
                                                    <div class="col-4">
                                                        <a href="#" class="d-flex justify-content-end"><i class='text-dark bx bx-pencil bx-sm'></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-1">
                                                    <div class="row">
                                                        <div class="col-6 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Aptitudes</h6>
                                                        </div>
                                                        <div class="col-6 d-flex justify-content-end">
                                                            <a href="#" class="text-success d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modal_agregar_aptitudes">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span>Agregar</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class="my-0">
                                                <div class="row mt-3">
                                                    <div class="col-8">
                                                        <p class="fw-bold">Aptitudes Técnicas</p>
                                                        <ul>
                                                            <li>Dominio de paquete Office</li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-4">
                                                        <a href="#" class="d-flex justify-content-end"><i class='text-dark bx bx-pencil bx-sm'></i></a>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-8">
                                                        <p class="fw-bold">Aptitudes Blandas</p>
                                                        <ul>
                                                            <li>Liderazgo</li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-4">
                                                        <a href="#" class="d-flex justify-content-end"><i class='text-dark bx bx-pencil bx-sm'></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Cuarta Sección, Estado del Empleado -->
                                <div class="tab-pane fade" id="successcontact" role="tabpanel">
                                    <div class="card">
                                        <div class="d-flex flex-column mx-4">
                                            <div class="card-body">
                                                <div class="mb-2">
                                                    <div class="row">
                                                        <div class="col-6 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Estado laboral:</h6>
                                                        </div>
                                                        <div class="col-6 d-flex justify-content-end">
                                                            <a href="#" class="text-success d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modal_estado_laboral">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span>Agregar</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row mb-3">
                                                    <div class="col-6">
                                                        <h6 class="fw-bold mb-2">Inactivo</h6>
                                                        <p>Ene 2022 - Oct 2023</p>
                                                    </div>
                                                    <div class="col-6">
                                                        <a href="#" class="d-flex justify-content-end"><i class='text-dark bx bx-pencil bx-sm'></i></a>
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
            </div>
        </div>
    </div>
</div>

<!-- Modal para cambiar la foto-->
<div class="modal" id="modal_cambiar_foto" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary">Foto de Perfil</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="text-center">
                    <img src="../img\usuarios\2043.jpeg" alt="Admin" class="img-fluid mb-3 p-2 bg-secondary" width="240">
                </div>
                <div class="mb-4 d-flex justify-content-center">
                    <input type="button" class="btn btn-primary " name="btn_elegir_foto" id="btn_elegir_foto" value="Elegir otra foto">
                    <input type="file" id="txt_elegir_foto" accept="image/*" style="display: none;">
                </div>
                <div class="mb-3 d-flex justify-content-center">
                    <input type="button" class="btn btn-success" name="btn_confirmar_foto" id="btn_confirmar_foto" value="Confirmar" onclick="insertar_editar_foto();">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para la informacion personal -->
<div class="modal" id="modal_informacion_personal" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary">Ingrese sus datos</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <p class="fw-bold">Nombres Completos</p>
                <div class="row">
                    <div class="col-4">
                        <div class="mb-3">
                            <label for="txt_primer_nombre" class="form-label form-label-sm">Primer Nombre</label>
                            <input type="text" class="form-control form-control-sm" name="txt_primer_nombre" id="txt_primer_nombre" placeholder="Escriba su primer nombre">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mb-3">
                            <label for="txt_primer_apellido" class="form-label form-label-sm">Primer Apellido</label>
                            <input type="text" class="form-control form-control-sm" name="txt_primer_apellido" id="txt_primer_apellido" placeholder="Escriba su apellido paterno">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mb-3">
                            <label for="txt_segundo_apellido" class="form-label form-label-sm">Segundo Apellido</label>
                            <input type="text" class="form-control form-control-sm" name="txt_segundo_apellido" id="txt_segundo_apellido" placeholder="Escriba su apellido materno">
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-4">
                        <div class="mb-3">
                            <label for="txt_fecha_nacimiento" class="form-label form-label-sm">Fecha de nacimiento</label>
                            <input type="date" class="form-control form-control-sm" name="txt_fecha_nacimiento" id="txt_fecha_nacimiento">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mb-3">
                            <label for="ddl_nacionalidad" class="form-label form-label-sm">Nacionalidad</label>
                            <select class="form-select form-select-sm" id="ddl_nacionalidad" name="ddl_nacionalidad" required>
                                <option selected disabled value="">-- Selecciona una Nacionalidad --</option>
                                <option value="Ecuatoriano">Ecuatoriano</option>
                                <option value="Colombiano">Colombiano</option>
                                <option value="Peruano">Peruano</option>
                                <option value="Venezolano">Venezolano</option>
                                <option value="Paraguayo">Paraguayo</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mb-3">
                            <label for="ddl_estado_civil" class="form-label form-label-sm">Estado civil</label>
                            <select class="form-select form-select-sm" id="ddl_estado_civil" name="ddl_estado_civil" required>
                                <option selected disabled value="">-- Selecciona un Estado Civil --</option>
                                <option value="Soltero">Soltero/a</option>
                                <option value="Casado">Casado/a</option>
                                <option value="Divorciado">Divorciado/a</option>
                                <option value="Viudo">Viudo/a</option>
                                <option value="Union">Unión de hecho</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button type="button" class="btn btn-success btn-sm" id="btn_guardar_informacion_personal" onclick="insertar_editar_informacion_personal();">Guardar</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal para la informacion contactos -->
<div class="modal" id="modal_informacion_contacto" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary">Ingrese sus datos de contacto</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <p class="fw-bold">Dirección:</p>
                <div class="row">
                    <div class="col-4">
                        <div class="mb-3">
                            <label for="txt_direccion_calle" class="form-label form-label-sm">Calle</label>
                            <input type="text" class="form-control form-control-sm" name="txt_direccion_calle" id="txt_direccion_calle" value="" placeholder="Escriba la calle de su dirección">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mb-3">
                            <label for="txt_direccion_numero" class="form-label form-label-sm">Número</label>
                            <input type="text" class="form-control form-control-sm" name="txt_direccion_numero" id="txt_direccion_numero" value="" placeholder="Escriba el número de su dirección">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mb-3">
                            <label for="txt_direccion_ciudad" class="form-label form-label-sm">Ciudad</label>
                            <input type="text" class="form-control form-control-sm" name="txt_direccion_ciudad" id="txt_direccion_ciudad" value="" placeholder="Escriba la ciudad de su dirección">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mb-3">
                            <label for="txt_direccion_estado" class="form-label form-label-sm">Provincia</label>
                            <input type="text" class="form-control form-control-sm" name="txt_direccion_estado" id="txt_direccion_estado" value="" placeholder="Escriba la provincia de su dirección">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mb-3">
                            <label for="txt_direccion_postal" class="form-label form-label-sm">Código Postal</label>
                            <div class="row">
                                <div class="col-11 me-0">
                                    <input type="text" class="form-control form-control-sm" name="txt_direccion_postal" id="txt_direccion_postal" placeholder="Escriba su código postal o de click en 'Obtener'">
                                </div>
                                <div class="col-1 d-flex justify-content-start">
                                    <button class="btn btn-sm btn-outline-primary">Obtener</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-4">
                        <div class="mb-3">
                            <label for="txt_telefono_1" class="form-label form-label-sm">Teléfono 1 (personal o fijo)</label>
                            <input type="text" class="form-control form-control-sm" name="txt_telefono_1" id="txt_telefono_1" value="" placeholder="Escriba su teléfono personal o fijo">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mb-3">
                            <label for="txt_telefono_2" class="form-label form-label-sm">Teléfono 2 (opcional)</label>
                            <input type="text" class="form-control form-control-sm" name="txt_telefono_2" id="txt_telefono_2" value="" placeholder="Escriba su teléfono personal o fijo (opcional)">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mb-3">
                            <label for="txt_correo" class="form-label form-label-sm">Correo Electrónico</label>
                            <input type="email" class="form-control form-control-sm" name="txt_correo" id="txt_correo" value="" placeholder="Escriba su correo electrónico">
                        </div>
                    </div>
                </div>
                <hr>
                <p class="fw-bold my-0 mb-2">Contacto de Emergencia:</p>
                <div class="sec_contacto_emergencia">
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="txt_nombre_contacto_emergencia" class="form-label form-label-sm">Nombre del contacto de Emergencia</label>
                                <input type="text" class="form-control form-control-sm txt_nombre_contacto_emergencia" name="txt_nombre_contacto_emergencia" id="txt_nombre_contacto_emergencia" value="" placeholder="Escriba el nombre de un contacto de emergencia">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="txt_telefono_contacto_emergencia" class="form-label form-label-sm">Teléfono del contacto de Emergencia</label>
                                <input type="text" class="form-control form-control-sm txt_telefono_contacto_emergencia" name="txt_telefono_contacto_emergencia" id="txt_telefono_contacto_emergencia" value="" placeholder="Escriba el número de un contacto de emergencia">
                            </div>
                        </div>
                    </div>
                </div>
                <button class="btn btn-sm btn-primary mb-2 d-flex align-items-center" id="btn_agregar_contacto_emergencia"><i class='bx bx-list-plus me-0'></i>Añadir otro contacto</button>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button type="button" class="btn btn-success btn-sm" id="btn_guardar_informacion_contacto" onclick="insertar_editar_informacion_contacto();">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para agregar experiencia laboral-->
<div class="modal" id="modal_agregar_experiencia" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary">Agregue una experiencia laboral</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="mb-3">
                    <label for="txt_nombre_empresa" class="form-label form-label-sm">Nombre de la empresa</label>
                    <input type="text" class="form-control form-control-sm" name="txt_nombre_empresa" id="txt_nombre_empresa" value="" placeholder="Escriba el nombre de la empresa donde trabajó">
                </div>
                <div class="mb-3">
                    <label for="txt_cargos_ocupados" class="form-label form-label-sm">Cargos ocupados</label>
                    <input type="text" class="form-control form-control-sm" name="txt_cargos_ocupados" id="txt_cargos_ocupados" value="" placeholder="Escriba los cargos que ocupo en la empresa">
                </div>
                <div class="mb-3">
                    <label for="txt_fecha_inicio_laboral" class="form-label form-label-sm">Fecha de inicio</label>
                    <input type="date" class="form-control form-control-sm" name="txt_fecha_inicio_laboral" id="txt_fecha_inicio_laboral" value="">
                </div>
                <div class="mb-3">
                    <label for="txt_fecha_final_laboral" class="form-label form-label-sm">Fecha de finalización</label>
                    <input type="date" class="form-control form-control-sm mb-2" name="txt_fecha_final_laboral" id="txt_fecha_final_laboral" value="">
                    <input type="checkbox" class="form-check-input" name="cbx_fecha_final_laboral" id="cbx_fecha_final_laboral">
                    <label for="cbx_fecha_final_laboral" class="form-label form-label-sm">Actualidad</label>
                </div>
                <div class="mb-3">
                    <label for="txt_responsabilidades_logros" class="form-label form-label-sm">Descripción de responsabilidades y logros</label>
                    <textarea type="text" class="form-control form-control-sm" name="txt_responsabilidades_logros" id="txt_responsabilidades_logros" value="" placeholder=""></textarea>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button type="button" class="btn btn-success btn-sm" id="btn_agregar_experiencia_laboral" onclick="insertar_editar_experiencia_laboral();">Agregar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para agregar formación académica-->
<div class="modal" id="modal_agregar_formacion" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary">Agregue una formación académica</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="mb-3">
                    <label for="txt_titulo_obtenido" class="form-label form-label-sm">Título obtenido</label>
                    <input type="text" class="form-control form-control-sm" name="txt_titulo_obtenido" id="txt_titulo_obtenido" value="" placeholder="Escriba su título académico">
                </div>
                <div class="mb-3">
                    <label for="txt_institucion" class="form-label form-label-sm">Institución</label>
                    <input type="text" class="form-control form-control-sm" name="txt_institucion" id="txt_institucion" value="" placeholder="Escriba la institución en la que se formó">
                </div>
                <div class="mb-3">
                    <label for="txt_fecha_inicio_academico" class="form-label form-label-sm">Fecha de inicio</label>
                    <input type="date" class="form-control form-control-sm" name="txt_fecha_inicio_academico" id="txt_fecha_inicio_academico" value="">
                </div>
                <div class="mb-3">
                    <label for="txt_fecha_final_academico" class="form-label form-label-sm">Fecha de finalización</label>
                    <input type="date" class="form-control form-control-sm mb-2" name="txt_fecha_final_academico" id="txt_fecha_final_academico" value="">
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button type="button" class="btn btn-success btn-sm" id="btn_guardar_formacion" onclick="insertar_editar_formacion_academica();">Agregar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para agregar certificaciones y capacitaciones-->
<div class="modal" id="modal_agregar_certificaciones" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary">Agregue una Certificación o Capacitación</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="mb-3">
                    <label for="txt_nombre_certificacion" class="form-label form-label-sm">Nombre del curso o capacitación</label>
                    <input type="text" class="form-control form-control-sm" name="txt_nombre_certificacion" id="txt_nombre_certificacion" value="" placeholder="Escriba el nombre del curso o capacitación">
                </div>
                <div class="mb-3">
                    <p class="fw-bold">Eliga una de las opciones:</p>
                    <label for="txt_enlace_certificado" class="form-label form-label-sm">1. Enlace del Certificado obtenido</label>
                    <input type="text" class="form-control form-control-sm mb-3" name="txt_enlace_certificado" id="txt_enlace_certificado" value="" placeholder="Escriba el enlace a su certificado">
                    <label for="txt_pdf_certificado" class="form-label form-label-sm">2. PDF del Certificado obtenido</label>
                    <input type="file" class="form-control form-control-sm" name="txt_pdf_certificado" id="txt_pdf_certificado" accept=".pdf" value="" placeholder="">
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button type="button" class="btn btn-success btn-sm" id="btn_guardar_certificaciones" onclick="insertar_editar_certificaciones_capacitaciones();">Guardar Certificación o Capacitación</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para agregar certificados médicos-->
<div class="modal" id="modal_agregar_certificado_medico" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary">Agregue un Certificado Médico</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="mb-3">
                    <label for="txt_nombre_certificado_medico" class="form-label form-label-sm">Nombre del certificado</label>
                    <input type="text" class="form-control form-control-sm" name="txt_nombre_certificado_medico" id="txt_nombre_certificado_medico" value="" placeholder="Escriba el nombre del certificado médico">
                </div>
                <div class="mb-3">
                    <label for="txt_respaldo_medico" class="form-label form-label-sm">Documentación que respalde la aptitud para el trabajo</label>
                    <input type="file" class="form-control form-control-sm mb-3" name="txt_respaldo_medico" id="txt_respaldo_medico" accept=".pdf" value="">
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button type="button" class="btn btn-success btn-sm" id="btn_guardar_certificado_medico" onclick="insertar_editar_certificado_medico()">Guardar Certificado Médico</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para agregar referencias laborales-->
<div class="modal" id="modal_agregar_referencia_laboral" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary">Agregue una referencia</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="mb-3">
                    <label for="txt_nombre_referencia" class="form-label form-label-sm">Nombre del empleador</label>
                    <input type="text" class="form-control form-control-sm" name="txt_nombre_referencia" id="txt_nombre_referencia" value="" placeholder="Escriba el nombre de el empleador">
                </div>
                <div class="mb-3">
                    <label for="txt_telefono_referencia" class="form-label form-label-sm">Teléfono del empleador</label>
                    <input type="text" class="form-control form-control-sm" name="txt_telefono_referencia" id="txt_telefono_referencia" value="" placeholder="Escriba el número de contacto de el empleador">
                </div>
                <div class="mb-3">
                    <label for="txt_copia_carta_recomendacion" class="form-label form-label-sm">Copia de la carta de recomendación</label>
                    <input type="file" class="form-control form-control-sm mb-3" name="txt_copia_carta_recomendacion" id="txt_copia_carta_recomendacion" accept=".pdf" value="">
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button type="button" class="btn btn-success btn-sm" id="btn_guardar_referencia" onclick="insertar_editar_referencias();">Guardar Referencia Laboral</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para agregar contratos de trabajo-->
<div class="modal" id="modal_agregar_contratos" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary">Agregue un Contrato</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="mb-3">
                    <label for="txt_nombre_empresa_contrato" class="form-label form-label-sm">Nombre de la empresa</label>
                    <input type="text" class="form-control form-control-sm" name="txt_nombre_empresa_contrato" id="txt_nombre_empresa_contrato" value="" placeholder="Escriba el nombre de la empresa que emitió el contrato">
                </div>
                <div class="mb-3">
                    <label for="txt_copia_contrato" class="form-label form-label-sm">Copia del contrato firmado</label>
                    <input type="file" class="form-control form-control-sm mb-3" name="txt_copia_contrato" id="txt_copia_contrato" accept=".pdf" value="">
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button type="button" class="btn btn-success btn-sm" id="btn_guardar_contratos" onclick="insertar_editar_contrato_laboral();">Guardar Contrato de Trabajo</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para agregar estado laboral-->
<div class="modal" id="modal_estado_laboral" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary">Agregue su estado laboral</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="mb-3">
                    <label for="ddl_estado_laboral" class="form-label form-label-sm">Estado laboral:</label>
                    <select class="form-select form-select-sm" id="ddl_estado_laboral" name="ddl_estado_laboral" required>
                        <option selected disabled value="">-- Selecciona un Estado Laboral --</option>
                        <option value="Activo">Activo</option>
                        <option value="Inactivo">Inactivo</option>
                        <option value="Prueba">En prueba</option>
                        <option value="Pasante">Pasante</option>
                        <option value="Freelancer">Freelancer</option>
                        <option value="Autonomo">Autónomo</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="txt_fecha_contratacion_estado" class="form-label form-label-sm">Fecha de contratación</label>
                    <input type="date" class="form-control form-control-sm mb-3" name="txt_fecha_contratacion_estado" id="txt_fecha_contratacion_estado" value="">
                </div>
                <div class="mb-3">
                    <label for="txt_fecha_salida_estado" class="form-label form-label-sm">Fecha de salida</label>
                    <input type="date" class="form-control form-control-sm mb-3" name="txt_fecha_salida_estado" id="txt_fecha_salida_estado" value="">
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button type="button" class="btn btn-success btn-sm" id="btn_guardar_estado_laboral" onclick="insertar_editar_estado_laboral();">Guardar Estado Laboral</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para agregar idiomas-->
<div class="modal" id="modal_agregar_idioma" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary">Agregue un idioma</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="mb-3">
                    <label for="ddl_seleccionar_idioma" class="form-label form-label-sm">Idioma</label>
                    <select class="form-select form-select-sm" id="ddl_seleccionar_idioma" name="ddl_seleccionar_idioma">
                        <option selected disabled value="">-- Selecciona un Idioma --</option>
                        <option value="Español">Español</option>
                        <option value="Inglés">Inglés</option>
                        <option value="Francés">Francés</option>
                        <option value="Alemán">Alemán</option>
                        <option value="Chino">Chino</option>
                        <option value="Italiano">Italiano</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="ddl_dominio_idioma" class="form-label form-label-sm">Dominio del Idioma</label>
                    <select class="form-select form-select-sm" id="ddl_dominio_idioma" name="ddl_dominio_idioma" required>
                        <option selected disabled value="">-- Selecciona su nivel de dominio del idioma --</option>
                        <option value="Nativo">Nativo</option>
                        <option value="C1">C1</option>
                        <option value="C2">C2</option>
                        <option value="B1">B1</option>
                        <option value="B2">B2</option>
                        <option value="C1">C1</option>
                        <option value="C2">C2</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button type="button" class="btn btn-success btn-sm" id="btn_guardar_idioma" onclick="insertar_editar_idiomas();">Guardar Idioma</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para agregar aptitudes técnicas y blandas-->
<div class="modal" id="modal_agregar_aptitudes" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary">Agregue Aptitudes</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <select class="form-select form-select-sm mb-4" id="ddl_tipo_aptitudes" name="ddl_tipo_aptitudes" required>
                    <option selected disabled value="">-- Selecciona el tipo de Aptitudes --</option>
                    <option value="Blandas">Aptitudes Blandas</option>
                    <option value="Tecnicas">Aptitudes Técnicas</option>
                </select>
                <div id="sec_blandas" style="display: none;">
                    <div class="mb-3">
                        <div class="row mb-3">
                            <div class="col-12 d-flex align-items-center">
                                <label for="ddl_seleccionar_aptitud_blanda" class="form-label form-label-sm fw-bold">Seleccione sus Aptitudes Blandas</label>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-12">
                                <select class="form-select form-select-sm ddl_seleccionar_aptitud_blanda" id="ddl_seleccionar_aptitud_blanda" name="ddl_seleccionar_aptitud_blanda" multiple="multiple">
                                    <option value="Liderazgo">Liderazgo</option>
                                    <option value="Comunicación Efectiva">Comunicación Efectiva</option>
                                    <option value="Trabajo en equipo">Trabajo en equipo</option>
                                    <option value="etc">etc</option>
                                    <option value="etc">etc</option>
                                    <option value="etc">etc</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="sec_tecnicas" style="display: none;">
                    <div class="mb-3">
                        <div class="row mb-3">
                            <div class="col-12 d-flex align-items-center">
                                <label for="ddl_seleccionar_aptitud_tecnica" class="form-label form-label-sm fw-bold">Seleccione sus Aptitudes Técnicas</label>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-12">
                                <select class="form-select form-select-sm ddl_seleccionar_aptitud_tecnica" id="ddl_seleccionar_aptitud_tecnica" name="ddl_seleccionar_aptitud_tecnica" multiple="multiple">
                                    <option value="Manejo de office">Manejo de office</option>
                                    <option value="Django">Django</option>
                                    <option value="Laravel">Laravel</option>
                                    <option value="Photoshop">Photoshop</option>
                                    <option value="Illustrator">Illustrator</option>
                                    <option value="etc">etc</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button type="button" class="btn btn-success btn-sm" id="btn_guardar_aptitudes" onclick="insertar_editar_aptitudes();">Guardar Aptitudes</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para agregar documento de identidad-->
<div class="modal" id="modal_agregar_documento_identidad" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary">Agregue un Documento de Identidad</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="mb-3">
                    <label for="ddl_tipo_documento_identidad" class="form-label form-label-sm">Tipo de Documento</label>
                    <select class="form-select form-select-sm" id="ddl_tipo_documento_identidad" name="ddl_tipo_documento_identidad" required>
                        <option selected disabled value="">-- Selecciona una opción --</option>
                        <option value="Cédula de Identidad">Cédula de Identidad</option>
                        <option value="Pasaporte">Pasaporte</option>
                        <option value="Tarjeta de identificación">Tarjeta de identificación</option>
                        <option value="Licencia">Licencia</option>
                        <option value="Carnét o Certificado para miembro de la Fuerza Pública Ecuatoriana">Carnét o Certificado para miembro de la Fuerza Pública Ecuatoriana</option>
                        <option value="Carnét de discapacidad">Carnét de discapacidad</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="txt_agregar_documento_identidad" class="form-label form-label-sm">Copia del Documento de identidad</label>
                    <input type="file" class="form-control form-control-sm" name="txt_agregar_documento_identidad" id="txt_agregar_documento_identidad" accept=".pdf">
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button type="button" class="btn btn-success btn-sm" id="btn_guardar_documento_identidad" onclick="insertar_editar_documento_identidad();">Guardar Documento de Identidad</button>
            </div>
        </div>
    </div>
</div>