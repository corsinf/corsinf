# CorsinfMonitor - Servicio de Monitoreo

### Rutas del Servidor
Tener en cuenta las rutas del servidor en el  archivo
[Archivo para editar](levantar_dispositivos.ps1)

```powershell
Script PS1: C:\xampp\htdocs\corsinf\Cron\levantar_dispositivos.ps1
```

Toca reemplzar
```powershell
$txtFile   = "C:\xampp\htdocs\corsinf\Cron\dispositivos_activos.txt"
$logFolder = "C:\xampp\htdocs\corsinf\logs\talento_humano\dispositivos"
```

Ejecutar el archivo en modo administrador
[Levantar dispositivo y editar](INSTALAR_SERVICIO.bat)

### Fragmento de código en caso de levantar desde local

```powershell
powershell.exe -ExecutionPolicy Bypass -File "C:\xampp\htdocs\corsinf\Cron\levantar_dispositivos.ps1"
```

# Administracion del Servicio

Nombre del Servicio: CorsinfMonitor

# Operaciones de control
```powershell
nssm status CorsinfMonitor
Start-Service CorsinfMonitor
Stop-Service CorsinfMonitor
Restart-Service CorsinfMonitor
```

# Eliminacion del servicio
```powershell
sc query "CorsinfMonitor"
net stop "CorsinfMonitor"
sc delete "CorsinfMonitor"
```

# Listar y finalizar procesos
```powershell
tasklist | findstr dotnet
taskkill /F /IM dotnet.exe
```

### Notas de Mantenimiento
Las rutas son obligatorias para el funcionamiento del servidor.

Cambios en la estructura de carpetas requieren actualizacion en .ps1 y .bat.

Ejecutar siempre con privilegios de Administrador.