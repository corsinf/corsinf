using CorsinfSDKHik.Modelos;
using Microsoft.Data.SqlClient;
using Microsoft.Extensions.Configuration;
using Newtonsoft.Json.Linq;
using System.Security.Cryptography;

namespace CorsinfSDKHik.ConfigDB
{
    public class Modelo
    {
        private dbConfig db = new dbConfig();
        private InsertsData _InsertData = new InsertsData();
        private SelectData _SelectData = new SelectData();
        private EmpresaDAL _empresaDal = new EmpresaDAL();
        private string esquema;
        private string tabla;
        private string fecha;

        public void configConsulta()
        {
            var config = new ConfigurationBuilder()
                .SetBasePath(AppContext.BaseDirectory)
                .AddJsonFile(Path.Combine("ConfigDB", "appsettings.json"), optional: false, reloadOnChange: true)
                .Build();
            esquema = config["Esquema"];
            fecha = DateTime.Now.ToString("yyyyMM");

        }

        public List<string> BuscarEmpresa(string idEmpresa)
        {
            List<string> tablasEncontradas = new List<string>();

            string sqlCommand = "SELECT * FROM EMPRESAS WHERE id_empresa = '" + idEmpresa + "'";

            using (SqlConnection conn = db.conexion())
            using (SqlCommand sql = new SqlCommand(sqlCommand, conn))
            {
                SqlDataReader reader = sql.ExecuteReader();

                while (reader.Read())
                {
                    tablasEncontradas.Add(reader["name"].ToString());
                }

                reader.Close();
            }

            return tablasEncontradas;
        }
        public Boolean ExisteTabla(SqlConnection conn)
        {
            int Contador = 0;
            String SqlComman = "SELECT * FROM sys.tables WHERE name = '" + tabla + "_" + fecha + "' AND schema_id = SCHEMA_ID('" + esquema + "')";
            SqlCommand sql = new SqlCommand(SqlComman, conn);
            SqlDataReader reader = sql.ExecuteReader();
            while (reader.Read())
            {
                Contador = Contador + 1;
            }
            reader.Close();
            if (Contador == 0)
            {
                return false;
            }
            else
            {
                //db.CerrarConexion(conn);
                return true;
            }

        }

