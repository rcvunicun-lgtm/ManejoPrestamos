<?php
    /* 
    Rutas relativas

        Aunque ambas pueden estar apuntando al mismo archivo esto puede verser alterado dependiendo 
        en donde se llame este archivo Para evitar esto se recomienta utilizar rutas absolutas.
    */

    // require_once 'modelos/vistasModelos.php';  
    // require_once '../../Prestamos/modelos/vistasModelos.php';

    /*
     Rutas absolutas

        Las rutas absolutas son especificaciones de ubicación de archivos en un sistema de archivos que indican 
        la ubicación completa desde la raíz del sistema de archivos o del servidor web. 
        Estas rutas comienzan desde la raíz del sistema o del entorno de ejecución y no dependen del directorio actual 
        desde donde se ejecuta un script.
     */

    require_once $_SERVER['DOCUMENT_ROOT'] . '/Prestamos/modelos/vistasModelos.php';
    //require_once __DIR__ . '/../modelos/vistasModelos.php';
    // require_once $_SERVER['DOCUMENT_ROOT'] . '/Prestamos/modelos/vistasModelos.php';


    class vistasControlador extends vistasModelo{
         
        //-------- Controlador para obtener plantilla --------//
        public function obtener_plantilla_controlador(){
            return require_once '../Prestamos/vistas/plantilla.php';
        }

         //-------- Controlador para obtener vistas --------//
         public function obtener_vistas_controlador(){
            if(isset($_GET['views'])){
                $ruta = explode("/",$_GET['views']);
                $respuesta = vistasModelo::obtener_vistas_modelo($ruta[0]);
            }else{
                $respuesta = "login";
            }
            return $respuesta;
        }
    }
?>