// See https://aka.ms/new-console-template for more information
using CorsinfSDKHik.ConfigDB;
using CorsinfSDKHik.Funciones;
using CorsinfSDKHik.NetSDK;
using CorsinfSDKHik.SDKs;
using Microsoft.Data.SqlClient;
using Microsoft.Extensions.Configuration;
using Microsoft.IdentityModel.Tokens;
using System;
using System.Diagnostics;
using System.Net;
using System.Net.Sockets;
using System.Runtime;
using System.Text;
using System.Text.Json;
using System.Text.Json.Nodes;
using System.Xml.Linq;

class Program
{
  
    public static async Task Main(string[] args)
    {
        if (args.Length == 0)
        {
            Console.WriteLine("Presione cualquier tecla en 5 segundos para cancelar...");

            int timeoutMs = 5000;
            Stopwatch sw = Stopwatch.StartNew();

            while (sw.ElapsedMilliseconds < timeoutMs)
            {
                if (Console.KeyAvailable)
                {
                    Console.ReadKey(true);
                    InfoConfiguracion();
                }
                Thread.Sleep(100); // evita consumo excesivo de CPU
            }

            //Console.WriteLine("Tiempo agotado, continuando proceso...");
            EjecutarProceso();
        }
        else {
            if (args[0].ToString().ToUpper() == "I")
            {
                Console.WriteLine("configuracion");
            }
            else
            {
                //Console.WriteLine("Levantando Biometricos");
                levantarBiometricos();
                FuncionesConsola Comandos = new FuncionesConsola();
                Comandos.funcionesAsync(args);
            }

        }
    }

    static IConfigurationRoot buscarConfig()
    {

        var config = new ConfigurationBuilder()
            .SetBasePath(AppContext.BaseDirectory)
            .AddJsonFile(Path.Combine("ConfigDB", "appsettings.json"), optional: false, reloadOnChange: true)
            .Build();

        return config;
    }

    static void EjecutarProceso()
    {
        Console.WriteLine("Ejecutando proceso...");
        // lógica principal
    }


    static void InfoConfiguracion()
    {
        var config = buscarConfig();

        string server = config["Database:Server"];
        string port = config["Database:Port"];
        string db = config["Database:DataBase"];
        string user = config["Database:User"];
        string pass = config["Database:Password"];


        string IdEmpresa = config["IdEmpresa"];

        Console.WriteLine("=========================Configuracion detalle==============================");
        Console.WriteLine("server: "+server);
        Console.WriteLine("puerto:"+port);
        Console.WriteLine("base datos: "+db);
        Console.WriteLine("user: "+user);
        Console.WriteLine("pass: "+pass);
        Console.WriteLine("idEmpresa: " + IdEmpresa);
        Console.WriteLine("============================================================================");

        Console.WriteLine("Quiero cambiar la configuracion (y/n)");
        string confirmacion = Console.ReadLine();
        if (confirmacion == "y")
        {
            Console.Clear();
            Console.WriteLine("serve:");
            server = Console.ReadLine();
            Console.WriteLine("port:");
            port = Console.ReadLine();
            Console.WriteLine("base de datos:");
            db = Console.ReadLine();
            Console.WriteLine("user:");
            user = Console.ReadLine();
            Console.WriteLine("password:");
            pass = Console.ReadLine();

            EditarConfig(server,port,db,user,pass);


        }
        Console.WriteLine("Quiero cambiar el numero de empresa (y/n)");
        confirmacion = Console.ReadLine();
        if (confirmacion == "y")
        {
            Console.Clear();
            Console.WriteLine("IdEmpresa:");
            IdEmpresa = Console.ReadLine();
            EditarIdEmpresa(IdEmpresa);


        }
        else
        {
            return;
        }

        // lógica principal
    }

