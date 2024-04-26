<?php 

// Configuración de conexión
$ldapconfig['host'] = 'ldap://186.4.219.172';  // Servidor de Active Directory
$ldapconfig['port'] = 389;  // Puerto LDAP predeterminado
$ldapconfig['basedn'] = 'DC=devcorsinf,DC=local';  // Base DN de tu dominio

// Nombre de usuario y contraseña de prueba
$username = 'efarinango';
$password = 'EF1722214507*';

// Intentar la conexión
$ldapconn = ldap_connect($ldapconfig['host'], $ldapconfig['port'])
    or die("No se pudo conectar al servidor LDAP.");

if ($ldapconn) {
    // Configurar opciones de LDAP
    ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
    ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);

    // Intentar la autenticación
    $ldapbind = @ldap_bind($ldapconn, "$username@devcorsinf.local", $password);

    if ($ldapbind) {
        echo "LDAP bind successful...";
        // Aquí puedes realizar operaciones LDAP adicionales, como buscar usuarios, etc.



        // Realizar búsqueda de usuarios
        $base_dn = 'OU=Users,' . $ldapconfig['basedn'];  // Base DN donde buscar usuarios
        $filter = '(objectClass=group)';  // Filtro para buscar todos los usuarios
        $attributes = array('cn', 'mail');  // Atributos a recuperar

        $result = ldap_search($ldapconn, $base_dn, $filter, $attributes);
        $entries = ldap_get_entries($ldapconn, $result);

        // Mostrar resultados
        for ($i = 0; $i < $entries['count']; $i++) {
            if (isset($entries[$i]['cn'][0])) {
                echo "Nombre: " . $entries[$i]['cn'][0] . "<br>";
            }
            if (isset($entries[$i]['mail'][0])) {
                echo "Correo electrónico: " . $entries[$i]['mail'][0] . "<br>";
            }
            echo "<br>";
        }






    } else {
        echo "LDAP bind failed...";
    }

    // Cerrar la conexión LDAP
    ldap_close($ldapconn);
}


?>