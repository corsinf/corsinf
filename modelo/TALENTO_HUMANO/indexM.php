<?php
if (!class_exists('db')) {
    include(dirname(__DIR__, 2) . '/db/db.php');
}
class indexM
{
    private $db;

    function __construct()
    {
        $this->db = new db();
    }

//Poner consultas para el indexfunction lista_articulos_tipo()
     function lista_articulos_tipo(){
        $sql =
            "SELECT 
                t.ID_TIPO_ARTICULO,
                t.DESCRIPCION,
                t.COLOR,
                COUNT(a.id_articulo) AS TOTAL_ARTICULOS
            FROM 
                ac_cat_tipo_articulo t
            LEFT JOIN 
                ac_articulos a ON t.ID_TIPO_ARTICULO = a.ID_TIPO_ARTICULO
            GROUP BY 
                t.ID_TIPO_ARTICULO,
                t.DESCRIPCION,
                t.COLOR
            ORDER BY t.ID_TIPO_ARTICULO ASC;";

        $datos = $this->db->datos($sql);
        return $datos;
    }
}