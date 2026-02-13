<?php

/**
 * @deprecated Archivo dado de baja el 13/02/2026.
 * @note Este archivo se mantiene como respaldo, pero ya no se utilizará en producción.
 * @warning No modificar este archivo. Para cambios, referirse a la nueva implementación.
 */


require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_contr_union_cargo_requisitoM extends BaseModel
{
    // Nombre real de la tabla
    protected $tabla = 'th_contr_union_cargo_requisito';

    // Primary key (con alias _id opcional para uso con DataTables o Select2)
    protected $primaryKey = 'th_carreq_id AS _id';

    // Campos permitidos para insertar o actualizar
    protected $camposPermitidos = [
        'th_car_id',
        'id',
        'th_carreq_estado',
        'th_carreq_fecha_creacion',
        'th_carreq_fecha_modificacion'
    ];

    public function listar_requisitos_no_asignados($car_id)
    {
        $car_id = intval($car_id);

        $sql = "
        SELECT r.*
        FROM th_cat_cargo_requisitos r 
        LEFT JOIN th_contr_union_cargo_requisito u  
        ON u.th_car_req_id = r.id_cargo_requisitos
        AND u.th_car_id = $car_id
		AND u.th_carreq_estado = 1
        WHERE u.th_car_req_id IS NULL;
    ";

        return $this->db->datos($sql);
    }

    public function listar_requisitos_asignados($car_id)
    {
        $car_id = intval($car_id);

        $sql = "
        SELECT 
            u.th_carreq_id,               
            r.id_cargo_requisitos,                    
            r.nombre AS nombre,
            r.descripcion AS descripcion,
            u.th_carreq_estado,
            u.th_carreq_fecha_creacion,
            u.th_carreq_fecha_modificacion
        FROM th_contr_union_cargo_requisito u
        INNER JOIN th_cat_cargo_requisitos r 
            ON r.id_cargo_requisitos = u.th_car_req_id
        WHERE u.th_car_id = {$car_id}
          AND u.th_carreq_estado = 1
          AND r.estado = 1
        ORDER BY r.nombre;
    ";

        return $this->db->datos($sql);
    }
}


// CREATE TABLE [_contratacion].[th_contr_union_cargo_requisito] (
//   [th_carreq_id] int  IDENTITY(1,1) NOT NULL,
//   [th_car_id] int  NOT NULL,
//   [th_car_req_id] int  NOT NULL,
//   [th_carreq_estado] bit DEFAULT 1 NULL,
//   [th_carreq_fecha_creacion] datetime2(7) DEFAULT sysdatetime() NULL,
//   [th_carreq_fecha_modificacion] datetime2(7)  NULL,
//   CONSTRAINT [PK__th_contr__FAF5E52E7BDD06E4] PRIMARY KEY CLUSTERED ([th_carreq_id])
// WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)  
// ON [PRIMARY]
// )  
// ON [PRIMARY]
// GO

// ALTER TABLE [_contratacion].[th_contr_union_cargo_requisito] SET (LOCK_ESCALATION = TABLE)