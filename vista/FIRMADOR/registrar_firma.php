<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);
?>

<script src="../js/GENERAL/operaciones_generales.js"></script>
<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script type="text/javascript">
  $(document).ready(function() {

    // Opciones para mostrar/ocultar sección de clave
    $("#datos_extras").hide();
    // Inicializamos el DataTable para firmas consumiendo datos vía AJAX
    tbl_personas = $('#tbl_firmas').DataTable($.extend({}, configuracion_datatable('Personas', 'personas'), {
      dom: 'lfrtip',
      language: {
        url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
      },
      responsive: true,
      ajax: {
        url: '../controlador/FIRMADOR/th_personas_firmasC.php?listar=true',
        dataSrc: ''
      },
      columns: [{
          // Muestra Nombre y Apellido concatenados
          data: null,
          render: function(data, type, item) {
            return item.th_per_primer_nombre + ' ' + item.th_per_primer_apellido;
          }
        },
        {
          data: 'th_perfir_nombre_firma',

        },
        {
          data: 'th_perfir_identificacion'
        },
        {
          // Ocultamos la clave con asteriscos
          data: 'th_perfir_contrasenia',
          render: function(data, type, item) {
            return '<span class="noMostrar">' + data.replace(/./g, '*') + '</span>';
          }
        },
        {
          data: 'th_perfir_fecha_creacion',
          render: function(data, type, item) {
            return fecha_formateada(data);
          }
        },
        {
          data: 'th_perfir_fecha_expiracion',
          render: function(data, type, item) {
            return fecha_formateada(data);
          }
        },
        {
          data: null,
          render: function(data, type, item) {
            return `
      <button type="button" class="btn btn-primary btn-xs" onclick="editar_firma(${item.th_perfir_id})">
        <i class="lni lni-pencil fs-7 me-0 fw-bold"></i>
      </button>
      <button type="button" class="btn btn-danger btn-xs" onclick="eliminar_firma(${item.th_perfir_id})">
        <i class="lni lni-trash fs-7 me-0 fw-bold"></i>
      </button>
    `;
          }
        }

      ],
      order: [
        [0, 'asc']
      ]
    }));

    // $("#datos_extras").hide();


    url_personasC = '../controlador/FIRMADOR/th_cat_tipo_firmaC.php?buscar=true';
    cargar_select2_url('ddl_tipo_firma', url_personasC, 'tipo de firma', '#modal_firma');
  });

  // Configuración de validación del formulario



  function crear_firma() {
    $("#txt_cargar_imagen").prop("disabled", false);
    $("#ddl_tipo_firma").attr("disabled", "disabled");
    $("#cbx_guardarClave_hidden").val(0);
    $.ajax({
      url: '../controlador/TALENTO_HUMANO/th_personasC.php?listar_personas_rol=true',
      dataType: 'json',
      success: function(response) {

        if (response.length > 0) {
          //data en vez de data
          var data = response[0]; // Obtener el primer objeto

          $("#cedula").val(data.cedula);
          $("#datos_extras").hide();

          // Mostrar el modal
          $("#modal_firma").modal("show");
        } else {
          Swal.fire({
            icon: "warning",
            title: "Registro no encontrado",
            text: "No se encontraron datos para este ID."
          });
        }
      },
      error: function(xhr, status, error) {
        //console.error("Error al obtener los datos:", error);
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "No se pudo obtener los datos de la persona."
        });
      }
    });


    let form = $("#registrar_firma");

    // Resetear formulario y validaciones
    form[0].reset();
    form.validate().resetForm();
    form.find(".is-invalid, .is-valid").removeClass("is-invalid is-valid");
    form.data("id", "");

    // Limpiar campos específicos
    let campos = [
      "#_id", "#txt_nombreFirma",
      "#txt_identidad", "#ddl_tipoPersona", "#txt_fecha_creacion", "#txt_url_firma",
      "#txt_fecha_expiracion", "#txt_ingresarClave", "#txt_comprobarClave"
    ];

    $("#file-loaded-message").remove();

    campos.forEach(id => $(id).val(""));

    // Restablecer checkbox y botón
    $("#cbx_politicaDeDatos").prop("checked", false);
    $("#btn_enviar").text("Guardar la firma");

    // Mostrar el modal
    $("#modal_firma").modal("show");
  }


  function generar_pdf() {
    // $.ajax({
    //   url: '../controlador/TALENTO_HUMANO/th_reportes_personasC.php?imprimirPDF=true',
    //   type: 'POST',
    //   dataType: 'json',
    //   success: function(response) {
    //     console.log(response);

    //     if (response.success && response.ruta) {
    //       // Abrir el PDF en una nueva ventana o descargar usando la ruta devuelta
    //       window.open(response.ruta, '_blank');

    //       Swal.fire({
    //         icon: "success",
    //         title: "PDF generado",
    //         text: "El PDF se ha generado correctamente."
    //       });
    //     } else {
    //       Swal.fire({
    //         icon: "error",
    //         title: "Error",
    //         text: response.message || "Ocurrió un error al generar el PDF."
    //       });
    //     }
    //   },
    //   error: function(xhr, status, error) {
    //     Swal.fire({
    //       icon: "error",
    //       title: "Error",
    //       text: "Ocurrió un error al procesar la solicitud."
    //     });
    //   }
    // });

    window.open('https://corsinf.com/cartsearch/index.html?sortOrderBy=relevance', '_blank');

  }


  // Función para validar que las contraseñas coincidan (si se requiere)


  // Función para insertar o editar una firma mediante AJAX
  function insertar_editar() {

    var form = $("#registrar_firma");

    $("#txt_fecha_expiracion").removeAttr("disabled");
    $("#txt_fecha_inicio").removeAttr("disabled");
    $("#txt_nombreFirma").removeAttr("disabled");
    $("#txt_identidad").removeAttr("disabled");
    $("#txt_url_firma").removeAttr("disabled");
    $("#ddl_tipo_firma").removeAttr("disabled");
    let url = $("#txt_url_firma").val();
    let file = $("#txt_cargar_imagen").val();

    console.log(url);
    console.log(file);

    if (file === "") {
      $("#txt_cargar_imagen").prop("disabled", true);
    } else {
      $("#txt_cargar_imagen").prop("disabled", false);
    }


    // Verifica si el formulario es válido antes de enviar la petición AJAX
    if (!form.valid()) {
      return;
    }

    var form_data = new FormData(document.getElementById("registrar_firma"));


    $.ajax({
      url: '../controlador/FIRMADOR/th_personas_firmasC.php?insertar=true',
      type: 'POST',
      data: form_data,
      contentType: false, // Necesario para enviar archivos
      processData: false, // No procesa los datos; se envían tal cual
      dataType: 'json', // Espera respuesta en JSON
      success: function(response) {
        // Si el controlador envía un -2 significa error de RUC duplicado
        if (response == 1) {
          Swal.fire({
            icon: "success",
            title: "Registro guardado",
            text: "Los datos se han guardado correctamente."
          });
          $("#registrar_firma")[0].reset();
          $("#datos_extras").hide();
          $('#modal_firma').modal('hide');
          $('#tbl_firmas').DataTable().ajax.reload();
        }
      },
      error: function(xhr, status, error) {
        //console.log(error);
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "Ocurrió un error al procesar la solicitud."
        });
      }
    });


  }

  // Función para editar (cargar datos en el formulario y mostrar el modal)
  function editar_firma(id) {


    //$('#datos_extras').show();

    let form = $("#registrar_firma");



    form.validate().resetForm();
    form.find(".is-invalid, .is-valid").removeClass("is-invalid is-valid");
    $.ajax({
      url: '../controlador/FIRMADOR/th_personas_firmasC.php?listar_persona=true',
      type: 'POST',
      data: {
        id: id // Enviar el ID correctamente
      },
      dataType: 'json', // Forzar que la respuesta sea interpretada como JSON
      success: function(response) {

        if (response.length > 0) {
          var data = response[0]; // Tomar el primer objeto si hay varios

          console.log(data);

          // Aquí suponemos que data.th_perfir_documento_url contiene la URL completa del archivo .p12
          let filePath = data.documento_url;

          // Guardamos la ruta en un atributo "data-filepath" o en un campo oculto
          $("#txt_url_firma").val(filePath);

          // Mostrar un mensaje que indique que el archivo ya está cargado
          if ($("#file-loaded-message").length === 0) {
            $("#txt_cargar_imagen").after('<div id="file-loaded-message" class="text-success mt-1"><small>Archivo cargado: ' + filePath.split('/').pop() + '</small></div>');
          } else {
            $("#file-loaded-message").html('<small>Archivo cargado: ' + filePath.split('/').pop() + '</small>');
          }
          let descripcion = "";
          if (data.id_tipfir == "1") {
            descripcion = "Natural";
          } else if (data.id_tipfir == "2") {
            descripcion = "Juridica";
          } else {
            descripcion = "Generica";
          }
          $('#ddl_tipo_firma').append($('<option>', {
            value: data.id_tipfir,
            text: "hola desde cambios",
            selected: true
          }));
          //console.log(data.id_tipfir);
          //console.log(descripcion);


          // Asignar valores a los campos del formulario
          $("#_id").val(data._id);
          $("#th_per_id").val(data.id_persona);
          $("#txt_identidad").val(data.identificacion);
          $("#txt_nombreFirma").val(data.nombre_firma);
          $("#ddl_tipoPersona").val(data.id_tipfir);
          $("#txt_fecha_expiracion").val(fecha_formateada(data.fecha_expiracion));

          // Limpiar campos de clave por seguridad
          $("#txt_ingresarClave").val(data.password);
          $("#txt_comprobarClave").val(data.password);
          $("#cbx_politicaDeDatos").prop("checked", data.th_perfir_politica_de_datos == "1");
          $("#ddl_tipo_firma").val(`${data.th_tipfir_id}`);
          $("#registrar_firma").data("id", id);
          $("#datos_extras").show();
          $("#btn_enviar").text("Actualizar la firma");
          $("#modal_firma").modal("show");


        } else {
          //console.error("No se encontró el registro con id: " + id);
          Swal.fire({
            icon: "warning",
            title: "Registro no encontrado",
            text: "No se encontraron datos para este ID."
          });
        }
      },
      error: function(xhr, status, error) {
        //console.error("Error al obtener los datos:", error);
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "No se pudo obtener los datos de la firma."
        });
      }
    });
  }



  // Función para eliminar una firma (eliminación lógica)
  function eliminar_firma(id) {
    Swal.fire({
      title: '¿Está seguro?',
      text: "Esta acción no se puede deshacer.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: '../controlador/FIRMADOR/th_personas_firmasC.php?eliminar=true',
          type: 'POST',
          data: {
            id: id
          },
          success: function(response) {
            if (response == 1) {
              Swal.fire({
                icon: "success",
                title: "Eliminado",
                text: "El registro ha sido eliminado."
              });
              $('#tbl_firmas').DataTable().ajax.reload();
            } else {
              Swal.fire({
                icon: "error",
                title: "Error",
                text: "Ocurrió un error al eliminar el registro."
              });
            }

          },
          error: function(xhr, status, error) {
            Swal.fire({
              icon: "error",
              title: "Error",
              text: "Ocurrió un error al eliminar el registro."
            });
          }
        });
      }
    });
  }

  function verificar_firma() {

    $("#txt_fecha_expiracion").attr("disabled", "disabled");
    $("#txt_fecha_inicio").attr("disabled", "disabled");
    $("#txt_nombreFirma").attr("disabled", "disabled");
    $("#txt_identidad").attr("disabled", "disabled");
    $("#txt_url_firma").removeAttr("disabled");

    if (!validar_form()) {
      event.preventDefault();
    }

    let url = $("#txt_url_firma").val();
    let file = $("#txt_cargar_imagen").val();

    console.log(url);
    console.log(file);

    if (file === "") {
      $("#txt_cargar_imagen").prop("disabled", true);
    } else {
      $("#txt_cargar_imagen").prop("disabled", false);
    }

    var formData = new FormData(document.getElementById("registrar_firma"));
    $.ajax({
      url: '../controlador/FIRMADOR/validar_firmaC.php?validar_firma=true',
      type: 'post',
      data: formData,
      contentType: false,
      processData: false,
      dataType: 'json',
      // beforeSend: function () {
      //        $("#foto_alumno").attr('src',"../img/gif/proce.gif");
      //     },
      success: function(response) {
        if (response.resp == 1) {

          let jsonObject = JSON.parse(response.data);

          // Suponiendo que jsonObject.FechaFin es "30/08/2025"
          let fechaRaw = jsonObject.FechaFin; // "30/08/2025"

          // Convertir "30/08/2025" a "2025-08-30"
          let partes = fechaRaw.split("/"); // Divide la fecha en partes [30, 08, 2025]
          let fechaFormateada = `${partes[2]}-${partes[1]}-${partes[0]}`; // Reorganiza en formato YYYY-MM-DD


          $("#txt_fecha_expiracion").val(fechaFormateada);
          $("#txt_fecha_inicio").val(fechaFormateada);
          $("#txt_nombreFirma").val(jsonObject.EMC_CN);
          $("#txt_identidad").val(jsonObject.SERIALNUMBER);
          $('#ddl_tipo_firma').append($('<option>', {
            value: jsonObject.valor,
            text: jsonObject.descripcion,
            selected: true
          }));

          // Asignar al input de tipo date

          // Mostrar el objeto en consola
          console.log(jsonObject);
          Swal.fire(response.msj, "", "success")
          $("#datos_extras").show();
          $("#btn_verificar").hide();
          $("#cbx_politicaDeDatos").prop("disabled", true);

        } else {
          Swal.fire(response.msj, "", "error")
          $("#datos_extras").hide();
        }
      }
    });


  }

  function validar_form() {
    var clave = $("#txt_ingresarClave").val();
    var confirmar_clave = $("#txt_comprobarClave").val();

    if ($('#cbx_guardarClave').is(':checked')) {
      if (clave !== confirmar_clave) {
        $("#datos_extras").hide();
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Las contraseñas no coinciden."
        });

        return false;
      }
    }
    return true;
  }

  $(document).on('click', '#togglePassword', function() {
    const passwordInput = $('#txt_ingresarClave');
    const icon = $(this);

    if (passwordInput.attr('type') === 'password') {
      passwordInput.attr('type', 'password');
      icon.removeClass('bx-show').addClass('bx-hide');
    } else {
      passwordInput.attr('type', 'text');
      icon.removeClass('bx-hide').addClass('bx-show');
    }
  });

  $(document).on('click', '#togglePasswordConfirm', function() {
    const passwordInput = $('#txt_comprobarClave');
    const icon = $(this);

    if (passwordInput.attr('type') === 'password') {
      passwordInput.attr('type', 'password');
      icon.removeClass('bx-show').addClass('bx-hide');
    } else {
      passwordInput.attr('type', 'text');
      icon.removeClass('bx-hide').addClass('bx-show');
    }
  });



  $(document).ready(function() {

    $("#cbx_guardarClave").on("change", function() {
      if ($(this).is(":checked")) {
        $("#cbx_guardarClave_hidden").val("1"); // Si está marcado, enviar 1
      } else {
        $("#cbx_guardarClave_hidden").val("0"); // Si está desmarcado, enviar 0
      }
    });





    function verificarClaves() {
      // Si hay cambios en los inputs, mostrar el botón "Verificar" y ocultar datos_extras
      $("#btn_verificar").fadeIn();
      $("#datos_extras").fadeOut(); // Oculta el formulario extra
      $("#cbx_politicaDeDatos").prop("disabled", false);
    }

    // Escuchar cambios en los campos de contraseña
    $("#txt_ingresarClave, #txt_comprobarClave").on("input", verificarClaves);

    $("#txt_cargar_imagen").on("change", function() {
      $("#btn_verificar").fadeIn(); // Oculta el botón "Verificar"
      $("#datos_extras").fadeOut(); // Oculta datos extras
      $("#cbx_politicaDeDatos").prop("disabled", false); // Habilita política de datos
    });

    // Toggle para mostrar/ocultar la contraseña
    $("#togglePassword").click(function() {
      let input = $("#txt_ingresarClave");
      let icon = $(this);
      if (input.attr("type") === "password") {
        input.attr("type", "text");
        icon.removeClass("bx-show").addClass("bx-hide");
      } else {
        input.attr("type", "password");
        icon.removeClass("bx-hide").addClass("bx-show");
      }
      $("#datos_extras").fadeOut(); // Ocultar datos_extras al cambiar visibilidad
    });

    $("#togglePasswordConfirm").click(function() {
      let input = $("#txt_comprobarClave");
      let icon = $(this);
      if (input.attr("type") === "password") {
        input.attr("type", "text");
        icon.removeClass("bx-show").addClass("bx-hide");
      } else {
        input.attr("type", "password");
        icon.removeClass("bx-hide").addClass("bx-show");
      }
      $("#datos_extras").fadeOut(); // Ocultar datos_extras al cambiar visibilidad
    });

    let $checkbox = $("#cbx_politicaDeDatos");
    let $btn = $("#btn_verificar");

    // Inicialmente bloqueamos el botón
    $btn.css({
      "opacity": "0.5",
      "pointer-events": "none"
    });

    $checkbox.on("change", function() {
      if ($(this).is(":checked")) {
        $btn.css({
          "opacity": "1",
          "pointer-events": "auto"
        });
      } else {
        $btn.css({
          "opacity": "0.5",
          "pointer-events": "none"
        });
      }
    });
  });
