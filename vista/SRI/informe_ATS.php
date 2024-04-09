<script type="text/javascript">
 $( document ).ready(function() {
  
 })
  
</script>
<div class="page-wrapper">
      <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-2">
          <div class="breadcrumb-title pe-3">Herramienta</div>
          <div class="ps-3">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Generar ATS</li>
              </ol>
            </nav>
          </div>          
        </div>
        <!--end breadcrumb-->
        <div class="row">
          <div class="col-xl-12 mx-auto">
            <hr class="m-1">
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col-sm-3">
                     <b>carpeta xmls</b><br>
                      <input type="file" name="file_xml" id="file_xml" webkitdirectory directory style="display:none">
                      <label for="file_xml" class="btn btn-sm btn-primary"><i class="bx bx-folder"></i> Seleccionar carpeta</label>
                      <label id="txt_carpeta"></label>                    
                  </div>
                  <div class="col-sm-5">
                     <b>Seleccione un archivo txt</b>
                     <div class="input-group">
                         <form id="form_file">
                          <input type="file" class="form-control form-control-sm" name="file" id="file">
                        </form>       
                      </div>                                          
                  </div>
                  <div class="col-sm-4 text-end">
                    <br>
                        <button type="button" class="btn btn-sm btn-primary" onclick="subir_archivo()">Leer archivo</button>                       
                  </div>


                  <div class="col-sm-4">
                      <b>tipo de documento</b>
                    <div class="input-group">
                      <select id="ddl_tipo" name="ddl_tipo" class="form-select form-select-sm">
                        <option value="">Seleccione documento</option>
                      </select>           
                      <button type="button" class="btn btn-sm btn-primary" onclick="filtrar_documentos()">filtrar</button>    
                    </div>                     
                  </div>
                  <div class="col-sm-8 text-end">
                    <br>
                    <button class="btn btn-sm btn-primary" onclick="$('#myModal_resumen').modal('show')" >Ver resumen</button>
                  </div>
                </div>
                              
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12" id="tbl_datos">
                <div class="card">
                    <div class="card-body">
                      
                    </div>
                </div>
                
              </div>
            </div>
            <!-- <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col-sm-12">
                    <table class="table table-hover" style="font-size: 10px;font-family: sans-serif; border-spacing: 1.5px;">
                        <tbody id="tbl_datoss">          
                            
                        </tbody>
                    </table>        
                    
                  </div>
                </div>
              </div>
            </div> -->
          </div>
        </div>
        <!--end row-->
      </div>
    </div>

<div class="modal fade" id="myModal_resumen" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
        </div>
        <div class="modal-body">
            <div class="row">
              <b>Total Facturas</b>
              <div class="col-sm-12">
                <table class="table table-hover">
                    <tr>
                        <th>%</th>
                        <th>SubTotal</th>
                        <th>iva</th>
                        <th>Total</th>
                    </tr>                    
                    <tr>
                        <td>12%</td><td id="lbl_com">0</td><td id="lbl_iva">0.00</td><td id="lbl_total_con_iva">0</td>
                    </tr>
                    <tr>                      
                        <td>0%</td><td id="lbl_sin">0</td><td id="">0.00</td><td id="lbl_total_sin_iva">0</td>
                    </tr>
                    <tr>                      
                        <td></td><td></td><td><b>Total</b></td><td id="lbl_total_todo">0</td>
                    </tr>
                </table>

                <!--   <b>Facturas</b>
                    <table class="table table-hover">
                        <tr>
                            <th>Total iva</th>
                            <th>Total Sin iva</th>
                            <th>Total Con iva</th>
                        </tr>
                        <tr>
                            <td id="lbl_imp">0</td>
                            <td id="lbl_sin">0</td>
                            <td id="lbl_con">0</td>
                        </tr>
                    </table> -->
                    <b>Retenciones</b>
                    <table class="table table-hover">
                        <tr>
                            <th>% Retencion</th>
                            <th> Valor</th>
                        </tr>
                        <tbody  id="retenciones_val">
                          
                        </tbody>                          
                    </table>                    
                </div>        
              
            </div> 
        </div>
        <div class="modal-footer">
                  <!-- <a type="button" class="btn btn-primary" href="#" id="doc_xml">Descargar xml</button>         -->
                  <!-- <button type="button" class="btn btn-default" onclick="location.reload();">Cerrar</button> -->
                   <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cerrar</button>
              </div>
      </div>
    </div>
</div>

