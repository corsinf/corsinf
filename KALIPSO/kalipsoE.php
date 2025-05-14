
<?php
$reporte = new kalipso();


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['kalipso'])) {
        $parametros = $_GET;
        echo json_encode(['hola' => 'como estas']);
    }
}

/**
 * 
 */
class kalipso
{

    function __construct()
    {

    }
}
?>