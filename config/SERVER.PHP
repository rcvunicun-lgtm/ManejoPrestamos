<?php
    
    // Constantes generales para la manipulacion de mi base de datos
    const SERVER = 'localhost';
    const DB = 'prestamos';
    const USER = 'root';
    const PASS = '';

    const SGBD = 'mysql:host='.SERVER.';dbname='.DB;

    
    // Creacion de constantes que van a llevar algunos parametros de configuracion para procesar por hash la encriptación de contraseñas 
    
    // Estas constantes en PHP están configuradas para usar el cifrado AES-256-CBC en criptografía de datos. Vamos una a una para comprender su función:
    /*
    const METHOD = "AES-256-CBC";: 
        Define el método de cifrado a utilizar. AES-256-CBC es un método de cifrado simétrico 
        en el cual el mismo algoritmo se utiliza para encriptar y desencriptar datos. 
        AES-256 significa que usa una clave de 256 bits,y CBC (Cipher Block Chaining)
        es un modo de operación que encadena bloques de cifrado para mayor seguridad.

    const SECRET_KEY = '$PRESTAMOS@2024';: 
        Define una clave secreta que se usa para el cifrado. 
        Esta clave es un parámetro importante para el algoritmo AES y debe ser conocida únicamente por quienes tienen acceso autorizado
        a los datos encriptados. Cambiar la clave haría que los datos cifrados ya no sean accesibles.

    const SECRET_IV = '98100555964';:
        Define el vector de inicialización (IV, Initialization Vector) para el cifrado. 
        El IV es un valor aleatorio que asegura que el mismo texto cifrado no resulte igual cuando se usa el mismo mensaje 
        y la misma clave. Es una medida adicional de seguridad que ayuda a evitar patrones en el cifrado.

    Juntas, estas constantes permiten realizar operaciones seguras de cifrado y descifrado en PHP.
    */

    const METHOD = "AES-256-CBC";
     // (Nota: estos numero solo se pueden cambiar antes de empezar a hacer registros a la BD)
    const SECRET_KEY = '$PRESTAMOS@2024'; // Esta clave secreta puede ser cualquier otro texto.
    const SECRET_IV = '98100555964'; // Este numero tambien puede ser cualquier otro numero.
?>