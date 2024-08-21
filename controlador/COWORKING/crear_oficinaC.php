<?php 
include(dirname(__DIR__, 2).'/modelo/COWORKING/ClaseEjemploM.php');

$controlador = new claseEjemplo();

if (isset($_GET['categoria'])) {
    echo json_encode($controlador->lista());
}

if (isset($_GET['listaIngresos'])) {
    echo json_encode($controlador->listaIngresos());
}

if (isset($_GET['add'])) {
    $data = $_POST["data"];
	//print_r($data);
    echo json_encode($controlador->add($data));
}

class claseEjemplo
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new claseEjemploM();
    }

     function add($parametros)
    {
        
        $res = $this->modelo->insertarnombre($parametros['costo']);
           
    }

     function lista()
    {
        $lista = [
            ['Nombre' =>'hola', 'id'=>1],
            ['Nombre' =>'hola2', 'id'=>2],
            ['Nombre' =>'hola3', 'id'=>3],
            ['Nombre' =>'hola4', 'id'=>4],
            ['Nombre' =>'hola5', 'id'=>5],
        ];

        $select = '';
        foreach ($lista as $value) {
            $select .= '<option value="'.$value['id'].'">'.$value['Nombre'].'</option>';
        }

        return $select;
    }

     function listaIngresos()
    {
        $lista = [
            ['Nombre' =>'hola', 'genero'=>"hombre"],
            ['Nombre' =>'hola2', 'genero'=>"hombre"],
            ['Nombre' =>'hola3', 'genero'=>3],
            ['Nombre' =>'hola4', 'genero'=>4],
            ['Nombre' =>'hola5', 'genero'=>5],
            ['Nombre' =>'sebastian', 'genero'=>"hola"],
            ['Nombre' =>'hola', 'genero'=>7, 'costo'=>10]
        ];

        $tr = '';
        foreach ($lista as $value) {
            $tr .= '<tr>
                <td>'.$value['genero'].'</td>
                <td>2</td>
                <td>3</td>
                <td>4</td>
                <td>4.5</td>
                <td>'.$value['Nombre'].'</td>
                <td>5</td>
                <td><button class="btn btn-sm btn-primary"><i class="bx bx-save"></i></button></td>
                </tr>';
        }
        
        return $tr;
    }

}
?>
