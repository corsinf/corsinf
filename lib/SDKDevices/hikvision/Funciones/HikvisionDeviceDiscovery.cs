using System;
using System.Collections.Generic;
using System.Linq;
using System.Net.Sockets;
using System.Net;
using System.Text;
using System.Threading.Tasks;
using System.Xml;
using Newtonsoft.Json;

namespace CorsinfSDKHik.Funciones
{
    public class HikvisionDeviceDiscovery
    {
        public String ScanNetworkForHikvisionDevices(String brodcast = "239.255.255.250",String puerto = "37020")
        {

            XmlDocument xmlDoc = new XmlDocument();
            List<string> receivedJsonList = new List<string>();
            HashSet<string> receivedJsonSet = new HashSet<string>();

            int port = int.Parse(puerto);
            using (UdpClient udpClient = new UdpClient())
            {
                try
                {
                    // Configurar la dirección de difusión
                    IPEndPoint broadcastEndPoint = new IPEndPoint(IPAddress.Parse(brodcast), port);

                    // Mensaje XML a enviar
                    string xmlMessage = "<?xml version=\"1.0\" encoding=\"utf-8\"?><Probe><Uuid>13A888A9-F1B1-4020-AE9F-05607682D23B</Uuid><Types>inquiry</Types></Probe>";
                    byte[] message = Encoding.UTF8.GetBytes(xmlMessage);

                    // Configurar el cliente UDP para permitir la difusión
                    udpClient.EnableBroadcast = true;

                    // Enviar el mensaje de difusión
                    udpClient.Send(message, message.Length, broadcastEndPoint);

                    // Establecer un tiempo de espera para recibir respuestas
                    udpClient.Client.ReceiveTimeout = 5000;

                   // Console.WriteLine("Buscando dispositivos Hikvision en la red...");

                    int repetidos = 0;
                    bool keepReceiving = true;

                    while (keepReceiving)
                    {
                        try
                        {
                            // Escuchar respuestas
                            IPEndPoint remoteEndPoint = new IPEndPoint(IPAddress.Any, 0);
                            byte[] data = udpClient.Receive(ref remoteEndPoint);

                            // Procesar los datos recibidos
                            string response = Encoding.UTF8.GetString(data);
                            xmlDoc.LoadXml(response); // Cargar el XML en XmlDocument
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
                            //Console.WriteLine($"Dispositivo encontrado: {response} en {remoteEndPoint.Address}");
                        }
                        catch (SocketException ex)
                        {
                            // Manejar el tiempo de espera o otros errores de socket
                            if (ex.SocketErrorCode == SocketError.TimedOut)
                            {
                                //Console.WriteLine("Búsqueda completada, no se encontraron más dispositivos.");
                                string receivedXml = "<Error>No se recibieron datos en el tiempo de espera especificado.</Error>";
                                xmlDoc.LoadXml(receivedXml); // Cargar el XML en XmlDocument
                                string jsonText = JsonConvert.SerializeXmlNode(xmlDoc); // Convertir a JSON
                                                                                        //Console.WriteLine(jsonText);
                                receivedJsonList.Add(jsonText);
                                keepReceiving = false;
                                //break;
                            }
                            else
                            {
                                //Console.WriteLine($"Error de socket: {ex.Message}");
                                string receivedXml = "<Error>$\"Error de socket: {ex.Message}\"</Error>";
                                xmlDoc.LoadXml(receivedXml); // Cargar el XML en XmlDocument
                                string jsonText = JsonConvert.SerializeXmlNode(xmlDoc); // Convertir a JSON
                                                                                        //Console.WriteLine(jsonText);
                                receivedJsonList.Add(jsonText);
                                keepReceiving = false;

                                //break;
                            }
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


                /*
                int port = int.Parse(puerto);   
                UdpClient udpClient = new UdpClient();
                try
                {
                    // Multicast address and port
                    IPEndPoint endPoint = new IPEndPoint(IPAddress.Parse(brodcast), port);

                    // XML message to send
                    string xmlMessage = "<?xml version=\"1.0\" encoding=\"utf-8\"?><Probe><Uuid>13A888A9-F1B1-4020-AE9F-05607682D23B</Uuid><Types>inquiry</Types></Probe>";
                    byte[] message = Encoding.UTF8.GetBytes(xmlMessage);

                    // Join multicast group
                    udpClient.JoinMulticastGroup(IPAddress.Parse(brodcast));

                    // Send the XML message
                    udpClient.Send(message, message.Length, endPoint);

                    // Set the timeout for receiving responses
                    udpClient.Client.ReceiveTimeout = 5000;

                    Console.WriteLine("Scanning for Hikvision devices...");
                    while (true)
                    {
                        try
                        {
                            // Listen for responses
                            IPEndPoint remoteEP = new IPEndPoint(IPAddress.Any, 0);
                            byte[] data = udpClient.Receive(ref remoteEP);

                            // Process the received data
                            string deviceInfo = Encoding.ASCII.GetString(data);
                            Console.WriteLine($"Device found: {deviceInfo} at {remoteEP.Address}");
                        }
                        catch (SocketException ex)
                        {
                            // Timeout or other socket exceptions
                            if (ex.SocketErrorCode == SocketError.TimedOut)
                            {
                                Console.WriteLine("Scan complete, no more devices found.");
                                break;
                            }
                            else
                            {
                                Console.WriteLine($"Socket error: {ex.Message}");
                                break;
                            }
                        }
                    }
                }
                finally
                {
                    udpClient.Close();
                }*/
            }

    }
}