        public HorarioPersonasxDiaModelo ConsultarHorariosxDia(SqlConnection conn,String diaVar = "",String CardNo="")
        {
            String Dia = diaVar;
            if (diaVar != "")
            {
                DateTime hoy = DateTime.Now;
                int dia = (int)hoy.DayOfWeek;
                Dia = (dia + 1).ToString();
            }
            HorarioPersonasxDiaModelo dataEncontrada = new HorarioPersonasxDiaModelo();
            String SqlText = "SELECT th_pro_id,PH.th_per_id,th_pro_fecha_inicio as 'periodo_ini',th_pro_fecha_fin as 'perido_fin',HO.th_hor_id," +
                "PE.th_per_cedula as 'cedula', PE.th_per_nombres_completos,th_card_id,th_cardNo,th_tur_hora_entrada as 'entrada_min',th_tur_hora_salida as 'salida_min'," +
                "th_tur_limite_tardanza_in as 'tolerancia_ini',th_tur_limite_tardanza_out as 'tolerancia_fin',th_tur_checkin_registro_inicio as 'entrada_tiempo_marcacion_valida_inicio'," +
                "th_tur_checkin_registro_fin as 'entrada_tiempo_marcacion_valida_fin',th_tur_checkout_salida_inicio as 'salida_tiempo_marcacion_valida_inicio',th_tur_checkout_salida_fin as 'salida_tiempo_marcacion_valida_fin'," +
                "th_tur_valor_hora_trabajar as 'horas_a_trabajar',th_tur_valor_min_trabajar as 'min_a_trabajar',th_tur_descanso as 'aplica_descanso',th_tur_usar_descanso as 'aplica_horario_descanso_intervalo',th_tur_hora_descanso as 'tiempo_descanso'," +
                "th_tur_descanso_inicio as 'descanso_inicio',th_tur_descanso_fin as 'descanso_fin', th_tur_tol_ini_descanso as 'adelanto_descanso'," +
                "th_tur_tol_fin_descanso as 'tolerancia_descanso',th_tur_calcular_horas_extra as 'calcular_horas_extra'," +
                "th_tur_supl_ini as 'inico_suplementario',th_tur_supl_fin as 'fin_suplementarias',th_tur_extra_ini as 'inicio_extraordinarias'," +
                "th_tur_extra_fin as 'fin_extraordinarias'" +
                "FROM _asistencias.th_programar_horarios PH " +
                "INNER JOIN _asistencias.th_horarios HO ON PH.th_hor_id = HO.th_hor_id " +
                "INNER JOIN _asistencias.th_turnos_horario TH ON HO.th_hor_id = TH.th_hor_id " +
                "INNER JOIN _asistencias.th_turnos TU ON TH.th_tur_id = TU.th_tur_id " +
                "INNER JOIN _talentoh.th_personas PE ON PH.th_per_id = PE.th_per_id " +
                "INNER JOIN _talentoh.th_card_data CA ON PE.th_per_id = CA.th_per_id " +
                "WHERE PH.th_pro_estado = 1 AND HO.th_hor_estado = 1 AND PE.th_per_estado = 1  AND TH.th_tuh_dia = '" + Dia + "' ";
            if (CardNo != "")
            {
                SqlText += " AND th_cardNo = '" + CardNo + "';";
            }

            using (SqlCommand sql = new SqlCommand(SqlText, conn))
            {
                SqlDataReader reader = sql.ExecuteReader();

                while (reader.Read())
                {
                    dataEncontrada.th_pro_id = (int)reader["th_pro_id"];
                    dataEncontrada.th_per_id = (int)reader["th_pro_id"];
                    dataEncontrada.periodo_ini = Convert.ToDateTime(reader["periodo_ini"]);
                    dataEncontrada.perido_fin = Convert.ToDateTime(reader["perido_fin"]);
                    dataEncontrada.th_hor_id = (int)reader["th_hor_id"];
                    dataEncontrada.cedula = reader["cedula"].ToString() ?? "";
                    dataEncontrada.th_per_nombres_completos = reader["th_per_nombres_completos"].ToString() ?? "";
                    dataEncontrada.th_card_id = (int)reader["th_card_id"];
                    dataEncontrada.th_cardNo = reader["th_cardNo"].ToString() ?? "";
                    dataEncontrada.entrada_min = (int)reader["entrada_min"];
                    dataEncontrada.salida_min = (int)reader["salida_min"];
                    dataEncontrada.tolerancia_ini = (int)reader["tolerancia_ini"];
                    dataEncontrada.tolerancia_fin = (int)reader["tolerancia_fin"];
                    dataEncontrada.entrada_tiempo_marcacion_valida_inicio = (int)reader["entrada_tiempo_marcacion_valida_inicio"];
                    dataEncontrada.entrada_tiempo_marcacion_valida_fin = (int)reader["entrada_tiempo_marcacion_valida_fin"];
                    dataEncontrada.salida_tiempo_marcacion_valida_inicio = (int)reader["salida_tiempo_marcacion_valida_inicio"];
                    dataEncontrada.salida_tiempo_marcacion_valida_fin = (int)reader["salida_tiempo_marcacion_valida_fin"];
                    dataEncontrada.horas_a_trabajar = Convert.ToInt32(reader["horas_a_trabajar"]);
                    dataEncontrada.min_a_trabajar = Convert.ToInt32(reader["min_a_trabajar"]);
                    dataEncontrada.aplica_descanso = Convert.ToInt32(reader["aplica_descanso"]);
                    dataEncontrada.aplica_horario_descanso_intervalo = Convert.ToInt32(reader["aplica_horario_descanso_intervalo"]);
                    dataEncontrada.tiempo_descanso = Convert.ToInt32(reader["tiempo_descanso"]);
                    dataEncontrada.descanso_inicio = Convert.ToInt32(reader["descanso_inicio"]);
                    dataEncontrada.descanso_fin = Convert.ToInt32(reader["descanso_fin"]);
                    dataEncontrada.adelanto_descanso = Convert.ToInt32(reader["adelanto_descanso"]);
                    dataEncontrada.tolerancia_descanso = Convert.ToInt32(reader["tolerancia_descanso"]);
                    dataEncontrada.calcular_horas_extra = Convert.ToInt32(reader["calcular_horas_extra"]);
                    dataEncontrada.inico_suplementario = Convert.ToInt32(reader["inico_suplementario"]);
                    dataEncontrada.fin_suplementarias = Convert.ToInt32(reader["fin_suplementarias"]);
                    dataEncontrada.inicio_extraordinarias = Convert.ToInt32(reader["inicio_extraordinarias"]);
                    dataEncontrada.fin_extraordinarias = Convert.ToInt32(reader["fin_extraordinarias"]);
                }

                reader.Close();
            }

            return dataEncontrada;
        }


