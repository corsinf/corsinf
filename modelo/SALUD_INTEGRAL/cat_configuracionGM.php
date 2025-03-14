<?php
if (!class_exists('db')) {
    include(dirname(__DIR__, 2) . '/db/db.php');
}

/**
 * 
 */



class cat_configuracionGM
{
    private $db;

    function __construct()
    {
        $this->db = new db();
    }

    //Para mostrar todos los registros con campos especificos para la vista principal
    function lista_vista_conf_general($nombre_accion)
    {
        $sql =
            "SELECT 
                sa_config_id,
                sa_config_nombre,
                sa_config_descripcion,
                sa_config_validar,
                sa_config_valor,
                sa_config_estado,
                sa_config_fecha_creacion

                FROM cat_configuracionG
                WHERE 1 = 1 ";

        if ($nombre_accion) {
            $sql .= "AND sa_config_nombre = '$nombre_accion';";
        } else {
            $sql .= ";";
        }

        $datos = $this->db->datos($sql);
        return $datos;
    }

    function editar($datos, $where)
    {
        $rest = $this->db->update('cat_configuracionG', $datos, $where);
        return $rest;
    }

    function validacion($validar)
    {
        $sql =
            "SELECT 
                sa_config_id,
                sa_config_nombre,
                sa_config_descripcion,
                sa_config_validar,
                sa_config_estado,
                sa_config_fecha_creacion

                FROM cat_configuracionG
                WHERE sa_config_validar = '$validar';";

        $datos = $this->db->datos($sql);

        $datos[0]['sa_config_estado'];

        return $datos[0]['sa_config_estado'];
    }

    function datos($sql)
    {
        //echo $sql;
        $datos = $this->db->datos($sql);
        return $datos;
    }

    function sql_string($sql)
    {
        //echo $sql;
        $datos = $this->db->sql_string($sql);
        return $datos;
    }
}