</script>
<!-- HTML para la tabla de firmas con estilos mejorados -->
<div class="page-wrapper">
  <div class="page-content">
    <!-- Breadcrumb -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3">Facturación</div>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
            <li class="breadcrumb-item active" aria-current="page">Firmador</li>
          </ol>
        </nav>
      </div>
    </div>
    <!-- Contenido -->
    <div class="row">
      <div class="col-xl-12 mx-auto">
        <div class="card border-top border-0 border-4 border-primary">
          <div class="card-body p-5">
            <div class="row">
              <div class="col-12 col-md-6">
                <div class="card-title d-flex align-items-center">
                  <div id="btn_nuevo">
                    <button type="button" class="btn btn-success btn-sm" onclick="crear_firma()">
                      <i class="bx bx-plus me-0 pb-1"></i> Agregar Firma
                    </button>

                    <button type="button" class="btn btn-success btn-sm" onclick="generar_pdf()">
                      <i class="bx bx-plus me-0 pb-1"></i> Generar PDF
                    </button>
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-6 text-md-end text-start">
                <div id="contenedor_botones"></div>
              </div>
            </div>
            <hr>
            <section class="content pt-2">
              <div class="container-fluid">
                <div class="table-responsive">
                  <table class="table table-striped responsive" id="tbl_firmas" style="width:100%">
                    <thead>
                      <tr>
                        <th>Nombre</th>
                        <th>Firma</th>
                        <th>RUC</th>
                        <th>Clave</th>
                        <th>Fecha de inicio</th>
                        <th>Fecha de vencimiento</th>
                        <th width="10%">Acción</th>
                      </tr>
                    </thead>
                    <tbody>
                      <!-- Los datos se cargarán vía AJAX -->
                    </tbody>
                  </table>
                </div>
              </div><!-- /.container-fluid -->
            </section>
          </div>
        </div>
      </div>
    </div>
    <!-- Fin row -->
  </div>
