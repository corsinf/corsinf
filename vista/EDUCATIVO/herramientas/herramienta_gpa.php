<script type="text/javascript">
 $( document ).ready(function() {

  
 })


   function gpa_pdf(id=0){
      var datos = todos;
      if(datos.length==0)
      {
        Swal.fire("","Ingrese notas escolares","info");
        return false;
      }
      var jsonArray = JSON.stringify(datos);
      var encodedArray = encodeURIComponent(jsonArray);
          var url = '../controlador/EDUCATIVO/herramientas_gpaC.php?gpa_pdf&idioma='+id+'&data='+encodedArray;                 
         window.open(url, '_blank');
    }

    function gpa_pdf2(id=0){
       
      var datos = todos2;
      if(datos.length==0)
      {
        Swal.fire("","Ingrese notas escolares","info");
        return false;
      }
      var jsonArray = JSON.stringify(datos);
      var encodedArray = encodeURIComponent(jsonArray);
          var url = '../controlador/EDUCATIVO/herramientas_gpaC.php?gpa_pdf&idioma='+id+'&data='+encodedArray;                 
         window.open(url, '_blank');
    }

    function gpa_pdf3(id=0){
      var datos = todos3;
      if(datos.length==0)
      {
        Swal.fire("","Ingrese notas escolares","info");
        return false;
      }
      var jsonArray = JSON.stringify(datos);
      var encodedArray = encodeURIComponent(jsonArray);
          var url = '../controlador/EDUCATIVO/herramientas_gpaC.php?gpa_pdf&idioma='+id+'&data='+encodedArray;                 
         window.open(url, '_blank');
    }
  