<script type="text/javascript">
    const folderInput = document.getElementById('file_xml');
    folderInput.addEventListener('change', async (event) => {
        await eliminar_xml();
      const selectedFiles = event.target.files;
      if (selectedFiles.length > 0) {
        subir_archivo_xml();
        const folderPath = selectedFiles[0].webkitRelativePath.split('/')[0];
        $('#txt_carpeta').text(folderPath);
        // console.log('Carpeta seleccionada: ' + folderPath);
        // console.log(selectedFiles);
      }
    });


  function  subir_archivo()
  {
    $('#tbl_datos').html('<tr><td colspan="7"><div class="card-body">'+
                '<div class="spinner-grow text-primary" role="status"> <span class="visually-hidden">Loading...</span>'+
                '</div>'+
                '<div class="spinner-grow text-secondary" role="status"> <span class="visually-hidden">Loading...</span>'+
                '</div>'+
                '<div class="spinner-grow text-success" role="status"> <span class="visually-hidden">Loading...</span>'+
                '</div>'+
                '<div class="spinner-grow text-danger" role="status"> <span class="visually-hidden">Loading...</span>'+
                '</div>'+
                '<div class="spinner-grow text-warning" role="status"> <span class="visually-hidden">Loading...</span>'+
                '</div>'+
                '<div class="spinner-grow text-info" role="status"> <span class="visually-hidden">Loading...</span>'+
                '</div>'+                
                '<div class="spinner-grow text-dark" role="status"> <span class="visually-hidden">Loading...</span>'+
                '</div>'+
              '</div>'+
            '</td>'+
            '</tr>')
    var fi = $('#file').val();
      if(fi != '')
      {
            var formData = new FormData(document.getElementById("form_file"));
            $.ajax({
                url: '../controlador/calcularATS.php?subir_archivo_server=true',
                type: 'post',
                data: formData,
                contentType: false,
                processData: false,
                dataType:'json',
             // beforeSend: function () {
             //        $("#foto_alumno").attr('src',"../../img/gif/proce.gif");
             //     },
                success: function(response) {
                  if(response == 1)
                  {
                    calcular()
                  }else
                  {
                    Alert( 'Formato del archivo incorrecto','asegurese que el archivo sea (.cvs)','error');

                  }
                }
            });
      }else
      {
         alert('Destino o archivo no seleccionados');
      }
  } 

  function  subir_archivo_xml(start=0)
  {
    // $('#tbl_datos').html('<tr><td><img src="img/ZZ5H.gif"></td></tr>')
    var fi = $('#file_xml').val();
      if(fi != '')
      {
            const fileInput = document.getElementById('file_xml');
            const files = fileInput.files;
            const formData = new FormData();
            const batchSize = 10;

            // Agregar los archivos seleccionados al FormData
             for (let i = start; i < files.length && i < start + batchSize; i++) {
                        formData.append('files[]', files[i]);
                    }
            console.log(files);
            console.log(formData);

            $.ajax({
                url: '../controlador/calcularATS.php?subir_archivo_xml_server=true',
                type: 'post',
                data: formData,
                processData: false,
                contentType: false,
                dataType:'json',
             // beforeSend: function () {
             //        $("#foto_alumno").attr('src',"../../img/gif/proce.gif");
             //     },
                success: function(response) {
                  if (start + batchSize < files.length) {
                        subir_archivo_xml(start + batchSize);
                    }else{
                        // alert('xml subidos')
                    }
                }
            });
      }else
      {
         alert('Destino o archivo no seleccionados');
      }
  }

    function  eliminar_xml()
    {          

        $.ajax({
            url: '../controlador/calcularATS.php?eliminar_xml=true',
            type: 'post',
            // data: formData,
            processData: false,
            contentType: false,
            dataType:'json',
         // beforeSend: function () {
         //        $("#foto_alumno").attr('src',"../../img/gif/proce.gif");
         //     },
            success: function(response) {
              if(response==1)
              {
                // alert("Eliminado xml anteriores");
              }
            }
        });
      
    } 

  function calcular()
  {
    var parametros = {'f':'d'}
    $.ajax({
         data:  {parametros:parametros},
         url:   '../controlador/calcularATS.php?calcularexcel=true',
         type:  'post',
         dataType: 'json',         
           success:  function (response) { 
            // console.log(response);
            $("#lbl_com").text(parseFloat(response.sub_con_iva).toFixed(2));
            $("#lbl_sin").text(parseFloat(response.sub_sin_iva).toFixed(2));
            $("#lbl_iva").text(parseFloat(response.iva_total).toFixed(2));
            $("#lbl_total_con_iva").text(parseFloat(response.total_con_iva).toFixed(2));
            $("#lbl_total_sin_iva").text(parseFloat(response.sub_sin_iva).toFixed(2));
            $('#lbl_total_todo').text(parseFloat(response.total).toFixed(2))
            $('#tbl_datos').html(response.tr);
           

            // response.Retencion_val
             $('#retenciones_val').html('')
            for (var clave in response.Retencion_val) {
              if (response.Retencion_val.hasOwnProperty(clave)) {
                var valor = response.Retencion_val[clave];
                $('#retenciones_val').append('<tr><td>'+clave+'</td><td>'+valor+'</td></tr>');
                // console.log("Clave: " + clave + ", Valor: " + valor);
              }
            }

            // console.log(response.Retencion_val);

            // console.log(response.tipo);
           var opt = '';
            response.tipo.forEach(function(item,i){
            // console.log(item);
              opt+= '<option value="'+item+'">'+item+'</option>'
           })

            $('#ddl_tipo').html(opt);
          
          } 
          
       });
  }

  function filtrar_documentos()
  {
    var parametros = {'tipo':$('#ddl_tipo').val(),}
    $.ajax({
         data:  {parametros:parametros},
         url:   '../controlador/calcularATS.php?filtrar_doc=true',
         type:  'post',
         dataType: 'json',         
           success:  function (response) { 
            // console.log(response);
            
            $('#tbl_datos').html(response.tr);

            // console.log(response.tipo);
           var opt = '';
            response.tipo.forEach(function(item,i){
            // console.log(item);
              opt+= '<option value="'+item+'">'+item+'</option>'
           })

            $('#ddl_tipo').html(opt);
          
          } 
          
       });
  }
</script>