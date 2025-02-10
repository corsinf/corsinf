<script type="text/javascript">
    var tablaAll;
    var tablaMedi;
    var tablaInsu;
    $(document).ready(function() {
        lista_medicamentos();
        tablaAll = $('#tabla_todos').DataTable({
            scrollX: true,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            responsive: true,
            ajax: {
                url: '../controlador/SALUD_INTEGRAL/salida_stockC.php?lista_kardex_all=true',
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
                        return (item.Fecha);
                    }
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        if (item.Tipo == 'Insumos') {
                            return '<a href="../vista/inicio.php?mod=7&acc=registrar_insumos&id=' + item.id_ar + '" target="_blank"><u>' + item.Productos + '</u></a>';
                        } else {
                            return '<a href="../vista/inicio.php?mod=7&acc=registrar_medicamentos&id=' + item.id_ar + '" target="_blank"><u>' + item.Productos + '</u></a>';
                        }
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
                    data: 'Stock'
                },
                {
                    data: 'Orden'
                },
            ],
            dom: '<"top"Bfr>t<"bottom"lip>',
            buttons: [
                'excel', 'pdf' // Configura los botones que deseas
            ],
            initComplete: function() {
                // Mover los botones al contenedor personalizado
                // $('#pnl_exportar').append($('.dt-buttons'));
                // $('#tabla_todos_filter input').unbind().bind('input', function() {
                //     buscarExacto($(this).val());
                // });
                $('#tabla_todos_filter').append('<label><select class="form-select form-select-sm" style="width:100px" onchange="recargar(this.value)"><option value="">Todos</option><option value="s">Salida</option><option value="e">Entada</option></select></label>');
            }
        });

        // $('#tabla_todos_filter').append('<select id="filtroSelect"><option value="opcion1">Opción 1</option><option value="opcion2">Opción 2</option></select>');


        function buscarExacto(valorABuscar) {
            tablaAll.search("^" + $.fn.dataTable.util.escapeRegex(valorABuscar) + "$|\\b" + $.fn.dataTable.util.escapeRegex(valorABuscar) + "\\b", true, false).draw();

        }

        $('#ddl_lista_productos').on('select2:select', function(e) {
            var data = e.params.data.data;
            if ($('input[name=rbl_farmaco]:checked').val() == 'Insumos') {
                $('#txt_stock').val(data['sa_cins_stock']);
            } else {
                $('#txt_stock').val(data['sa_cmed_stock']);
            }
        })

    });

    function recargar(valor) {
        tablaAll.clear().draw();
        switch (valor) {
            case 's':
                url = '../controlador/SALUD_INTEGRAL/salida_stockC.php?lista_kardex=true';
                break;
            case 'e':
                url = '../controlador/SALUD_INTEGRAL/salida_stockC.php?lista_kardex_entrada=true';
                break;
            default:
                url = '../controlador/SALUD_INTEGRAL/salida_stockC.php?lista_kardex_all=true';
                break;
        }
        // Llenar la tabla con nuevos datos
        tablaAll.ajax.url(url).load();
    }


    function lista_medicamentos() {
        $('#ddl_lista_productos').empty();
        var tipo = $('input[name=rbl_farmaco]:checked').val();
        $('#ddl_lista_productos').select2({
            placeholder: 'Seleccione producto',
            width: '87%',
            ajax: {
                url: '../controlador/SALUD_INTEGRAL/salida_stockC.php?lista_articulos=true&tipo=' + tipo,
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
                        <li class="breadcrumb-item active" aria-current="page">Stock de Medicamentos</li>
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
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-8" id="btn_nuevo">

                                    </div>
                                    <div class="col-sm-4 text-end" id="pnl_exportar">

                                    </div>
                                </div>
                                <table class="table table-striped responsive " id="tabla_todos" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Fecha Ingreso</th>
                                            <th>Productos</th>
                                            <th>Tipo</th>
                                            <th>Entrada</th>
                                            <th>Salida</th>
                                            <th>Stock</th>
                                            <th>Orden</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
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