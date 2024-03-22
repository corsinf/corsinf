<?php

$id = $_SESSION['INICIO']['NO_CONCURENTE'];

if ($id != null && $id != '') {
    $id_docente = $id;
}

?>

<script src="../js/ENFERMERIA/operaciones_generales.js"></script>
<script src="../js/ENFERMERIA/pacientes.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        var id_docente = '<?php echo $id_docente; ?>';

        carga_tabla();

        //consultar_paralelos_datos();
    });

    function carga_tabla() {
        var id_docente = '<?php echo $id_docente; ?>';

        tbl_doc_par = $('#tbl_doc_par').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            responsive: true,
            ajax: {
                url: '../controlador/docente_paraleloC.php',
                data: function(d) {
                    d.listar = true;
                    d.id_docente = id_docente;
                },
                dataSrc: ''
            },
            columns: [{
                    data: null,
                    render: function(data, type, item) {
                        return item.sa_sec_nombre + ' - ' + item.sa_gra_nombre + ' - ' + item.sa_par_nombre;
                    }
                },
                {
                    data: 'sa_par_id',
                    visible: false,
                }
            ],
            initComplete: function() {
                // Obtener los IDs de los cursos ya agregados en la tabla
                var cursosAgregados = tbl_doc_par.rows().data().pluck('sa_par_id').toArray();

                // Llamar a la función para cargar el select2
                consultar_paralelos_datos(cursosAgregados);
            }
        });
    }

    function consultar_paralelos_datos(cursosAgregados) {
        $('#sa_par_id').select2({
            placeholder: 'Seleccione un Curso',
            dropdownParent: $('#modal_paralelo'),
            language: 'es',
            minimumInputLength: 3,
            ajax: {
                url: '../controlador/paraleloC.php?listar_todo=true',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        searchTerm: params.term,
                        cursosAgregados: cursosAgregados // Envía los cursos ya agregados
                    };
                },
                processResults: function(data, params) {
                    var searchTerm = params.term.toLowerCase();

                    var options = data.reduce(function(filtered, item) {
                        var fullName = item['sa_sec_nombre'] + " - " + item['sa_gra_nombre'] + " - " + item['sa_par_nombre'];

                        if (fullName.toLowerCase().includes(searchTerm) && !cursosAgregados.includes(item['sa_par_id'])) {
                            filtered.push({
                                id: item['sa_par_id'],
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

    function insertar() {
        var ac_docente_id = '<?php echo $id_docente; ?>';
        var ac_paralelo_id = $('#sa_par_id').val();

        //alert(ac_par_id + ' ' + ac_doc_id);

        var parametros = {
            'ac_docente_paralelo_id': '',
            'ac_docente_id': ac_docente_id,
            'ac_paralelo_id': ac_paralelo_id,
        }

        $.ajax({
            url: '../controlador/docente_paraleloC.php?insertar=true',
            data: {
                parametros: parametros
            },
            type: 'post',
            dataType: 'json',
            success: function(response) {
                //console.log(response)
                Swal.fire('', 'Curso Asignado.', 'success').then(function() {
                    //location.href = '../vista/inicio.php?mod=7&acc=agendamiento';
                })
            }
        });


        if (tbl_doc_par) {
            tbl_doc_par.destroy(); // Destruir la instancia existente del DataTable
        }

        $('#modal_paralelo').modal('hide');
        carga_tabla(); // Volver a cargar la tabla
    }
</script>

<form id="form_enviar" action="../vista/inicio.php?mod=7&acc=ficha_medica_pacientes" method="post" style="display: none;">
    <input type="hidden" id="sa_pac_id" name="sa_pac_id" value="">
</form>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Accesos</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Áreas de instrucción que dirige.
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

                            <div class="row mx-0">
                                <div class="col-sm-12" id="btn_nuevo">

                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modal_paralelo"><i class="bx bx-plus"></i> Nuevo Curso</button>

                                </div>
                            </div>
                        </div>


                        <section class="content pt-2">
                            <div class="container-fluid">
                                <div class="table-responsive">
                                    <table class="table table-striped responsive " id="tbl_doc_par" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Sección - Grado - Curso</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div><!-- /.container-fluid -->
                        </section>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>


<div class="modal" id="modal_paralelo" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
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
                        <label for="sa_par_id">Curso <label class="text-danger">*</label></label>
                        <select name="sa_par_id" id="sa_par_id" class="form-select">
                            <option value="">Seleccione un Curso</option>
                        </select>
                    </div>
                </div>

                <div class="row pt-3">
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-success btn-sm" onclick="insertar()"><i class="bx bx-save"></i> Agregar</button>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>