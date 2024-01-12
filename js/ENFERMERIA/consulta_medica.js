function calcularIMC() {
    var peso = $('#sa_conp_peso').val();
    var altura = $('#sa_conp_altura').val();

    if (peso && altura) {
        var imc = peso / (altura * altura);
        $('#txt_imc').val(imc.toFixed(2));

        var nivelPeso = obtenerNivelPeso(imc);
        $('#txt_np').val(nivelPeso);
    } else {
        $('#txt_imc, #txt_np').val('');
    }
}

function obtenerNivelPeso(imc) {
    if (imc < 18.5) {
        return 'Bajo Peso';
    } else if (imc >= 18.5 && imc < 24.9) {
        return 'Peso Saludable';
    } else if (imc >= 25 && imc < 29.9) {
        return 'Sobrepeso';
    } else if (imc >= 30 && imc < 34.9) {
        return 'Obesidad Grado I';
    } else if (imc >= 35 && imc < 39.9) {
        return 'Obesidad Grado II';
    } else {
        return 'Obesidad Grado III';
    }
}

$('#sa_conp_peso, #sa_conp_altura').on('input', calcularIMC);

function abrir_ventana_emergente(sa_conp_id) {
    // URL de la página que quieres cargar en la ventana emergente
    var url = '../controlador/consultasC.php?pdf_consulta=true&id_consulta=' + sa_conp_id;

    // Configuración de la ventana emergente
    var ventana_emergente = window.open(url, '_blank', 'width=1000,height=1000');

    // Se puede personalizar la configuración según tus necesidades
    // window.open(url, nombreVentana, opciones);
    // Ejemplo de opciones: 'width=500,height=400,toolbar=no,location=no,menubar=no,scrollbars=yes,resizable=yes'
}
