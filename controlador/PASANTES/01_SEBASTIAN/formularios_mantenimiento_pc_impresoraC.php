<?php
require_once(dirname(__DIR__, 3) . '/lib/pdf/fpdf.php');
//require_once(dirname(__DIR__, 3) . '/modelo/PASANTES/01_SEBASTIAN/formularios_mantenimiento_pc_impresoraM.php');

$controlador = new formularios_mantenimiento_pc_impresoraC();

if (isset($_GET['mantenimiento_preventivo'])) {
    echo $controlador->mantenimiento_preventivo($_GET['id']);
}

if (isset($_GET['mantenimiento_impresora'])) {
    echo $controlador->mantenimiento_impresora($_GET['id']);
}

class formularios_mantenimiento_pc_impresoraC{
    private $modelo;

    function __construct()
    {
        //$this->modelo = new formularios_mantenimiento_pc_impresoraM();
    }

    function mantenimiento_preventivo($id){
        //////////////////////////////////////////////////////////////////////////////////////////////////////
        //* Datos del Cliente
        //$datos = $this->modelo->where('form_man_id', $id)->listar();
        $for_man_primer_nombre = 'Ruben';//$datos[0]['for_man_primer_nombre'];
        $for_man_segundo_nombre = 'Ruben';//$datos[0]['for_man_segundo_nombre'];
        $for_man_primer_apellido = 'Hola';//$datos[0]['for_man_primer_apellido'];
        $for_man_segundo_apellido = 'Hola';//$datos[0]['for_man_segundo_apellido'];
        $for_man_nombres_completos =  $for_man_primer_apellido . ' ' . $for_man_segundo_apellido  . ' ' . $for_man_primer_nombre . ' ' . $for_man_segundo_nombre;
        $for_man_empresa = 'Corsinf';//$datos[0]['for_man_empresa'];
        $for_man_telefono = '0991543264';//$datos[0]['for_man_telefono'];
        $for_man_correo = 'Ruben@corsinf.corsinf';//$datos[0]['for_man_correo'];
        $for_man_equipo = 'Adrian Equipo';//$datos[0]['for_man_equipo'];
        $for_man_tipo_equipo = 'Testeo';//$datos[0]['for_man_tipo_equipo'];
        $for_man_dia = '03';//$datos[0]['for_man_dia'];
        $for_man_mes = '10';//$datos[0]['for_man_mes'];
        $for_man_ano = '2024';//$datos[0]['for_man_ano'];

        //? Configuración del Hardware
        $for_man_marca_pc = 'GeForce Nvidia';//$datos[0]['for_man_marca_pc'];
        $for_man_marca_monitor = 'ENV2';//$datos[0]['for_man_marca_monitor'];
        $for_man_procesador = 'Intel i5 10400F ';//$datos[0]['for_man_procesador'];
        $for_man_velocidad_ghz = '3.20';//$datos['for_man_velocidad_ghz'];
        $for_man_serial = 'ABCD123456';//$datos[0]['for_man_serial'];
        $for_man_teclado = 'APEX';//$datos[0]['for_man_teclado'];
        $for_man_placa = 'B650M Wifi +';//$datos[0]['for_man_placa'];
        $for_man_velocidad_mb = '2000';//$datos[0]['for_man_velocidad_mb'];
        $for_man_serial_placa = 'ABCD654321';//$datos[0]['for_man_serial_pl'];
        $for_man_mouse = 'APEX';//$datos[0]['for_man_mouse'];
        $for_man_memoria_ram = 'Kingston Fury';//$datos[0]['for_man_memoria_ram'];
        $for_man_capacidad_gb = '8';//$datos[0]['for_man_capacidad_gb'];
        $for_man_serial_procesador = 'ABCD123456';//$datos[0]['for_man_serial_procesador'];
        $for_man_cd_rom = 'NO';//$datos[0]['for_man_cd_rom'];
        $for_man_marca_disco_duro = 'Kingston SSD';//$datos[0]['for_man_marca_disco'];
        $for_man_capacidad_mb = '150000';//$datos[0]['for_man_capacidad_mb'];
        $for_man_serial_ram = 'ABCD123456';//$datos[0]['for_man_serial_ram'];
        $for_man_unidad_dvd = 'SONY';//$datos[0]['for_man_unidad_dvd'];
        $for_man_tarjeta_video = 'RTX 3060';//$datos[0]['for_man_tarjeta_video'];
        $for_man_capacidad_gpu_gb = '4';//$datos[0]['for_man_capacidad_gpu_gb'];
        $for_man_serial_hd = 'ABCD654321';//$datos[0]['for_man_serial_hd'];
        $for_man_otros_dispositivos = 'NO';//$datos[0]['for_man_otros_dispositivos_gpu'];
        $for_man_tarjeta_sonido = 'Sonic Sony';//$datos[0]['for_man_tarjeta_sonido'];
        $for_man_capacidad_mb_sonido = '80000';//$datos[0]['for_man_capacidad_mb_sonido'];
        $for_man_serial_video = 'ABCD123456';//$datos[0]['for_man_serial_video'];
        $for_man_otros_dispositivos_sonido = 'NO';//$datos[0]['for_man_otros_dispositivos_sonido'];

        //* Software
        $for_man_sistema_operativo = 'Windows 11 Pro'; //$datos[0]['for_man_sistema_operativo'];
        $for_man_antivirus = 'Malwarebites';//$datos[0]['for_man_antivirus'];
        $for_man_paquete_office = 'Office 2021 +';//$datos[0]['for_man_paquete_office'];
        $for_man_multimedia = 'VLC Video';//$datos[0]['for_man_multimedia'];
        $for_man_internet = 'Netlife 750MB';//$datos[0]['for_man_internet'];
        $for_man_otros = 'No';//$datos[0]['for_man_otros'];

        //? PC
        $for_man_enciende_pc = 'Si';//$datos[0]['for_man_enciende_pc'];
        $for_man_unidades_pc = 'Disco Flexible';//$datos[0]['for_man_unidades_pc'];
        $for_man_botones_pc = 'No';//$datos[0]['for_man_botones_pc'];
        $for_man_condicion_pc = 'test';//$datos[0]['for_man_condicion_pc'];
        $for_man_disco_duro_pc = 'Kingston SSD';//$datos[0]['for_man_disco_duro_pc'];

        //* Pantalla
        $for_man_enciende_pantalla = 'Si';//$datos[0]['for_man_enciende_pantalla'];
        $for_man_colores_pantalla = 'Si';//$datos[0]['for_man_colores_pantalla'];
        $for_man_botones_pantalla = 'No';//$datos[0]['for_man_botones_pantalla'];
        $for_man_condicion_pantalla = 'test';//$datos[0]['for_man_condicion_pantalla'];

        //? Teclado
        $for_man_funciona_teclado = 'No';//$datos[0]['for_man_funciona_teclado'];
        $for_man_botones_teclado = 'Si';//$datos[0]['for_man_botones_teclado'];
        $for_man_condicion_teclado = 'test';//$datos[0]['for_man_condicion_teclado'];
        //////////////////////////////////////////////////////////////////////////////////////////////////////

        $pdf = new FPDF('P','mm','A4');
        $pdf->SetMargins(28, 15, 28);
        $pdf->SetAutoPageBreak(true, 15);
        $pdf->AddPage();

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(0, 7, utf8_decode('FORMULARIO DE MANTENIMIENTO PREVENTIVO RECEPCION DEL EQUIPO'), 1, 1, 'C');

        $pdf->SetFillColor(0, 0, 0);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(0, 7, utf8_decode('DATOS DEL CLIENTE'), 1, 1, 'C', 1);

        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(33, 7, utf8_decode('Nombre: '), 1, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(121, 7, utf8_decode($for_man_nombres_completos), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(33, 7, utf8_decode('Empresa: '), 1, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(121, 7, utf8_decode($for_man_empresa), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(33, 7, utf8_decode('Teléfono: '), 1, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(121, 7, utf8_decode($for_man_telefono), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(33, 7, utf8_decode('Correo Electrónico: '), 1, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(121, 7, utf8_decode($for_man_correo), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(33, 7, utf8_decode('Equipo N°: '), 1, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(121, 7, utf8_decode($for_man_equipo), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(33, 7, utf8_decode('Tipo de equipo: '), 1, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(121, 7, utf8_decode($for_man_tipo_equipo), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(33, 7, utf8_decode('Fecha'), 1, 0, 'L');
        $pdf->SetFillColor(201, 200, 199);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(10, 7, utf8_decode('Dia'), 1, 0, 'C', 1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(33, 7, utf8_decode($for_man_dia), 1, 0, 'L');

        $pdf->SetFillColor(201, 200, 199);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(10, 7, utf8_decode('Mes'), 1, 0, 'C', 1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(33, 7, utf8_decode($for_man_mes), 1, 0, 'L');

        $pdf->SetFillColor(201, 200, 199);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(10, 7, utf8_decode('Año'), 1, 0, 'C', 1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(25, 7, utf8_decode($for_man_ano), 1, 1, 'L');

        $pdf->SetFillColor(0, 0, 0);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(0, 7, utf8_decode('CONFIGURACIÓN DE HARDWARE'), 1, 1, 'C', 1);

        $pdf->SetFillColor(201, 200, 199);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(32, 14, utf8_decode('Marca PC'), 1, 0, 'L', 1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(89, 14, utf8_decode($for_man_marca_pc), 1, 0, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(23, 14, utf8_decode('Marca monitor'), 1, 0, 'L', 1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(10, 14, utf8_decode($for_man_marca_monitor), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(32, 7, utf8_decode('Procesador'), 1, 0, 'L', 1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(24, 7, utf8_decode($for_man_procesador), 1, 0, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(25, 7, utf8_decode('Velocidad GHZ'), 1, 0, 'L', 1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(12, 7, utf8_decode($for_man_velocidad_ghz), 1, 0, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(11, 7, utf8_decode('Serial'), 1, 0, 'L', 1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(17, 7, utf8_decode($for_man_serial), 1, 0, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(23, 7, utf8_decode('Teclado'), 1, 0, 'L', 1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(10, 7, utf8_decode($for_man_teclado), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(32, 7, utf8_decode('Placa'), 1, 0, 'L', 1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(24, 7, utf8_decode($for_man_placa), 1, 0, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(25, 7, utf8_decode('Velocidad MB'), 1, 0, 'L', 1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(12, 7, utf8_decode($for_man_velocidad_mb), 1, 0, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(11, 7, utf8_decode('Serial'), 1, 0, 'L', 1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(17, 7, utf8_decode($for_man_serial_placa), 1, 0, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(23, 7, utf8_decode('Mouse'), 1, 0, 'L', 1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(10, 7, utf8_decode($for_man_mouse), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(32, 7, utf8_decode('Memoria RAM'), 1, 0, 'L', 1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(24, 7, utf8_decode($for_man_memoria_ram), 1, 0, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(25, 7, utf8_decode('Capacidad GB'), 1, 0, 'L', 1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(12, 7, utf8_decode($for_man_capacidad_gb), 1, 0, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(11, 7, utf8_decode('Serial'), 1, 0, 'L', 1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(17, 7, utf8_decode($for_man_serial_procesador), 1, 0, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(23, 7, utf8_decode('CD ROM'), 1, 0, 'L', 1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(10, 7, utf8_decode($for_man_cd_rom), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(32, 7, utf8_decode('Marca Disco Duro'), 1, 0, 'L', 1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(24, 7, utf8_decode($for_man_marca_disco_duro), 1, 0, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(25, 7, utf8_decode('Capacidad MB'), 1, 0, 'L', 1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(12, 7, utf8_decode($for_man_capacidad_mb), 1, 0, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(11, 7, utf8_decode('Serial'), 1, 0, 'L', 1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(17, 7, utf8_decode($for_man_serial_ram), 1, 0, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(23, 7, utf8_decode('Unidad DVD'), 1, 0, 'L', 1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(10, 7, utf8_decode($for_man_unidad_dvd), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(32, 7, utf8_decode('Tarjeta de video'), 1, 0, 'L', 1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(24, 7, utf8_decode($for_man_tarjeta_video), 1, 0, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(25, 7, utf8_decode('Capacidad GB'), 1, 0, 'L', 1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(12, 7, utf8_decode($for_man_capacidad_gpu_gb), 1, 0, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(11, 7, utf8_decode('Serial'), 1, 0, 'L', 1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(17, 7, utf8_decode($for_man_serial_hd), 1, 0, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(23, 7, utf8_decode('Otros devices'), 1, 0, 'L', 1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(10, 7, utf8_decode($for_man_otros_dispositivos), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(32, 7, utf8_decode('Tarjeta de sonido'), 1, 0, 'L', 1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(24, 7, utf8_decode($for_man_tarjeta_sonido), 1, 0, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(25, 7, utf8_decode('Capacidad MB'), 1, 0, 'L', 1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(12, 7, utf8_decode($for_man_capacidad_mb_sonido), 1, 0, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(11, 7, utf8_decode('Serial'), 1, 0, 'L', 1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(17, 7, utf8_decode($for_man_serial_video), 1, 0, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(23, 7, utf8_decode('Otros devices'), 1, 0, 'L', 1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(10, 7, utf8_decode($for_man_otros_dispositivos_sonido), 1, 1, 'L');

        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(0, 7, utf8_decode('Software'), 1, 1, 'C', 1);

        $pdf->SetFillColor(201, 200, 199);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(32, 7, utf8_decode('Sistema Operativo'), 1, 0, 'C', 1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(49, 7, utf8_decode($for_man_sistema_operativo), 1, 0, 'C');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(25, 7, utf8_decode('Antivirus'), 1, 0, 'C', 1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(48, 7, utf8_decode($for_man_antivirus), 1, 1, 'C');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(32, 7, utf8_decode('Paquete de Office'), 1, 0, 'C', 1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(49, 7, utf8_decode($for_man_paquete_office), 1, 0, 'C');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(25, 7, utf8_decode('Multimedia'), 1, 0, 'C', 1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(48, 7, utf8_decode($for_man_multimedia), 1, 1, 'C');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(32, 7, utf8_decode('Internet'), 1, 0, 'C', 1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(49, 7, utf8_decode($for_man_internet), 1, 0, 'C');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(25, 7, utf8_decode('Otros'), 1, 0, 'C', 1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(48, 7, utf8_decode($for_man_otros), 1, 1, 'C');

        $pdf->SetFillColor(0, 0, 0);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(0, 7, utf8_decode('PC'), 1, 1, 'C', 1);

        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(49.5, 7, utf8_decode('Enciende'), 1, 0, 'C', 1);
        $pdf->Cell(46.5, 7, utf8_decode('Unidades'), 1, 0, 'C', 1);
        $pdf->Cell(58, 7, utf8_decode('¿Botones Completos?'), 1, 1, 'C', 1);
        
        $pdf->Cell(24.75, 7, utf8_decode('Si'), 1, 0, 'C', 1);
        $pdf->Cell(24.75, 7, utf8_decode('No'), 1, 0, 'C', 1);

        $pdf->Cell(24.5, 7, utf8_decode('Disco Flexible'), 1, 0, 'C', 1);
        $pdf->Cell(22, 7, utf8_decode('CD/DVD'), 1, 0, 'C', 1);

        $pdf->Cell(29, 7, utf8_decode('Si'), 1, 0, 'C', 1);
        $pdf->Cell(29, 7, utf8_decode('No'), 1, 1, 'C', 1);

        if ($for_man_enciende_pc == 'Si'){
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(24.75, 7, utf8_decode('X'), 1, 0, 'C', 1);
            $pdf->Cell(24.75, 7, utf8_decode(''), 1, 0, 'C', 1);
        } else {
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(24.75, 7, utf8_decode(''), 1, 0, 'C', 1);
            $pdf->Cell(24.75, 7, utf8_decode('X'), 1, 0, 'C', 1);
        }

        if ($for_man_unidades_pc == 'Disco Flexible'){
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(24.5, 7, utf8_decode('X'), 1, 0, 'C', 1);
            $pdf->Cell(22, 7, utf8_decode(''), 1, 0, 'C', 1);
        } else {
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(24.5, 7, utf8_decode(''), 1, 0, 'C', 1);
            $pdf->Cell(22, 7, utf8_decode('X'), 1, 0, 'C', 1);
        }

        if ($for_man_botones_pc == 'Si'){
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(29, 7, utf8_decode('X'), 1, 0, 'C', 1);
            $pdf->Cell(29, 7, utf8_decode(''), 1, 1, 'C', 1);
        } else {
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(29, 7, utf8_decode(''), 1, 0, 'C', 1);
            $pdf->Cell(29, 7, utf8_decode('X'), 1, 1, 'C', 1);
        }

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(49.5, 7, utf8_decode('Procesador'), 1, 0, 'C', 1);
        $pdf->Cell(46.5, 7, utf8_decode('Memoria RAM'), 1, 0, 'C', 1);
        $pdf->Cell(58, 7, utf8_decode('Disco Duro'), 1, 1, 'C', 1);

        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(49.5, 7, utf8_decode($for_man_procesador), 1, 0, 'C', 1);
        $pdf->Cell(46.5, 7, utf8_decode($for_man_memoria_ram), 1, 0, 'C', 1);
        $pdf->Cell(58, 7, utf8_decode($for_man_disco_duro_pc), 1, 1, 'C', 1);

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(0, 7, utf8_decode('Condición Física'), 1, 1, 'C', 1);
        if (strlen($for_man_condicion_pc) > 100 & strlen($for_man_condicion_pc) < 201 ){ 
            $pdf->SetFont('Arial', '', 9);
            $pdf->MultiCell(0, 14, utf8_decode($for_man_condicion_pc), 1, 'C', 1, 1);  
        } else {
            if (strlen($for_man_condicion_pc) < 101){
                $pdf->SetFont('Arial', '', 9);
                $pdf->MultiCell(0, 7, utf8_decode($for_man_condicion_pc), 1, 'C', 1, 1);  
            }
            if (strlen($for_man_condicion_pc) > 200 & strlen($for_man_condicion_pc) < 301 ){
                $pdf->SetFont('Arial', '', 9);
                $pdf->MultiCell(0, 21, utf8_decode($for_man_condicion_pc), 1, 'C', 1, 1);
            }
        }

        $pdf->AddPage();
        $pdf->SetFillColor(0, 0, 0);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(0, 7, utf8_decode('PANTALLA'), 1, 1, 'C', 1);

        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(49.5, 7, utf8_decode('Enciende'), 1, 0, 'C', 1);
        $pdf->Cell(46.5, 7, utf8_decode('¿Colores correctos?'), 1, 0, 'C', 1);
        $pdf->Cell(58, 7, utf8_decode('¿Botones Completos?'), 1, 1, 'C', 1);

        $pdf->Cell(24.75, 7, utf8_decode('Si'), 1, 0, 'C', 1);
        $pdf->Cell(24.75, 7, utf8_decode('No'), 1, 0, 'C', 1);

        $pdf->Cell(24.5, 7, utf8_decode('Si'), 1, 0, 'C', 1);
        $pdf->Cell(22, 7, utf8_decode('No'), 1, 0, 'C', 1);

        $pdf->Cell(29, 7, utf8_decode('Si'), 1, 0, 'C', 1);
        $pdf->Cell(29, 7, utf8_decode('No'), 1, 1, 'C', 1);

        if ($for_man_enciende_pantalla == 'Si'){
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(24.75, 7, utf8_decode('X'), 1, 0, 'C', 1);
            $pdf->Cell(24.75, 7, utf8_decode(''), 1, 0, 'C', 1);
        } else {
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(24.75, 7, utf8_decode(''), 1, 0, 'C', 1);
            $pdf->Cell(24.75, 7, utf8_decode('X'), 1, 0, 'C', 1);
        }

        if ($for_man_colores_pantalla == 'Si'){
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(24.5, 7, utf8_decode('X'), 1, 0, 'C', 1);
            $pdf->Cell(22, 7, utf8_decode(''), 1, 0, 'C', 1);
        } else {
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(24.5, 7, utf8_decode(''), 1, 0, 'C', 1);
            $pdf->Cell(22, 7, utf8_decode('X'), 1, 0, 'C', 1);
        }

        if ($for_man_botones_pantalla == 'Si'){
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(29, 7, utf8_decode('X'), 1, 0, 'C', 1);
            $pdf->Cell(29, 7, utf8_decode(''), 1, 1, 'C', 1);
        } else {
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(29, 7, utf8_decode(''), 1, 0, 'C', 1);
            $pdf->Cell(29, 7, utf8_decode('X'), 1, 1, 'C', 1);

        }

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(0, 7, utf8_decode('Condición Física'), 1, 1, 'C', 1);
        if (strlen($for_man_condicion_pantalla) > 100 & strlen($for_man_condicion_pantalla) < 201 ){ 
            $pdf->SetFont('Arial', '', 9);
            $pdf->MultiCell(0, 14, utf8_decode($for_man_condicion_pantalla), 1, 'C', 1, 1);
        } else {
            if (strlen($for_man_condicion_pantalla) < 101){
                $pdf->SetFont('Arial', '', 9);
                $pdf->MultiCell(0, 7, utf8_decode($for_man_condicion_pantalla), 1, 'C', 1, 1);
            }
            if (strlen($for_man_condicion_pantalla) > 200 & strlen($for_man_condicion_pantalla) < 301 ){
                $pdf->SetFont('Arial', '', 9);
                $pdf->MultiCell(0, 21, utf8_decode($for_man_condicion_pantalla), 1, 'C', 1, 1);
            }
        }

        $pdf->SetFillColor(0, 0, 0);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(0, 7, utf8_decode('TECLADO'), 1, 1, 'C', 1);

        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(77, 7, utf8_decode('¿FUNCIONA CORRECTAMENTE?'), 1, 0, 'C', 1);
        $pdf->Cell(77, 7, utf8_decode('¿Botones Completos?'), 1, 1, 'C', 1);

        $pdf->Cell(38.5, 7, utf8_decode('Si'), 1, 0, 'C', 1);
        $pdf->Cell(38.5, 7, utf8_decode('No'), 1, 0, 'C', 1);

        $pdf->Cell(38.5, 7, utf8_decode('Si'), 1, 0, 'C', 1);
        $pdf->Cell(38.5, 7, utf8_decode('No'), 1, 1, 'C', 1);

        if ($for_man_funciona_teclado == 'Si'){
            $pdf->Cell(38.5, 7, utf8_decode('X'), 1, 0, 'C', 1);
            $pdf->Cell(38.5, 7, utf8_decode(''), 1, 0, 'C', 1);
        } else {
            $pdf->Cell(38.5, 7, utf8_decode(''), 1, 0, 'C', 1);
            $pdf->Cell(38.5, 7, utf8_decode('X'), 1, 0, 'C', 1);
        }

        if ($for_man_botones_teclado == 'Si'){
            $pdf->Cell(38.5, 7, utf8_decode('X'), 1, 0, 'C', 1);
            $pdf->Cell(38.5, 7, utf8_decode(''), 1, 1, 'C', 1);
        } else {
            $pdf->Cell(38.5, 7, utf8_decode(''), 1, 0, 'C', 1);
            $pdf->Cell(38.5, 7, utf8_decode('X'), 1, 1, 'C', 1);
        }

        $pdf->Cell(0, 7, utf8_decode('Condición Física'), 1, 1, 'C', 1);
        if (strlen($for_man_condicion_teclado) > 100 & strlen($for_man_condicion_teclado) < 201 ){ 
            $pdf->SetFont('Arial', '', 9);
            $pdf->MultiCell(0, 14, utf8_decode($for_man_condicion_teclado), 1, 'C', 1, 1);
        } else {
            if (strlen($for_man_condicion_teclado) < 101){
                $pdf->SetFont('Arial', '', 9);
                $pdf->MultiCell(0, 7, utf8_decode($for_man_condicion_teclado), 1, 'C', 1, 1);
            }
            if (strlen($for_man_condicion_teclado) > 200 & strlen($for_man_condicion_teclado) < 301 ){
                $pdf->SetFont('Arial', '', 9);
                $pdf->MultiCell(0, 21, utf8_decode($for_man_condicion_teclado), 1, 'C', 1, 1);
            }
        }

        $pdf->Output();
    }

    function mantenimiento_impresora($id){ 
        //////////////////////////////////////////////////////////////////////////////////////////////////////
        //$datos = $this->modelo->where('form_man_id', $id)->listar();
        //* Datos de la empresa
        $for_imp_empresa = 'Corsinf';//$datos[0]->for_imp_empresa;
        $for_imp_consecutivo = 'CONSECUTIVO';//$datos[0]->for_imp_consecutivo;
        $for_imp_fecha = '05/09/2024';//$datos[0]->for_imp_fecha;
        $for_imp_sede = 'SEDE';//$datos[0]->for_imp_sede;
        $for_imp_dependencia = 'DEPENDENCIA';//$datos[0]->for_imp_dependencia;
        $for_imp_telefono = 'TELEFONO';//$datos[0]->for_imp_telefono;
        $for_imp_usuario = 'USUARIO';//$datos[0]->for_imp_usuario;
        $for_imp_email_usuario = 'GSMARQUEZ@puce.edu.ec';//$datos[0]->for_imp_email_usuario;

        //? Datos del equipo
        $for_imp_impresora = 'Si';//$datos[0]->for_imp_impresora;
        $for_imp_ploter = 'Si';//$datos[0]->for_imp_marca;
        $for_imp_marca = 'MARCA';//$datos[0]->for_imp_marca;
        $for_imp_procesador = 'Si';//$datos[0]->for_imp_modelo;
        $for_imp_serial = 'SERIAL';//$datos[0]->for_imp_serial;
        $for_imp_modelo = 'MODELO';//$datos[0]->for_imp_modelo;
        $for_imp_placa = 'PLACA';//$datos[0]->for_imp_placa;
        $for_imp_memoria = 'Si';//$datos[0]->for_imp_memoria;
        $for_imp_duplex = 'Si';//$datos[0]->for_imp_duplex;
        $for_imp_disco_duro = 'Si';//$datos[0]->for_imp_disco_duro;
        $for_imp_toner = 'Si';//$datos[0]->for_imp_toner;

        //* Inspección inicial
        $for_imp_danos_externos = 'No';//$datos[0]->for_imp_danos_externos;
        $for_imp_danos_externos_detalle = 'DAÑOS EXTERNOS';//$datos[0]->for_imp_danos_externos_detalle;
        $for_imp_encendido = 'Si';//$datos[0]->for_imp_encendido;
        $for_imp_hoja_prueba = 'Si';//$datos[0]->for_imp_hoja_prueba;
        $for_imp_cantidad_hojas_impresas = '10 hojas';//$datos[0]->for_imp_cantidad_hojas_impresas;
        $for_imp_verificacion_funcionamiento_red = 'No';//$datos[0]->for_imp_verificacion_funcionamiento_red;

        //? Etapas de mantenimiento
        $for_imp_verificar_funcionamiento = 'Si';//$datos[0]->for_imp_verificar_funcionamiento;
        $for_imp_limpiar_interna_externa = 'No';//$datos[0]->for_imp_limpiar_interna_externa;
        $for_imp_desconectar = 'Si';//$datos[0]->for_imp´_desconectar;
        $for_imp_limpiar_perifericos = 'No';//$datos[0]->for_imp_limpiar_perifericos;
        $for_imp_destapar_maquina = 'Si';//$datos[0]->for_imp_destapar_maquina;
        $for_imp_limpiar_cables = 'No';//$datos[0]->for_imp_limpiar_cables;
        $for_imp_soplado = 'Si';//$datos[0]->for_imp_soplado;
        $for_imp_pruebas_funcionamiento = 'No';//$datos[0]->for_imp_pruebas_funcionamiento;
        $for_imp_verificar_conexiones = 'Si';//$datos[0]->for_imp_verificar_conexiones;
        $for_imp_entregar_equipo = 'No';//$datos[0]->for_imp_entregar_equipo;

        //* Observaciones
        $for_imp_observaciones = 'La impresora fue entregada con exito';//$datos[0]->for_imp_observaciones;
        $for_imp_satisfaccion = 'Si';//$datos[0]->for_imp_satisfaccion;

        //? Firmas
        $for_imp_nombre_usuario = 'USUARIO';//$datos[0]->for_imp_firma_usuario;
        $for_imp_nombre_contratista = 'TECNICO';//$datos[0]->for_imp_firma_contratista;
        $for_imp_nombre_udistrital = 'UDISTRITAL';//$datos[0]->for_imp_firma_udistrital;
        $for_imp_cedula_usuario = '1734567890';//$datos[0]->for_imp_cedula_usuario;
        $for_imp_cedula_contratista = '1234567890';//$datos[0]->for_imp_cedula_contratista;
        $for_imp_cedula_udistrital = '9876543210';//$datos[0]->for_imp_cedula_udistrital;

        //* Checkboxes
        function CheckBox($pdf, $x, $y, $checked = false)
        {
            $pdf->SetDrawColor(0);
            $pdf->Rect($x, $y, 3, 3);
            if ($checked) {
                $pdf->Line($x, $y, $x + 3, $y + 3);
                $pdf->Line($x, $y + 3, $x + 3, $y);
            }
        }
        //////////////////////////////////////////////////////////////////////////////////////////////////////

        $pdf = new FPDF('P','mm','A4');
        $pdf->SetMargins(28, 15, 28);
        $pdf->SetAutoPageBreak(true, 15);
        $pdf->AddPage();

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(40, 14, utf8_decode('EMPRESA'), 1, 0,'C');
        $pdf->Cell(120, 14 , utf8_decode('MANTENIMIENTO PREVENTIVO PARA IMPRESORAS Y PLOTERS'), 1, 1, 'C');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(40, 6, utf8_decode($for_imp_empresa), 1, 0, 'C');
        $pdf->Ln(5);

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(40, 5, utf8_decode(''), 0, 0,'C');
        $pdf->Cell(40, 5, utf8_decode(''), 0, 0,'C');
        $pdf->Cell(20, 5, utf8_decode(''), 0, 0,'C');
        $pdf->Cell(30, 5, utf8_decode('Consecutivo:'), 1, 0,'C');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(27, 5, utf8_decode($for_imp_consecutivo), 1, 0,'C');
        $pdf->Ln(10);

        $pdf->SetFillColor(192, 192, 192);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(160, 5, utf8_decode('1. IDENTIFICACIÓN'), 1, 1,'C', 1);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(26.665, 5, utf8_decode(' Fecha:'), 1, 0,'L', 1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(26.665, 5, utf8_decode($for_imp_fecha), 1, 0,'L', 1);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(26.665, 5, utf8_decode(' Sede:'), 1, 0,'L', 1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(26.665, 5, utf8_decode($for_imp_sede), 1, 0,'L', 1);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(26.665, 5, utf8_decode(' Dependencia:'), 1, 0,'L', 1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(26.665, 5, utf8_decode($for_imp_dependencia), 1, 1,'L', 1);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(26.665, 5, utf8_decode(' Teléfono:'), 1, 0,'L', 1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(26.665, 5, utf8_decode($for_imp_telefono), 1, 0,'L', 1);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(26.665, 5, utf8_decode(' Usuario:'), 1, 0,'L', 1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(26.665, 5, utf8_decode($for_imp_usuario), 1, 0,'L', 1);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(26.665, 5, utf8_decode(' Email usuario:'), 1, 0,'L', 1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->MultiCell(26.665, 5, utf8_decode($for_imp_email_usuario), 1, 1,'L', 1);
        $pdf->Ln(5);

        $pdf->SetFillColor(192, 192, 192);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(160, 5, utf8_decode('2. INFORMACIÓN DEL EQUIPO'), 1, 1,'C', 1);

        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(26.665, 5, utf8_decode(' Impresora:'), 1, 0,'L', 1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(53.335, 5, utf8_decode($for_imp_impresora), 1, 0,'L', 1);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(35, 5, utf8_decode(' Ploter:'), 1, 0,'L', 1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(45, 5, utf8_decode($for_imp_ploter), 1, 1,'L', 1);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(26.665, 5, utf8_decode(' Marca:'), 1, 0,'L', 1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(26.665, 5, utf8_decode($for_imp_marca), 1, 0,'L', 1);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(26.665, 5, utf8_decode(' Procesador:'), 1, 0,'L', 1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(26.665, 5, utf8_decode($for_imp_procesador), 1, 0,'L', 1);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(26.665, 5, utf8_decode(' Serial:'), 1, 0,'L', 1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(26.665, 5, utf8_decode($for_imp_serial), 1, 1,'L', 1);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(26.665, 5, utf8_decode(' Modelo:'), 1, 0,'L', 1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(53.335, 5, utf8_decode($for_imp_modelo), 1, 0,'L', 1);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(35, 5, utf8_decode(' Placa de Inventario:'), 1, 0,'L', 1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(45, 5, utf8_decode($for_imp_placa), 1, 1,'L', 1);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(26.665, 5, utf8_decode(' Memoria:'), 1, 0,'L', 1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(53.335, 5, utf8_decode($for_imp_memoria), 1, 0,'L', 1);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(35, 5, utf8_decode(' Duplex:'), 1, 0,'L', 1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(45, 5, utf8_decode($for_imp_duplex), 1, 1,'L', 1);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(26.665, 5, utf8_decode(' Disco Duro:'), 1, 0,'L', 1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(53.335, 5, utf8_decode($for_imp_disco_duro), 1, 0,'L', 1);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(35, 5, utf8_decode(' Toner:'), 1, 0,'L', 1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(45, 5, utf8_decode($for_imp_toner), 1, 1,'L', 1);
        $pdf->Ln(5);

        $pdf->SetFillColor(192, 192, 192);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(160, 5, utf8_decode('3. INSPECCIÓN INICIAL'), 1, 1,'C', 1);

        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(80, 5, utf8_decode(' Se observan daños externos:'), 1, 0,'L', 1);
        $pdf->Cell(20, 5, utf8_decode('SI'), 1, 0,'C', 1);
        if ($for_imp_danos_externos == 'Si'){
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(20, 5, utf8_decode('X'), 1, 0,'C', 1);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, utf8_decode('NO'), 1, 0,'C', 1);
            $pdf->Cell(20, 5, utf8_decode(''), 1, 1,'C', 1);
            $pdf->SetFillColor(192, 192, 192);
            $pdf->Cell(160, 5, utf8_decode('Cual daño externo observas?'), 1, 1,'C', 1);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->SetFont('Arial', '', 9);
            $pdf->MultiCell(160, 5, utf8_decode($for_imp_danos_externos_detalle), 1, 'C', 1, 1);
        } else {
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, utf8_decode(''), 1, 0,'C', 1);
            $pdf->Cell(20, 5, utf8_decode('NO'), 1, 0,'C', 1);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(20, 5, utf8_decode('X'), 1, 1,'C', 1);
        }
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(80, 5, utf8_decode(' Verificación de encendido:'), 1, 0,'L', 1);
        if ($for_imp_encendido == 'Si'){
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, utf8_decode('SI'), 1, 0,'C', 1);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(20, 5, utf8_decode('X'), 1, 0,'C', 1);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, utf8_decode('NO'), 1, 0,'C', 1);
            $pdf->Cell(20, 5, utf8_decode(''), 1, 1,'C', 1);
        } else {
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, utf8_decode('SI'), 1, 0,'C', 1);
            $pdf->Cell(20, 5, utf8_decode(''), 1, 0,'C', 1);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, utf8_decode('NO'), 1, 0,'C', 1);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(20, 5, utf8_decode('X'), 1, 1,'C', 1);
            $pdf->Cell(20, 5, utf8_decode('SI'), 1, 0,'C', 1);
        }
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(80, 5, utf8_decode(' Hoja de impresión de prueba:'), 1, 0,'L', 1);
        if ($for_imp_hoja_prueba == 'Si'){
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, utf8_decode('SI'), 1, 0,'C', 1);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(20, 5, utf8_decode('X'), 1, 0,'C', 1);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, utf8_decode('NO'), 1, 0,'C', 1);
            $pdf->Cell(20, 5, utf8_decode(''), 1, 1,'C', 1);
        } else {
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, utf8_decode('SI'), 1, 0,'C', 1);
            $pdf->Cell(20, 5, utf8_decode(''), 1, 0,'C', 1);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, utf8_decode('NO'), 1, 0,'C', 1);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(20, 5, utf8_decode('X'), 1, 1,'C', 1);
        }
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(80, 5, utf8_decode(' Cantidad de hojas impresas:'), 1, 0,'L', 1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(80, 5, utf8_decode($for_imp_cantidad_hojas_impresas), 1, 1,'C', 1);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(80, 5, utf8_decode(' Verificación de funcionamiento en red:'), 1, 0,'L', 1);
        if ($for_imp_verificacion_funcionamiento_red == 'Si'){
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, utf8_decode('SI'), 1, 0,'C', 1);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(20, 5, utf8_decode('X'), 1, 0,'C', 1);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, utf8_decode('NO'), 1, 0,'C', 1);
            $pdf->Cell(20, 5, utf8_decode(''), 1, 1,'C', 1);
        } else {
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, utf8_decode('SI'), 1, 0,'C', 1);
            $pdf->Cell(20, 5, utf8_decode(''), 1, 0,'C', 1);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, utf8_decode('NO'), 1, 0,'C', 1);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(20, 5, utf8_decode('X'), 1, 1,'C', 1);
        }
        $pdf->Ln(5);

        $pdf->SetFillColor(192, 192, 192);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(160, 5, utf8_decode('4. ETAPAS DE MANTENIMIENTO'), 1, 1,'C', 1);

        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(80, 5, utf8_decode(' 1. Recibir maquina y verificar su funcionamiento:'), 1, 0,'L', 1);
        if ($for_imp_verificar_funcionamiento == 'Si'){
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, utf8_decode('SI'), 1, 0,'C', 1);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(20, 5, utf8_decode('X'), 1, 0,'C', 1);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, utf8_decode('NO'), 1, 0,'C', 1);
            $pdf->Cell(20, 5, utf8_decode(''), 1, 1,'C', 1);
        } else {
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, utf8_decode('SI'), 1, 0,'C', 1);
            $pdf->Cell(20, 5, utf8_decode(''), 1, 0,'C', 1);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, utf8_decode('NO'), 1, 0,'C', 1);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(20, 5, utf8_decode('X'), 1, 1,'C', 1);
        }
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(80, 5, utf8_decode(' 2. Desconectar las máquinas:'), 1, 0,'L', 1);
        if ($for_imp_desconectar == 'Si'){
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, utf8_decode('SI'), 1, 0,'C', 1);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(20, 5, utf8_decode('X'), 1, 0,'C', 1);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, utf8_decode('NO'), 1, 0,'C', 1);
            $pdf->Cell(20, 5, utf8_decode(''), 1, 1,'C', 1);
        } else {
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, utf8_decode('SI'), 1, 0,'C', 1);
            $pdf->Cell(20, 5, utf8_decode(''), 1, 0,'C', 1);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, utf8_decode('NO'), 1, 0,'C', 1);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(20, 5, utf8_decode('X'), 1, 1,'C', 1);
        }
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(80, 5, utf8_decode(' 3. Destapar y verificar máquina:'), 1, 0,'L', 1);
        if ($for_imp_destapar_maquina == 'Si'){
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, utf8_decode('SI'), 1, 0,'C', 1);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(20, 5, utf8_decode('X'), 1, 0,'C', 1);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, utf8_decode('NO'), 1, 0,'C', 1);
            $pdf->Cell(20, 5, utf8_decode(''), 1, 1,'C', 1);
        } else {
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, utf8_decode('SI'), 1, 0,'C', 1);
            $pdf->Cell(20, 5, utf8_decode(''), 1, 0,'C', 1);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, utf8_decode('NO'), 1, 0,'C', 1);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(20, 5, utf8_decode('X'), 1, 1,'C', 1);
        }
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(80, 5, utf8_decode(' 4. Llevar al equipo a un lugar de soplado:'), 1, 0,'L', 1);
        if ($for_imp_soplado == 'Si'){
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, utf8_decode('SI'), 1, 0,'C', 1);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(20, 5, utf8_decode('X'), 1, 0,'C', 1);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, utf8_decode('NO'), 1, 0,'C', 1);
            $pdf->Cell(20, 5, utf8_decode(''), 1, 1,'C', 1);
        } else {
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, utf8_decode('SI'), 1, 0,'C', 1);
            $pdf->Cell(20, 5, utf8_decode(''), 1, 0,'C', 1);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, utf8_decode('NO'), 1, 0,'C', 1);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(20, 5, utf8_decode('X'), 1, 1,'C', 1);
        }
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(80, 5, utf8_decode(' 5. Verificar conexiones y limpiar tarjetas:'), 1, 0,'L', 1);
        if ($for_imp_verificar_conexiones == 'Si'){
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, utf8_decode('SI'), 1, 0,'C', 1);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(20, 5, utf8_decode('X'), 1, 0,'C', 1);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, utf8_decode('NO'), 1, 0,'C', 1);
            $pdf->Cell(20, 5, utf8_decode(''), 1, 1,'C', 1);
        } else {
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, utf8_decode('SI'), 1, 0,'C', 1);
            $pdf->Cell(20, 5, utf8_decode(''), 1, 0,'C', 1);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, utf8_decode('NO'), 1, 0,'C', 1);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(20, 5, utf8_decode('X'), 1, 1,'C', 1);
        }
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(80, 5, utf8_decode(' 6. Limpiar interna y externa de toda la máquina'), 1, 0,'L', 1);
        if ($for_imp_limpiar_interna_externa == 'Si'){
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, utf8_decode('SI'), 1, 0,'C', 1);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(20, 5, utf8_decode('X'), 1, 0,'C', 1);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, utf8_decode('NO'), 1, 0,'C', 1);
            $pdf->Cell(20, 5, utf8_decode(''), 1, 1,'C', 1);
        } else {
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, utf8_decode('SI'), 1, 0,'C', 1);
            $pdf->Cell(20, 5, utf8_decode(''), 1, 0,'C', 1);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, utf8_decode('NO'), 1, 0,'C', 1);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(20, 5, utf8_decode('X'), 1, 1,'C', 1);
        }
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(80, 5, utf8_decode(' 7. Limpiar periféricos:'), 1, 0,'L', 1);
        if ($for_imp_limpiar_perifericos == 'Si'){
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, utf8_decode('SI'), 1, 0,'C', 1);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(20, 5, utf8_decode('X'), 1, 0,'C', 1);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, utf8_decode('NO'), 1, 0,'C', 1);
            $pdf->Cell(20, 5, utf8_decode(''), 1, 1,'C', 1);
        } else {
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, utf8_decode('SI'), 1, 0,'C', 1);
            $pdf->Cell(20, 5, utf8_decode(''), 1, 0,'C', 1);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, utf8_decode('NO'), 1, 0,'C', 1);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(20, 5, utf8_decode('X'), 1, 1,'C', 1);
        }
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(80, 5, utf8_decode(' 8. Limpieza de cables'), 1, 0,'L', 1);
        if ($for_imp_limpiar_cables == 'Si'){
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, utf8_decode('SI'), 1, 0,'C', 1);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(20, 5, utf8_decode('X'), 1, 0,'C', 1);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, utf8_decode('NO'), 1, 0,'C', 1);
            $pdf->Cell(20, 5, utf8_decode(''), 1, 1,'C', 1);
        } else {
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, utf8_decode('SI'), 1, 0,'C', 1);
            $pdf->Cell(20, 5, utf8_decode(''), 1, 0,'C', 1);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, utf8_decode('NO'), 1, 0,'C', 1);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(20, 5, utf8_decode('X'), 1, 1,'C', 1);
        }
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(80, 5, utf8_decode(' 9. Pruebas de funcionamiento:'), 1, 0,'L', 1);
        if ($for_imp_pruebas_funcionamiento == 'Si'){
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, utf8_decode('SI'), 1, 0,'C', 1);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(20, 5, utf8_decode('X'), 1, 0,'C', 1);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, utf8_decode('NO'), 1, 0,'C', 1);
            $pdf->Cell(20, 5, utf8_decode(''), 1, 1,'C', 1);
        } else {
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, utf8_decode('SI'), 1, 0,'C', 1);
            $pdf->Cell(20, 5, utf8_decode(''), 1, 0,'C', 1);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, utf8_decode('NO'), 1, 0,'C', 1);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(20, 5, utf8_decode('X'), 1, 1,'C', 1);
        }
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(80, 5, utf8_decode(' 10. Entrega de la máquina al usuario:'), 1, 0,'L', 1);
        if ($for_imp_entregar_equipo == 'Si'){
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, utf8_decode('SI'), 1, 0,'C', 1);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(20, 5, utf8_decode('X'), 1, 0,'C', 1);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, utf8_decode('NO'), 1, 0,'C', 1);
            $pdf->Cell(20, 5, utf8_decode(''), 1, 1,'C', 1);
        } else {
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, utf8_decode('SI'), 1, 0,'C', 1);
            $pdf->Cell(20, 5, utf8_decode(''), 1, 0,'C', 1);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, utf8_decode('NO'), 1, 0,'C', 1);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(20, 5, utf8_decode('X'), 1, 1,'C', 1);
        }
        $pdf->Ln(5);

        $pdf->SetFillColor(192, 192, 192);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(160, 5, utf8_decode('5. OBSERVACIONES'), 1, 1,'C', 1);

        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetFont('Arial', '', 9);
        if (strlen($for_imp_observaciones) > 100 & strlen($for_imp_observaciones) < 201) {
            $pdf->MultiCell(160, 9, utf8_decode($for_imp_observaciones), 1, 'C', 1, 1);
        } else {
            if (strlen($for_imp_observaciones) < 101){
                $pdf->SetFont('Arial', '', 9);
                $pdf->MultiCell(160, 7, utf8_decode($for_imp_observaciones), 1, 'C', 1, 1);
            }
            if (strlen($for_imp_observaciones) > 200 & strlen($for_imp_observaciones) < 301 ){
                $pdf->SetFont('Arial', '', 9);
                $pdf->MultiCell(160, 10, utf8_decode($for_imp_observaciones), 1, 'C', 1, 1);
            }
        }
        $pdf->Ln(5);

        $pdf->Cell(35, 5, utf8_decode('Se recibe a satisfacción:'), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(0, 5, utf8_decode($for_imp_satisfaccion), 0, 1, 'L');
        $pdf->Ln(15);

        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(53.33, 5, utf8_decode('____________________________'), 0, 0, 'L');
        $pdf->Cell(53.33, 5, utf8_decode('____________________________'), 0, 0, 'L');
        $pdf->Cell(53.33, 5, utf8_decode('____________________________'), 0, 1, 'L');

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(53.33, 5, utf8_decode('FIRMA DEL USUARIO DEL EQUIPO'), 0, 0, 'L');
        $pdf->Cell(53.33, 5, utf8_decode('FIRMA FUNCIONARIO CONTRATISTA'), 0, 0, 'L');
        $pdf->Cell(53.33, 5, utf8_decode('FIRMA FUNCIONARIO UDISTRITAL'), 0, 1, 'L');

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(15, 5, utf8_decode('NOMBRE:'), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(38.33, 5, utf8_decode($for_imp_nombre_usuario), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(15, 5, utf8_decode('NOMBRE:'), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(38.33, 5, utf8_decode($for_imp_nombre_contratista), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(15, 5, utf8_decode('NOMBRE:'), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(38.33, 5, utf8_decode($for_imp_nombre_udistrital), 0, 1, 'L');

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(15, 5, utf8_decode('CEDULA:'), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(38.33, 5, utf8_decode($for_imp_cedula_usuario), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(15, 5, utf8_decode('CEDULA:'), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(38.33, 5, utf8_decode($for_imp_cedula_contratista), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(15, 5, utf8_decode('CEDULA:'), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(38.33, 5, utf8_decode($for_imp_cedula_udistrital), 0, 1, 'L');
        $pdf->Ln(5);

        $pdf->Cell(0, 5, utf8_decode('Nota: el consecutivo y el numeral CUATRO será llenado por el funcionario SUPERVISOR'), 0, 0, 'L');

    
        $pdf->Output();
    }
}
?>