using Lextm.SharpSnmpLib.Security;
using Microsoft.Data.SqlClient;
using Microsoft.Extensions.Configuration;
using System;
using System.Collections.Generic;
using System.Data;
using System.Linq;
using System.Reflection.Metadata.Ecma335;
using System.Text;
using System.Threading.Tasks;
using System.Xml.Linq;

namespace CorsinfSDKHik.ConfigDB
{
    public class dbConfig
    {
        private SqlConnection conn_;
        private String Cadena = "";

        public void CadenaConexion()
        {
            String cadena = "";

            var config = new ConfigurationBuilder()
           .SetBasePath(AppContext.BaseDirectory)
           .AddJsonFile(Path.Combine("ConfigDB", "appsettings.json"), optional: false, reloadOnChange: true)
           .Build();

            string server = config["Database:Server"];
            string port = config["Database:Port"];
            string db = config["Database:DataBase"];
            string user = config["Database:User"];
            string pass = config["Database:Password"];

            if (!string.IsNullOrEmpty(port))
            {
                Cadena = "Server=" + server + "," + port + ";Database=" + db + ";User Id=" + user + ";Password=" + pass + ";TrustServerCertificate=True";
            }
            else
            {
                Cadena = "Server=" + server + ";Database=" + db + ";User Id=" + user + ";Password=" + pass + ";TrustServerCertificate=True";
            }
        }

        public void CadenaEmpresa(String server,String port,String user,String pass,String db)
        {
            if (!string.IsNullOrEmpty(port))
            {
                Cadena = "Server=" + server + "," + port + ";Database=" + db + ";User Id=" + user + ";Password=" + pass + ";TrustServerCertificate=True";
            }
            else
            {
                Cadena = "Server=" + server + ";Database=" + db + ";User Id=" + user + ";Password=" + pass + ";TrustServerCertificate=True";
            }
        }
        public SqlConnection conexion()
        {
            try
            {
                string cadena = Cadena;
                conn_ = new SqlConnection(cadena);
                if (conn_.State == ConnectionState.Closed)
                {
                    try
                    {
                        conn_.Open();
                    }
                    catch (SqlException ex)
                    {
                        return null;
                    }
                }
                return conn_;
            }
            catch (Exception e)
            {

                Console.WriteLine($"error {e}");
                return null;
            }

        }
        public SqlConnection CerrarConexion(SqlConnection conn)
        {
            if (conn.State == ConnectionState.Open)
            {
                conn.Close();
            }
            return conn;
        }


        public SqlDataReader dataQuery(String query,SqlConnection conn)
        {
            try
            {
                String SqlComman = query;
                SqlCommand sql = new SqlCommand(SqlComman, conn);
                SqlDataReader reader = sql.ExecuteReader();
                return reader;
            }
            catch (SqlException e)
            {
                 Console.WriteLine(e);
                return null;
            }

        }
    }
}
