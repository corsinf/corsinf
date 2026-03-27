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
        // Limpiamos el query para evitar inyecciones
        $q = addslashes(trim($query));

        $sql = "
        SELECT 
            p.th_per_cedula AS cedula,
            p.th_per_nombres_completos,
            p.th_per_telefono_1,
            p.th_per_correo,
            p.th_per_cedula,
            c.th_per_id
        FROM th_personas p
        INNER JOIN CLIENTES c ON p.th_per_id = c.th_per_id
        WHERE p.th_per_estado = 1 
          AND c.DELETE_LOGIC = 0
    ";

        if ($q !== '') {
            $sql .= " AND (p.th_per_nombres_completos LIKE '%$q%' 
                   OR p.th_per_cedula LIKE '%$q%')";
        }

        $sql .= " ORDER BY p.th_per_nombres_completos ASC";

        return $this->db->datos($sql);
    }
}
