<?php include('./header.php'); ?>
<link rel="stylesheet" href="../css/style_agenda.css">
<script type="text/javascript"></script>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
   <section class="content">
      <div class="container-fluid">
<!-- partial:index.partial.html -->

  <div class="card">
    <div class="card-body p-0">
      <div id="calendar"></div>
    </div>
  </div>

<!-- calendar modal -->
<div id="modal-view-event" class="modal modal-top fade calendar-modal">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body">
          <h4 class="modal-title"><span class="event-icon"></span><span class="event-title"></span></h4>
          <div class="event-body"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

<div id="modal-view-event-add" class="modal modal-top fade calendar-modal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="add-event">
        <div class="modal-body">
          <h4>Crear evento</h4>     
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group">
                <label>Nombre del evento</label>
                <input type="text" class="form-control" name="ename">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label>Inicio del evento</label>
                <input type='datetime-local' class="form-control" name="date">
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label>Fin del evento</label>
                <input type='datetime-local' class="form-control" name="date">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group">
                <label>Detalle del evento</label>
                <textarea class="form-control" name="edesc"></textarea>
              </div>
            </div>            
          </div>     
      </div>
        <div class="modal-footer">
        <button type="submit" class="btn btn-primary" >Guardar</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>        
      </div>
      </form>
    </div>
  </div>
</div>

                   

      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

<!-- <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script> -->
<script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js'></script>
<!-- <script src='https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js'></script> -->
<script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/js/datepicker.js'></script>
<!-- <script src='https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/js/i18n/datepicker.en.js'></script> -->
<!-- <script src=' https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.9.1/lang-all.js'></script> -->
<script  src="../js/locale.js"></script>
<script  src="../js/script_agenda.js"></script>



<?php include('./footer.php'); ?>
