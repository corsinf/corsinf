using Microsoft.Data.SqlClient;
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
        public SqlConnection conexion(String ServerName,String Port,String DBName,String User,String Pass)
        {
            String cadena = "Server=" + ServerName +","+Port+";Database="+DBName+";User Id="+User+";Password="+Pass+ ";TrustServerCertificate=True";
            conn_ = new SqlConnection(cadena);
            if (AbrirConexion(conn_)!=null)
            {
                CerrarConexion(conn_);
                return conn_;
            }
            else 
            {
                return null;
            }
        }


        public SqlConnection AbrirConexion(SqlConnection conn)
        {
            if (conn.State == ConnectionState.Closed)
            {
                try
                {
                    conn.Open();
                }
                catch (SqlException ex)
                {
                    return null;
                }
            }
            return conn;
        }

        public SqlConnection CerrarConexion(SqlConnection conn)
        {
            if (conn.State == ConnectionState.Open)
            {
                conn.Close();
            }
            return conn;
        }
    }
}
