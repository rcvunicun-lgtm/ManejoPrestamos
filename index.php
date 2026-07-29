<?php
    require_once '../Prestamos/config/APP.php';
    require_once '../Prestamos/controladores/vistasControlador.php';

    $plantilla = new vistasControlador();
    $plantilla->obtener_plantilla_controlador();

?>
 
