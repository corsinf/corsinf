<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_reportes_hvM extends BaseModel
{
    //protected $tabla = 'th_dispositivos';
    //protected $primaryKey = 'th_dis_id AS _id';

    function conectar_saint()
    {
        /* 

        $this->db->conexion_db_terceros($database, $usuario, $password, $servidor, $puerto);

        $sql = "SELECT * FROM Prueba ORDER BY date2 DESC;";

        return $this->db->datos_db_terceros($database, $usuario, $password, $servidor, $puerto, $sql); */

        //$sql = "SELECT * FROM Prueba ORDER BY date2 DESC;";

        $sql =
            "SELECT 
                personid AS '_id',
                Last_Name AS 'APELLIDOS',
                personname AS 'NOMBRES',
                Person_Name AS 'EMPLEADO',
                Person_Group AS 'DEPARTAMENTO',
                date2 AS 'FECHA',
                MIN(CONVERT(varchar(5), date3, 108)) AS Hora_Entrada, 
                MAX(CONVERT(varchar(5), date3, 108)) AS Hora_Salida  

            FROM 
                [dbo].[Prueba]
            WHERE 
                date2 BETWEEN '2024-09-16' AND '2024-09-19'
            GROUP BY 
                personid,
                Last_Name,
                personname,
                Person_Name,
                Person_Group,
                date2

            ORDER BY 
                date2;
        ";
        return $this->db->datos($sql);
    }
}
