using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace CorsinfSDKHik.Modelos
{
    public class JustificacionModel
    {
        public int th_jus_id { get; set; }
        public string th_jus_fecha_inicio { get; set; }
        public string th_jus_fecha_fin { get; set; }
        public int th_tip_jus_id { get; set; }
        public string th_jus_motivo { get; set; }
        public int th_per_id { get; set; }
        public int th_dep_id { get; set; }
        public string th_jus_fecha_creacion { get; set; }
        public string th_jus_fecha_modificacion { get; set; }
        public string th_jus_estado { get; set; }
        public int id_usuario { get; set; }
        public Boolean th_jus_es_rango { get; set; }
        public int th_jus_minutos_justificados { get; set; }
        public string th_jus_tipo { get; set; }

    }
}
