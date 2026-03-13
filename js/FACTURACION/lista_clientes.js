$(document).ready(function () {


});

function lista_cliente()
{
    var parametros = 
    {
        'query':''
    }
    $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/FACTURACION/cliente_facturaC.php?lista_clientes=true',      
        type:  'post',
        dataType: 'json',
        success:  function (response) { 

            var lista = ``;

            response.forEach(function(item,i){
                lista+=`<div class="col">
                        <div class="card radius-15">
                            <div class="card-body text-center">
                                <div class="p-4 border radius-15">
                                    <img src="`+item.th_per_foto_url+`" width="110" height="110" class="rounded-circle shadow" alt="">
                                    <h6 class="mb-0 mt-5">`+item.th_per_nombres_completos+`</h6>
                                    <p class="mb-3">`+item.th_per_cedula+`</p>
                                    <div class="d-grid"> <button onclick="ver_detalles_cliente('`+item.th_per_id+`')" class="btn btn-outline-primary radius-15">Detalles</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`;
            })

            $('#pnl_lista_cliente').html(lista);
            console.log(response);         
        }
      });
}

function ver_detalles_cliente(id)
{
    
}