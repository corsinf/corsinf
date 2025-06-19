// See https://aka.ms/new-console-template for more information
using System;
using System.Net.Sockets;
using System.Net;
using System.Text;

using CorsinfSDKHik.Funciones;
using CorsinfSDKHik.ConfigDB;
using CorsinfSDKHik.NetSDK;
using CorsinfSDKHik.SDKs;
using System.Xml.Linq;
using System.Text.Json;
using System.Runtime;
using Microsoft.Data.SqlClient;
using Microsoft.IdentityModel.Tokens;

class Program
{
    static int m_UserId = -1;
    static int m_SetSuccess = -1;
    static int m_SetSuccessFing = -1;
    static String ip;
    static String user;
    static String port;
    static String pass;
    static String ipHost;
    static String portHost;
    static String dbName;
    static String userHost;
    static String passHost;


    public static async Task Main(string[] args)
    {
        var json = "";
        String r;
        if (args.Length > 0)
        {
            loginSDK login = new loginSDK();
            dbConfig db = new dbConfig();
            if (CHCNetSDK.NET_DVR_Init() == false)
            {
                Console.WriteLine("error al inicializar");
                return;
            }
            String config = "[0]-Opcion;[1]-IP de dispositivo;[2]-Usario de dispositivo;[3]-Puerto de dispositivo;[4]-pass de dispositivo;";
            switch (args[0])
            {
                case "-info":
                    if (args.Length == 2)
                    {
                        switch (args[1])
                        {
                            case "1":
                                //DETECTAR DISPOSITIVOS CONECTADOS EN LA RED
                                json = JsonSerializer.Serialize(new { msj = config });
                                break;
                            case "2":
                                //COMPROBAR CONEXION
                                json = JsonSerializer.Serialize(new { msj = config });
                                break;
                            case "3":
                                //CAPTURAR EL DEDO
                                config += "[5]-nombre del archivo;[6]-ruta donde guardar el archivo";
                                json = JsonSerializer.Serialize(new { msj = config });
                                break;
                            case "4":
                                //AGREGAR USUARIO A BIOMETRICO
                                config += "[5]-Nombre de persona;[6]-NUmero de empleado;[7]-Numero de tarjeta;[8]-ruta de huella digital";
                                json = JsonSerializer.Serialize(new { msj = config });
                                break;
                            case "5":
                                //TRAER A LOS USUARIOS DEL BIOMETRICO
                                json = JsonSerializer.Serialize(new { msj = config });
                                break;
                        }
                    }
                    else {
                        json = JsonSerializer.Serialize(new { msj = config });
                    }
                    break;
                case "1":
                    //DETECTAR DISPOSITIVOS CONECTADOS EN LA RED
                    String vlans = args.Length > 1 ? args[1] : string.Empty;
                    r = await login.DetectarDeviceAsync(vlans);
                    json = JsonSerializer.Serialize(new { msj = r.ToString()});
                    break;
                case "2":
                    //COMPROBAR CONEXION
                    ip = args[1];
                    user = args[2];
                    port = args[3];
                    pass = args[4];
                    r = login.loginSDKDevice(ip, port, user, pass);
                    m_UserId = login.m_UserID;
                    json = JsonSerializer.Serialize(new { msj = r });
                    break;
                case "3":
                    //CAPTURAR EL DEDO
                    ip = args[1];
                    user = args[2];
                    port = args[3];
                    pass = args[4];
                    String userName = args[5];
                    String patch = args[6];
                    r = login.loginSDKDevice(ip, port, user, pass);
                    m_UserId = login.m_UserID;
                    if (m_UserId >= 0)
                    {
                        FingerManagerSDK FingerMan = new FingerManagerSDK();
                        r = FingerMan.capturarDedo(m_UserId, userName, patch);

                        m_SetSuccess = FingerMan.m_SaveSuccessFing;
                    }
                    json = JsonSerializer.Serialize(new { msj = r,resp= m_SetSuccess});
                    break;
                case "4":
                    //AGREGAR USUARIO A BIOMETRICO
                    ip = args[1];
                    user = args[2];
                    port = args[3];
                    pass = args[4];
                    String CardNo = args[5]; // numero de tarjeta

                    r = login.loginSDKDevice(ip, port, user, pass);
                    m_UserId = login.m_UserID;
                    if (m_UserId >= 0)
                    {                       
                        String ruta = args[6];
                        String FingerID = args[7]; //enviar el numero de huellas, si ya tiene uno enviar 2 3 4 como corresponda
                        String CardReaderNo = "1";  // esta variable solo se coloca entre 1 y 2 

                        FingerManagerSDK FingerMan = new FingerManagerSDK();
                        r = FingerMan.SetearFinger(m_UserId, FingerID, CardReaderNo, CardNo, ruta);
                        m_SetSuccessFing = FingerMan.m_SetSuccessFing;
                       
                    }
                    json = JsonSerializer.Serialize(new { msj = r,resp = m_SetSuccessFing });
                    break;

                case "5":
                    //TRAER A LOS USUARIOS DEL BIOMETRICO
                    ip = args[1];
                    user = args[2];
                    port = args[3];
                    pass = args[4];
                    r = login.loginSDKDevice(ip, port, user, pass);
                    m_UserId = login.m_UserID;
                    if (m_UserId >= 0)
                    {
                        CardManagerSDK CardMan = new CardManagerSDK();
                        r = CardMan.BuscarPersonas(m_UserId);
                        r = r.Substring(0, r.Length - 1);
                    }

                    json = JsonSerializer.Serialize(new { msj = r });
                    break;
                case "6":
                    //CAPTURAR EVENTO DE DETECCION DE DEDO
                    ip = args[1];
                    user = args[2];
                    port = args[3];
                    pass = args[4];
                    r = login.LoginFing(ip, port, user, pass);
                    ipHost = args[5];
                    portHost = args[6];
                    dbName = args[7];
                    userHost = args[8];
                    passHost = args[9];
                    m_UserId = login.m_UserID;
                    if (m_UserId >= 0)
                    {
                        SqlConnection conn_ = db.conexion(ipHost, portHost, dbName, userHost, passHost);
                        FingerManagerSDK FingerMan = new FingerManagerSDK();
                        Task.Run(() => FingerMan.Escuchando(m_UserId,conn_,port));
                        // Mantener la consola ejecutándose para que el proceso de escucha no se detenga
                        // Console.WriteLine("Presiona 'q' para salir...");
                        while (true)
                        {
                            Thread.Sleep(1000); // Mantener el programa vivo
                        }
                    }

                    break;
                case "7":
                    //INGRESAR TARJETA PERSONA Y TARJETA
                    ip = args[1];
                    user = args[2];
                    port = args[3];
                    pass = args[4];
                    String NombreT = args[5];
                    string CardRightPlanT = "1"; // por defecto
                    String EmployeeNoT = args[6];// este es el id del empleado
                    String CardNoT = args[7]; // numero de tarjeta

                    r = login.loginSDKDevice(ip, port, user, pass);
                    m_UserId = login.m_UserID;
                    if (m_UserId >= 0)
                    {
                        CardManagerSDK CardMan = new CardManagerSDK();
                        r = CardMan.SetearCard(m_UserId, CardNoT, CardRightPlanT, EmployeeNoT, NombreT);
                        m_SetSuccess = CardMan.m_SetSuccess;
                        if (m_SetSuccess == 1)
                        {
                            r = "Persona Registrada";
                        }
                    }
                    json = JsonSerializer.Serialize(new { msj = r,resp = m_SetSuccess });
                    break;
                case "8":
                    //eliminar TARJETA PERSONA Y TARJETA
                    ip = args[1];
                    user = args[2];
                    port = args[3];
                    pass = args[4];
                    String CardNoDEL = args[5]; // numero de tarjeta

                    r = login.loginSDKDevice(ip, port, user, pass);
                    m_UserId = login.m_UserID;
                    if (m_UserId >= 0)
                    {
                        CardManagerSDK CardMan = new CardManagerSDK();
                        r = CardMan.EliminarCardNo(m_UserId, CardNoDEL);
                        m_SetSuccess = CardMan.m_SetSuccess;

                    }
                    json = JsonSerializer.Serialize(new { msj = r,resp = m_SetSuccess});

                    break;
                case "9":
                    //eliminar FINGER DE  PERSONA
                    ip = args[1];
                    user = args[2];
                    port = args[3];
                    pass = args[4];
                    String CardNof = args[5]; // numero de tarjeta
                    String IDdevice = args[6]; // numero de tarjeta

                    r = login.loginSDKDevice(ip, port, user, pass);
                    m_UserId = login.m_UserID;
                    if (m_UserId >= 0)
                    {
                        FingerManagerSDK FingerMan = new FingerManagerSDK();
                        r = FingerMan.GetRegisteredFingerprintIDs(m_UserId,CardNof,IDdevice);
                        m_SetSuccessFing = FingerMan.m_SetSuccessFing;

                    }
                    json = JsonSerializer.Serialize(new { msj = r, resp = m_SetSuccessFing });
                   
                    break;
                case "10":
                    //TRAER A LOS logs USUARIOS DEL BIOMETRICO

                    DateTime fechaHoy = DateTime.Today;
                    ip = args[1];
                    user = args[2];
                    port = args[3];
                    pass = args[4];
                    String fechaIni = args.Length > 5 ? args[5] : fechaHoy.ToString("yyyy-MM-dd");
                    String fechaFin = args.Length > 6 ? args[6] : fechaHoy.ToString("yyyy-MM-dd");
                    r = login.loginSDKDevice(ip, port, user, pass);
                    m_UserId = login.m_UserID;
                    if (m_UserId >= 0)
                    {
                        hikvisionSDKLog logs = new hikvisionSDKLog();
                        r =  logs.buscarLogs(m_UserId,fechaIni,fechaFin,ip,port).ToString();
                    }

                    json = JsonSerializer.Serialize(new { msj = r });
                    break;
                case "11":
                    //GENERAR TABLA DE LOG PARA EJEMPLO
                    ipHost = args[5];
                    portHost = args[6];
                    dbName = args[7];
                    userHost = args[8];
                    passHost = args[9];
                    Modelo dbModelo = new Modelo();
                    String data = "hola desde dispositivo";
                    SqlConnection conn = db.conexion(ipHost, portHost, dbName, userHost, passHost);
                    r = "connexion a base incorrecta";
                    if (conn == null)
                    {
                        json = JsonSerializer.Serialize(new { msj = r });
                    }
                    else
                    {
                        dbModelo.InsertData(conn, data);
                    }

                   



                    break;
            }
            CHCNetSDK.NET_DVR_Logout_V30(m_UserId);
            CHCNetSDK.NET_DVR_Cleanup();
        }
        else 
        {
            json = JsonSerializer.Serialize(new { msj = "para mas informacion del sdk -info" });
        }
        Console.WriteLine(json);

    }
}