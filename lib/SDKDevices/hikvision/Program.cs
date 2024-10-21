// See https://aka.ms/new-console-template for more information
using libreriasHik;
using System;
using static libreriasHik.CHCNetSDK;
using System.Runtime.InteropServices;
using System.Net.Sockets;
using System.Net;
using System.Text;
using System.Xml;
using Newtonsoft.Json;

class Program
{
    public static String ip = "192.168.100.111";
    public static String user = "admin";
    public static String port = "8000";
    public static String pass = "Data12/*";

    public static int m_UserID = -1;
    public int lFortifyHandle = -1;
    public static CHCNetSDK.MSGCallBack m_falarmData = null;
    public int m_lLogNum = 0;
    public static string path = null;
    public string ShowData = null;
    static void Main(string[] args)
    {
        if (args.Length > 0)
        {
            if (args[0] == "1")
            {
                //Task.Run(() => DetectarDevice());
                DetectarDevice();
            }
            else
            {
                if (CHCNetSDK.NET_DVR_Init() == false)
                {
                    Console.WriteLine("NET_DVR_Init error!");
                    return;
                }
                Login();
                Task.Run(() => Escuchando());
                // Mantener la consola ejecutándose para que el proceso de escucha no se detenga
                Console.WriteLine("Presiona 'q' para salir...");
                while (Console.ReadKey().Key != ConsoleKey.Q)
                {
                    //    // Aquí puedes agregar otra lógica si lo necesitas
                }
        }
        }
        else 
        {
            string jsonText = JsonConvert.SerializeObject("No se a enviado ningun parametro"); // Convertir a JSON
            // Mostrar el JSON
            Console.WriteLine(jsonText);
        }
      
    }

    static void DetectarDevice()
    {
        String xmlMessage = "<?xml version=\"1.0\" encoding=\"utf-8\"?><Probe><Uuid>13A888A9-F1B1-4020-AE9F-05607682D23B</Uuid><Types>inquiry</Types></Probe>";

        // Crear un cliente UDP

        XmlDocument xmlDoc = new XmlDocument();
        UdpClient udpClient = new UdpClient();
        List<string> receivedJsonList = new List<string>();
        udpClient.EnableBroadcast = true;
        //IPEndPoint endPoint = new IPEndPoint(IPAddress.Broadcast, 37020);
        IPEndPoint endPoint = new IPEndPoint(IPAddress.Parse("239.255.255.250"), 37020); // Puerto SADP por defecto

        // Paquete SADP de búsqueda
        byte[] sadpMessage = Encoding.UTF8.GetBytes(xmlMessage); // Ejemplo de paquete de búsqueda
        bool keepReceiving = true;
        try
        {

            while (keepReceiving)
            {
                // Enviar el paquete de búsqueda
                udpClient.Send(sadpMessage, sadpMessage.Length, endPoint);
                udpClient.Client.ReceiveTimeout = 10000;  // Establecer el tiempo de espera en 5000 ms (5 segundos)
                IPEndPoint receiveEndPoint = new IPEndPoint(IPAddress.Any, 0);

                try
                {
                    // Bloquea hasta que se reciba una respuesta
                    byte[] receivedBytes = udpClient.Receive(ref receiveEndPoint);

                    // Convertir la respuesta a cadena XML
                    string receivedXml = Encoding.UTF8.GetString(receivedBytes);
                    xmlDoc.LoadXml(receivedXml); // Cargar el XML en XmlDocument
                    string jsonText = JsonConvert.SerializeXmlNode(xmlDoc); // Convertir a JSON
                    receivedJsonList.Add(jsonText);
                    keepReceiving = false;
                    // Mostrar el JSON
                    //Console.WriteLine(jsonText);
                }
                catch (SocketException ex)
                {
                    string receivedXml = "<Error>No se recibieron datos en el tiempo de espera especificado.</Error>";
                    xmlDoc.LoadXml(receivedXml); // Cargar el XML en XmlDocument
                    string jsonText = JsonConvert.SerializeXmlNode(xmlDoc); // Convertir a JSON
                    //Console.WriteLine(jsonText);
                    receivedJsonList.Add(jsonText);
                    keepReceiving = false;
                }
            }
        }
        finally
        {
            udpClient.Close();
        }

            string jsonText2 = JsonConvert.SerializeObject(receivedJsonList);
            Console.WriteLine(jsonText2);

            //}

        //}
        //catch (Exception ex)
        //{
        //    // Console.WriteLine($"Error: {ex.Message}");
        //    string receivedXml = "<Error>" + ex.Message + "</Error>";
        //    xmlDoc.LoadXml(receivedXml); // Cargar el XML en XmlDocument
        //    string jsonText = JsonConvert.SerializeXmlNode(xmlDoc); // Convertir a JSON
        //    // Mostrar el JSON
        //    Console.WriteLine(jsonText);
        //}
       
    }

