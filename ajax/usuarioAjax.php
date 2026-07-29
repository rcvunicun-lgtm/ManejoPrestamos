<?php
    $peticionAjax = true;

    require_once "../config/APP.php";

    if(isset($_POST['usuario_dni_reg']) || isset($_POST['usuadio_id_del']) || isset($_POST['usuario_id_update'])){

        /*---------- Instancia al controlador ----------*/
        require_once "../controladores/usuarioControlador.php";
        $ins_usuario = new usuarioControlador();

        /*---------- Agregar un usuario ----------*/
        // Aca debemos verificar que campos son obligatorios
        if(isset($_POST['usuario_dni_reg']) && isset($_POST["usuario_nombre_reg"])){
            echo($ins_usuario->agregar_usuario_controlador());
        } 
        
        /*---------- Eliminar un usuario ----------*/
        if(isset($_POST['usuadio_id_del'])){
            echo($ins_usuario->eliminar_usuario_controlador());
        } 

        /*---------- Actualizar un usuario ----------*/
        if(isset($_POST['usuario_id_update'])){
            echo($ins_usuario->actualizar_usuario_ontrolador());
        }
        
    }else{
       session_start(['name'=>'SPM']);
       session_unset();
       session_destroy();
       header('Location:'.SERVERURL."login/"); 
    }
?>