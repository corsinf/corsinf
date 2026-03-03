using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace CorsinfSDKHik.Modelos
{
    public class FaltasModelo
    {
        public int asi_faltas_id { get; set; }
        public string th_per_id { get; set; }
        public string th_dep_id { get; set; }
        public DateTime asi_faltas_fecha_inicio { get; set; }
        public DateTime asi_faltas_fecha_fin { get; set; }
        public int asi_faltas_total_min { get; set; }
    }
}
