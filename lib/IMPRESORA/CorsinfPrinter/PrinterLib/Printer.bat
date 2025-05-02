@echo off
cd /d C:\PrinterLib

:: Verificar si Printlib.jar ya está en ejecución (por el título de la ventana)
wmic process where "name='javaw.exe'" get commandline | find "Printlib.jar" > nul
if %errorlevel% equ 0 (
    echo [%time%] El servidor Printlib YA está en ejecución.
    echo.
    pause
    exit /b
)

:: Verificar por puerto (opcional - descomenta si usas puerto fijo)
:: netstat -ano | find ":3000" > nul && (
::    echo [%time%] El puerto 3000 está en uso. Servidor posiblemente activo.
::    echo.
::    pause
::    exit /b
:: )

echo [%time%] Iniciando servidor Printlib...
start "" javaw -jar Printlib.jar > server.log 2>&1

echo [%time%] Servidor iniciado en segundo plano.
echo Verifica el log: C:\PrinterLib\server.log
echo.
pause