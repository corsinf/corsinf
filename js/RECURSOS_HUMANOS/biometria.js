

$(document).ready(function() {
    listaTarjetas();
    dispositivos();
    listaHuella()
    listaFace();
    tarjetasddl();


    document.getElementById("file_huella").addEventListener("change", function () {
        const fileNameSpan = document.getElementById("file-name");
        const file = this.files[0];
        fileNameSpan.textContent = file ? file.name : "Ningún archivo seleccionado";
      });

    document.getElementById("file_face").addEventListener("change", function () {
        const fileNameSpan = document.getElementById("file-name-face");
        const file = this.files[0];
        fileNameSpan.textContent = file ? file.name : "Ningún archivo seleccionado";
      });
});





 function tarjetasddl() {
      var parametros = {
          id:PersonaId, // Parámetro personalizado
      };
        $.ajax({
            data: {parametros:parametros},
            url: '../controlador/TALENTO_HUMANO/th_personasC.php?listaTarjetas=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                console.log(response);
                op = '';
                response.forEach(function(item,i){
                    op+='<option value="'+item.th_cardNo+'">'+item.th_cardNo+'</option>';
                })
                $('#ddl_tarjetas').html(op);
                $('#ddl_tarjetas_facial').html(op);
               
            },  error: function(xhr, status, error) {
                console.log('Status: ' + status); 
                console.log('Error: ' + error); 
                console.log('XHR Response: ' + xhr.responseText); 

                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });
}

 function dispositivos() {
        $.ajax({
            // data: {
            //     id: id
            // },
            url: '../controlador/TALENTO_HUMANO/th_dispositivosC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                console.log(response);
                op = '';
                response.forEach(function(item,i){
                    op+='<option value="'+item._id+'">'+item.nombre+'</option>';
                })
                $('#ddl_dispositivos').html(op);
               
            },  error: function(xhr, status, error) {
                console.log('Status: ' + status); 
                console.log('Error: ' + error); 
                console.log('XHR Response: ' + xhr.responseText); 

                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });
    }


