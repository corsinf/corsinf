//Aqui llega para que alguno de las tablas vea si existe o no el paciente 
function gestion_paciente_comunidad(sa_pac_id_comunidad, sa_pac_tabla) {
    $.ajax({
        data: {
            sa_pac_id_comunidad: sa_pac_id_comunidad,
            sa_pac_tabla: sa_pac_tabla
        },
        url: '../controlador/ficha_MedicaC.php?administrar_comunidad_ficha_medica=true',
        type: 'post',
        dataType: 'json',

        success: function(response) {
            // Crear un formulario dinámicamente
            var form = document.createElement('form');
            form.method = 'post';
            form.action = '../vista/inicio.php?mod=7&acc=ficha_medica_pacientes';
            //form.action = 'http://localhost/corsinf/pruebas_eliminar/index1.php';

            

            // Función para agregar un campo oculto al formulario
            function agregarCampo(nombre, valor) {
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = nombre;
                input.value = valor;
                form.appendChild(input);
            }

            // Agregar campos al formulario
            agregarCampo('sa_pac_id', response.sa_pac_id);
            agregarCampo('sa_pac_tabla', response.sa_pac_tabla);

            // Agregar el formulario al cuerpo del documento
            document.body.appendChild(form);

            // Enviar el formulario
            form.submit();
        }
    });
}