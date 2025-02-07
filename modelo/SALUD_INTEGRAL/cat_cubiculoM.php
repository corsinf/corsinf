<?php
if (!class_exists('db')) {
    include('../db/db.php');
}
/**
 * 
 */
class cat_cubiculoM
{
    private $db;

    function __construct()
    {
        $this->db = new db();
    }

    //Para mostrar todos los registros con campos especificos para la vista principal
    function lista_cubiculos($hora_inicio, $hora_fin, $fecha_disponible)
    {
        $sql1 =
            "WITH CubiculosOcupados AS (
                SELECT DISTINCT hd.ac_horarioD_ubicacion
                FROM horario_disponible hd
                WHERE ('$hora_inicio' BETWEEN hd.ac_horarioD_inicio AND hd.ac_horarioD_fin
                        OR '$hora_fin' BETWEEN hd.ac_horarioD_inicio AND hd.ac_horarioD_fin)
                        AND hd.ac_horarioD_fecha_disponible = '$fecha_disponible'
            )
            
            -- Consulta principal para obtener los cubículos disponibles
            SELECT cc.ac_cubiculo_id, cc.ac_cubiculo_nombre
            FROM cat_cubiculo cc
            LEFT JOIN CubiculosOcupados co ON cc.ac_cubiculo_id = co.ac_horarioD_ubicacion
            WHERE co.ac_horarioD_ubicacion IS NULL";
            
            $sql = 
                    "WITH CubiculosOcupados AS (
                        SELECT DISTINCT hd.ac_horarioD_ubicacion
                        FROM horario_disponible hd
                        WHERE (('$hora_inicio' >= hd.ac_horarioD_inicio AND '$hora_inicio' < hd.ac_horarioD_fin)
                                OR ('$hora_fin' > hd.ac_horarioD_inicio AND '$hora_fin' <= hd.ac_horarioD_fin)
                                OR ('$hora_inicio' <= hd.ac_horarioD_inicio AND '$hora_fin' >= hd.ac_horarioD_fin))
                              AND hd.ac_horarioD_fecha_disponible = '$fecha_disponible'
                    )
                    
                    SELECT cc.ac_cubiculo_id, cc.ac_cubiculo_nombre
                    FROM cat_cubiculo cc
                    LEFT JOIN CubiculosOcupados co ON cc.ac_cubiculo_id = co.ac_horarioD_ubicacion
                    WHERE co.ac_horarioD_ubicacion IS NULL;";

        $datos = $this->db->datos($sql);
        return $datos;
    }

    function insertar($datos)
    {
        $rest = $this->db->inserts('cat_cubiculo', $datos);
        return $rest;
    }

    function editar($datos, $where)
    {
        $rest = $this->db->update('cat_cubiculo', $datos, $where);
        return $rest;
    }

    function eliminar($id)
    {
        $sql = "DELETE FROM cat_cubiculo WHERE ac_horarioD_id = $id;";

        //"UPDATE cat_cubiculo SET sa_reu_estado = 0 WHERE " . $datos[0]['campo'] . "='" . $datos[0]['dato'] . "';";
        $datos = $this->db->sql_string($sql);
        return $datos;
    }
}
