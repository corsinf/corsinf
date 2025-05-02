Tener disponible el puerto 300 es por donde se va a comunicar

1.- Descomprimir
2.- colocar la carpeta en C:
3.- 
=============================================
javaw -jar socket-server.jar > server.log 2>&1
======================================
=   Ver si el proceso esta arriba    =
=   tasklist | findstr java          =
======================================
para matar el proceso
taskkill /PID <PID> /F   

remplazar <PID> por el numero de la lista

netstat -ano | findstr ":3000"