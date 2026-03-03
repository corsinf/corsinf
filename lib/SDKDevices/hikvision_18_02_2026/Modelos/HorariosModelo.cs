using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace CorsinfSDKHik.Modelos
{
    public  class HorariosModelo
    {
        public  turnos_horarios _turnos_horarios { get; set; }
        public  turnos _turnos { get; set; }
        public  personas _personas { get; set; }
        public programar_horarios _programar_horarios { get; set; }
        public horarios _horarios { get; set; }
    }
    public  class turnos_horarios
    {
        public  int th_tuh_id { get; set; }
        public  string th_hor_id { get; set; }
        public  string th_tur_id { get; set; }
        public  string th_tuh_dia { get; set; }
        public  string th_tuh_estado { get; set; }
        public  string th_tuh_fecha_creacion { get; set; }
        public  string th_tuh_fecha_modificacion { get; set; }
    }
    public  class turnos
    {
        public static string th_tur_id { get; set; }
        public static string th_tur_nombre { get; set; }
        public static string th_tur_checkin_registro_inicio { get; set; }
        public static string th_tur_hora_entrada { get; set; }
        public static string th_tur_checkin_registro_fin { get; set; }
        public static string th_tur_limite_tardanza_in { get; set; }
        public static string th_tur_checkout_salida_inicio { get; set; }
        public static string th_tur_hora_salida { get; set; }
        public static string th_tur_checkout_salida_fin { get; set; }
        public static string th_tur_limite_tardanza_out { get; set; }
        public static string th_tur_turno_nocturno { get; set; }
        public static string th_tur_valor_trabajar { get; set; }
        public static string th_tur_valor_hora_trabajar { get; set; }
        public static string th_tur_valor_min_trabajar { get; set; }
        public static string th_tur_estado { get; set; }
        public static string th_tur_fecha_creacion { get; set; }
        public static string th_tur_fecha_modificacion { get; set; }
        public static string th_tur_color { get; set; }
        public static string th_tur_descanso { get; set; }
        public static string th_tur_hora_descanso { get; set; }
        public static string th_tur_descanso_inicio { get; set; }
        public static string th_tur_descanso_fin { get; set; }
        public static string th_tur_tol_ini_descanso { get; set; }
        public static string th_tur_tol_fin_descanso { get; set; }
        public static string th_tur_usar_descanso { get; set; }
        public static string th_tur_calcular_horas_extra { get; set; }
        public static string th_tur_supl_ini { get; set; }
        public static string th_tur_supl_fin { get; set; }
        public static string th_tur_extra_ini { get; set; }
        public static string th_tur_extra_fin { get; set; }

    }
    public  class personas
    {
        public static int th_per_id { get; set; }
        public static string th_per_primer_nombre { get; set; }
        public static string th_per_segundo_nombre { get; set; }
        public static string th_per_primer_apellido { get; set; }
        public static string th_per_segundo_apellido { get; set; }
        public static string th_per_cedula { get; set; }
        public static string th_per_estado_civil { get; set; }
        public static string th_per_sexo { get; set; }
        public static string th_per_fecha_nacimiento { get; set; }
        public static string th_per_nacionalidad { get; set; }
        public static string th_per_telefono_1 { get; set; }
        public static string th_per_telefono_2 { get; set; }
        public static string th_per_correo { get; set; }
        public static string th_per_direccion { get; set; }
        public static string th_per_foto_url { get; set; }
        public static string th_prov_id { get; set; }
        public static string th_ciu_id { get; set; }
        public static string th_parr_id { get; set; }
        public static string th_per_postal { get; set; }
        public static string th_per_observaciones { get; set; }
        //public static string th_per_tabla AS tabla { get; set; }
        public static string th_per_id_comunidad { get; set; }
        //public static string th_per_tabla_union AS tabla_union { get; set; }
        public static string th_per_estado { get; set; }
        public static string th_per_fecha_creacion { get; set; }
        //public static string th_per_fecha_modificacion AS fecha_modificacion { get; set; }
        public static string PERFIL { get; set; }
        public static string PASS { get; set; }
    }
    public  class programar_horarios
    {
        public static int th_pro_id { get; set; }
        public static string th_hor_id { get; set; }
        public static string th_per_id { get; set; }
        public static string th_dep_id { get; set; }
        public static string th_pro_fecha_inicio { get; set; }
        public static string th_pro_fecha_fin { get; set; }
        public static string th_pro_no_ciclo { get; set; }
        public static string th_pro_tipo_ciclo { get; set; }
        public static string th_pro_si_ciclo { get; set; }
        public static string th_pro_estado { get; set; }
        public static string th_pro_fecha_creacion { get; set; }
        public static string th_pro_fecha_modificacion { get; set; }
    }
    public  class horarios
    {
        public static int th_hor_id { get; set; }
        public static string th_hor_nombre { get; set; }
        public static string th_hor_tipo { get; set; }
        public static string th_hor_ciclos { get; set; }
        public static string th_hor_inicio { get; set; }
        public static string th_hor_estado { get; set; }
        public static string th_hor_fecha_creacion { get; set; }
        public static string th_hor_fecha_modificacion { get; set; }
    }
}