        public List<HorarioPersonasxDiaModelo> ObtenerHorariosXpersona(SqlConnection conn,string diaVar = "", string CardNo = "")
        {
            List<HorarioPersonasxDiaModelo> listaHorarios = new List<HorarioPersonasxDiaModelo>();

            String Dia = diaVar;
            if (diaVar == "")
            {
                DateTime hoy = DateTime.Now;
                int dia = (int)hoy.DayOfWeek;
                Dia = (dia + 1).ToString();
            }

            String SqlText = "SELECT th_pro_id,PH.th_per_id,th_pro_fecha_inicio as 'periodo_ini',th_pro_fecha_fin as 'perido_fin',HO.th_hor_id," +
                "PE.th_per_cedula as 'cedula', PE.th_per_nombres_completos,th_card_id,th_cardNo,th_tur_hora_entrada as 'entrada_min',th_tur_hora_salida as 'salida_min'," +
                "th_tur_limite_tardanza_in as 'tolerancia_ini',th_tur_limite_tardanza_out as 'tolerancia_fin',th_tur_checkin_registro_inicio as 'entrada_tiempo_marcacion_valida_inicio'," +
                "th_tur_checkin_registro_fin as 'entrada_tiempo_marcacion_valida_fin',th_tur_checkout_salida_inicio as 'salida_tiempo_marcacion_valida_inicio',th_tur_checkout_salida_fin as 'salida_tiempo_marcacion_valida_fin'," +
                "th_tur_valor_hora_trabajar as 'horas_a_trabajar',th_tur_valor_min_trabajar as 'min_a_trabajar',th_tur_descanso as 'aplica_descanso',th_tur_usar_descanso as 'aplica_horario_descanso_intervalo',th_tur_hora_descanso as 'tiempo_descanso'," +
                "th_tur_descanso_inicio as 'descanso_inicio',th_tur_descanso_fin as 'descanso_fin', th_tur_tol_ini_descanso as 'adelanto_descanso'," +
                "th_tur_tol_fin_descanso as 'tolerancia_descanso',th_tur_calcular_horas_extra as 'calcular_horas_extra'," +
                "th_tur_supl_ini as 'inico_suplementario',th_tur_supl_fin as 'fin_suplementarias',th_tur_extra_ini as 'inicio_extraordinarias'," +
                "th_tur_extra_fin as 'fin_extraordinarias'" +
                "FROM _asistencias.th_programar_horarios PH " +
                "INNER JOIN _asistencias.th_horarios HO ON PH.th_hor_id = HO.th_hor_id " +
                "INNER JOIN _asistencias.th_turnos_horario TH ON HO.th_hor_id = TH.th_hor_id " +
                "INNER JOIN _asistencias.th_turnos TU ON TH.th_tur_id = TU.th_tur_id " +
                "INNER JOIN _talentoh.th_personas PE ON PH.th_per_id = PE.th_per_id " +
                "INNER JOIN _talentoh.th_card_data CA ON PE.th_per_id = CA.th_per_id " +
                "WHERE PH.th_pro_estado = 1 AND HO.th_hor_estado = 1 AND PE.th_per_estado = 1 AND TH.th_tuh_dia = @Dia ";

            if (!string.IsNullOrEmpty(CardNo))
            {
                SqlText += " AND th_cardNo = @CardNo";
            }

            SqlText += ";";

            using (SqlCommand sql = new SqlCommand(SqlText, conn))
            {
                // Usar parámetros para evitar SQL Injection
                sql.Parameters.AddWithValue("@Dia", Dia);
                if (!string.IsNullOrEmpty(CardNo))
                {
                    sql.Parameters.AddWithValue("@CardNo", CardNo);
                }

                using (SqlDataReader reader = sql.ExecuteReader())
                {
                    while (reader.Read())
                    {
                        // Crear un nuevo objeto para cada registro
                        HorarioPersonasxDiaModelo data = new HorarioPersonasxDiaModelo();

                        data.th_pro_id = (int)reader["th_pro_id"];
                        data.th_per_id = (int)reader["th_per_id"];
                        data.periodo_ini = Convert.ToDateTime(reader["periodo_ini"]);
                        data.perido_fin = Convert.ToDateTime(reader["perido_fin"]);
                        data.th_hor_id = (int)reader["th_hor_id"];
                        data.cedula = reader["cedula"].ToString() ?? "";
                        data.th_per_nombres_completos = reader["th_per_nombres_completos"].ToString() ?? "";
                        data.th_card_id = (int)reader["th_card_id"];
                        data.th_cardNo = reader["th_cardNo"].ToString() ?? "";
                        data.entrada_min = (int)reader["entrada_min"];
                        data.salida_min = (int)reader["salida_min"];
                        data.tolerancia_ini = (int)reader["tolerancia_ini"];
                        data.tolerancia_fin = (int)reader["tolerancia_fin"];
                        data.entrada_tiempo_marcacion_valida_inicio = (int)reader["entrada_tiempo_marcacion_valida_inicio"];
                        data.entrada_tiempo_marcacion_valida_fin = (int)reader["entrada_tiempo_marcacion_valida_fin"];
                        data.salida_tiempo_marcacion_valida_inicio = (int)reader["salida_tiempo_marcacion_valida_inicio"];
                        data.salida_tiempo_marcacion_valida_fin = (int)reader["salida_tiempo_marcacion_valida_fin"];
                        data.horas_a_trabajar = Convert.ToInt32(reader["horas_a_trabajar"]);
                        data.min_a_trabajar = Convert.ToInt32(reader["min_a_trabajar"]);
                        data.aplica_descanso = Convert.ToInt32(reader["aplica_descanso"]);
                        data.aplica_horario_descanso_intervalo = Convert.ToInt32(reader["aplica_horario_descanso_intervalo"]);
                        data.tiempo_descanso = Convert.ToInt32(reader["tiempo_descanso"]);
                        data.descanso_inicio = Convert.ToInt32(reader["descanso_inicio"]);
                        data.descanso_fin = Convert.ToInt32(reader["descanso_fin"]);
                        data.adelanto_descanso = Convert.ToInt32(reader["adelanto_descanso"]);
                        data.tolerancia_descanso = Convert.ToInt32(reader["tolerancia_descanso"]);
                        data.calcular_horas_extra = Convert.ToInt32(reader["calcular_horas_extra"]);
                        data.inico_suplementario = Convert.ToInt32(reader["inico_suplementario"]);
                        data.fin_suplementarias = Convert.ToInt32(reader["fin_suplementarias"]);
                        data.inicio_extraordinarias = Convert.ToInt32(reader["inicio_extraordinarias"]);
                        data.fin_extraordinarias = Convert.ToInt32(reader["fin_extraordinarias"]);

                        // Agregar a la lista
                        listaHorarios.Add(data);
                    }
                }
            }

            return listaHorarios;
        }

