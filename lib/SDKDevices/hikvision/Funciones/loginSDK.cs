using CorsinfSDKHik;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Xml;
using CorsinfSDKHik.NetSDK;
using System.Net.Sockets;
using System.Net;
using Newtonsoft.Json;
using static CorsinfSDKHik.NetSDK.CHCNetSDK;
using CorsinfSDKHik.Funciones;
//using FingerPrintManagement;

namespace CorsinfSDKHik.SDKs
{
    public class loginSDK
    {
        public int m_UserID = -1;
        XmlDocument xmlDoc = new XmlDocument();

        public String loginSDKDevice(String ip, String puerto, String user, String pass)
        {
            if (ip.Length <= 0 || ip.Length > 128)
            {
                //Console.WriteLine("Ip invalida");
                return "Ip invalida";
            }

            int port;
            int.TryParse(puerto, out port);
            if (puerto.Length > 5 || port <= 0)
            {
                //Console.WriteLine("Ouerto no valido");
                return "Puerto no valido";
            }

            if (user.Length > 32 || pass.Length > 16)
            {
                //Console.WriteLine("usuario no valido");
                return "usuario no valido";
            }
           return Login(ip,puerto,user,pass);
        }

        public String Login(String ip,String port, String user,String pass)
        {

            String msj = "";
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
                //Console.WriteLine("Login Successful");
                msj = "Login Successful";
            }
            else
            {
                uint nErr = CHCNetSDK.NET_DVR_GetLastError();
                if (nErr == CHCNetSDK.NET_DVR_PASSWORD_ERROR)
                {
                   // Console.WriteLine("user name or password error!");
                    msj = "user name or password error!";
                    if (1 == struDeviceInfoV40.bySupportLock)
                    {
                        string strTemp1 = string.Format("Left {0} try opportunity", struDeviceInfoV40.byRetryLoginTime);
                        msj += strTemp1;
                        //Console.WriteLine(strTemp1);

                    }
                }
                else if (nErr == CHCNetSDK.NET_DVR_USER_LOCKED)
                {
                    if (1 == struDeviceInfoV40.bySupportLock)
                    {
                        string strTemp1 = string.Format("user is locked, the remaining lock time is {0}", struDeviceInfoV40.dwSurplusLockTime);
                        //Console.WriteLine(strTemp1);
                        msj = strTemp1;

                    }
                }
                else
                {
                    //Console.WriteLine("net error or dvr is busy!");
                    msj = "net error or dvr is busy!";
                }
            }

            return msj;
        }

        public String LoginFing(String ip, String port, String user, String pass)
        {
            String msj = "";
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
                //Login Successful
                msj = "Login Successful";
            }
            else
            {
                uint nErr = CHCNetSDK.NET_DVR_GetLastError();
                if (nErr == CHCNetSDK.NET_DVR_PASSWORD_ERROR)
                {
                    msj = "user name or password error! ";
                    if (1 == struDeviceInfoV40.bySupportLock)
                    {
                        string strTemp1 = string.Format("Left {0} try opportunity", struDeviceInfoV40.byRetryLoginTime);
                        msj+= strTemp1;
                    }
                }
                else if (nErr == CHCNetSDK.NET_DVR_USER_LOCKED)
                {
                    if (1 == struDeviceInfoV40.bySupportLock)
                    {
                        string strTemp1 = string.Format("user is locked, the remaining lock time is {0}", struDeviceInfoV40.dwSurplusLockTime);
                        msj = strTemp1;
                    }
                }
                else
                {
                    CHCNetSDK.NET_DVR_Logout_V30(m_UserID);
                    CHCNetSDK.NET_DVR_Cleanup();

                    msj = "dvr ocupado o no esta conectado";
                }
            }

            return msj;
        }

    

        public  async Task<String> DetectarDeviceAsync(String Brodcast,String puerto)
        {
            //var discoverer = new HikvisionDeviceDiscovery();
            var discoverer = new SearchDevices();

           String  Resultado = await discoverer.SearchDevicesNet();
            //if (Brodcast.Length > 1 && puerto.Length > 1)
            //{
            //    return discoverer.ScanNetworkForHikvisionDevices(Brodcast,puerto);
            //   return discoverer.ScanNetworkForHikvisionDevices(Brodcast, puerto);
            //}
            //else 
            //{
            //    string resultados = discoverer.ScanNetworkForHikvisionDevices(puerto);
            //    return resultados;
            //    //return discoverer.ScanNetworkForHikvisionDevices();
            //}
            return Resultado;
        }

       
        public String DetectarDevice2()
        {
            String msj = "";
            String xmlMessage = "<?xml version=\"1.0\" encoding=\"utf-8\"?><Probe><Uuid>13A888A9-F1B1-4020-AE9F-05607682D23B</Uuid><Types>inquiry</Types></Probe>";

            // Crear un cliente UDP

            XmlDocument xmlDoc = new XmlDocument();
            UdpClient udpClient = new UdpClient();
            List<string> receivedJsonList = new List<string>();
            HashSet<string> receivedJsonSet = new HashSet<string>();

            udpClient.EnableBroadcast = true;
            //IPEndPoint endPoint = new IPEndPoint(IPAddress.Broadcast, 37020);
            IPEndPoint endPoint = new IPEndPoint(IPAddress.Parse("239.255.255.250"), 37020); // Puerto SADP por defecto

            // Paquete SADP de búsqueda
            byte[] sadpMessage = Encoding.UTF8.GetBytes(xmlMessage); // Ejemplo de paquete de búsqueda
            bool keepReceiving = true;
            try
            {

                int error = 0;
                int repetidos = 0;
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
                        if (!receivedJsonSet.Contains(jsonText))
                        {
                            receivedJsonSet.Add(jsonText); // Agregar solo si no está presente
                            receivedJsonList.Add(jsonText);
                        }
                        else
                        {
                            repetidos++;
                        }
                        if (repetidos == 10)
                        {
                            keepReceiving = false;
                        }
                        // receivedJsonSet.Add(jsonText);
                        //  keepReceiving = false;
                        // Mostrar el JSON
                        //Console.WriteLine(jsonText);
                    }
                    catch (SocketException ex)
                    {
                        if (error >= 3)
                        {
                            string receivedXml = "<Error>No se recibieron datos en el tiempo de espera especificado.</Error>";
                            xmlDoc.LoadXml(receivedXml); // Cargar el XML en XmlDocument
                            string jsonText = JsonConvert.SerializeXmlNode(xmlDoc); // Convertir a JSON
                                                                                    //Console.WriteLine(jsonText);
                            receivedJsonList.Add(jsonText);
                            keepReceiving = false;
                        }
                        error++;
                    }
                }
            }
            finally
            {
                udpClient.Close();
            }

            string jsonText2 = JsonConvert.SerializeObject(receivedJsonList);

            return jsonText2;
            //Console.WriteLine(jsonText2);

            
        }
    }
}
