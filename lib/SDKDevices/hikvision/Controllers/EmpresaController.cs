using CorsinfSDKHik.ConfigDB;
using CorsinfSDKHik.Modelos;
using Microsoft.Data.SqlClient;
using Microsoft.Extensions.Configuration;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace CorsinfSDKHik.Controllers
{
    public class EmpresaController
    {
        private EmpresaDAL _empresaDal= new EmpresaDAL();
        public SqlConnection ConexionEmpresa(String IdEmpresa = "")
        {
            String cadena = "";
            SqlConnection conn_ = null;
            var config = new ConfigurationBuilder()
           .SetBasePath(AppContext.BaseDirectory)
           .AddJsonFile(Path.Combine("ConfigDB", "appsettings.json"), optional: false, reloadOnChange: true)
           .Build();
            String Empresa = config["IdEmpresa"];
            String Esquema = config["Esquema"];
            if (string.IsNullOrEmpty(IdEmpresa) && string.IsNullOrEmpty(Empresa))
            {
                //configure cual id tomar
                return null;
            }
            else if (!string.IsNullOrEmpty(IdEmpresa) && !string.IsNullOrEmpty(Empresa))
            {
                //seleccione a cual darle prioridad
                return null;
            }
            else if (string.IsNullOrEmpty(IdEmpresa) && !string.IsNullOrEmpty(Empresa))
            {
                conn_ = _empresaDal.conexionEmpresa(Empresa);
                validarTablas(conn_, Esquema);
                return conn_;
            }
            else
            {
                conn_ =  _empresaDal.conexionEmpresa(IdEmpresa);
                validarTablas(conn_, Esquema);
                return conn_;
            }
        }

        public void validarTablas(SqlConnection conn, String esquema) 
        {
            String fecha = DateTime.Now.ToString("yyyyMM");
            _empresaDal.validarTablas(conn, esquema, fecha);
        }

       
    }
}
