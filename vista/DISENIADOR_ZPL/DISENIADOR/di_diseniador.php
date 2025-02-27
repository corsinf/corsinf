<script type="text/javascript">
    $(document).ready(function() {

        $('#modal_buscar_impresoras').modal('show')

        dataExcel = [];
        document.getElementById('archivoExcel').addEventListener('change', function (e) {
            // Obtener el archivo seleccionado
            const archivo = e.target.files[0];

            // Verificar si se seleccionó un archivo
            if (!archivo) {
                console.log("No se seleccionó ningún archivo.");
                return;
            }

            // Crear un FileReader para leer el archivo
            const reader = new FileReader();

            // Definir qué hacer cuando el archivo se haya leído
            reader.onload = function (e) {
                const data = new Uint8Array(e.target.result);
                const workbook = XLSX.read(data, { type: 'array' });

                // Obtener la primera hoja del archivo
                const primeraHoja = workbook.Sheets[workbook.SheetNames[0]];

                // Convertir la hoja a JSON
                const jsonData = XLSX.utils.sheet_to_json(primeraHoja, { header: 1 });
                 // Filtrar filas vacías
                dataExcel = jsonData.filter(fila => {
                    return fila.some(celda => celda !== null && celda !== '');
                });

                // Mostrar los datos filtrados en la consola
                console.log(dataExcel);
            };

            // Leer el archivo como un array binario
            reader.readAsArrayBuffer(archivo);
        });

    });
</script>


<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/themes/smoothness/jquery-ui.css">
<script src="../js/xlsx.full.min.js"></script>
<!-- <script language="javascript" type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script> -->

<link rel="stylesheet" href="../lib/ZPL/css/designer.css">
<script language="javascript" type="text/javascript" src="../js/DISENIADOR/impresora.js"></script>
<script language="javascript" type="text/javascript" src="../lib/ZPL/js/designer.js"></script>
<script language="javascript" type="text/javascript" src="../lib/ZPL/js/designer_propertyInspector.js"></script>
<script language="javascript" type="text/javascript" src="../lib/ZPL/js/designer_toolbar.js"></script>
<script language="javascript" type="text/javascript" src="../lib/ZPL/js/designer_labelInspector.js"></script>
<script language="javascript" type="text/javascript" src="../lib/ZPL/js/labelControls/LabelSize.js"></script>
<script language="javascript" type="text/javascript" src="../lib/ZPL/js/labelControls/GenerateZPL.js"></script>
<script language="javascript" type="text/javascript" src="../lib/ZPL/js/lindell-barcode/JsBarcode.all.min.js"></script>
<script language="javascript" type="text/javascript" src="../lib/ZPL/js/lindell-barcode/qrcode.min.js"></script>
<script language="javascript" type="text/javascript" src="../lib/ZPL/js/tools/rectangle.js"></script>
<script language="javascript" type="text/javascript" src="../lib/ZPL/js/tools/barcode.js"></script>
<script language="javascript" type="text/javascript" src="../lib/ZPL/js/tools/text.js"></script>
<script language="javascript" type="text/javascript" src="../lib/ZPL/js/tools/image.js"></script>
<script language="javascript" type="text/javascript" src="../lib/ZPL/js/tools/QRcode.js"></script>
<script language="javascript" type="text/javascript" src="../lib/ZPL/js/tools/DBdata.js"></script>

