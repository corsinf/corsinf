


<?PHP

function imprimirPDF()
{

    function crearCampo($pdf, $etiqueta, $x, $y, $valor = '')
    {
        // Color azul cielo SOLO para la etiqueta
        $pdf->SetFillColor(173, 216, 230);

        // Etiqueta con fondo de color
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->SetXY($x, $y);
        $pdf->MultiCell(40, 5, $etiqueta . ':', 0, 0, 'L', true); // Se activa el fondo solo aquí

        // Restablecer fondo blanco para el valor
        $pdf->SetFillColor(255, 255, 255);

        // Valor con borde negro
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetXY($x + 42, $y);  // Ajustamos la posición para dar un padding-right más pequeño
        $pdf->MultiCell(50, 5, $valor, 1, 0, 'L', false); // Aquí se agrega un borde negro (1)

        // Ajuste de la posición Y para el siguiente campo, se añade un pequeño espacio (padding-bottom)
        $y += 12; // Espacio ajustado para el padding-bottom (puedes modificar este valor si necesitas más o menos espacio)
    }

    function crearCampoAncho($pdf, $etiqueta, $y, $valor = '')
    {
        $pdf->SetFillColor(173, 216, 230);

        // Etiqueta con fondo de color
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->SetXY(12, $y);
        $pdf->MultiCell(40, 5, $etiqueta . ':', 0, 0, 'L', true); // Se activa el fondo solo aquí

        // Restablecer fondo blanco para el valor
        $pdf->SetFillColor(255, 255, 255);

        // Valor con borde negro y centrado dentro de su celda
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetXY(54, $y); // Posición X justo después de la etiqueta
        $pdf->MultiCell(143, 5, $valor, 1, 'C', false); // Ahora el valor se extiende hasta el final de la segunda columna

        // Ajuste de la posición Y para el siguiente campo
        return $y + 6; // Espacio ajustado para la siguiente línea
    }

    function crearCampoMovilizacion($pdf, $etiqueta, $x, $y, $valor, $col_width, $row_height)
    {
        // Color azul cielo SOLO para la etiqueta
        $pdf->SetFillColor(173, 216, 230);

        // Etiqueta con fondo de color
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->SetXY($x, $y);
        $pdf->MultiCell(18, $row_height, $etiqueta . ':', 0, 0, 'L', true); // Fondo azul SOLO en la etiqueta

        // Restablecer fondo blanco para el valor
        $pdf->SetFillColor(255, 255, 255);

        // Valor sin fondo
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetXY($x + 22, $y);  // Reducimos un poco el padding-right (ajustando la posición X)
        $pdf->MultiCell($col_width, $row_height, $valor, 1, 0, 'L', false); // Sin fondo

        // Ajuste de la posición Y para el siguiente campo (espacio añadido para padding-bottom)
        $y += $row_height + 2;  // Añadimos un poco de espacio para padding-bottom (puedes ajustar el valor si es necesario)
    }


    $datos = [];

    $clase = $datos[0]['clase'] ?? '';
    $nombres = $datos[0]['nombres'] ?? '';
    $apellidos = $datos[0]['apellidos'] ?? '';
    $cedula = $datos[0]['cedula'] ?? '';
    $codigo_dactilar = $datos[0]['codigo_dactilar'] ?? '';
    $fecha_nacimiento = $datos[0]['fecha_nacimiento'] ?? '';
    $estado_civil = $datos[0]['estado_civil'] ?? '';
    $nacionalidad = $datos[0]['nacionalidad'] ?? '';
    $tipo_visa = $datos[0]['tipo_visa'] ?? '';
    $numero_visa = $datos[0]['numero_visa'] ?? '';
    $fecha_vencimiento_visa = $datos[0]['fecha_vencimiento_visa'] ?? '';
    $provincia = $datos[0]['provincia'] ?? '';
    $canton = $datos[0]['canton'] ?? '';
    $parroquia = $datos[0]['parroquia'] ?? '';
    $origen_indigena = $datos[0]['origen_indigena'] ?? '';
    $sexo = $datos[0]['sexo'] ?? '';
    $identidad_genero = $datos[0]['identidad_genero'] ?? '';
    $orientacion = $datos[0]['orientacion'] ?? '';
    $tipo_discapacidad = $datos[0]['tipo_discapacidad'] ?? '';
    $etnia = $datos[0]['etnia'] ?? '';


    // Datos segunda sección
    $calle_principal = $datos[0]['calle_principal'] ?? '';
    $calle_secundaria = $datos[0]['calle_secundaria'] ?? '';
    $numero_vivienda = $datos[0]['numero_vivienda'] ?? '';
    $tipo_vivienda = $datos[0]['tipo_vivienda'] ?? '';
    $ocupacion_hogar = $datos[0]['ocupacion_hogar'] ?? '';

    $canton = $datos[0]['canton'] ?? '';
    $parroquia = $datos[0]['parroquia'] ?? '';
    $zona_sector_barrio = $datos[0]['zona_sector_barrio'] ?? '';
    $referencia = $datos[0]['referencia'] ?? '';
    $telefono_domicilio = $datos[0]['telefono_domicilio'] ?? '';

    $numero_piso_vivienda = $datos[0]['numero_piso_vivienda'] ?? '';
    $telefono_celular = $datos[0]['telefono_celular'] ?? '';
    $correo_electronico = $datos[0]['correo_electronico'] ?? '';
    $clase = $datos[0]['clase'] ?? '';  // Variable adicional

    // Datos de emergencia
    $nombres_apellidos_1 = $datos[0]['nombres_apellidos_1'] ?? '';
    $parentesco_1 = $datos[0]['parentesco_1'] ?? '';
    $telefono_domicilio_1 = $datos[0]['telefono_domicilio_1'] ?? '';
    $telefono_celular_1 = $datos[0]['telefono_celular_1'] ?? '';

    $nombres_apellidos_2 = $datos[0]['nombres_apellidos_2'] ?? '';
    $parentesco_2 = $datos[0]['parentesco_2'] ?? '';
    $telefono_domicilio_2 = $datos[0]['telefono_domicilio_2'] ?? '';
    $telefono_celular_2 = $datos[0]['telefono_celular_2'] ?? '';


    // datos familiares 
    $datos_familiares = [
        ['Gerardo Luis Santilla Torres', 'Hijo/a', '20/6/2010', '0984975811', 'Estudiante', '026197815'],
        ['Soledad Eduarda Torres Tobar', 'Esposo/a', '15/8/1987', '1547896755', 'Arquitecto', '023684915'],
        ['Maria Luisa Santilla Torres', 'Hijo/a', '26/9/2016', '0786948755', 'Estudiante', '023541975']
    ];

    // informacion de cargas familiares

    $datos_cargas = [
        ['Maria Luisa Santilla Torres', 'Hijo/a', 'Sí', 'Enfermedad Rara o Huérfana', 'Ninguna', '0%', 'SI'],
        ['Gerardo Luis Santilla Torres', 'Hijo/a', 'Sí', 'Cédula Ciudadanía EC', 'Física', '50%', 'SI']
    ];

    // referencias no familiares 

    $datos_referencias = [
        ['Gonzalo Eduardo Fajardo Ruiz', 'Amigo/a', '55', 'Empleado Privado (Arca S.A.)', 'Calle Maldonado y Calle Rioverde', '0998457895'],
        ['Lourdes Kerly Vivas Vilema', 'Amigo/a', '45', 'Empleado Privado (FastFood)', 'Calle 12 de octubre y Madrid', '0987124897']
    ];

    // educacion academica

    $formaciones_academicas = [
        [
            'nivel_instruccion' => 'Tercer Nivel',
            'titulo_obtenido' => 'Ingeniería en Sistemas Computacionales',
            'unidad_educativa' => 'Universidad Politécnica',
            'pais' => 'Ecuador',
            'cuarto_nivel' => 'NO',
            'registro_senecyt' => '123456',
            'motivo' => 'Abandonado',
            'fecha_inicio' => '01/01/2012',
            'fecha_fin' => '31/12/2015'
        ],
        [
            'nivel_instruccion' => 'Cuarto Nivel',
            'titulo_obtenido' => 'Máster en Administración de Empresas',
            'unidad_educativa' => 'Universidad Internacional',
            'pais' => 'EE.UU.',
            'cuarto_nivel' => 'SÍ',
            'registro_senecyt' => '654321',
            'motivo' => 'Culminado',
            'fecha_inicio' => '01/01/2016',
            'fecha_fin' => '31/12/2018'
        ],
        [
            'nivel_instruccion' => 'Tercer Nivel',
            'titulo_obtenido' => 'Licenciatura en Ciencias de la Educación',
            'unidad_educativa' => 'Universidad Nacional',
            'pais' => 'Ecuador',
            'cuarto_nivel' => 'NO',
            'registro_senecyt' => '789123',
            'motivo' => 'Culminado',
            'fecha_inicio' => '01/01/2008',
            'fecha_fin' => '31/12/2012'
        ]
    ];

    // conocimientos lengua extranjera

    $idiomas = [
        ['Inglés', 'Cambridge', 'Cambridge Institute', 'B2'],
        ['Francés', 'DELF', 'Alliance Française', 'B1'],
        ['Alemán', 'Goethe-Zertifikat', 'Goethe-Institut', 'A2']
    ];

    // datos de conocimiento 

    $datos_conocimientos = [
        [
            'paquetes_utilitarios' => 'Office: 64%',
            'base_de_datos' => 'SQL Server',
            'herramientas_graficas' => 'Adobe, Pixpa, Affinity',
            'otros_conocimientos' => 'Python',
            'registro_profesional_1' => 'Medicina',
            'numero_o_codigo_1' => '1784275994',
            'registro_profesional_2' => 'Contabilidad',
            'numero_o_codigo_2' => 'SAINT DOMINIC SCHOOL',
            'idiomas' => 'Inglés, Español', // Ejemplo adicional
            'habilidades_tecnicas' => 'Desarrollo web, Diseño gráfico' // Otro ejemplo adicional
        ],
        [
            'paquetes_utilitarios' => 'Office: 64%',
            'base_de_datos' => 'SQL Server',
            'herramientas_graficas' => 'Adobe, Pixpa, Affinity',
            'otros_conocimientos' => 'Python',
            'registro_profesional_1' => 'Medicina',
            'numero_o_codigo_1' => '1784275994',
            'registro_profesional_2' => 'Contabilidad',
            'numero_o_codigo_2' => 'SAINT DOMINIC SCHOOL',
            'idiomas' => 'Inglés, Español', // Ejemplo adicional
            'habilidades_tecnicas' => 'Desarrollo web, Diseño gráfico' // Otro ejemplo adicional
        ],
    ];


    // experiencia laborar

    $datos_experiencia = [
        [
            'institucion_empresa' => 'SAINT DOMINIC SCHOOL',
            'cargo_puesto' => 'DOCENTE',
            'motivo_salida' => 'ACTUAL',
            'fecha_ingreso' => '16 de junio de 2014',
            'tiempo_laborado' => 'Año/Mes/Día',
            'sector_empresarial' => 'Privado',
            'ultima_remuneracion' => '700,00 - 900,00',
            'fecha_salida' => '',
            'figura_legal' => 'Contrato Indefinido',
            'telefono_empresa' => '022648444 / 0998457895',
            'nombre_jefe_inmediato' => 'Alberto Zamora',
        ],
        [
            'institucion_empresa' => 'EMPRESA XYZ',
            'cargo_puesto' => 'GERENTE DE VENTAS',
            'motivo_salida' => 'Finalizado contrato',
            'fecha_ingreso' => '01 de marzo de 2015',
            'tiempo_laborado' => '2 años, 6 meses',
            'sector_empresarial' => 'Privado',
            'ultima_remuneracion' => '1,200,00',
            'fecha_salida' => '30 de agosto de 2017',
            'figura_legal' => 'Contrato Temporal',
            'telefono_empresa' => '022648888 / 0998451122',
            'nombre_jefe_inmediato' => 'Carlos Pérez',
        ]
    ];

    //informacion de eventos de capacitación 
    $datos_eventos = [
        [
            'nombre_evento' => 'Curso de Programación PHP',
            'tipo_evento' => 'Taller',
            'duracion_horas' => '40 horas',
            'institucion_auspiciante' => 'Universidad Técnica',
            'tipo_certificado' => 'Certificado de Participación',
            'fecha_inicio' => '01 de enero de 2023',
            'pais' => 'Ecuador',
            'fecha_fin' => '30 de enero de 2023'
        ],
        [
            'nombre_evento' => 'Diplomado en Desarrollo Web',
            'tipo_evento' => 'Diplomado',
            'duracion_horas' => '120 horas',
            'institucion_auspiciante' => 'Universidad de Tecnología',
            'tipo_certificado' => 'Diploma de Especialización',
            'fecha_inicio' => '15 de marzo de 2023',
            'pais' => 'Perú',
            'fecha_fin' => '15 de julio de 2023'
        ]
    ];

    // datos bancarios 
    $institucion_financiera = $datos[0]['institucion_financiera'] ?? 'Produbanco';
    $tipo_cuenta = $datos[0]['tipo_cuenta'] ?? 'Ahorros';
    $numero_cuenta = $datos[0]['numero_cuenta'] ?? '1208562700';

    //datos vehiculo 
    $vehiculo = $datos[0]['vehiculo'] ?? '';
    $propietario = $datos[0]['propietario'] ?? '';
    $telefono = $datos[0]['telefono'] ?? '';

    $clase = $datos[0]['clase'] ?? '';
    $tipo = $datos[0]['tipo'] ?? '';
    $placa = $datos[0]['placa'] ?? '';

    $marca = $datos[0]['marca'] ?? '';
    $modelo = $datos[0]['modelo'] ?? '';
    $ano = $datos[0]['ano'] ?? '';

    $color1 = $datos[0]['color1'] ?? '';
    $color2 = $datos[0]['color2'] ?? '';
    $licencia = $datos[0]['licencia'] ?? '';

    //habitos personales
    $deportes_que_practica = $datos[0]['deportes_que_practica'] ?? '';
    $pasatiempos_favoritos = $datos[0]['pasatiempos_favoritos'] ?? '';
    $consumos_nocivos = $datos[0]['consumos_nocivos'] ?? '';
    $seguro_vida_privado = $datos[0]['seguro_vida_privado'] ?? '';

    $asistencia_psicologica = $datos[0]['asistencia_psicologica'] ?? '';
    $grupo_sanguineo = $datos[0]['grupo_sanguineo'] ?? '';
    $enfermedades = $datos[0]['enfermedades'] ?? '';
    $religion = $datos[0]['religion'] ?? '';

    // informacion complementario
    $conocimiento_oferta = $datos[0]['conocimiento_oferta'] ?? '';
    $integra_agrupaciones = $datos[0]['integra_agrupaciones'] ?? '';
    $trabajo_conyugue = $datos[0]['trabajo_conyugue'] ?? '';
    $detalle_agrupacion = $datos[0]['detalle_agrupacion'] ?? '';

    $valor_ingresos_mensuales = $datos[0]['valor_ingresos_mensuales'] ?? '';
    $cargo_cp = $datos[0]['cargo_cp'] ?? '';
    $integro_grupos_laborales = $datos[0]['integro_grupos_laborales'] ?? '';
    $remuneracion_cp = $datos[0]['remuneracion_cp'] ?? '';
    $parientes_en_institucion = $datos[0]['parientes_en_institucion'] ?? '';

    $total_ingresos = $datos[0]['total_ingresos'] ?? '';



    try {
        // Asegúrate de que TCPDF esté incluido correctamente
        // require_once('tcpdf_include.php');

        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator('TCPDF');
        $pdf->SetAuthor('Autor del Formulario');
        $pdf->SetTitle('Formulario de Datos Personales');
        $pdf->SetSubject('Formulario TCPDF');

        // Remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Set margins
        $pdf->SetMargins(10, 20, 10);

        // Add a page
        $pdf->AddPage();

        // Primero crear el encabezado del formulario (título)
        $pdf->SetFont('helvetica', 'B', 12);

        $pdf->SetFillColor(242, 246, 250); // #f2f6fa

        // Create header row
        $pdf->Cell(0, 15, '', 0, 1, 'L', true);
        $pdf->setY($pdf->getY() - 15); // Move back to start of header

        // Add logo placeholder
        $pdf->Cell(40, 15, '', 0, 0, 'L', false);
        // Or use Image: $pdf->Image('ruta/al/logo.png', $pdf->GetX() - 38, $pdf->GetY() + 1, 20);

        // Add title (centered)
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->SetTextColor(0, 51, 102); // #003366
        $pdf->Cell(120, 7, 'FORMULARIO DE DATOS', 0, 0, 'C', false);
        $pdf->SetXY($pdf->GetX() - 120, $pdf->GetY() + 7);
        $pdf->Cell(120, 8, 'PERSONALES Y PROFESIONALES', 0, 0, 'C', false);

        // Add metadata (right aligned)
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetTextColor(51, 51, 51); // #333333
        $pdf->SetXY(170, $pdf->GetY() - 7);
        $pdf->Cell(20, 5, 'Código: GD-GTH-PR-001', 0, 0, 'R', false);
        $pdf->SetXY(170, $pdf->GetY() + 5);
        $pdf->Cell(20, 5, 'Versión: 1.0', 0, 0, 'R', false);
        $pdf->SetXY(170, $pdf->GetY() + 5);
        $pdf->Cell(20, 5, 'Página: 1 de 4', 0, 0, 'R', false);

        // Reset position for next content
        $pdf->SetY($pdf->GetY() + 5);

        // Add horizontal line
        $pdf->SetDrawColor(0, 51, 102); // #003366
        $pdf->SetLineWidth(0.5);
        $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
        $pdf->Ln(10);

        // Reset colors and font for rest of document
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetFont('helvetica', '', 10);
        // --- SECCIÓN 1: INFORMACIÓN PERSONAL ---
        $y = 45; // Posición inicial

        // === SECCIÓN 1: INFORMACIÓN PERSONAL ===
        $seccionAltura = 95;
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetCellMargins(0, 0, 0);
        $pdf->MultiCell(190, 7, '1. INFORMACIÓN PERSONAL', 0, 1, 'L', true);

        $y_start = $y + 7; // Posición inicial después del título

        // Establecer el color de fondo azul para la etiqueta 'Fotografía'
        $pdf->SetFillColor(173, 216, 230);

        // Establecer la fuente para la etiqueta (negrita)
        $pdf->SetFont('helvetica', 'B', 8);

        // Establecer la posición para la etiqueta 'Fotografía'
        $pdf->SetXY(105, $y_start + 8);

        // Mostrar la etiqueta 'Fotografía' con fondo de color azul
        $pdf->Cell(40, 5, 'Fotografía:', 0, 0, 'L', true);

        // Ajustar la posición Y para el siguiente campo
        $y_start += 6; // Ajuste para pasar al siguiente campo
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetXY(147, $y_start + 2);
        $pdf->MultiCell(50, 54, '', 1, 'C');  // Campo de fotografía con borde de 1, tamaño 50x28 y centrado
        $pdf->SetFillColor(255, 255, 255);
        // Agregar texto centrado dentro del campo de la fotografía
        $pdf->SetXY(150, $y_start + 9);  // Ajustar la posición Y para los textos (centrado dentro del campo)
        $pdf->MultiCell(40, 5, 'Fotografía:', 0, 0, 'C');  // Texto centrado dentro del campo de fotografía

        $pdf->SetXY(150, $y_start + 15);  // Ajustar la posición Y para el siguiente texto
        $pdf->MultiCell(40, 5, 'Tamaño Carné', 0, 0, 'C');  // Texto centrado dentro del campo de fotografía

        $pdf->SetXY(150, $y_start + 20);  // Ajustar la posición Y para el siguiente texto
        $pdf->MultiCell(40, 5, '(Física o Digital)', 0, 0, 'C');  // Texto centrado dentro del campo de fotografía



        // Primera columna (izquierda)
        crearCampo($pdf, 'Nombres', 12, $y_start + 2, $nombres);
        crearCampo($pdf, 'Apellidos', 12, $y_start + 9, $apellidos);
        crearCampo($pdf, 'No. de Cédula EC', 12, $y_start + 16, $cedula);
        crearCampo($pdf, 'Código Dactilar', 12, $y_start + 23, $codigo_dactilar);
        crearCampo($pdf, 'Fecha de Nacimiento', 12, $y_start + 30, $fecha_nacimiento);
        crearCampo($pdf, 'Estado Civil', 12, $y_start + 37, $estado_civil);
        crearCampo($pdf, 'Nacionalidad (natal)', 12, $y_start + 44, $nacionalidad);
        crearCampo($pdf, 'Tipo de Visa (extranjero)', 12, $y_start + 51, $tipo_visa);
        crearCampo($pdf, 'No. de Visa (extranjero)', 12, $y_start + 58, $numero_visa);
        crearCampo($pdf, 'Fecha Vencimiento Visa', 12, $y_start + 65, $fecha_vencimiento_visa);
        crearCampo($pdf, 'Provincia', 12, $y_start + 72, $provincia);
        crearCampo($pdf, 'Cantón', 12, $y_start + 79, $canton);
        crearCampo($pdf, 'Parroquia', 12, $y_start + 86, $parroquia);
        crearCampoAncho($pdf, 'Origen Indígena', $y_start + 93, $origen_indigena);

        // Segunda columna (derecha)
        crearCampo($pdf, 'Tipo de Discapacidad', 105, $y_start + 58, $tipo_discapacidad);
        crearCampo($pdf, 'Sexo', 105, $y_start + 65, $sexo);  // Ajuste del espacio
        crearCampo($pdf, 'Etnia', 105, $y_start + 72, $etnia);  // Ajuste del espacio
        crearCampo($pdf, 'Identidad de Género', 105, $y_start + 79, $identidad_genero);  // Ajuste del espacio
        crearCampo($pdf, 'Cantón', 105, $y_start + 86, $canton);  // Ajuste del espacio


        // Ajuste de posición para la siguiente sección
        $y = $y_start + $seccionAltura + 5; // Se suma 5 para dejar un pequeño margen

        // === SECCIÓN 2: DATOS DOMICILIARIOS ===
        $seccionAltura = 55;
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetCellMargins(0, 0, 0);
        $pdf->SetXY(10, $y); // Asegura que el título inicie en la nueva línea
        $pdf->MultiCell(190, 7, '2. DATOS DOMICILIARIOS', 0, 1, 'L', true);

        $y_start = $y + 7; // Ahora inicia correctamente después del título

        // Primera columna (izquierda)
        crearCampo($pdf, 'Calle Principal', 12, $y_start + 2, $calle_principal);
        crearCampo($pdf, 'Calle Secundaria', 12, $y_start + 9, $calle_secundaria);
        crearCampo($pdf, 'Número de Vivienda', 12, $y_start + 16, $numero_vivienda);
        crearCampo($pdf, 'Tipo de Vivienda', 12, $y_start + 24, $tipo_vivienda);
        crearCampo($pdf, 'Ocupación del Hogar', 12, $y_start + 32, $ocupacion_hogar);
        crearCampo($pdf, 'Número Piso de Vivienda', 12, $y_start + 40, $numero_piso_vivienda);
        crearCampo($pdf, 'Número del Hogar', 12, $y_start + 48, $clase);

        // Segunda columna (derecha)
        crearCampo($pdf, 'Cantón', 105, $y_start + 2, $canton);
        crearCampo($pdf, 'Parroquia', 105, $y_start + 9, $parroquia);
        crearCampo($pdf, 'Zona/Sector/Barrio', 105, $y_start + 16, $zona_sector_barrio);
        crearCampo($pdf, 'Referencia', 105, $y_start + 24, $referencia);
        crearCampo($pdf, 'Teléfono Domicilio', 105, $y_start + 32, $telefono_domicilio);
        crearCampo($pdf, 'Teléfono Celular', 105, $y_start + 40, $telefono_celular);
        crearCampo($pdf, 'Correo Electrónico', 105, $y_start + 48, $correo_electronico);

        // Ajuste de posición para la siguiente sección
        $y = $y_start + $seccionAltura + 5;


        // === SECCIÓN 3: CONTACTOS DE EMERGENCIA ===
        $seccionAltura = 50;
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetCellMargins(0, 0, 0);
        $pdf->SetXY(10, $y); // Asegura que el título inicie en la nueva línea
        $pdf->MultiCell(190, 7, '3. CONTACTOS DE EMERGENCIA', 0, 1, 'L', true);

        $y_start = $y + 7; // Inicia justo debajo del título

        // Primera fila de contactos
        // Primera fila de contactos
        crearCampo($pdf, 'Nombres y Apellidos', 12, $y_start + 2, $nombres_apellidos_1);
        crearCampo($pdf, 'Parentesco', 105, $y_start + 2, $parentesco_1);
        crearCampo($pdf, 'Teléfono Domicilio', 12, $y_start + 9, $telefono_domicilio_1); // Aumento del espacio
        crearCampo($pdf, 'Teléfono Celular', 105, $y_start + 9, $telefono_celular_1); // Aumento del espacio

        // Segunda fila de contactos
        crearCampo($pdf, 'Nombres y Apellidos', 12, $y_start + 16, $nombres_apellidos_2); // Aumento del espacio
        crearCampo($pdf, 'Parentesco', 105, $y_start + 16, $parentesco_2); // Aumento del espacio
        crearCampo($pdf, 'Teléfono Domicilio', 12, $y_start + 23, $telefono_domicilio_2); // Aumento del espacio
        crearCampo($pdf, 'Teléfono Celular', 105, $y_start + 23, $telefono_celular_2); // Aumento del espacio


        // Ajuste de posición para la siguiente sección
        $y = $y_start + $seccionAltura + 5;
        if ($y + $seccionAltura > 270) { // Si está cerca del final de la página
            $pdf->AddPage(); // Agregar nueva página
            $y = 20; // Resetear posición Y
        }

        // === SECCIÓN 4: INFORMACIÓN FAMILIAR (Convivientes Actuales) ===
        $seccionAltura = 30;
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetCellMargins(0, 0, 0);
        $pdf->SetXY(10, $y); // Asegura que el título inicie en la nueva línea
        $pdf->MultiCell(190, 7, '4. INFORMACIÓN FAMILIAR (Convivientes Actuales)', 0, 1, 'L', true);

        $y_start = $y + 7; // Inicia justo debajo del título

        // Cabecera de la tabla de convivientes
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetXY(12, $y_start + 2);
        $pdf->Cell(50, 5, 'Nombres y Apellidos', 1, 0, 'C', true);
        $pdf->Cell(20, 5, 'Parentesco', 1, 0, 'C', true);
        $pdf->Cell(25, 5, 'Fecha Nacimiento', 1, 0, 'C', true);
        $pdf->Cell(30, 5, 'No. Cédula/Pasaporte', 1, 0, 'C', true);
        $pdf->Cell(25, 5, 'Ocupación', 1, 0, 'C', true);
        $pdf->Cell(25, 5, 'Teléfono', 1, 1, 'C', true);

        // Datos de convivientes
        $pdf->SetFont('helvetica', '', 7);

        foreach ($datos_familiares as $fila) {
            $pdf->SetX(12);
            $pdf->Cell(50, 5, $fila[0], 1, 0, 'L');
            $pdf->Cell(20, 5, $fila[1], 1, 0, 'L');
            $pdf->Cell(25, 5, $fila[2], 1, 0, 'L');
            $pdf->Cell(30, 5, $fila[3], 1, 0, 'L');
            $pdf->Cell(25, 5, $fila[4], 1, 0, 'L');
            $pdf->Cell(25, 5, $fila[5], 1, 1, 'L');
        }

        // Ajuste de posición para la siguiente sección
        $y = $y_start + $seccionAltura + 5;


        // Verificar si hay suficiente espacio para sección 5
        if ($y > 220) { // Si está cerca del final de la página
            $pdf->AddPage(); // Agregar nueva página
            $y = 20; // Resetear posición Y
        }

        // === SECCIÓN 5: INFORMACIÓN DE CARGAS FAMILIARES ===
        $seccionAltura = 25;

        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetCellMargins(0, 0, 0);
        $pdf->SetXY(10, $y); // Asegura que el título inicie en la nueva línea
        $pdf->SetFillColor(255, 255, 255);
        $pdf->MultiCell(190, 7, '5. INFORMACIÓN DE CARGAS FAMILIARES (Impuesto a la renta - SRI)', 0, 1, 'L', true);

        $y_start = $y + 7; // Inicia después del título

        // Cabecera de la tabla
        $pdf->SetFont('helvetica', 'B', 7);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetXY(12, $y_start + 2);
        $pdf->Cell(40, 5, 'Nombres y Apellidos', 1, 0, 'C', true);
        $pdf->Cell(15, 5, 'Parentesco', 1, 0, 'C', true);
        $pdf->Cell(25, 5, 'Carga Familiar', 1, 0, 'C', true);
        $pdf->Cell(40, 5, 'Certificado/Aval', 1, 0, 'C', true);
        $pdf->Cell(25, 5, 'Discapacidad', 1, 0, 'C', true);
        $pdf->Cell(15, 5, 'Porcentaje', 1, 0, 'C', true);
        $pdf->Cell(25, 5, 'Autorización IR', 1, 1, 'C', true);

        // Datos de cargas familiares
        $pdf->SetFont('helvetica', '', 7);
        foreach ($datos_cargas as $fila) {
            $pdf->SetX(12);
            $pdf->Cell(40, 5, $fila[0], 1, 0, 'L');
            $pdf->Cell(15, 5, $fila[1], 1, 0, 'L');
            $pdf->Cell(25, 5, $fila[2], 1, 0, 'L');
            $pdf->Cell(40, 5, $fila[3], 1, 0, 'L');
            $pdf->Cell(25, 5, $fila[4], 1, 0, 'L');
            $pdf->Cell(15, 5, $fila[5], 1, 0, 'L');
            $pdf->Cell(25, 5, $fila[6], 1, 1, 'L');
        }

        // Ajuste de posición para la siguiente sección
        $y = $y_start + $seccionAltura + 5;

        // === SECCIÓN 6: REFERENCIAS NO FAMILIARES ===
        $seccionAltura = 25;
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetCellMargins(0, 0, 0);
        $pdf->SetXY(10, $y);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->MultiCell(190, 7, '6. REFERENCIAS NO FAMILIARES (Certificado de Honorabilidad)', 0, 1, 'L', true);

        $y_start = $y + 7;

        // Cabecera de la tabla
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetXY(12, $y_start + 2);
        $pdf->Cell(50, 5, 'Nombres y Apellidos', 1, 0, 'C', true);
        $pdf->Cell(20, 5, 'Parentesco', 1, 0, 'C', true);
        $pdf->Cell(10, 5, 'Edad', 1, 0, 'C', true);
        $pdf->Cell(35, 5, 'Ocupación', 1, 0, 'C', true);
        $pdf->Cell(40, 5, 'Dirección', 1, 0, 'C', true);
        $pdf->Cell(30, 5, 'Teléfono', 1, 1, 'C', true);

        // Datos de referencias no familiares
        $pdf->SetFont('helvetica', '', 7);
        foreach ($datos_referencias as $fila) {
            $pdf->SetX(12);
            $pdf->Cell(50, 5, $fila[0], 1, 0, 'L');
            $pdf->Cell(20, 5, $fila[1], 1, 0, 'L');
            $pdf->Cell(10, 5, $fila[2], 1, 0, 'L');
            $pdf->Cell(35, 5, $fila[3], 1, 0, 'L');
            $pdf->Cell(40, 5, $fila[4], 1, 0, 'L');
            $pdf->Cell(30, 5, $fila[5], 1, 1, 'L');
        }

        // Ajuste de posición para la siguiente sección
        $y = $y_start + $seccionAltura + 5;

        // === SECCIÓN 7: EDUCACIÓN ACADÉMICA ===
        $seccionAltura = 85;
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetCellMargins(0, 0, 0);
        $pdf->SetXY(10, $y);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->MultiCell(190, 7, '7. EDUCACIÓN ACADÉMICA (Tres más reciente - culminado y/o en estudios)', 0, 1, 'L', true);

        $y_start = $y + 7;

        // Datos de educación académica
        foreach ($formaciones_academicas as $formacion) {
            if (!empty($formacion['nivel_instruccion'])) {
                crearCampo($pdf, 'Nivel de Instrucción', 12, $y_start + 2, $formacion['nivel_instruccion']);
                crearCampo($pdf, 'Título Obtenido', 105, $y_start + 2, $formacion['titulo_obtenido']);
                crearCampoAncho($pdf, 'Unidad Educativa', $y_start + 11, $formacion['unidad_educativa']);
                crearCampo($pdf, 'País', 105, $y_start + 18, $formacion['pais']); // Espacio aumentado a +16
                crearCampo($pdf, 'Cuarto Nivel', 12, $y_start + 18, $formacion['cuarto_nivel']); // Espacio aumentado a +16

                // Incremento del espacio entre estos campos
                crearCampo($pdf, 'Nro Registro SENESCYT', 105, $y_start + 25, $formacion['registro_senecyt']); // Espacio incrementado
                crearCampo($pdf, 'Motivo/Horario/Otros:', 12, $y_start + 25, $formacion['motivo']); // Espacio incrementado
                crearCampo($pdf, 'Fecha Inicio', 105, $y_start + 32, $formacion['fecha_inicio']); // Espacio incrementado
                crearCampo($pdf, 'Fecha Fin', 12, $y_start + 32, $formacion['fecha_fin']); // Espacio incrementado

                // Ajustar la posición Y para la siguiente formación académica
                $y_start += 39; // Incremento más grande para evitar sobreposición
            }
        }

        // Ajuste de espacio para la siguiente sección
        $y = $y_start + 7;


        if ($y > 240) { // Si está muy cerca del final de la página
            $pdf->AddPage(); // Agregar nueva página
            $y = 20; // Resetear posición Y
        }

        // === SECCIÓN 8: CONOCIMIENTOS LENGUA EXTRANJERA ===
        $seccionAltura = 30;
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetCellMargins(0, 0, 0);
        $pdf->SetXY(10, $y);
        $pdf->MultiCell(190, 7, '8. CONOCIMIENTOS LENGUA EXTRANJERA (Actual)', 0, 1, 'L', true);

        $y_start = $y + 7; // Inicia después del título

        // Cabecera de la fila
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetXY(12, $y_start + 2);
        $pdf->Cell(30, 6, 'Idioma', 1, 0, 'C', true);
        $pdf->Cell(50, 6, 'Certificación Internacional', 1, 0, 'C', true);
        $pdf->Cell(60, 6, 'Institución', 1, 0, 'C', true);
        $pdf->Cell(30, 6, 'Nivel', 1, 1, 'C', true);

        // Datos de los idiomas
        $pdf->SetFont('helvetica', '', 8);
        foreach ($idiomas as $fila) {
            $pdf->SetX(12);
            $pdf->Cell(30, 6, $fila[0], 1, 0, 'C'); // Idioma
            $pdf->Cell(50, 6, $fila[1], 1, 0, 'C'); // Certificación
            $pdf->Cell(60, 6, $fila[2], 1, 0, 'C'); // Institución
            $pdf->Cell(30, 6, $fila[3], 1, 1, 'C'); // Nivel
        }

        // Ajuste de posición
        $y = $y_start + $seccionAltura + 5;

        // === SECCIÓN 9: INFORMACIÓN ADICIONAL ===
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetCellMargins(0, 0, 0);
        $pdf->SetXY(10, $y);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->MultiCell(190, 7, '9. INFORMACIÓN ADICIONAL (Actual)', 0, 1, 'L', true);

        $y_start = $y + 7; // Inicia después del título

        foreach ($datos_conocimientos as $index => $item) {
            // Verificar si el espacio es suficiente en la página antes de agregar un nuevo bloque
            if ($y_start + 27 > 270) { // Si el siguiente bloque sobrepasa el límite de la hoja
                $pdf->AddPage(); // Agregar nueva página
                $y_start = 20; // Reiniciar la posición Y en la nueva página
            }

            crearCampo($pdf, 'Paquetes Utilitarios', 12, $y_start + 2, $item['paquetes_utilitarios']);
            crearCampo($pdf, 'Base de Datos', 105, $y_start + 2, $item['base_de_datos']);
            crearCampo($pdf, 'Herramientas Gráficas', 12, $y_start + 9, $item['herramientas_graficas']);
            crearCampo($pdf, 'Otros Conocimientos', 105, $y_start + 9, $item['otros_conocimientos']);
            crearCampo($pdf, 'Registro Profesional 1', 12, $y_start + 16, $item['registro_profesional_1']);
            crearCampo($pdf, 'Número o Código', 105, $y_start + 16, $item['numero_o_codigo_1']);
            crearCampo($pdf, 'Registro Profesional 2', 12, $y_start + 23, $item['registro_profesional_2']);
            crearCampo($pdf, 'Número o Código', 105, $y_start + 23, $item['numero_o_codigo_2']);
            crearCampo($pdf, 'Idiomas', 12, $y_start + 30, $item['idiomas']);
            crearCampo($pdf, 'Habilidades Técnicas', 105, $y_start + 30, $item['habilidades_tecnicas']);

            // Ajuste de la posición para el siguiente bloque de datos
            $y_start += 36;
        }

        // Ajuste final de la posición
        $y = $y_start + 2;



        // --- SECCIÓN 10: EXPERIENCIA LABORAL ---
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetCellMargins(0, 0, 0);
        $pdf->SetXY(10, $y);
        $pdf->MultiCell(190, 7, '10. EXPERIENCIA LABORAL', 0, 1, 'L', true);

        $y_start = $y + 7; // Inicia después del título

        foreach ($datos_experiencia as $index => $experiencia) {
            // Verificar si el espacio es suficiente antes de agregar el siguiente bloque de experiencia
            if ($y_start + 32 > 270) { // Si el siguiente bloque sobrepasa el límite de la hoja
                $pdf->AddPage(); // Agregar nueva página
                $y_start = 20; // Reiniciar la posición Y en la nueva página
            }
            crearCampoAncho($pdf, 'Institución/Empresa', $y_start + 2, $experiencia['institucion_empresa']);
            crearCampo($pdf, 'Cargo/Puesto', 12, $y_start + 9, $experiencia['cargo_puesto']);
            crearCampo($pdf, 'Motivo Salida', 105, $y_start + 9, $experiencia['motivo_salida']);
            crearCampo($pdf, 'Fecha Ingreso', 12, $y_start + 16, $experiencia['fecha_ingreso']);
            crearCampo($pdf, 'Tiempo Laborado', 105, $y_start + 16, $experiencia['tiempo_laborado']);
            crearCampo($pdf, 'Sector Empresarial', 12, $y_start + 23, $experiencia['sector_empresarial']);
            crearCampo($pdf, 'Última Remuneración', 105, $y_start + 23, $experiencia['ultima_remuneracion']);
            crearCampo($pdf, 'Fecha Salida', 12, $y_start + 30, $experiencia['fecha_salida']);
            crearCampo($pdf, 'Figura Legal', 105, $y_start + 30, $experiencia['figura_legal']);
            crearCampo($pdf, 'Teléfono Empresa', 12, $y_start + 37, $experiencia['telefono_empresa']);
            crearCampo($pdf, 'Nombre Jefe Inmediato', 105, $y_start + 37, $experiencia['nombre_jefe_inmediato']);

            // Ajuste de la posición para el siguiente bloque de experiencia laboral
            $y_start += 44; // Incrementa la posición Y para el siguiente bloque
        }

        $y = $y_start + 5; // Espacio adicional después de la sección

        // --- SECCIÓN 11: INFORMACIÓN EVENTOS DE CAPACITACIÓN ---
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetCellMargins(0, 0, 0);
        $pdf->SetXY(10, $y);
        $pdf->MultiCell(190, 7, '11. INFORMACIÓN EVENTOS DE CAPACITACIÓN', 0, 1, 'L', true);

        $y_start = $y + 7; // Inicia después del título

        foreach ($datos_eventos as $item) {
            // Verificar si el espacio es suficiente antes de agregar el siguiente bloque de eventos
            if ($y_start + 27 > 270) { // Si el siguiente bloque sobrepasa el límite de la hoja
                $pdf->AddPage(); // Agregar nueva página
                $y_start = 20; // Reiniciar la posición Y en la nueva página
            }

            crearCampoAncho($pdf, 'Nombre del Evento', $y_start + 2, $item['nombre_evento']);
            crearCampo($pdf, 'Tipo de Evento', 12, $y_start + 9, $item['tipo_evento']);
            crearCampo($pdf, 'Duración de Horas', 105, $y_start + 9, $item['duracion_horas']);
            crearCampoAncho($pdf, 'Institución Auspiciante', $y_start + 16, $item['institucion_auspiciante']);
            crearCampo($pdf, 'Tipo de Certificado', 12, $y_start + 23, $item['tipo_certificado']);
            crearCampo($pdf, 'Fecha Inicio', 105, $y_start + 23, $item['fecha_inicio']);
            crearCampo($pdf, 'País', 12, $y_start + 30, $item['pais']);
            crearCampo($pdf, 'Fecha Fin', 105, $y_start + 30, $item['fecha_fin']);

            // Ajuste de la posición para el siguiente bloque de eventos
            $y_start += 36; // Incrementa la posición Y para el siguiente bloque
        }

        $y = $y_start + 5; // Espacio adicional después de la sección

        // --- SECCIÓN 12: INFORMACIÓN BANCARIA ---
        $seccionAltura = 20;
        if ($y + $seccionAltura > 270) { // Verificar si se está cerca del final de la página
            $pdf->AddPage(); // Agregar nueva página
            $y = 20; // Resetear la posición Y
        }
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetCellMargins(0, 0, 0);
        $pdf->SetXY(10, $y);
        $pdf->MultiCell(190, 7, '12. INFORMACIÓN BANCARIA', 0, 1, 'L', true);
        $y_start = $y + 7; // Inicia después del título

        $col1_x = 12;  // Primera columna
        $col2_x = 65;  // Segunda columna (antes era 75)
        $col3_x = 140; // Tercera columna (antes era 140)
        $row_height = 5; // Altura de fila más compacta

        // Campos de información bancaria
        // Mostrar los campos con el mismo formato que `crearCampoMovilizacion`
        crearCampoMovilizacion($pdf, 'Institución Financiera', $col1_x, $y_start, $institucion_financiera, 20, $row_height);
        crearCampoMovilizacion($pdf, 'Tipo Cuenta', $col2_x, $y_start, $tipo_cuenta, 20, $row_height);
        crearCampoMovilizacion($pdf, 'Número Cuenta', $col3_x, $y_start, $numero_cuenta, 20, $row_height);

        $y = $y_start + $seccionAltura + 5; // Ajuste de altura para la siguiente sección

        if ($y > 240) { // Si está muy cerca del final de la página
            $pdf->AddPage(); // Agregar nueva página
            $y = 20; // Resetear posición Y
        }

        // --- SECCIÓN 13: INFORMACIÓN MOVILIZACIÓN (Matrícula del Vehículo) ---
        $seccionAltura = 30;
        if ($y + $seccionAltura > 270) { // Si está cerca del final de la página
            $pdf->AddPage(); // Agregar nueva página
            $y = 20; // Resetear posición Y
        }
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetCellMargins(0, 0, 0);
        $pdf->SetXY(10, $y);
        $pdf->MultiCell(190, 7, '13. INFORMACIÓN MOVILIZACIÓN (Matrícula del Vehículo)', 0, 1, 'L', true);
        $y_start = $y + 7; // Inicia después del título

        // Definir coordenadas y ancho de cada columna (reducidos para mayor compactación)


        // Primera fila de información
        crearCampoMovilizacion($pdf, 'Vehículo', $col1_x, $y_start, $vehiculo, 20, $row_height);
        crearCampoMovilizacion($pdf, 'Propietario', $col2_x, $y_start, $propietario, 50, $row_height);
        crearCampoMovilizacion($pdf, 'Teléfono', $col3_x, $y_start, $telefono, 30, $row_height);

        // Segunda fila de información
        crearCampoMovilizacion($pdf, 'Clase', $col1_x, $y_start + $row_height + 2, $clase, 20, $row_height);
        crearCampoMovilizacion($pdf, 'Tipo', $col2_x, $y_start + $row_height  + 2, $tipo, 50, $row_height);
        crearCampoMovilizacion($pdf, 'Placa', $col3_x, $y_start + $row_height + 2, $placa, 30, $row_height);

        // Tercera fila de información
        crearCampoMovilizacion($pdf, 'Marca', $col1_x, $y_start + 2 * $row_height + 4, $marca, 20, $row_height);
        crearCampoMovilizacion($pdf, 'Modelo', $col2_x, $y_start + 2 * $row_height +  4, $modelo, 50, $row_height);
        crearCampoMovilizacion($pdf, 'Año', $col3_x, $y_start + 2 * $row_height +  4, $ano, 30, $row_height);

        // Cuarta fila de información
        crearCampoMovilizacion($pdf, 'Color 1', $col1_x, $y_start + 3 * $row_height + 6, $color1, 20, $row_height);
        crearCampoMovilizacion($pdf, 'Color 2', $col2_x, $y_start + 3 * $row_height + 6, $color2, 50, $row_height);
        crearCampoMovilizacion($pdf, 'Licencia', $col3_x, $y_start + 3 * $row_height + 6, $licencia, 30, $row_height);

        $y = $y_start + $seccionAltura + 5; // Ajuste de altura para la siguiente sección

        if ($y > 240) { // Si está muy cerca del final de la página
            $pdf->AddPage();
            $y = 20; // Resetear posición Y
        }

        // === SECCIÓN 14: CROQUIS DOMICILIARIO (Foto Google Maps) ===
        $seccionAltura = 30;
        if ($y + $seccionAltura > 270) { // Verificar si está cerca del final de la página
            $pdf->AddPage(); // Agregar nueva página
            $y = 20; // Resetear la posición Y
        }
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetCellMargins(0, 0, 0);
        $pdf->SetXY(10, $y);
        $pdf->MultiCell(190, 7, '14. CROQUIS DOMICILIARIO (Foto Google Maps)', 0, 1, 'L', true);
        $y_start = $y + 7; // Inicia después del título

        // URL de la imagen del mapa (reemplaza "YOUR_API_KEY" con tu clave de API)
        $mapUrl = "https://maps.googleapis.com/maps/api/staticmap?center=-0.2802442,-78.4629427&zoom=17&size=600x400&markers=color:red|label:S|-0.2802442,-78.4629427&key=YOUR_API_KEY";

        // Insertar la imagen en el PDF (ajustar las coordenadas y el tamaño de la imagen)
        $pdf->Image($mapUrl, 12, $y_start + 5, 180, 120);

        // Actualizar la posición Y después de la imagen
        $y = $y_start + 40; // posición de inicio + offset (5) + altura de imagen (120) + margen (5)

        if ($y > 240) {
            $pdf->AddPage();
            $y = 20;
        }


        // === SECCIÓN 15: HÁBITOS PERSONALES ===
        $seccionAltura = 30;
        if ($y + $seccionAltura > 270) { // Verificar si está cerca del final de la página
            $pdf->AddPage(); // Agregar nueva página
            $y = 20; // Resetear la posición Y
        }
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetCellMargins(0, 0, 0);
        $pdf->SetXY(10, $y);
        $pdf->MultiCell(190, 7, '15. HÁBITOS PERSONALES', 0, 1, 'L', true);
        $y_start = $y + 7; // Inicia después del título

        // Columna 1: Datos
        crearCampo($pdf, 'Deportes que Práctica', 12, $y_start + 2, $deportes_que_practica);
        crearCampo($pdf, 'Pasatiempos Favoritos', 12, $y_start + 9, $pasatiempos_favoritos);
        crearCampo($pdf, 'Consumos Nocivos', 12, $y_start + 16, $consumos_nocivos);
        crearCampo($pdf, 'Seguro de Vida Privado', 12, $y_start + 24, $seguro_vida_privado);

        // Columna 2: Datos
        crearCampo($pdf, 'Asistencia Psicológica', 105, $y_start + 2, $asistencia_psicologica);
        crearCampo($pdf, 'Grupo Sanguíneo', 105, $y_start + 9, $grupo_sanguineo);
        crearCampo($pdf, 'Enfermedades', 105, $y_start + 16, $enfermedades);
        crearCampo($pdf, 'Religión', 105, $y_start + 24, $religion);

        // Actualizar la posición para la siguiente sección
        $y = $y_start + 30 + 5;

        if ($y > 240) {
            $pdf->AddPage();
            $y = 20;
        }


        // === SECCIÓN 16: INFORMACIÓN COMPLEMENTARIA ===
        $seccionAltura = 35;
        if ($y + $seccionAltura > 270) { // Verificar si está cerca del final de la página
            $pdf->AddPage(); // Agregar nueva página
            $y = 20; // Resetear la posición Y
        }
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetCellMargins(0, 0, 0);
        $pdf->SetXY(10, $y);
        $pdf->MultiCell(190, 7, '16. INFORMACIÓN COMPLEMENTARIA', 0, 1, 'L', true);
        $y_start = $y + 7; // Inicia después del título

        // Columna 1: Datos
        crearCampo($pdf, 'Conocimiento de Oferta', 12, $y_start + 2, $conocimiento_oferta);
        crearCampo($pdf, 'Integra Agrupaciones', 12, $y_start + 9, $integra_agrupaciones);
        crearCampo($pdf, 'Trabajo Cónyugue/Pareja', 12, $y_start + 16, $trabajo_conyugue);
        crearCampo($pdf, 'Detalle Agrupación', 12, $y_start + 23, $detalle_agrupacion);

        // Columna 2: Datos
        crearCampo($pdf, 'Valor Ingresos Mensuales', 105, $y_start + 2, $valor_ingresos_mensuales);
        crearCampo($pdf, 'Cargo C./P.', 105, $y_start + 9, $cargo_cp);
        crearCampo($pdf, 'Integro Grupos Laborales', 105, $y_start + 16, $integro_grupos_laborales);
        crearCampo($pdf, 'Remuneración C./P.', 105, $y_start + 23, $remuneracion_cp);

        // Columna 1 (continuación)
        crearCampo($pdf, 'Parientes en Institución', 12, $y_start + 30, $parientes_en_institucion);
        // Columna 2 (continuación)
        crearCampo($pdf, 'Total Ingresos', 105, $y_start + 30, $total_ingresos);

        // Actualizar la posición para la siguiente sección
        $y = $y_start + $seccionAltura + 5;

        if ($y > 240) {
            $pdf->AddPage();
            $y = 20;
        }

        // === SECCIÓN 17: DECLARATORIA DE RESPONSABILIDAD ===
        $seccionAltura = 60;
        if ($y + $seccionAltura > 270) { // Verificar si está cerca del final de la página
            $pdf->AddPage(); // Agregar nueva página
            $y = 20; // Resetear la posición Y
        }
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetCellMargins(0, 0, 0);
        $pdf->SetXY(10, $y);
        $pdf->MultiCell(190, 7, '17. DECLARATORIA DE RESPONSABILIDAD', 0, 1, 'L', true);
        $y_start = $y + 7; // Inicia después del título

        // Texto de la declaratoria dentro de la sección
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->SetXY(12, $y_start + 10);
        $pdf->MultiCell(0, 10, 'Declaro que la información proporcionada en el presente formulario es veraz y autorizo a la Institución que realice las verificaciones pertinentes que requiera', 0, 'L');

        // Espacio para la firma (misma fila)
        $pdf->SetXY(12, $y_start + 30);
        $pdf->Line(12, $y_start + 30, 100, $y_start + 30); // Línea para firma
        $pdf->SetFont('Helvetica', 'B', 10);
        $pdf->MultiCell(95, 10, 'ALEJANDRA VALERIA SANTILLAN BERMEO', 0, 'L');
        $pdf->SetFont('Helvetica', '', 10);

        $pdf->SetXY(120, $y_start + 30);
        $pdf->Line(120, $y_start + 30, 200, $y_start + 30); // Línea para firma de coordinación
        $pdf->SetFont('Helvetica', 'B', 10);
        $pdf->Cell(95, 10, 'COORDINACIÓN DE TALENTO HUMANO', 0, 1, 'L');

        // Establecer localización para fechas en español y formatear fecha
        setlocale(LC_TIME, 'es_ES.UTF-8');
        $fecha = strftime('%A, %d de %B de %Y');

        $pdf->SetXY(12, $y_start + 45);
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->MultiCell(0, 10, 'Fecha última actualización: ' . $fecha, 0, 1, 'L');


        // Actualizar la posición para la siguiente sección (si existiese)
        $y = $y_start + $seccionAltura + 5;
        $tempDir = dirname(__DIR__, 2) . '/temp/';
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0777, true);
        }
        $fileName = 'formulario_' . time() . '.pdf';
        $filePath = $tempDir . $fileName;

        // Guardar el PDF en el servidor
        $pdf->Output($filePath, 'F');
        $relativePath = str_replace($_SERVER['DOCUMENT_ROOT'], '', $filePath);

        return [
            'success' => true,
            'ruta' => $relativePath,
            'message' => 'PDF generado correctamente'
        ];
    } catch (Exception $e) {
        return [
            'success' => false,
            'message' => 'Error al generar el PDF: ' . $e->getMessage()
        ];
    }
}