    static void Login()
    {

        Console.WriteLine("Iniciando login");
        if (m_UserID >= 0)
        {
            CHCNetSDK.NET_DVR_Logout_V30(m_UserID);
            m_UserID = -1;
        }
        CHCNetSDK.NET_DVR_USER_LOGIN_INFO struLoginInfo = new CHCNetSDK.NET_DVR_USER_LOGIN_INFO();
        CHCNetSDK.NET_DVR_DEVICEINFO_V40 struDeviceInfoV40 = new CHCNetSDK.NET_DVR_DEVICEINFO_V40();
        struDeviceInfoV40.struDeviceV30.sSerialNumber = new byte[CHCNetSDK.SERIALNO_LEN];

        struLoginInfo.sDeviceAddress = ip;
        struLoginInfo.sUserName = user;
        struLoginInfo.sPassword = pass;
        ushort.TryParse(port, out struLoginInfo.wPort);

        int lUserID = -1;
        lUserID = CHCNetSDK.NET_DVR_Login_V40(ref struLoginInfo, ref struDeviceInfoV40);
        if (lUserID >= 0)
        {
            m_UserID = lUserID;
            Console.WriteLine("Login Successful");
        }
        else
        {
            uint nErr = CHCNetSDK.NET_DVR_GetLastError();
            if (nErr == CHCNetSDK.NET_DVR_PASSWORD_ERROR)
            {
                Console.WriteLine("user name or password error!");
                if (1 == struDeviceInfoV40.bySupportLock)
                {
                    string strTemp1 = string.Format("Left {0} try opportunity", struDeviceInfoV40.byRetryLoginTime);
                    Console.WriteLine(strTemp1);
                }
            }
            else if (nErr == CHCNetSDK.NET_DVR_USER_LOCKED)
            {
                if (1 == struDeviceInfoV40.bySupportLock)
                {
                    string strTemp1 = string.Format("user is locked, the remaining lock time is {0}", struDeviceInfoV40.dwSurplusLockTime);
                    Console.WriteLine(strTemp1);
                }
            }
            else
            {
                Console.WriteLine("Login fail error: " + nErr);
            }
        }
    }

    static void Escuchando()
    {
        if (m_UserID < 0)
        {
            Console.WriteLine("Please Login First!");
            return;
        }
        else
        {
            CHCNetSDK.NET_DVR_SETUPALARM_PARAM struSetupAlarmParam = new CHCNetSDK.NET_DVR_SETUPALARM_PARAM();
            struSetupAlarmParam.dwSize = (uint)Marshal.SizeOf(struSetupAlarmParam);
            struSetupAlarmParam.byLevel = 1;
            struSetupAlarmParam.byAlarmInfoType = 1;
            struSetupAlarmParam.byDeployType = 1; //este es para detectar

            if (CHCNetSDK.NET_DVR_SetupAlarmChan_V41(m_UserID, ref struSetupAlarmParam) < 0)
            {
                Console.WriteLine("Error al configurar el canal de alarma: " + CHCNetSDK.NET_DVR_GetLastError(), "Setup alarm chan failed");
                return;
            }
            else
            {
                Console.WriteLine("Configuración del canal de alarma exitosa.");
            }

            m_falarmData = new CHCNetSDK.MSGCallBack(MsgCallback);
            if (CHCNetSDK.NET_DVR_SetDVRMessageCallBack_V50(0, m_falarmData, IntPtr.Zero))
            {
                Console.WriteLine("Callback registrado exitosamente.");
            }
            else
            {
                Console.WriteLine("Error al registrar el callback.");
            }

            // Mantener el proceso en ejecución
            Console.WriteLine("Escuchando alarmas...");
            while (true)
            {
                // Puedes agregar una pequeña pausa para evitar un uso excesivo de CPU
                Thread.Sleep(1000);
            }
        }
    }

