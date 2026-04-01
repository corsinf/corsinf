<?php
require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class CLIENTESM extends BaseModel
{
    protected $tabla = 'CLIENTES';
    protected $primaryKey = 'id_clientes AS _id';

    protected $camposPermitidos = [
        'th_per_id',
        'PERFIL',
        'PASS',
        'POLITICAS_ACEPTACION',
        'DELETE_LOGIC',
    ];


    public function buscar_clientes($query = '')
    {
        $q = addslashes(trim($query));

        $sql = "
        SELECT 
            p.th_per_cedula AS cedula,
            CONCAT(
                ISNULL(p.th_per_primer_nombre, ''), ' ',
                ISNULL(p.th_per_segundo_nombre, ''), ' ',
                ISNULL(p.th_per_primer_apellido, ''), ' ',
                ISNULL(p.th_per_segundo_apellido, '')
            ) AS nombre_persona,
            p.th_per_telefono_1,
            p.th_per_correo,
            c.th_per_id

        FROM th_personas p
        INNER JOIN CLIENTES c ON p.th_per_id = c.th_per_id

        WHERE p.th_per_estado = 1 
          AND c.DELETE_LOGIC = 0
    ";

        if ($q !== '') {
            $sql .= " AND (
            CONCAT(
                ISNULL(p.th_per_primer_nombre, ''), ' ',
                ISNULL(p.th_per_segundo_nombre, ''), ' ',
                ISNULL(p.th_per_primer_apellido, ''), ' ',
                ISNULL(p.th_per_segundo_apellido, '')
            ) LIKE '%$q%'
            OR p.th_per_cedula LIKE '%$q%'
        )";
        }

        $sql .= " ORDER BY nombre_persona ASC";

        return $this->db->datos($sql);
    }
}
