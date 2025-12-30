<link rel="stylesheet" href="../assets/plugins/notifications/css/lobibox.min.css" />

<style>
    .horarios-container {
        padding: 1rem;
    }

    .header-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .header-left h5 {
        margin: 0;
        font-size: 1rem;
        font-weight: 700;
        color: #0d47a1;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .header-right h5 {
        margin: 0;
        font-size: 1rem;
        font-weight: 700;
        color: #0d47a1;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .select-wrapper {
        margin-top: 0.5rem;
    }

    .select-wrapper select {
        min-width: 250px;
    }

    /* Resumen con fondo azul */
    .resumen-bar {
        background: linear-gradient(135deg, #0d47a1 0%, #1565c0 100%);
        color: white;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        display: flex;
        justify-content: space-around;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .resumen-item {
        text-align: center;
        flex: 1;
        min-width: 100px;
    }

    .resumen-item .numero {
        font-size: 2rem;
        font-weight: 700;
        line-height: 1;
    }

    .resumen-item .label {
        font-size: 0.85rem;
        opacity: 0.95;
        margin-top: 0.25rem;
    }

    /* Info del horario */
    .info-horario {
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        border-left: 4px solid #0d47a1;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-item strong {
        color: #0d47a1;
    }

    .horario-badge {
        display: inline-block;
        padding: 0.35rem 1rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.85rem;
        background: #0d47a1;
        color: white;
    }

    /* Leyenda */
    .leyenda-section {
        margin-bottom: 1.5rem;
    }

    .leyenda-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #0d47a1;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .leyenda-list {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .leyenda-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: #f8f9fa;
        border-radius: 6px;
        transition: all 0.2s;
    }

    .leyenda-item:hover {
        background: #e9ecef;
        transform: translateX(3px);
    }

    .color-box {
        width: 16px;
        height: 16px;
        border-radius: 3px;
        flex-shrink: 0;
    }

    .leyenda-content {
        font-size: 0.85rem;
    }

    .leyenda-content strong {
        display: block;
        color: #2c3e50;
    }

    .leyenda-content small {
        color: #6c757d;
    }

    /* Distribución semanal */
    .distribucion-section {
        margin-bottom: 1rem;
    }

    .distribucion-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #0d47a1;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .dias-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1rem;
    }

    .dia-box {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1rem;
        border: 1px solid #e9ecef;
    }

    .dia-header {
        font-size: 0.95rem;
        font-weight: 700;
        color: #0d47a1;
        padding-bottom: 0.5rem;
        margin-bottom: 0.75rem;
        border-bottom: 2px solid #0d47a1;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .turno-item {
        background: white;
        border-left: 4px solid #0d47a1;
        padding: 0.75rem;
        margin-bottom: 0.5rem;
        border-radius: 6px;
        transition: all 0.2s;
    }

    .turno-item:last-child {
        margin-bottom: 0;
    }

    .turno-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .turno-nombre {
        font-weight: 700;
        color: #2c3e50;
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .turno-horario {
        font-size: 0.85rem;
        color: #6c757d;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Estado vacío */
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: #6c757d;
    }

    .empty-state i {
        font-size: 4rem;
        opacity: 0.3;
        margin-bottom: 1rem;
    }

    .empty-state h6 {
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        font-size: 0.9rem;
        margin: 0;
    }

    @media (max-width: 768px) {
        .header-section {
            flex-direction: column;
            align-items: stretch;
        }

        .select-wrapper select {
            width: 100%;
        }

        .dias-grid {
            grid-template-columns: 1fr;
        }

        .leyenda-list {
            flex-direction: column;
        }
    }
</style>

<div class="horarios-container">
    <!-- Header -->
    <div class="header-section">
        <div class="header-left">
            <h5><i class="bx bx-time"></i> Horarios Disponibles</h5>
            <div class="select-wrapper">
                <select id="ddl_tipo_horario" class="form-select form-select-sm" disabled>
                    <option value="">-- Seleccione Horario --</option>
                </select>
            </div>
        </div>
        <div class="header-right">
            <h5><i class="bx bx-calendar"></i> Horarios del Personal</h5>
        </div>
    </div>

    <!-- Estado vacío -->
    <div id="estado_vacio" class="empty-state">
        <i class="bx bx-calendar-x"></i>
        <h6>No hay horarios seleccionados</h6>
        <p>Selecciona un departamento para ver los horarios</p>
    </div>

    <!-- Contenido principal -->
    <div id="pnl_horarios_persona" style="display:none;">
        
        <!-- Resumen -->
        <div class="resumen-bar">
            <div class="resumen-item">
                <div class="numero" id="total_turnos">0</div>
                <div class="label">Turnos</div>
            </div>
            <div class="resumen-item">
                <div class="numero" id="total_dias">0</div>
                <div class="label">Días</div>
            </div>
            <div class="resumen-item">
                <div class="numero" id="total_horas">0</div>
                <div class="label">Horas/Sem</div>
            </div>
        </div>

        <!-- Info del horario -->
        <div class="info-horario">
            <div class="info-row">
                <div class="info-item">
                    <strong>Horario:</strong>
                    <span id="info_horario_nombre" class="horario-badge">No seleccionado</span>
                </div>
                <div class="info-item">
                    <i class="bx bx-tag"></i>
                    <span>Tipo:</span>
                    <strong id="info_horario_tipo">-</strong>
                </div>
            </div>
        </div>

        <!-- Leyenda -->
        <div class="leyenda-section">
            <div class="leyenda-title">
                <i class="bx bx-palette"></i> Leyenda de Turnos
            </div>
            <div id="leyenda_turnos" class="leyenda-list">
                <!-- Leyenda dinámica -->
            </div>
        </div>

        <!-- Distribución semanal -->
        <div class="distribucion-section">
            <div class="distribucion-title">
                <i class="bx bx-calendar-week"></i> Distribución Semanal
            </div>
            <div id="lista_turnos_por_dia" class="dias-grid">
                <!-- Días dinámicos -->
            </div>
        </div>

    </div>
</div>

<input id="id_perdep" type="hidden" value="" />

<script src="../assets/plugins/notifications/js/lobibox.min.js"></script>

<script>
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
                if (!response || response.length === 0) {
                    Swal.fire('', 'No hay turnos configurados para este horario', 'info');
                    $('#pnl_horarios_persona').hide();
                    $('#estado_vacio').show();
                    return;
                }

                $('#estado_vacio').hide();
                $('#pnl_horarios_persona').show();

                const turnosPorDia = {};
                const turnosUnicos = {};
                let totalHoras = 0;

                response.forEach(function(turno) {
                    const dia = turno.dia;
                    if (!turnosPorDia[dia]) {
                        turnosPorDia[dia] = [];
                    }
                    turnosPorDia[dia].push(turno);

                    if (!turnosUnicos[turno.id_turno]) {
                        turnosUnicos[turno.id_turno] = {
                            nombre: turno.nombre,
                            color: turno.color || '#0d47a1',
                            hora_entrada: turno.hora_entrada,
                            hora_salida: turno.hora_salida
                        };
                    }

                    let horas = (turno.hora_salida - turno.hora_entrada) / 60;
                    if (horas < 0) horas += 24;
                    totalHoras += horas;
                });

                $('#total_turnos').text(Object.keys(turnosUnicos).length);
                $('#total_dias').text(Object.keys(turnosPorDia).length);
                $('#total_horas').text(totalHoras.toFixed(1));

                $('#info_horario_nombre').text($('#ddl_tipo_horario option:selected').text());

                const textoHorario = $('#ddl_tipo_horario option:selected').text();
                let tipoHorario = 'Personal';
                if (textoHorario.includes('Departamento')) {
                    tipoHorario = 'Departamento';
                }
                $('#info_horario_tipo').text(tipoHorario);

                // Renderizar leyenda
                let htmlLeyenda = '';
                Object.values(turnosUnicos).forEach(function(turno) {
                    const horaInicio = minutos_formato_hora(turno.hora_entrada);
                    const horaFin = minutos_formato_hora(turno.hora_salida);

                    htmlLeyenda += `
                        <div class="leyenda-item">
                            <span class="color-box" style="background-color: ${turno.color};"></span>
                            <div class="leyenda-content">
                                <strong>${turno.nombre}</strong>
                                <small>${horaInicio} - ${horaFin}</small>
                            </div>
                        </div>
                    `;
                });
                $('#leyenda_turnos').html(htmlLeyenda);

                // Renderizar días
                let htmlDias = '';
                const diasOrdenados = Object.keys(turnosPorDia).sort();

                diasOrdenados.forEach(function(dia) {
                    const turnos = turnosPorDia[dia];

                    htmlDias += `
                        <div class="dia-box">
                            <div class="dia-header">
                                <i class="bx bx-calendar"></i> ${DIAS_SEMANA[dia]}
                            </div>
                    `;

                    turnos.forEach(function(turno) {
                        const horaInicio = minutos_formato_hora(turno.hora_entrada);
                        const horaFin = minutos_formato_hora(turno.hora_salida);

                        htmlDias += `
                            <div class="turno-item" style="border-left-color: ${turno.color || '#0d47a1'};">
                                <div class="turno-nombre">
                                    <span class="color-box" style="background-color: ${turno.color || '#0d47a1'};"></span>
                                    ${turno.nombre}
                                </div>
                                <div class="turno-horario">
                                    <i class="bx bx-time"></i>
                                    <span>${horaInicio} - ${horaFin}</span>
                                </div>
                            </div>
                        `;
                    });

                    htmlDias += '</div>';
                });

                $('#lista_turnos_por_dia').html(htmlDias);

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