<script type="text/javascript">
 $( document ).ready(function() {
 	 ListaEtiquetas()  
 })

	function ListaEtiquetas()
    {
      
        $.ajax({
            url:  '../controlador/DISENIADOR_ZPL/di_diseniadorC.php?ListaEtiquetas=true',
            type:  'post',
            dataType: 'json',  
             // data:{parametros:parametros},        
            success:  function (response) {
               console.log(response);
               var tr = '';
               response.forEach(function(item,i)
               {
               		tr+=`<tr>
               			<td>`+(i+1)+`</td>
               			<td><a href="inicio.php?mod=2013&acc=di_diseniador&id=`+item.ac_disenio_tag_id+`">`+item.ac_disenio_tag_nombre+`</a></td>
               			<td>`+item.ac_disenio_tag_creacion+`</td>
               			<td>
               				<button class="btn btn-sm btn-danger" onclick="eliminar_etiqueta('`+item.ac_disenio_tag_id+`')"><i class="bx bx-trash me-0"></i></button>
               				<a href="inicio.php?mod=2013&acc=di_diseniador&id=`+item.ac_disenio_tag_id+`" class="btn btn-sm btn-primary"><i class="bx bx-pencil me-0"></i></a>
               			</td>
               		</tr>`;
               })

               $('#tbl_body').html(tr);
            },
            error: function (error) {
                
               $('#modal_print').modal('hide');
              console.error('Error en numero_comprobante:', error);
              // Puedes manejar el error aquí si es necesario
            },
        });

	}

  function eliminar_etiqueta(id)
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
          eliminar(id);
        }
    })

  }

  function eliminar(id)
  {
      var parametros = 
    {
      'id':id,
    }
    $.ajax({
         data:  {parametros:parametros},
         url:  '../controlador/DISENIADOR_ZPL/di_diseniadorC.php?deleteEtiquetas=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) { 
           if(response==1)
           {
             Swal.fire("Registro eliminado","","success");
             ListaEtiquetas();
           }         
           
          } 
          
       });
  }

  
</script>
<div class="page-wrapper">
      <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
          <div class="breadcrumb-title pe-3">Forms</div>
          <div class="ps-3">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Wizard</li>
              </ol>
            </nav>
          </div>
          <!-- <div class="ms-auto">
            <div class="btn-group">
              <button type="button" class="btn btn-primary">Settings</button>
              <button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"> <span class="visually-hidden">Toggle Dropdown</span>
              </button>
              <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">  <a class="dropdown-item" href="javascript:;">Action</a>
                <a class="dropdown-item" href="javascript:;">Another action</a>
                <a class="dropdown-item" href="javascript:;">Something else here</a>
                <div class="dropdown-divider"></div>  <a class="dropdown-item" href="javascript:;">Separated link</a>
              </div>
            </div>
          </div> -->
        </div>
        <!--end breadcrumb-->
        <div class="row">
          <div class="col-xl-12 mx-auto">
            <hr>
            <div class="card">
              <div class="card-body">
              	<div class="row">
              		<div class="col-sm-12">
              			<button class="btn btn-primary btn-sm"><i class="bx bx-plus me-0"></i> Nuevo</button>
              		</div>
              		<div class="col-sm-12">
              			<table class="table table-sm">
              				<thead>
              					<th>Item</th>
              					<th>Nombre</th>
              					<th>Fecha</th>
              					<th></th>
              				</thead>
              				<tbody id="tbl_body">
              					
              				</tbody>
              			</table>
              			
              		</div>
              		
              	</div>
               
              </div>
            </div>
          </div>
        </div>
        <!--end row-->
      </div>
    </div>