<script>
    var canvasDesigner = null;
    let textoRfid = [];
    var zplCode;
    $(document).ready(function() {

        canvasDesigner = new com.logicpartners.labelDesigner('labelDesigner', 1.75, 0.75);
         canvasDesigner.labelInspector.addTool(new com.logicpartners.labelControl.size(canvasDesigner));
        canvasDesigner.labelInspector.addTool(new com.logicpartners.labelControl.generatezpl(canvasDesigner));

       
        canvasDesigner.toolbar.addTool(new com.logicpartners.designerTools.text());
        canvasDesigner.toolbar.addTool(new com.logicpartners.designerTools.rectangle());
        canvasDesigner.toolbar.addTool(new com.logicpartners.designerTools.barcode());
        canvasDesigner.toolbar.addTool(new com.logicpartners.designerTools.image());
        canvasDesigner.toolbar.addTool(new com.logicpartners.designerTools.QRcode());
        canvasDesigner.toolbar.addTool(new com.logicpartners.designerTools.DBdata());

        // canvasDesigner.addRectangle(50, 50, 300, 50);
        // canvasDesigner.addRectangle(100, 100, 50, 50);
        size = $('#pnl_size')
        detalle = $('#pnl_detalles')
        tools = $('#pnl_tools')

         $('#pnl_new_size').append(size)
         $('#pnl_new_detalles').append(detalle)
         $('#pnl_new_tools').append(tools)

        // $('#pnl_size').empty()

        getDBtable();

          $('#txt_rfid_simple').autocomplete({
           source: textoRfid, 
            select: function(event, ui) {
                $('#txt_rfid_simple').val(ui.item.label); // Asignar el valor seleccionado
                // $('#txt_tipo_usuario_new').val(ui.item.label);   // Asignar la etiqueta seleccionada
                return false;
            },
            focus: function(event, ui) {
                $('#txt_rfid_simple').val(ui.item.label);   // Mostrar la etiqueta al enfocar
                return false;
            },
            open: function(event, ui) {
                // Imprimir el contenido del source en la consola
                console.log('Contenido de textoRfid:', textoRfid);
            }
        });

    });

  
</script>


