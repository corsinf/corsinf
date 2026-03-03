using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace CorsinfSDKHik.Modelos
{
    public class AtrasosModelo
    {
        public int asi_atrasos_id { get; set; }
        public string th_per_id { get; set; }
        public string asi_fecha_parametrizada{ get; set; }
        public string asi_hora_parametrizada { get; set; }
        public string asi_atrasos_fecha_marcacion { get; set; }
        public string asi_atrasos_hora_marcacion { get; set; }
        public int asi_atrasos_total_min { get; set; }
    }
}
