<?php @session_start(); $id='';?>
<script type="text/javascript">
    var usu = '<?php echo $_SESSION['INICIO']['ID_USUARIO']; ?>' ;
    var empresa = '<?php echo $_SESSION['INICIO']['ID_EMPRESA']; ?>' ;
    var estadofac = '<?php echo $_GET['estado']; ?>' 
</script>
<script src="../js/lista_facturas.js"></script> 
<script type="text/javascript">
    $(document).ready(function () {
        var id = '<?php if(isset($_GET['id'])){$id = $_GET['id']; echo $_GET['id']; };?>';
        $('#txt_fac').val(id);
        console.log(id);
        detalle_factura(id);
        categorias();     
        
        if(estadofac=='P' || estadofac=='' && id!='')
        {
            $('#btn_eliminar').css('display','initial');
        }

        if(estadofac=='A')
        {
            $('#btn_modal_guia').css('display','initial');
        }
     });
</script>
<!-- Begin Page Content -->
<main class="content">
    <div class="container-fluid p-0">
        <h1 class="h3 mb-3"><strong>Factura </strong><strong id="lbl_anulado" style="display:none;"><u>ANULADO</u></strong></h1>
    <div class="row">
    	<div class="col-sm-10">    		
    		<a class="btn btn-default btn-sm" style="border: 1px solid;" href="lista_facturas.php"><i class="fa fa-arrow-left"></i> Regresar</a>
            <button class="btn btn-default btn-sm" style="border: 1px solid;" onclick="pdf_factura()"><i class="fa fa-print"></i> Imprimir</button>
            <button class="btn btn-warning btn-sm" style="border: 1px solid;" id="btn_autorizar" onclick="autorizar()"><i class="fa fa-paper-plane"></i> Autorizar</button>
            <button class="btn btn-warning btn-sm" style="border: 1px solid; display: none;" id="btn_modal_guia" onclick="modal_guia()"><i class="fa fa-paper-plane"></i> Generar guia de remision</button>
            <button class="btn btn-info btn-sm" style="border: 1px solid;" onclick="modal_email()"><i class="fa fa-envelope"></i> Enviar</button>
            <button class="btn btn-primary btn-sm" id="btn_marcar_pagado" style="border: 1px solid;" onclick="pagada(<?php echo $id; ?>)"><i class="fa fa-money-bill"></i> Marcar como cobrada</button>
            <button class="btn btn-danger btn-sm" style="border: 1px solid;display: none;" id="btn_sri_error" onclick="modal_error_seri($('#txt_autorizacion').text(),'FACTURAS')"><i class="fa fa-eye"></i> Ver error en xml</button>      
    	</div>
        <div class="col-sm-2 text-end">
        <button class="btn btn-sm btn-danger" style="display:none;" id="btn_eliminar" onclick="eliminar_factura(<?php echo $id; ?>)"><i class="fa fa-trash"></i> Eliminar</button>    
        <button class="btn btn-sm btn-danger" style="display:none;" id="btn_anular" onclick="anular_factura(<?php echo $id; ?>)"><i class="fa fa-times-circle"></i> Anular</button>          
        </div>                   	
    </div>
    <hr>
    <div class="row">
        <div class="col-sm-4">
           <b> Cliente:</b>
             <h1 class="h3 mb-1 text-gray-800" id="txt_nombre">Home</h1> 
            <div class="row">
                <div class="col-sm-6">
                   <b>C.I. / R.U.C</b><br>
                   <label  id="txt_ci_ruc"></label>
                </div>
                <div class="col-sm-6">
                    <b>Fecha Emision:</b>
                    <input type="date" name="txt_fecha" id="txt_fecha" class="form-control form-control-sm">              
                </div>
            </div>            
         </div>
         <div class="col-sm-6">
            <b>Autorizacion:</b><br>
            <label id="txt_autorizacion"></label><br>
            <div class="row">
                <div class="col-sm-4">
                     <b>Estado</b><br>
                     <label id="txt_estado"></label>
                     <label id="txt_estadoP" style="background:coral; color: white;" >Factura no cobrada</label>
                </div>                 
                <div class="col-sm-3">
                    <b>Telefono</b>
                    <input type="text" name="txt_telefono" id="txt_telefono" class="form-control form-control-sm">
                </div> 
                <div class="col-sm-5">
                    <b>Email:</b>
                    <input type="text" name="txt_email_p" id="txt_email_p" class="form-control form-control-sm">              
                </div>                               
            </div>
           
            <!-- <input type="date" name="" id="" class="form-control form-control-sm"> -->
        </div>       
        <div class="col-sm-2 text-center">
            <b>No. Factura:</b><br>
            <div class="btn-group btn-group-sm">
                <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <label id="txt_serie"></label>
                </button>
                <button class="btn btn-sm btn-outline-primary" style="display:none;" id="btn_guardar_serie" title="Guardar Serie" onclick="guardar_serie()"><i class="fa fa-save"></i></button>
                <button class="btn btn-sm btn-outline-info" style="display:none;" id="btn_recargar" onclick="location.reload()"><i class="fa fa-close"></i></button>
                <div class="dropdown-menu" id="opciones">
                </div>
            </div>
             <h1 class="h3 mb-4 text-gray-800" id="txt_NoFac">0</h1>             
        </div>
        <div class="col-sm-2">
            <b>Forma de pago</b>
        </div>
        <div class="col-sm-6">
            <select class="" style="width: 100%;" id="DCTipoPago">
                <option value="">Seleccione tipo de pago</option>
            </select>
        </div>
    </div>    
    <div class="row">        
          
        
      <!--   <div class="col-sm-2">
            <input type="date" name="" id="" class="form-control form-control-sm">
        </div> -->
        
    </div> 
    <hr>
    <div class="row">
         <div class="col-lg-12">
            <!-- Basic Card Example -->
            <div class="card shadow mb-8">
                <!-- <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Basic Card Example</h6>
                </div> -->
                <div class="card-body">
                    <div class="row" id="form_add_producto">
                        <input type="hidden" name="txt_fac" id="txt_fac">
                        <input type="hidden" name="txt_llevaiva" id="txt_llevaiva">
                        <input type="hidden" name="txt_idpro" id="txt_idpro">
                        <div class="col-sm-2">
                            <b>Codigo</b>
                            <input type="text" name="txt_codigo" id="txt_codigo" class="form-control form-control-sm" onfocus="abrir_productos('ref')">
                        </div>
                        <div class="col-sm-5">
                            <b>Producto</b>
                            <input type="text" name="txt_articulo" id="txt_articulo" class="form-control form-control-sm" onfocus="abrir_productos('det')" onkeyup="validar_campo()">
                        </div>
                        <div class="col-sm-1">
                            <b>Cant</b>
                            <input type="text" name="txt_can" id="txt_can" class="form-control form-control-sm" value="0" onblur="calcular()">
                        </div>
                        <div class="col-sm-1">
                            <b>P.V.P</b>
                            <input type="text" name="txt_pvp" id="txt_pvp" class="form-control form-control-sm" value="0.00" onblur="calcular()">
                        </div>
                        <div class="col-sm-1" style="padding: 0px;">
                            <b>Iva <label id="lbl_iva" style="padding:0px;margin: 0px;"><?php echo $_SESSION['INICIO']['IVA']?></label>%</b>
                            <input type="text" name="txt_iva" id="txt_iva" class="form-control form-control-sm" readonly value="0.00">
                        </div>
                        <div class="col-sm-1" style="padding: 0px;">
                            <b>% Dscto</b>
                            <input type="text" name="txt_dcto" id="txt_dcto" class="form-control form-control-sm" value="0.00" onblur="calcular()">
                        </div>
                        <div class="col-sm-1">
                            <b>SubTot</b>
                            <input type="text" name="txt_sub" id="txt_sub" class="form-control form-control-sm" readonly value="0.00">
                        </div>
                        <div class="col-sm-6">
                            <b>Observacion</b>
                            <input type="text" class="form-control form-control-sm" placeholder="" id="txt_detalle" name="txt_detalle">
                        </div>
                        <div class="col-sm-2">
                            
                        </div>
                        <div class="col-sm-2">
                            <b>Total</b>
                            <input type="text" name="txt_total" id="txt_total" class="form-control form-control-sm" readonly value="0.00">
                        </div> 
                        <div class="col-sm-2 text-end">
                            <br>
                            <button class="btn btn-primary btn-sm" onclick="agregar_factura()"><i class="fa fa-arrow-down"></i> Agregar</button>
                        </div>                       
                    </div>
                    <div class="row">
                        <div class="col-12 col-lg-12">
                            <div class="tab tab-secondary">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" href="#colored-icon-1" data-bs-toggle="tab" role="tab" aria-selected="true">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-package align-middle me-2"><line x1="16.5" y1="9.4" x2="7.5" y2="4.21"></line><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                                            Detalle
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation" id="btn_guia" style="display:none;">
                                        <a class="nav-link" href="#colored-icon-2" data-bs-toggle="tab" role="tab" aria-selected="false" tabindex="-1">
                                           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-truck align-middle me-2"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle>
                                           </svg>
                                            Guia de remision <br>
                                            <span class="badge bg-danger rounded-pill" id="alerta_no_auto" style="display:none">No autorizado</span>
                                        </a>
                                    </li>                                    
                                </ul>
                                <div class="tab-content" style="background: #fff;color: #353738;">
                                    <div class="tab-pane active" id="colored-icon-1" role="tabpanel">
                                        <div class="card-body" style="padding: 15px 0px 0px 0px;">                            
                                            <div class="row" style="overflow-y: scroll; height:350px">
                                                <div class="col-sm-12">
                                                    <table class="table table-bordered dataTable">
                                                        <thead class="text-primary">
                                                            <th></th>
                                                            <th width="30%">PRODUCTO</th>
                                                            <th>CANT</th>
                                                            <th>PRECIO</th>
                                                            <th>IVA</th>
                                                            <th>DCTO</th>
                                                            <th>SUBTOTAL</th>
                                                            <th>TOTAL</th>
                                                            <th>OBSERVACION</th>
                                                            <th width="10%">IMG</th>
                                                        </thead>
                                                        <tbody id="tbl_lineas">
                                                           
                                                            
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <hr>
                                                <div class="col-sm-6">
                                                    <textarea class="form-control-sm form-control" style="resize:none; height: 100px;" placeholder="Datos Adicionales" id="txt_datos_adicionales" name="txt_datos_adicionales"></textarea>
                                                </div>
                                                <div class="col-sm-6">
                                                    <table class="table table-bordered dataTable table-sm text-center">
                                                        <tr class="text-primary">                                   
                                                            <td>TOTAL DCTO</td>
                                                            <td>TOTAL IVA</td>
                                                            <td>SUBTOTAL</td>
                                                            <td>TOTAL</td>
                                                        </tr>
                                                        <tr>                                            
                                                            <td><label style="margin:0px" id="lbl_totDcto">0.00</label></td>
                                                            <td><label style="margin:0px" id="lbl_totIva">0.00</label></td>
                                                            <td><label style="margin:0px" id="lbl_subtot">0.00</label></td>
                                                            <td><label style="margin:0px" id="lbl_tot">0.00</label></td>
                                                        </tr>                                                        
                                                                                                  
                                                    </table>
                                                </div>                      
                                            </div>                  
                                          </div>                                        
                                    </div>
                                    <div class="tab-pane" id="colored-icon-2" role="tabpanel">
                                        <div class="alert alert-danger alert-dismissible" role="alert" id="alerta_error" style="display:none;">
                                            <div class="alert-message">
                                                 <button class="btn btn-default btn-sm" style="border: 1px solid;" id="btn_modal_guia" onclick="pdf_guia()"><i class="fa fa-file"></i> Mirar error</button>
                                                 <button class="btn btn-primary btn-sm" style="border: 1px solid;" id="btn_modal_guia" onclick="modal_guia_editar()"><i class="fa fa-paper-plane"></i> Editar guia de remision</button>
                                                 <button class="btn btn-default btn-sm" style="border: 1px solid;" id="btn_modal_guia" onclick="pdf_guia()"><i class="fa fa-file"></i> Imprimir</button><br>
                                                <strong>No autorizado!</strong> la guia de remision no se pudo autorizar
                                            </div>
                                        </div>
                                          <div class="alert alert-success alert-dismissible" role="alert" id="alerta_success" style="display:none;">
                                            <div class="alert-message">
                                                <strong>Autorizado!</strong> la guia de remision fue autorizada
                                                <button class="btn btn-warning btn-sm" style="border: 1px solid;" id="btn_modal_guia" onclick="pdf_guia()"><i class="fa fa-file"></i> Imprimir</button>
                                            </div>
                                        </div>

                                       

                                        
                                        <div class="row">
                                              <input type="hidden" name="txt_idGR" id="txt_idGR">
                                              <div class="col-sm-4">
                                                <b style="padding: 0px">Fecha de emision de guia</b>                          
                                                <p id="lbl_fechagr">2223-02-15</p>
                                              </div>
                                              <div class="col-sm-4">
                                                  <b style="padding: 0px">Serie.</b><br>
                                                   <p id="lbl_seriegr">2223-02-15</p>
                                              </div>
                                              <div class="col-sm-4" style="padding: 0px">
                                                  <b style="padding: 0px">No. Guia remision.</b><br>
                                                  <p id="lbl_numeroguia">2223-02-15</p>
                                              </div>
                                              <div class="col-sm-12">
                                                  <b>AUTORIZACION GUIA DE REMISION</b>
                                                  <p id="lbl_autorizaciongr">2223-02-15</p>
                                              </div>
                                              <div class="col-sm-6">
                                                    <b  style="padding: 0px">Iniciacion del traslados</b>
                                                     <p id="lbl_fechaini">2223-02-15</p>
                                              </div>
                                              <div class="col-sm-6">
                                                  <b style="padding: 0px">Ciudad</b>                          
                                                      <p id="lbl_ciudadini">2223-02-15</p>
                                              </div>
                                              <div class="col-sm-6">
                                                    <b style="padding: 0px">Finalizacion del traslados</b>
                                                    <p id="lbl_fechafin">2223-02-15</p>
                                                
                                              </div>
                                              <div class="col-sm-6">
                                                  <b  style="padding: 0px">ciudad</b>
                                                   <p id="lbl_ciudadfin">2223-02-15</p>
                                              </div>
                                              <div class="col-sm-12">
                                                  <b>Nombre o razon socila (Transportista)</b>
                                                   <p id="lbl_razon">2223-02-15</p>
                                              </div>
                                              <div class="col-sm-12">
                                                  <b>Empresa de Transporte</b>
                                                   <p id="lbl_entrega">2223-02-15</p>
                                              </div>
                                              <div class="col-sm-4">
                                                  <b>Placa</b>
                                                   <p id="lbl_placa">2223-02-15</p>
                                              </div>
                                              <div class="col-sm-4">
                                                  <b>Pedido</b>
                                                   <p id="lbl_pedido">2223-02-15</p>
                                              </div>
                                              <div class="col-sm-4">
                                                  <b>Zona</b>
                                                   <p id="lbl_zona">2223-02-15</p>
                                              </div>
                                              <div class="col-sm-12">
                                                  <b>Lugar entrega</b>
                                                   <p id="lbl_lugar">2223-02-15</p>
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
</main>
<!-- Button to Open the Modal -->