<script>

    function cargar_autoText()
    {
        textoRfid = []
         // $('#txt_rfid_simple').autocomplete('option', 'source', []);
        elem = canvasDesigner.getElementos();
        elem.forEach(function(item,i){
             elem = elem.filter(elemento => elemento !== null);
            textoRfid.push({'value':(i+1),'label':item.text})
        })
       
        $('#txt_rfid_simple').autocomplete('option', 'source', textoRfid);
    }

    var DatosOrigen = [];
    function vista_previa(datos='')
    {
        lista_impresora();
        let canvas = document.getElementById("labelDesigner");
        let div = document.getElementById("previer");
               // Aplicar el tamaño del canvas al div previer
           
        let img = new Image();
        img.src = canvas.toDataURL("image/png"); // Obtener la imagen del canvas
        img.style.width = "100%";  // Ajustar tamaño al contenedor
        img.style.height = "100%"; // Ajustar tamaño al contenedor
        img.style.objectFit = "contain"; // Mantener proporciones

        // Limpiar el contenedor antes de agregar la nueva imagen
        div.innerHTML = "";
        div.appendChild(img);

        data =  canvasDesigner.getElementos();
        console.log(data)
        if(yaTieneOrigendatos==0)
        {
            var rfid = 0;
            var head='<thead>';
            var body = '<tbody><tr>'
            var datoRfid = '';
            // console.log(data)
            if(data.length !== 0 && data !== null)
            {
                data.forEach(function(item,i){
                    head+='<th>'+item.name+'</th>'
                     body+='<td>'+item.text+'</td>'
                    console.log(item)
                })
                if($('#rbl_rfid_simple').prop('checked'))
                {
                    if($('#txt_rfid_simple').val()=='')
                    {
                        Swal.fire("El texto para RFID esta vacio","si desea codificar RFID este debe estar lleno","info")
                        return false;
                    }else{
                        head+='<th>RFID</th>'
                        body+='<td>'+$('#txt_rfid_simple').val()+'</td>'
                    }
                }
                head+='</thead>'
                body+= '</tr></tbody>'
                $('#tbl_datos').html(head+body);
                $('#modal_impresion').modal('show');
            }else
            {
                $('#tbl_datos').html('<tr><td>Sin datos a imprimir</td></tr>')
            }

        }else
        {
           console.log(datos);
           var origen = [];
          if(data.length !== 0 && data !== null)
            {
                 data.forEach(function(item,i){
                        head+='<th>'+item.name+'</th>'
                })
                if(datos.rfid.length>0)
                {
                    head+='<th>RFID</th>'
                }
                datos.principal.forEach(function(item,i){
                    linea = {};
                    body+='<tr>'
                    data.forEach(function(item2,j){
                        elemento = item2.name.replaceAll(' ','_');
                        valor = item[elemento];
                        body+='<td>'+valor+'</td>'
                        linea[elemento] = valor;

                    })
                    if(datos.rfid.length>0)
                    {
                        body+='<td>'+datos.rfid[i]['rfid']+'</td>'
                        linea['rfid'] = datos.rfid[i]['rfid'];
                    }

                    origen.push(linea);
                    body+='</tr>'
                })

                // console.log(origen);

                DatosOrigen = origen;
               
                // head+='</thead>'
                // body+= '</tr></tbody>'
                $('#tbl_datos').html('<thead>'+head+'</thead><tbody>'+body+'</body>');
                $('#modal_impresion').modal('show');
            }else
            {
                $('#tbl_datos').html('<tr><td>Sin datos a imprimir</td></tr>')
            }

        }




    }

    function cargarOrigendatos()
    {
        console.log(DatosOrigen);        
        data =  canvasDesigner.getElementos();
        DatosOrigen.forEach(function(item,i){
            data.forEach(function(item2,j){
                elemento = item2.name.replaceAll(' ','_');
                item2.text = item[elemento];
                // console.log(item);
                if ('rfid' in item) {
                   $('#rbl_rfid_simple').prop('checked',true)
                   $('#txt_rfid_simple').val(item['rfid'])
                } 
            })
            data1 =  canvasDesigner.generateZPL();            
            $('#rbl_rfid_simple').prop('checked',false)
             impresion_simple(i,data1)
        })
    }


    function imprimir_final()
    {
        canvasDesigner.update();

        data =  canvasDesigner.getElementos();
        if(data.length === 0 || data === null)
        {
            Swal.fire("Etiqueta vacia","no se puede eliminar una etiqueta vacia","error")
            return false;
        }

        if(yaTieneOrigendatos==1)
        {
            cargarOrigendatos()
        }else
        {
           data =  canvasDesigner.generateZPL();

           // console.log(data);
           // return false;
           impresion_simple(1,data);
           $('#modal_impresion').modal('hide')
        }

    }


    function impresion_simple(i=1,data)
    {
        var parametros = 
        {
            'indice':i,
            'code':data,
            'impresora':$('#ddl_impresora').val(),
        }

         $.ajax({
          data:  {parametros:parametros},
          url:  '../controlador/DISENIADOR_ZPL/di_diseniadorC.php?imprimirTag=true',
          type:  'post',
          dataType: 'json',
          /*beforeSend: function () {   
               var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
             $('#tabla_').html(spiner);
          },*/
            success:  function (response) {
                 setTimeout(() => {                    
                   $('#modal_print').modal('hide');             
                   // $('#ddl_informes').html(response); 
                }, 2000);
          },
            error: function (error) {
                 setTimeout(() => {                    
                   $('#modal_print').modal('hide');
                }, 2000);
                
            },
        });

    }


  

    let yaTieneCodificacion = 0;
    let yaTieneOrigendatos = 0;
    let copyElemeto = '';
    let OrigenDatos = "";

   

    function modal_datos()
    {
        yaTieneOrigendatos = 1;
        opciondb()
        $('#modal_datos').modal('show');
       }

    function addData()
    {

        data = $('#form_origenes_data').serialize();
        data = data+'&ddl_tabla2='+$('#ddl_tabla2').val();
        $.ajax({
        data:  {data:data},
        url:  '../controlador/DISENIADOR_ZPL/di_diseniadorC.php?addDatos=true',
        type:  'post',
        dataType: 'json',
        success:  function (response) {
            if(response=='-2')
            {
                Swal.fire('La catidad de datos y RFID deben coincidir','','error')
            }else
            {

               $('#modal_datos').modal('hide');
                vista_previa(response);
            }
            
          },
            error: function (error) {
                
               $('#modal_print').modal('hide');
              console.error('Error en numero_comprobante:', error);
              // Puedes manejar el error aquí si es necesario
            },
        });
    }

    function cerrarDatos()
    {
        $('#modal_datos').modal('hide');
    }

    function saliDatos()
    {
         yaTieneOrigendatos = 0;
        $('#modal_datos').modal('hide');
    }

    function eliminar_origen(valor)
    {
        $('#btn_origen_delete_'+valor).css('display','none')
        $('#btn_origen_add_'+valor).css('display','block')
        $('#txt_textCode_'+valor).prop('readonly',false);
        $('#txt_textCode_'+valor).css('background-color','#ffffff');
        $('#txt_origen_datos').val(0);
        $('#txt_lista').val("");
        OrigenDatos = 0;
        yaTieneOrigendatos = 0;
        canvasDesigner.update()
        eliminar_etiquetas_ante();
    }

    function  eliminar_etiquetas_ante()
    {
         $.ajax({
          // data:  {datos:datos},
          url:  '../controlador/DISENIADOR_ZPL/di_diseniadorC.php?EliminarAnte=true',
          type:  'post',
          dataType: 'json',
          /*beforeSend: function () {   
               var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
             $('#tabla_').html(spiner);
          },*/
            success:  function (response) {
                if(response==1)
                {
                    Swal.fire("Etiquetas eliminadas","","success").then(function(){
                        canvasDesigner.update()
                    })

                }
          },
            error: function (error) {
                
               $('#modal_print').modal('hide');
              console.error('Error en numero_comprobante:', error);
              // Puedes manejar el error aquí si es necesario
            },
        });

    }

    function copy_rfid(elem)
    {
        data = $('#txt_lista_'+elem).val();
        console.log(elem)
        console.log(copyElemeto)
        if(elem==copyElemeto)
        {
            $('#txt_lista_rfid').val(data);
        }
    }

    function buscar_camposdb()
    {
         elem = canvasDesigner.getElementos();
         parametros = 
         {
            'tabla':$('#ddl_tabla').val(),
         }
         $.ajax({
            url:  '../controlador/DISENIADOR_ZPL/di_diseniadorC.php?getDBcampos=true',
            data:{parametros:parametros},
            type:  'post',
            dataType: 'json',          
            success:  function (response) {
                 var op = '<option value="">Seleccione campo</option>';
                    response.forEach(function(item,i){
                        op+='<option value="'+item.col+'">'+item.col+'</option>'
                    })

                elem = elem.filter(elemento => elemento !== null);
                elem.forEach(function(item,i){                   
                    $('#ddl_'+item.name.replaceAll(' ','_')).html(op)
                })

                $('#ddl_rfid_code').html(op)
              
            },
            error: function (error) {
                
               $('#modal_print').modal('hide');
              console.error('Error en numero_comprobante:', error);
              // Puedes manejar el error aquí si es necesario
            },
        });
    }

    function llenar_ddl_camposdb(elemento)
    {
         parametros = 
         {
            'tabla':$('#ddl_tabla').val(),
         }
         $.ajax({
            url:  '../controlador/DISENIADOR_ZPL/di_diseniadorC.php?getDBcampos=true',
            data:{parametros:parametros},
            type:  'post',
            dataType: 'json',          
            success:  function (response) {
                 var op = '<option value="">Seleccione campo</option>';
                    response.forEach(function(item,i){
                        op+='<option value="'+item.col+'">'+item.col+'</option>'
                    })

                $('#'+elemento).html(op)
              
            },
            error: function (error) {
                
               $('#modal_print').modal('hide');
              console.error('Error en numero_comprobante:', error);
              // Puedes manejar el error aquí si es necesario
            },
        });
    }

     function buscar_camposdb2()
    {
         parametros = 
         {
            'tabla':$('#ddl_tabla2').val(),
         }
         $.ajax({
            url:  '../controlador/DISENIADOR_ZPL/di_diseniadorC.php?getDBcampos=true',
            data:{parametros:parametros},
            type:  'post',
            dataType: 'json',          
            success:  function (response) {
                $('#modal_cambiar_tabla').modal('hide')
                 var op = '<option value="">Seleccione campo</option>';
                    response.forEach(function(item,i){
                        op+='<option value="'+item.col+'">'+item.col+'</option>'
                    })
                $('#ddl_rfid_code').html(op)
              
            },
            error: function (error) {
                
                $('#modal_cambiar_tabla').modal('hide')
              console.error('Error en numero_comprobante:', error);
              // Puedes manejar el error aquí si es necesario
            },
        });
    }

    function opciondb()
    {
        elem = canvasDesigner.getElementos();
        elem = elem.filter(elemento => elemento !== null);
         console.log(elem)
         tr = '';
         tr2 = '';
         nuevo = 1;
        elem.forEach(function(item,i){
            console.log($('#tr_'+item.name.replaceAll(' ','_')).length)
            
                if ($('#tr_'+item.name.replaceAll(' ','_')).length == 0) {                   
                    tr ='<tr id="tr_'+item.name.replaceAll(' ','_')+'"><td>'+item.name+'</td><td><select class="form-select form-select-sm" id="ddl_'+item.name.replaceAll(' ','_')+'" name="ddl_'+item.name.replaceAll(' ','_')+'"><option>Seleccione campo<option></select></td><td></td></tr>'
                    $('#tbl_db_data').append(tr);
                    if($('#ddl_tabla').val()!='')
                    {
                        llenar_ddl_camposdb('ddl_'+item.name.replaceAll(' ','_'));
                    }
                }
        })
    }

    function rfid_automatico()
    {
        if($('#rbx_rfid_automatico').prop('checked'))
        {
            $('#ddl_rfid_code').prop('disabled',true)
            $('#ddl_rfid_code').val('')
            $('#btn_cambiar_tabla').prop('disabled',true)
            console.log('si')
        }else
        {

            $('#ddl_rfid_code').prop('disabled',false)
            $('#ddl_rfid_code').val('')
            $('#btn_cambiar_tabla').prop('disabled',false)
            console.log('no')
        }

    }

    function getDBtable()
    {
        $.ajax({
        url:  '../controlador/DISENIADOR_ZPL/di_diseniadorC.php?getDBtable=true',
        type:  'post',
        dataType: 'json',          
        success:  function (response) {
           var op = '<option value="">Seleccione una tabla</option>';
           response.forEach(function(item,i){
            op+='<option value="'+item.TABLE_NAME+'">'+item.TABLE_NAME+'</option>'
           })
           $('#ddl_tabla').html(op)
           $('#ddl_tabla2').html(op)
        },
        error: function (error) {
            
           $('#modal_print').modal('hide');
          console.error('Error en numero_comprobante:', error);
          // Puedes manejar el error aquí si es necesario
        },
    });
    }

    function cargarExcel()
    {
         $.ajax({
            url:  '../controlador/DISENIADOR_ZPL/di_diseniadorC.php?desde_excel=true',
            type:  'post',
            dataType: 'json',          
            success:  function (response) {
              console.log(response)
            },
            error: function (error) {
                
               $('#modal_print').modal('hide');
              console.error('Error en numero_comprobante:', error);
              // Puedes manejar el error aquí si es necesario
            },
        });

    }

    function show_rfid()
    {
        if($('#rbl_rfid').prop('checked'))
        {
            console.log('si')
            $('#tbl_rfid').removeClass('d-none')
        }else
        {
            console.log('no')
            $('#tbl_rfid').addClass('d-none')
        }
    }

    function cambiar_metodo()
    {
        metodo = $('#ddl_metodo_busqueda').val()
        if(metodo=='MULTICAST_BROADCAST')
        {
            $('#pnl_host').removeClass('d-none')
        }else
        {
            $('#pnl_host').addClass('d-none')
        }
    }


    function prueba()
    {
        $.ajax({
            url: 'http://localhost:3000', // Dirección del servidor en Java
            type: 'POST', // Método HTTP
            contentType: 'application/json', // Tipo de contenido enviado
            data: JSON.stringify({
                comando: 'imprimir',
                datos: '^XA^FO50,50^A0N,50,50^FDHello, World!^FS^XZ'
            }),
            success: function(response) {
                console.log('Respuesta del servidor:', response);
            },
            error: function(xhr, status, error) {
                console.error('Error en la solicitud:', status, error);
            }
        });
    }


