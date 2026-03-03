using Lextm.SharpSnmpLib.Messaging;
using Lextm.SharpSnmpLib;
using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Net;
using System.Net.NetworkInformation;
using System.Net.Sockets;
using System.Text;
using System.Threading.Tasks;
using System.Xml;
using System.Net.NetworkInformation;
using Microsoft.IdentityModel.Tokens;

namespace CorsinfSDKHik.Funciones
{
    public class SearchDevices
    {
        private static HashSet<String> listaIps = new HashSet<String>();

        //public HashSet<String> GetArpTable()
        //{
        //    HashSet<String> lista = new HashSet<String>();
        //    foreach (NetworkInterface ni in NetworkInterface.GetAllNetworkInterfaces())
        //    {
        //        if (ni.OperationalStatus == OperationalStatus.Up &&
        //            ni.NetworkInterfaceType != NetworkInterfaceType.Loopback)
        //        {
        //            var props = ni.GetIPProperties();
        //            var gateway = props.GatewayAddresses;
        //            foreach (var gw in gateway)
        //            {
        //                //Console.WriteLine("Gateway encontrado: " + gw.Address);
        //                lista.Add(gw.Address.ToString());
        //                //ipVlans(gw.Address.ToString());
        //                GetVlansFromSwitch(gw.Address.ToString());
        //            }
        //        }
        //    }
        //    return lista;
        //}

        public static void GetVlansFromSwitch(String vlans)
        {
            // Rangos comunes de VLANs (ajusta según tu red)
            string[] vlanRanges = vlans.Split(",");
            //string[] vlanRanges = { "192.168.1", "192.168.10", "192.168.100", "10.0.0" };

            Parallel.ForEach(vlanRanges, vlan => {
                Parallel.For(1, 255, i => {
                    string ip = $"{vlan}.{i}";
                    if (IsHostActive(ip))
                    {
                        listaIps.Add(ip);
                    }
                });
            });
        }

        public static bool IsHostActive(string ip, int timeout = 100)
        {
            try
            {
                Ping ping = new Ping();
                PingReply reply = ping.Send(ip, timeout);
                return reply.Status == IPStatus.Success;
            }
            catch
            {
                return false;
            }
        }

        public void listaVlans()
        {           
            HashSet<String> lista = new HashSet<String>();
            var interfaces = NetworkInterface.GetAllNetworkInterfaces()
             .Where(n => n.OperationalStatus == OperationalStatus.Up &&
                        n.NetworkInterfaceType != NetworkInterfaceType.Loopback);
            foreach (var ni in interfaces)
            {
                foreach (var unicastAddr in ni.GetIPProperties().UnicastAddresses)
                {
                    if (unicastAddr.Address.AddressFamily == AddressFamily.InterNetwork &&
                        unicastAddr.IPv4Mask != null)
                    {
                        listaIps.Add(unicastAddr.Address.ToString());
                    }
                }
            }
        }

        public async Task<string> SearchDevicesNet(String vlans)
        {
            if (vlans.IsNullOrEmpty())
            {
                listaVlans();
            }
            else
            {
                GetVlansFromSwitch(vlans);
            }
            var results = new HashSet<string>();
            const string multicastIp = "239.255.255.250";
            const int port = 37020;
            const string xmlMessage = @"<?xml version=""1.0"" encoding=""utf-8""?>
                                  <Probe>
                                      <Uuid>13A888A9-F1B1-4020-AE9F-05607682D23B</Uuid>
                                      <Types>inquiry</Types>
                                  </Probe>";
            HashSet<string> vlanIps = listaIps;
            foreach (string ipLocal in vlanIps)
            {
                try
                {
                    using (UdpClient cliente = new UdpClient())
                    {
                        cliente.Client.Bind(new IPEndPoint(IPAddress.Parse(ipLocal), 0));
                        cliente.JoinMulticastGroup(IPAddress.Parse(multicastIp), IPAddress.Parse(ipLocal));

                        byte[] datos = Encoding.UTF8.GetBytes($"{xmlMessage} {ipLocal}");
                        IPEndPoint destino = new IPEndPoint(IPAddress.Parse(multicastIp), port);
                        results.UnionWith(await ScanEndpointAsync(datos,destino,port));
;
                    }
                }
                catch (Exception ex)
                {

                  //  return $"Error desde {ipLocal}: {ex.Message}";
                    //Console.WriteLine($"Error desde {ipLocal}: {ex.Message}");
                }
            }
            return JsonConvert.SerializeObject(results); ;

        }
      
        private async Task<HashSet<string>> ScanEndpointAsync(byte[] message, IPEndPoint endpoint, int port)
        {
            var responses = new HashSet<string>();
            using (var udpClient = new UdpClient(new IPEndPoint(IPAddress.Any, 0)))
            {
                try
                {
                    udpClient.EnableBroadcast = true;
                    endpoint.Port = port;

                    // Enviar solicitud
                    await udpClient.SendAsync(message, message.Length, endpoint);

                    // Recibir respuestas con timeout
                    var cancellationToken = new CancellationTokenSource(5000).Token;
                    while (!cancellationToken.IsCancellationRequested)
                    {
                        var result = await udpClient.ReceiveAsync()
                            .WithCancellation(cancellationToken);

                        try
                        {
                            var xmlDoc = new XmlDocument();
                            xmlDoc.LoadXml(Encoding.UTF8.GetString(result.Buffer));
                            XmlNode probeMatchNode = xmlDoc.SelectSingleNode("//ProbeMatch");

                            if (probeMatchNode != null)
                            {
                                string probeMatchXml = JsonConvert.SerializeXmlNode(probeMatchNode);
                                responses.Add(probeMatchXml); 
                            }                           
                        }
                        catch (XmlException) { /* Respuesta no XML válida */ }
                    }
                }
                catch (OperationCanceledException) { /* Timeout */ }
                catch (Exception ex)
                {
                    responses.Add($"{{\"error\":\"{endpoint.Address}:{port} - {ex.Message}\"}}");
                }
            }
            return responses;
        }

    }

    // Extensión para soportar cancellation en ReceiveAsync
    public static class SocketExtensions
    {
        public static async Task<UdpReceiveResult> WithCancellation(
            this Task<UdpReceiveResult> task,
            CancellationToken cancellationToken)
        {
            var tcs = new TaskCompletionSource<bool>();
            using (cancellationToken.Register(s => ((TaskCompletionSource<bool>)s).TrySetResult(true), tcs))
            {
                if (task != await Task.WhenAny(task, tcs.Task))
                    throw new OperationCanceledException(cancellationToken);
            }
            return await task;
        }
    }
}
