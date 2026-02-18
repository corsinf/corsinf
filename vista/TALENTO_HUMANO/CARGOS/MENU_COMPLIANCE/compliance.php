<script>
    // ============================================
    // FUNCIONES PARA COMPLIANCE
    // ============================================

    function abrir_modal_compliance() {
        var modal = new bootstrap.Modal(
            document.getElementById('modal_compliance'), {
                backdrop: 'static',
                keyboard: false
            }
        );

        // Limpiar formulario
        $('#form_compliance')[0].reset();
        $('#th_comp_id').val('');
        $('#pnl_crear_compliance').show();
        $('#pnl_actualizar_compliance').hide();
        $('#modalComplianceLabel').html('<i class="bx bx-check-shield me-2"></i> Registrar Compliance del Cargo');

        // Verificar si ya existe compliance para este cargo
        verificar_compliance_existente(<?= $_id ?>);

        modal.show();
    }

    function verificar_compliance_existente(cargoId) {
        $.ajax({
            data: {
                id: cargoId
            },
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_cargo_complianceC.php?listar_compliance_cargo=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response && (Array.isArray(response) ? response.length > 0 : true)) {
                    const data = Array.isArray(response) ? response[0] : response;

                    // Llenar formulario para edición (sin prefijo th_comp_)
                    $('#th_comp_id').val(data._id || data.id || '');
                    $('#th_comp_requisitos_totales').val(data.requisitos_totales || 0);
                    $('#th_comp_requisitos_completados').val(data.requisitos_completados || 0);

                    // Formatear fecha para input type="date" (YYYY-MM-DD)
                    if (data.ultima_revision) {
                        const fecha = new Date(data.ultima_revision);
                        const fechaFormatted = fecha.toISOString().split('T')[0];
                        $('#th_comp_ultima_revision').val(fechaFormatted);
                    }

                    $('#th_comp_observaciones').val(data.observaciones || '');

                    // Calcular valores automáticos
                    calcularComplianceModal();

                    // Cambiar a modo edición
                    $('#pnl_crear_compliance').hide();
                    $('#pnl_actualizar_compliance').show();
                    $('#modalComplianceLabel').html(
                        '<i class="bx bx-edit me-2"></i> Editar Compliance del Cargo'
                    );
                } else {
                    // Modo crear nuevo
                    $('#pnl_crear_compliance').show();
                    $('#pnl_actualizar_compliance').hide();
                    $('#modalComplianceLabel').html(
                        '<i class="bx bx-check-shield me-2"></i> Registrar Compliance del Cargo'
                    );
                }
            },
            error: function(err) {
                console.error('Error al verificar compliance:', err);
                // Modo crear nuevo por defecto
                $('#pnl_crear_compliance').show();
                $('#pnl_actualizar_compliance').hide();
                $('#modalComplianceLabel').html(
                    '<i class="bx bx-check-shield me-2"></i> Registrar Compliance del Cargo'
                );
            }
        });
    }


    // Calcular automáticamente los valores en el modal
    function calcularComplianceModal() {
        const totales = parseInt($('#th_comp_requisitos_totales').val()) || 0;
        const completados = parseInt($('#th_comp_requisitos_completados').val()) || 0;

        // Validar que completados no sea mayor que totales
        if (completados > totales) {
            $('#th_comp_requisitos_completados').val(totales);
            return;
        }

        const faltantes = totales - completados;
        const porcentaje = totales > 0 ? ((completados / totales) * 100).toFixed(2) : 0;

        // Actualizar campos calculados
        $('#th_comp_requisitos_faltantes').val(faltantes);
        $('#th_comp_porcentaje_completado').val(porcentaje);

        // Actualizar vista previa
        $('#preview_completados').text(completados);
        $('#preview_faltantes').text(faltantes);
        $('#preview_porcentaje').text(porcentaje + '%');
    }

    // Obtener parámetros del formulario
    function obtenerParametrosCompliance() {
        return {
            '_id': $('#th_comp_id').val() || '',
            'th_car_id': "<?= isset($_id) ? $_id : '' ?>",
            'th_comp_porcentaje_completado': $('#th_comp_porcentaje_completado').val() || 0,
            'th_comp_requisitos_totales': $('#th_comp_requisitos_totales').val() || 0,
            'th_comp_requisitos_completados': $('#th_comp_requisitos_completados').val() || 0,
            'th_comp_requisitos_faltantes': $('#th_comp_requisitos_faltantes').val() || 0,
            'th_comp_ultima_revision': $('#th_comp_ultima_revision').val() || null,
            'th_comp_observaciones': $('#th_comp_observaciones').val().trim() || ''
        };
    }

    // Validar parámetros
    function validarComplianceParametros(p) {
        if (!p.th_car_id || p.th_car_id === '') {
            Swal.fire('', 'ID del cargo no encontrado. Abra el modal desde un cargo válido.', 'warning');
            return false;
        }
        if (parseInt(p.th_comp_requisitos_totales) < 0) {
            Swal.fire('', 'Los requisitos totales no pueden ser negativos.', 'warning');
            return false;
        }
        if (parseInt(p.th_comp_requisitos_completados) > parseInt(p.th_comp_requisitos_totales)) {
            Swal.fire('', 'Los requisitos completados no pueden ser mayores a los totales.', 'warning');
            return false;
        }
        return true;
    }

    // Guardar o actualizar compliance
    function guardar_o_actualizar_compliance() {
        var parametros = obtenerParametrosCompliance();

        if (!validarComplianceParametros(parametros)) return;

        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_cargo_complianceC.php?insertar_editar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1 || response === true) {
                    Swal.fire('', (parametros._id ? 'Compliance actualizado con éxito.' :
                            'Compliance creado con éxito.'), 'success')
                        .then(function() {
                            var modalEl = document.getElementById('modal_compliance');
                            var modal = bootstrap.Modal.getInstance(modalEl);
                            if (modal) modal.hide();

                            // Recargar datos de compliance en la vista
                            listar_compliance_cargo("<?= isset($_id) ? $_id : '' ?>");
                        });
                } else if (response == -2) {
                    Swal.fire('', 'Ya existe un registro de compliance para este cargo.', 'warning');
                } else {
                    var msg = (typeof response === 'object' && response.msg) ? response.msg :
                        'Error al guardar el compliance.';
                    Swal.fire('', msg, 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error guardar_compliance: ', status, error, xhr.responseText);
                Swal.fire('', 'Error al conectar con el servidor: ' + xhr.responseText, 'error');
            }
        });
    }

    function insertar_compliance() {
        guardar_o_actualizar_compliance();
    }

    // Listar compliance del cargo en la vista
    function listar_compliance_cargo(cargoId) {
        $.ajax({
            data: {
                id: cargoId
            },
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_cargo_complianceC.php?listar_compliance_cargo=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (!response || (Array.isArray(response) && response.length === 0)) {
                    mostrarComplianceVacio();
                    return;
                }

                const data = Array.isArray(response) ? response[0] : response;

                // Extraer datos SIN el prefijo th_comp_
                const porcentaje = parseFloat(data.porcentaje_completado || 0).toFixed(2);
                const totales = parseInt(data.requisitos_totales || 0);
                const completados = parseInt(data.requisitos_completados || 0);
                const faltantes = parseInt(data.requisitos_faltantes || 0);
                const ultimaRevision = data.ultima_revision || '';
                const estado = data.estado || '';
                const observaciones = data.observaciones || '';

                // Actualizar vista principal
                $('#comp_porcentaje').text(porcentaje + '%');
                $('#comp_progress_bar').css('width', porcentaje + '%').attr('aria-valuenow', porcentaje);
                $('#comp_progress_text').text(porcentaje + '%');

                // Cambiar color de la barra según porcentaje
                const $progressBar = $('#comp_progress_bar');
                $progressBar.removeClass('bg-danger bg-warning bg-success');
                if (porcentaje < 50) {
                    $progressBar.addClass('bg-danger');
                } else if (porcentaje < 80) {
                    $progressBar.addClass('bg-warning');
                } else {
                    $progressBar.addClass('bg-success');
                }

                $('#comp_totales').text(totales);
                $('#comp_completados').text(completados);
                $('#comp_faltantes').text(faltantes);

                // Última revisión
                if (ultimaRevision) {
                    const fecha = new Date(ultimaRevision);
                    $('#comp_ultima_revision').text(fecha.toLocaleDateString('es-ES'));
                } else {
                    $('#comp_ultima_revision').html('<em class="text-muted">No registrada</em>');
                }

                // Estado
                const estadoConfig = {
                    '1': {
                        label: 'Activo',
                        class: 'bg-success'
                    },
                    '0': {
                        label: 'Inactivo',
                        class: 'bg-secondary'
                    },
                    '2': {
                        label: 'En Revisión',
                        class: 'bg-warning'
                    },
                    '3': {
                        label: 'Observado',
                        class: 'bg-danger'
                    }
                };
                const estadoInfo = estadoConfig[estado] || {
                    label: 'Sin estado',
                    class: 'bg-secondary'
                };
                $('#comp_estado_badge').removeClass('bg-success bg-secondary bg-warning bg-danger')
                    .addClass(estadoInfo.class)
                    .html(`<i class="bi bi-circle-fill me-1"></i>${estadoInfo.label}`);

                // Observaciones
                if (observaciones && observaciones.trim() !== '') {
                    $('#comp_observaciones').text(observaciones);
                } else {
                    $('#comp_observaciones').html('<em class="text-muted">Sin observaciones registradas</em>');
                }

                // Badge resumen
                $('#badge_resumen').html(
                    `<i class="bi bi-graph-up me-1"></i>Completados: ${completados} de ${totales} (${porcentaje}%)`
                );

                console.log('Compliance cargado exitosamente:', data);
            },
            error: function(err) {
                console.error('Error al cargar compliance:', err);
                mostrarComplianceVacio();
            }
        });
    }

    function mostrarComplianceVacio() {
        $('#comp_porcentaje').text('0%');
        $('#comp_progress_bar').css('width', '0%').attr('aria-valuenow', 0).removeClass('bg-warning bg-success').addClass(
            'bg-secondary');
        $('#comp_progress_text').text('0%');
        $('#comp_totales').text('0');
        $('#comp_completados').text('0');
        $('#comp_faltantes').text('0');
        $('#comp_ultima_revision').html('<em class="text-muted">No registrada</em>');
        $('#comp_estado_badge').removeClass('bg-success bg-warning bg-danger').addClass('bg-secondary')
            .html('<i class="bi bi-circle-fill me-1"></i>Sin estado');
        $('#comp_observaciones').html('<em class="text-muted">Sin observaciones registradas</em>');
        $('#badge_resumen').html('<i class="bi bi-graph-up me-1"></i>Completados: 0 de 0 (0%)');
    }

    // Event listeners
    $(function() {
        // Calcular automáticamente cuando cambien los valores
        $('#th_comp_requisitos_totales, #th_comp_requisitos_completados').on('input change', function() {
            calcularComplianceModal();
        });

        // Actualizar
        $(document).on('click', '#btn_editar_compliance', function(e) {
            e.preventDefault();
            guardar_o_actualizar_compliance();
        });

        // Eliminar
        $(document).on('click', '#btn_eliminar_compliance', function(e) {
            e.preventDefault();
            eliminar_compliance();
        });

        // Cargar compliance al iniciar
        const cargoId = "<?= isset($_id) ? $_id : '' ?>";
        if (cargoId) {
            listar_compliance_cargo(cargoId);
        }
    });

    function eliminar_compliance() {
        const compId = $('#th_comp_id').val();

        if (!compId) {
            Swal.fire('', 'No hay registro de compliance para eliminar.', 'warning');
            return;
        }

        Swal.fire({
            title: '¿Está seguro?',
            text: "Esta acción eliminará el registro de compliance del cargo",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    data: {
                        id: compId
                    },
                    url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_cargo_complianceC.php?eliminar=true',
                    type: 'post',
                    dataType: 'json',
                    success: function(response) {
                        if (response == 1 || response === true) {
                            Swal.fire('Eliminado', 'El registro de compliance ha sido eliminado.',
                                    'success')
                                .then(function() {
                                    var modalEl = document.getElementById('modal_compliance');
                                    var modal = bootstrap.Modal.getInstance(modalEl);
                                    if (modal) modal.hide();

                                    mostrarComplianceVacio();
                                });
                        } else {
                            Swal.fire('', 'Error al eliminar el registro de compliance.', 'error');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('', 'Error al conectar con el servidor: ' + xhr.responseText,
                            'error');
                    }
                });
            }
        });
    }
