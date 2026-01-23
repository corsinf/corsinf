using Microsoft.Data.SqlClient;
using Microsoft.Win32;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Xml;

namespace CorsinfSDKHik.ConfigDB
{
    public class Modelo
    {
        private dbConfig db = new dbConfig();
        public Boolean ExisteTabla(SqlConnection conn)
        {
            int Contador = 0;
            String SqlComman = "SELECT * FROM sys.tables WHERE name = 'th_log_dispositivos' AND schema_id = SCHEMA_ID('dbo')";
            SqlCommand sql = new SqlCommand(SqlComman, db.AbrirConexion(conn));
            SqlDataReader reader = sql.ExecuteReader();
            while (reader.Read())
            {
                Contador = Contador + 1;               
            }
            reader.Close();
            db.CerrarConexion(conn);
            if (Contador == 0)
            {
                return false;
            }
            else 
            {
                return true;
            }

        }

        public Boolean CreateTable(SqlConnection conn)
        {
            try
            {
                String SqlText = "CREATE TABLE th_log_dispositivos (ID INT PRIMARY KEY IDENTITY(1,1),LOG_DEVICE NVARCHAR(MAX));";
                SqlCommand sql = new SqlCommand(SqlText, db.AbrirConexion(conn));
                sql.ExecuteNonQuery();
                db.CerrarConexion(conn);
                return true;
            }
            catch (Exception ex)
            {
                Console.WriteLine(ex.Message);
                return false;
            }
        }

        public Boolean InsertTabla(SqlConnection conn, String data)
        {
            try
            {
                String SqlText = "INSERT INTO th_log_dispositivos (LOG_DEVICE) VALUES ('"+data+"');";
                SqlCommand sql = new SqlCommand(SqlText, db.AbrirConexion(conn));
                sql.ExecuteNonQuery();
                db.CerrarConexion(conn);
                return true;
            }
            catch (Exception ex)
            {
                Console.WriteLine(ex.Message);
                return false;
            }

        }

        public void InsertData(SqlConnection conn,String data)
        {
            if (ExisteTabla(conn))
            {
                InsertTabla(conn, data);
            }
            else 
            {
                if (CreateTable(conn))
                {
                    InsertTabla(conn,data);
                }
                else 
                {
                    Console.WriteLine("No se pudo crear la tabla th_log_dispositivos");
                }
            }
        }
    }

}