    static void EditarConfig(String server,String port,String db,String user,String pass)
    {
        string baseDir = AppDomain.CurrentDomain.BaseDirectory;
        string configDir = Path.Combine(baseDir, "ConfigDB");
        string file = Path.Combine(configDir, "appsettings.json");

        // Asegurar directorio
        Directory.CreateDirectory(configDir);

        // Leer o crear JSON
        JsonObject config;
        if (File.Exists(file))
        {
            string json = File.ReadAllText(file);
            config = JsonSerializer.Deserialize<JsonObject>(json) ?? new JsonObject();
        }
        else
        {
            config = new JsonObject();
        }

        // Asegurar estructura Database
        if (!config.ContainsKey("Database"))
        {
            config["Database"] = new JsonObject();
        }

        // Actualizar servidor
        if (!string.IsNullOrEmpty(server))
        {
            ((JsonObject)config["Database"])["Server"] = server;
        }
        if (!string.IsNullOrEmpty(port))
        {
            ((JsonObject)config["Database"])["Port"] = port;
        }
        if (!string.IsNullOrEmpty(db))
        {
            ((JsonObject)config["Database"])["Database"] = db;
        }
        if (!string.IsNullOrEmpty(user))
        {
            ((JsonObject)config["Database"])["User"] = user;
        }
        if (!string.IsNullOrEmpty(pass))
        {
            ((JsonObject)config["Database"])["Password"] = pass;
        }

        // Guardar con FORCE FLUSH
        string updatedJson = JsonSerializer.Serialize(config, new JsonSerializerOptions
        {
            WriteIndented = true
        });

        using (FileStream fs = new FileStream(file, FileMode.Create, FileAccess.Write, FileShare.None))
        using (StreamWriter sw = new StreamWriter(fs, Encoding.UTF8))
        {
            sw.Write(updatedJson);
            sw.Flush(); // Forzar escritura inmediata
        }

        Console.WriteLine("===========================================================");
        Console.WriteLine("**************** Configuracion guardada *******************");
        Console.WriteLine("===========================================================");
        ProbarConexion();
    }


    static void EditarIdEmpresa(String empresa)
    {
        string baseDir = AppDomain.CurrentDomain.BaseDirectory;
        string configDir = Path.Combine(baseDir, "ConfigDB");
        string file = Path.Combine(configDir, "appsettings.json");

        // Asegurar directorio
        Directory.CreateDirectory(configDir);

        // Leer o crear JSON
        JsonObject config;
        if (File.Exists(file))
        {
            string json = File.ReadAllText(file);
            config = JsonSerializer.Deserialize<JsonObject>(json) ?? new JsonObject();
        }
        else
        {
            config = new JsonObject();
        }



        // Actualizar servidor
        if (!string.IsNullOrEmpty(empresa))
        {
            config["IdEmpresa"] = empresa;
        }
        else {
            Console.WriteLine("Quiere guardar sin datos ? (y/n)");
            string confirmacion = Console.ReadLine();
            if (confirmacion == "y")
            {
                config["IdEmpresa"] = "";

            }
            else
            {
                return;
            }
        }

            // Guardar con FORCE FLUSH
            string updatedJson = JsonSerializer.Serialize(config, new JsonSerializerOptions
            {
                WriteIndented = true
            });

        using (FileStream fs = new FileStream(file, FileMode.Create, FileAccess.Write, FileShare.None))
        using (StreamWriter sw = new StreamWriter(fs, Encoding.UTF8))
        {
            sw.Write(updatedJson);
            sw.Flush(); // Forzar escritura inmediata
        }

        Console.WriteLine("===========================================================");
        Console.WriteLine("**************** Configuracion guardada *******************");
        Console.WriteLine("===========================================================");
        ProbarConexion();
    }


    static void ProbarConexion()
    {
        string[] args = [];
        dbConfig db = new dbConfig();
        if (db.conexion() != null)
        {
            Console.Clear();
            Console.WriteLine("===========================================================");
            Console.WriteLine("********************** Conexion exitosa *******************");
            Console.WriteLine("===========================================================");
            Main(args);
        }
        else
        {
            Console.WriteLine("===========================================================");
            Console.WriteLine("********** No se pudo conectar a la base de datos *********");
            Console.WriteLine("===========================================================");
            InfoConfiguracion(); 
        }

    }

    static void levantarBiometricos()
    {
        
    }

}

