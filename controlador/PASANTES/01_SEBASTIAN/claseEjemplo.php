<?php 
require_once(dirname(__DIR__,2).'/modelo/COWORKING/claseEjemploM.php');

$controlador = new claseEjemplo();

if(isset($_GET['categoria']))
{
	echo json_encode($controlador->lista());
}
if(isset($_GET['listaIngresos']))
{
	echo json_encode($controlador->listaIngresos());
}

if(isset($_GET['add'])){
	$data = $_POST['data'];
	//$data1 = $_POST['data1'];
	// print_r($data)
	echo json_encode($controlador->add($data));
}







/**
 * 
 */
class claseEjemplo
{
	private $modelo;

	function __construct()
	{
		$this->modelo = new claseEjemploM();
	}


	function add($parametros){
		$res = $this->modelo->insertarnombre($parametros['primer_nombre']);
		return $res;
	}

	function lista()
	{
		$lista = array( array('Nombre' =>'hola', 'id'=>1),
						array('Nombre' =>'hola2', 'id'=>2),
						array('Nombre' =>'hola3', 'id'=>3),
						array('Nombre' =>'hola4', 'id'=>4),
						array('Nombre' =>'hola5', 'id'=>5),
					);
		$select = '';
		foreach ($lista as $key => $value) {
			$select.='<option value="'.$value['id'].'">'.$value['Nombre'].'</option>';
		}


		// $select = '<option>Hola</option>
		// 			<option>Hola</option>';
		return $select;
	}

	function listaIngresos()
	{
		$lista = array( array('Nombre' =>'hola', 'id'=>"hombre"),
						array('Nombre' =>'hola', 'id'=>"hombre"),
						array('Nombre' =>'hola', 'id'=>3),
						array('Nombre' =>'hola', 'id'=>4),
						array('Nombre' =>'hola', 'id'=>5),
						array('Nombre' =>'sebastian', 'id'=>"hola"),
						
						array('Nombre' =>'hola',
							   'id'=>7,
							   'costo'=>10
							),

					);
		$lista = $this->modelo->listardebase();
		$tr='';
		//print_r($lista);die();
		foreach ($lista as $key => $value) {
			$tr.= '<tr>
				<td>'.$value['id'].'</td>
				<td>2</td>
				<td>3</td>
				<td>4</td>
				<td>4.5</td>
				<td>'.$value['nombre'].'</td>
				<td>5</td>
				<td><button class="btn btn-sm btn-primary"><i class="bx bx-save"></i></button></td>
				</tr>';

		}
		
		return $tr;
	}

	function eliminar()
	{

	}

}

?>