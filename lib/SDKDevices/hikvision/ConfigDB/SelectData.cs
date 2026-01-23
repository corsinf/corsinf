using CorsinfSDKHik.Modelos;
using Microsoft.Data.SqlClient;
using Microsoft.Extensions.Configuration;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Xml;

namespace CorsinfSDKHik.ConfigDB
{
    public class SelectData
    {
        private dbConfig db = new dbConfig();
        private string esquema;
        private string tabla;
        private string fecha;

        public void configConsulta()
        {
            var config = new ConfigurationBuilder()
                .SetBasePath(AppContext.BaseDirectory)
                .AddJsonFile(Path.Combine("ConfigDB", "appsettings.json"), optional: false, reloadOnChange: true)
                .Build();
            esquema = config["Esquema"];
            fecha = DateTime.Now.ToString("yyyyMM");

        }

        public Boolean ExisteMarcacion(SqlConnection conn, String CardNo,String FechaMarcacion)
        {
            configConsulta();
            int Contador = 0;
            String SqlComman = "SELECT * " +
                "FROM " + esquema + ".th_control_acceso  " +
                "WHERE th_cardNo = '" + CardNo + "' AND th_acc_fecha = '"+FechaMarcacion+"' AND th_acc_tipo_registro = 'Entrada'";
            SqlCommand sql = new SqlCommand(SqlComman, conn);
            SqlDataReader reader = sql.ExecuteReader();
            while (reader.Read())
            {
                Contador = Contador + 1;
            }
            reader.Close();
            if (Contador == 0)
            {
                return false;
            }
            else
            {
                //db.CerrarConexion(conn);
                return true;
            }
        }

        public Boolean ExisteDescuento(SqlConnection conn, String idPersona, String motivo, String fechaMarcacion)
        {
            configConsulta();
            int Contador = 0;
            String SqlComman = "SELECT * " +
                "FROM " + esquema + ".asis_descuentosTime " +
                "WHERE th_per_id = '" + idPersona + "' AND asi_desc_motivo = '" + motivo + "' AND asi_fecha_parametrizada = '" + fechaMarcacion + "'";
            SqlCommand sql = new SqlCommand(SqlComman, conn);
            SqlDataReader reader = sql.ExecuteReader();
            while (reader.Read())
            {
                Contador = Contador + 1;
            }
            reader.Close();
            if (Contador == 0)
            {
                return false;
            }
            else
            {
                //db.CerrarConexion(conn);
                return true;
            }
        }

        public Boolean ExisteDescanso(SqlConnection conn, String idPersona, String fechaMarcacion,string detalle)
        {
            configConsulta();
            int Contador = 0;
            String SqlComman = "SELECT * " +
                "FROM " + esquema + ".asis_descansos " +
                "WHERE th_per_id = '" + idPersona + "' AND asi_descanso_detalle='"+detalle+ "' AND asi_fecha_parametrizada =  CAST( '" + fechaMarcacion + "' AS DATE) ";
            SqlCommand sql = new SqlCommand(SqlComman, conn);
            SqlDataReader reader = sql.ExecuteReader();
            while (reader.Read())
            {
                Contador = Contador + 1;
            }
            reader.Close();
            if (Contador == 0)
            {
                return false;
            }
            else
            {
                //db.CerrarConexion(conn);
                return true;
            }
        }

        public Boolean ExisteAtraso(SqlConnection conn, String idPersona, String fechaMarcacion)
        {
            configConsulta();
            int Contador = 0;
            String SqlComman = "SELECT * " +
                "FROM " + esquema + ".asis_atrasos " +
                "WHERE th_per_id = '" + idPersona + "' AND asi_fecha_parametrizada = '" + fechaMarcacion + "' ";
            SqlCommand sql = new SqlCommand(SqlComman, conn);
            SqlDataReader reader = sql.ExecuteReader();
            while (reader.Read())
            {
                Contador = Contador + 1;
            }
            reader.Close();
            if (Contador == 0)
            {
                return false;
            }
            else
            {
                //db.CerrarConexion(conn);
                return true;
            }
        }