</script>


<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Blank</div>
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

        <div class="row p-2">
            <div class="col-sm-12">
                <button class="btn-sm btn-success btn" onclick="vista_previa()"><i class="bx bx-print"></i> Imprimir</button>
                 <button class="btn-sm btn-success btn" onclick="cargarExcel()"><i class="bx bx-print"></i> excel</button>
                 <button class="btn-sm btn-success btn" onclick="prueba()"><i class="bx bx-print"></i> excel</button>
            </div>    
        </div>
        <div class="row">
            <div class="card mb-1">
                <div class="card-body p-1">
                    <div class="col-12" id="pnl_new_size">                                
                    </div>   
                </div>                
            </div>                     
        </div>

        <div class="row">
             <div class="col-sm-3" > 
                <div class="card">
                    <div class="card-body p-0">
                        <div class="row">
                             <div class="col-12 mb-1">
                                <div class="accordion accordion-flush" id="accordionFlushExample">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="flush-headingOne">
                                          <button class="accordion-button collapsed designerPropertyTitle" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne"><label class=""><input type="checkbox" class="" id="rbl_rfid_simple" name="rbl_rfid_simple">  Codificar RFID ?</label>
                                          </button>
                                        </h2>
                                        <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample" style="">
                                            <div class="accordion-body" style="border: 2px solid #169DD9;">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <b>Texto a codificar</b>
                                                        <input type="" name="txt_rfid_simple" id="txt_rfid_simple" class="form-control form-control-sm" onfocus="cargar_autoText()">
                                                    </div>                                                  
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>                                
                            </div>
                            <div class="col-sm-12" id="pnl_new_detalles">
                                
                            </div>
                        </div>
                        <div class="row" id="pnl_boton">

                        </div>
                    </div>
                </div>    
            </div>   
            <div class="col-sm-9">
                <div class="card">
                    <div class="card-body">
                        <div class="row">                            
                            <div class="col-12" id="pnl_new_tools">
                                
                            </div>
                                                   
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <canvas id="labelDesigner" tabindex="1" width="800" height="900"
                                style="border: 1px solid #000000;">
                                </canvas>                                
                            </div>
                        </div>
                    </div>
                </div>    
            </div>
                    
        </div>

      
        
        <!--end row-->
    </div>
