$(document).ready(function () {
    autocmpletar_color();
    autocmpletar_marca();
    autocmpletar_genero();
    lista_materia_prima_ddl();
    estado();
    sucursales();

//-------------------------------

    
     $("#btnExportarArt").on('click', function() {
        window.open("../lib/Reporte_excel.php?ListaArticulos=true", "_blank");
    });



// ----------------------------
 $("#subir_imagen").on('click', function() {
        var formData = new FormData(document.getElementById("form_img"));
        var files = $('#file_img')[0].files[0];
        formData.append('file',files);
       // formData.append('curso',curso);
        $.ajax({
            url: '../controlador/FACTURACION/lista_productosC.php?cargar_imagen=true',
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            dataType:'json',
         // beforeSend: function () {
         //        $("#foto_alumno").attr('src',"../../img/gif/proce.gif");
         //     },
            success: function(response) {
               if(response==-1)
               {
                 Swal.fire(
                  '',
                  'Algo extraño a pasado intente mas tarde.',
                  'error')

               }else if(response ==-2)
               {
                  Swal.fire(
                  '',
                  'Asegurese que el archivo subido sea una imagen.',
                  'error')
               }else
               {
                var id = $('#txt_id').val();
                if(id!='')
                {
                 detalle_articulo(id);
                }else
                {
                    location.href = 'detalle_articulos.php?id='+response
                }
               } 
            }
        });
    });
    // --------------------------

    $('#ddl_materia').on('select2:select', function (e) {
      var detalle = e.params.data.data;

      if(detalle.uni_medida=='kg')
      {
        $('#txt_cant_materia').val(0)        
        $('#txt_cant_materia').attr('readonly',true);
        $('#txt_peso_materia').attr('readonly',false);
        $('#txt_peso_materia').val()
      }else if(detalle.uni_medida=='Uni')
      {
        $('#txt_peso_materia').val(0)
        $('#txt_peso_materia').attr('readonly',true);
        $('#txt_cant_materia').attr('readonly',false);
        $('#txt_cant_materia').val()
      }else
      {
        Swal.fire('','forma de reducir stock no asignado','info').then(function(){
            $('#ddl_materia').empty();
        })
      }
     
      console.log(detalle);
    });


});

function detalle_articulo(id)
{

     $.ajax({
        data:  {id:id},
        url:   '../controlador/FACTURACION/lista_productosC.php?detalle_articulo=true',      
        type:  'post',
        dataType: 'json',
        success:  function (response) {
        console.log(response); 
            $('#txt_id').val(response.id);
            $('#lbl_nombre').text(response.nombre);  
            $('#txt_description').val(response.nombre);
            $('#txt_asset').val(response.referencia);
            $('#txt_nom_img').val(response.referencia);
            $('#txt_rfid').val(response.RFID);
            $('#txt_tag_anti').val(response.codigo_aux);
            $('#txt_cant').val(response.stock);
            $('#txt_unidad').val(response.uni_medida);
            $('#txt_valor').val(parseFloat(response.precio).toFixed(2));
            $('#txt_modelo').val(response.modelo);
            $('#ddl_estado').val(response.idEs);
            $('#txt_fecha').val(response.fecha_creacion);
            $('#ddl_marca').append($('<option>',{value: response.idMa, text: response.marca,selected: true }));
            $('#ddl_color').append($('<option>',{value: response.idCo, text: response.color,selected: true }));
            $('#ddl_genero').append($('<option>',{value: response.idGe, text: response.genero,selected: true })); 
            $('#ddl_categoria').append($('<option>',{value: response.idCa, text: response.categoria,selected: true })); 
            $('#ddl_localizacion').append($('<option>',{value: response.idSu, text: response.sucursal,selected: true })); 
            if(response.inventario==1)
            {
                $('#opcInv1').prop('checked',true);
            }else
            {
                $('#opcInv0').prop('checked',true);
            }

            if(response.servicio==1)
            {
                $('#opcs').prop('checked',true);
            }else
            {
                $('#opcp').prop('checked',true);
            }

            if(response.iva==1)
            {
                $('#opcsi').prop('checked',true);
            }else
            {
                $('#opcno').prop('checked',true);
            }
            $('#txt_tag_anti').val(response.codigo_aux);
            $('#txt_barras').val(response.codigo_bar);
            $('#txt_description2').val(response.des2);
            $('#txt_max').val(response.max);
            $('#txt_min').val(response.min);
            $('#txt_serie').val(response.serie_pro);
            $('#txt_peso').val(parseFloat(response.peso).toFixed(2));


            if(response.foto!='' && response.foto!=null)
            {          
             $("#img_articulo").attr("src",response.foto+'?'+Math.random());
            }

        }
      });

}

