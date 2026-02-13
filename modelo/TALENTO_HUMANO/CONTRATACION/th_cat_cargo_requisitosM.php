<?php

/**
 * @deprecated Archivo dado de baja el 13/02/2026.
 * @note Este archivo se mantiene como respaldo, pero ya no se utilizará en producción.
 * @warning No modificar este archivo. Para cambios, referirse a la nueva implementación.
 */


require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_cat_cargo_requisitosM extends BaseModel
{
    // Nombre real de la tabla
    protected $tabla = 'th_cat_cargo_requisitos';

    // Primary key
    protected $primaryKey = 'id_cargo_requisitos AS _id';

    // Campos permitidos para insertar/editar
    protected $camposPermitidos = [
        'nombre ',
        'descripcion ',
        'estado ',
        'fecha_creacion ',
        'fecha_modificacion '
    ];
}


// CREATE TABLE [_contratacion].[th_cat_cargo_requisitos] (
//   [id_cargo_requisitos] int  IDENTITY(1,1) NOT NULL,
//   [nombre] varchar(100) COLLATE Modern_Spanish_CI_AS  NULL,
//   [descripcion] varchar(255) COLLATE Modern_Spanish_CI_AS  NULL,
//   [estado] bit DEFAULT 1 NULL,
//   [fecha_creacion] datetime2(7) DEFAULT sysdatetime() NULL,
//   [fecha_modificacion] datetime2(7)  NULL,
//   CONSTRAINT [PK__th_contr__C66E40D2E0C0CD34] PRIMARY KEY CLUSTERED ([id_cargo_requisitos])
// WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)  
// ON [PRIMARY]
// )  
// ON [PRIMARY]
// GO

// ALTER TABLE [_contratacion].[th_cat_cargo_requisitos] SET (LOCK_ESCALATION = TABLE)