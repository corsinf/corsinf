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
    public class InsertsData
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
        public Boolean InsertarAccesos(SqlConnection conn, object modelo)
        {
            configConsulta();
            string querySQL = "INSERT INTO  " + esquema + ".th_control_acceso (" +
                "th_cardNo," +
                "th_dis_id," +
                "th_acc_tipo_registro," +
                "th_acc_hora," +
                "th_acc_fecha," +
                "th_acc_fecha_hora," +
                "th_acc_fecha_creacion," +
                "th_acc_fecha_modificacion," +
                "th_per_id," +
                "th_acc_puerto," +
                "th_acc_tipo_origen," +
                "th_act_id," +
                "th_acc_detalle_registro," +
                "th_acc_dia," +
                "th_acc_atraso_min," +
                "th_acc_almuerzo_min," +
                "th_acc_justificacion_min," +
                "th_acc_hor_faltantesJornada_min," +
                "th_acc_hor_suplementarias_min," +
                "th_acc_hor_extraordinarias_min," +
                "th_acc_horas_trabajadasJornada_min," +
                "th_acc_horario_jornada," +
                "th_acc_hora_ingreso) VALUES (@th_cardNo," +
                "@th_dis_id," +
                "@th_acc_tipo_registro," +
                "@th_acc_hora," +
                "@th_acc_fecha," +
                "@th_acc_fecha_hora," +
                "@th_acc_fecha_creacion," +
                "@th_acc_fecha_modificacion," +
                "@th_per_id," +
                "@th_acc_puerto," +
                "@th_acc_tipo_origen," +
                "@th_act_id," +
                "@th_acc_detalle_registro," +
                "@th_acc_dia," +
                "@th_acc_atraso_min," +
                "@th_acc_almuerzo_min," +
                "@th_acc_justificacion_min," +
                "@th_acc_hor_faltantesJornada_min," +
                "@th_acc_hor_suplementarias_min," +
                "@th_acc_hor_extraordinarias_min," +
                "@th_acc_horas_trabajadasJornada_min," +
                "@th_acc_horario_jornada," +
                "@th_acc_hora_ingreso) ";
            try
            {
                var cmd = new SqlCommand(querySQL, conn);

                if (modelo is ControlAccesosModelo registro)
                {
                    cmd.Parameters.AddWithValue("@th_cardNo", registro.th_cardNo ?? (object)DBNull.Value);
                    cmd.Parameters.AddWithValue("@th_dis_id", registro.th_dis_id ?? (object)DBNull.Value);
                    cmd.Parameters.AddWithValue("@th_acc_tipo_registro", registro.th_acc_tipo_registro ?? (object)DBNull.Value);
                    cmd.Parameters.AddWithValue("@th_acc_hora", registro.th_acc_hora ?? (object)DBNull.Value);
                    cmd.Parameters.AddWithValue("@th_acc_fecha", registro.th_acc_fecha ?? (object)DBNull.Value);
                    cmd.Parameters.AddWithValue("@th_acc_fecha_hora", registro.th_acc_fecha_hora ?? (object)DBNull.Value);
                    cmd.Parameters.AddWithValue("@th_acc_fecha_creacion", registro.th_acc_fecha_creacion ?? (object)DBNull.Value);
                    cmd.Parameters.AddWithValue("@th_acc_fecha_modificacion", registro.th_acc_fecha_modificacion ?? (object)DBNull.Value);
                    cmd.Parameters.AddWithValue("@th_per_id", registro.th_per_id);
                    cmd.Parameters.AddWithValue("@th_acc_puerto", registro.th_acc_puerto ?? (object)DBNull.Value);
                    cmd.Parameters.AddWithValue("@th_acc_tipo_origen", registro.th_acc_tipo_origen ?? (object)DBNull.Value);
                    cmd.Parameters.AddWithValue("@th_act_id", registro.th_act_id);
                    cmd.Parameters.AddWithValue("@th_acc_detalle_registro", registro.th_acc_detalle_registro ?? (object)DBNull.Value);
                    cmd.Parameters.AddWithValue("@th_acc_dia", registro.th_acc_dia ?? (object)DBNull.Value);
                    cmd.Parameters.AddWithValue("@th_acc_atraso_min", registro.th_acc_atraso_min);
                    cmd.Parameters.AddWithValue("@th_acc_almuerzo_min", registro.th_acc_almuerzo_min);
                    cmd.Parameters.AddWithValue("@th_acc_justificacion_min", registro.th_acc_justificacion_min);
                    cmd.Parameters.AddWithValue("@th_acc_hor_faltantesJornada_min", registro.th_acc_hor_faltantesJornada_min);
                    cmd.Parameters.AddWithValue("@th_acc_hor_suplementarias_min", registro.th_acc_hor_suplementarias_min);
                    cmd.Parameters.AddWithValue("@th_acc_hor_extraordinarias_min", registro.th_acc_hor_extraordinarias_min);
                    cmd.Parameters.AddWithValue("@th_acc_horas_trabajadasJornada_min", registro.th_acc_horas_trabajadasJornada_min);
                    cmd.Parameters.AddWithValue("@th_acc_horario_jornada", registro.th_acc_horario_jornada ?? (object)DBNull.Value);
                    cmd.Parameters.AddWithValue("@th_acc_hora_ingreso", registro.th_acc_hora_ingreso ?? (object)DBNull.Value);

                }

                cmd.ExecuteNonQuery();
                return true;
            }
            catch (Exception ex)
            {
                Console.WriteLine($"Error: {ex.Message}");
                return false;
            }
        }

        public Boolean InsertarAtrasos(SqlConnection conn, object modelo)
        {
            configConsulta();
            string querySQL = "INSERT INTO  " + esquema + ".asis_atrasos (" +
                "th_per_id," +
                "asi_fecha_parametrizada," +
                "asi_hora_parametrizada," +
                "asi_atrasos_fecha_marcacion," +
                "asi_atrasos_hora_marcacion," +
                "asi_atrasos_total_min) VALUES (" +
                "@th_per_id," +
                "@asi_fecha_parametrizada," +
                "@asi_hora_parametrizada," +
                "@asi_atrasos_fecha_marcacion," +
                "@asi_atrasos_hora_marcacion," +
                "@asi_atrasos_total_min) ";
            try
            {
                var cmd = new SqlCommand(querySQL, conn);

                if (modelo is AtrasosModelo registro)
                {
                    cmd.Parameters.AddWithValue("@th_per_id", registro.th_per_id ?? (object)DBNull.Value);
                    cmd.Parameters.AddWithValue("@asi_fecha_parametrizada", registro.asi_fecha_parametrizada ?? (object)DBNull.Value);
                    cmd.Parameters.AddWithValue("@asi_hora_parametrizada", registro.asi_hora_parametrizada ?? (object)DBNull.Value);
                    cmd.Parameters.AddWithValue("@asi_atrasos_fecha_marcacion", registro.asi_atrasos_fecha_marcacion ?? (object)DBNull.Value);
                    cmd.Parameters.AddWithValue("@asi_atrasos_hora_marcacion", registro.asi_atrasos_hora_marcacion ?? (object)DBNull.Value);
                    cmd.Parameters.AddWithValue("@asi_atrasos_total_min", registro.asi_atrasos_total_min);
                }

                cmd.ExecuteNonQuery();
                return true;
            }
            catch (Exception ex)
            {
                Console.WriteLine($"Error: {ex.Message}");
                return false;
            }
        }

        public Boolean InsertarDescanso(SqlConnection conn, object modelo)
        {
            configConsulta();
            string querySQL = "INSERT INTO  " + esquema + ".asis_descansos (" +
                "th_per_id," +
                "asi_fecha_parametrizada," +
                "asi_hora_parametrizada," + 
                "asi_descanso_detalle,"+
                "asi_descanso_fecha_marcacion," +
                "asi_descanso_hora_marcacion," +
                "asi_descanso_total_min) VALUES (" +
                "@th_per_id," +
                "@asi_fecha_parametrizada," +
                "@asi_hora_parametrizada," +
                "@asi_descanso_detalle," +
                "@asi_descanso_fecha_marcacion," +
                "@asi_descanso_hora_marcacion," +
                "@asi_descanso_total_min) ";
            try
            {
                var cmd = new SqlCommand(querySQL, conn);

                if (modelo is DescansoModelo registro)
                {
                    cmd.Parameters.AddWithValue("@th_per_id", registro.th_per_id ?? (object)DBNull.Value);
                    cmd.Parameters.AddWithValue("@asi_fecha_parametrizada", registro.asi_fecha_parametrizada ?? (object)DBNull.Value);
                    cmd.Parameters.AddWithValue("@asi_hora_parametrizada", registro.asi_hora_parametrizada ?? (object)DBNull.Value);
                    cmd.Parameters.AddWithValue("@asi_descanso_detalle", registro.asi_descanso_detalle ?? (object)DBNull.Value);
                    cmd.Parameters.AddWithValue("@asi_descanso_fecha_marcacion", registro.asi_descanso_fecha_marcacion ?? (object)DBNull.Value);
                    cmd.Parameters.AddWithValue("@asi_descanso_hora_marcacion", registro.asi_descanso_hora_marcacion ?? (object)DBNull.Value);
                    cmd.Parameters.AddWithValue("@asi_descanso_total_min", registro.asi_descanso_total_min);
                }

                cmd.ExecuteNonQuery();
                return true;
            }
            catch (Exception ex)
            {
                Console.WriteLine($"Error: {ex.Message}");
                return false;
            }
        }

        public Boolean InsertarDescuentos(SqlConnection conn, object modelo)
        {
            configConsulta();
            string querySQL = "INSERT INTO  " + esquema + ".asis_descuentosTime (" +
                "th_per_id," +
                "asi_desc_motivo, " +
                "asi_fecha_parametrizada," +
                "asi_hora_parametrizada," +
                "asi_desc_fecha_marcacion," +
                "asi_desc_hora_marcacion," +
                "asi_desc_total_min) VALUES (" +
                "@th_per_id," +
                "@asi_desc_motivo," +
                "@asi_fecha_parametrizada," +
                "@asi_hora_parametrizada," +
                "@asi_desc_fecha_marcacion," +
                "@asi_desc_hora_marcacion," +
                "@asi_desc_total_min) ";
            try
            {
                var cmd = new SqlCommand(querySQL, conn);

                if (modelo is DescuentosTiempoModelo registro)
                {
                    cmd.Parameters.AddWithValue("@th_per_id", registro.th_per_id ?? (object)DBNull.Value);
                    cmd.Parameters.AddWithValue("@asi_desc_motivo", registro.asi_desc_motivo ?? (object)DBNull.Value);
                    cmd.Parameters.AddWithValue("@asi_fecha_parametrizada", registro.asi_fecha_parametrizada ?? (object)DBNull.Value);
                    cmd.Parameters.AddWithValue("@asi_hora_parametrizada", registro.asi_hora_parametrizada ?? (object)DBNull.Value);
                    cmd.Parameters.AddWithValue("@asi_desc_fecha_marcacion", registro.asi_desc_fecha_marcacion ?? (object)DBNull.Value);
                    cmd.Parameters.AddWithValue("@asi_desc_hora_marcacion", registro.asi_desc_hora_marcacion ?? (object)DBNull.Value);
                    cmd.Parameters.AddWithValue("@asi_desc_total_min", registro.asi_desc_total_min);
                }

                cmd.ExecuteNonQuery();
                return true;
            }
            catch (Exception ex)
            {
                Console.WriteLine($"Error: {ex.Message}");
                return false;
            }
        }

        public Boolean InsertarExtraordinarias(SqlConnection conn, object modelo)
        {
            configConsulta();
            string querySQL = "INSERT INTO  " + esquema + ".asis_extraordinarias (" +
                "th_per_id," +
                "asis_extraordinarias_detalle, " +
                "asi_fecha_parametrizada," +
                "asi_hora_parametrizada," +
                "asis_extraordinarias_fecha," +
                "asis_extraordinarias_hora," +
                "asis_extraordinarias_total_min) VALUES (" +
                "@th_per_id," +
                "@asis_extraordinarias_detalle," +
                "@asi_fecha_parametrizada," +
                "@asi_hora_parametrizada," +
                "@asis_extraordinarias_fecha," +
                "@asis_extraordinarias_hora," +
                "@asis_extraordinarias_total_min) ";
            try
            {
                var cmd = new SqlCommand(querySQL, conn);

                if (modelo is ExtraordinariasModelo registro)
                {
                    cmd.Parameters.AddWithValue("@th_per_id", registro.th_per_id ?? (object)DBNull.Value);
                    cmd.Parameters.AddWithValue("@asis_extraordinarias_detalle", registro.asis_extraordinarias_detalle ?? (object)DBNull.Value);
                    cmd.Parameters.AddWithValue("@asi_fecha_parametrizada", registro.asi_fecha_parametrizada ?? (object)DBNull.Value);
                    cmd.Parameters.AddWithValue("@asi_hora_parametrizada", registro.asi_hora_parametrizada ?? (object)DBNull.Value);
                    cmd.Parameters.AddWithValue("@asis_extraordinarias_fecha", registro.asis_extraordinarias_fecha ?? (object)DBNull.Value);
                    cmd.Parameters.AddWithValue("@asis_extraordinarias_hora", registro.asis_extraordinarias_hora ?? (object)DBNull.Value);
                    cmd.Parameters.AddWithValue("@asis_extraordinarias_total_min", registro.asis_extraordinarias_total_min);
                }

                cmd.ExecuteNonQuery();
                return true;
            }
            catch (Exception ex)
            {
                Console.WriteLine($"Error: {ex.Message}");
                return false;
            }
        }

        public Boolean InsertarFaltas(SqlConnection conn, object modelo)
        {
            configConsulta();
            string querySQL = "INSERT INTO  " + esquema + ".asis_faltas (" +
                "th_per_id," +
                "asi_faltas_fecha_inicio," +
                "asi_faltas_fecha_fin," +
                "asi_faltas_total_min) VALUES (" +
                "@th_per_id," +
                "@asi_faltas_fecha_inicio," +
                "@asi_faltas_fecha_fin," +
                "@asi_faltas_total_min) ";
            try
            {
                var cmd = new SqlCommand(querySQL, conn);

                if (modelo is FaltasModelo registro)
                {
                    cmd.Parameters.AddWithValue("@th_per_id", registro.th_per_id ?? (object)DBNull.Value);
                    cmd.Parameters.AddWithValue("@asi_faltas_fecha_inicio", registro.asi_faltas_fecha_inicio);
                    cmd.Parameters.AddWithValue("@asi_faltas_fecha_fin", registro.asi_faltas_fecha_fin);
                    cmd.Parameters.AddWithValue("@asi_faltas_total_min", registro.asi_faltas_total_min);
                    }

                cmd.ExecuteNonQuery();
                return true;
            }
            catch (Exception ex)
            {
                Console.WriteLine($"Error: {ex.Message}");
                return false;
            }
        }

        public Boolean InsertTabla(SqlConnection conn, String data)
        {

            configConsulta();
            try
            {
                String SqlText = "INSERT INTO " + esquema + ".th_log_dispositivos (LOG_DEVICE) VALUES ('" + data + "');";
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


    }
}