function add_edit()
{
    if($('#txt_description').val()=='' || $('#txt_asset').val()=='' || $('#txt_valor').val()=='' || $('#ddl_categoria').val()=='' || $('#ddl_localizacion').val()=='')
    {

        Swal.fire('','llene todo los campos obligatorios','info');
        return false;
    }
    var parametros = {
    'id':$('#txt_id').val(),
    'nombre':$('#txt_description').val(),
    'referencia':$('#txt_asset').val(),
    'rfid':$('#txt_rfid').val(),
    'codAuxiliar':$('#txt_tag_anti').val(),
    'stock':$('#txt_cant').val(),
    'uni':$('#txt_unidad').val(),
    'precio':$('#txt_valor').val(),
    'peso':$('#txt_peso').val(),
    'modelo':$('#txt_modelo').val(),
    'estado':$('#ddl_estado').val(),
    'fechaC':$('#txt_fecha').val(),
    'barras':$('#txt_barras').val(),
    'marca':$('#ddl_marca').val(),
    'color':$('#ddl_color').val(),
    'genero':$('#ddl_genero').val(),
    'cate':$('#ddl_categoria').val(),
    'des2':$('#txt_description2').val(),
    'sucu':$('#ddl_localizacion').val(),
    'serie':$('#txt_serie').val(),
    'max':$('#txt_max').val(),
    'min':$('#txt_min').val(),
    'lleva':$('input:radio[name=opc]:checked').val(),
    'tipo':$('input:radio[name=opcT]:checked').val(),
    'Inv':$('input:radio[name=opcInv]:checked').val(),
   }
    $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/FACTURACION/lista_productosC.php?add_edit=true',      
        type:  'post',
        dataType: 'json',
        success:  function (response) {
            if(response==1 && $('#txt_id').val()!='')
            { 
               Swal.fire('Producto Editado','','success'); 
            }else if(response!=-1 && $('#txt_id').val()=='')
            {
                Swal.fire('Producto Ingresado','','success'); 
                location.href = 'detalle_articulos.php?id='+response
            }else
            {
                Swal.fire('algo salio mal =C','','error'); 
            }          
        }
      });

}

function list_articulos()
{
    var parametros = 
    {
        'query':$('#txt_query').val(),
        'ref':$('#txt_ref').val(),
        'cate':$('#ddl_categoria').val(),
        'tipo':$('input:radio[name=opc]:checked').val()
    }
     $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/FACTURACION/lista_productosC.php?buscar_articulo=true',      
        type:  'post',
        dataType: 'json',
        success:  function (response) { 
            var tr = "";

            if(response.length>0)
            {
                response.forEach(function(item,i){
                    var alerta='table-default';
                    var lleva = '';
                    if(item.iva=='1')
                    {
                        lleva = '<span class="badge rounded-pill bg-primary">lleva iva</span>';
                    }


                    tr+=`<tr  class="`+alerta+`" onclick="usar('`+item.id_productos+`','`+item.referencia+`','`+item.nombre+`','`+parseFloat(item.precio_uni).toFixed(2)+`','`+item.iva+`')">
                        <td><a href="inicio.php?mod=`+modulo+`&acc=detalle_articulos&id=`+item.id_productos+`"><u>`+item.referencia+`</u></a></td>
                        <td>`+item.codigo_aux+`</td>
                        <td><a href="inicio.php?mod=`+modulo+`&acc=detalle_articulos&id=`+item.id_productos+`"><u>`+item.nombre+` `+lleva+`</u></a></td>
                        <td>`+item.stock+`</td>
                        <td>`+item.uni_medida+`</td>
                        <td>`+item.peso+`</td>
                        <td>`+item.nombre_sucursal+`</td>
                        <td>`+item.categoria+`</td>
                    </tr>`
                    console.log(item);
                })
            }

            $('#tbl_productos').html(tr);          
        }
      });
}

function list_trasferencias()
{
    var parametros = 
    {
        'query':$('#txt_query').val(),
        'ref':$('#txt_ref').val(),
        'cate':$('#ddl_categoria').val(),
        'tipo':$('input:radio[name=opc]:checked').val()
    }
     $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/FACTURACION/lista_productosC.php?buscar_transferencias=true',      
        type:  'post',
        dataType: 'json',
        success:  function (response) { 
            $('#tbl_productos').html(response);          
        }
      });
}