    static void MsgCallback(int lCommand, ref CHCNetSDK.NET_DVR_ALARMER pAlarmer, IntPtr pAlarmInfo, uint dwBufLen, IntPtr pUser)
    {
        switch (lCommand)
        {
            case CHCNetSDK.COMM_ALARM_ACS:
                ProcessCommAlarmACS(ref pAlarmer, pAlarmInfo, dwBufLen, pUser);
                break;
            default:
                break;
        }
    }

    //static void ProcessCommAlarmACS(ref CHCNetSDK.NET_DVR_ALARMER pAlarmer, IntPtr pAlarmInfo, uint dwBufLen, IntPtr pUser)
    //{
    //    CHCNetSDK.NET_DVR_ACS_ALARM_INFO struAcsAlarmInfo = (CHCNetSDK.NET_DVR_ACS_ALARM_INFO)Marshal.PtrToStructure(pAlarmInfo, typeof(CHCNetSDK.NET_DVR_ACS_ALARM_INFO));

    //    // Aquí puedes extraer la información de la alarma y mostrarla en la consola
    //    Console.WriteLine("Alarma recibida: " + struAcsAlarmInfo.dwMajor + ", " + struAcsAlarmInfo.dwMinor+" Fecha:"+ DateTime.Now.ToString());

    //    // Mostrar más información de la alarma (puedes personalizarlo)
    //    Console.WriteLine("Tarjeta: " + System.Text.Encoding.UTF8.GetString(struAcsAlarmInfo.struAcsEventInfo.byCardNo).TrimEnd('\0'));
    //    Console.WriteLine("Dispositivo: " + struAcsAlarmInfo.struRemoteHostAddr.sIpV4);
    //}

