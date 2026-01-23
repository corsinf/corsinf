using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace CorsinfSDKHik.Modelos
{
     public class EmpresaModel
    {
        public int Id_empresa { get; set; }
        public string Razon_Social { get; set; }
        public string Nombre_Comercial { get; set; }
        public string Ruc { get; set; }
        public string Direccion { get; set; }
        public string Telefono { get; set; }
        public string Email { get; set; }
        public string Logo { get; set; }
        public string Ruta_Certificado { get; set; }
        public string Clave_certificado { get; set; }
        public string Ip_host { get; set; }
        public string Base_datos { get; set; }
        public string Tipo_base { get; set; }
        public string Usuario_db { get; set; }
        public string Password_db { get; set; }
        public string Puerto_db { get; set; }
        public int Ambiente { get; set; }
        public string Periodo { get; set; }
        public string Obligado_conta { get; set; }
        public string Contribuyente_esp { get; set; }
        public decimal Valor_iva { get; set; }
        public string smtp_host { get; set; }
        public string smtp_port { get; set; }
        public string smtp_usuario { get; set; }
        public string smtp_pass { get; set; }
        public string smtp_secure { get; set; }
        public int  numero_mesas { get; set; }
        public Boolean Facturacion_electronica { get; set; }
        public string Tabla_seguros { get; set; }
        public string Estado { get; set; }
        public string ip_directory { get; set; }
        public string puerto_directory { get; set; }
        public string basedn_directory { get; set; }
        public string usuario_directory { get; set; }
        public string password_directory { get; set; }
        public string dominio_directory { get; set; }
        public string ip_api_hikvision { get; set; }
        public string key_api_hikvision { get; set; }
        public string user_api_hikvision { get; set; }
        public string tcp_puerto_hikvision { get; set; }
        public string puerto_api_hikvision { get; set; }
        public string ambiente_empresa { get; set; }
        public string acerca_de { get; set; }
        public string titulo_pestania { get; set; }
        public string url_api_idukay { get; set; }
        public string token_idukay { get; set; }
        public string anio_lectivo_idukay { get; set; }
        public string ruta_huellas { get; set; }
        public string ruta_img_absoluta { get; set; }
        public string ruta_img_relativa { get; set; }
        public string codigo_empresa_api { get; set; }
        public string ruta_img_compartida { get; set; }

    }
}