        public List<HorarioPersonasxDiaModelo> ObtenerHorariosXDepartamento(SqlConnection conn, string diaVar = "", string CardNo = "")
        {
            List<HorarioPersonasxDiaModelo> listaHorarios = new List<HorarioPersonasxDiaModelo>();

            String Dia = diaVar;
            if (diaVar == "")
            {
                DateTime hoy = DateTime.Now;
                int dia = (int)hoy.DayOfWeek;
                Dia = (dia + 1).ToString();
            }

            String SqlText = "SELECT th_pro_id,PE.th_per_id,th_pro_fecha_inicio as 'periodo_ini',th_pro_fecha_fin as 'perido_fin'," +
                "HO.th_hor_id,PE.th_per_cedula as 'cedula', PE.th_per_nombres_completos,th_card_id,th_cardNo," +
                "th_tur_hora_entrada as 'entrada_min',th_tur_hora_salida as 'salida_min'," +
                "th_tur_limite_tardanza_in as 'tolerancia_ini',th_tur_limite_tardanza_out as 'tolerancia_fin'," +
                "th_tur_checkin_registro_inicio as 'entrada_tiempo_marcacion_valida_inicio'," +
                "th_tur_checkin_registro_fin as 'entrada_tiempo_marcacion_valida_fin'," +
                "th_tur_checkout_salida_inicio as 'salida_tiempo_marcacion_valida_inicio'," +
                "th_tur_checkout_salida_fin as 'salida_tiempo_marcacion_valida_fin'," +
                "th_tur_valor_hora_trabajar as 'horas_a_trabajar',th_tur_valor_min_trabajar as 'min_a_trabajar'," +
                "th_tur_descanso as 'aplica_descanso',th_tur_usar_descanso as 'aplica_horario_descanso_intervalo'," +
                "th_tur_hora_descanso as 'tiempo_descanso' ,th_tur_descanso_inicio as 'descanso_inicio'," +
                "th_tur_descanso_fin as 'descanso_fin', th_tur_tol_ini_descanso as 'adelanto_descanso'," +
                "th_tur_tol_fin_descanso as 'tolerancia_descanso',th_tur_calcular_horas_extra as 'calcular_horas_extra'," +
                "th_tur_supl_ini as 'inico_suplementario',th_tur_supl_fin as 'fin_suplementarias'," +
                "th_tur_extra_ini as 'inicio_extraordinarias',th_tur_extra_fin as 'fin_extraordinarias' " +
                "FROM _asistencias.th_programar_horarios PH " +
                "INNER JOIN _asistencias.th_horarios HO ON PH.th_hor_id = HO.th_hor_id " +
                "INNER JOIN _asistencias.th_turnos_horario TH ON HO.th_hor_id = TH.th_hor_id " +
                "INNER JOIN _asistencias.th_turnos TU ON TH.th_tur_id = TU.th_tur_id " +
                "INNER JOIN _talentoh.th_personas_departamentos PD ON PH.th_dep_id = PD.th_dep_id " +
                "INNER JOIN _talentoh.th_personas PE ON PD.th_per_id = PE.th_per_id " +
                "INNER JOIN _talentoh.th_card_data CA ON PE.th_per_id = CA.th_per_id " +
                "WHERE PH.th_pro_estado = 1 AND HO.th_hor_estado = 1 AND PE.th_per_estado = 1 AND TH.th_tuh_dia = @Dia ";

            if (!string.IsNullOrEmpty(CardNo))
            {
                SqlText += " AND th_cardNo = @CardNo";
            }

            SqlText += ";";

            using (SqlCommand sql = new SqlCommand(SqlText, conn))
            {
                // Usar parámetros para evitar SQL Injection
                sql.Parameters.AddWithValue("@Dia", Dia);
                if (!string.IsNullOrEmpty(CardNo))
                {
                    sql.Parameters.AddWithValue("@CardNo", CardNo);
                }

                using (SqlDataReader reader = sql.ExecuteReader())
                {
                    while (reader.Read())
                    {
                        // Crear un nuevo objeto para cada registro
                        HorarioPersonasxDiaModelo data1 = new HorarioPersonasxDiaModelo();

                        data1.th_pro_id = (int)reader["th_pro_id"];
                        data1.th_per_id = (int)reader["th_per_id"];
                        data1.periodo_ini = Convert.ToDateTime(reader["periodo_ini"]);
                        data1.perido_fin = Convert.ToDateTime(reader["perido_fin"]);
                        data1.th_hor_id = (int)reader["th_hor_id"];
                        data1.cedula = reader["cedula"].ToString() ?? "";
                        data1.th_per_nombres_completos = reader["th_per_nombres_completos"].ToString() ?? "";
                        data1.th_card_id = (int)reader["th_card_id"];
                        data1.th_cardNo = reader["th_cardNo"].ToString() ?? "";
                        data1.entrada_min = (int)reader["entrada_min"];
                        data1.salida_min = (int)reader["salida_min"];
                        data1.tolerancia_ini = (int)reader["tolerancia_ini"];
                        data1.tolerancia_fin = (int)reader["tolerancia_fin"];
                        data1.entrada_tiempo_marcacion_valida_inicio = (int)reader["entrada_tiempo_marcacion_valida_inicio"];
                        data1.entrada_tiempo_marcacion_valida_fin = (int)reader["entrada_tiempo_marcacion_valida_fin"];
                        data1.salida_tiempo_marcacion_valida_inicio = (int)reader["salida_tiempo_marcacion_valida_inicio"];
                        data1.salida_tiempo_marcacion_valida_fin = (int)reader["salida_tiempo_marcacion_valida_fin"];
                        data1.horas_a_trabajar = Convert.ToInt32(reader["horas_a_trabajar"]);
                        data1.min_a_trabajar = Convert.ToInt32(reader["min_a_trabajar"]);
                        data1.aplica_descanso = Convert.ToInt32(reader["aplica_descanso"]);
                        data1.aplica_horario_descanso_intervalo = Convert.ToInt32(reader["aplica_horario_descanso_intervalo"]);
                        data1.tiempo_descanso = Convert.ToInt32(reader["tiempo_descanso"]);
                        data1.descanso_inicio = Convert.ToInt32(reader["descanso_inicio"]);
                        data1.descanso_fin = Convert.ToInt32(reader["descanso_fin"]);
                        data1.adelanto_descanso = Convert.ToInt32(reader["adelanto_descanso"]);
                        data1.tolerancia_descanso = Convert.ToInt32(reader["tolerancia_descanso"]);
                        data1.calcular_horas_extra = Convert.ToInt32(reader["calcular_horas_extra"]);
                        data1.inico_suplementario = Convert.ToInt32(reader["inico_suplementario"]);
                        data1.fin_suplementarias = Convert.ToInt32(reader["fin_suplementarias"]);
                        data1.inicio_extraordinarias = Convert.ToInt32(reader["inicio_extraordinarias"]);
                        data1.fin_extraordinarias = Convert.ToInt32(reader["fin_extraordinarias"]);

                        // Agregar a la lista
                        listaHorarios.Add(data1);
                    }
                }
            }

            return listaHorarios;
        }