</script>
<div class="page-wrapper">
      <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
          <div class="breadcrumb-title pe-3">Herramienta</div>
          <div class="ps-3">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">GPA</li>
              </ol>
            </nav>
          </div>        
        </div>
        <!--end breadcrumb-->
        <div class="row">
          <div class="col-xl-12 mx-auto">
            <!-- <h6 class="mb-0 text-uppercase">Form Wizard</h6> -->
            <hr>
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col-sm-12">
                      <div class="row">
                        <div class="col-sm-12">
                          <select class="form-select">
                            <option value="0" disabled> Seleccione un estudiantes</option>
                            <option>Estudiante Prueba</option>
                          </select>
                        </div>
                        <div class="col-sm-12">
                          <div class="card-body">
                            <hr/>
                            <div class="row">
                               
                                      <div class="accordion" id="accordionExample">
                                        <div class="accordion-item">
                                          <h2 class="accordion-header" id="headingOne">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            Primero de bachillerato
                                        </button>
                                      </h2>
                                          <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                              <div class="row">
                                                <div class="col-sm-12 text-end">                                                  
                                                  <button id="gpa_pdf" class="btn btn-primary btn-sm" onclick="gpa_pdf(1)" type="button">generate document</button>
                                                  <button id="gpa_pdf" class="btn btn-primary btn-sm" onclick="gpa_pdf()" type="button">Generar documento</button>   
                                                </div> 
                                              </div> 
                                              <div class="row gy-3">
                                                <div class="col-md-4">
                                                  Area
                                                  <select class="form-select" id="todo-area">
                                                      <option value="Matematicas">Matematicas</option>
                                                      <option value="Ciencia Naturales">Ciencias Naturales</option>
                                                      <option value="Ciencias Sociales">Ciencias Sociales</option>
                                                      <option value="Lenguaje y Literatura">Lenguaje y Literatura</option>
                                                      <option value="Lenguaje Extrangero">Lenguaje Extrangero</option>
                                                      <option value="Educacion Cultura y Artistica">Educacion Cultura y Artistica</option>
                                                      <option value="Educacion Fisica">Educacion Fisica</option>
                                                      <option value="Estudios Interdisiplinario">Estudios Interdisiplinario</option>
                                                      <option value="Educacion Religiosa">Educacion Religiosa</option>
                                                      <option value="Investigacion">Investigacion</option>
                                                      <option value="Matematicas">Matematicas</option>
                                                      <option value="Matematicas">Matematicas</option>

                                                  </select>
                                                </div>
                                                <div class="col-md-5">
                                                  Materia
                                                  <input id="todo-input" type="text" class="form-control" value="">
                                                </div>
                                                <div class="col-md-2">
                                                  Nota
                                                  <input id="todo-nota" type="text" class="form-control" value="">
                                                </div>
                                                <div class="col-md-1">
                                                  <br>
                                                  <button type="button" onclick="CreateTodo();" class="btn btn-primary btn-sm">Add</button>
                                                </div>
                                              </div>
                                            </div>
                                            <div class="form-row mt-3">
                                                <div class="col-12">
                                                      <div id="todo-container"></div>
                                                </div>
                                              </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                          <h2 class="accordion-header" id="headingTwo">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        Segundo de Bachillerato
                                        </button>
                                          </h2>
                                          <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                              <div class="row">
                                                <div class="col-sm-12 text-end">                                                  
                                                  <button id="gpa_pdf" class="btn btn-primary btn-sm" onclick="gpa_pdf2(1)" type="button">generate document</button>
                                                  <button id="gpa_pdf" class="btn btn-primary btn-sm" onclick="gpa_pdf2()" type="button">Generar documento</button>   
                                                </div> 
                                              </div>
                                              <div class="row gy-3">
                                                <div class="col-md-4">
                                                  Area
                                                  <select class="form-select" id="todo-area2">
                                                      <option value="Matematicas">Matematicas</option>
                                                      <option value="Ciencia Naturales">Ciencias Naturales</option>
                                                      <option value="Ciencias Sociales">Ciencias Sociales</option>
                                                      <option value="Lenguaje y Literatura">Lenguaje y Literatura</option>
                                                      <option value="Lenguaje Extrangero">Lenguaje Extrangero</option>
                                                      <option value="Educacion Cultura y Artistica">Educacion Cultura y Artistica</option>
                                                      <option value="Educacion Fisica">Educacion Fisica</option>
                                                      <option value="Estudios Interdisiplinario">Estudios Interdisiplinario</option>
                                                      <option value="Educacion Religiosa">Educacion Religiosa</option>
                                                      <option value="Investigacion">Investigacion</option>
                                                      <option value="Matematicas">Matematicas</option>
                                                      <option value="Matematicas">Matematicas</option>

                                                  </select>
                                                </div>
                                                <div class="col-md-5">
                                                  Materia
                                                  <input id="todo-input2" type="text" class="form-control" value="">
                                                </div>
                                                <div class="col-md-2">
                                                  Nota
                                                  <input id="todo-nota2" type="text" class="form-control" value="">
                                                </div>
                                                <div class="col-md-1">
                                                  <br>
                                                  <button type="button" onclick="CreateTodo2();" class="btn btn-primary btn-sm">Add</button>
                                                </div>
                                              </div> 
                                            </div>

                                            <div class="form-row mt-3">
                                                <div class="col-12">
                                                      <div id="todo-container2"></div>
                                                </div>
                                              </div>
                                          </div>
                                        </div>
                                        <div class="accordion-item">
                                          <h2 class="accordion-header" id="headingThree">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        Tercero de bachillerato
                                        </button>
                                      </h2>
                                          <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                               <div class="row">
                                                <div class="col-sm-12 text-end">                                                  
                                                  <button id="gpa_pdf" class="btn btn-primary btn-sm" onclick="gpa_pdf3(1)" type="button">generate document</button>
                                                  <button id="gpa_pdf" class="btn btn-primary btn-sm" onclick="gpa_pdf3()" type="button">Generar documento</button>   
                                                </div> 
                                              </div>
                                              <div class="row gy-3">
                                                <div class="col-md-4">
                                                  Area
                                                  <select class="form-select" id="todo-area3">
                                                      <option value="Matematicas">Matematicas</option>
                                                      <option value="Ciencia Naturales">Ciencias Naturales</option>
                                                      <option value="Ciencias Sociales">Ciencias Sociales</option>
                                                      <option value="Lenguaje y Literatura">Lenguaje y Literatura</option>
                                                      <option value="Lenguaje Extrangero">Lenguaje Extrangero</option>
                                                      <option value="Educacion Cultura y Artistica">Educacion Cultura y Artistica</option>
                                                      <option value="Educacion Fisica">Educacion Fisica</option>
                                                      <option value="Estudios Interdisiplinario">Estudios Interdisiplinario</option>
                                                      <option value="Educacion Religiosa">Educacion Religiosa</option>
                                                      <option value="Investigacion">Investigacion</option>
                                                      <option value="Matematicas">Matematicas</option>
                                                      <option value="Matematicas">Matematicas</option>

                                                  </select>
                                                </div>
                                                <div class="col-md-5">
                                                  Materia
                                                  <input id="todo-input3" type="text" class="form-control" value="">
                                                </div>
                                                <div class="col-md-2">
                                                  Nota
                                                  <input id="todo-nota3" type="text" class="form-control" value="">
                                                </div>
                                                <div class="col-md-1">
                                                  <br>
                                                  <button type="button" onclick="CreateTodo3();" class="btn btn-primary btn-sm">Add</button>
                                                </div>
                                              </div> 
                                            </div>

                                            <div class="form-row mt-3">
                                                <div class="col-12">
                                                      <div id="todo-container3"></div>
                                                </div>
                                              </div> 
                                              
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                            </div>                            
                            
                          </div>
                        </div>
                      </div>
                  </div>           
                </div>
               
               
              </div>
            </div>
          </div>
        </div>
        <!--end row-->
      </div>
    </div>

  <script src="../assets/js/app-to-do.js"></script>