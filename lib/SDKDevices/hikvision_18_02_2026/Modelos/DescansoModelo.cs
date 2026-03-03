using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace CorsinfSDKHik.Modelos
{
    public class DescansoModelo
    {

        public int asi_descanso_id { get; set; }
        public string th_per_id { get; set; }
        public string asi_fecha_parametrizada { get; set; }
        public string asi_hora_parametrizada { get; set; }
        public string asi_descanso_detalle { get; set; }
        public string asi_descanso_fecha_marcacion { get; set; }
        public string asi_descanso_hora_marcacion { get; set; }
        public int asi_descanso_total_min { get; set; }
    }
}
