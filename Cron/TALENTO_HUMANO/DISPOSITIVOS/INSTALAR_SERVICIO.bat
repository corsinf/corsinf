@echo off
:: Debes ejecutar este .bat como Administrador
@REM set NSSM_PATH="C:\xampp\htdocs\corsinf\Cron\nssm\nssm.exe"
set NSSM_PATH="C:\Apache24\htdocs\php81\corsinf\Cron\nssm\nssm.exe"

echo Instalando Servicio CorsinfMonitor...

@REM %NSSM_PATH% install CorsinfMonitor "C:\Windows\System32\WindowsPowerShell\v1.0\powershell.exe" "-ExecutionPolicy Bypass -File ""C:\xampp\htdocs\corsinf\Cron\TALENTO_HUMANO\DISPOSITIVOS\levantar_dispositivos.ps1"""
%NSSM_PATH% install CorsinfMonitor "C:\Windows\System32\WindowsPowerShell\v1.0\powershell.exe" "-ExecutionPolicy Bypass -File ""C:\Apache24\htdocs\php81\corsinf\Cron\TALENTO_HUMANO\DISPOSITIVOS\levantar_dispositivos.ps1"""

:: Configurar el directorio de inicio
@REM %NSSM_PATH% set CorsinfMonitor AppDirectory "C:\xampp\htdocs\corsinf\Cron\TALENTO_HUMANO\DISPOSITIVOS"
%NSSM_PATH% set CorsinfMonitor AppDirectory "c:\Apache24\htdocs\php81\corsinf\Cron\TALENTO_HUMANO\DISPOSITIVOS"

:: Configurar descripción y auto-arranque
%NSSM_PATH% set CorsinfMonitor Description "Monitoreo automatico de SDK Devices para Talento Humano"
%NSSM_PATH% set CorsinfMonitor Start SERVICE_AUTO_START

echo Iniciando Servicio...
net start CorsinfMonitor

echo Proceso completado.
pause