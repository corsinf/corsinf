<script type="text/javascript">
    var tablaAll;
    var tablaMedi;
    var tablaInsu;
    $(document).ready(function() {
        consultar_datos_comunidad_tabla();
        lista_medicamentos();
        tablaAll = $('#tabla_todos').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            responsive: true,
            ajax: {
                url: '../controlador/ingreso_stockC.php?lista_kardex=true',
                dataSrc: ''
            },
            columns: [{
                    data: null,
                    searchable: false
                }, // #
                {
                    data: null,
                    searchable: true
                }, // Fecha 
                {
                    data: null,
                    searchable: true
                }, // Producto
                {
                    data: null,
                    searchable: true
                }, // Tipo
                // ... el resto de columnas
            ],
            columns: [{
                    data: null,
                    render: function(data, type, item, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        return formatoDate(item.Fecha.date);
                    }
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        return '<a href="../vista/inicio.php?mod=7&acc=registrar_insumos&id=' + item.id_ar + '"><u>' + item.Productos + '</u></a>';
                    }
                },
                {
                    data: 'Tipo'
                },
                {
                    data: 'Entrada'
                },
                {
                    data: 'Salida'
                },
                {
                    data: 'Precio'
                },
                {
                    data: 'Stock'
                },
                {
                    data: 'Serie'
                },
                {
                    data: 'Factura'
                },
            ],
            dom: '<"top"Bfr>t<"bottom"lip>',
            buttons: [
                'excel', 'pdf' // Configura los botones que deseas
            ],
            // initComplete: function() {
            //     // Mover los botones al contenedor personalizado
            //     $('#pnl_exportar').append($('.dt-buttons'));
            //     $('#tabla_todos_filter input').unbind().bind('input', function() {
            //         buscarExacto($(this).val());
            //     });
            // }
        });


        function buscarExacto(valorABuscar) {
            tablaAll.search("^" + $.fn.dataTable.util.escapeRegex(valorABuscar) + "$|\\b" + $.fn.dataTable.util.escapeRegex(valorABuscar) + "\\b", true, false).draw();

        }


        tablaInsu = $('#tabla_insumos').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            responsive: true,
            ajax: {
                url: '../controlador/insumosC.php?listar_todo=true',
                dataSrc: ''
            },
            columns: [{
                    data: null,
                    render: function(data, type, item, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        return '<a href="../vista/inicio.php?mod=7&acc=registrar_insumos&id=' + item.sa_cins_id + '"><u>' + item.sa_cins_presentacion + '</u></a>';
                    }
                },
                {
                    data: 'sa_cins_lote'
                },
                {
                    data: 'sa_cins_minimos'
                },
                {
                    data: 'sa_cins_stock'
                },

            ]
        });


        tablaMedi = $('#tabla_medicamentos').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            responsive: true,
            ajax: {
                url: '../controlador/medicamentosC.php?listar_todo=true',
                dataSrc: ''
            },
            columns: [{
                    data: null,
                    render: function(data, type, item, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        return '<a href="../vista/inicio.php?mod=7&acc=registrar_medicamentos&id=' + item.sa_cmed_id + '"><u>' + item.sa_cmed_presentacion + '</u></a>';
                    }
                },
                {
                    data: 'sa_cmed_concentracion'
                },
                {
                    data: 'sa_cmed_serie'
                },
                {
                    data: 'sa_cmed_minimos'
                },
                {
                    data: 'sa_cmed_stock'
                }
            ]
        });


        $('#ddl_lista_productos').on('select2:select', function(e) {
            var data = e.params.data.data;
            if ($('input[name=rbl_farmaco]:checked').val() == 'Insumos') {
                $('#txt_stock').val(data['sa_cins_stock']);
            } else {
                $('#txt_stock').val(data['sa_cmed_stock']);
            }
        })

    });


    function lista_medicamentos() {
        $('#ddl_lista_productos').empty();
        var tipo = $('input[name=rbl_farmaco]:checked').val();
        $('#ddl_lista_productos').select2({
            placeholder: 'Seleccione producto',
            width: '87%',
            ajax: {
                url: '../controlador/salida_stockC.php?lista_articulos=true&tipo=' + tipo,
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    // console.log(data);
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
    }

    function agregar_tabla() {
        var farmaco = $('#ddl_lista_productos option:selected').text();
        var farmaco_id = $('#ddl_lista_productos').val();
        var tipo = $('#sa_pac_tabla option:selected').text();
        var paciente = $('#sa_pac_id_comunidad option:selected').text();
        var tipo_farmaco = $('input[name=rbl_farmaco]:checked').val();
        var cant = $('#txt_cantidad').val();
        var stock = $('#txt_stock').val();

        if ($('#ddl_lista_productos').val() == '' || $('#sa_pac_tabla').val() == '' || $('#sa_pac_id_comunidad').val() == '' || cant == '' || cant == 0) {
            Swal.fire("Llene todo los campos", "", "info");
            return false;
        }

        if (parseFloat(cant) > parseFloat(stock)) {
            Swal.fire("Cantidad ingresada supera al stock", "", "error");
            return false;
        }
        var existe = buscar_medicamento_existente(farmaco.trim())
        if (existe) {
            Swal.fire('Este farmaco ya esta registrado', '', 'error');
            return false;
        }

        var rowCount = $('#lista_medicamentos tbody').find('tr').length;
        var fila = rowCount;
        var tr = '<tr id="ln_' + rowCount + '"><td><button class="btn btn-danger btn-sm" onclick="remover_fila(' + fila + ')"><i class="bx bx-trash me-0"></i></button></td><td style="display:none">' + farmaco_id + '</td><td>' + tipo + '</td><td>' + paciente + '</td><td>' + tipo_farmaco + '</td><td>' + farmaco + '</td><td>' + cant + '</td></tr>';
        $("#tbl_body").append(tr);
    }

    function buscar_medicamento_existente(texto) {
        var searchText = texto.toLowerCase();
        var encontrado = false;
        $('#lista_medicamentos tbody tr').each(function() {
            $(this).find('td').each(function() {
                var cellText = $(this).text().toLowerCase();
                if (cellText.indexOf(searchText) !== -1) {
                    encontrado = true;
                    return false; // Sale del bucle each interno
                }
            });

            if (encontrado) {
                return false; // Sale del bucle each externo
            }
        });

        return encontrado;
    }



    function guardar_producto() {
        var datos = $("#form_nuevo_producto").serialize();

        var ddl = $('#ddl_tipo option:selected').text();
        var datos = datos + '&ddl_tipo=' + ddl;

        if ($('#ddl_proveedor').val() == '' || $('#ddl_lista_productos').val() == '' || $('#txt_precio').val() == '0' || $('#txt_precio').val() == '' || $('#txt_serie').val() == '' || $('#txt_factura').val() == '' || $('#txt_fecha_ela').val() == '' || $('#txt_fecha_exp').val() == '') {
            Swal.fire('', 'Llene todo los campos.', 'info');
            return false;
        }

        if ($('#txt_canti').val() < 1) {
            Swal.fire('', 'Cantidad no valida.', 'info');
            return false;
        }

        $.ajax({
            data: datos,
            url: '../controlador/ingreso_stockC.php?producto_nuevo=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                // console.log(response);

                $('#Nuevo_producto').modal('hide');
                if (response == 1) {

                    Swal.fire('Ingresado a stock.', '', 'success').then(function() {
                        if (tablaAll) {
                            tablaAll.ajax.reload();
                            tablaInsu.ajax.reload();
                            tablaMedi.ajax.reload();
                        }
                    });
                    limpiar_nuevo_producto();
                }
            }
        });
        // console.log(datos);
    }

    function limpiar_nuevo_producto() {
        $('#ddl_lista_productos').empty();
    }

    function consultar_datos_comunidad_tabla() {
        var salida = '<option value="">Seleccione el Tipo de Paciente</option>';

        $.ajax({
            url: '../controlador/Comunidad_TablasC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                $.each(response, function(i, item) {
                    // Concatenar dos variables en el valor del atributo "value"
                    salida += '<option value="' + item.sa_tbl_pac_nombre + '-' + item.sa_tbl_pac_prefijo + '">' + item.sa_tbl_pac_nombre.toUpperCase() + '</option>';
                });

                $('#sa_pac_tabla').html(salida);
            }
        });
    }

    function consultar_tablas_datos(valor_seleccionar) {

        var valor_seleccionar = valor_seleccionar.split('-');
        var sa_tbl_pac_tabla = valor_seleccionar[0];
        var sa_tbl_pac_prefijo = valor_seleccionar[1];

        // alert(sa_tbl_pac_prefijo);

        $('#sa_pac_id_comunidad').select2({
            placeholder: 'Seleccione una tipo de usuario',
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
            ajax: {
                url: '../controlador/' + sa_tbl_pac_tabla + 'C.php?listar_todo=true',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        searchTerm: params.term // Envía el término de búsqueda al servidor
                    };
                },
                processResults: function(data, params) {

                    var searchTerm = params.term.toLowerCase();
                    var options = data.reduce(function(filtered, item) {

                        var fullName = item['' + sa_tbl_pac_prefijo + '_cedula'] + " - " + item['' + sa_tbl_pac_prefijo + '_primer_apellido'] + " " + item['' + sa_tbl_pac_prefijo + '_segundo_apellido'] + " " + item['' + sa_tbl_pac_prefijo + '_primer_nombre'] + " " + item['' + sa_tbl_pac_prefijo + '_segundo_nombre'];

                        if (fullName.toLowerCase().includes(searchTerm)) {
                            filtered.push({
                                id: item['' + sa_tbl_pac_prefijo + '_id'],
                                text: fullName
                            });
                        }

                        return filtered;
                    }, []);

                    return {
                        results: options
                    };
                },
                cache: true
            }
        });
    }


    function remover_fila(id) {
        $('#lista_medicamentos tbody tr#ln_' + id).remove();
    }

    function ingresar_salida() {
        $('#lista_medicamentos tbody tr').each(function() {
            var id = $(this).find('td:eq(1)').text();
            var orden = $(this).find('td:eq(3)').text();
            var tipo = $(this).find('td:eq(4)').text();
            var cant = $(this).find('td:eq(6)').text();
            parametros = {
                'ddl_lista_productos': id,
                'orden': orden,
                'ddl_tipo': tipo,
                'txt_canti': cant,
                'txt_subtotal': 0,
                'txt_total': 0,
            }
            salida_registro(parametros);
        });
    }

    function salida_registro(parametros) {
        $.ajax({
            data: parametros,
            url: '../controlador/ingreso_stockC.php?producto_nuevo_salida=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                // console.log(response);

                $('#Nuevo_producto').modal('hide');
                if (response == 1) {

                    Swal.fire('Salida de stock registrada.', '', 'success').then(function() {
                        if (tablaAll) {
                            // tablaAll.ajax.reload();
                            location.reload();
                        }
                    });
                    limpiar_nuevo_producto();
                }
            }
        });
    }
