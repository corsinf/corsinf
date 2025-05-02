<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);

$_id = '';

if (isset($_GET['_id'])) {
    $_id = $_GET['_id'];
}

$color_lista_destino = '#d4edda';

?>

<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>
<style>
    .list-group-item {
        cursor: grab;
    }

    .sortable-placeholder {
        border: 2px dashed #007bff;
        background: #f8f9fa;
        height: 40px;
        margin-bottom: 5px;
    }

    .lista-container {
        min-height: 200px;
        border: 1px solid #ccc;
        padding: 10px;
        border-radius: 5px;
    }

    .scroll-y {
        max-height: 300px;
        overflow-y: auto;
        border: 1px solid #ddd;
    }
</style>

<script type="text/javascript">
    $(document).ready(function() {
        <?php if (isset($_GET['_id'])) { ?>
            cargar_cat_reporte_atributos('<?= $_id ?>');
            cargar_reporte_atributos('<?= $_id ?>');
        <?php } ?>
    });

    function cargar_cat_reporte_atributos(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/th_cat_reporte_atributoC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                // console.log(response);

                let items = response.map(item =>
                    `<li class="list-group-item" data-id-cat-campo="${item._id}" data-id-destino="">${item.nombre_encabezado || "Sin nombre"}</li>`
                ).join("");

                $("#pnl_lista_origen").html(items);

                // Habilitar la funcionalidad de arrastrar y soltar
                $("#pnl_lista_origen").sortable({
                    update: function(event, ui) {
                        lista_origen_valores(); // Actualizar los valores al mover elementos
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error("Error al cargar los datos:", error);
            }
        });
    }

    function cargar_reporte_atributos(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/th_reporte_camposC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                // console.log(response);

                // Ordenar los datos según item.orden (si no están ordenados ya)
                response.sort((a, b) => a.orden - b.orden);

                let items = response.map(item =>
                    `<li class="list-group-item" data-id-cat-campo="${item.id_catalogo_reporte}" data-id-destino="${item._id}" style="background-color: <?= $color_lista_destino ?>;">
                        ${item.nombre_encabezado || "Sin nombre"}
                    </li>`
                ).join("");

                $("#pnl_lista_destino").html(items);

                // Habilitar la funcionalidad de arrastrar y soltar
                $("#pnl_lista_destino").sortable({
                    update: function(event, ui) {
                        lista_destino_valores();
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error("Error al cargar los datos:", error);
            }
        });
    }

    function editar_insertar() {
        let parametros = {
            'lista_destino_valores': lista_destino_valores(),
            'lista_origen_valores': lista_origen_valores(),
        };

        //  console.log(parametros);

        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/TALENTO_HUMANO/th_reporte_camposC.php?insertar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success');
                } else if (response == -2) {
                    Swal.fire('', 'Error algo salió mal.', 'warning');
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

    /**
     * 
     * Para generar las listas para eliminar insertar o modificar el orden
     * 
     */

    function lista_destino_valores() {
        let parametros = [];

        $("#pnl_lista_destino li").each(function(index) {
            let txt_id_rt = $(this).data("id-destino");
            let txt_id_ct = $(this).data("id-cat-campo");

            parametros.push({
                txt_id_rt: txt_id_rt,
                txt_id_ct: txt_id_ct,
                txt_orden: index + 1,
                txt_id_rep: '<?= $_id ?>'
            }); // Suma 1 para que no empiece en 0
        });

        // console.log(parametros);

        $("#pnl_lista_destino li").each(function() {
            $(this).css("background-color", "<?= $color_lista_destino ?>");
        });


        return parametros;
    }

    function lista_origen_valores() {
        let parametros = [];

        // Recorrer todos los li dentro de la lista de origen
        $("#pnl_lista_origen li").each(function(index) {
            let txt_id_rt = $(this).data("id-destino"); // Si tienes el data-id-destino, puedes usarlo
            let txt_id_ct = $(this).data("id-cat-campo");

            // Verificar que txt_id_rt no esté vacío
            if (txt_id_rt) {
                parametros.push({
                    txt_id_rt: txt_id_rt,
                    txt_id_ct: txt_id_ct,
                    txt_orden: index + 1, // Sumar 1 para que no empiece en 0
                    txt_id_rep: '<?= $_id ?>' // Agregar el id de reporte
                });
            }
        });

        $("#pnl_lista_origen li").each(function() {
            $(this).css("background-color", "");
        });

        // console.log(parametros);
        return parametros;
    }

    //Para eliminar los eventos dando click
    // $(document).on("click", "#pnl_lista_destino .list-group-item", function() {
    //     console.log('response');
    //     let id = $(this).data("id");

    //     if (!id) {
    //         console.error("Error: ID no encontrado.");
    //         return;
    //     }

    //     if (confirm("¿Seguro que quieres eliminar este elemento?")) {
    //         $.ajax({
    //             url: '../controlador/TALENTO_HUMANO/th_reporte_camposC.php?eliminar=true',
    //             type: 'POST',
    //             data: {
    //                 id: id
    //             },
    //             success: function(response) {
    //                 console.log("Eliminado con éxito:", response);
    //                 $(`li[data-id="${id}"]`).remove();
    //             },
    //             error: function(xhr, status, error) {
    //                 console.error("Error al eliminar:", error);
    //             }
    //         });
    //     }
    // });
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Reporte - Encabezado</div>
            <?php
            //print_r($_SESSION['INICIO']);die(); 

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Agregar Reporte - Encabezado
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

                            <div><i class="bx bxs-user me-1 font-22 text-primary"></i>
                            </div>
                            <h5 class="mb-0 text-primary">
                                <?php
                                if ($_id == '') {
                                    echo 'Registrar Reporte - Encabezado';
                                } else {
                                    echo 'Modificar Reporte - Encabezado';
                                }
                                ?>
                            </h5>

                            <div class="row m-2">
                                <div class="col-sm-12">
                                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_reportes" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <form id="form_reporte_atributo">

                            <div class="row pt-3 mb-col">
                                <!-- Lista de origen -->
                                <div class="col-md-5">
                                    <h6 class="mb-1 text-success fw-bold">Opciones - Encabezado del reporte</h6>
                                    <ul id="pnl_lista_origen" class="list-group lista-container pnl_sort_table scroll-y">
                                        <!-- <li class="list-group-item">Item 1</li>
                                        <li class="list-group-item">Item 2</li>
                                        <li class="list-group-item">Item 3</li>
                                        <li class="list-group-item">Item 4</li> -->
                                    </ul>
                                </div>

                                <!-- Espacio entre listas -->
                                <div class="col-md-2 d-flex align-items-center flex-column justify-content-center">

                                    <h5 class="text-muted">
                                        <i class='bx bx-left-arrow-alt fs-4' style="vertical-align: middle;"></i>
                                        Arrastra
                                        <i class='bx bx-right-arrow-alt fs-4' style="vertical-align: middle;"></i>
                                    </h5>

                                </div>

                                <!-- Lista de destino -->
                                <div class="col-md-5">
                                    <h6 class="mb-1 text-success fw-bold">Encabezado del reporte</h6>
                                    <ul id="pnl_lista_destino" class="list-group lista-container pnl_sort_table scroll-y">
                                        <!-- Elementos movidos aparecerán aquí -->
                                    </ul>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end pt-2">
                                <button class="btn btn-success btn-sm px-4 m-0" onclick="editar_insertar()" type="button"><i class="bx bx-save"></i> Guardar</button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>

<script>
    $(document).ready(function() {
        $(".pnl_sort_table").sortable({
            connectWith: ".pnl_sort_table", // Permite mover entre listas
            placeholder: "sortable-placeholder", // Estilo visual de arrastre
            cursor: "move", // Cursor de movimiento
            revert: 200, // Suaviza el movimiento al soltar
            update: function(event, ui) {
                let item = ui.item;
                let pnl_lista_destino = $("#pnl_lista_destino");
                let pnl_lista_origen = $("#pnl_lista_origen");

                // console.log(item);

                // Si el elemento se mueve a la lista destino, cambia su color
                if (item.parent().attr("id") === "pnl_lista_destino") {
                    item.css("background-color", "<?= $color_lista_destino ?>");
                } else {
                    item.css("background-color", "");
                }
            }
        }).disableSelection(); // Evita selección de texto mientras se arrastra
    });
</script>

<script>
    //Validacion de formulario
    $(document).ready(function() {
        // Selecciona el label existente y añade el nuevo label

        agregar_asterisco_campo_obligatorio('ddl_modelo');
        agregar_asterisco_campo_obligatorio('txt_nombre');

        $("#form_reporte_atributo").validate({
            rules: {
                txt_nombre: {
                    required: true,
                },
                txt_fecha_inicio_feriado: {
                    required: true,
                },
                txt_dias: {
                    required: true,
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