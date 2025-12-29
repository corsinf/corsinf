<script>
    $(document).ready(function() {
        dispositivos();
    });

    function cargar_departamento(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/th_personasC.php?listar_persona_departamento=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response && response.length > 0) {
                    // Cargar el _id_perdep en el campo oculto para edición
                    $('#id_perdep').val(response[0]._id_perdep);

                    // Cargar el departamento seleccionado
                    $('#ddl_departamentos').append($('<option>', {
                        value: response[0].id_departamento,
                        text: response[0].nombre_departamento,
                        selected: true
                    }));

                    if (response[0].id_departamento == 0) {
                        // cargar_persona_horario(response[0].id_persona);
                        $('#pnl_horarios_persona').hide();
                    } else {
                        // cargar_persona_horario(response[0].id_persona);
                        $('#pnl_horarios_persona').show();
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error('Error al cargar departamento:', error);
            }
        });
    }

    function insertar_persona_departamento() {
        const deptId = $('#ddl_departamentos').val();
        const perdepId = $('#id_perdep').val();

        if (!deptId) {
            Swal.fire('', 'Seleccione un departamento', 'warning');
            return;
        }

        const parametros = {
            '_id': perdepId || '',
            'id_persona': '<?= $_id ?>',
            'id_departamento': deptId,
            'txt_visitor': $('#txt_visitor').val() || ''
        };

        $.ajax({
            url: '../controlador/TALENTO_HUMANO/th_personas_departamentosC.php?insertar_editar_persona=true',
            type: 'post',
            dataType: 'json',
            data: {
                parametros: parametros
            },
            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operación realizada con éxito.', 'success').then(() => {
                        location.reload();
                    });
                } else if (response == -2) {
                    Swal.fire('', 'Esta persona ya está asignada a este departamento', 'warning');
                } else {
                    Swal.fire('', 'Error en la operación', 'error');
                }
            }
        });
    }
</script>

<div class="row">
    <div class="col-md-6">
        <input id="id_perdep" type="hidden" value="" />
        <label for="ddl_departamentos" class="form-label fw-bold">
            <i class="bx bxs-building"></i> Departamento
        </label>

        <select id="ddl_departamentos" class="form-select form-select-sm">
            <option value="">-- Seleccione Departamento --</option>
        </select>

        <button
            class="btn btn-primary btn-sm px-4 mt-2 d-flex align-items-center"
            onclick="insertar_persona_departamento();"
            type="button">
            <i class="bx bx-save me-1"></i> Guardar
        </button>
        
    </div>
</div>