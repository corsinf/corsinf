<link rel="stylesheet" href="../assets/plugins/notifications/css/lobibox.min.css" />

<style>
    .card-header-calendar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        margin-bottom: 0.75rem;
        flex-wrap: wrap;
    }

    .card-header-calendar .titles {
        text-align: center;
        flex: 1 1 auto;
        min-width: 220px;
    }

    .card-header-calendar h3 {
        margin: 0;
        font-size: 1.05rem;
        font-weight: 700;
        color: #1e3a8a;
        letter-spacing: 0.5px;
    }

    .card-header-calendar p {
        margin: 0;
        font-size: 0.85rem;
        color: #475569;
    }

    .resumen-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.5rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
    }

    .resumen-item {
        text-align: center;
    }

    .resumen-item .numero {
        font-size: 2rem;
        font-weight: 700;
    }

    .resumen-item .label {
        font-size: 0.9rem;
        opacity: 0.9;
    }

    .info-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        margin-bottom: 1.5rem;
    }

    .horario-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }

    /* MEJORA: Grid para los turnos */
    .turnos-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .dia-container {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 1rem;
        border: 1px solid #e9ecef;
    }

    .dia-container h6 {
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #dee2e6;
    }

    .turno-card {
        background: white;
        border-left: 4px solid;
        padding: 0.75rem;
        margin-bottom: 0.75rem;
        border-radius: 8px;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .turno-card:last-child {
        margin-bottom: 0;
    }

    .turno-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .turno-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.25rem;
    }

    .turno-nombre {
        font-size: 0.95rem;
        font-weight: 700;
        color: #2c3e50;
    }

    .turno-horario {
        font-size: 0.85rem;
        color: #5a6c7d;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .color-indicator {
        width: 16px;
        height: 16px;
        border-radius: 4px;
        display: inline-block;
        flex-shrink: 0;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #6c757d;
    }

    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.3;
    }

    /* MEJORA: Grid para la leyenda */
    .leyenda-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .leyenda-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem;
        background: #f8f9fa;
        border-radius: 8px;
        transition: transform 0.2s;
    }

    .leyenda-item:hover {
        transform: translateX(5px);
        background: #e9ecef;
    }

    @media (max-width: 768px) {
        .card-header-calendar {
            gap: 0.5rem;
            flex-direction: column;
        }

        .turnos-grid {
            grid-template-columns: 1fr;
        }

        .leyenda-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="card-header-calendar">
    <div style="flex: 0 0 250px;">
        <label for="ddl_tipo_horario" class="form-label fw-bold">
            <i class="bx bx-time"></i> Tipo de Horario
        </label>
        <select id="ddl_tipo_horario" class="form-select form-select-sm" disabled>
            <option value="">-- Seleccione Horario --</option>
        </select>
    </div>
    <div class="titles" style="flex: 1;">
        <h3>📅 Horarios del Personal</h3>
        <p id="subtitle-center">Vista de horarios asignados</p>
    </div>
</div>

<!-- Estado vacío -->
<div id="estado_vacio" class="info-card empty-state">
    <i class="bx bx-calendar-x"></i>
    <h5>No hay horarios seleccionados</h5>
    <p class="text-muted">Selecciona un departamento para ver los horarios asignados</p>
</div>

<!-- Panel de horarios -->
<div id="pnl_horarios_persona" style="display:none;">

    <!-- Resumen -->
    <div class="resumen-section">
        <div class="row">
            <div class="col-md-4 resumen-item">
                <div class="numero" id="total_turnos">0</div>
                <div class="label">Turnos Configurados</div>
            </div>
            <div class="col-md-4 resumen-item">
                <div class="numero" id="total_dias">0</div>
                <div class="label">Días Laborables</div>
            </div>
            <div class="col-md-4 resumen-item">
                <div class="numero" id="total_horas">0</div>
                <div class="label">Horas Semanales</div>
            </div>
        </div>
    </div>


    <div class="row">
        <!-- Columna izquierda: Información del Horario -->
        <div class="col-md-6">
            <div class="info-card h-100">
                <h5 class="mb-3">
                    <i class="bx bx-info-circle"></i> Información del Horario
                </h5>
                <div id="info_horario_nombre" class="mb-2">
                    <strong>Horario:</strong> <span class="horario-badge bg-primary text-white">No seleccionado</span>
                </div>
                <div id="info_horario_tipo" class="text-muted">
                    <i class="bx bx-tag"></i> Tipo: <span>-</span>
                </div>
            </div>
        </div>

        <!-- Columna derecha: Leyenda de Turnos -->
        <div class="col-md-6">
            <div class="info-card h-100">
                <h5 class="mb-3">
                    <i class="bx bx-palette"></i> Leyenda de Turnos
                </h5>
                <div id="leyenda_turnos" class="leyenda-grid">
                    <!-- Aquí se cargarán los turnos únicos con sus colores -->
                </div>
            </div>
        </div>
    </div>

    <!-- Turnos por día - MEJORADO CON GRID -->
    <div class="info-card">
        <h5 class="mb-4">
            <i class="bx bx-calendar-week"></i> Distribución Semanal de Turnos
        </h5>
        <div id="lista_turnos_por_dia" class="turnos-grid">
            <!-- Aquí se cargarán los turnos en grid -->
        </div>
    </div>

    <!-- Leyenda de turnos únicos - MEJORADO CON GRID -->

</div>

<input id="id_perdep" type="hidden" value="" />

<script src="../assets/plugins/notifications/js/lobibox.min.js"></script>

<script>
    // Mapeo de días
    const DIAS_SEMANA = {
        '1': 'Domingo',
        '2': 'Lunes',
        '3': 'Martes',
        '4': 'Miércoles',
        '5': 'Jueves',
        '6': 'Viernes',
        '7': 'Sábado'
    };

    function cargar_persona_horario(id_persona) {
        $.ajax({
            data: {
                id: id_persona
            },
            url: '../controlador/TALENTO_HUMANO/th_programar_horariosC.php?listar_persona_horario=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                const ddlTipo = $('#ddl_tipo_horario');
                ddlTipo.empty().append('<option value="">-- Seleccione Horario --</option>');

                if (response && response.length > 0) {
                    if (response.length > 1) {
                        ddlTipo.prop('disabled', false);

                        response.forEach(item => {
                            let texto = '';
                            if (item.fuente === 'departamento') {
                                texto = `Horario de Departamento: ${item.nombre_horario}`;
                            } else if (item.fuente === 'persona') {
                                texto = `Horario Personal: ${item.nombre_horario}`;
                            } else {
                                texto = `Horario: ${item.nombre_horario}`;
                            }

                            ddlTipo.append(
                                $('<option>', {
                                    value: item.id_horario,
                                    text: texto
                                })
                            );
                        });

                        ddlTipo.off('change').on('change', function() {
                            const idHorario = $(this).val();
                            if (idHorario) {
                                cargar_turnos_horario(idHorario);
                            }
                        });

                        const idHorarioInicial = response[0].id_horario;
                        ddlTipo.val(idHorarioInicial).trigger('change');
                    } else {
                        ddlTipo.prop('disabled', true);
                        ddlTipo.append(
                            $('<option>', {
                                value: response[0].id_horario,
                                text: response[0].nombre_horario,
                                selected: true
                            })
                        );

                        cargar_turnos_horario(response[0].id_horario);
                    }
                } else {
                    ddlTipo.prop('disabled', true);
                    ddlTipo.append('<option value="">-- Sin horarios --</option>');
                    $('#pnl_horarios_persona').hide();
                    $('#estado_vacio').show();
                }
            },
            error: function(xhr, status, error) {
                console.error('Error al cargar horarios:', error);
            }
        });
    }

    function cargar_turnos_horario(id_horario) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/th_turnos_horarioC.php?listar=true',
            type: 'post',
            data: {
                id: id_horario
            },
            dataType: 'json',
            success: function(response) {
                console.log('Turnos recibidos:', response);

                if (!response || response.length === 0) {
                    Swal.fire('', 'No hay turnos configurados para este horario', 'info');
                    $('#pnl_horarios_persona').hide();
                    $('#estado_vacio').show();
                    return;
                }

                $('#estado_vacio').hide();
                $('#pnl_horarios_persona').show();

                // Agrupar por día
                const turnosPorDia = {};
                const turnosUnicos = {};
                let totalHoras = 0;

                response.forEach(function(turno) {
                    const dia = turno.dia;
                    if (!turnosPorDia[dia]) {
                        turnosPorDia[dia] = [];
                    }
                    turnosPorDia[dia].push(turno);

                    // Turnos únicos para la leyenda
                    if (!turnosUnicos[turno.id_turno]) {
                        turnosUnicos[turno.id_turno] = {
                            nombre: turno.nombre,
                            color: turno.color || '#2196F3',
                            hora_entrada: turno.hora_entrada,
                            hora_salida: turno.hora_salida
                        };
                    }

                    // Calcular horas
                    let horas = (turno.hora_salida - turno.hora_entrada) / 60;
                    if (horas < 0) horas += 24;
                    totalHoras += horas;
                });

                // Actualizar resumen
                $('#total_turnos').text(Object.keys(turnosUnicos).length);
                $('#total_dias').text(Object.keys(turnosPorDia).length);
                $('#total_horas').text(totalHoras.toFixed(1));

                // Actualizar info del horario
                $('#info_horario_nombre span').text($('#ddl_tipo_horario option:selected').text());

                // Detectar tipo basado en el texto del select
                const textoHorario = $('#ddl_tipo_horario option:selected').text();
                let tipoHorario = 'Personal';
                if (textoHorario.includes('Departamento')) {
                    tipoHorario = 'Departamento';
                }
                $('#info_horario_tipo span').text(tipoHorario);

                // Renderizar turnos por día EN GRID
                let htmlTurnos = '';
                const diasOrdenados = Object.keys(turnosPorDia).sort();

                diasOrdenados.forEach(function(dia) {
                    const turnos = turnosPorDia[dia];

                    htmlTurnos += `
                        <div class="dia-container">
                            <h6 class="text-primary mb-2">
                                <i class="bx bx-calendar"></i> ${DIAS_SEMANA[dia]}
                            </h6>
                    `;

                    turnos.forEach(function(turno) {
                        const horaInicio = minutos_formato_hora(turno.hora_entrada);
                        const horaFin = minutos_formato_hora(turno.hora_salida);

                        htmlTurnos += `
                            <div class="turno-card" style="border-left-color: ${turno.color || '#2196F3'};">
                                <div class="turno-header">
                                    <span class="color-indicator" style="background-color: ${turno.color || '#2196F3'};"></span>
                                    <span class="turno-nombre">${turno.nombre}</span>
                                </div>
                                <div class="turno-horario">
                                    <i class="bx bx-time"></i>
                                    <span>${horaInicio} - ${horaFin}</span>
                                </div>
                            </div>
                        `;
                    });

                    htmlTurnos += '</div>';
                });

                $('#lista_turnos_por_dia').html(htmlTurnos);

                // Renderizar leyenda EN GRID
                let htmlLeyenda = '';
                Object.values(turnosUnicos).forEach(function(turno) {
                    const horaInicio = minutos_formato_hora(turno.hora_entrada);
                    const horaFin = minutos_formato_hora(turno.hora_salida);

                    htmlLeyenda += `
                        <div class="leyenda-item">
                            <span class="color-indicator" style="background-color: ${turno.color};"></span>
                            <div>
                                <strong>${turno.nombre}</strong><br>
                                <small class="text-muted">${horaInicio} - ${horaFin}</small>
                            </div>
                        </div>
                    `;
                });

                $('#leyenda_turnos').html(htmlLeyenda);

                // Notificación de éxito
                Lobibox.notify('success', {
                    size: 'mini',
                    rounded: true,
                    delayIndicator: false,
                    sound: false,
                    position: 'top right',
                    msg: 'Horarios cargados correctamente'
                });
            },
            error: function(xhr, status, error) {
                console.error('Error al cargar turnos:', error);
                Swal.fire('', 'Error al cargar los turnos del horario', 'error');
            }
        });
    }

    function minutos_formato_hora(minutos) {
        const horas = Math.floor(minutos / 60);
        const mins = minutos % 60;
        return horas.toString().padStart(2, '0') + ':' + mins.toString().padStart(2, '0') + ':00';
    }
</script>