<!-- The Modal -->
<div class="modal" id="myModal_productos" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header" style="padding:1%">
        <h5 class="modal-title">Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <div class="row">
            <div class="col-sm-2">
                <label><input type="radio" name="opc" id="OpcP" checked value="P"  onclick="lista_articulos()">Producto</label>
                <label><input type="radio" name="opc" id="OpcS" value="S" onclick="lista_articulos()">Servicio</label>
            </div>
            <div class="col-sm-3">
                <b>Referencia</b>
                <input type="text" name="txt_ref" id="txt_ref" class="form-control form-control-sm" onkeyup="lista_articulos()">
            </div>
            <div class="col-sm-4">
                <b>Producto</b>
                <input type="text" name="txt_query" id="txt_query" class="form-control form-control-sm" onkeyup="lista_articulos()">
            </div>
            <div class="col-sm-3">
                <b>Categoria</b><br>
                <select class="form-control-sm form-control" style="width:100%" id="ddl_categoria" name="ddl_categoria" onchange="lista_articulos()">
                    <option value="">Categoria</option>
                </select>
            </div>            
        </div>
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-bordered dataTable table-sm">
                    <thead class="text-primary">
                        <th>Referencia</th>
                        <th>Producto</th>
                        <th>Stock</th>
                        <th>Precio</th>
                        <th>Peso</th>
                        <th>Medida</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Categoria</th>
                    </thead>
                    <tbody id="tbl_productos">
                       
                        
                    </tbody>
                </table>
            </div>       
            
        </div>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick="limpiar()">Close</button>
      </div>

    </div>
  </div>
