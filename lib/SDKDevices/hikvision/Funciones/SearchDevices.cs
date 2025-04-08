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

namespace CorsinfSDKHik.Funciones
{
    public class SearchDevices
    {

        public HashSet<String> listaVlans()
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
                        lista.Add(unicastAddr.Address.ToString());
                    }
                }
            }
            return lista;
        }

        public async Task<string> SearchDevicesNet()
        {

            var results = new HashSet<string>();
            const string multicastIp = "239.255.255.250";
            const int port = 37020;
            const string xmlMessage = @"<?xml version=""1.0"" encoding=""utf-8""?>
                                  <Probe>
                                      <Uuid>13A888A9-F1B1-4020-AE9F-05607682D23B</Uuid>
                                      <Types>inquiry</Types>
                                  </Probe>";
            HashSet<string> vlanIps = listaVlans();
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

                    return $"Error desde {ipLocal}: {ex.Message}";
                    Console.WriteLine($"Error desde {ipLocal}: {ex.Message}");
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
                            responses.Add(JsonConvert.SerializeXmlNode(xmlDoc));
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
