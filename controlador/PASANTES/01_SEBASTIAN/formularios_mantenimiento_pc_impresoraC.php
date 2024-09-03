<?php
require_once(dirname(__DIR__, 3) . '/lib/pdf/fpdf.php');
//require_once(dirname(__DIR__, 3) . '/modelo/PASANTES/01_SEBASTIAN/formularios_mantenimiento_pc_impresoraM.php');

$controlador = new formularios_mantenimiento_pc_impresoraC();

if (isset($_GET['mantenimiento_preventivo'])) {
    echo $controlador->mantenimiento_preventivo($_GET['id']);
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
        $for_man_marca_pc = 'test';//$datos[0]['for_man_marca_pc'];
        $for_man_marca_monitor = 'test';//$datos[0]['for_man_marca_monitor'];
        $for_man_procesador = 'test';//$datos[0]['for_man_procesador'];
        $for_man_velocidad_ghz = 'test';//$datos['for_man_velocidad_ghz'];
        $for_man_serial = 'test';//$datos[0]['for_man_serial'];
        $for_man_teclado = 'test';//$datos[0]['for_man_teclado'];
        $for_man_placa = 'test';//$datos[0]['for_man_placa'];
        $for_man_velocidad_mb = 'test';//$datos[0]['for_man_velocidad_mb'];
        $for_man_serial_placa = 'test';//$datos[0]['for_man_serial_pl'];
        $for_man_mouse = 'test';//$datos[0]['for_man_mouse'];
        $for_man_memoria_ram = 'test';//$datos[0]['for_man_memoria_ram'];
        $for_man_capacidad_gb = 'test';//$datos[0]['for_man_capacidad_gb'];
        $for_man_serial_procesador = 'test';//$datos[0]['for_man_serial_procesador'];
        $for_man_cd_rom = 'test';//$datos[0]['for_man_cd_rom'];
        $for_man_marca_disco_duro = 'test';//$datos[0]['for_man_marca_disco'];
        $for_man_capacidad_mb = 'test';//$datos[0]['for_man_capacidad_mb'];
        $for_man_serial_ram = 'test';//$datos[0]['for_man_serial_ram'];
        $for_man_unidad_dvd = 'test';//$datos[0]['for_man_unidad_dvd'];
        $for_man_tarjeta_video = 'test';//$datos[0]['for_man_tarjeta_video'];
        $for_man_capacidad_gpu_gb = 'test';//$datos[0]['for_man_capacidad_gpu_gb'];
        $for_man_serial_hd = 'test';//$datos[0]['for_man_serial_hd'];
        $for_man_otros_dispositivos = 'test';//$datos[0]['for_man_otros_dispositivos_gpu'];
        $for_man_tarjeta_sonido = 'test';//$datos[0]['for_man_tarjeta_sonido'];
        $for_man_capacidad_mb_sonido = 'test';//$datos[0]['for_man_capacidad_mb_sonido'];
        $for_man_serial_video = 'test';//$datos[0]['for_man_serial_video'];
        $for_man_otros_dispositivos_sonido = 'test';//$datos[0]['for_man_otros_dispositivos_sonido'];

        //* Software
        $for_man_sistema_operativo = 'test'; //$datos[0]['for_man_sistema_operativo'];
        $for_man_antivirus = 'test';//$datos[0]['for_man_antivirus'];
        $for_man_paquete_office = 'test';//$datos[0]['for_man_paquete_office'];
        $for_man_multimedia = 'test';//$datos[0]['for_man_multimedia'];
        $for_man_internet = 'test';//$datos[0]['for_man_internet'];
        $for_man_otros = 'test';//$datos[0]['for_man_otros'];

        //? PC
        $for_man_enciende_pc = 'Si';//$datos[0]['for_man_enciende_pc'];
        $for_man_unidades_pc = 'Disco Flexible';//$datos[0]['for_man_unidades_pc'];
        $for_man_botones_pc = 'No';//$datos[0]['for_man_botones_pc'];
        $for_man_condicion_pc = 'test';//$datos[0]['for_man_condicion_pc'];
        $for_man_procesador_pc = 'test';//$datos[0]['for_man_procesador_pc'];
        $for_man_memoria_ram = 'test';//$datos[0]['for_man_memoria_ram'];
        $for_man_disco_duro_pc = 'test';//$datos[0]['for_man_disco_duro_pc'];

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
}
?>