function syncronizarPersona(id) {
    $('#sync_biometrico').modal('show');
    switch(id){
    case 1:
        $('#btn_tarjeta_all').removeClass('d-none');
        $('#btn_tarjeta').addClass('d-none');
        $('#btn_delete_tarjeta').addClass('d-none');

        $('#btn_huella_all').addClass('d-none');
        $('#btn_huella').addClass('d-none');
        $('#btn_delete_tarjeta').addClass('d-none');        
        $('#btn_huella_lectura').addClass('d-none');

        $('#btn_facial').addClass('d-none');
        $('#btn_delete_facial').addClass('d-none');

        $('#btn_huella_lectura').addClass('d-none');
        $('#btn_face_lectura').addClass('d-none');

        break;
     case 2:
        $('#btn_tarjeta_All').addClass('d-none');
        $('#btn_tarjeta').removeClass('d-none');
        $('#btn_delete_tarjeta').addClass('d-none');
        
        $('#btn_huella_all').addClass('d-none');
        $('#btn_huella').addClass('d-none');
        $('#btn_delete_tarjeta').addClass('d-none');     
        $('#btn_huella_lectura').addClass('d-none');

        $('#btn_facial').addClass('d-none');
        $('#btn_delete_facial').addClass('d-none');

        $('#btn_huella_lectura').addClass('d-none');
        $('#btn_face_lectura').addClass('d-none');
        break;
     case 3:
        $('#btn_tarjeta_all').addClass('d-none');
        $('#btn_tarjeta').addClass('d-none');
        $('#btn_delete_tarjeta').removeClass('d-none');
        
        $('#btn_huella_all').addClass('d-none');
        $('#btn_huella').addClass('d-none');
        $('#btn_delete_tarjeta').addClass('d-none');     
        $('#btn_huella_lectura').addClass('d-none');

        $('#btn_facial').addClass('d-none');
        $('#btn_delete_facial').addClass('d-none');

        $('#btn_huella_lectura').addClass('d-none');
        $('#btn_face_lectura').addClass('d-none');

        break;
    // seccion para nueva huella digital

    case 4:
        
        $('#btn_tarjeta_all').addClass('d-none');
        $('#btn_tarjeta').addClass('d-none');
        $('#btn_delete_tarjeta').addClass('d-none');
      
        $('#btn_huella_all').removeClass('d-none');
        $('#btn_huella').addClass('d-none');
        $('#btn_delete_huella').addClass('d-none');     
        $('#btn_huella_lectura').addClass('d-none');

        $('#btn_facial').addClass('d-none');
        $('#btn_delete_facial').addClass('d-none');

        $('#btn_huella_lectura').addClass('d-none');
        $('#btn_face_lectura').addClass('d-none');

        break;
     case 5:
        
        $('#btn_tarjeta_all').addClass('d-none');
        $('#btn_tarjeta').addClass('d-none');
        $('#btn_delete_tarjeta').addClass('d-none');
      
        $('#btn_huella_all').addClass('d-none');
        $('#btn_huella').removeClass('d-none');
        $('#btn_delete_huella').addClass('d-none');     
        $('#btn_huella_lectura').addClass('d-none');

        $('#btn_facial').addClass('d-none');
        $('#btn_delete_facial').addClass('d-none');

        $('#btn_huella_lectura').addClass('d-none');
        $('#btn_face_lectura').addClass('d-none');
        break;
     case 6:
        
        $('#btn_tarjeta_all').addClass('d-none');
        $('#btn_tarjeta').addClass('d-none');
        $('#btn_delete_tarjeta').addClass('d-none');
      
        $('#btn_huella_all').addClass('d-none');
        $('#btn_huella').addClass('d-none');
        $('#btn_delete_huella').removeClass('d-none');     
        $('#btn_huella_lectura').addClass('d-none');

        $('#btn_facial').addClass('d-none');
        $('#btn_delete_facial').addClass('d-none');


        $('#btn_huella_lectura').addClass('d-none');
        $('#btn_face_lectura').addClass('d-none');

        break;
     case 7:

        $('#btn_tarjeta_all').addClass('d-none');
        $('#btn_tarjeta').addClass('d-none');
        $('#btn_delete_tarjeta').addClass('d-none');
      
        $('#btn_huella_all').addClass('d-none');
        $('#btn_huella').addClass('d-none');
        $('#btn_delete_huella').addClass('d-none');     
        $('#btn_huella_lectura').addClass('d-none');

        $('#btn_facial').removeClass('d-none');
        $('#btn_delete_facial').addClass('d-none');


        $('#btn_huella_lectura').addClass('d-none');
        $('#btn_face_lectura').addClass('d-none');

        
        break;

    case 8:

        $('#btn_tarjeta_all').addClass('d-none');
        $('#btn_tarjeta').addClass('d-none');
        $('#btn_delete_tarjeta').addClass('d-none');
      
        $('#btn_huella_all').addClass('d-none');
        $('#btn_huella').addClass('d-none');
        $('#btn_delete_huella').addClass('d-none');     
        $('#btn_huella_lectura').addClass('d-none');

        $('#btn_facial').addClass('d-none');
        $('#btn_delete_facial').removeClass('d-none');


        $('#btn_huella_lectura').addClass('d-none');
        $('#btn_face_lectura').addClass('d-none');

        
        break;

    case 98:
        $('#btn_tarjeta_All').addClass('d-none');
        $('#btn_tarjeta').addClass('d-none');
        $('#btn_delete_tarjeta').addClass('d-none');
        
        $('#btn_huella_all').addClass('d-none');
        $('#btn_huella').addClass('d-none');
        $('#btn_delete_tarjeta').addClass('d-none');

        $('#btn_facial').addClass('d-none');
        $('#btn_delete_facial').addClass('d-none');

        $('#btn_huella_lectura').addClass('d-none');
        $('#btn_face_lectura').removeClass('d-none');

        break;
     case 99:
        $('#btn_tarjeta_All').addClass('d-none');
        $('#btn_tarjeta').addClass('d-none');
        $('#btn_delete_tarjeta').addClass('d-none');
        
        $('#btn_huella_all').addClass('d-none');
        $('#btn_huella').addClass('d-none');
        $('#btn_delete_tarjeta').addClass('d-none');

        $('#btn_facial').addClass('d-none');
        $('#btn_delete_facial').addClass('d-none');

        $('#btn_huella_lectura').removeClass('d-none');
        $('#btn_face_lectura').addClass('d-none');

        break;
    // seccion de eliminar
   

        default:
        break;
    }
}