</div>


<div class="modal" id="modal_datos" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <!-- <button type="button" class="btn-close" data-bs-dismiss="modal"></button> -->
                <h3>Origen de datos</h3>
            </div>

            <!-- Modal body -->
            <div class="modal-body">

                <div class="row">
                    <div class="col-sm-12">
                        <ul class="nav nav-tabs">
                          <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#home">Desde Lista</a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#menu1" onclick="opciondb()">Desde Base de datos</a>
                          </li>                         
                        </ul>
                        <div class="tab-content">
                          <div class="tab-pane container active" id="home">
                              <input type="hidden" name="txt_elemento" id="txt_elemento" value="">
                              <div class="row">
                                <input type="file" id="archivoExcel" accept=".xlsx, .xls" />
                              </div>
                              <div class="row">
                                 <div class="col-sm-12 text-end">
                                    <button type="button" class="btn-sm btn btn-success" onclick="addData()">Imprimir</button>
                                    <button type="button" class="btn-sm btn btn-secondary" onclick="cerrarDatos()">Minimizar</button> 
                                    <button type="button" class="btn-sm btn btn-default" onclick="saliDatos()">Salir</button> 
                                </div>                                   
                              </div>
                          </div>
                          <div class="tab-pane container fade" id="menu1">
                            <form id="form_origenes_data">
                                
                                <div class="row mt-1">
                                    <div class="col-sm-10">
                                        <div class="d-flex align-items-center text-danger"> <i class="bx bx-radio-circle-marked bx-burst bx-rotate-90 align-middle font-18 me-1"></i>
                                            <span>Sin conexion a base de datos</span>
                                        </div>                                    
                                    </div>
                                    <div class="col-sm-2">
                                        <button class="btn-sm btn-secondary btn" onclick="$('#modal_conexion_db').modal('show')" ><i class="bx bx-plug m-0"></i></button>
                                        
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <select class="form-select form-select-sm" id="ddl_tabla" name="ddl_tabla" onchange="buscar_camposdb()">
                                            <option value="">Seleccione tabla</option>
                                        </select>                                    
                                    </div>                                
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                       <table class="table table-sm">
                                            <thead>
                                                <th>Elemento</th>
                                                <th>Tomar texto de</th>
                                                <th></th>
                                            </thead>  
                                            <tbody id="tbl_db_data">
                                                
                                            </tbody>                                     
                                       </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label><input type="checkbox" id="rbl_rfid" name="rbl_rfid" onclick="show_rfid()"> Codificacion RFID ?</label>                                    
                                    </div>
                                    <div class="col-sm-12">
                                       <table class="table table-sm d-none" id="tbl_rfid">
                                            <thead>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </thead>  
                                            <tbody>
                                                <tr>
                                                    <td>RFID Code</td>
                                                    <td>
                                                        <div class="input input-group">
                                                            <select id="ddl_rfid_code" name="ddl_rfid_code" class="form-select form-select-sm" readonly>
                                                                <option value="">Seleccione campo<option>
                                                            </select>
                                                            <button type="button" class="btn btn-primary btn-sm" title="Cambiar de tabla" id="btn_cambiar_tabla" onclick="$('#modal_cambiar_tabla').modal('show')"><i class="bx bx-table me-0"></i></button>
                                                        </div>
                                                    </td>
                                                    <td class="p-2">
                                                        <label><input type="checkbox" class="" id="rbx_rfid_automatico" name="rbx_rfid_automatico" onclick="rfid_automatico()">  RFID Automatico</label>
                                                    </td>
                                                </tr>                                            
                                            </tbody>                                     
                                       </table>
                                    </div>                                     
                                </div>
                                <div class="row">
                                    <div class="col-12 text-end">                                    
                                        <button type="button" class="btn-sm btn btn-success" onclick="addData()">Imprimir</button>
                                        <button type="button" class="btn-sm btn btn-secondary" onclick="cerrarDatos()">Minimizar</button> 
                                        <button type="button" class="btn-sm btn btn-default" onclick="saliDatos()">Salir</button> 
                                    </div>
                                </div>

                            </form>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="modal_cambiar_tabla" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <!-- <button type="button" class="btn-close" data-bs-dismiss="modal"></button> -->
                <h3>Tabla para rfid</h3>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">                       
                   <div class="col-sm-12">
                        <select class="form-select form-select-sm" id="ddl_tabla2" onchange="buscar_camposdb2()">
                            <option value="">Seleccione tabla</option>
                        </select>                                    
                    </div>                                              
                </div>               
            </div>
            <div class="modal-footer">
                <button class="btn-sm btn btn-success" onclick="">Aceptar</button>
                <button class="btn-sm btn btn-secondary" onclick="$('#modal_cambiar_tabla').modal('hide')">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="modal_impresion" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <!-- <button type="button" class="btn-close" data-bs-dismiss="modal"></button> -->
                <h3>Detalles de impression</h3>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">                       
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-12">
                                <b>impresora</b>
                                <div class="input-group input">
                                     <select class="form-select form-select-sm" id="ddl_impresora" name="ddl_impresora">
                                        <option value=""> Seleccione impresora</option>
                                    </select>
                                    <button class="btn-sm btn-primary btn" onclick="$('#modal_buscar_impresoras').modal('show')"><i class="bx bx-search"></i></button>
                                    
                                </div>
                               
                            </div>
                            <div class="col-12">
                                <b>Datos a imprimir</b>
                                <input type="hidden" name="" id="txt_origen_datos" value="0">
                               <table class="table table-sm" id="tbl_datos">
                                </table>
                            </div>
                        </div>
                    </div> 
                    <div class="col-sm-6">
                        <b>vista previa</b>
                        <div class="row" id="previer">
                            
                        </div> 
                         <div class="row">
                            <div class="col-12 text-end">
                                    <button class="btn-sm btn btn-success" onclick="imprimir_final()">Imprimir</button>
                                    <button class="btn-sm btn btn-secondary" onclick="$('#modal_impresion').modal('hide')">Cancelar</button>
                            </div>
                           
                        </div>                          
                    </div>                                 
                </div>
               
            </div>
        </div>
    </div>
