
<script src="../js/GENERAL/operaciones_generales.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        cargar_selects2();
         $('input[name="tipo_busqueda_principal"]').on('change', function() {
            let tipo = $(this).val();

            if (tipo === 'departamento') {
                // Mostrar contenedor de departamento
                $('#select_departamento_container').show();
                $('#select_persona_container').addClass('d-none');
                $('#select_departamento_container').removeClass('d-none');
                $('#ddl_personas').val('').trigger('change');

                // Mostrar opciones de filtro y ordenamiento
                $('#pnl_filtro_departamento').slideDown(300);
                $('#pnl_ordenamiento').slideDown(300);

                // Resetear filtro a "todos"
                $('#radio_dept_todos').prop('checked', true);
                $('#pnl_personas_departamento').slideUp(300);
            } else {
                // Mostrar contenedor de persona
                $('#select_persona_container').show();
                $('#select_departamento_container').addClass('d-none');
                $('#select_persona_container').removeClass('d-none');
                $('#ddl_departamentos').val('').trigger('change');

                // Ocultar opciones de filtro y ordenamiento
                $('#pnl_filtro_departamento').slideUp(300);
                $('#pnl_ordenamiento').slideUp(300);
                $('#pnl_personas_departamento').slideUp(300);
            }
        });
    });

    function sincronizar_calculo_asistencia_fecha() 
    {

        var all = 1;

        fecha_inicio = $('#txt_fecha_inicio').val();
        fecha_fin = $('#txt_fecha_fin').val();
        personas = $("#ddl_personas").val();
        departamentos = $("#ddl_departamentos").val();
        tipo_busqueda = $('input[name="tipo_busqueda_principal"]:checked').val();
        if(fecha_fin=='' || fecha_fin=='')
        {
            Swal.fire("Seleccione una rango de fechas valido","","info");
            return false;
        }

        if((tipo_busqueda=="departamento" && departamentos=='') || (tipo_busqueda=="persona" && personas==''))
        {
            Swal.fire({
                 title: 'Esta seguro?',
                 text: "Esta usted seguro que quiere recalcular tod@s l@s "+tipo_busqueda,
                 icon: 'warning',
                 showCancelButton: true,
                 confirmButtonColor: '#3085d6',
                 cancelButtonColor: '#d33',
                 confirmButtonText: 'Si!'
            }).then((result) => {
                 if (result.value==true) {
                    sincronizar_calculo_asistencia_fecha_all(all)            
                 }

            })
        }else
        {
            all = 0;
            sincronizar_calculo_asistencia_fecha_all(all)            
        }
    }


    function sincronizar_calculo_asistencia_fecha_all(all)
    {       

         Swal.fire({
                title: 'Por favor, espere',
                text: 'Procesando la solicitud...',
                allowOutsideClick: false,
                onOpen: () => {
                    Swal.showLoading();
                }
            });

            var parametros = {
                'inicio':fecha_inicio,
                'fin':fecha_fin,
                'departamento':departamentos,
                'persona':personas,
                'tipo_busqueda':tipo_busqueda,
                'all':all
            }

            $.ajax({
                url: '../controlador/TALENTO_HUMANO/th_reportesC.php?sincronizar_calculo_asistencia_fecha=true',
                type: 'post',
                dataType: 'json',
                data: {parametros,parametros},
                success: function(response) {
                    // console.log(response);
                    Swal.close();
                    Swal.fire('Sinconizado correctamente.', response, 'success');
                },
                error: function(xhr, status, error) {
                    console.log('Status: ' + status);
                    console.log('Error: ' + error);
                    console.log('XHR Response: ' + xhr.responseText);

                    Swal.fire('', 'Error: ' + xhr.responseText, 'error');
                }
            });        
        
    }

     function cargar_selects2() {
        url_departamentosC = '../controlador/TALENTO_HUMANO/th_departamentosC.php?buscar_departamento=true';
        cargar_select2_url('ddl_departamentos', url_departamentosC);
        let option = new Option('Todos los departamentos', '0', true, true);
        $('#ddl_departamentos').append(option).trigger('change');

        // Cargar select de personas con opción "Todos los Departamentos"
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/th_personasC.php?buscar=true',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                const $select = $('#ddl_personas');
                $select.empty();
                $select.append('<option value="">-- Seleccione --</option>');

                if (response.length > 0) {
                    $.each(response, function(index, item) {
                        $select.append(
                            $('<option>', {
                                value: item.id,
                                text: item.text
                            })
                        );
                    });
                }

                // Inicializar select2
                $select.select2({
                    placeholder: '-- Seleccione --',
                    allowClear: true
                });
            },
            error: function(xhr, status, error) {
                console.error('Error al cargar personas:', error);
            }
        });

        // Inicializar select de personas por departamento con opción "Todos"
        $('#ddl_personas_departamento').select2({
            placeholder: '-- Seleccione --',
            allowClear: true
        });
    }

    function validar_rango_valido()
    {
        var inicio = $('#txt_fecha_inicio').val();
        $('#txt_fecha_fin').val("");
        if(inicio!='')
        {
            $('#txt_fecha_fin').attr('readOnly',false);
            const [año, mes, dia] = inicio.split('-').map(Number);
            const d = new Date(año, mes - 1, dia);
            var final =  new Date(d.getFullYear(), d.getMonth() + 1, 0).toISOString().split('T')[0];

            console.log(final);
            $("#txt_fecha_fin").attr({ "max" : final, "min" : inicio });

        }else
        {
            $('#txt_fecha_fin').val("");
            $('#txt_fecha_fin').attr('readOnly',true);
        }
    }