        public List<DescansoModelo> DescansoRegistros(SqlConnection conn, String idPersona, String fechaMarcacion, string detalle)
        {

            List<DescansoModelo> RegistroDescanso = new List<DescansoModelo>();
            configConsulta();
            int Contador = 0;
            String SqlComman = "SELECT asi_descansos_id,th_per_id,asi_fecha_parametrizada,asi_hora_parametrizada,asi_descanso_detalle,asi_descanso_fecha_marcacion," +
                "asi_descanso_hora_marcacion,asi_descanso_total_min " +
                "FROM " + esquema + ".asis_descansos " +
                "WHERE th_per_id = '" + idPersona + "' AND asi_fecha_parametrizada =  CAST( '" + fechaMarcacion + "' AS DATE) ";

            if (!string.IsNullOrEmpty(detalle))
            {
                SqlComman += " AND asi_descanso_detalle='" + detalle + "' ";
            }

            using (SqlCommand sql = new SqlCommand(SqlComman, conn))
            {
                // Usar parámetros para evitar SQL Injection
                
                using (SqlDataReader reader = sql.ExecuteReader())
                {
                    while (reader.Read())
                    {
                        // Crear un nuevo objeto para cada registro
                        DescansoModelo data = new DescansoModelo();

                        data.asi_descanso_id = (int)reader["asi_descansos_id"];
                        data.th_per_id = reader["th_per_id"].ToString() ?? "";
                        data.asi_fecha_parametrizada = reader["asi_fecha_parametrizada"].ToString() ?? "";
                        data.asi_hora_parametrizada = reader["asi_hora_parametrizada"].ToString() ?? "";
                        data.asi_descanso_detalle = reader["asi_descanso_detalle"].ToString() ?? "";
                        data.asi_descanso_fecha_marcacion = reader["asi_descanso_fecha_marcacion"].ToString() ?? "";
                        data.asi_descanso_hora_marcacion = reader["asi_descanso_hora_marcacion"].ToString() ?? "";
                        data.asi_descanso_total_min = (int)reader["asi_descanso_total_min"];

                        // Agregar a la lista
                        RegistroDescanso.Add(data);

                    }
                }
            }
            return RegistroDescanso;
        }

        public Boolean ExisteHorasExtra(SqlConnection conn, String idPersona, String fechaMarcacion)
        {
            configConsulta();
            int Contador = 0;
            String SqlComman = "SELECT * " +
                "FROM " + esquema + ".asis_extraordinarias  " +
                "WHERE th_per_id = '" + idPersona + "' AND asi_fecha_parametrizada = '" + fechaMarcacion + "'";
            SqlCommand sql = new SqlCommand(SqlComman, conn);
            SqlDataReader reader = sql.ExecuteReader();
            while (reader.Read())
            {
                Contador = Contador + 1;
            }
            reader.Close();
            if (Contador == 0)
            {
                return false;
            }
            else
            {
                //db.CerrarConexion(conn);
                return true;
            }
        }

        public Boolean ExisteMarcacioFaltas(SqlConnection conn, String FechaMarcacion)
        {
            configConsulta();
            int Contador = 0;
            String SqlComman = "SELECT * " +
                "FROM " + esquema + ".asis_faltas  " +
                "WHERE CAST(asi_faltas_fecha_inicio AS DATE) = CAST( '" + FechaMarcacion + "' AS DATE) ";
            SqlCommand sql = new SqlCommand(SqlComman, conn);
            SqlDataReader reader = sql.ExecuteReader();
            while (reader.Read())
            {
                Contador = Contador + 1;
            }
            reader.Close();
            if (Contador == 0)
            {
                return false;
            }
            else
            {
                //db.CerrarConexion(conn);
                return true;
            }
        }