// funciones para agregar tarjetas

function listaTarjetas()
    {
        if ($.fn.DataTable.isDataTable('#tbl_cards')) {
            $('#tbl_cards').DataTable().destroy();
        }
        tbl_dispositivos = $('#tbl_cards').DataTable($.extend({},{
            reponsive: true,
            searching: false,  // Desactiva el buscador
            paging: false,     // Desactiva la paginación
            info: false,       // Opcional: Desactiva la información (ej. "Mostrando 1 a 10 de 100 registros")

            language: {
                url: '../assets/plugins/datatable/spanish.json'
            },
            ajax: {
                type:'POST',
                url: '../controlador/TALENTO_HUMANO/th_personasC.php?listaTarjetas=true',
                data: function (d) {
                   
                     var parametros = {
                      id:PersonaId, // Parámetro personalizado
                  };
                  return { parametros: parametros };
                },
                dataSrc: ''
            },
            columns: [
                 { data: null,
                        render: function(data, type, item) {
                        return `<div class="d-flex align-items-center">
                                    <div class="">
                                        <img src="../img/de_sistema/card.png" width="46" height="46" alt="">
                                    </div>
                                    <div class="ms-2">
                                        <h6 class="mb-1 font-14"><b>${item.th_cardNo}</b></h6>
                                    </div>
                                </div>`;
                        }
                    },                
                    { data: null,
                        render: function(data, type, item) {
                         return ` <div class="list-inline d-flex customers-contacts ms-auto"> 
                                    <a href="javascript:;" class="list-inline-item bg-danger" title="Eliminar registro" onclick="deteleTarjetaBase('${item.th_cardNo}')"><i class="bx bxs-trash"></i></a>
                                     <a href="javascript:;" class="list-inline-item bg-success" title="Sincronizar en Biometrico" onclick="TarjeInditaBio('${item.th_cardNo}')"><i class="bx bx-sync"></i></a>
                                    <a href="javascript:;" class="list-inline-item bg-danger" title="Eliminar y sincronizar en biometrico" onclick="deteleTarjeta('${item.th_cardNo}')"><i class="bx bxs-trash-alt"></i></a>
                                </div>`;
                        }
                    },        

               
            ],
            order: [
                [1, 'asc']
            ],
        }));

        $('#tbl_cards').on('xhr.dt', function (e, settings, json, xhr) {
        if (json && json.length > 0) {
            $('#v-pills-profile-tab').removeClass('disabled')
            $('#v-pills-messages-tab').removeClass('disabled')
        } else {
            $('#v-pills-profile-tab').addClass('disabled')
            $('#v-pills-messages-tab').addClass('disabled')
        }
    });

    }

    function nuevaTarjeta()
    {
        $('#nuevaTarjeta').modal('show');
        generar_CardNo();
    }
    function generar_CardNo()
    {
        $.ajax({
            // data: { parametros: parametros },
            url: '../controlador/TALENTO_HUMANO/th_personasC.php?generar_CardNo=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                $('#txt_CardNumero').val(response[0]['NUMERO'])
            }
        });
    }

    function addTarjetaBase()
    {
        var id = $('#txt_id').val(); 
        var parametros = 
        {
            'idPerson':PersonaId,
            'CardNo':$('#txt_CardNumero').val(),
        }
        $.ajax({
            data: { parametros: parametros },
            url: '../controlador/TALENTO_HUMANO/th_personasC.php?addTarjetaBase=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                console.log(response);
                if(response==1)
                {
                    Swal.fire("Tarjeta registrada","","success").then(function(){
                        $('#nuevaTarjeta').modal('hide');
                         listaTarjetas();
                         tarjetasddl();
                    })
                }
                // $('#txt_CardNumero').val(response[0]['NUMERO'])
            }
        });
    }

    function deteleTarjetaBase(CardNo)
    {

        var id = $('#txt_id').val(); 
        Swal.fire({
          title: 'Quiere eliminar este registro solo de la base de datos?',
          text: "Esta seguro de eliminar este registro solo de la base de datos!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si'
        }).then((result) => {
            if (result.value) {

                 var parametros = 
                {
                    'idPerson':PersonaId,
                    'CardNo':CardNo,
                }
                $.ajax({
                    data: { parametros: parametros },
                    url: '../controlador/TALENTO_HUMANO/th_personasC.php?DeleteTarjetaBase=true',
                    type: 'post',
                    dataType: 'json',
                    success: function(response) {
                        if(response==1)
                        {
                            Swal.fire("Eliminado","","success").then(function(){
                                listaTarjetas();
                                tarjetasddl();
                            })
                        }
                    }
                });
            }
        })
    }

    function deteleTarjeta(CardNo)
    {
        $('#txt_cardNo').val(CardNo);
        syncronizarPersona(3);
    }

    function deteleTarjetaBio()
    {
         Swal.fire({
          title: 'Quiere eliminar este registro de base y biometrico?',
          text: "Esta seguro de eliminar este registro!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si'
        }).then((result) => {
            if (result.value) {


                 var parametros = 
                {
                    'device':$('#ddl_dispositivos').val(),
                    'idPerson':PersonaId,
                    'CardNo':$('#txt_cardNo').val(),
                }
                $.ajax({
                    data: { parametros: parametros },
                    url: '../controlador/TALENTO_HUMANO/th_personasC.php?DeleteTarjetaBio=true',
                    type: 'post',
                    dataType: 'json',
                    success: function(response) {
                        if(response['resp']==1)
                        {
                            Swal.fire("Eliminado","","success").then(function(){
                                $('#sync_biometrico').modal('hide');
                                listaTarjetas();
                                tarjetasddl();
                            })
                        }
                    }
                });


            }
        })
            
    }


    function addTarjetaBioAll()
    {
        var id = $('#txt_id').val(); 
        var parametros = 
        {
            'device':$('#ddl_dispositivos').val(),
            'idPerson':PersonaId,
        }
        $.ajax({
            data: { parametros: parametros },
            url: '../controlador/TALENTO_HUMANO/th_personasC.php?addTarjetaBioAll=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if(response['resp']==1)
                {
                    Swal.fire("Tarjeta registrada","","success").then(function(){
                        $('#sync_biometrico').modal('hide');
                         listaTarjetas();
                         tarjetasddl();
                    })
                }
                // $('#txt_CardNumero').val(response[0]['NUMERO'])
            }
        });
    }

    function TarjeInditaBio(CardNo)
    {
        $('#txt_cardNo').val(CardNo);
        syncronizarPersona(2)
    }

    function addTarjetaBio()
    {
        var id = $('#txt_id').val(); 
        var parametros = 
        {
            'device':$('#ddl_dispositivos').val(),
            'idPerson':PersonaId,
            'CardNo':$('#txt_cardNo').val(),
        }
        $.ajax({
            data: { parametros: parametros },
            url: '../controlador/TALENTO_HUMANO/th_personasC.php?addTarjetaBio=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if(response['resp']==1)
                {
                    Swal.fire("Tarjeta registrada","","success").then(function(){
                        $('#sync_biometrico').modal('hide');
                         listaTarjetas();
                         tarjetasddl();
                    })
                }
                // $('#txt_CardNumero').val(response[0]['NUMERO'])
            }
        });
    }


