<?php include('./header3.php');
$id = '';
if (isset($_GET['id'])) {
  $id = $_GET['id'];
} ?>

<script type="text/javascript">
  $(document).ready(function() {
    var art = '<?php echo $id; ?>';
    if (art != '') {
      cargar_tarjeta(art);
    }
  });

  function cargar_tarjeta(id) {
    $.ajax({
      data: {
        id: id
      },
      url: '../controlador/ACTIVOS_FIJOS/detalle_articuloC.php?cargar_tarjeta=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        if (response != '') {
          $('.textarea').html(response[0].HTML_INFO);
          $('#txt_id_tarjeta').val(response[0].id_tarjeta);
          console.log(response);

        }

      }
    })

  }
</script>

<div class="wrapper">
  <div class="error-404 d-flex align-items-center justify-content-center">
    <div class="container" style="margin-top: 5%;">
      <div class="card py-5">
        <div class="row g-0">
          <div class="col col-xl-12">
            <div class="card-body p-4">
              <input type="hidden" name="txt_id_tarjeta" id="txt_id_tarjeta">
              <div class="mb-3 textarea">
                <p>Edite tarjeta informativa
                <p>
              </div>
            </div>
          </div>
        </div>
        <!--end row-->
      </div>
    </div>
  </div>
</div>

<script>
  // $(function () {
  //   // Summernote
  //   $('.textarea').summernote({
  //   	height: 500,
  //   })
  // })

  function Editar() {
    $('.textarea').summernote({
      focus: true,
      height: 500
    });
  };

  function Guardar() {
    var markup = $('.textarea').summernote('code');
    var id_t = $('#txt_id_tarjeta').val();
    var id = '<?php echo $id; ?>';
    var parametros = {
      'articulo': id,
      'tarjeta': markup,
      'id_tarjeta': id_t,
    }
    $.ajax({
      data: {
        parametros: parametros
      },
      url: '../controlador/ACTIVOS_FIJOS/detalle_articuloC.php?tarjeta_guardar=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        $('.textarea').summernote('destroy');
        cargar_tarjeta(id);
      }
    });

  };
</script>

<?php include('footer3.php');