function categorias()
  {
    $('#ddl_categoria').select2({
      // width: 'resolve',
      placeholder: 'categoria',
      ajax: {
        url:   '../controlador/FACTURACION/lista_facturaC.php?categorias=true',  
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
          return {
            results: data
          };
        },
        cache: true
      }
    });
  }

  function sucursales()
  {
    $('#ddl_localizacion').select2({
      // width: 'resolve',
      placeholder: 'Seleccione',
      ajax: {
        url:   '../controlador/sucursalesC.php?sucursales=true',  
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
          return {
            results: data
          };
        },
        cache: true
      }
    });
  }



function autocmpletar_color(){
  $('#ddl_color').select2({
    placeholder: 'Seleccione un color',
    ajax: {
      url:  '../controlador/coloresC.php?colores=true',
      dataType: 'json',
      delay: 250,
      processResults: function (data) {
        return {
          results: data
        };
      },
      cache: true
    }
  });
}

 function autocmpletar_marca(){
      $('#ddl_marca').select2({
        placeholder: 'Seleccione una marca',
        ajax: {
          url: '../controlador/marcasC.php?marca=true',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            return {
              results: data
            };
          },
          cache: true
        }
      });
  }
  function autocmpletar_genero(){
      $('#ddl_genero').select2({
        placeholder: 'Seleccione una custodio',
        ajax: {
          url: '../controlador/generoC.php?genero=true',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            return {
              results: data
            };
          },
          cache: true
        }
      });
  }

 function estado()
  { 
    var id='';
    var estado = '<option value="">Seleccione Estado</option>';

    $.ajax({
      data:  {id:id},
      url:   '../controlador/estadoC.php?lista=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {    
        // console.log(response);   
        $.each(response, function(i, item){
            estado+="<option value='"+item.ID_ESTADO+"''>"+item.DESCRIPCION+"</option>";

          // console.log(item);
        });       
        $('#ddl_estado').html(estado);        
      }
    });
  }

function eliminar()
  {
     Swal.fire({
      title: 'Quiere eliminar este registro?',
      text: "Si elimina este articulo no podra ser usado en un futuro!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si'
    }).then((result) => {
        if (result.value) {
           eliminar_articulo();
        }
      });
   }


  function eliminar_articulo()
  {
     var parametros = 
    {
        'id':$('#txt_id').val(),
    }
     $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/FACTURACION/lista_productosC.php?eliminar=true',      
        type:  'post',
        dataType: 'json',
        success:  function (response) { 
          if(response==2)
          {
            Swal.fire('No se puedo eliminar','Este articulo esta ligado a 1 o mas registros','info');
          }else if(response==1)
          {
            Swal.fire('Registro eliminado','','success');  
            location.href = 'lista_articulos.php';          
          }else
          {
             Swal.fire('No se pudo eliminar','','error');       
          }      
        }
      });

  }

  function cantidad_transferencia(id,stock)
  {
    $('#txt_id').val(id);
    $('#modal_cantidad').modal('show');
    $('#txt_stock').val(stock);
  }

  function agregar_trasnferencia()
  {
    var stock = parseFloat($('#txt_stock').val());
    var cant = parseFloat($('#txt_cant').val());
     if(cant>stock)
     {
        Swal.fire('cantidad supera a Stock','','info');
        return false;
     }
     var parametros = 
    {
        'id':$('#txt_id').val(),
        'cant':$('#txt_cant').val(),
    }
     $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/FACTURACION/lista_productosC.php?agregar_trasnferencia=true',      
        type:  'post',
        dataType: 'json',
        success:  function (response) { 
            if(response==1)
            {
                Swal.fire('','Agregado a transferencias','success');
                $('#txt_stock').val(0);
                $('#txt_cant').val(1);
                $('#modal_cantidad').modal('hide');
                cargar_transferencias();
            }         
        }
      });
  }

  function cargar_transferencias()
  {
      $.ajax({
        // data:  {parametros:parametros},
        url:   '../controlador/FACTURACION/lista_productosC.php?lista_trasnferencia=true',      
        type:  'post',
        dataType: 'json',
        success:  function (response) { 
            console.log(response);
            if(response!=null)
            {
                $('#noti').css('display','initial');              
            }else
            {                
                $('#noti').css('display','none'); 
            }
            
                $('#tbl_trans').html(response);          
        }
      });
  }

  function eliminar_trans(id)
  {
     Swal.fire({
      title: 'Quiere eliminar este registro?',
      text: "Si elimina este articulo no podra ser transferido!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si'
    }).then((result) => {
        if (result.value) {
          eliminar_articulo_transferencia(id);
        }
      });
   }


  function eliminar_articulo_transferencia(id)
  {
     var parametros = 
    {
        'id':id,
    }
     $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/FACTURACION/lista_productosC.php?eliminar_transferencia=true',      
        type:  'post',
        dataType: 'json',
        success:  function (response) { 
          if(response==2)
          {
            Swal.fire('No se puedo eliminar','Este articulo esta ligado a 1 o mas registros','info');
          }else if(response==1)
          {
            Swal.fire('Registro eliminado','','success');
            cargar_transferencias();   
          }else
          {
             Swal.fire('No se pudo eliminar','','error');       
          }      
        }
      });

  }

  function tranferencia()
  {
    var entrada = $('#ddl_localizacion').val();
    console.log(entrada);
    if(entrada=='')
    {
        Swal.fire('Seleccione destino','','info');
    }
    var parametros = 
    {
        'destino':entrada,
    }
     $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/FACTURACION/lista_productosC.php?transferencia=true',      
        type:  'post',
        dataType: 'json',
        success:  function (response) { 
           
        }
      });
  }
