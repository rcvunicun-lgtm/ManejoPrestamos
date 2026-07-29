<?php

    if($peticionAjax){
        // require_once "../modelos/loginModelo.php";
        // require_once SERVERURL."modelos/loginModelo.php";
        // require_once "D:/Instalados/Xampp/htdocs/Prestamos/modelos/loginModelo.php";
        require_once SERVERPATH.'modelos/loginModelo.php';
				
    }else{
        // require_once "./modelos/usuarioModelo.php";
        // require_once "D:/Instalados/Xampp/htdocs/Prestamos/modelos/loginModelo.php";
        require_once SERVERPATH.'modelos/loginModelo.php';
    }

    class loginControlador extends loginModelo {

        /*-------- Controlador para iniciar sesion --------- */
        public function iniciar_sesion_controlador(){

            $usuario = mainModel::limpiar_cadena($_POST['usuario']);
            $clave = mainModel::limpiar_cadena($_POST['clave']);

            /*-------- Comprobar campos vacios --------- */
            if($usuario == "" || $clave == "")
            {
                echo ('
                        <script>
                            Swal.fire({
                            title: "Ocurrio un error inesperado.",
                            text: "No has llenado todos los campos que son requeridos.",
                            type: "error",
                            confirmButtonText: "Aceptar"});
                        </script>
                    ');
                exit;
            }

            /*-------- Verificar la integridad de los datos (pattern) --------- */
            if(mainModel::verificar_datos("[a-zA-Z0-9]{1,35}",$usuario)){
                echo ('
                        <script>
                            Swal.fire({
                            title: "Ocurrio un error inesperado.",
                            text: "El nombre de usuario no coincide con el formato solicitado.",
                            type: "error",
                            confirmButtonText: "Aceptar"});
                        </script>
                    ');
                exit;
            }

            if(mainModel::verificar_datos("[a-zA-Z0-9\$\@\.\-]{7,100}",$clave)){
            //if(mainModel::verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave)){
                echo ('
                        <script>
                            Swal.fire({
                            title: "Ocurrio un error inesperado.",
                            text: "La clave no coincide con el formato solicitado.",
                            type: "error",
                            confirmButtonText: "Aceptar"});
                        </script>
                    ');
                exit;
            }

            /*-------- Ejecutamos la consulata SQL para ingresar al sistema --------- */
            $clave = mainModel::encryption($clave);
            $datos_login = [
                "Usuario"=> $usuario,
                "Clave"=>$clave
            ];
            $datos_cuenta = loginModelo::iniciar_sesion_modelo($datos_login);

            if($datos_cuenta->rowCount() == 1){
                $row = $datos_cuenta->fetch();//Convertir los datos SQL en un array php
                
                session_start(['name'=>'SPM']);
                $_SESSION['id_spm'] = $row['usuario_id'];
                $_SESSION['nombre_spm'] = $row['usuario_nombre'];
                $_SESSION['apellido_spm'] = $row['usuario_apellido'];
                $_SESSION['usuario_spm'] = $row['usuario_usuario'];
                $_SESSION['privilegio_spm'] = $row['usuario_privilegio'];
                $_SESSION['token_spm'] = md5(uniqid(mt_rand(),true));
          
                return header("Location: ".SERVERURL."home/");
            }else{
                echo ('
                <script>
                    Swal.fire({
                    title: "Ocurrio un error inesperado.",
                    text: "El usuario o clave son incorrectos.",
                    type: "error",
                    confirmButtonText: "Aceptar"});
                </script>
            ');
      
            }
        }/*-------- Fin del controlador --------- */

        /*-------- Controlador forzar cierre de sesion --------- */
        public function forzar_cierre_sesion_controlador(){
           session_unset();
           session_destroy();
           if(headers_sent()){
            return "<script>window.location.href = '".SERVERURL."login/';</script>";
           }else{
            header("Location: ".SERVERURL."login/");
           }
           /*
                Condicional if (headers_sent()):

                headers_sent() es una función que verifica si los encabezados HTTP ya se han enviado al navegador. 
                Los encabezados son enviados automáticamente antes de cualquier salida visible (como HTML o texto).

                Si los encabezados ya han sido enviados (por ejemplo, si hay algo de HTML o texto antes de esta línea de código), 
                PHP no puede enviar encabezados adicionales, como los de redirección. 
                En este caso, no se puede usar header() para redirigir a otra página.
                
                Si los encabezados ya han sido enviados, entonces en vez de usar la función header() (que causaría un error), 
                el código devuelve un script JavaScript que realiza la redirección con window.location.href. 
                Esto funciona incluso si PHP no puede enviar los encabezados directamente.
           */
        }/*-------- Fin del controlador --------- */ 


        /*-------- Controlador cierre de sesion --------- */
        public function cerrar_sesion_controlador(){
            session_start(['name'=>'SPM']);
            $token = mainModel::decryption($_POST['token']);
            $usuario = mainModel::decryption($_POST['usuario']);
            
            if($token == $_SESSION['token_spm'] && $usuario == $_SESSION['usuario_spm']){
                session_unset();
                session_destroy();
                $alerta = [
                    "Alerta"=>"redireccionar",
                    "URL"=> SERVERURL."login/"
                ];
            }else{
                $alerta = [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrio un error inesperado",
                    "Texto"=>"No se pudo cerrar la sesión en el sistema.",
                    "Tipo"=>"error"
                ];
            }
            echo (json_encode($alerta));
        }/*-------- Fin del controlador --------- */ 
    }

?>