</div>

<div class="modal" id="modal_conexion_db" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Conexion a base</h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-8">
                        <b>Host</b>
                        <input type="" name="" id="" class="form-control form-control-sm" value="<?php echo $_SESSION['INICIO']['IP_HOST']; ?>" >                  
                    </div>
                    <div class="col-sm-4">
                        <b>Port</b>
                        <input type="" name="" id="" class="form-control form-control-sm" value="<?php echo $_SESSION['INICIO']['PUERTO_DB']; ?>" >                  
                    </div>
                    <div class="col-sm-6">
                        <b>Usuario</b>
                        <input type="" name="" id="" class="form-control form-control-sm" value="<?php echo $_SESSION['INICIO']['USUARIO_DB']; ?>" >                  
                    </div>
                    <div class="col-sm-6">
                        <b>Password</b>
                        <input type="" name="" id="" class="form-control form-control-sm"  value="<?php echo $_SESSION['INICIO']['PASSWORD_DB']; ?>" >                 
                    </div>
                    <div class="col-sm-4">
                        <br>
                        <button class="btn-sm btn-primary btn">Probar conectar</button>                 
                    </div>
                    <div class="col-sm-8">
                         <b>Base</b>
                        <input type="" name="" id="" class="form-control form-control-sm"  value="<?php echo $_SESSION['INICIO']['BASEDATO']; ?>" >                  
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-sm btn-primary btn">Cerrar</button>
                <button class="btn-sm btn-primary btn">Guardar</button>                
            </div>
        </div>
    </div>