function tamanio_lista(id)
{
   
    var parametros = 
    {
        'id':id,
    }
     $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/FACTURACION/lista_productosC.php?kit_tamanio=true',      
        type:  'post',
        dataType: 'json',
        success:  function (response) {
            if(response!='')
            {
                $('#tbl_tama').html(response);
            }
        }
    });
}
function editar_tama(id)
{  
    var parametros = 
    {
        'id':id,
    }
     $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/FACTURACION/lista_productosC.php?kit_tamanio_datos=true',      
        type:  'post',
        dataType: 'json',
        success:  function (response) {
            if(response!='')
            {
                $('#txt_tama').val(response[0].nombre);
                $('#txt_precio_ta').val(response[0].precio);
                $('#txt_id_tama').val(response[0].id);
            }
        }
    });
}

function tamanio_add()
{
    var ta = $('#txt_tama').val();
    var pre = $('#txt_precio_ta').val();
    var id = $('#txt_id_tama').val();
    var id_p = $('#txt_id').val();
    if(ta=='' || pre == '')
    {
        Swal.fire('Llene todo los campos','','success');
        return false;
    }
    var parametros = 
    {
        'id':id,
        'nombre': ta,
        'precio': pre,
        'producto': id_p,
    }
     $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/FACTURACION/lista_productosC.php?kit_tamanio_add=true',      
        type:  'post',
        dataType: 'json',
        success:  function (response) {
            console.log(response);
            if(response==1)
            {
                Swal.fire('Guardado','','success');
                $('#txt_tama').val('');
                $('#txt_precio_ta').val('');
                $('#txt_id_tama').val('');
                tamanio_lista(id_p);
            }
        }
    });
}

function eliminar_tama(id)
{
     Swal.fire({
      title: 'Quiere eliminar este registro?',
      text: "Si elimina este articulo no podra ser usado en un futuro!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si'
    }).then((result) => {
        if (result.value) {
           eliminar_tamanio(id);
        }
      });
}


  function eliminar_tamanio(id)
  {
     
    var id_p = $('#txt_id').val();
     $.ajax({
        data:  {id:id},
        url:   '../controlador/FACTURACION/lista_productosC.php?eliminar_tama=true',      
        type:  'post',
        dataType: 'json',
        success:  function (response) { 
          if(response==1)
          {           
            Swal.fire('Registro eliminado','','success');  
            tamanio_lista(id_p);
          }else
          {
             Swal.fire('No se pudo eliminar','','error');       
          }      
        }
      });

}

function lista_articulos_adicionales()
  {
    $('#ddl_producto_add').select2({
      width: '90%',
      placeholder: 'Productos adicionales',
      ajax: {
        url:   '../controlador/FACTURACION/lista_productosC.php?detalle_articulo_ddl=true',  
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
          return {
            results: data
          };
        },
        cache: true
      }
    });
  }

function lista_materia_prima_ddl()
  {
    $('#ddl_materia').select2({
      width: '100%',
      placeholder: 'Productos adicionales',
      ajax: {
        url:   '../controlador/FACTURACION/lista_productosC.php?ddl_materia_prima=true',  
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
          return {
            results: data
          };
        },
        cache: true
      }
    });
  }


function adicionales_lista(id)
{
   
    var parametros = 
    {
        'id':id,
    }
     $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/FACTURACION/lista_productosC.php?adicionales=true',      
        type:  'post',
        dataType: 'json',
        success:  function (response) {
            if(response!='')
            {
                $('#tbl_adicional').html(response);
            }
        }
    });
}
function editar_adicionales(id)
{  
    var parametros = 
    {
        'id':id,
    }
     $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/FACTURACION/lista_productosC.php?adicionales_datos=true',      
        type:  'post',
        dataType: 'json',
        success:  function (response) {
            if(response!='')
            {
                $('#txt_tama').val(response[0].nombre);
                $('#txt_precio_ta').val(response[0].precio);
                $('#txt_id_tama').val(response[0].id);
            }
        }
    });
}