        public void InsertData(SqlConnection conn, String data)
        {
            configConsulta();
            _empresaDal.validarTablas(conn, esquema, fecha);
            ControlAccesosModelo Acceso = new ControlAccesosModelo();
            DescuentosTiempoModelo DescuentoTime = new DescuentosTiempoModelo();
            String dato = data;
            JArray array = JArray.Parse(dato);

            //control de  accesos
            string cardNumber = array[0]?["Card_Number"]?.ToString() ?? string.Empty;
            string FechaMarcacionHora = array[0]["fecha"]?.ToString() ?? string.Empty;
            string FechaMarcacion = DateTime.Parse(FechaMarcacionHora).ToString("yyyy-MM-dd");

            if (!string.IsNullOrEmpty(cardNumber))
            {
                _InsertData.InsertTabla(conn, data);
                //revisa las faltas del dia de ayer
                ValidaFaltas(conn);

                TimeSpan ts = TimeSpan.Parse(array?[0]?["hora"]?.ToString() ?? "");
                int MinutosMarcacion = Convert.ToInt32(ts.TotalMinutes);

                //HorarioPersonasxDiaModelo horariosEncontradas = ConsultarHorariosxDia(conn,"",cardNumber);

                List<HorarioPersonasxDiaModelo> horarios = ObtenerHorariosXpersona(conn, "", cardNumber);
                HorarioPersonasxDiaModelo horariosEncontradas = horarios.FirstOrDefault();
                if (horarios.Count() == 0)
                {
                   horarios = ObtenerHorariosXDepartamento(conn, "", cardNumber);
                   horariosEncontradas = horarios.FirstOrDefault();
                }
                if (horarios.Count() > 0)
                {

                    string idPersona = horariosEncontradas.th_per_id.ToString() ?? "";
                    Acceso.th_dis_id = array[0]["ip"].ToString();
                    Acceso.th_acc_hora = array[0]["hora"].ToString();
                    Acceso.th_acc_fecha_hora = array[0]["fecha"].ToString();
                    Acceso.th_acc_puerto = array[0]["Puerto"].ToString();
                    Acceso.th_cardNo = cardNumber;
                    Acceso.th_acc_tipo_origen = "BIO";
                    Acceso.th_per_id = Convert.ToInt32(idPersona);
                    Acceso.th_acc_fecha = FechaMarcacion;

                    int rangoValidoIngreso = horariosEncontradas.entrada_min + horariosEncontradas.tolerancia_ini;
                    int rangoValidoSalida = horariosEncontradas.salida_min - horariosEncontradas.tolerancia_fin;

                    int descasoHabilitado = horariosEncontradas.aplica_descanso;
                    int descasoXIntervalosHabilitado = horariosEncontradas.aplica_horario_descanso_intervalo;

                    int rangoMarcacionSalidaFin = horariosEncontradas.salida_tiempo_marcacion_valida_fin;

                    //desde que hora a que hora se puede hacer la marcacion
                    int rangoMarcacionEntradaIni = horariosEncontradas.entrada_tiempo_marcacion_valida_inicio;
                    int rangoMarcacionEntradaFin = horariosEncontradas.entrada_tiempo_marcacion_valida_fin;

                    if (!_SelectData.ExisteMarcacion(conn, cardNumber, FechaMarcacion))
                    {
                        //si no existe marcacion alguna ingresa primera intrada 
                        if (MinutosMarcacion >= rangoMarcacionEntradaIni && MinutosMarcacion <= rangoMarcacionEntradaFin)
                        {
                            //validamos que no este conretrasos en la marcacion
                            if (MinutosMarcacion > rangoValidoIngreso)
                            {
                                AtrasosModelo Atrasos = new AtrasosModelo();
                                int RetrazadoX = MinutosMarcacion - rangoValidoIngreso;
                                Atrasos.th_per_id = idPersona;
                                Atrasos.asi_fecha_parametrizada = FechaMarcacion;
                                Atrasos.asi_hora_parametrizada = ConvertirConTimeSpan(rangoValidoIngreso);
                                Atrasos.asi_atrasos_fecha_marcacion = FechaMarcacionHora;
                                Atrasos.asi_atrasos_hora_marcacion = ts.ToString();
                                Atrasos.asi_atrasos_total_min = RetrazadoX;
                                _InsertData.InsertarAtrasos(conn, Atrasos);
                            }

                            //validamos que el descanso sea fijo
                            if (descasoHabilitado == 1 && descasoXIntervalosHabilitado == 0)
                            {
                                //descanzo con intervalos
                                int inicioDescanso = horariosEncontradas.descanso_inicio - horariosEncontradas.adelanto_descanso;
                                int finDescanso = horariosEncontradas.descanso_fin + horariosEncontradas.tolerancia_descanso;
                                if (!_SelectData.ExisteDescanso(conn, idPersona, FechaMarcacion, "Inicio descanso"))
                                {
                                    DescansoModelo _descansoModelo = new DescansoModelo();
                                    _descansoModelo.th_per_id = idPersona;
                                    _descansoModelo.asi_fecha_parametrizada = FechaMarcacion;
                                    _descansoModelo.asi_hora_parametrizada = ConvertirConTimeSpan(inicioDescanso);
                                    _descansoModelo.asi_descanso_detalle = "Inicio descanso";
                                    _descansoModelo.asi_descanso_fecha_marcacion = FechaMarcacionHora;
                                    _descansoModelo.asi_descanso_hora_marcacion = ts.ToString();
                                    _descansoModelo.asi_descanso_total_min = MinutosMarcacion - horariosEncontradas.descanso_inicio;
                                    _InsertData.InsertarDescanso(conn, _descansoModelo);
                                }
                            }

                            List<ControlAccesosModelo> horariosAcceso = _SelectData.RegistroEntradaCC(conn, FechaMarcacion, cardNumber);
                            ControlAccesosModelo horariosAccesoXPersona = horariosAcceso.FirstOrDefault();
                            int totalAtrazo = _SelectData.TotalAtrazos(conn, idPersona, FechaMarcacion);

                            Acceso.th_acc_tipo_registro = "Entrada";
                            Acceso.th_acc_detalle_registro = "Marcacion inicial";
                            Acceso.th_acc_atraso_min = totalAtrazo;
                            Acceso.th_acc_almuerzo_min = horariosEncontradas.tiempo_descanso;
                            Acceso.th_acc_horas_trabajadasJornada_min = 0;
                            Acceso.th_acc_justificacion_min = 0;
                            Acceso.th_acc_hor_faltantesJornada_min = horariosEncontradas.salida_min - horariosEncontradas.entrada_min - horariosEncontradas.tiempo_descanso;
                            Acceso.th_acc_horario_jornada = ConvertirConTimeSpan(horariosEncontradas.entrada_min) + " - " + ConvertirConTimeSpan(horariosEncontradas.salida_min);
                            _InsertData.InsertarAccesos(conn, Acceso);

                        }
                        else
                        {
                            //en este caso la marcacion esta fuera del rango de inicio y ya esta retrasado

                            if (MinutosMarcacion > rangoMarcacionEntradaFin)
                            {
                                if (!_SelectData.ExisteAtraso(conn, idPersona, FechaMarcacion))
                                {
                                    AtrasosModelo Atrasos = new AtrasosModelo();
                                    int RetrazadoX = MinutosMarcacion - horariosEncontradas.entrada_min;
                                    Atrasos.th_per_id = idPersona;
                                    Atrasos.asi_fecha_parametrizada = FechaMarcacion;
                                    Atrasos.asi_hora_parametrizada = ConvertirConTimeSpan(horariosEncontradas.entrada_min);
                                    Atrasos.asi_atrasos_fecha_marcacion = FechaMarcacionHora;
                                    Atrasos.asi_atrasos_hora_marcacion = ts.ToString();
                                    Atrasos.asi_atrasos_total_min = RetrazadoX;
                                    _InsertData.InsertarAtrasos(conn, Atrasos);
                                }
                                List<ControlAccesosModelo> horariosAcceso = _SelectData.RegistroEntradaCC(conn, FechaMarcacion, cardNumber);
                                ControlAccesosModelo horariosAccesoXPersona = horariosAcceso.FirstOrDefault();
                                int totalAtrazo = _SelectData.TotalAtrazos(conn, idPersona, FechaMarcacion);


                                if (horariosAcceso.Count() == 0)
                                {
                                    Acceso.th_acc_detalle_registro = "Marcacion inicial fuera de rango";
                                    Acceso.th_acc_horas_trabajadasJornada_min = 0;

                                    Acceso.th_acc_hor_faltantesJornada_min = (horariosEncontradas.salida_min - horariosEncontradas.tiempo_descanso) - (MinutosMarcacion - totalAtrazo);
                                }
                                else
                                {
                                    Acceso.th_acc_detalle_registro = "Registro normal";

                                    int nuevo_intervalo = horariosEncontradas.salida_min - horariosEncontradas.entrada_min - horariosEncontradas.tiempo_descanso - totalAtrazo;
                                    Acceso.th_acc_horas_trabajadasJornada_min = nuevo_intervalo - MinutosMarcacion - ConvertirHoratomin(horariosAccesoXPersona.th_acc_hora);

                                    Acceso.th_acc_hor_faltantesJornada_min = (horariosEncontradas.salida_min - horariosEncontradas.tiempo_descanso) - (MinutosMarcacion - totalAtrazo);

                                }

                                List<ControlAccesosModelo> Lista = _SelectData.RegistroEntradaCC(conn, FechaMarcacion, cardNumber);
                                int RegNum = Lista.Count();
                                if (RegNum % 2 == 0)
                                {
                                    Acceso.th_acc_tipo_registro = "Entrada";
                                }
                                else
                                {
                                    Acceso.th_acc_tipo_registro = "Salida";
                                }

                                Acceso.th_acc_atraso_min = totalAtrazo;
                                Acceso.th_acc_almuerzo_min = horariosEncontradas.tiempo_descanso;
                                Acceso.th_acc_justificacion_min = 0;
                                Acceso.th_acc_horario_jornada = ConvertirConTimeSpan(horariosEncontradas.entrada_min) + " - " + ConvertirConTimeSpan(horariosEncontradas.salida_min);

                                _InsertData.InsertarAccesos(conn, Acceso);

                            }
                        }

                    }
                    else
                    {
                        int iniSuplementarias = horariosEncontradas.inico_suplementario;
                        int finiSuplementarias = horariosEncontradas.fin_suplementarias;


                        int iniExtraordinarias = horariosEncontradas.inicio_extraordinarias;
                        int FinExtraordinarias = horariosEncontradas.fin_extraordinarias;

                        if (MinutosMarcacion > iniSuplementarias && MinutosMarcacion <= finiSuplementarias)
                        {
                            Acceso.th_acc_hor_suplementarias_min = MinutosMarcacion - iniSuplementarias;
                            Acceso.th_acc_hor_extraordinarias_min = 0;
                        }
                        else if (MinutosMarcacion > iniExtraordinarias && MinutosMarcacion <= FinExtraordinarias)
                        {
                            Acceso.th_acc_hor_suplementarias_min = finiSuplementarias - iniSuplementarias;
                            Acceso.th_acc_hor_extraordinarias_min = MinutosMarcacion - iniExtraordinarias;
                        }
                        else if (MinutosMarcacion > FinExtraordinarias)
                        {
                            Acceso.th_acc_hor_suplementarias_min = finiSuplementarias - iniSuplementarias;
                            Acceso.th_acc_hor_extraordinarias_min = FinExtraordinarias - iniExtraordinarias;
                        }

                            //ingresa en accesos
                            List<ControlAccesosModelo> horariosAcceso = _SelectData.RegistroEntradaCC(conn, FechaMarcacion, cardNumber);
                        ControlAccesosModelo horariosAccesoXPersona = horariosAcceso.FirstOrDefault();
                        int totalAtrazo = _SelectData.TotalAtrazos(conn, idPersona, FechaMarcacion);

                        List<ControlAccesosModelo> Lista = _SelectData.RegistroEntradaCC(conn, FechaMarcacion, cardNumber);
                        int RegNum = Lista.Count();
                        if (RegNum % 2 == 0)
                        {
                            Acceso.th_acc_tipo_registro = "Entrada";
                        }
                        else
                        {
                            Acceso.th_acc_tipo_registro = "Salida";
                        }
                        int nuevo_intervalo = horariosEncontradas.salida_min - horariosEncontradas.entrada_min - horariosEncontradas.tiempo_descanso - totalAtrazo;

                        Acceso.th_acc_detalle_registro = "Registro normal";
                        Acceso.th_acc_atraso_min = totalAtrazo;
                        Acceso.th_acc_almuerzo_min = horariosEncontradas.tiempo_descanso;

                        Acceso.th_acc_horas_trabajadasJornada_min = MinutosMarcacion - ConvertirHoratomin(horariosAccesoXPersona.th_acc_hora);
                        Acceso.th_acc_justificacion_min = 0;
                        Acceso.th_acc_hor_faltantesJornada_min = (horariosEncontradas.salida_min - horariosEncontradas.tiempo_descanso) - (MinutosMarcacion - totalAtrazo);
                        Acceso.th_acc_horario_jornada = ConvertirConTimeSpan(horariosEncontradas.entrada_min) + " - " + ConvertirConTimeSpan(horariosEncontradas.salida_min);
                        if (MinutosMarcacion > horariosEncontradas.salida_min)
                        {
                            Acceso.th_acc_hor_faltantesJornada_min = 0;
                            Acceso.th_acc_horas_trabajadasJornada_min = nuevo_intervalo;
                        }

                        _InsertData.InsertarAccesos(conn, Acceso);
                    }

                    //aplicamos los descansos
                    if (descasoHabilitado == 1 && descasoXIntervalosHabilitado == 0)
                    {
                        //descanzo con intervalos
                        int inicioDescanso = horariosEncontradas.descanso_inicio - horariosEncontradas.adelanto_descanso;
                        int finDescanso = horariosEncontradas.descanso_fin + horariosEncontradas.tolerancia_descanso;
                        if (!_SelectData.ExisteDescanso(conn, idPersona, FechaMarcacion, "Descanso sin intervalos"))
                        {
                            DescansoModelo _descansoModelo = new DescansoModelo();
                            _descansoModelo.th_per_id = idPersona;
                            _descansoModelo.asi_fecha_parametrizada = FechaMarcacion;
                            _descansoModelo.asi_hora_parametrizada = "00:00:00";
                            _descansoModelo.asi_descanso_detalle = "Descanso sin intervalos";
                            _descansoModelo.asi_descanso_fecha_marcacion = FechaMarcacionHora;
                            _descansoModelo.asi_descanso_hora_marcacion = ts.ToString();
                            _descansoModelo.asi_descanso_total_min = horariosEncontradas.tiempo_descanso;
                            _InsertData.InsertarDescanso(conn, _descansoModelo);
                        }
                    }
                    else if (descasoHabilitado == 0 && descasoXIntervalosHabilitado == 1)
                    {
                        //descanzo con intervalos
                        int inicioDescanso = horariosEncontradas.descanso_inicio - horariosEncontradas.adelanto_descanso;
                        int finDescanso = horariosEncontradas.descanso_fin + horariosEncontradas.tolerancia_descanso;

                        if (MinutosMarcacion >= inicioDescanso && MinutosMarcacion <= finDescanso)
                        {

                            DescansoModelo _descansoModelo = new DescansoModelo();
                            List<DescansoModelo> DescansoModelo = _SelectData.DescansoRegistros(conn, idPersona, FechaMarcacion, "");
                            int RegNumDes = DescansoModelo.Count();
                            if (RegNumDes % 2 == 0)
                            {
                                _descansoModelo.asi_descanso_detalle = "Inicio Descanzo";
                            }
                            else
                            {
                                _descansoModelo.asi_descanso_detalle = "Fin Descanzo";
                            }

                            _descansoModelo.th_per_id = idPersona;
                            _descansoModelo.asi_fecha_parametrizada = FechaMarcacion;
                            _descansoModelo.asi_hora_parametrizada = ConvertirConTimeSpan(inicioDescanso);
                            _descansoModelo.asi_descanso_fecha_marcacion = FechaMarcacionHora;
                            _descansoModelo.asi_descanso_hora_marcacion = ts.ToString();
                            _descansoModelo.asi_descanso_total_min = MinutosMarcacion - horariosEncontradas.descanso_inicio;
                            _InsertData.InsertarDescanso(conn, _descansoModelo);
                        }
                        else if (MinutosMarcacion > finDescanso)
                        {
                            if (!_SelectData.ExisteDescanso(conn, idPersona, FechaMarcacion, "Fin Descanzo"))
                            {
                                //BuscarEmpresa registro de descuento si ya existe
                                AtrasosModelo Atrasos = new AtrasosModelo();
                                int RetrazadoX = MinutosMarcacion - finDescanso;
                                Atrasos.th_per_id = idPersona;
                                Atrasos.asi_fecha_parametrizada = FechaMarcacion;
                                Atrasos.asi_hora_parametrizada = ConvertirConTimeSpan(finDescanso);
                                Atrasos.asi_atrasos_fecha_marcacion = FechaMarcacionHora;
                                Atrasos.asi_atrasos_hora_marcacion = ts.ToString();
                                Atrasos.asi_atrasos_total_min = RetrazadoX;
                                _InsertData.InsertarAtrasos(conn, Atrasos);

                                int totalAtrazo = _SelectData.TotalAtrazos(conn, idPersona, FechaMarcacion);
                                Acceso.th_acc_atraso_min = totalAtrazo;
                                _InsertData.InsertarAccesos(conn, Acceso);

                                DescansoModelo _descansoModelo = new DescansoModelo();
                                _descansoModelo.asi_descanso_detalle = "Fin Descanzo";
                                _descansoModelo.th_per_id = idPersona;
                                _descansoModelo.asi_fecha_parametrizada = FechaMarcacion;
                                _descansoModelo.asi_hora_parametrizada = ConvertirConTimeSpan(inicioDescanso);
                                _descansoModelo.asi_descanso_fecha_marcacion = FechaMarcacionHora;
                                _descansoModelo.asi_descanso_hora_marcacion = ts.ToString();
                                _descansoModelo.asi_descanso_total_min = MinutosMarcacion - horariosEncontradas.descanso_inicio;
                                _InsertData.InsertarDescanso(conn, _descansoModelo);
                            }
                        }
                    }

                }
            }
        }