</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Recalcular</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Recalcular
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
                        <div class="card-title d-flex align-items-center">

                            <h5 class="mb-0 text-primary"></h5>

                        </div>

                        <div class="row mb-1">
                            <div class="col-md-6">
                                  <!-- Departamento -->
                                <div class="col-md-12 d-none" id="select_departamento_container">
                                    <label for="ddl_departamentos" class="form-label fw-bold">
                                        <i class="bx bx-building me-1"></i> Departamento
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select form-select-sm select2-validation"
                                        id="ddl_departamentos" name="ddl_departamentos">
                                        <option value="">-- Seleccione un Departamento --</option>
                                    </select>
                                </div>

                                 <!-- Persona Individual -->
                                <div class="col-md-12" id="select_persona_container">
                                    <label for="ddl_personas" class="form-label fw-bold">
                                        <i class="bx bx-user me-1"></i> Persona
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select form-select-sm select2-validation"
                                        id="ddl_personas" name="ddl_personas">
                                        <option value="">-- Seleccione una Persona --</option>
                                    </select>
                                </div>

                                <div class="d-flex">
                                     <div class="form-check form-check-custom m-1">
                                        <input class="form-check-input" type="radio" name="tipo_busqueda_principal" id="radio_persona" value="persona"  checked="">
                                        <label class="form-check-label" for="radio_persona">
                                            <i class="bx bx-user me-1"></i> Por Persona
                                        </label>
                                    </div>
                                    <div class="form-check form-check-custom m-1">
                                        <input class="form-check-input" type="radio" name="tipo_busqueda_principal" id="radio_departamento" value="departamento">
                                        <label class="form-check-label" for="radio_departamento">
                                            <i class="bx bx-building me-1"></i> Por Departamento
                                        </label>
                                    </div>
                                   
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="txt_fecha_inicio" class="form-label fw-bold">
                                    <i class="bx bx-calendar me-1"></i> Fecha Inicio
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="date" class="form-control form-control-sm"
                                    id="txt_fecha_inicio" name="txt_fecha_inicio" onblur="validar_rango_valido()" onchange="validar_rango_valido()">
                            </div>

                            <div class="col-md-3">
                                <label for="txt_fecha_fin" class="form-label fw-bold">
                                    <i class="bx bx-calendar me-1"></i> Fecha Fin
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="date" class="form-control form-control-sm"
                                    id="txt_fecha_fin" name="txt_fecha_fin" max="???" min="???" readonly >
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="modal-footer pt-2" id="seccion_boton_consulta">

                                    <!-- <button class="btn btn-primary btn-sm px-3" onclick="buscar_fechas();" type="button"><i class='bx bx-search'></i> Buscar</button> -->

                                    <button onclick="sincronizar_calculo_asistencia_fecha();"
                                        type="button" class="btn btn-primary btn-sm">
                                        <i class="bx bx-rotate-right"></i> Recalcular Fecha
                                    </button>

                                </div>
                            </div>
                        </div>



                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>


<div class="modal" id="modal_Recalcular" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">

                <div class="row">
                    <div class="col-12">
                        <label for="">Tipo de <label class="text-danger">*</label></label>
                        <select name="" id="" class="form-select form-select-sm" onchange="">
                            <option value="">Seleccione el </option>
                        </select>
                    </div>
                </div>

                <div class="row pt-3">
                    <div class="col-12">
                        <label for="">Recalcular <label class="text-danger">*</label></label>
                        <select name="" id="" class="form-select form-select-sm">
                            <option value="">Seleccione el </option>
                        </select>
                    </div>
                </div>

                <div class="row pt-3">
                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-success btn-sm" onclick=""><i class="bx bx-save"></i> Agregar</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>