</div>



<div class="modal fade" id="myModal_email" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Enviar email</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body m-3">
                <div class="row"> 
                  <form enctype="multipart/form-data" id="form_img" method="post" class="col-sm-12">
                    <div class="col-sm-12">
                        <div id="emails-input" name="emails-input" placeholder="añadir email"></div>
                        <input type="hidden" name="txt_to" id="txt_to">
                        <input type="hidden" name="txt_fac_ema" id="txt_fac_ema">
                    </div>
                    <div class="col-sm-12">
                        <textarea class="form-control" rows="3" style="resize:none" placeholder="Texto" id="txt_texto" name="txt_texto" ></textarea>
                    </div>                                                  
                    <div class="col-sm-8">
                        <input type="file" name="file_adjunto" id="file_adjunto" class="form-control">                       
                    </div> 
                    <div class="col-sm-3">
                        <label><input type="checkbox" name="cbx_factura" id="cbx_factura" checked>Enviar Factura</label>                        
                    </div>  
                  </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="enviar_email()" >Enviar</button>
            </div>
        </div>
    </div>
</div>




  <script src="../js/utils.js"></script>
  <script src="../js/emails-input.js"></script>
  <script src="../js/multiple_email.js"></script>



<div class="modal fade" id="myModal_guia" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Guia de remision</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
              <div class="modal-body">
                <form id="form_guia">
                  <div class="row">
                      <div class="col-sm-4">
                        <b style="padding: 0px">Fecha de emision de guia</b>                          
                        <input type="date" name="MBoxFechaGRE" id="MBoxFechaGRE" class="form-control input-xs"
                                  value="<?php echo date('Y-m-d'); ?>" onblur="MBoxFechaGRE_LostFocus()">
                      </div>
                      <div class="col-sm-4">
                          <b style="padding: 0px">Serie.</b><br>
                          <input type="text" name="LblSerieGuiaR" id="LblSerieGuiaR" class="form-control input-xs"
                                  value="<?php echo $_SESSION['INICIO']['SERIE']; ?>" readonly>
                      </div>
                      <div class="col-sm-4" style="padding: 0px">
                          <b style="padding: 0px">No. Guia remision.</b><br>
                          <input type="text" name="LblGuiaR" id="LblGuiaR" class="form-control input-xs"
                              value="000000" readonly>
                      </div>
                      <div class="col-sm-12">
                          <b>AUTORIZACION GUIA DE REMISION</b>
                          <input type="text" name="LblAutGuiaRem" id="LblAutGuiaRem" class="form-control input-xs"
                              value="0" readonly>
                      </div>
                      <div class="col-sm-6">
                            <b  style="padding: 0px">Iniciacion del traslados</b>
                            <input type="date" name="MBoxFechaGRI" id="MBoxFechaGRI" class="form-control input-xs"
                                  value="<?php echo date('Y-m-d'); ?>">
                      </div>
                      <div class="col-sm-6">
                          <b style="padding: 0px">Ciudad</b>                          
                              <select class="form-select input-xs" id="DCCiudadI" name="DCCiudadI" style="width:100%">
                                  <option value=""></option>
                              </select>
                      </div>
                      <div class="col-sm-6">
                            <b style="padding: 0px">Finalizacion del traslados</b>
                            <input type="date" name="MBoxFechaGRF" id="MBoxFechaGRF" class="form-control input-xs"
                                  value="<?php echo date('Y-m-d'); ?>">
                        
                      </div>
                      <div class="col-sm-6">
                          <b  style="padding: 0px">ciudad</b>
                          <select class="form-control input-xs" id="DCCiudadF" name="DCCiudadF" style="width:100%">
                              <option value=""></option>
                          </select>
                      </div>
                      <div class="col-sm-12">
                          <b>Nombre o razon socila (Transportista)</b>
                          <select class="form-select input-xs" id="DCRazonSocial" name="DCRazonSocial" style="width:100%">
                              <option value=""></option>
                          </select>
                      </div>
                      <div class="col-sm-12">
                          <b>Empresa de Transporte</b>
                          <select class="form-select form-select-sm" id="DCEmpresaEntrega" name="DCEmpresaEntrega" style="width:100%">
                              <option value=""></option>
                          </select>
                      </div>
                      <div class="col-sm-4">
                          <b>Placa</b>
                          <input type="text" name="TxtPlaca" id="TxtPlaca" class="form-control input-xs"
                              value="XXX-999">
                      </div>
                      <div class="col-sm-4">
                          <b>Pedido</b>
                          <input type="text" name="TxtPedido" id="TxtPedido" class="form-control input-xs">
                      </div>
                      <div class="col-sm-4">
                          <b>Zona</b>
                          <input type="text" name="TxtZona" id="TxtZona" class="form-control input-xs">
                      </div>
                      <div class="col-sm-12">
                          <b>Lugar entrega</b>
                          <input type="text" name="TxtLugarEntrega" id="TxtLugarEntrega" class="form-control input-xs">
                      </div>
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                  <button class="btn btn-primary" onclick="guardar_guia()">Generar y autorizar</button>
                  <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cerrar</button>
              </div>
          </div>

      </div>
  </div>           