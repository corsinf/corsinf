using CorsinfSDKHik.Modelos;
using Microsoft.Data.SqlClient;
using Microsoft.Extensions.Configuration;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace CorsinfSDKHik.ConfigDB
{
    public class EmpresaDAL
    {
        private dbConfig db = new dbConfig();
        private HorariosModelo horarios = new HorariosModelo();

        public SqlConnection conexionEmpresa(String id)
        {
            EmpresaModel empresa = SearchEmpresa(id);
            db.CadenaEmpresa(empresa.Ip_host, empresa.Puerto_db, empresa.Usuario_db, empresa.Password_db, empresa.Base_datos);
            return db.conexion();
        }
        public EmpresaModel SearchEmpresa(string idEmpresa)
        {
            try
            {
                EmpresaModel empresa = new EmpresaModel();
                dbConfig db = new dbConfig();
                db.CadenaConexion();
                SqlConnection conn = db.conexion();
                String query = "SELECT * FROM EMPRESAS WHERE Id_Empresa = '" + idEmpresa + "'";
                SqlDataReader reader = db.dataQuery(query, conn);

                if (reader == null)
                {
                    Console.WriteLine("ERROR: dataQuery retornó null");
                    conn.Close();
                    return empresa;
                }
                if (reader.Read())
                {
                    empresa.Ip_host = reader["Ip_host"].ToString();
                    empresa.Puerto_db = reader["Puerto_db"].ToString();
                    empresa.Usuario_db = reader["Usuario_db"].ToString();
                    empresa.Password_db = reader["Password_db"].ToString();
                    empresa.Base_datos = reader["Base_datos"].ToString();
                }

                conn.Close();
                return empresa;
            }
            catch (SqlException e)
            {
                 Console.WriteLine(e);
                return null;
            }
        }

        public void validarTablas(SqlConnection conn, String esquema, String fecha)
        {
            CreateTblControlAcceso(esquema,fecha,conn);
            CreateTblAtrasos(esquema, fecha, conn);
            CreateTblFaltas(esquema, fecha, conn);
            CreateTblExtraordinarias(esquema, fecha, conn);
            //CreateTblDescuentosTime(esquema, fecha, conn);
            CreateTblLogDispositivos(esquema, conn);
            CreateTblDescanso(esquema, fecha, conn);
        }


        public int ExisteTabla(SqlConnection conn,String esquema,String tabla)
        {
            int Contador = 0;
            String SqlComman = "SELECT * FROM sys.tables WHERE name = '" + tabla + "' AND schema_id = SCHEMA_ID('" + esquema + "')";
            SqlCommand sql = new SqlCommand(SqlComman, conn);
            SqlDataReader reader = sql.ExecuteReader();
            while (reader.Read())
            {
                Contador = Contador + 1;
            }
            reader.Close();
            if (Contador == 0)
            {
                return 0;
            }
            else
            {
                //db.CerrarConexion(conn);
                return 1;
            }

        }

        public Boolean CreateTblControlAcceso(String esquema,String fecha,SqlConnection conn)
        {
            String tabla = "th_control_acceso";
            if (ExisteTabla(conn, esquema, tabla) == 0)
            {
                try
                {
                    String SqlText = "CREATE TABLE " + esquema + "." + tabla + " (" +
                        "th_acc_id INT PRIMARY KEY IDENTITY(1,1)," +
                        "th_cardNo NVARCHAR(MAX)," +
                        "th_dis_id NVARCHAR(MAX)," +
                        "th_acc_tipo_registro NVARCHAR(MAX)," +
                        "th_acc_hora time," +
                        "th_acc_fecha date," +
                        "th_acc_fecha_hora datetime2," +
                        "th_acc_fecha_creacion datetime2," +
                        "th_acc_fecha_modificacion datetime2," +
                        "th_per_id INT," +
                        "th_acc_puerto NVARCHAR(MAX)," +
                        "th_acc_tipo_origen NVARCHAR(MAX)," +
                        "th_act_id INT," +
                        "th_acc_detalle_registro NVARCHAR(MAX)," +
                        "th_acc_dia nvarchar(20)," +
                        "th_acc_atraso_min  INT, " +
                        "th_acc_almuerzo_min  INT, " +
                        "th_acc_justificacion_min  INT, " +
                        "th_acc_hor_faltantesJornada_min  INT, " +
                        "th_acc_hor_suplementarias_min  INT, " +
                        "th_acc_hor_extraordinarias_min  INT, " +
                        "th_acc_horas_trabajadasJornada_min INT," +
                        "th_acc_horario_jornada NVARCHAR(MAX)," +
                        "th_acc_hora_ingreso time" +
                        ");";
                    SqlCommand sql = new SqlCommand(SqlText, conn);
                    sql.ExecuteNonQuery();
                    return true;
                }
                catch (Exception ex)
                {
                    Console.WriteLine(ex.Message);
                    return false;
                }
            }
            else { return true; }
        }

        public Boolean CreateTblAtrasos(String esquema, String fecha, SqlConnection conn)
        {
            String tabla = "asis_atrasos";

            if (ExisteTabla(conn, esquema, tabla) == 0)
            {
                try
                {
                    String SqlText = "CREATE TABLE " + esquema + "." + tabla + " (" +
                        "asi_atrasos_id INT PRIMARY KEY IDENTITY(1,1)," +
                        "th_per_id NVARCHAR(MAX)," +
                        "asi_fecha_parametrizada NVARCHAR(MAX)," +
                        "asi_hora_parametrizada NVARCHAR(MAX)," +
                        "asi_atrasos_fecha_marcacion datetime," +
                        "asi_atrasos_hora_marcacion time," +
                        "asi_atrasos_total_min INT" +
                        ");";
                    SqlCommand sql = new SqlCommand(SqlText, conn);
                    sql.ExecuteNonQuery();
                    return true;
                }
                catch (Exception ex)
                {
                    Console.WriteLine(ex.Message);
                    return false;
                }
            }
            else { return true; }
        }

        public Boolean CreateTblDescanso(String esquema, String fecha, SqlConnection conn)
        {
            String tabla = "asis_descansos";

            if (ExisteTabla(conn, esquema, tabla) == 0)
            {
                try
                {
                    String SqlText = "CREATE TABLE " + esquema + "." + tabla + " (" +
                        "asi_descansos_id INT PRIMARY KEY IDENTITY(1,1)," +
                        "th_per_id NVARCHAR(MAX)," +
                        "asi_fecha_parametrizada NVARCHAR(MAX)," +
                        "asi_hora_parametrizada NVARCHAR(MAX)," +
                        "asi_descanso_detalle NVARCHAR(MAX)," +
                        "asi_descanso_fecha_marcacion datetime," +
                        "asi_descanso_hora_marcacion time," +
                        "asi_descanso_total_min INT" +
                        ");";
                    SqlCommand sql = new SqlCommand(SqlText, conn);
                    sql.ExecuteNonQuery();
                    return true;
                }
                catch (Exception ex)
                {
                    Console.WriteLine(ex.Message);
                    return false;
                }
            }
            else { return true; }
        }

        public Boolean CreateTblLogDispositivos(String esquema, SqlConnection conn)
        {
            String tabla = "th_log_dispositivos";

            if (ExisteTabla(conn, esquema, tabla) == 0)
            {
                try
                {
                    String SqlText = "CREATE TABLE " + esquema + "." + tabla + " (" +
                        "ID INT PRIMARY KEY IDENTITY(1,1)," +
                        "LOG_DEVICE NVARCHAR(MAX)" +
                        ");";

                    SqlCommand sql = new SqlCommand(SqlText, conn);
                    sql.ExecuteNonQuery();
                    return true;
                }
                catch (Exception ex)
                {
                    Console.WriteLine(ex.Message);
                    return false;
                }
            }
            else { return true; }
        }

        public Boolean CreateTblFaltas(String esquema, String fecha, SqlConnection conn)
        {
            String tabla = "asis_faltas" ;

            if (ExisteTabla(conn, esquema, tabla) == 0)
            {
                try
                {
                    String SqlText = "CREATE TABLE " + esquema + "." + tabla + " (" +
                        "asi_faltas_id INT PRIMARY KEY IDENTITY(1,1)," +
                        "th_per_id NVARCHAR(MAX)," +
                        "th_dep_id NVARCHAR(MAX)," +
                        "asi_faltas_fecha_inicio datetime," +
                        "asi_faltas_fecha_fin datetime," +
                        "asi_faltas_total_min INT" +
                        ");";
                    SqlCommand sql = new SqlCommand(SqlText, conn);
                    sql.ExecuteNonQuery();
                    return true;
                }
                catch (Exception ex)
                {
                    Console.WriteLine(ex.Message);
                    return false;
                }
            }
            else { return true; }
        }


        public Boolean CreateTblExtraordinarias(String esquema, String fecha, SqlConnection conn)
        {
            String tabla = "asis_extraordinarias";

            if (ExisteTabla(conn, esquema, tabla) == 0)
            {
                try
                {
                    String SqlText = "CREATE TABLE " + esquema + "." + tabla + " (" +
                        "asis_extraordinarias_id INT PRIMARY KEY IDENTITY(1,1)," +
                        "th_per_id NVARCHAR(MAX)," +
                        "asi_fecha_parametrizada NVARCHAR(MAX)," +
                        "asi_hora_parametrizada NVARCHAR(MAX)," +
                        "asis_extraordinarias_detalle NVARCHAR(MAX)," +
                        "asis_extraordinarias_fecha datetime," +
                        "asis_extraordinarias_hora time," +
                        "asis_extraordinarias_total_min INT" +
                        ");";
                    SqlCommand sql = new SqlCommand(SqlText, conn);
                    sql.ExecuteNonQuery();
                    return true;
                }
                catch (Exception ex)
                {
                    Console.WriteLine(ex.Message);
                    return false;
                }
            }
            else { return true; }
        }

        public Boolean CreateTblDescuentosTime(String esquema, String fecha, SqlConnection conn)
        {
            String tabla = "asis_descuentosTime";

            if (ExisteTabla(conn, esquema, tabla) == 0)
            {
                try
                {
                    String SqlText = "CREATE TABLE " + esquema + "." + tabla + " (" +
                        "asi_desc_id INT PRIMARY KEY IDENTITY(1,1)," +
                        "th_per_id NVARCHAR(MAX)," +
                        "asi_desc_motivo NVARCHAR(MAX)," +
                        "asi_fecha_parametrizada NVARCHAR(MAX)," +
                        "asi_hora_parametrizada NVARCHAR(MAX)," +
                        "asi_desc_fecha_marcacion datetime," +
                        "asi_desc_hora_marcacion time," +
                        "asi_desc_total_min INT" +
                        ");";
                    SqlCommand sql = new SqlCommand(SqlText, conn);
                    sql.ExecuteNonQuery();
                    return true;
                }
                catch (Exception ex)
                {
                    Console.WriteLine(ex.Message);
                    return false;
                }
            }
            else { return true; }
        }

        public void horarioAcceso(SqlConnection conn) 
        {
            String SqlText = "turnos_horarios";
            String SqlText1 = "turnos";
            String SqlText2 = "personas";
            String SqlText3 = "programar_horarios";
            String SqlText4 = "horarios";

            try
            {
                SqlDataReader reader = db.dataQuery(SqlText, conn);
                if (reader == null)
                {
                    Console.WriteLine("ERROR: dataQuery retornó null");
                    conn.Close();
                }
                if (reader.Read())
                {
                    //empresa.Ip_host = reader["Ip_host"].ToString();
                    //empresa.Puerto_db = reader["Puerto_db"].ToString();
                    //empresa.Usuario_db = reader["Usuario_db"].ToString();
                    //empresa.Password_db = reader["Password_db"].ToString();
                    //empresa.Base_datos = reader["Base_datos"].ToString();
                }

            }
            catch (SqlException e)
            {
                Console.WriteLine(e);
            }


            horarios._turnos_horarios = new turnos_horarios();
            horarios._turnos_horarios.th_tuh_id = 1;
        }

    }
}
