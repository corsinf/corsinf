<script type="text/javascript">
    $(document).ready(function() {
        cargar_orden();
        autocoplet_material();
        autocoplet_bodegas();
        var id = '<?php if (isset($_GET['id'])) {
                        echo $_GET['id'];
                    } ?>';
        var estado = '<?php if (isset($_GET['estado'])) {
                            echo $_GET['estado'];
                        } ?>';
        // if(estado!='P'){read();}else{write();}
        // if(id!=''){ $('#btn_edi_enca').css('display','initial');$('#txt_encargado').prop('readonly',true);}
        // else{$('#btn_edi_enc').css('display','none');$('#txt_encargado').prop('readonly',false);}
        // Lista_clientes();
        // Lista_procesos();
        // ------------------------------------------------------
        $("#txt_producto").autocomplete({
            source: function(request, response) {

                $.ajax({
                    url: "../controlador/punto_ventaC.php?search_ord",
                    type: 'post',
                    dataType: "json",
                    data: {
                        search: request.term
                    },
                    success: function(data) {
                        response(data);
                    }
                });
            },
            select: function(event, ui) {
                $('#txt_producto').val(ui.item.nombre); // display the selected text
                $('#txt_referencia').val(ui.item.value); // save selected id to input
                $('#txt_bodega').val(ui.item.bodega); // save selected id to input
                $('#txt_precio').val(ui.item.precio); // save selected id to input
                // $('#txt_bodega').append($('<option>',{value:ui.item.bodega, text: ui.item.bodega_nom,selected: true }));
                return false;
            },
            focus: function(event, ui) {
                $('#txt_producto').val(ui.item.nombre); // display the selected text
                $('#txt_referencia').val(ui.item.value); // save selected id to input
                $('#txt_bodega').val(ui.item.bodega); // save selected id to input
                // $('#txt_bodega').empty();
                // $('#txt_bodega').append($('<option>',{value:ui.item.bodega, text: ui.item.bodega_nom,selected: true }));

                return false;
            },
        });
        //----------------------------------------------------------------------


        // ------------------------------------------------------
        $("#txt_maestro").autocomplete({
            source: function(request, response) {

                $.ajax({
                    url: "../controlador/orden_trabajoC.php?search_maestro",
                    type: 'post',
                    dataType: "json",
                    data: {
                        search: request.term
                    },
                    success: function(data) {
                        response(data);
                    }
                });
            },
            select: function(event, ui) {
                $('#txt_maestro').val(ui.item.label); // display the selected text
                $('#txt_id_ma').val(ui.item.value); // save selected id to input
                // $('#txt_bodega').val(ui.item.bodega); // save selected id to input
                // $('#txt_precio').val(ui.item.precio); // save selected id to input
                // $('#txt_bodega').append($('<option>',{value:ui.item.bodega, text: ui.item.bodega_nom,selected: true }));
                return false;
            },
            focus: function(event, ui) {
                $('#txt_maestro').val(ui.item.label); // display the selected text
                $('#txt_id_ma').val(ui.item.value); // save selected id to input
                // $('#txt_bodega').val(ui.item.bodega); // save selected id to input
                // $('#txt_bodega').empty();
                // $('#txt_bodega').append($('<option>',{value:ui.item.bodega, text: ui.item.bodega_nom,selected: true }));

                return false;
            },
        });
        //----------------------------------------------------------------------

        $("#subir_imagen").on('click', function() {

            var id = '<?php if (isset($_GET['id'])) {
                            echo $_GET['id'];
                        } ?>';
            $('#num_ord').val(id);
            var formData = new FormData(document.getElementById('form_img'));
            // var num_file = document.getElementsByName('file_img[]').length;
            // console.log(formData);
            // console.log(id);
            // $('#files_num').val(num_file)

            // for (var i = 1; i < num_file+1; i++) {
            //   let files = $('#file_img_'+i)[0].files[0];
            //   // console.log(files);
            //   formData.append('file[]',files);
            //   formData.append('num', num_file);
            //   formData.append('num1', 'ssss');
            // }        
            $.ajax({
                url: '../controlador/orden_trabajoC.php?Articulos_imagen=true',
                type: 'post',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                // beforeSend: function () {
                //        $("#foto_alumno").attr('src',"../../img/gif/proce.gif");
                //     },
                success: function(response) {
                    if (response == -1) {
                        Swal.fire(
                            '',
                            'Algo extraño a pasado intente mas tarde.',
                            'error')

                    } else if (response == -2) {
                        Swal.fire(
                            '',
                            'Asegurese que el archivo subido sea una imagen.',
                            'error')
                    } else {
                        cargar_orden_boceto();
                        Swal.fire(
                            '',
                            'Imagenes subidas.',
                            'success')
                    }
                }
            });
        });


    });

    function autocoplet_material() {
        $('#ddl_material').select2({
            placeholder: 'Seleccione material',
            width: '310px',
            ajax: {
                url: '../controlador/materialesC.php?material=true',
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

    function cargar_orden() {
        var id = '<?php if (isset($_GET['id'])) {
                        echo $_GET['id'];
                    } ?>';
        var estado = '<?php if (isset($_GET['estado'])) {
                            echo $_GET['estado'];
                        } ?>';
        var parametros = {
            'idorden': id,
            'estado': estado,
        }
        // console.log(parametros);
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/orden_trabajoC.php?lineas=true',
            type: 'post',
            dataType: 'json',
            /*beforeSend: function () {   
                 var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
               $('#tabla_').html(spiner);
            },*/
            success: function(response) {
                if (response) {
                    console.log(response);
                    $('#tbl_orden').html(response.tbl);
                    $('#txt_num').text(response.num);
                    $('#lbl_or').text(response.num);
                    $('#txt_encargado').val(response.encargado);
                    $('#lbl_nombre').text(response.encargado);
                    $('#lbl_punto').text(response.punto);
                    $('#lbl_bodega').text(response.punto);
                    $('#lbl_bodega_recibe').text(response.bode);
                    $('#txt_fecha_ent').val(formatDate(response.fecha_ex.date));
                    $('#lbl_fecha_emi').text(formatDate(response.fecha.date));
                    $('#lbl_fecha_expi').text(formatDate(response.fecha_ex.date));
                    $('#txt_num_li').val(response.num_li);
                    $('#txt_id_ma').val(response.idma);
                    $('#txt_maestro').val(response.maestro);
                    $('#lbl_maestro').text(response.maestro);
                    $('#txt_tipo1').val('C');
                    if (response.boceto == 1) {
                        $('#txt_tipo1').val('P');
                        $('#proveedores').addClass('active');
                        $('#clientes').removeClass('active');
                        $('#btn_di').addClass('active');
                        $('#btn_pro').removeClass('active');
                        cargar_orden_boceto();
                    }
                    if (response.idbo != '') {
                        $('#ddl_bodega').append($('<option>', {
                            value: response.idbo,
                            text: response.bode,
                            selected: true
                        }));
                    }
                    if (response.estado == 'F') {
                        read();
                    } else {
                        write();
                    }
                }
            }

        });

    }

    function cargar_orden_boceto() {
        var id = '<?php if (isset($_GET['id'])) {
                        echo $_GET['id'];
                    } ?>';
        var estado = '<?php if (isset($_GET['estado'])) {
                            echo $_GET['estado'];
                        } ?>';
        var parametros = {
            'idorden': id,
            'estado': estado,
        }
        // console.log(parametros);
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/orden_trabajoC.php?boceto=true',
            type: 'post',
            dataType: 'json',
            /*beforeSend: function () {   
                 var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
               $('#tabla_').html(spiner);
            },*/
            success: function(response) {
                console.log(response);
                if (response) {
                    $('#txt_modelo').val(response[0].modelo);
                    $('#txt_medida').val(response[0].medida);
                    $('#txt_observacion').val(response[0].observacion);

                    $('#lbl_modelo').text(response[0].modelo);
                    $('#lbl_medida').text(response[0].medida);
                    $('#lbl_observacion').text(response[0].observacion)

                    $('#ddl_material').html(response[0].opcion);
                    $('#ddl_material_f').html(response[0].opcion);
                    $("#ddl_material_f").select2();
                    // console.log(response);
                    $('#txt_id_detalle_trabajo').val(response[0].id_detalle_trabajo);
                    var imag = '';
                    var con_foto = 0;
                    if (response[0].foto1 != '' && response[0].foto1 != null) {
                        $("#img_1").attr("src", response[0].foto1 + '?' + Math.random());
                        $('#btn_eli_1').css('display', 'initial');
                        imag += '<img src="' + response[0].foto1 + '?' + Math.random() + '" style="width:250px;height: 250px; border: 1px solid;">';
                        con_foto += 1;
                    } else {
                        $("#img_1").attr("src", '../img/de_sistema/sin_imagen.jpg?' + Math.random());
                        $('#btn_eli_1').css('display', 'none');
                    }


                    if (response[0].foto2 != '' && response[0].foto2 != null) {
                        $("#img_2").attr("src", response[0].foto2 + '?' + Math.random());
                        $('#btn_eli_2').css('display', 'initial');
                        imag += '<img src="' + response[0].foto2 + '?' + Math.random() + '" style="width:250px;height: 250px; border: 1px solid;">';
                        con_foto += 1;
                    } else {
                        $("#img_2").attr("src", '../img/de_sistema/sin_imagen.jpg?' + Math.random());
                        $('#btn_eli_2').css('display', 'none');
                    }


                    if (response[0].foto3 != '' && response[0].foto3 != null) {
                        $("#img_3").attr("src", response[0].foto3 + '?' + Math.random());
                        $('#btn_eli_3').css('display', 'initial');
                        imag += '<img src="' + response[0].foto3 + '?' + Math.random() + '" style="width:250px;height: 250px; border: 1px solid;">';
                        con_foto += 1;
                    } else {
                        $("#img_3").attr("src", '../img/de_sistema/sin_imagen.jpg?' + Math.random());
                        $('#btn_eli_3').css('display', 'none');
                    }


                    if (response[0].foto4 != '' && response[0].foto4 != null) {
                        $("#img_4").attr("src", response[0].foto4 + '?' + Math.random());
                        $('#btn_eli_4').css('display', 'initial');
                        imag += '<img src="' + response[0].foto4 + '?' + Math.random() + '" style="width:250px;height: 250px; border: 1px solid;">';
                        con_foto += 1;
                    } else {
                        $("#img_4").attr("src", '../img/de_sistema/sin_imagen.jpg?' + Math.random());
                        $('#btn_eli_4').css('display', 'none');
                    }


                    if (response[0].foto5 != '' && response[0].foto5 != null) {
                        $("#img_5").attr("src", response[0].foto5 + '?' + Math.random());
                        $('#btn_eli_5').css('display', 'initial');
                        imag += '<img src="' + response[0].foto5 + '?' + Math.random() + '" style="width:250px;height: 250px; border: 1px solid;">';
                        con_foto += 1;
                    } else {
                        $("#img_5").attr("src", '../img/de_sistema/sin_imagen.jpg?' + Math.random());
                        $('#btn_eli_5').css('display', 'none');
                    }


                    if (response[0].foto6 != '' && response[0].foto6 != null) {
                        $("#img_6").attr("src", response[0].foto6 + '?' + Math.random());
                        $('#btn_eli_6').css('display', 'initial');
                        imag += '<img src="' + response[0].foto6 + '?' + Math.random() + '" style="width:250px;height: 250px; border: 1px solid;">';
                        con_foto += 1;
                    } else {
                        $("#img_6").attr("src", '../img/de_sistema/sin_imagen.jpg?' + Math.random());
                        $('#btn_eli_6').css('display', 'none');
                    }

                    $('#gal_ima').html(imag);
                    $('#sin_foto').val(con_foto);

                }
            }

        });

    }

    function formatDate(date) {
        var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2)
            month = '0' + month;
        if (day.length < 2)
            day = '0' + day;

        return [year, month, day].join('-');
    }


    function add() {

        var orden = '<?php if (isset($_GET['id'])) {
                            echo $_GET['id'];
                        } ?>';
        var id = $('#txt_referencia').val();
        var articulo = $('#txt_producto').val();
        var cantidad = $('#txt_cantidad').val();
        var detalle = $('#txt_detalle').val();
        var bodega = $('#txt_bodega').val();


        var parametros = {
            'orden': orden,
            'ref': id,
            'art': articulo,
            'cant': cantidad,
            'detalle': detalle,
            'bodega': bodega
        }
        // console.log(parametros);
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/orden_trabajoC.php?add_linea=true',
            type: 'post',
            dataType: 'json',
            /*beforeSend: function () {   
                 var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
               $('#tabla_').html(spiner);
            },*/
            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Agregado a orden de trabajo.', 'success');
                    cargar_orden();
                } else {
                    Swal.fire('', 'Algo salio mal.', 'info');
                }
            }

        });

    }

    function validar_produccion() {
        var detalle = $('#txt_tipo1').val();
        if (detalle == 'P') {
            Swal.fire({
                title: 'Se cambiara la orden de trabajo a Producto!',
                text: "Esta seguro!, se eliminaran registros de Diseño",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si'
            }).then((result) => {
                if (result.value) {
                    add_validar_produccion();

                }
            });
        } else {
            add_validar_produccion();
        }

    }

    function add_validar_produccion() {
        if (articulo == '' || cantidad == '') {
            Swal.fire('', 'LLene todo los campos', 'info');
            return false;
        }
        var id = $('#txt_referencia').val();
        var articulo = $('#txt_producto').val();
        var cantidad = $('#txt_cantidad').val();
        var bode = $('#txt_bodega').val();
        var parametros = {
            'refe': id,
            'cant': cantidad,
            'bode': bode,
        }
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/orden_trabajoC.php?validar_produccion=true',
            type: 'post',
            dataType: 'json',
            /*beforeSend: function () {   
                 var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
               $('#tabla_').html(spiner);
            },*/
            success: function(response) {
                if (response == 1) {
                    add();
                } else if (response == 2) {
                    Swal.fire('', 'No se a asignado materia prima para este producto.', 'info');
                } else {
                    Swal.fire('', 'Stock de materia prima insuficiente.', 'info');
                }
            }

        });

    }

    function Eliminar(id) {

        Swal.fire({
            title: 'Quiere eliminar este registro?',
            text: "Esta seguro de eliminar este registro!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si'
        }).then((result) => {
            if (result.value) {


                var parametros = {
                    'linea': id,

                }
                // console.log(parametros);
                $.ajax({
                    data: {
                        parametros: parametros
                    },
                    url: '../controlador/orden_trabajoC.php?eliminar_linea=true',
                    type: 'post',
                    dataType: 'json',
                    /*beforeSend: function () {   
                         var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
                       $('#tabla_').html(spiner);
                    },*/
                    success: function(response) {
                        if (response == 1) {
                            Swal.fire('', 'Registro eliminado.', 'success');
                            cargar_orden();
                        } else {
                            Swal.fire('', 'Algo salio mal.', 'info');
                        }
                    }

                });

            }
        });


    }

    function datos_deseños() {

        var detalle = $('#txt_tipo1').val();
        if (detalle == 'C') {
            Swal.fire({
                title: 'Se cambiara la orden de trabajo a nuevo diseño!',
                text: "Esta seguro!, se eliminaran registros de producto",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si'
            }).then((result) => {
                if (result.value) {
                    add_datos_deseños();

                }
            });
        } else {
            add_datos_deseños();
        }


    }



    function eliminar_img(id) {

        var detalle = '<?php if (isset($_GET['id'])) {
                            echo $_GET['id'];
                        } ?>';
        Swal.fire({
            title: 'Quiere eliminar esta foto?',
            text: "Esta seguro de eliminar esta foto !",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si'
        }).then((result) => {
            if (result.value) {

                var parametros = {
                    'id': detalle,
                    'posicion': id,
                }
                // console.log(parametros);
                $.ajax({
                    data: {
                        parametros: parametros
                    },
                    url: '../controlador/orden_trabajoC.php?eliminar_imagen=true',
                    type: 'post',
                    dataType: 'json',
                    /*beforeSend: function () {   
                         var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
                       $('#tabla_').html(spiner);
                    },*/
                    success: function(response) {
                        if (response == 1) {
                            Swal.fire('', 'Registro eliminado.', 'success');
                            cargar_orden_boceto();
                        } else {
                            Swal.fire('', 'Algo salio mal.', 'info');
                        }
                    }

                });

            }
        });


    }


    function autocoplet_bodegas() {
        $('#ddl_bodega').select2({
            placeholder: 'Seleccione una bodega',
            width: '100%',
            ajax: {
                url: '../controlador/articulosC.php?bodegas=true',
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

    function guardar_cabecera() {

        var id = '<?php if (isset($_GET['id'])) {
                        echo $_GET['id'];
                    } ?>';
        var fecha = $('#txt_fecha_ent').val();
        var encar = $('#txt_encargado').val();
        var bodega = $('#ddl_bodega').val();
        var idmaestro = $('#txt_id_ma').val();
        // console.log(fecha);
        if (bodega == '' || encar == '' || fecha == '') {
            Swal.fire('', 'Llene todo los campos', 'info');
            return false;
        }
        var parametros = {
            'id': id,
            'fecha': fecha,
            'encargado': encar,
            'bodega': bodega,
            'maestro': idmaestro,
        }
        // console.log(parametros);
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/orden_trabajoC.php?editar_cabecera=true',
            type: 'post',
            dataType: 'json',
            /*beforeSend: function () {   
                 var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
               $('#tabla_').html(spiner);
            },*/
            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Cabecera editada.', 'success');
                    cargar_orden();
                } else {
                    Swal.fire('', 'Algo salio mal.', 'info');
                }
            }

        });

    }

    function finalizar_orden() {
        var li = $('#txt_num_li').val();
        var tipo = $('#txt_tipo1').val();
        var det = $('#txt_id_detalle_trabajo').val();

        // valida cabecera
        var encar = $('#txt_encargado').val();
        var bodega = $('#ddl_bodega').val();
        var fecha = $('#txt_fecha_ent').val();
        var sin_foto = $('#sin_foto').val();
        // console.log(fecha);
        if (bodega == '' || encar == '' || fecha == '') {
            Swal.fire('No se Puede finalizar', 'Llene todo los campos de cabecera', 'info');
            return false;
        }
        // fin validar cabecera

        if (tipo == 'C') {
            if (li == 0) {
                Swal.fire('No se Puede finalizar', 'la orden de trabajo no tiene ningun elemento agregado ', 'info');
                return false;
            }
        } else {
            if ($('#txt_modelo').val() == '' || $('#txt_medida').val() == '' || $('#ddl_material').val() == '') {
                Swal.fire('No se Puede finalizar', 'Llene todo los detalle de la orden de trabajo ', 'info');
                return false;
            }
            if (det == '') {
                Swal.fire('No se Puede finalizar', 'la orden de trabajo no tiene detalles para produccion de nuevo diseño ', 'info');
                return false;
            }
            if (sin_foto < 2) {
                Swal.fire('No se Puede finalizar', 'Ingrese por lo menos 2 fotos de diseño ', 'info');
                return false;
            }

        }


        var id = '<?php if (isset($_GET['id'])) {
                        echo $_GET['id'];
                    } ?>';

        var parametros = {
            'id': id,
        }
        // console.log(parametros);
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/orden_trabajoC.php?finalizar_orden=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Orden de trabajo Finalizada.', 'success');
                    var url = window.location.href;
                    url = url.slice(0, -1);
                    window.location.href = url + 'F';
                } else {
                    Swal.fire('', 'Algo salio mal.', 'info');
                }
            }

        });


    }

    function read() {
        var t = $('#txt_tipo1').val();
        console.log(t);

        $('#pnl_cabecera_pendiente').css('display', 'none');
        $('#pnl_add_productos').css('display', 'none');
        $('#pnl_cabecera_finalizado').css('display', 'initial');
        $('#btn_fin').css('display', 'none');
        if (t == 'P') { //cuando es diseño

            $('#btn_di').css('display', 'initial');
            $('#btn_pro').css('display', 'none');
            $('#pnl_diseño_finalizado').css('display', 'flex');
            $('#pnl_editar_diseño').css('display', 'none');

        } else {
            //cuando es producto terminado
            $('#btn_pro').css('display', 'initial');
            $('#btn_di').css('display', 'none');
            $('#pnl_diseño_finalizado').css('display', 'none');
        }
    }

    function write() {
        $('#pnl_cabecera_pendiente').css('display', 'initial');
        $('#btn_di').css('display', 'initial');
        $('#pnl_cabecera_finalizado').css('display', 'none');
    }

    function add_datos_deseños() {

        var id = '<?php if (isset($_GET['id'])) {
                        echo $_GET['id'];
                    } ?>';
        var material = $('#ddl_material').val();
        var modelo = $('#txt_modelo').val();
        var medida = $('#txt_medida').val();
        var observacion = $('#txt_observacion').val();

        var parametros = {
            'material': material,
            'modelo': modelo,
            'medida': medida,
            'observacion': observacion,
            'id': id,
            'idDT': $('#txt_id_detalle_trabajo').val(),
        }
        // console.log(parametros);
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/orden_trabajoC.php?detalle_diseño=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Agregado a orden de trabajo.', 'success');
                    cargar_orden();
                } else {
                    Swal.fire('', 'Algo salio mal.', 'info');
                }
            }

        });

    }


    function reporte() {
        var orden = '<?php if (isset($_GET['id'])) {
                            echo $_GET['id'];
                        } ?>';
        var url = '../controlador/orden_trabajoC.php?reporte=true&orden=' + orden;
        window.open(url, '_blank');
        // var datos =  $("#form_nuevo_articulo").serialize();
        // $.ajax({
        //     data:  {parametros:parametros},
        //     url:   url,
        //     type:  'post',
        //     dataType: 'json',
        //     success:  function (response) {  

        //      } 
        //   });
    }

    function nueva_orden() {
        $.ajax({
            // data:  {parametros:parametros},
            url: '../controlador/orden_trabajoC.php?new_order=true',
            type: 'post',
            dataType: 'json',
            /*beforeSend: function () {   
                 var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
               $('#tabla_').html(spiner);
            },*/
            success: function(response) {

                // console.log(response);
                ver(response, 'P');
            }

        });


    }

    function ver(id, est) {
        var url = 'nueva_orden_trabajo.php?id=' + id + '&estado=' + est;
        $(location).attr('href', url);
    }
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Nueva Orden de Trabajo</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Blank
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

                            <h5 class="mb-0 text-primary">Nueva Orden de Trabajo</h5>

                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <a class="btn btn-outline-secondary btn-sm" href="orden_trabajo.php">
                                    <i class="fas fa-arrow-left"></i> Regresar
                                </a>
                                <button class="btn btn-primary btn-sm" onclick="nueva_orden()">
                                    <i class="fas fa-plus"></i> Nuevo
                                </button>
                            </div>
                            <div class="col-sm-6 text-end">
                                <button class="btn btn-warning btn-sm" id="btn_imprimir" onclick="reporte()">
                                    <i class="fas fa-file-pdf"></i> Imprimir
                                </button>
                                <button class="btn btn-success btn-sm" id="btn_fin" onclick="finalizar_orden()">
                                    Finalizar orden de trabajo
                                </button>
                            </div>
                        </div>

                        <section class="content">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="card border-primary">
                                            <div class="card-header text-white bg-primary py-2">
                                                <h5 class="card-title mb-0">Datos de orden de trabajo</h5>
                                            </div>
                                            <div class="card-body" id="pnl_cabecera_pendiente">
                                                <div class="row g-3">
                                                    <div class="col-sm-3">
                                                        <b>Desde:</b><br>
                                                        <label id="lbl_punto"></label>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <b>Para bodega:</b>
                                                        <select class="form-select form-select-sm" id="ddl_bodega">
                                                            <option>Seleccione un destino</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <b>Fecha de pedido</b>
                                                        <input type="date" class="form-control form-control-sm" value="<?php echo date('Y-m-d') ?>" readonly>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <b>Fecha de entrega</b>
                                                        <input type="date" name="txt_fecha_ent" class="form-control form-control-sm" value="<?php echo date('Y-m-d') ?>" id="txt_fecha_ent">
                                                    </div>
                                                    <div class="col-sm-2 text-center">
                                                        <b>No. Orden</b><br>
                                                        <label style="font-size:18px" id="txt_num">0</label>
                                                    </div>
                                                    <div class="col-sm-5">
                                                        <b>Encargado</b>
                                                        <div class="input-group input-group-sm">
                                                            <input type="text" class="form-control" id="txt_encargado">
                                                            <button type="button" class="btn btn-info btn-sm" style="display:none" id="btn_edi_enca">
                                                                <i class="fas fa-pen"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <b>Maestro que recibe:</b>
                                                        <input type="hidden" id="txt_id_ma">
                                                        <input type="text" class="form-control form-control-sm" id="txt_maestro" name="txt_maestro">
                                                    </div>
                                                    <div class="col-sm-3 text-center">
                                                        <button class="btn btn-primary btn-sm mt-3" onclick="guardar_cabecera()">Guardar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pestañas -->
                                <div class="row">
                                    <div class="col-md-12 mt-3">
                                        <div class="card">
                                            <div class="card-header p-2">
                                                <ul class="nav nav-pills">
                                                    <li class="nav-item">
                                                        <a class="nav-link active" id="btn_pro" data-bs-toggle="tab" href="#clientes">Producto</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="btn_di" data-bs-toggle="tab" href="#proveedores">Nuevo diseño</a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="card-body">
                                                <div class="tab-content">
                                                    <!-- Tab Producto -->
                                                    <div class="tab-pane fade show active" id="clientes">
                                                        <div class="row g-3">
                                                            <div class="col-sm-2">
                                                                <label>Referencia</label>
                                                                <input type="text" class="form-control form-control-sm" id="txt_referencia" readonly>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <label>Producto</label>
                                                                <input type="hidden" id="txt_bodega">
                                                                <input type="text" class="form-control form-control-sm" id="txt_producto">
                                                            </div>
                                                            <div class="col-sm-1">
                                                                <label>Cantidad</label>
                                                                <input type="text" class="form-control form-control-sm" id="txt_cantidad" value="1">
                                                            </div>
                                                            <div class="col-sm-2">
                                                                <label>Detalle</label>
                                                                <input type="text" class="form-control form-control-sm" id="txt_detalle">
                                                            </div>
                                                            <div class="col-sm-2 text-center">
                                                                <br>
                                                                <button class="btn btn-primary btn-sm" onclick="validar_produccion()">
                                                                    <i class="fa fa-plus"></i> Agregar
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <div class="row">
                                                            <div class="col-sm-12" id="tbl_orden"></div>
                                                        </div>
                                                    </div>

                                                    <!-- Tab Proveedores -->
                                                    <div class="tab-pane fade" id="proveedores">
                                                        <div class="row g-3">
                                                            <div class="col-sm-4">
                                                                <b>Material:</b>
                                                                <select class="form-select form-select-sm" id="ddl_material" name="ddl_material[]" multiple>
                                                                    <option value="">Seleccione</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-2">
                                                                <b>Modelo:</b>
                                                                <input type="text" class="form-control form-control-sm" id="txt_modelo" name="txt_modelo">
                                                            </div>
                                                            <div class="col-sm-1">
                                                                <b>Medida:</b>
                                                                <input type="text" class="form-control form-control-sm" name="txt_medida" id="txt_medida">
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <b>Observación:</b>
                                                                <textarea rows="1" class="form-control form-control-sm" name="txt_observacion" id="txt_observacion"></textarea>
                                                            </div>
                                                            <div class="col-sm-2 text-center">
                                                                <br>
                                                                <button class="btn btn-primary btn-sm" onclick="datos_deseños()">Guardar</button>
                                                            </div>
                                                        </div>

                                                        <!-- Subida de imágenes -->
                                                        <div class="row mt-3">
                                                            <div class="col-sm-4 text-center">
                                                                <b>Diseño base</b>
                                                                <img src="../img/de_sistema/sin_imagen.jpg" class="img-fluid border" id="img_1">
                                                                <input type="file" id="file_img_1" name="file_img[]" class="form-control form-control-sm">
                                                            </div>
                                                            <div class="col-sm-4 text-center">
                                                                <b>Producto terminado</b>
                                                                <img src="../img/de_sistema/sin_imagen.jpg" class="img-fluid border" id="img_2">
                                                                <input type="file" id="file_img_2" name="file_img[]" class="form-control form-control-sm">
                                                            </div>
                                                            <div class="col-sm-4 text-center">
                                                                <b>Foto modelo</b>
                                                                <img src="../img/de_sistema/sin_imagen.jpg" class="img-fluid border" id="img_3">
                                                                <input type="file" id="file_img_3" name="file_img[]" class="form-control form-control-sm">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>


                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>


<div class="modal" id="modal_blank" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
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
                        <label for="">Blank <label class="text-danger">*</label></label>
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