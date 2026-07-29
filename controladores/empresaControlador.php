<?php
if($peticionAjax){
	
	require_once SERVERPATH."modelos/empresaModelo.php";
		
}else{
	 require_once "./modelos/empresaModelo.php";

}

class EmpresaControlador extends empresaModelo {
    /*-------- Controlador datos empresa --------- */
    public function datos_empresa_controlador(){
        return EmpresaModelo::datos_empresa_modelo();
    }/* Fin del controlador */

    /*-------- Controlador registrar datos empresa --------- */
   public function agregar_empresa_controlador(){
        $nombre = mainModel::limpiar_cadena($_POST['empresa_nombre_reg']);
        $email = mainModel::limpiar_cadena($_POST['empresa_email_reg']);
        $telefono = mainModel::limpiar_cadena($_POST['empresa_telefono_reg']);
        $direccion = mainModel::limpiar_cadena($_POST['empresa_direccion_reg']);

        		/*---------- Comprobar los campos vacios ----------*/
		if($nombre == "" || $email == "" || $telefono == "" || $direccion == "")
		{
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"Todos los campos son obligatorios",
				"Tipo"=>"error"
			];
			echo (json_encode($alerta));
			exit();
		}

        		/*---------- Verificamos la integridad de los datos pattern ----------*/
		if(mainModel::verificar_datos("[a-zA-z0-9áéíóúÁÉÍÓÚñÑ \. ]{1,70}",$nombre)){
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"El nombre no coincide con el formato solicitado",
				"Tipo"=>"error"
			];
			echo (json_encode($alerta));
			exit();
		}

        if(mainModel::verificar_datos("[0-9 \( \) \+]{8,20}",$telefono)){
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"El teléfono no coincide con el formato solicitado",
				"Tipo"=>"error"
			];
			echo (json_encode($alerta));
			exit();
		}

        if(mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ \( \) \. \, \# \\ \- ]{1,190}",$direccion)){
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"La dirección no coincide con el formato solicitado",
				"Tipo"=>"error"
			];
			echo (json_encode($alerta));
			exit();
		}

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"El correo no coincide con el formato solicitado",
				"Tipo"=>"error"
			];
			echo (json_encode($alerta));
			exit();
        }

        /*---------- Comprobar empresas registradas ----------*/

        $check_empresas = mainModel::ejecutar_consulta_simple("SELECT empresa_id FROM empresa");
        if($check_empresas->rowCount() >= 1){
            $alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"Ya existe una empresa registrada, ya no puedesa registrar más.",
				"Tipo"=>"error"
			];
			echo (json_encode($alerta));
			exit(); 
        }

        $datos_empresa_reg = [
            "Nombre" => $nombre,
            "Email" => $email,
            "Telefono" => $telefono,
            "Direccion" => $direccion
        ];

        $agregar_empresa = EmpresaModelo::agregar_empresa_modelo($datos_empresa_reg);
        if($agregar_empresa->rowCount() == 1){
            $alerta = [
				"Alerta"=>"recargar",
				"Titulo"=>"Empresa registrada",
				"Texto"=>"Los datos de la empresa se registraron con éxito.",
				"Tipo"=>"success"
			];
			echo (json_encode($alerta));
        }else{
            $alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"No hemos podido registrar la empresa, por favor inténtelo nuevamente.",
				"Tipo"=>"error"
			];
			echo (json_encode($alerta));
        }

   }/* Fin del controlador */

     /*-------- Controlador actualizar datos empresa --------- */
	public function actualizar_empresa_controlador(){
		$id = mainModel::limpiar_cadena($_POST['empresa_id_up']);
		$nombre = mainModel::limpiar_cadena($_POST['empresa_nombre_up']);
		$email = mainModel::limpiar_cadena($_POST['empresa_email_up']);
		$telefono = mainModel::limpiar_cadena($_POST['empresa_telefono_up']);
		$direccion = mainModel::limpiar_cadena($_POST['empresa_direccion_up']);

		if($nombre == "" || $email == "" || $telefono == "" || $direccion == "")
		{
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"Todos los campos son obligatorios",
				"Tipo"=>"error"
			];
			echo (json_encode($alerta));
			exit();
		}

		/*---------- Verificamos la integridad de los datos pattern ----------*/
		if(mainModel::verificar_datos("[a-zA-z0-9áéíóúÁÉÍÓÚñÑ \. ]{1,70}",$nombre)){
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"El nombre no coincide con el formato solicitado",
				"Tipo"=>"error"
			];
			echo (json_encode($alerta));
			exit();
		}

		if(mainModel::verificar_datos("[0-9 \( \) \+]{8,20}",$telefono)){
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"El teléfono no coincide con el formato solicitado",
				"Tipo"=>"error"
			];
			echo (json_encode($alerta));
			exit();
		}

		if(mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ \( \) \. \, \# \\ \- ]{1,190}",$direccion)){
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"La dirección no coincide con el formato solicitado",
				"Tipo"=>"error"
			];
			echo (json_encode($alerta));
			exit();
		}

		if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"El correo no coincide con el formato solicitado",
				"Tipo"=>"error"
			];
			echo (json_encode($alerta));
			exit();
		}

		/*---------- Comprobar privilegios ----------*/
		session_start(['name'=>'SPM']);
		if($_SESSION['privilegio_spm'] < 1 || $_SESSION['privilegio_spm'] > 2 ){
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"No tienes los permisos necesarios para realizar esta operación.",
				"Tipo"=>"error"
			];
			echo (json_encode($alerta));
			exit();
		}

		
        $datos_empresa_up = [
            "Nombre" => $nombre,
            "Email" => $email,
            "Telefono" => $telefono,
            "Direccion" => $direccion,
			"ID" => $id
        ];

		if(EmpresaModelo::actualizar_empresa_modelo($datos_empresa_up)){
			$alerta = [
				"Alerta"=>"recargar",
				"Titulo"=>"Empresa actualizada.",
				"Texto"=>"Los datos de la empresa han sido actualizados con éxito.",
				"Tipo"=>"success"
			];
		}else{
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"No hemos podido actualizar los datos de la empresa, Por favor inténtelo nuevamente.",
				"Tipo"=>"error"
			];
		}
		echo (json_encode($alerta));
		exit();
	}/* Fin del controlador */
}
?>