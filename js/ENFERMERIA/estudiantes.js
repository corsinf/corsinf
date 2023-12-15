function select_parentesco(paretesco = '', campo = '') {

    if (paretesco === 'Padre') {
        selectElement = $(campo);
        optionElement = selectElement.find('option[value="Padre"]');
        if (optionElement.length > 0) {
            optionElement.prop('selected', true); // Selecciona la opción 'Femenino'
        }
    } else if (paretesco === 'Madre') {
        selectElement = $(campo);
        optionElement = selectElement.find('option[value="Madre"]');
        if (optionElement.length > 0) {
            optionElement.prop('selected', true); // Selecciona la opción 'Masculino'
        }
    } else if (paretesco === 'Hermano') {
        selectElement = $(campo);
        optionElement = selectElement.find('option[value="Hermano"]');
        if (optionElement.length > 0) {
            optionElement.prop('selected', true); // Selecciona la opción 'Masculino'
        }
    } else if (paretesco === 'Tio') {
        selectElement = $(campo);
        optionElement = selectElement.find('option[value="Tio"]');
        if (optionElement.length > 0) {
            optionElement.prop('selected', true); // Selecciona la opción 'Masculino'
        }
    } else if (paretesco === 'Primo') {
        selectElement = $(campo);
        optionElement = selectElement.find('option[value="Primo"]');
        if (optionElement.length > 0) {
            optionElement.prop('selected', true); // Selecciona la opción 'Masculino'
        }
    } else if (paretesco === 'Abuelo') {
        selectElement = $(campo);
        optionElement = selectElement.find('option[value="Abuelo"]');
        if (optionElement.length > 0) {
            optionElement.prop('selected', true); // Selecciona la opción 'Masculino'
        }
    } else if (paretesco === 'Otro') {
        selectElement = $(campo);
        optionElement = selectElement.find('option[value="Abuelo"]');
        if (optionElement.length > 0) {
            optionElement.prop('selected', true); // Selecciona la opción 'Masculino'
        }
    }
}