// funciones para agregar huellas difitales


    function listaHuella()
    {
        if ($.fn.DataTable.isDataTable('#tbl_bio_finger')) {
            $('#tbl_bio_finger').DataTable().destroy();
        }
        tbl_dispositivos = $('#tbl_bio_finger').DataTable($.extend({},{
            reponsive: true,
            searching: false,  // Desactiva el buscador
            paging: false,     // Desactiva la paginación
            info: false,       // Opcional: Desactiva la información (ej. "Mostrando 1 a 10 de 100 registros")

            language: {
                url: '../assets/plugins/datatable/spanish.json'
            },
            ajax: {
                type:'POST',
                url: '../controlador/TALENTO_HUMANO/th_personasC.php?listaHuellas=true',
                data: function (d) {
                   
                     var parametros = {
                      id:PersonaId, // Parámetro personalizado
                  };
                  return { parametros: parametros };
                },
                dataSrc: ''
            },
            columns: [
                 { data: null,
                        render: function(data, type, item) {
                        return `<div class="d-flex align-items-center">
                                    <div class="">
                                        <img src="../img/de_sistema/finger_huella.png" width="46" height="46" alt="">
                                    </div>
                                    <div class="ms-2">
                                        <h6 class="mb-1 font-14"><b>Dedo No:${item.th_finger_numero}</b></h6>
                                    </div>
                                </div>`;
                        }
                    },   
                     { data: 'th_cardNo'},                
                    { data: null,
                        render: function(data, type, item) {
                         return ` <div class="list-inline d-flex customers-contacts ms-auto"> 
                                    <a href="javascript:;" class="list-inline-item bg-danger" title="Eliminar de Base de datos" onclick="deteleHuellaBase('${item._id}')"><i class="bx bxs-trash"></i></a>
                                     <a href="javascript:;" class="list-inline-item bg-success" title="Sincronizar en Biometrico" onclick="HuellaInditaBio('${item._id}','${item.th_cardNo}')"><i class="bx bx-sync"></i></a>
                                    <a href="javascript:;" class="list-inline-item bg-danger" title="Eliminar de base y Biometrico" onclick="deteleHuellaBio('${item.th_cardNo}','${item._id}')"><i class="bx bxs-trash-alt"></i></a>
                                </div>`;
                        }
                    },        

               
            ],
            order: [
                [1, 'asc']
            ],
        }));

    }

    function nuevahuellaBio()
    {
        $('#nuevahuella').modal('show');
    }

    function addHuellaBase()
    {
        var formData = new FormData();
        var archivo = $('#file_huella')[0].files[0]; // obtener el archivo
        var detectado = $('#file-name_bio').text();
        var numfinger = $('#txt_dedo_num').val();
        var CardNo = $('#ddl_tarjetas').val();

        // console.log(archivo)

        if(detectado=='' && archivo==undefined)
        {
            Swal.fire("No sea encontrado huella valida","","info")
            return false;
        }

        formData.append('huella', archivo); // adjuntar al formData
        formData.append('idPerson', PersonaId);
        formData.append('detectado', detectado);
        formData.append('NumFinger', numfinger);
        formData.append('CardNo', CardNo);

        $.ajax({
           url: '../controlador/TALENTO_HUMANO/th_personasC.php?addHuellaBase=true',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (response) {
                console.log(response)
                if(response==1)
                {
                    Swal.fire("Huella digital agregada","","success");
                    $('#nuevahuella').modal('hide');
                    listaHuella();
                }else if(response==-2)
                {
                    Swal.fire("Huella ya registrada","","error")
                }else
                {
                    Swal.fire("","","info")
                }
            },
            error: function () {
                console.error('Error al subir el archivo');
            }
        });
    }

    function addHuellaBio()
    {
        var parametros = 
        {
            '_id':$('#txt_id_reg').val(),
            'CardNo':$('#txt_cardNo').val(),
            'PersonaId':PersonaId,
            'dispositivo':$('#ddl_dispositivos').val(),
        }

        $.ajax({

            data: { parametros: parametros },
            url: '../controlador/TALENTO_HUMANO/th_personasC.php?addHuellaBio2=true',
            type: 'post',
            dataType: 'json',
            success: function (response) {
                console.log(response)
                if(response.resp==1)
                {
                    Swal.fire("Huella digital agregada","","success");
                    $('#sync_biometrico').modal('hide');
                }else if(response.resp==-2)
                {
                    Swal.fire("Huella digital no encontrada","","error");
                }else
                {
                    Swal.fire("",response.msj,"info")
                }
            },
            error: function () {
                console.error('Error al subir el archivo');
            }
        });
    }

    function HuellaInditaBio(id,CardNo)
    {
        $('#txt_cardNo').val(CardNo);
        $('#txt_id_reg').val(id);
        syncronizarPersona(5)
    }


    function deteleHuellaBase(id)
    {
        Swal.fire({
          title: 'Quiere eliminar este registro solo de la base de datos?',
          text: "Esta seguro de eliminar este registro solo de la base de datos!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si'
        }).then((result) => {
            if (result.value) {

                var parametros = 
                {
                    '_id':id,
                }
                $.ajax({
                    data: { parametros: parametros },
                    url: '../controlador/TALENTO_HUMANO/th_personasC.php?deteleHuellaBase=true',
                    type: 'post',
                    dataType: 'json',
                    success: function(response) {
                        if(response==1)
                        {
                            Swal.fire("Eliminado","","success").then(function(){
                                listaHuella();
                            })
                        }
                    }
                });
            }
        })

    }
    function deteleHuellaBio(CardNo,idHuella)
    {
        syncronizarPersona(6);
        $('#txt_cardNo').val(CardNo);
        $('#txt_id_reg').val(idHuella);
    }

    function deteleHuella()
    {
        Swal.fire({
          title: 'Quiere eliminar este registro?',
          text: "Esta seguro de eliminar este registro!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si'
        }).then((result) => {
            if (result.value) {

                 var parametros = 
                {
                    'device':$('#ddl_dispositivos').val(),
                    'idPerson':PersonaId,
                    'CardNo':$('#txt_cardNo').val(),
                    'idHuella':$('#txt_id_reg').val()
                }
                $.ajax({
                    data: { parametros: parametros },
                    url: '../controlador/TALENTO_HUMANO/th_personasC.php?deteleHuella=true',
                    type: 'post',
                    dataType: 'json',
                    success: function(response) {
                        if(response==1)
                        {
                            Swal.fire("Eliminado","","success").then(function(){
                                $('#sync_biometrico').modal('hide');
                                listaHuella();
                            })
                        }
                    }
                });
            }
        })
    }

    function leerDedo()
    {
        // $('#myModal_espera').modal('show');
        var parametros = 
        {
            'iddispostivos':$('#ddl_dispositivos').val(),
            'idPerson': PersonaId, //usuario id
            'dedo':$('#txt_dedo_num').val(),
        }
        $.ajax({
            data:  {parametros:parametros},
            url:   '../controlador/TALENTO_HUMANO/th_personasC.php?CapturarFinger=true',
            type:  'post',
            dataType: 'json',
            success:  function (response) { 
                $('#myModal_espera').modal('hide');
                if(response.resp==1)
                {
                    Swal.fire("Huella dactilar Guardada",response.patch,"success");
                    $('#file-name_bio').text(response.patch);
                    $('#sync_biometrico').modal('hide');
                }else
                {
                    Swal.fire("Huella dactilar",response.msj,"info");
                }

               
                     tbl_dispositivos.ajax.reload(null, false);

            } ,
              error: function(xhr, status, error) {
                console.log('Status: ' + status); 
                console.log('Error: ' + error); 
                console.log('XHR Response: ' + xhr.responseText); 

                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
                $('#myModal_espera').modal('hide');
            }         
        });
    }



    // funciones para agregar un facial

    function listaFace()
    {
        if ($.fn.DataTable.isDataTable('#tbl_bio_face')) {
            $('#tbl_bio_face').DataTable().destroy();
        }
        tbl_dispositivos = $('#tbl_bio_face').DataTable($.extend({},{
            reponsive: true,
            searching: false,  // Desactiva el buscador
            paging: false,     // Desactiva la paginación
            info: false,       // Opcional: Desactiva la información (ej. "Mostrando 1 a 10 de 100 registros")

            language: {
                url: '../assets/plugins/datatable/spanish.json'
            },
            ajax: {
                type:'POST',
                url: '../controlador/TALENTO_HUMANO/th_personasC.php?listaFace=true',
                data: function (d) {
                   
                     var parametros = {
                      id:PersonaId, // Parámetro personalizado
                  };
                  return { parametros: parametros };
                },
                dataSrc: ''
            },
            columns: [
                 { data: null,
                        render: function(data, type, item) {
                        return `<div class="d-flex align-items-center">
                                    <div class="">
                                        <img src="${item.imagen}" width="46" height="46" alt="">
                                    </div>
                                </div>`;
                        }
                    },   
                     { data: 'th_cardNo'},                
                    { data: null,
                        render: function(data, type, item) {
                         return ` <div class="list-inline d-flex customers-contacts ms-auto"> 
                                    <a href="javascript:;" class="list-inline-item bg-danger" onclick="deteleFaceBase('${item._id}')"><i class="bx bxs-trash"></i></a>  
                                    <a href="javascript:;" class="list-inline-item bg-success" title="Sincronizar en Biometrico" onclick="FaceInditaBio('${item._id}','${item.th_cardNo}')"><i class="bx bx-sync"></i></a>   
                                    <a href="javascript:;" class="list-inline-item bg-danger" onclick="deteleFaceBio('${item.th_cardNo}','${item._id}')"><i class="bx bxs-trash-alt"></i></a>
                               
                                </div>`;
                        }
                    },        

               
            ],
            order: [
                [1, 'asc']
            ],
        }));

    }


    function nuevofacial()
    {
        if($('#txt_tarjeta').val()=='')
        {
            Swal.fire("Ingrese una tarjeta primero","","info");
            return false;
        }
        $('#nuevofacial').modal('show');
    }

    function FaceInditaBio(idFace,CardNo)
    {
        $('#txt_cardNo').val(CardNo);
        $('#txt_id_reg').val(idFace);
        syncronizarPersona(7)
    }

    function addFaceBase()
    {
        var formData = new FormData();
        var archivo = $('#file_face')[0].files[0]; // obtener el archivo
        var CardNo = $('#ddl_tarjetas_facial').val();
        var idPerson =  PersonaId; 
        var detectado = $('#file_name_bio_face').text();

        // console.log(archivo)

        if(detectado=='' && archivo==undefined)
        {
            Swal.fire("No sea encontrado huella valida","","info")
            return false;
        }

        formData.append('huella', archivo); // adjuntar al formData
        formData.append('CardNo', CardNo);
        formData.append('idPerson', idPerson);
        formData.append('detectado', detectado);

        $.ajax({
           url: '../controlador/TALENTO_HUMANO/th_personasC.php?addFaceBase=true',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (response) {
                console.log(response)
                if(response==1)
                {
                    Swal.fire("Imagen facial agregada","","success");
                    $('#nuevofacial').modal('hide');

                    limpiar_facial();
                    listaFace()
                }else  if(response==-2)
                {
                    Swal.fire("Facia ya registrado","facial ya registrado para el numero de tarjeta seleccionada","error");
                    listaFace()
                }else
                {
                    Swal.fire(response.msj,"","info")
                }
            },
            error: function () {
                console.error('Error al subir el archivo');
            }
        });

    }


    function addFaceBio()
    {
         var parametros = 
        {
            '_id':$('#txt_id_reg').val(),
            'CardNo':$('#txt_cardNo').val(),
            'PersonaId':PersonaId,
            'dispositivo':$('#ddl_dispositivos').val(),
        }

        $.ajax({
            data: { parametros: parametros },
            url: '../controlador/TALENTO_HUMANO/th_personasC.php?addFaceBio2=true',
            type: 'post',
            dataType: 'json',
            success: function (response) {
                console.log(response)
                if(response.resp==1)
                {
                    Swal.fire("Imagen facial agregada","","success");
                    $('#sync_biometrico').modal('hide');
                    listaFace()
                }else
                {
                    Swal.fire(response.msj,"","info")
                }
            },
            error: function () {
                console.error('Error al subir el archivo');
            }
        });
    }


    function deteleFaceBase(idFace)
    {
        Swal.fire({
          title: 'Quiere eliminar este registro?',
          text: "Esta seguro de eliminar este registro!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si'
        }).then((result) => {
            if (result.value) {

                var parametros = 
                {
                    '_idFace':idFace,
                }
                $.ajax({
                    data: { parametros: parametros },
                    url: '../controlador/TALENTO_HUMANO/th_personasC.php?DeleteFaceBase=true',
                    type: 'post',
                    dataType: 'json',
                    success: function(response) {
                        if(response==1)
                        {
                            Swal.fire("Eliminado","","success").then(function(){
                                listaFace();
                            })
                        }
                    }
                });
            }
        })
    }

    function deteleFaceBio(CardNo,idFace)
    {
        $('#txt_cardNo').val(CardNo);
        $('#txt_id_reg').val(idFace);
        syncronizarPersona(8)
    }

    function deteleFace()
    {

        Swal.fire({
          title: 'Quiere eliminar este registro?',
          text: "Esta seguro de eliminar este registro!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si'
        }).then((result) => {
            if (result.value) {

                 var parametros = 
                {
                    'device':$('#ddl_dispositivos').val(),
                    'idPerson':PersonaId,
                    '_idFace':$('#txt_id_reg').val(),
                    'CardNo':$('#txt_cardNo').val(),
                }
                $.ajax({
                    data: { parametros: parametros },
                    url: '../controlador/TALENTO_HUMANO/th_personasC.php?DeleteFaceBio=true',
                    type: 'post',
                    dataType: 'json',
                    success: function(response) {
                        if(response['resp']==1)
                        {
                            Swal.fire("Eliminado","","success").then(function(){
                                $('#sync_biometrico').modal('hide');
                                listaFace();
                            })
                        }
                    }
                });
            }
        })
    }