</div>

<!-- Modal para agregar/editar firma -->
<div class="modal" id="modal_firma" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <!-- Header del Modal -->
      <div class="modal-header">
        <h5><small class="text-body-secondary">Ingrese los datos</small></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <!-- Body del Modal -->
      <div class="modal-body">
        <form id="registrar_firma" enctype="multipart/form-data" method="post" style="width: inherit;">
          <input type="hidden" name="_id" id="_id" value="">
          <input type="hidden" name="th_per_id" id="th_per_id" value="<?= $_SESSION['INICIO']['NO_CONCURENTE'] ? $_SESSION['INICIO']['NO_CONCURENTE'] : NULL  ?>">
          <input type="hidden" name="th_usuario_id" id="th_usuario_id" value="<?= $_SESSION['INICIO']['ID_USUARIO'] ? $_SESSION['INICIO']['ID_USUARIO'] : 2 ?>">
          <input type="hidden" name="txt_fecha_inicio" id="txt_fecha_inicio" value="">
          <input type="hidden" name="cedula" id="cedula" value="">
          <div class="mb-3">
            <label for="txt_cargar_imagen" class="form-label form-label-sm">Subir un documento <span style="color: red;">*</span></label>
            <input type="file" class="form-control form-control-sm" name="txt_cargar_imagen" id="txt_cargar_imagen" accept=".p12">
            <input id="txt_url_firma" type="hidden" name="txt_url_firma" value="">
          </div>

          <div class="mb-3" id="seccionClave">
            <label for="txt_ingresarClave" class="form-label form-label-sm">
              Contraseña <span style="color: red;">*</span>
            </label>
            <div class="d-flex align-items-center">
              <div class="flex-grow-1 position-relative">
                <input type="password" class="form-control form-control-sm" name="txt_ingresarClave" id="txt_ingresarClave" placeholder="Ingrese una contraseña">
                <!-- Aquí se insertarán los indicadores de validación -->
              </div>
              <div class="ms-2">
                <i class="bx bx-show" id="togglePassword" style="cursor: pointer;"></i>
              </div>
            </div>
          </div>

          <div class="mb-3" id="seccionValidar">
            <label for="txt_comprobarClave" class="form-label form-label-sm">
              Verificar Contraseña <span style="color: red;">*</span>
            </label>
            <div class="d-flex align-items-center">
              <div class="flex-grow-1 position-relative">
                <input type="password" class="form-control form-control-sm" name="txt_comprobarClave" id="txt_comprobarClave" placeholder="Ingrese nuevamente su contraseña">
                <!-- Aquí se insertarán los indicadores de validación -->
              </div>
              <div class="ms-2">
                <i class="bx bx-show" id="togglePasswordConfirm" style="cursor: pointer;"></i>
              </div>
            </div>
          </div>

          <div class="mb-3">
            <label for="cbx_guardarClave" class="form-check-label">¿Quiere guardar su contraseña?</label>
            <input type="checkbox" class="form-check-input form-check-input-sm" name="cbx_guardarClave" id="cbx_guardarClave">
            <input type="hidden" name="cbx_guardarClave_hidden" id="cbx_guardarClave_hidden" value="0">
          </div>


          <div class="mb-3">
            <input class="form-check-input" type="checkbox" name="cbx_politicaDeDatos" id="cbx_politicaDeDatos">
            <label class="form-label" for="cbx_politicaDeDatos">Aceptar los términos y condiciones y la política de datos.</label>
            <label class="error" style="display: none;" for="cbx_politicaDeDatos"></label>
          </div>

          <div class="mb-3 text-end">
            <button type="button" class="btn btn-success btn-sm" id="btn_verificar" onclick="verificar_firma();">Verificar</button>
          </div>
          <div id="datos_extras">
            <!-- Si deseas incluir apellido, agrega este campo -->
            <div class="mb-3">
              <label for="txt_nombreFirma" class="form-label form-label-sm">Nombre de la firma <span style="color: red;">*</span></label>
              <input type="text" class="form-control form-control-sm" name="txt_nombreFirma" id="txt_nombreFirma" placeholder="Escriba el nombre de la firma" disabled>
            </div>
            <div class="mb-3">
              <label for="txt_identidad" class="form-label form-label-sm">
                Identidad <span style="color: red;">*</span>
              </label>
              <input type="text" class="form-control form-control-sm" name="txt_identidad" id="txt_identidad" placeholder="Escriba su identidad" disabled>
            </div>

            <div class="mb-3">
              <label for="ddl_tipo_firma" class="form-label form-label-sm">Tipo de firma</label>
              <select class="form-control form-control-sm" name="ddl_tipo_firma" id="ddl_tipo_firma" disabled>
              </select>
            </div>
            <div class="mb-3">
              <label for="txt_fecha_expiracion" class="form-label form-label-sm">Fecha Expiración</label>
              <input type="date" class="form-control form-control-sm" name="txt_fecha_expiracion" id="txt_fecha_expiracion" placeholder="Fecha Expiración" disabled>
            </div>



            <div class="mb-3 text-end">
              <button type="button" class="btn btn-success btn-sm" id="btn_enviar" onclick="insertar_editar();">Guardar la firma</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    $("#registrar_firma").validate({
      rules: {
        txt_nombreFirma: {
          required: true
        },
        txt_identidad: {
          required: true
        },
        txt_cargar_imagen: {
          required: true
        },
        txt_ingresarClave: {
          required: true
        },
        txt_comprobarClave: {
          required: true,
          equalTo: "#txt_ingresarClave"
        },
        cbx_politicaDeDatos: { // Validar que el checkbox esté marcado
          required: true
        },
        txt_identidad: {
          required: true
        },
        ddl_tipo_firma: {
          required: true

        }
      },
      messages: {
        txt_nombreFirma: {
          required: "Por favor ingresa el nombre de la firma"
        },
        txt_identidad: {
          required: "Por favor ingresa tu RUC"
        },
        txt_cargar_imagen: {
          required: "Por favor sube un documento"
        },
        txt_ingresarClave: {
          required: "Por favor ingresa una clave"
        },
        txt_comprobarClave: {
          required: "Por favor ingrese la misma clave",
          equalTo: "Las contraseñas no coinciden"
        },
        cbx_politicaDeDatos: { // Mensaje si el usuario no marca el checkbox
          required: "Debe aceptar la Política de Datos para continuar"
        },
        txt_identidad: {
          required: "Por favor ingresa tu RUC"
        },
        ddl_tipo_firma: {
          required: "Por favor selecciona el tipo de firma"
        }

      },
      highlight: function(element) {
        $(element).addClass('is-invalid');
        $(element).removeClass('is-valid');
      },
      unhighlight: function(element) {
        $(element).removeClass('is-invalid');
        $(element).addClass('is-valid');
      },
      errorPlacement: function(error, element) {
        if (element.attr("type") === "checkbox") {
          error.insertAfter(element.parent()); // Ubica el mensaje debajo del checkbox
        } else {
          error.insertAfter(element);
        }
      }
    });
  });
</script>