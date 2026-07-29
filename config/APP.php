<?php
    
    // Constantes generales para la configuración de mi aplicación
    /*
        En algunas instrucciones require_once() saldra un error porque estás intentando incluir un archivo utilizando una URL (http://)
        en una instrucción require_once().
        en PHP, pero la configuración del servidor no permite incluir archivos externos con URLs. La directiva allow_url_include
        está configurada en 0, lo cual desactiva esta funcionalidad por motivos de seguridad.

         aquí tienes algunas opciones:

            1- Usa rutas locales en lugar de URLs: En lugar de usar una URL 
            2- Configura allow_url_include en 1 (No recomendado)  :Si tienes acceso a la configuración del servidor y deseas permitir incluir archivos remotos (aunque no se recomienda por razones de seguridad), puedes habilitar allow_url_include editando el archivo php.ini:
            3- Alternativa con file_get_contents o curl (si solo necesitas el contenido) $contenido = file_get_contents("http://example.com/archivo.php");
            4- Acceder a los archivos desde el directorio del servidor: $_SERVER["DOCUMENT_ROOT"]
    */
    const SERVERURL = "http://localhost/Prestamos/";
    const COMPANY = 'SISTEMAS PRESTAMOS';
    const MONEDA = '$';
    date_default_timezone_set('America/Bogota');

    // const SERVERPATH = $_SERVER["DOCUMENT_ROOT"]; // El problema con esta línea es que estás intentando definir una constante en PHP usando const, pero en PHP, const solo puede asignar valores constantes en tiempo de compilación, como strings, números, o arrays simples. No puede usar expresiones como $_SERVER["DOCUMENT_ROOT"], que se evalúan en tiempo de ejecución.
    define('SERVERPATH', $_SERVER["DOCUMENT_ROOT"]."/Prestamos/");
?>