</script>

<div class="page-wrapper">
    <div class="page-content">

        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Enfermería </div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Salida de Medicamentos</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body p-3">
                        <div class="content">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <ul class="nav nav-tabs nav-success" role="tablist" id="myTabs">
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link active" data-bs-toggle="tab" href="#all" role="tab" aria-selected="true">
                                                    <div class="d-flex align-items-center">
                                                        <div class="tab-icon">
                                                            <i class='bx bxs-capsule font-18 me-1'></i>
                                                        </div>
                                                        <div class="tab-title">Salidas</div>
                                                    </div>
                                                </a>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link" data-bs-toggle="tab" href="#kardex" role="tab" aria-selected="true">
                                                    <div class="d-flex align-items-center">
                                                        <div class="tab-icon">
                                                            <i class='bx bxs-capsule font-18 me-1'></i>
                                                        </div>
                                                        <div class="tab-title">Kardex</div>
                                                    </div>
                                                </a>
                                            </li>
                                        </ul>
                                        <div class="tab-content py-3">
                                            <div class="tab-pane fade show active" id="all" role="tabpanel">

                                                <div class="row">

                                                    <div class="col-sm-5">

                                                        <div class="col-12 mb-3">
                                                            <label for="sa_pac_tabla" class="fw-bold">Tipo de Paciente <label class="text-danger">*</label></label>
                                                            <select name="sa_pac_tabla" id="sa_pac_tabla" class="form-select form-select-sm" onchange="consultar_tablas_datos(this.value)">
                                                                <option value="">Seleccione el Tipo de Paciente</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-12 mb-2">
                                                            <label for="sa_pac_id_comunidad" class="fw-bold">Paciente <label class="text-danger">*</label></label>
                                                            <select name="sa_pac_id_comunidad" id="sa_pac_id_comunidad" class="form-select form-select-sm">
                                                                <option value="">Seleccione el Paciente</option>
                                                            </select>
                                                        </div>

                                                    </div>

                                                    <div class="col-sm-7">

                                                        <div class="card border-top border-0 border-4">
                                                            <div class="card-body">
                                                                <label for="sa_pac_id_comunidad" class="fw-bold">Farmacos <label class="text-danger">*</label></label>

                                                                <div class="row">
                                                                    <div class="col-sm-6">
                                                                        <label><input type="radio" value="Medicamento" checked name="rbl_farmaco" onclick="lista_medicamentos()"> Medicamento</label>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <label><input type="radio" value="Insumos" name="rbl_farmaco" onclick="lista_medicamentos()"> Insumo</label>
                                                                    </div>
                                                                </div>

                                                                <div class="row pt-2">
                                                                    <div class="col-sm-12">
                                                                        <select class="form-select form-select-sm" id="ddl_lista_productos" name="ddl_lista_productos">
                                                                            <option value="">-- Seleccione farmaco --</option>
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="row pt-3">
                                                                    <div class="col-sm-2">
                                                                    </div>
                                                                    <div class="col-sm-3">
                                                                        <label for="" class="fw-bold">Stock <label style="color: red;"></label> </label>
                                                                        <input type="number" readonly min="1" class="form-control form-control-sm" value="0" id="txt_stock" name="txt_stock">
                                                                    </div>

                                                                    <div class="col-sm-3">
                                                                        <label for="" class="fw-bold">Cantidad <label style="color: red;">*</label> </label>
                                                                        <input type="number" min="1" id="txt_cantidad" name="txt_cantidad" class="form-control form-control-sm" value="1">
                                                                    </div>

                                                                    <div class="col-sm-2 mt-2 text-end">
                                                                        <button type="button" class="btn btn-primary btn-sm" onclick="agregar_tabla()"><i class='bx bx-plus me-0'></i> Ingresar</button>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>



                                                    </div>
                                                </div>

                                            </div>


                                            <div class="row mt-2 pt-3">
                                                <div class="col-sm-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-hover" id="lista_medicamentos">
                                                            <thead>
                                                                <th width="10%"></th>
                                                                <th width="20%">Tipo</th>
                                                                <th width="25%">Entregado a</th>
                                                                <th width="25%">Tipo farmaco</th>
                                                                <th width="15%">Farmacología</th>
                                                                <th width="8%">Cantidad</th>
                                                            </thead>
                                                            <tbody id="tbl_body">
                                                            </tbody>

                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 text-end mt-1">
                                                    <button type="button" class="btn btn-primary btn-sm" onclick="ingresar_salida()">Generar salida</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="kardex" role="tabpanel">
                                            <div class="row">
                                                <div class="col-sm-8" id="btn_nuevo">

                                                </div>
                                                <div class="col-sm-4 text-end" id="pnl_exportar">

                                                </div>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-striped responsive " id="tabla_todos" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Fecha Ingreso</th>
                                                            <th>Productos</th>
                                                            <th>Tipo</th>
                                                            <th>Entrada</th>
                                                            <th>Salida</th>
                                                            <th>Precio</th>
                                                            <th>Stock</th>
                                                            <th>Serie</th>
                                                            <th>Factura</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <br>
                        </div><!-- /.container-fluid -->
                        <!-- /.content -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>