function leerFace()
    {
        // $('#myModal_espera').modal('show');
        var parametros = 
        {
            'iddispostivos':$('#ddl_dispositivos').val(),
            'idPerson':PersonaId, //usuario id
        }
        $.ajax({
            data:  {parametros:parametros},
            url:   '../controlador/TALENTO_HUMANO/th_personasC.php?capturarFace=true',
            type:  'post',
            dataType: 'json',
            success:  function (response) { 
                console.log(response);
                $('#myModal_espera').modal('hide');
                if(response.resp==1)
                {
                    $('#img_face').prop('src',response.imagen);
                    $('#img_face').css('width','50%');
                    Swal.fire("Facial Guardada",response.patch,"success");
                    $('#file_name_bio_face').text(response.patch);
                    $('#sync_biometrico').modal('hide');
                }else
                {
                    Swal.fire("Facial",response.msj,"info");
                }

               
                     tbl_dispositivos.ajax.reload(null, false);

            } ,
              error: function(xhr, status, error) {
                console.log('Status: ' + status); 
                console.log('Error: ' + error); 
                console.log('XHR Response: ' + xhr.responseText); 

                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
                $('#myModal_espera').modal('hide');
            }         
        });
    }


    function limpiar_facial()
    {
        $('#img_face').prop('src',"../img/de_sistema/facial.png");
        $('#file_face').val("");
        $('#file-name-face').val("");
        $('#file_name_bio_face').val('');
        dispositivos();
    }
