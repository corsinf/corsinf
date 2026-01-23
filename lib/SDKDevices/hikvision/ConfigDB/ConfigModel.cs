using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace CorsinfSDKHik.ConfigDB
{
    class ConfigModel
    {
        public DatabaseConfig Database { get; set; }
        public string IdEmpresa { get; set; }
        public int TimeoutSeconds { get; set; }
        public Boolean ModoDebug { get; set; }
        public string Esquema { get; set; }
        public string TablaLogs { get; set; }
    }

    class DatabaseConfig
    {
        public string Server { get; set; }
        public string Port { get; set; }
        public string Database { get; set; }
        public string User { get; set; }
        public string Password { get; set; }
    }
}