</script>

<script>
    // ============================================
    // FUNCIONES PARA FUNCIONES DEL CARGO
    // ============================================

    function abrir_modal_funciones() {
        var modal = new bootstrap.Modal(
            document.getElementById('modal_funciones_cargo'), {
                backdrop: 'static',
                keyboard: false
            }
        );

        // Limpiar formulario
        $('#form_funciones_cargo')[0].reset();
        $('#th_carfun_id').val('');
        $('#th_car_id_funcion').val(<?= $_id ?>);
        $('#pnl_crear_funcion').show();
        $('#pnl_actualizar_funcion').hide();
        $('#modalFuncionesLabel').html('<i class="bx bx-list-check me-2"></i> Registrar Función del Cargo');

        // Limpiar vista previa
        $('#preview_frecuencia').text('-');
        $('#preview_porcentaje').text('0%');
        $('#preview_tipo').removeClass('bg-warning bg-info').addClass('bg-secondary').text('-');
        $('#preview_orden').text('#1');

        modal.show();
    }

    // ============================================
    // ACTUALIZAR VISTA PREVIA EN TIEMPO REAL
    // ============================================
    function actualizarVistaPrevia() {
        const frecuencia = $('#th_carfun_frecuencia').val() || '-';
        const porcentaje = $('#th_carfun_porcentaje_tiempo').val() || '0';
        const esPrincipal = $('#th_carfun_es_principal').val();
        const orden = $('#th_carfun_orden').val() || '1';

        $('#preview_frecuencia').text(frecuencia);
        $('#preview_porcentaje').text(porcentaje + '%');
        $('#preview_orden').text('#' + orden);

        const $tipoBadge = $('#preview_tipo');
        $tipoBadge.removeClass('bg-warning bg-info bg-secondary');

        if (esPrincipal === '1') {
            $tipoBadge.addClass('bg-warning').text('Principal');
        } else if (esPrincipal === '0') {
            $tipoBadge.addClass('bg-info').text('Secundaria');
        } else {
            $tipoBadge.addClass('bg-secondary').text('-');
        }
    }

    // ============================================
    // OBTENER PARÁMETROS DEL FORMULARIO
    // ============================================
    function obtenerParametrosFuncion() {
        return {
            '_id': $('#th_carfun_id').val() || '',
            'th_car_id': "<?= isset($_id) ? $_id : '' ?>",
            'nombre': $('#th_carfun_nombre').val().trim(),
            'descripcion': $('#th_carfun_descripcion').val().trim(),
            'frecuencia': $('#th_carfun_frecuencia').val(),
            'porcentaje_tiempo': $('#th_carfun_porcentaje_tiempo').val() || 0,
            'es_principal': $('#th_carfun_es_principal').val() === '1' ? 1 : 0,
            'orden': $('#th_carfun_orden').val() || 1,
            'estado': 1
        };
    }

    // ============================================
    // VALIDAR PARÁMETROS
    // ============================================
    function validarFuncionParametros(p) {
        if (!p.th_car_id || p.th_car_id === '') {
            Swal.fire('', 'ID del cargo no encontrado.', 'warning');
            return false;
        }
        if (!p.nombre || p.nombre === '') {
            Swal.fire('', 'El nombre de la función es obligatorio.', 'warning');
            return false;
        }
        if (!p.frecuencia || p.frecuencia === '') {
            Swal.fire('', 'La frecuencia es obligatoria.', 'warning');
            return false;
        }
        const porcentaje = parseFloat(p.porcentaje_tiempo);
        if (porcentaje < 0 || porcentaje > 100) {
            Swal.fire('', 'El porcentaje de tiempo debe estar entre 0 y 100.', 'warning');
            return false;
        }
        if (p.es_principal === '' || p.es_principal === null) {
            Swal.fire('', 'Debe indicar si es función principal o no.', 'warning');
            return false;
        }
        return true;
    }

    // ============================================
    // GUARDAR O ACTUALIZAR FUNCIÓN
    // ============================================
    function guardar_o_actualizar_funcion() {
        var parametros = obtenerParametrosFuncion();

        if (!validarFuncionParametros(parametros)) return;

        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_cargo_funcionesC.php?insertar_editar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1 || response === true) {
                    Swal.fire('', (parametros._id ? 'Función actualizada con éxito.' :
                            'Función creada con éxito.'), 'success')
                        .then(function() {
                            var modalEl = document.getElementById('modal_funciones_cargo');
                            var modal = bootstrap.Modal.getInstance(modalEl);
                            if (modal) modal.hide();

                            // Recargar tabla de funciones
                            listar_funciones_cargo(<?= $_id ?>);
                        });
                } else if (response == -2) {
                    Swal.fire('', 'Ya existe una función con ese nombre para este cargo.', 'warning');
                } else {
                    var msg = (typeof response === 'object' && response.msg) ? response.msg :
                        'Error al guardar la función.';
                    Swal.fire('', msg, 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error guardar_funcion:', status, error, xhr.responseText);
                Swal.fire('', 'Error al conectar con el servidor: ' + xhr.responseText, 'error');
            }
        });
    }


    // ============================================
    // LISTAR FUNCIONES EN LA TABLA
    // ============================================
    function listar_funciones_cargo(cargoId) {
        $.ajax({
            data: {
                id: cargoId
            },
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_cargo_funcionesC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                const $tbody = $('#tbody_funciones');
                $tbody.empty();

                if (!response || response.length === 0) {
                    $tbody.html(`
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            <i class="bx bx-info-circle" style="font-size: 2rem;"></i>
                            <p class="mb-0 mt-2">No hay funciones registradas para este cargo</p>
                        </td>
                    </tr>
                `);
                    actualizarEstadisticasFunciones(0, 0, 0, 0);
                    return;
                }

                let totalPrincipales = 0;
                let totalSecundarias = 0;
                let porcentajeAcumulado = 0;

                response.forEach((func, index) => {
                    const esPrincipal = parseInt(func.th_carfun_es_principal) === 1;
                    const porcentaje = parseFloat(func.th_carfun_porcentaje_tiempo || 0);

                    if (esPrincipal) totalPrincipales++;
                    else totalSecundarias++;

                    porcentajeAcumulado += porcentaje;

                    const tipoBadge = esPrincipal ?
                        '<span class="badge bg-warning"><i class="bx bx-star"></i> Principal</span>' :
                        '<span class="badge bg-info"><i class="bx bx-bookmark"></i> Secundaria</span>';

                    const row = `
                    <tr>
                        <td class="text-center fw-bold">${index + 1}</td>
                        <td>
                            <div class="fw-bold text-primary">${func.th_carfun_nombre}</div>
                            ${func.th_carfun_descripcion ? `<small class="text-muted">${func.th_carfun_descripcion}</small>` : ''}
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                <i class="bx bx-time"></i> ${func.th_carfun_frecuencia || 'N/A'}
                            </span>
                        </td>
                        <td class="text-center">
                            <strong class="text-success">${porcentaje.toFixed(2)}%</strong>
                        </td>
                        <td class="text-center">${tipoBadge}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-primary" onclick="editar_funcion('${func._id}')" title="Editar">
                                <i class="bx bx-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="confirmar_eliminar_funcion('${func._id}')" title="Eliminar">
                                <i class="bx bx-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
                    $tbody.append(row);
                });

                actualizarEstadisticasFunciones(response.length, totalPrincipales, totalSecundarias,
                    porcentajeAcumulado);
            },
            error: function(err) {
                console.error('Error al cargar funciones:', err);
                $('#tbody_funciones').html(`
                <tr>
                    <td colspan="6" class="text-center py-4 text-danger">
                        <i class="bx bx-error-circle" style="font-size: 2rem;"></i>
                        <p class="mb-0 mt-2">Error al cargar las funciones</p>
                    </td>
                </tr>
            `);
            }
        });
    }

    // ============================================
    // ACTUALIZAR ESTADÍSTICAS EN EL FOOTER
    // ============================================
    function actualizarEstadisticasFunciones(total, principales, secundarias, porcentajeTotal) {
        $('#stat_total').text(total);
        $('#stat_principales').text(principales);
        $('#stat_secundarias').text(secundarias);
        $('#stat_porcentaje_total').text(porcentajeTotal.toFixed(2) + '%');
    }

    // ============================================
    // EDITAR FUNCIÓN
    // ============================================
    function editar_funcion() {
        $.ajax({
            data: {
                id: "<?= isset($_id) ? $_id : '' ?>"
            },
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_cargo_funcionesC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                // Llenar formulario
                $('#th_carfun_id').val(response[0]._id);
                $('#th_car_id_funcion').val(response[0].th_car_id);
                $('#th_carfun_nombre').val(response[0].th_carfun_nombre);
                $('#th_carfun_descripcion').val(response[0].th_carfun_descripcion || '');
                $('#th_carfun_frecuencia').val(response[0].th_carfun_frecuencia);
                $('#th_carfun_porcentaje_tiempo').val(response[0].th_carfun_porcentaje_tiempo || 0);
                $('#th_carfun_es_principal').val(response[0].th_carfun_es_principal);
                $('#th_carfun_orden').val(response[0].th_carfun_orden || 1);

                // Actualizar vista previa
                actualizarVistaPrevia();

                // Cambiar a modo edición
                $('#pnl_crear_funcion').hide();
                $('#pnl_actualizar_funcion').show();
                $('#modalFuncionesLabel').html('<i class="bx bx-edit me-2"></i> Editar Función del Cargo');

                // Abrir modal
                var modal = new bootstrap.Modal(document.getElementById('modal_funciones_cargo'));
                modal.show();
            },
            error: function(err) {
                console.error('Error al cargar función:', err);
                Swal.fire('', 'Error al cargar los datos de la función', 'error');
            }
        });
    }

    // ============================================
    // CONFIRMAR ELIMINAR FUNCIÓN
    // ============================================
    function confirmar_eliminar_funcion(funcionId) {
        Swal.fire({
            title: '¿Está seguro?',
            text: "Esta acción eliminará la función del cargo",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                eliminar_funcion(funcionId);
            }
        });
    }

    // ============================================
    // ELIMINAR FUNCIÓN
    // ============================================
    function eliminar_funcion(funcionId) {
        $.ajax({
            data: {
                id: funcionId
            },
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_cargo_funcionesC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1 || response === true) {
                    Swal.fire('Eliminado', 'La función ha sido eliminada.', 'success')
                        .then(function() {
                            listar_funciones_cargo(<?= $_id ?>);
                        });
                } else {
                    Swal.fire('', 'Error al eliminar la función.', 'error');
                }
            },
            error: function(xhr) {
                Swal.fire('', 'Error al conectar con el servidor: ' + xhr.responseText, 'error');
            }
        });
    }

    // ============================================
    // EVENT LISTENERS
    // ============================================
    $(function() {
        // Actualizar vista previa en tiempo real
        $('#th_carfun_frecuencia, #th_carfun_porcentaje_tiempo, #th_carfun_es_principal, #th_carfun_orden')
            .on('input change', actualizarVistaPrevia);

        // Actualizar
        $(document).on('click', '#btn_editar_funcion', function(e) {
            e.preventDefault();
            guardar_o_actualizar_funcion();
        });

        // Eliminar desde modal
        $(document).on('click', '#btn_eliminar_funcion', function(e) {
            e.preventDefault();
            const funcionId = $('#th_carfun_id').val();
            if (funcionId) {
                confirmar_eliminar_funcion(funcionId);
            }
        });

        // Cargar funciones al iniciar
        const cargoId = "<?= isset($_id) ? $_id : '' ?>";
        if (cargoId) {
            listar_funciones_cargo(cargoId);
        }
    });
</script>

<div class="container-fluid">

    <!-- Encabezado -->
    <div class="row mb-3 align-items-center">
        <div class="col-md-8">
            <h5 class="fw-bold text-primary mb-1">
                <i class="bx bx-check-shield me-2"></i>Compliance del Cargo
            </h5>
            <small class="text-muted">
                <i class="bi bi-clipboard-check me-1"></i>
                Estado de cumplimiento de requisitos y documentación
            </small>
        </div>
        <?php if (isset($_GET['_id'])) { ?>
            <div class="col-md-4 d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-success btn-sm shadow-sm"
                    onclick="abrir_modal_funciones()">
                    <i class="bx bx-plus-circle me-1"></i> Funciones
                </button>

                <button type="button" class="btn btn-success btn-sm shadow-sm"
                    onclick="abrir_modal_compliance()">
                    <i class="bx bx-plus-circle me-1"></i> Actualizar Compliance
                </button>
            </div>
        <?php } ?>
    </div>
    <?php if (isset($_GET['_id'])) { ?>
        <!-- Tarjeta Principal de Compliance -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">

                <!-- Indicador de Progreso Principal -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="text-center p-4 bg-light rounded-3 border">
                            <h3 class="fw-bold text-primary mb-2"
                                id="comp_porcentaje">
                                0%</h3>
                            <p class="text-muted mb-3 small">Porcentaje de
                                Completitud
                            </p>

                            <div class="progress" style="height: 25px;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                                    id="comp_progress_bar" role="progressbar"
                                    style="width: 0%;" aria-valuenow="0"
                                    aria-valuemin="0" aria-valuemax="100">
                                    <span class="fw-bold"
                                        id="comp_progress_text">0%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas Rápidas -->
                <div class="row g-3 mb-4">

                    <!-- Requisitos Totales -->
                    <div class="col-md-4">
                        <div
                            class="card border-primary border-2 h-100 shadow-sm">
                            <div class="card-body text-center p-3">
                                <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                                    style="width: 50px; height: 50px;">
                                    <i class="bi bi-list-check text-primary"
                                        style="font-size: 1.5rem;"></i>
                                </div>
                                <h4 class="fw-bold text-primary mb-1"
                                    id="comp_totales">
                                    0</h4>
                                <small class="text-muted">Requisitos
                                    Totales</small>
                            </div>
                        </div>
                    </div>

                    <!-- Requisitos Completados -->
                    <div class="col-md-4">
                        <div
                            class="card border-success border-2 h-100 shadow-sm">
                            <div class="card-body text-center p-3">
                                <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                                    style="width: 50px; height: 50px;">
                                    <i class="bi bi-check-circle-fill text-success"
                                        style="font-size: 1.5rem;"></i>
                                </div>
                                <h4 class="fw-bold text-success mb-1"
                                    id="comp_completados">
                                    0</h4>
                                <small class="text-muted">Completados</small>
                            </div>
                        </div>
                    </div>

                    <!-- Requisitos Faltantes -->
                    <div class="col-md-4">
                        <div
                            class="card border-danger border-2 h-100 shadow-sm">
                            <div class="card-body text-center p-3">
                                <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                                    style="width: 50px; height: 50px;">
                                    <i class="bi bi-exclamation-circle-fill text-danger"
                                        style="font-size: 1.5rem;"></i>
                                </div>
                                <h4 class="fw-bold text-danger mb-1"
                                    id="comp_faltantes">0
                                </h4>
                                <small class="text-muted">Faltantes</small>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Información Adicional -->
                <div class="row g-3">

                    <!-- Última Revisión y Estado -->
                    <div class="col-md-6">
                        <div
                            class="p-3 bg-light rounded border-start border-info border-4">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-calendar-check text-info me-2"
                                    style="font-size: 1.2rem;"></i>
                                <strong class="text-dark">Última
                                    Revisión:</strong>
                            </div>
                            <p class="mb-0 text-muted small"
                                id="comp_ultima_revision">
                                <em>No registrada</em>
                            </p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div
                            class="p-3 bg-light rounded border-start border-warning border-4">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-flag text-warning me-2"
                                    style="font-size: 1.2rem;"></i>
                                <strong class="text-dark">Estado:</strong>
                            </div>
                            <span class="badge bg-secondary"
                                id="comp_estado_badge">
                                <i class="bi bi-circle-fill me-1"></i>Sin estado
                            </span>
                        </div>
                    </div>

                    <!-- Observaciones -->
                    <div class="col-12">
                        <div
                            class="p-3 bg-light rounded border-start border-primary border-4">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-chat-left-text text-primary me-2"
                                    style="font-size: 1.2rem;"></i>
                                <strong
                                    class="text-dark">Observaciones:</strong>
                            </div>
                            <p class="mb-0 text-muted small"
                                id="comp_observaciones">
                                <em>Sin observaciones registradas</em>
                            </p>
                        </div>
                    </div>

                </div>

                <!-- Badge de Resumen -->
                <div class="mt-3 pt-3 border-top text-center">
                    <span
                        class="badge rounded-pill bg-info-subtle text-info px-3 py-2 me-2"
                        id="badge_resumen">
                        <i class="bi bi-graph-up me-1"></i>
                        Completados: 0 de 0 (0%)
                    </span>
                </div>

            </div>
        </div>
    <?php } ?>


</div>


<?php if (isset($_GET['_id'])) { ?>
    <div class="row mb-3 align-items-center">
        <div class="col-md-8">
            <h5 class="fw-bold text-primary mb-1">
                <i class="bx bx-list-check me-2"></i>Funciones del Cargo
            </h5>
            <small class="text-muted">
                <i class="bi bi-briefcase me-1"></i>
                Listado de funciones y responsabilidades del cargo
            </small>
        </div>
    </div>

    <!-- Tarjeta con Tabla -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">

            <!-- Tabla de Funciones -->
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0"
                    id="tabla_funciones">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-center" style="width: 50px;">#</th>
                            <th><i class="bx bx-notepad me-1"></i> Función</th>
                            <th><i class="bx bx-time me-1"></i> Frecuencia</th>
                            <th class="text-center"><i
                                    class="bx bx-pie-chart-alt-2 me-1"></i> %
                                Tiempo</th>
                            <th class="text-center"><i
                                    class="bx bx-star me-1"></i>
                                Tipo</th>
                            <th class="text-center"><i
                                    class="bx bx-cog me-1"></i>
                                Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tbody_funciones">
                        <!-- Se cargará dinámicamente -->
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="bx bx-loader bx-spin"
                                    style="font-size: 2rem;"></i>
                                <p class="mb-0 mt-2">Cargando funciones...</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Footer con estadísticas -->
            <div class="card-footer bg-light">
                <div class="row text-center">
                    <div class="col-md-3">
                        <small class="text-muted d-block">Total
                            Funciones</small>
                        <strong class="text-primary" id="stat_total">0</strong>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted d-block">Principales</small>
                        <strong class="text-warning"
                            id="stat_principales">0</strong>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted d-block">Secundarias</small>
                        <strong class="text-info"
                            id="stat_secundarias">0</strong>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted d-block">% Asignado</small>
                        <strong class="text-success"
                            id="stat_porcentaje_total">0%</strong>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>


    <div class="modal fade" id="modal_compliance" tabindex="-1" aria-labelledby="modalComplianceLabel" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="modalComplianceLabel">
                        <i class="bx bx-check-shield me-2"></i> Registrar Compliance del Cargo
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <!-- Modal body -->
                <div class="modal-body p-4">
                    <form id="form_compliance">
                        <input type="hidden" id="th_comp_id" name="th_comp_id">
                        <input type="hidden" id="th_car_id_comp" name="th_car_id_comp">

                        <div class="row g-3">

                            <!-- Porcentaje Completado -->
                            <div class="col-md-6">
                                <label for="th_comp_porcentaje_completado" class="form-label fw-bold">
                                    <i class="bx bx-pie-chart-alt-2 me-2 text-primary"></i> Porcentaje Completado
                                    (%)
                                </label>
                                <input type="number" class="form-control" id="th_comp_porcentaje_completado"
                                    name="th_comp_porcentaje_completado" min="0" max="100" step="0.01" placeholder="0.00"
                                    readonly>
                                <small class="text-muted">
                                    <i class="bi bi-info-circle"></i> Calculado automáticamente
                                </small>
                            </div>

                            <!-- Requisitos Totales -->
                            <div class="col-md-6">
                                <label for="th_comp_requisitos_totales" class="form-label fw-bold">
                                    <i class="bx bx-list-ul me-2 text-info"></i> Requisitos Totales
                                </label>
                                <input type="number" class="form-control" id="th_comp_requisitos_totales"
                                    name="th_comp_requisitos_totales" min="0" placeholder="Ej: 10" required>
                            </div>

                            <!-- Requisitos Completados -->
                            <div class="col-md-6">
                                <label for="th_comp_requisitos_completados" class="form-label fw-bold">
                                    <i class="bx bx-check-circle me-2 text-success"></i> Requisitos Completados
                                </label>
                                <input type="number" class="form-control" id="th_comp_requisitos_completados"
                                    name="th_comp_requisitos_completados" min="0" placeholder="Ej: 7" required>
                            </div>

                            <!-- Requisitos Faltantes -->
                            <div class="col-md-6">
                                <label for="th_comp_requisitos_faltantes" class="form-label fw-bold">
                                    <i class="bx bx-error-circle me-2 text-danger"></i> Requisitos Faltantes
                                </label>
                                <input type="number" class="form-control" id="th_comp_requisitos_faltantes"
                                    name="th_comp_requisitos_faltantes" min="0" placeholder="Ej: 3" readonly>
                                <small class="text-muted">
                                    <i class="bi bi-calculator"></i> Calculado automáticamente
                                </small>
                            </div>

                            <!-- Última Revisión -->
                            <div class="col-md-6">
                                <label for="th_comp_ultima_revision" class="form-label fw-bold">
                                    <i class="bx bx-calendar me-2 text-warning"></i> Última Revisión
                                </label>
                                <input type="date" class="form-control" id="th_comp_ultima_revision"
                                    name="th_comp_ultima_revision">
                            </div>

                            <!-- Estado -->


                            <!-- Observaciones -->
                            <div class="col-md-12">
                                <label for="th_comp_observaciones" class="form-label fw-bold">
                                    <i class="bx bx-message-detail me-2 text-info"></i> Observaciones
                                </label>
                                <textarea class="form-control" id="th_comp_observaciones" name="th_comp_observaciones"
                                    rows="3" placeholder="Ingrese observaciones o notas adicionales..."></textarea>
                            </div>

                            <!-- Vista Previa del Cálculo -->
                            <div class="col-12">
                                <div class="alert alert-info mb-0">
                                    <h6 class="fw-bold mb-2">
                                        <i class="bi bi-calculator me-2"></i>Vista Previa del Cálculo:
                                    </h6>
                                    <div class="row text-center g-2">
                                        <div class="col-4">
                                            <small class="text-muted d-block">Completados</small>
                                            <strong class="text-success" id="preview_completados">0</strong>
                                        </div>
                                        <div class="col-4">
                                            <small class="text-muted d-block">Faltantes</small>
                                            <strong class="text-danger" id="preview_faltantes">0</strong>
                                        </div>
                                        <div class="col-4">
                                            <small class="text-muted d-block">Porcentaje</small>
                                            <strong class="text-primary" id="preview_porcentaje">0%</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Botones de acción -->
                        <div class="d-flex justify-content-end gap-2 pt-3 mt-3 border-top">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                <i class="bx bx-x me-1"></i> Cancelar
                            </button>

                            <div id="pnl_crear_compliance">
                                <button type="button" class="btn btn-success" onclick="insertar_compliance()">
                                    <i class="bx bx-save me-1"></i> Guardar Compliance
                                </button>
                            </div>

                            <div id="pnl_actualizar_compliance" style="display:none">
                                <button type="button" class="btn btn-danger" id="btn_eliminar_compliance">
                                    <i class="bx bx-trash me-1"></i> Eliminar
                                </button>
                                <button type="button" class="btn btn-primary" id="btn_editar_compliance">
                                    <i class="bx bx-check me-1"></i> Actualizar Compliance
                                </button>
                            </div>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_funciones_cargo" tabindex="-1" aria-labelledby="modalFuncionesLabel"
        aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalFuncionesLabel">
                        <i class="bx bx-list-check me-2"></i> Registrar Función del Cargo
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Cerrar"></button>
                </div>

                <!-- Modal body -->
                <div class="modal-body p-4">
                    <form id="form_funciones_cargo">
                        <input type="hidden" id="th_carfun_id" name="th_carfun_id">
                        <input type="hidden" id="th_car_id_funcion" name="th_car_id_funcion">

                        <div class="row g-3">

                            <!-- Nombre de la Función -->
                            <div class="col-md-12">
                                <label for="th_carfun_nombre" class="form-label fw-bold">
                                    <i class="bx bx-notepad me-2 text-primary"></i> Nombre de la Función
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="th_carfun_nombre" name="th_carfun_nombre"
                                    placeholder="Ej: Supervisión de equipos de trabajo" required>
                                <small class="text-muted">
                                    <i class="bi bi-info-circle"></i> Título breve y descriptivo de la función
                                </small>
                            </div>

                            <!-- Descripción -->
                            <div class="col-md-12">
                                <label for="th_carfun_descripcion" class="form-label fw-bold">
                                    <i class="bx bx-message-detail me-2 text-info"></i> Descripción
                                </label>
                                <textarea class="form-control" id="th_carfun_descripcion" name="th_carfun_descripcion"
                                    rows="4"
                                    placeholder="Detalle las actividades y responsabilidades específicas de esta función..."></textarea>
                                <small class="text-muted">
                                    <i class="bi bi-pencil"></i> Describa detalladamente las actividades de esta función
                                </small>
                            </div>

                            <!-- Frecuencia -->
                            <div class="col-md-6">
                                <label for="th_carfun_frecuencia" class="form-label fw-bold">
                                    <i class="bx bx-time me-2 text-warning"></i> Frecuencia
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="th_carfun_frecuencia" name="th_carfun_frecuencia" required>
                                    <option value="">-- Seleccione frecuencia --</option>
                                    <option value="Diaria">Diaria</option>
                                    <option value="Semanal">Semanal</option>
                                    <option value="Quincenal">Quincenal</option>
                                    <option value="Mensual">Mensual</option>
                                    <option value="Trimestral">Trimestral</option>
                                    <option value="Semestral">Semestral</option>
                                    <option value="Anual">Anual</option>
                                    <option value="Eventual">Eventual</option>
                                    <option value="Permanente">Permanente</option>
                                </select>
                            </div>

                            <!-- Porcentaje de Tiempo -->
                            <div class="col-md-6">
                                <label for="th_carfun_porcentaje_tiempo" class="form-label fw-bold">
                                    <i class="bx bx-pie-chart-alt-2 me-2 text-success"></i> Porcentaje de Tiempo (%)
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control" id="th_carfun_porcentaje_tiempo"
                                    name="th_carfun_porcentaje_tiempo" min="0" max="100" step="0.01" placeholder="Ej: 25.5"
                                    required>
                                <small class="text-muted">
                                    <i class="bi bi-calculator"></i> % del tiempo dedicado a esta función (0-100)
                                </small>
                            </div>

                            <!-- Es Función Principal -->
                            <div class="col-md-6">
                                <label for="th_carfun_es_principal" class="form-label fw-bold">
                                    <i class="bx bx-star me-2 text-warning"></i> ¿Es Función Principal?
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="th_carfun_es_principal" name="th_carfun_es_principal"
                                    required>
                                    <option value="">-- Seleccione --</option>
                                    <option value="1">Sí, es función principal</option>
                                    <option value="0">No, es función secundaria</option>
                                </select>
                                <small class="text-muted">
                                    <i class="bi bi-info-circle"></i> Indica si es una función primaria del cargo
                                </small>
                            </div>

                            <!-- Orden de Prioridad -->
                            <div class="col-md-6">
                                <label for="th_carfun_orden" class="form-label fw-bold">
                                    <i class="bx bx-sort-alt-2 me-2 text-secondary"></i> Orden de Prioridad
                                </label>
                                <input type="number" class="form-control" id="th_carfun_orden" name="th_carfun_orden"
                                    min="1" placeholder="Ej: 1, 2, 3..." value="1">
                                <small class="text-muted">
                                    <i class="bi bi-sort-numeric-down"></i> Orden en que aparecerá la función (menor número
                                    = mayor prioridad)
                                </small>
                            </div>

                            <!-- Resumen Visual -->
                            <div class="col-12">
                                <div class="alert alert-light border">
                                    <h6 class="fw-bold mb-3">
                                        <i class="bi bi-eye me-2"></i>Vista Previa:
                                    </h6>
                                    <div class="row g-2">
                                        <div class="col-md-3">
                                            <small class="text-muted d-block">Frecuencia</small>
                                            <strong id="preview_frecuencia" class="text-primary">-</strong>
                                        </div>
                                        <div class="col-md-3">
                                            <small class="text-muted d-block">% Tiempo</small>
                                            <strong id="preview_porcentaje" class="text-success">0%</strong>
                                        </div>
                                        <div class="col-md-3">
                                            <small class="text-muted d-block">Tipo</small>
                                            <span id="preview_tipo" class="badge bg-secondary">-</span>
                                        </div>
                                        <div class="col-md-3">
                                            <small class="text-muted d-block">Prioridad</small>
                                            <strong id="preview_orden" class="text-dark">#1</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Botones de acción -->
                        <div class="d-flex justify-content-end gap-2 pt-3 mt-3 border-top">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                <i class="bx bx-x me-1"></i> Cancelar
                            </button>

                            <div id="pnl_crear_funcion">
                                <button type="button" class="btn btn-success" onclick="guardar_o_actualizar_funcion()">
                                    <i class="bx bx-save me-1"></i> Guardar Función
                                </button>
                            </div>

                            <div id="pnl_actualizar_funcion" style="display:none">
                                <button type="button" class="btn btn-danger" id="btn_eliminar_funcion">
                                    <i class="bx bx-trash me-1"></i> Eliminar
                                </button>
                                <button type="button" class="btn btn-primary" id="btn_editar_funcion">
                                    <i class="bx bx-check me-1"></i> Actualizar Función
                                </button>
                            </div>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>


    <script>
        $(function() {
            // Actualizar vista previa de frecuencia
            $('#th_carfun_frecuencia').on('change', function() {
                const valor = $(this).val();
                $('#preview_frecuencia').text(valor || '-');
            });

            // Actualizar vista previa de porcentaje
            $('#th_carfun_porcentaje_tiempo').on('input change', function() {
                const valor = parseFloat($(this).val()) || 0;
                $('#preview_porcentaje').text(valor.toFixed(2) + '%');
            });

            // Actualizar vista previa de tipo (principal/secundaria)
            $('#th_carfun_es_principal').on('change', function() {
                const valor = $(this).val();
                const $badge = $('#preview_tipo');

                if (valor === '1') {
                    $badge.removeClass('bg-secondary bg-info')
                        .addClass('bg-warning')
                        .html('<i class="bx bx-star me-1"></i>Principal');
                } else if (valor === '0') {
                    $badge.removeClass('bg-secondary bg-warning')
                        .addClass('bg-info')
                        .html('<i class="bx bx-checkbox-checked me-1"></i>Secundaria');
                } else {
                    $badge.removeClass('bg-warning bg-info')
                        .addClass('bg-secondary')
                        .text('-');
                }
            });

            // Actualizar vista previa de orden
            $('#th_carfun_orden').on('input change', function() {
                const valor = parseInt($(this).val()) || 1;
                $('#preview_orden').text('#' + valor);
            });
        });

        // ============================================
        // FUNCIÓN PARA ABRIR MODAL DE NUEVA FUNCIÓN
        // ============================================
        function abrir_modal_funcion(cargoId) {
            // Limpiar formulario
            $('#form_funciones_cargo')[0].reset();
            $('#th_carfun_id').val('');
            $('#th_car_id_funcion').val(cargoId);

            // Resetear vista previa
            $('#preview_frecuencia').text('-');
            $('#preview_porcentaje').text('0%');
            $('#preview_tipo').removeClass('bg-warning bg-info').addClass('bg-secondary').text('-');
            $('#preview_orden').text('#1');

            // Modo crear
            $('#pnl_crear_funcion').show();
            $('#pnl_actualizar_funcion').hide();
            $('#modalFuncionesLabel').html('<i class="bx bx-list-check me-2"></i> Registrar Función del Cargo');

            // Abrir modal
            $('#modal_funciones_cargo').modal('show');
        }
    </script>