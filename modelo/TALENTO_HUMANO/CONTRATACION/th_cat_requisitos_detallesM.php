<?php

/**
 * @deprecated Archivo dado de baja el 13/02/2026.
 * @note Este archivo se mantiene como respaldo, pero ya no se utilizará en producción.
 * @warning No modificar este archivo. Para cambios, referirse a la nueva implementación.
 */


require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_cat_requisitos_detallesM extends BaseModel
{
    // Nombre real de la tabla
    protected $tabla = 'th_cat_requisitos_detalles';

    // Primary key (alias _id)
    protected $primaryKey = 'id_requisitos_detalle AS _id';

    // Campos permitidos para insertar/editar (alias para uso en vistas)
    protected $camposPermitidos = [
        'nombre ',
        'descripcion ',
        'tipo_dato ',
        'obligatorio ',
        'estado ',
        'fecha_creacion ',
        'fecha_modificacion '
    ];
}

// CREATE TABLE [_contratacion].[th_cat_requisitos_detalles] (
//   [id_requisitos_detalle] int  IDENTITY(1,1) NOT NULL,
//   [nombre] varchar(100) COLLATE Modern_Spanish_CI_AS  NULL,
//   [descripcion] varchar(500) COLLATE Modern_Spanish_CI_AS  NULL,
//   [tipo_dato] varchar(20) COLLATE Modern_Spanish_CI_AS  NULL,
//   [es_obligatorio] bit DEFAULT 0 NULL,
//   [estado] bit DEFAULT 1 NULL,
//   [fecha_creacion] datetime2(7) DEFAULT sysdatetime() NULL,
//   [fecha_modificacion] datetime2(7)  NULL,
//   CONSTRAINT [PK__th_contr__10CBD2CE6386464A] PRIMARY KEY CLUSTERED ([id_requisitos_detalle])
// WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)  
// ON [PRIMARY]
// )  
// ON [PRIMARY]
// GO

// ALTER TABLE [_contratacion].[th_cat_requisitos_detalles] SET (LOCK_ESCALATION = TABLE)