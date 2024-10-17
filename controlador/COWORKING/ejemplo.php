<?php 
$controlador = new ejemplo();

if(isset($_GET['listar_mienbro']))
{
	echo json_encode($controlador->listar());
}

if(isset($_GET['categoria']))
{
	
}

/**
 * 
 */
class ejemplo
{
	
	function __construct()
	{
		// code...
	}

	function add()
	{
		//codigo
	}
	function listar()
	{	 
		$tr = '';
		for ($i=0; $i < 5; $i++) { 
			$tr.= '<tr>
					<td>1</td>
					<td>hola</td>
					<td>mundo</td>
					<td>1234</td>
					<td>
						<button class="btn btn-sm btn-danger"><i class="bx bx-trash"></i></button>
						<button class="btn btn-sm btn-danger"><i class="bx bx-camera"></i></button>
					</td>
				</tr>';
		}
		

	 	return $tr;
	}
	function eliminar()
	{
		//codigo
		// hola
	}
}

?>