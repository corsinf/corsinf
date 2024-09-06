el base model ocupale el que ya le tienes 
<?php

/**
 * Class BaseModel
 *
 * BaseModel es un modelo para generar los query de manera mas agil
 * Tiene los siguiente métodos: (cada metodo se le puede agregar la codicion where y join)
 * listar
 * insertar
 * insertar
 * editar
 * eliminar
 * like -> implentado para los select2
 */

require_once(dirname(__DIR__, 4) . '/db/db.php');

class BaseModel
{
    protected $db;
    protected $tabla;
    protected $primaryKey;
    protected $camposPermitidos = [];
    protected $condicionesWhere = [];
    protected $relaciones = [];

    function __construct()
    {
        $this->db = new db();
    }

    function listar()
    {
        // Obtener cláusulas WHERE y JOIN
        $whereClause = $this->retornaValoresWhere();
        $joinClause = $this->retornaValoresJoin();

        // Validar y construir la selección de campos
        if (empty($joinClause)) {
            $camposSeleccionados = implode(', ', $this->camposPermitidos);
            $camposSelect = $this->primaryKey . ', ' . $camposSeleccionados;
        } else {
            $camposSelect = '*';
        }

        // Construir consulta SQL
        $sql = sprintf(
            "SELECT %s FROM %s %s %s",
            $camposSelect,
            $this->tabla,
            $joinClause,
            $whereClause
        );

        // Mostrar la consulta SQL para depuración y salir
        //print_r($sql); exit();

        // Ejecutar consulta y devolver resultados
        $datos = $this->db->datos($sql);
        return $datos;
    }

    function insertar($datos)
    {
        $rest = $this->db->inserts($this->tabla, $datos);
        return $rest;
    }

    function editar($datos, $where)
    {
        $rest = $this->db->update($this->tabla, $datos, $where);
        return $rest;
    }

    function eliminar($datos)
    {
        $rest = $this->db->delete($this->tabla, $datos);
        return $rest;
    }

    // Listar registros basado en condiciones LIKE
    function like($campos, $valor)
    {
        $whereClause = $this->retornaValoresWhere();
        $joinClause = $this->retornaValoresJoin();

        //$camposSelect = implode(', ', $this->camposPermitidos);

        // Inicializar la cláusula WHERE si no hay condiciones previas
        if (empty($whereClause)) {
            $whereClause = " WHERE ";
        } else {
            $whereClause .= " AND ";
        }

        $whereClause .= " CONCAT($campos) LIKE '%" . $valor . "%'";

        $sql = sprintf(
            "SELECT * FROM %s %s %s",
            //$camposSelect,
            $this->tabla,
            $joinClause,
            $whereClause
        );

        //print_r($sql);exit();die();

        $datos = $this->db->datos($sql);
        return $datos;
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Para generar consultas dinamicas
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //Funcion para guardar todos los datos de un where

    // Para generar consultas dinámicas
    function where($atributo, $valor)
    {
        $this->condicionesWhere[] = "$atributo = '$valor'";
        return $this;
    }

    function join($tabla, $condicion, $tipo = 'INNER')
    {
        $this->relaciones[] = compact('tabla', 'condicion', 'tipo');
        return $this;
    }

    // Arma la sentencia del where
    protected function retornaValoresWhere()
    {
        $whereClause = '';
        if (!empty($this->condicionesWhere)) {
            $whereClause = " WHERE " . implode(' AND ', $this->condicionesWhere);
        }

        return $whereClause;
    }

    // Arma la sentencia del join
    protected function retornaValoresJoin()
    {
        $joinClause = '';
        foreach ($this->relaciones as $relacion) {
            $joinClause .= " {$relacion['tipo']} JOIN {$relacion['tabla']} ON {$relacion['condicion']}";
        }

        return $joinClause;
    }




    /*
    *
    *
    *
    */
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    /*   ------------------------------------------------ */
    //Pequeñas ayudas si algo sale mal
    /*   ------------------------------------------------ */

    //Si no funciona la funcion listar()
    function listar_1()
    {
        $whereClause = $this->retornaValoresWhere();
        $joinClause = $this->retornaValoresJoin();

        // Consulta SQL
        if ($joinClause == '') {
            $camposSeleccionados = implode(', ', $this->camposPermitidos);
        } else {
            $camposSeleccionados = '*';
        }

        $sql = "SELECT $this->primaryKey, $camposSeleccionados FROM $this->tabla" . $joinClause . $whereClause;

        print_r($sql);
        exit();

        // Ejecutar consulta y devolver resultados
        $datos = $this->db->datos($sql);
        return $datos;
    }
}