        public void ValidaFaltas(SqlConnection conn)
        {
            DateTime hoy = DateTime.Now;
            int dia = (int)hoy.DayOfWeek;
            String diaVar = dia.ToString();

            DateTime ayer = DateTime.Now.AddDays(-1);
            string fechaAyer = ayer.ToString("yyyy-MM-dd");
            //validar si existen datos del dia anterior
            if(!_SelectData.ExisteMarcacioFaltas( conn, fechaAyer)) { 
            List<HorarioPersonasxDiaModelo> horarios = ObtenerHorariosXpersona(conn, diaVar, "");
                if (horarios.Count() == 0) { horarios = ObtenerHorariosXDepartamento(conn, diaVar, ""); }
                foreach (var horario in horarios)
                {
                    if (!_SelectData.ExisteMarcacion(conn, horario.th_cardNo, fechaAyer))
                    {
                        //descontartiempo
                        DescuentosTiempoModelo DescuentoTime = new DescuentosTiempoModelo();
                        DescuentoTime.asi_desc_motivo = "Falta";
                        DescuentoTime.th_per_id = horario.th_per_id.ToString();
                        DescuentoTime.asi_fecha_parametrizada = fechaAyer;
                        DescuentoTime.asi_hora_parametrizada = ConvertirConTimeSpan(horario.entrada_min);
                        DescuentoTime.asi_desc_total_min = horario.salida_min - horario.entrada_min;
                        _InsertData.InsertarDescuentos(conn, DescuentoTime);

                        FaltasModelo Faltas = new FaltasModelo();
                        Faltas.th_per_id = horario.th_per_id.ToString();
                        Faltas.asi_faltas_fecha_inicio = Convert.ToDateTime(fechaAyer + " 00:00:00");
                        Faltas.asi_faltas_fecha_fin = Convert.ToDateTime(fechaAyer + " 00:00:00");
                        Faltas.asi_faltas_total_min = horario.salida_min - horario.entrada_min;
                        _InsertData.InsertarFaltas(conn, Faltas);

                    }
                }
            }
        }

      
        public static string ConvertirConTimeSpan(int minutos)
        {
            TimeSpan tiempo = TimeSpan.FromMinutes(minutos);
            return $"{(int)tiempo.TotalHours}:{tiempo.Minutes:D2}";
        }

        public static int ConvertirHoratomin(String hora)
        {
            TimeSpan tiempo = TimeSpan.Parse(hora);
            int minutos = Convert.ToInt32(tiempo.TotalMinutes);
            return minutos;
        }
    }

}
