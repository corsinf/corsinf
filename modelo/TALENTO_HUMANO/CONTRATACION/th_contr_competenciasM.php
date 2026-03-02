<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_contr_competenciasM extends BaseModel
{
    // Nombre real de la tabla en la base de datos
    protected $tabla = 'th_contr_competencias';

    // Clave primaria con alias _id para compatibilidad con BaseModel
    protected $primaryKey = 'th_comp_id AS _id';

    // Campos permitidos para inserción / actualización
    protected $camposPermitidos = [
        'th_comp_codigo AS codigo',
        'th_comp_nombre AS nombre',
        'th_comp_categoria AS categoria',
        'th_comp_tipo_disc AS tipo_disc',
        'th_comp_descripcion AS descripcion',
        'th_comp_definicion_completa AS definicion_completa',
        'th_comp_comportamientos_esperados AS comportamientos_esperados',
        'th_comp_es_disc AS es_disc',
        'th_comp_estado AS estado',
        'th_comp_fecha_creacion AS fecha_creacion',
        'th_comp_fecha_modificacion AS fecha_modificacion'
    ];
}

CREATE TABLE [_contratacion].[th_contr_competencias] (
  [th_comp_id] int  IDENTITY(1,1) NOT NULL,
  [th_comp_codigo] nvarchar(50) COLLATE Modern_Spanish_CI_AS  NULL,
  [th_comp_nombre] nvarchar(200) COLLATE Modern_Spanish_CI_AS  NOT NULL,
  [th_comp_categoria] nvarchar(50) COLLATE Modern_Spanish_CI_AS  NULL,
  [th_comp_tipo_disc] nvarchar(10) COLLATE Modern_Spanish_CI_AS  NULL,
  [th_comp_descripcion] nvarchar(max) COLLATE Modern_Spanish_CI_AS  NULL,
  [th_comp_definicion_completa] nvarchar(max) COLLATE Modern_Spanish_CI_AS  NULL,
  [th_comp_comportamientos_esperados] nvarchar(max) COLLATE Modern_Spanish_CI_AS  NULL,
  [th_comp_es_disc] bit DEFAULT 0 NULL,
  [th_comp_estado] bit DEFAULT 1 NULL,
  [th_comp_fecha_creacion] datetime2(7) DEFAULT getdate() NULL,
  [th_comp_fecha_modificacion] datetime2(7)  NULL,
  CONSTRAINT [PK__th_contr__DF0368FB62EBBF8C] PRIMARY KEY CLUSTERED ([th_comp_id])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)  
ON [PRIMARY],
  CONSTRAINT [UQ__th_contr__1BB0B318FDCF1F3E] UNIQUE NONCLUSTERED ([th_comp_codigo] ASC)
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)  
ON [PRIMARY]
)  
ON [PRIMARY]
TEXTIMAGE_ON [PRIMARY]
GO

ALTER TABLE [_contratacion].[th_contr_competencias] SET (LOCK_ESCALATION = TABLE)


CREATE TABLE [_contratacion].[th_contr_cargo_competencias_detalle] (
  [th_carcompdet_id] int  IDENTITY(1,1) NOT NULL,
  [th_carcomp_id] int  NOT NULL,
  [th_carcompdet_subcompetencia] nvarchar(200) COLLATE Modern_Spanish_CI_AS  NULL,
  [th_carcompdet_descripcion] nvarchar(max) COLLATE Modern_Spanish_CI_AS  NULL,
  [th_carcompdet_nivel_utilizacion] int  NULL,
  [th_carcompdet_nivel_contribucion] int  NULL,
  [th_carcompdet_nivel_habilidad] int  NULL,
  [th_carcompdet_nivel_maestria] int  NULL,
  [th_carcompdet_indicador_medicion] nvarchar(max) COLLATE Modern_Spanish_CI_AS  NULL,
  [th_carcompdet_comportamientos_observables] nvarchar(max) COLLATE Modern_Spanish_CI_AS  NULL,
  [th_carcompdet_orden] int  NULL,
  [th_carcompdet_estado] bit DEFAULT 1 NULL,
  [th_carcompdet_fecha_creacion] datetime2(7) DEFAULT getdate() NULL,
  [th_carcompdet_fecha_modificacion] datetime2(7)  NULL,
  CONSTRAINT [PK__th_contr__4EE30E1E939C3E85] PRIMARY KEY CLUSTERED ([th_carcompdet_id])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)  
ON [PRIMARY]
)  
ON [PRIMARY]
TEXTIMAGE_ON [PRIMARY]
GO

ALTER TABLE [_contratacion].[th_contr_cargo_competencias_detalle] SET (LOCK_ESCALATION = TABLE)


CREATE TABLE [_contratacion].[th_contr_cargo_competencias] (
  [th_carcomp_id] int  IDENTITY(1,1) NOT NULL,
  [th_car_id] int  NOT NULL,
  [th_comp_id] int  NOT NULL,
  [th_carcomp_nivel_requerido] int  NULL,
  [th_carcomp_disc_valor_d] int  NULL,
  [th_carcomp_disc_valor_i] int  NULL,
  [th_carcomp_disc_valor_s] int  NULL,
  [th_carcomp_disc_valor_c] int  NULL,
  [th_carcomp_disc_grafica_json] nvarchar(max) COLLATE Modern_Spanish_CI_AS  NULL,
  [th_carcomp_nivel_utilizacion] int  NULL,
  [th_carcomp_nivel_contribucion] int  NULL,
  [th_carcomp_nivel_habilidad] int  NULL,
  [th_carcomp_nivel_maestria] int  NULL,
  [th_carcomp_es_critica] bit DEFAULT 0 NULL,
  [th_carcomp_es_evaluable] bit DEFAULT 1 NULL,
  [th_carcomp_metodo_evaluacion] nvarchar(200) COLLATE Modern_Spanish_CI_AS  NULL,
  [th_carcomp_ponderacion] decimal(5,2)  NULL,
  [th_carcomp_observaciones] nvarchar(max) COLLATE Modern_Spanish_CI_AS  NULL,
  [th_carcomp_estado] bit DEFAULT 1 NULL,
  [th_carcomp_fecha_creacion] datetime2(7) DEFAULT getdate() NULL,
  [th_carcomp_fecha_modificacion] datetime2(7)  NULL,
  CONSTRAINT [PK__th_contr__116B8C04FACD5693] PRIMARY KEY CLUSTERED ([th_carcomp_id])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)  
ON [PRIMARY],
  CONSTRAINT [UQ__th_contr__6A9C3E28C57E8DFA] UNIQUE NONCLUSTERED ([th_car_id] ASC, [th_comp_id] ASC)
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)  
ON [PRIMARY]
)  
ON [PRIMARY]
TEXTIMAGE_ON [PRIMARY]
GO

ALTER TABLE [_contratacion].[th_contr_cargo_competencias] SET (LOCK_ESCALATION = TABLE)