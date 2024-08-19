<?php
$controlador= new crear_mienbrosC();
if(isset($_GET['lista_mienbro']))
{
	
	echo json_encode($controlador->listar());
}

class crear_mienbrosC
{
       function __construct()
        {

        }
       function compraslista()
       {
         $tr ='<tr>
                    
                <td><input type="text" class="form-control form-control-sm"></td>
                <td><input type="text" class="form-control form-control-sm"></td>
                <td><input type="number" class="form-control form-control-sm"></td>
                <td><input type="text" class="form-control form-control-sm"></td>
                <td><input type="text" class="form-control form-control-sm"></td>
                <td>
                    <button class="btn btn-danger btn-sm btn_eliminar_compra">
                          <i class="bx bx-x"></i>
                    </button>
                    <button class="btn btn-primary btn-sm btn_abrir_modal">
                          <i class="bx bx-x"></i>
                    </button> 
                </td>
             <tr>';
            return $tr;

       }
       function add()
       {
        
       }

       function eliminar()
       {
        return 'mundo';
       }
       
       function listar()
       {
       $tr = '<tr>
               <td><input type="text" class="form-control form-control-sm"></td>
               <td><input type="email" class="form-control form-control-sm"></td>
               <td><input type="text" class="form-control form-control-sm"></td>
               <td><input type="text" class="form-control form-control-sm"></td>
               
               <td>
                    <button class="btn btn-danger btn-sm btn_eliminar_compra">
                         <i class="bx bx-x"></i>
                    </button>
                    <button class="btn btn-primary btn-sm btn_abrir_modal">
                         <i class="bx bx-save"></i>
                    </button>
               </td>
             </tr>';
         return $tr;
        }

}
?>