</div>
<div class="modal" id="modal_buscar_impresoras" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4>Buscar Impresoras</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="row">
                             <div class="col-sm-12">
                                <b>Metodo</b>
                               <select id="ddl_metodo_busqueda" class="form-select form-select-sm" onchange="cambiar_metodo()">
                                    <option value="" disabled >Seleccione Metodo de busqueda</option>
                                    <option value="USB_DIRECT_SEARCH">Find USB Printers</option>
                                    <option value="USB_DRIVER_SEARCH">Zebra USB Drivers</option>
                                    <option value="MULTICAST_BROADCAST">Directed Broadcast</option>
                                </select>           
                            </div>                           
                            <div class="col-sm-12 d-none" id="pnl_host">
                                <div class="row">
                                    <div class="col-sm-9">
                                        <b>IP Address</b>
                                        <input type="" name="txt_ip_address" id="txt_ip_address" class="form-control form-control-sm" value="" placeholder="0.0.0.0" >                                        
                                    </div>
                                    <div class="col-sm-3">
                                        <b>Puerto</b>
                                        <input type="" name="txt_puerto" id="txt_puerto" class="form-control form-control-sm" value="" placeholder="8080" >
                                    </div>
                                    
                                </div>                                                
                            </div>
                            <div class="col-sm-12 text-end">
                                <br>
                                <button class="btn-sm btn btn-primary" onclick="buscar_impresora()"><i class="bx bx-search"></i>Buscar</button>
                            </div>      
                            
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <b>Respuesta</b>
                        <select class="form-select" multiple id="ddl_lista_empresas">
                            <option disabled value="">Sin Datos</option>
                        </select>
                    </div>                              
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-sm btn-default btn" onclick="$('#modal_buscar_impresoras').modal('hide')">Cerrar</button>
                <button class="btn-sm btn-primary btn" onclick="guardar_impresora()">Guardar</button>                
            </div>
        </div>
    </div>
</div>
<?php 

// print_r($_SESSION['INICIO']);
?>