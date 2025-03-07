<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);
?>

<script src="../js/GENERAL/operaciones_generales.js"></script>
<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script type="text/javascript">
  $(document).ready(function() {

    // Opciones para mostrar/ocultar sección de clave
    $("#seccionClave, #seccionValidar").hide();
    // Inicializamos el DataTable para firmas consumiendo datos vía AJAX
    tbl_personas = $('#tbl_firmas').DataTable($.extend({}, configuracion_datatable('Personas', 'personas'), {
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
    cargar_select2_url('ddl_tipo_firma', url_personasC, 'persona', '#modal_firma');
  });

  // Configuración de validación del formulario



  $(document).on('change', '#cbx_guardarClave', function() {
    if ($(this).is(':checked')) {
      $("#seccionClave, #seccionValidar").show();
      $("#txt_clave, #txt_validarClave").prop('required', true);
    } else {
      $("#seccionClave, #seccionValidar").hide();
    }
  });

  //crear_firma_firma



  function crear_firma() {
    // $("#datos_extras").hide();
    $.ajax({
      url: '../controlador/TALENTO_HUMANO/th_personasC.php?listar_personas_rol=true',
      dataType: 'json',
      success: function(response) {

        if (response.length > 0) {
          //data en vez de data
          var data = response[0]; // Obtener el primer objeto

          $("#cedula").val(data.cedula);

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
      "#txt_identidad", "#ddl_tipoPersona", "#txt_fecha_creacion",
      "#txt_fecha_expiracion", "#txt_clave", "#txt_validarClave"
    ];
    campos.forEach(id => $(id).val(""));

    // Restablecer checkbox y botón
    $("#cbx_politicaDeDatos").prop("checked", false);
    $("#btn_enviar").text("Guardar la firma");

    // Mostrar el modal
    $("#modal_firma").modal("show");
  }

  // Función para validar que las contraseñas coincidan (si se requiere)
  function validar_form() {
    var clave = $("#txt_clave").val();
    var confirmar_clave = $("#txt_validarClave").val();

    if ($('#cbx_guardarClave').is(':checked')) {
      if (clave !== confirmar_clave) {
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

  // Función para insertar o editar una firma mediante AJAX
  function insertar_editar() {

    var form = $("#registrar_firma");

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
          // Asignar valores a los campos del formulario

          $("#_id").val(data._id);
          $("#th_per_id").val(data.id_persona);
          $("#txt_identidad").val(data.identificacion);
          $("#txt_nombreFirma").val(data.nombre_firma);
          $("#ddl_tipoPersona").val(data.id_tipfir);
          $("#txt_fecha_expiracion").val(fecha_formateada(data.fecha_expiracion));

          // Limpiar campos de clave por seguridad
          $("#txt_clave").val('');
          $("#txt_validarClave").val('');
          $("#cbx_politicaDeDatos").prop("checked", data.th_perfir_politica_de_datos == "1");
          $("#ddl_tipo_firma").val(data.th_tipfir_id);
          $("#registrar_firma").data("id", id);
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

    var formData = new FormData(document.getElementById("registrar_firma"));
    $.ajax({
      url: '../controlador/FIRMADOR/validar_firmaC.php?validar_firma_funcional=true',
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
          Swal.fire(response.msj, "", "success")
        } else {
          Swal.fire(response.msj, "", "error")
        }
      }
    });

  }

  $(document).on('click', '#togglePassword', function() {
    const passwordInput = $('#txt_clave');
    const icon = $(this);

    if (passwordInput.attr('type') === 'password') {
      passwordInput.attr('type', 'text');
      icon.removeClass('bx-show').addClass('bx-hide');
    } else {
      passwordInput.attr('type', 'password');
      icon.removeClass('bx-hide').addClass('bx-show');
    }
  });

  $(document).on('click', '#togglePasswordConfirm', function() {
    const passwordInput = $('#txt_validarClave');
    const icon = $(this);

    if (passwordInput.attr('type') === 'password') {
      passwordInput.attr('type', 'text');
      icon.removeClass('bx-show').addClass('bx-hide');
    } else {
      passwordInput.attr('type', 'password');
      icon.removeClass('bx-hide').addClass('bx-show');
    }
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
          <input type="hidden" name="th_per_id" id="th_per_id" value="<?= $_SESSION['INICIO']['NO_CONCURENTE']; ?>">
          <input type="hidden" name="cedula" id="cedula" value="">
          <div class="mb-3">
            <label for="doc_subirDocumento" class="form-label form-label-sm">Subir un documento <span style="color: red;">*</span></label>
            <input type="file" class="form-control form-control-sm" name="txt_ruta_archivo" id="txt_ruta_archivo" accept=".p12">
          </div>
          <div class="mb-3">
            <label for="cbx_guardarClave" class="form-check-label">¿Quiere guardar su contraseña?</label>
            <input type="checkbox" class="form-check-input form-check-input-sm" name="cbx_guardarClave" id="cbx_guardarClave">
          </div>
          <div class="mb-3" id="seccionClave">
            <label for="txt_clave" class="form-label form-label-sm">
              Contraseña <span style="color: red;">*</span>
            </label>
            <div style="position: relative;">
              <input type="password" class="form-control form-control-sm" name="txt_clave" id="txt_clave" placeholder="Ingrese una contraseña">
              <!-- Ícono para toggle usando Boxicons -->
              <i class="bx bx-show" id="togglePassword" style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%); cursor: pointer;"></i>
            </div>
          </div>

          <div class="mb-3" id="seccionValidar">
            <label for="txt_validarClave" class="form-label form-label-sm">
              Verificar Contraseña <span style="color: red;">*</span>
            </label>
            <div style="position: relative;">
              <input type="password" class="form-control form-control-sm" name="txt_validarClave" id="txt_validarClave" placeholder="Ingrese nuevamente su contraseña">
              <!-- Ícono para toggle usando Boxicons -->
              <i class="bx bx-show" id="togglePasswordConfirm" style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%); cursor: pointer;"></i>
            </div>
          </div>

          <div class="mb-3 text-end">
            <button type="button" class="btn btn-success btn-sm" id="btn_verificar" onclick="verificar_firma();">Verificar</button>
          </div>
          <div id="datos_extras">
            <!-- Si deseas incluir apellido, agrega este campo -->
            <div class="mb-3">
              <label for="txt_nombreFirma" class="form-label form-label-sm">Nombre de la firma <span style="color: red;">*</span></label>
              <input type="text" class="form-control form-control-sm" name="txt_nombreFirma" id="txt_nombreFirma" placeholder="Escriba el nombre de la firma">
            </div>
            <div class="mb-3">
              <label for="txt_identidad" class="form-label form-label-sm">
                Identidad <span style="color: red;">*</span>
              </label>
              <input type="text" class="form-control form-control-sm" name="txt_identidad" id="txt_identidad" placeholder="Escriba su RUC">
            </div>

            <div class="mb-3">
              <label for="ddl_tipo_firma" class="form-label form-label-sm">Tipo de firma</label>
              <select class="form-control form-control-sm" name="ddl_tipo_firma" id="ddl_tipo_firma">
                <option>-- Seleccione el tipo de firma --</option>
                <option value="1">Natural</option>
                <option value="2">Juridica</option>
                <option value="3">RUC</option>
                <option value="4">Generica</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="txt_fecha_expiracion" class="form-label form-label-sm">Fecha Expiración</label>
              <input type="text" class="form-control form-control-sm" name="txt_fecha_expiracion" id="txt_fecha_expiracion" placeholder="Fecha Expiración" disabled>
            </div>

            <div class="mb-3">
              <input class="form-check-input" type="checkbox" id="cbx_politicaDeDatos" name="cbx_politicaDeDatos">
              <label class="form-label" for="cbx_politicaDeDatos">Política de datos</label>
              <label class="error" style="display: none;" for="cbx_politicaDeDatos"></label>
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
        txt_ruta_archivo: {
          required: true
        },
        txt_clave: {
          required: true
        },
        txt_validarClave: {
          required: true,
          equalTo: "#txt_clave"
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
        txt_ruta_archivo: {
          required: "Por favor sube un documento"
        },
        txt_clave: {
          required: "Por favor ingresa una clave"
        },
        txt_validarClave: {
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