    static void ProcessCommAlarmACS(ref CHCNetSDK.NET_DVR_ALARMER pAlarmer, IntPtr pAlarmInfo, uint dwBufLen, IntPtr pUser)
    {
        CHCNetSDK.NET_DVR_ACS_ALARM_INFO struAcsAlarmInfo = new CHCNetSDK.NET_DVR_ACS_ALARM_INFO();
        struAcsAlarmInfo = (CHCNetSDK.NET_DVR_ACS_ALARM_INFO)Marshal.PtrToStructure(pAlarmInfo, typeof(CHCNetSDK.NET_DVR_ACS_ALARM_INFO));
        CHCNetSDK.NET_DVR_LOG_V30 struFileInfo = new CHCNetSDK.NET_DVR_LOG_V30();
        struFileInfo.dwMajorType = struAcsAlarmInfo.dwMajor;
        struFileInfo.dwMinorType = struAcsAlarmInfo.dwMinor;
        char[] csTmp = new char[256];

        if (CHCNetSDK.MAJOR_ALARM == struFileInfo.dwMajorType)
        {
            TypeMap.AlarmMinorTypeMap(struFileInfo, csTmp);
        }
        else if (CHCNetSDK.MAJOR_OPERATION == struFileInfo.dwMajorType)
        {
            TypeMap.OperationMinorTypeMap(struFileInfo, csTmp);
        }
        else if (CHCNetSDK.MAJOR_EXCEPTION == struFileInfo.dwMajorType)
        {
            TypeMap.ExceptionMinorTypeMap(struFileInfo, csTmp);
        }
        else if (CHCNetSDK.MAJOR_EVENT == struFileInfo.dwMajorType)
        {
            TypeMap.EventMinorTypeMap(struFileInfo, csTmp);
        }

        String szInfo = new String(csTmp).TrimEnd('\0');
        String szInfoBuf = null;
        szInfoBuf = szInfo;
        /**************************************************/
        String name = System.Text.Encoding.UTF8.GetString(struAcsAlarmInfo.sNetUser).TrimEnd('\0');
        for (int i = 0; i < struAcsAlarmInfo.sNetUser.Length; i++)
        {
            if (struAcsAlarmInfo.sNetUser[i] == 0)
            {
                name = name.Substring(0, i);
                break;
            }
        }
        /**************************************************/

        szInfoBuf = string.Format("{0} time:{1,4}-{2:D2}-{3} {4:D2}:{5:D2}:{6:D2}, [{7}]({8})", szInfo, struAcsAlarmInfo.struTime.dwYear, struAcsAlarmInfo.struTime.dwMonth,
            struAcsAlarmInfo.struTime.dwDay, struAcsAlarmInfo.struTime.dwHour, struAcsAlarmInfo.struTime.dwMinute, struAcsAlarmInfo.struTime.dwSecond,
            struAcsAlarmInfo.struRemoteHostAddr.sIpV4, name);

        if (struAcsAlarmInfo.struAcsEventInfo.byCardNo[0] != 0)
        {
            szInfoBuf = szInfoBuf + "+Card Number:" + System.Text.Encoding.UTF8.GetString(struAcsAlarmInfo.struAcsEventInfo.byCardNo).TrimEnd('\0');
        }
        String[] szCardType = { "normal card", "disabled card", "blocklist card", "night watch card", "stress card", "super card", "guest card" };
        byte byCardType = struAcsAlarmInfo.struAcsEventInfo.byCardType;

        if (byCardType != 0 && byCardType <= szCardType.Length)
        {
            szInfoBuf = szInfoBuf + "+Card Type:" + szCardType[byCardType - 1];
        }

        if (struAcsAlarmInfo.struAcsEventInfo.dwCardReaderNo != 0)
        {
            szInfoBuf = szInfoBuf + "+Card Reader Number:" + struAcsAlarmInfo.struAcsEventInfo.dwCardReaderNo;
        }
        if (struAcsAlarmInfo.struAcsEventInfo.dwDoorNo != 0)
        {
            szInfoBuf = szInfoBuf + "+Door Number:" + struAcsAlarmInfo.struAcsEventInfo.dwDoorNo;
        }
        if (struAcsAlarmInfo.struAcsEventInfo.dwVerifyNo != 0)
        {
            szInfoBuf = szInfoBuf + "+Multiple Card Authentication Serial Number:" + struAcsAlarmInfo.struAcsEventInfo.dwVerifyNo;
        }
        if (struAcsAlarmInfo.struAcsEventInfo.dwAlarmInNo != 0)
        {
            szInfoBuf = szInfoBuf + "+Alarm Input Number:" + struAcsAlarmInfo.struAcsEventInfo.dwAlarmInNo;
        }
        if (struAcsAlarmInfo.struAcsEventInfo.dwAlarmOutNo != 0)
        {
            szInfoBuf = szInfoBuf + "+Alarm Output Number:" + struAcsAlarmInfo.struAcsEventInfo.dwAlarmOutNo;
        }
        if (struAcsAlarmInfo.struAcsEventInfo.dwCaseSensorNo != 0)
        {
            szInfoBuf = szInfoBuf + "+Event Trigger Number:" + struAcsAlarmInfo.struAcsEventInfo.dwCaseSensorNo;
        }
        if (struAcsAlarmInfo.struAcsEventInfo.dwRs485No != 0)
        {
            szInfoBuf = szInfoBuf + "+RS485 Channel Number:" + struAcsAlarmInfo.struAcsEventInfo.dwRs485No;
        }
        if (struAcsAlarmInfo.struAcsEventInfo.dwMultiCardGroupNo != 0)
        {
            szInfoBuf = szInfoBuf + "+Multi Recombinant Authentication ID:" + struAcsAlarmInfo.struAcsEventInfo.dwMultiCardGroupNo;
        }
        if (struAcsAlarmInfo.struAcsEventInfo.byCardReaderKind != 0)
        {
            szInfoBuf = szInfoBuf + "+CardReaderKind:" + struAcsAlarmInfo.struAcsEventInfo.byCardReaderKind.ToString();
        }
        if (struAcsAlarmInfo.struAcsEventInfo.wAccessChannel >= 0)
        {
            szInfoBuf = szInfoBuf + "+wAccessChannel:" + struAcsAlarmInfo.struAcsEventInfo.wAccessChannel;
        }
        if (struAcsAlarmInfo.struAcsEventInfo.dwEmployeeNo != 0)
        {
            szInfoBuf = szInfoBuf + "+EmployeeNo:" + struAcsAlarmInfo.struAcsEventInfo.dwEmployeeNo;
        }
        if (struAcsAlarmInfo.struAcsEventInfo.byDeviceNo != 0)
        {
            szInfoBuf = szInfoBuf + "+byDeviceNo:" + struAcsAlarmInfo.struAcsEventInfo.byDeviceNo.ToString();
        }
        if (struAcsAlarmInfo.struAcsEventInfo.wLocalControllerID >= 0)
        {
            szInfoBuf = szInfoBuf + "+wLocalControllerID:" + struAcsAlarmInfo.struAcsEventInfo.wLocalControllerID;
        }
        if (struAcsAlarmInfo.struAcsEventInfo.byInternetAccess >= 0)
        {
            szInfoBuf = szInfoBuf + "+byInternetAccess:" + struAcsAlarmInfo.struAcsEventInfo.byInternetAccess.ToString();
        }
        if (struAcsAlarmInfo.struAcsEventInfo.byType >= 0)
        {
            szInfoBuf = szInfoBuf + "+byType:" + struAcsAlarmInfo.struAcsEventInfo.byType.ToString();
        }
        if (struAcsAlarmInfo.struAcsEventInfo.bySwipeCardType != 0)
        {
            szInfoBuf = szInfoBuf + "+bySwipeCardType:" + struAcsAlarmInfo.struAcsEventInfo.bySwipeCardType.ToString();
        }
        //其它消息先不罗列了......

        if (struAcsAlarmInfo.dwPicDataLen > 0)
        {
            path = null;
            Random rand = new Random(unchecked((int)DateTime.Now.Ticks));
            path = string.Format(@"C:/Picture/ACS_LocalTime{0}_{1}.bmp", szInfo, rand.Next());
            using (FileStream fs = new FileStream(path, FileMode.Create))
            {
                int iLen = (int)struAcsAlarmInfo.dwPicDataLen;
                byte[] by = new byte[iLen];
                Marshal.Copy(struAcsAlarmInfo.pPicData, by, 0, iLen);
                fs.Write(by, 0, iLen);
                fs.Close();
            }
            szInfoBuf = szInfoBuf + "SavePath:" + path;
        }

        Console.WriteLine(szInfoBuf);

        Console.WriteLine("Alarma recibida: " + struAcsAlarmInfo.dwMajor + ", " + struAcsAlarmInfo.dwMinor + " Fecha:" + DateTime.Now.ToString());

        // Mostrar más información de la alarma (puedes personalizarlo)
        Console.WriteLine("Tarjeta: " + System.Text.Encoding.UTF8.GetString(struAcsAlarmInfo.struAcsEventInfo.byCardNo).TrimEnd('\0'));
        Console.WriteLine("Dispositivo: " + struAcsAlarmInfo.struRemoteHostAddr.sIpV4);

    }

}
       
