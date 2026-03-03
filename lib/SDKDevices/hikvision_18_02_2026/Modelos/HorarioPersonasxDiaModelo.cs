using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace CorsinfSDKHik.Modelos
{
    public class HorarioPersonasxDiaModelo
    {
        public int th_pro_id { get; set; }
        public int th_per_id { get; set; }
        public DateTime periodo_ini { get; set; }
        public DateTime perido_fin { get; set; }
        public int th_hor_id { get; set; }
        public string cedula { get; set; }
        public string th_per_nombres_completos { get; set; }
        public int th_card_id { get; set; }
        public string th_cardNo { get; set; }
        public int entrada_min { get; set; }
        public int salida_min { get; set; }
        public int tolerancia_ini { get; set; }
        public int tolerancia_fin { get; set; }
        public int entrada_tiempo_marcacion_valida_inicio { get; set; }
        public int entrada_tiempo_marcacion_valida_fin { get; set; }
        public int salida_tiempo_marcacion_valida_inicio { get; set; }
        public int salida_tiempo_marcacion_valida_fin { get; set; }
        public int horas_a_trabajar { get; set; }
        public int min_a_trabajar { get; set; }
        public int aplica_descanso { get; set; }
        public int aplica_horario_descanso_intervalo { get; set; }
        public int tiempo_descanso { get; set; }
        public int descanso_inicio { get; set; }
        public int descanso_fin { get; set; }
        public int adelanto_descanso { get; set; }
        public int tolerancia_descanso { get; set; }
        public int calcular_horas_extra { get; set; }
        public int inico_suplementario { get; set; }
        public int fin_suplementarias { get; set; }
        public int inicio_extraordinarias { get; set; }
        public int fin_extraordinarias { get; set; }

    }
}