function adicionales_add()
{
    var id =  $('#txt_id').val();
    var id_p = $('#ddl_producto_add').val();
    if(id== '')
    {
        Swal.fire('Seleccione un producto a adicionar','','success');
        return false;
    }
    var parametros = 
    {
        'id':id,
        'producto': id_p,       
    }
     $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/FACTURACION/lista_productosC.php?adicionales_add=true',      
        type:  'post',
        dataType: 'json',
        success:  function (response) {
            console.log(response);
            if(response==1)
            {
                Swal.fire('Guardado','','success');
                $('#ddl_producto_add').empty();
                adicionales_lista(id);
            }
        }
    });
}

function eliminar_adicionales(id)
{
     Swal.fire({
      title: 'Quiere eliminar este registro?',
      text: "Si elimina este articulo no podra ser usado en un futuro!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si'
    }).then((result) => {
        if (result.value) {
           eliminar_adi(id);
        }
      });
}


  function eliminar_adi(id)
  {
     
    var id_p = $('#txt_id').val();
     $.ajax({
        data:  {id:id},
        url:   '../controlador/FACTURACION/lista_productosC.php?eliminar_adicionales=true',      
        type:  'post',
        dataType: 'json',
        success:  function (response) { 
          if(response==1)
          {           
            Swal.fire('Registro eliminado','','success');  
            adicionales_lista(id_p);
          }else
          {
             Swal.fire('No se pudo eliminar','','error');       
          }      
        }
      });

  }




function materia_prima(id)
{
    var parametros = 
    {
        'id':id,
    }
     $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/FACTURACION/lista_productosC.php?kit_materia_prima=true',      
        type:  'post',
        dataType: 'json',
        success:  function (response) {
            $('#tbl_materia').html(response);
            console.log(response);
        }
    });
}


function materia_prima_add()
{
    var id =  $('#txt_id').val();
    var id_m = $('#ddl_materia').val();
    var cant = $('#txt_cant_materia').val();
    var peso = $('#txt_peso_materia').val();
    if(id_m== '')
    {
        Swal.fire('No se pudo agregar','Seleccione materia prima a adicionar','info');
        return false;
    }
    var parametros = 
    {
        'id':id,
        'materia': id_m,
        'cantidad':cant,
        'peso':peso,       
    }
     $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/FACTURACION/lista_productosC.php?materia_add=true',      
        type:  'post',
        dataType: 'json',
        success:  function (response) {
            console.log(response);
            if(response==1)
            {
                Swal.fire('Guardado','','success');
                $('#ddl_materia').empty();
                $('#txt_cant_materia').val(0);
                $('#txt_peso_materia').val(0);
                materia_prima(id)
            }
        }
    });
}


function eliminar_materia_prima(id)
{
     Swal.fire({
      title: 'Quiere eliminar este registro?',
      text: "Si elimina este articulo no sera tomado en cuenta pra el stock!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si'
    }).then((result) => {
        if (result.value) {
            eliminar_materia(id);
        }
      });
}


  function eliminar_materia(id)
  {
     
    var id_p = $('#txt_id').val();
     $.ajax({
        data:  {id:id},
        url:   '../controlador/FACTURACION/lista_productosC.php?eliminar_materia=true',      
        type:  'post',
        dataType: 'json',
        success:  function (response) { 
          if(response==1)
          {           
            Swal.fire('Registro eliminado','','success');  
             materia_prima(id_p);
          }else
          {
             Swal.fire('No se pudo eliminar','','error');       
          }      
        }
      });

  }
function add_categoria()
{
    cat = $('#txt_new_cate').val();
    if(cat=='')
    {
        Swal.fire('Llene el nombre','','info');
        return false;
    } 
    var parametros =
    {
        'categoria':cat,
    }
    $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/FACTURACION/lista_productosC.php?add_categoria=true',      
        type:  'post',
        dataType: 'json',
        success:  function (response) { 
          if(response==1)
          {           
             Swal.fire('Registro guardado','','success');  
             $('#nueva_categoria').modal('hide');
             materia_prima(id_p);
          }else if(response==-2)
          {
             Swal.fire('Nombre de categoria encontrada','','error');       
          }else
          {
             Swal.fire('No se pudo insertar','','error'); 
          }      
        }
      });
}