        public int TotalAtrazos(SqlConnection conn,String Persona ,String FechaMarcacion)
        {
            configConsulta();
            int Contador = 0;
            String SqlComman = "SELECT * " +
                "FROM " + esquema + ".asis_atrasos  " +
                "WHERE asi_fecha_parametrizada = '" + FechaMarcacion + "' AND th_per_id = '"+Persona+"' ";
            SqlCommand sql = new SqlCommand(SqlComman, conn);
            SqlDataReader reader = sql.ExecuteReader();
            while (reader.Read())
            {
                Contador = Contador + (int)reader["asi_atrasos_total_min"]; ;
            }
            reader.Close();

            return Contador;


        }
        public List<ControlAccesosModelo> RegistroEntradaCC(SqlConnection conn,String FechaMarcacion, string CardNo = "")
        {
            List<ControlAccesosModelo> RegistroEntrada = new List<ControlAccesosModelo>();

            String SqlText = "SELECT th_acc_id,th_cardNo,th_dis_id,th_acc_tipo_registro,th_acc_hora,th_acc_fecha,th_acc_fecha_hora,th_acc_fecha_creacion,th_acc_fecha_modificacion," +
                "th_per_id,th_acc_puerto,th_acc_tipo_origen,th_act_id,th_acc_detalle_registro,th_acc_dia,th_acc_atraso_min,th_acc_almuerzo_min,th_acc_justificacion_min," +
                "th_acc_hor_faltantesJornada_min,th_acc_hor_suplementarias_min,th_acc_hor_extraordinarias_min,th_acc_horas_trabajadasJornada_min,th_acc_horario_jornada" +
                " FROM " + esquema + ".th_control_acceso " +
                "WHERE th_acc_fecha = @Fecha ";

            if (!string.IsNullOrEmpty(CardNo))
            {
                SqlText += " AND th_cardNo = @CardNo";
            }

            SqlText += ";";

            using (SqlCommand sql = new SqlCommand(SqlText, conn))
            {
                // Usar parámetros para evitar SQL Injection
                sql.Parameters.AddWithValue("@Fecha", FechaMarcacion);
                if (!string.IsNullOrEmpty(CardNo))
                {
                    sql.Parameters.AddWithValue("@CardNo", CardNo);
                }

                using (SqlDataReader reader = sql.ExecuteReader())
                {
                    while (reader.Read())
                    {
                        // Crear un nuevo objeto para cada registro
                        ControlAccesosModelo data = new ControlAccesosModelo();

                        data.th_acc_id = (int)reader["th_acc_id"];
                        data.th_cardNo = reader["th_cardNo"].ToString() ?? "";
                        data.th_dis_id = reader["th_dis_id"].ToString() ?? "";
                        data.th_acc_tipo_registro = reader["th_acc_tipo_registro"].ToString() ?? "";
                        data.th_acc_hora = reader["th_acc_hora"].ToString() ?? "";
                        data.th_acc_fecha = reader["th_acc_fecha"].ToString() ?? "";
                        data.th_acc_fecha_hora = reader["th_acc_fecha_hora"].ToString() ?? "";
                        data.th_acc_fecha_creacion = reader["th_acc_fecha_creacion"].ToString() ?? "";
                        data.th_acc_fecha_modificacion = reader["th_acc_fecha_modificacion"].ToString() ?? "";
                        data.th_per_id = (int)reader["th_per_id"];
                        data.th_acc_puerto = reader["th_acc_puerto"].ToString() ?? "";
                        data.th_acc_tipo_origen = reader["th_acc_tipo_registro"].ToString() ?? "";
                        data.th_act_id = (int)reader["th_act_id"];
                        data.th_acc_detalle_registro = reader["th_acc_detalle_registro"].ToString() ?? "";
                        data.th_acc_dia = reader["th_acc_dia"].ToString() ?? "";
                        data.th_acc_atraso_min = (int)reader["th_acc_atraso_min"];
                        data.th_acc_almuerzo_min = (int)reader["th_acc_almuerzo_min"];
                        data.th_acc_justificacion_min = (int)reader["th_acc_justificacion_min"];
                        data.th_acc_hor_faltantesJornada_min = (int)reader["th_acc_hor_faltantesJornada_min"];
                        data.th_acc_hor_suplementarias_min = (int)reader["th_acc_hor_suplementarias_min"];
                        data.th_acc_hor_extraordinarias_min = (int)reader["th_acc_hor_extraordinarias_min"];
                        data.th_acc_horas_trabajadasJornada_min = (int)reader["th_acc_horas_trabajadasJornada_min"];
                        data.th_acc_horario_jornada = reader["th_acc_horario_jornada"].ToString() ?? "";
                        // Agregar a la lista
                        RegistroEntrada.Add(data);
                    }
                }
            }

            return RegistroEntrada;
        }
    }
}
