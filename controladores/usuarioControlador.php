<?php
if($peticionAjax){
	// require_once "../modelos/usuarioModelo.php";
	require_once "D:/Instalados/Xampp/htdocs/Prestamos/modelos/usuarioModelo.php";
		
}else{
	// require_once "./modelos/usuarioModelo.php";
	require_once "D:/Instalados/Xampp/htdocs/Prestamos/modelos/usuarioModelo.php";
}

class usuarioControlador extends usuarioModelo {

	/*---------- controlador agregar usuario ----------*/
	public function agregar_usuario_controlador(){
		$dni = mainModel::limpiar_cadena($_POST['usuario_dni_reg']);
		$nombre = mainModel::limpiar_cadena($_POST['usuario_nombre_reg']);
		$apellido = mainModel::limpiar_cadena($_POST['usuario_apellido_reg']);
		$telefono = mainModel::limpiar_cadena($_POST['usuario_telefono_reg']);
		$direccion = mainModel::limpiar_cadena($_POST['usuario_direccion_reg']);
		
		$usuario = mainModel::limpiar_cadena($_POST['usuario_usuario_reg']);
		$email = mainModel::limpiar_cadena($_POST['usuario_email_reg']);
		$clave1 = mainModel::limpiar_cadena($_POST['usuario_clave_1_reg']);
		$clave2 = mainModel::limpiar_cadena($_POST['usuario_clave_2_reg']);
		
		$privilegio = mainModel::limpiar_cadena($_POST['usuario_privilegio_reg']);

		/*---------- Comprobar los campos vacios ----------*/
		if($dni == "" || $nombre == "" || $apellido == "" || $usuario == "" || $clave1 == "" || $clave2 == "")
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
		if(mainModel::verificar_datos("[0-9\-]{1,20}",$dni)){
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"El DNI no coincide con el formato solicitado",
				"Tipo"=>"error"
			];
			echo (json_encode($alerta));
			exit();
		}

		if(mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35}",$nombre)){
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"El nombre no coincide con el formato solicitado",
				"Tipo"=>"error"
			];
			echo (json_encode($alerta));
			exit();
		}

		if(mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35}",$apellido)){
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"El apellido no coincide con el formato solicitado",
				"Tipo"=>"error"
			];
			echo (json_encode($alerta));
			exit();
		}

		if($telefono != ''){ //Esta comprobacion se hace si no es un campo obligatorio
			if(mainModel::verificar_datos("[0-9 \( \) \+]{8,20}",$telefono)){
				$alerta = [
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrio un error inesperado",
					"Texto"=>"El telefono no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo (json_encode($alerta));
				exit();
			}
		}

		if($direccion != ''){ //Esta comprobacion se hace si no es un campo obligatorio
			if(mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ \( \) \. \, \# \- ]{1,190}",$direccion)){
				$alerta = [
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrio un error inesperado",
					"Texto"=>"La direccion no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo (json_encode($alerta));
				exit();
			}
		}

		if(mainModel::verificar_datos("[a-zA-Z0-9]{1,35}",$usuario)){
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"El usuario no coincide con el formato solicitado",
				"Tipo"=>"error"
			];
			echo (json_encode($alerta));
			exit();
		}

		if(mainModel::verificar_datos("[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}",$email)){
		// if(mainModel::verificar_datos("[^\s@]+@[^\s@]+\.[^\s@]{2,}",$email)){
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"El email no coincide con el formato solicitado",
				"Tipo"=>"error"
			];
			echo (json_encode($alerta));
			exit();
		}

		if(mainModel::verificar_datos("[a-zA-Z0-9\$\@\.\-]{7,100}",$clave1)){
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"La contraseña no coincide con el formato solicitado",
				"Tipo"=>"error"
			];
			echo (json_encode($alerta));
			exit();
		}

		if(mainModel::verificar_datos("[a-zA-Z0-9\$\@\.\-]{7,100}",$clave2)){
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"La contraseña repetida no coincide con el formato solicitado",
				"Tipo"=>"error"
			];
			echo (json_encode($alerta));
			exit();
		}

		/*---------- Verificamos si el DNI a agregar no se encuentra en la BD ----------*/
		$check_dni = mainModel::ejecutar_consulta_simple("SELECT usuario_dni FROM usuario WHERE usuario_dni = $dni;");
		if($check_dni->rowCount() > 0){
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"El DNI ingresado ya se encuentra registrado en el sistema.",
				"Tipo"=>"error"
			];
			echo (json_encode($alerta));
			exit();
		}

		/*---------- Verificamos si el USUARIO a agregar no se encuentra en la BD ----------*/
		$check_user = mainModel::ejecutar_consulta_simple("SELECT usuario_usuario FROM usuario WHERE usuario_usuario = '$usuario';");
		if($check_user->rowCount() > 0){
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"El usuario ingresado ya se encuentra registrado en el sistema.",
				"Tipo"=>"error"
			];
			echo (json_encode($alerta));
			exit();
		}

		/*---------- Verificamos si el EMAIL a agregar no se encuentra en la BD ----------*/
		if($email != ""){
			if(filter_var($email,FILTER_VALIDATE_EMAIL)){
				$check_email = mainModel::ejecutar_consulta_simple("SELECT usuario_email FROM usuario WHERE usuario_email = '$email';");
				if($check_email->rowCount() > 0){
					$alerta = [
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrio un error inesperado",
						"Texto"=>"El email ingresado ya se encuentra registrado en el sistema.",
						"Tipo"=>"error"
					];
					echo (json_encode($alerta));
					exit();
				}
			}
		}

		/*---------- Verificamos si las ambas contraseñas son las mismas ----------*/
		if($clave1 != $clave2){
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"Las contraseñas no coinciden.",
				"Tipo"=>"error"
			];
			echo (json_encode($alerta));
			exit();
		}else{
			$clave = mainModel::encryption($clave1);
		}

		/*---------- Comprovamos el privilegio ----------*/
		if($privilegio < 1 || $privilegio > 3){
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"El privilegio seleccionado no es valido.",
				"Tipo"=>"error"
			];
			echo (json_encode($alerta));
			exit();
		}

		/* Ejecutamos la consulta SQL para agregar un usuario */
		$datos_usuario_reg = [
			"DNI" => $dni,
			"Nombre" => $nombre,
			"Apellido" => $apellido,
			"Telefono" => $telefono,
			"Direccion" => $direccion,
			"Email" => $email,
			"Usuario" => $usuario,
			"Clave" => $clave,
			"Estado" => "Activa",
			"Privilegio" => $privilegio
		];

		$agregar_usuario = usuarioModelo::agregar_usuario_modelo($datos_usuario_reg);

		if($agregar_usuario->rowCount() == 1){
			$alerta = [
				"Alerta"=>"limpiar",
				"Titulo"=>"Usuario registrado",
				"Texto"=>"Los datos del usuario han sido registrados con exito.",
				"Tipo"=>"success"
			];
		
		}else{
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"No hemos podido registrar el usuario.",
				"Tipo"=>"error"
			];
		}
		echo (json_encode($alerta));
		// exit(); //Opcional
	}/* Fin del controlador */

	/*---------- controlador paginar usuarioa ----------*/
	public function paginador_usuario_controlador($pagina, $registros, $privilegio, $id, $url, $busqueda){

		$pagina = mainModel::limpiar_cadena($pagina);
		$registros = mainModel::limpiar_cadena($registros);
		$privilegio = mainModel::limpiar_cadena($privilegio);
		$id = mainModel::limpiar_cadena($id);

		$url = mainModel::limpiar_cadena($url);
		$url = SERVERURL.$url."/";

		$busqueda = mainModel::limpiar_cadena($busqueda);
		$tabla = "";

		$pagina = (isset($pagina) && $pagina > 0)? (int)$pagina : 1;
		$inicio = ($pagina > 0)? (($pagina * $registros) - $registros):0 ;

		if(isset($busqueda) && $busqueda != ""){
			$consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM usuario WHERE ((usuario_id != '$id' AND usuario_id != '1') AND (usuario_dni LIKE '%$busqueda%' OR usuario_nombre LIKE '%$busqueda%' OR usuario_apellido LIKE '%$busqueda%' OR usuario_telefono LIKE '%$busqueda%' OR usuario_email LIKE '%$busqueda%' OR usuario_usuario LIKE '%$busqueda%')) ORDER BY usuario_nombre ASC LIMIT $inicio, $registros;";
		}else{
			$consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM usuario WHERE usuario_id != '$id' AND usuario_id != '1' ORDER BY usuario_nombre ASC LIMIT $inicio, $registros;";
		}

		$conexion = mainModel::conectar();

		$datos = $conexion->query($consulta);

		$datos = $datos->fetchAll();

		$total = $conexion->query("SELECT FOUND_ROWS();");
		$total = (int) $total->fetchColumn();

		$Npaginas = ceil($total/$registros);

		$tabla .= '<div class="table-responsive">
        <table class="table table-dark table-sm">
            <thead>
                <tr class="text-center roboto-medium">
                    <th>#</th>
                    <th>DNI</th>
                    <th>NOMBRE</th>
                    <th>APELLIDO</th>
                    <th>TELÉFONO</th>
                    <th>USUARIO</th>
                    <th>EMAIL</th>
                    <th>ACTUALIZAR</th>
                    <th>ELIMINAR</th>
                </tr>
            </thead>
            <tbody>';

		if($total >= 1 && $pagina <= $Npaginas){
			$contador = $inicio + 1;
			
			$reg_inicio = $inicio+1;

			foreach($datos as $rows){
				$tabla .= '
				<tr class="text-center" >
                    <td>'.$contador.'</td>
                    <td>'.$rows['usuario_dni'].'</td>
                    <td>'.$rows['usuario_nombre'].'</td>
                    <td>'.$rows['usuario_apellido'].'</td>
                    <td>'.$rows['usuario_telefono'].'</td>
                    <td>'.$rows['usuario_usuario'].'</td>
                    <td>'.$rows['usuario_email'].'</td>
                    <td>
                        <a href="'.SERVERURL.'user-update/'.mainModel::encryption($rows['usuario_id']).'/" class="btn btn-success">
                            <i class="fas fa-sync-alt"></i>	
                        </a>
                    </td>
                    <td>
					
						<!-- ESTE FORMULARIO SE ESTA CONTROLANDO DESDE alertas.js -->
						
                        <form class="FormularioAjax" action="'.SERVERURL.'ajax/usuarioAjax.php" method="post" data-form="delete" autocomplete="off">
							<input type="hidden" name= "usuadio_id_del" value="'.mainModel::encryption($rows['usuario_id']).'">
                            <button type="submit" class="btn btn-warning">
                                <i class="far fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>';
				$contador ++;
			}

			$reg_final = $contador - 1;
		}else{
			if($total >= 1){
				$tabla .= '<tr class="text-center"><td colspan="9"><a href="'.$url.'" class="btn btn-raise btn-primary btn-sm">Haga click acá para recargar el listado.</a></td></tr>';
			}else{	
				$tabla .= '<tr class="text-center"><td colspan="9">No hay registros en el sistema.</td></tr>';
			}
		}

		$tabla .= '</tbody></table></div>';

	
		if($total >= 1 && $pagina <= $Npaginas){

			$tabla .= '<p class="text-right">Mostrando usuarios '.$reg_inicio.' al...'.$reg_final.' de un total de '.$total.'</p>';

			$tabla .= mainModel::paginador_tablas($pagina, $Npaginas, $url, 2);
		}

		return $tabla;

	}/* Fin del controlador */


	/*---------- controlador eliminar usuario ----------*/
	public function eliminar_usuario_controlador(){
		$id = mainModel::decryption($_POST['usuadio_id_del']);
		$id = mainModel::limpiar_cadena($id);

		/* Comprobar si es el usuario 1*/
		if($id == 1){
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"No puedes eliminar al usuario 1, ya que es el administrador del sistema.",
				"Tipo"=>"error"
			];
			echo (json_encode($alerta));
			exit();
		}

		/* Comprobar el usuario en la BD */
		$check_usuario = mainModel::ejecutar_consulta_simple("SELECT usuario_id FROM usuario WHERE usuario_id = '$id';");
		if($check_usuario->rowCount() <= 0){
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"El usuario no existe en el sistema.",
				"Tipo"=>"error"
			];
			echo (json_encode($alerta));
			exit();
		}

		/* Comprobar los prestamos en la BD */
		$check_prestamos = mainModel::ejecutar_consulta_simple("SELECT usuario_id FROM prestamo WHERE usuario_id = '$id' LIMIT 1;");
		if($check_prestamos->rowCount() > 0){
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"No podemos eliminar este usuario debido a que tiene prestamos asociados, recomendamos deshabilitar el usuario si ya no sera utilizado.",
				"Tipo"=>"error"
			];
			echo (json_encode($alerta));
			exit();
		}

		/* Comprobar privilegios*/
		session_start(['name'=>'SPM']);
		if($_SESSION['privilegio_spm'] != 1){
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"No tienes los permisos necesarios para realizar esta acción.",
				"Tipo"=>"error"
			];
			echo (json_encode($alerta));
			exit();
		}

		$eliminar_usuario = usuarioModelo::eliminar_usuario_modelo($id);
		if($eliminar_usuario->rowCount() == 1){
			$alerta = [
				"Alerta"=>"recargar",
				"Titulo"=>"Usuario eliminado.",
				"Texto"=>"El usuario ha sido eliminado del sistema exitosamente.",
				"Tipo"=>"success"
			];
	
		}else{
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"No hemos podido eliminar el usuario, por favor intentelo nuevamente.",
				"Tipo"=>"error"
			];
			
		}
		echo (json_encode($alerta));
		exit();
	}/* Fin del controlador */

	/*---------- Controlador seleccionar datos del usuario ----------*/
	public function datos_usuario_controlador($tipo, $id){
		$tipo = mainModel::limpiar_cadena($tipo);
		$id = mainModel::decryption($id);
		$id = mainModel::limpiar_cadena($id);

		return usuarioModelo::datos_usuario_modelo($tipo, $id);
	}/* Fin del controlador */

	/*---------- Controlador actualizar datos del usuario ----------*/
	public function actualizar_usuario_ontrolador(){
		// Recibiendo el id
		$id = mainModel::decryption($_POST['usuario_id_update']);
		$id = mainModel::limpiar_cadena($id);

		// comprobar la existencia del usuario en la bd
		$check_user = mainModel::ejecutar_consulta_simple("SELECT * FROM usuario WHERE usuario_id = $id;");

		if($check_user->rowCount() <= 0){
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"No hemos encontrado este usuario en el sistema",
				"Tipo"=>"error"
			];
			
			echo (json_encode($alerta));
			exit();
		}else{
			$campos = $check_user->fetch();
			$dni = mainModel::limpiar_cadena($_POST['usuario_dni_up']);
			$nombre = mainModel::limpiar_cadena($_POST['usuario_nombre_up']);
			$apellido = mainModel::limpiar_cadena($_POST['usuario_apellido_up']);
			$telefono = mainModel::limpiar_cadena($_POST['usuario_telefono_up']);
			$direccion = mainModel::limpiar_cadena($_POST['usuario_direccion_up']);

			$usuario = mainModel::limpiar_cadena($_POST['usuario_usuario_up']);
			$email = mainModel::limpiar_cadena($_POST['usuario_email_up']);
			
			if(isset($_POST['usuario_estado_up'])){
				$estado =  mainModel::limpiar_cadena($_POST['usuario_estado_up']);
				
			}else{
				$estado = $campos['usuario_estado'];
			}

			if(isset($_POST['usuario_privilegio_up'])){
				$privilegio =  mainModel::limpiar_cadena($_POST['usuario_privilegio_up']);
			}else{
				$privilegio = $campos['usuario_privilegio'];
			}

			$admin_usuario = mainModel::limpiar_cadena($_POST['usuario_admin']);
			$admin_clave = mainModel::limpiar_cadena($_POST['clave_admin']);

			$tipo_cuenta = mainModel::limpiar_cadena($_POST['tipo_cuenta']);

			/*---------- Comprobar los campos vacios ----------*/
			if($dni == "" || $nombre == "" || $apellido == "" || $usuario == "" || $admin_usuario == "" || $admin_clave =="")
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
			if(mainModel::verificar_datos("[0-9\-]{1,20}",$dni)){
				$alerta = [
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrio un error inesperado",
					"Texto"=>"El DNI no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo (json_encode($alerta));
				exit();
			}

			if(mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35}",$nombre)){
				$alerta = [
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrio un error inesperado",
					"Texto"=>"El nombre no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo (json_encode($alerta));
				exit();
			}

			if(mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35}",$apellido)){
				$alerta = [
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrio un error inesperado",
					"Texto"=>"El apellido no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo (json_encode($alerta));
				exit();
			}

			if($telefono != ''){ //Esta comprobacion se hace si no es un campo obligatorio
				if(mainModel::verificar_datos("[0-9 \( \) \+]{8,20}",$telefono)){
					$alerta = [
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrio un error inesperado",
						"Texto"=>"El telefono no coincide con el formato solicitado",
						"Tipo"=>"error"
					];
					echo (json_encode($alerta));
					exit();
				}
			}

			if($direccion != ''){ //Esta comprobacion se hace si no es un campo obligatorio
				if(mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ \( \) \. \, \# \- ]{1,190}",$direccion)){
					$alerta = [
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrio un error inesperado",
						"Texto"=>"La direccion no coincide con el formato solicitado",
						"Tipo"=>"error"
					];
					echo (json_encode($alerta));
					exit();
				}
			}

			if(mainModel::verificar_datos("[a-zA-Z0-9]{1,35}",$usuario)){
				$alerta = [
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrio un error inesperado",
					"Texto"=>"El usuario no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo (json_encode($alerta));
				exit();
			}

			if($email != '' && mainModel::verificar_datos("[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}",$email)){
			// if(mainModel::verificar_datos("[^\s@]+@[^\s@]+\.[^\s@]{2,}",$email)){
				$alerta = [
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrio un error inesperado",
					"Texto"=>"El email no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo (json_encode($alerta));
				exit();
			}

			if(mainModel::verificar_datos("[a-zA-Z0-9]{1,35}",$admin_usuario)){
				$alerta = [
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrio un error inesperado",
					"Texto"=>"Tu nombre de usuario no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo (json_encode($alerta));
				exit();
			}

			if(mainModel::verificar_datos("[a-zA-Z0-9\$\@\.\-]{7,100}",$admin_clave)){
				$alerta = [
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrio un error inesperado",
					"Texto"=>"Tu clave no coincide con el formato solicitado.",
					"Tipo"=>"error"
				];
				echo (json_encode($alerta));
				exit();
			}

			$admin_clave = mainModel::encryption($admin_clave);

			if($privilegio < 1 || $privilegio > 3){
				$alerta = [
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrio un error inesperado",
					"Texto"=>"El privilegio no corresponde con un valor valido.",
					"Tipo"=>"error"
				];
				echo (json_encode($alerta));
				exit();
			}

			
			if($estado != "Activa" && $estado != "Deshabilitada"){
		
				$alerta = [
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrio un error inesperado",
					"Texto"=>"El estado no corresponde con un valor valido.",
					"Tipo"=>"error"
				];
			
				echo (json_encode($alerta));
				
				exit();
			}


			if($dni != $campos['usuario_dni']){
				/*---------- Verificamos si el DNI a agregar no se encuentra en la BD ----------*/
				$check_dni = mainModel::ejecutar_consulta_simple("SELECT usuario_dni FROM usuario WHERE usuario_dni = $dni;");
				if($check_dni->rowCount() > 0){
					$alerta = [
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrio un error inesperado",
						"Texto"=>"El DNI ingresado ya se encuentra registrado en el sistema.",
						"Tipo"=>"error"
					];
					echo (json_encode($alerta));
					exit();
				}
			}
		
			if($usuario != $campos['usuario_usuario']){
				
				/*---------- Verificamos si el USUARIO a agregar no se encuentra en la BD ----------*/
				$check_user = mainModel::ejecutar_consulta_simple("SELECT usuario_usuario FROM usuario WHERE usuario_usuario = '$usuario';");
				if($check_user->rowCount() > 0){
					$alerta = [
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrio un error inesperado",
						"Texto"=>"El usuario ingresado ya se encuentra registrado en el sistema.",
						"Tipo"=>"error"
					];
					echo (json_encode($alerta));
					exit();
				}
			}

			if($email != $campos['usuario_email']){
				/*---------- Verificamos si el EMAIL a agregar no se encuentra en la BD ----------*/
				if($email != ""){
					if(filter_var($email,FILTER_VALIDATE_EMAIL)){
						$check_email = mainModel::ejecutar_consulta_simple("SELECT usuario_email FROM usuario WHERE usuario_email = '$email';");
						if($check_email->rowCount() > 0){
							$alerta = [
								"Alerta"=>"simple",
								"Titulo"=>"Ocurrio un error inesperado",
								"Texto"=>"El email ingresado ya se encuentra registrado en el sistema.",
								"Tipo"=>"error"
							];
							echo (json_encode($alerta));
							exit();
						}
					}else{
						$alerta = [
							"Alerta"=>"simple",
							"Titulo"=>"Ocurrio un error inesperado",
							"Texto"=>"Ha ingresado un correo no valido.",
							"Tipo"=>"error"
						];
						echo (json_encode($alerta));
						exit();
					}
				}
			}


			// Comprobando claves

			if($_POST['usuario_clave_nueva_1'] != '' || $_POST['usuario_clave_nueva_2'] != ''){
				if($_POST['usuario_clave_nueva_1'] != $_POST['usuario_clave_nueva_2']){
					$alerta = [
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrio un error inesperado",
						"Texto"=>"Las nuevas claves ingresadas no coinciden",
						"Tipo"=>"error"
					];
					echo (json_encode($alerta));
					exit();
				}else{
					if(mainModel::verificar_datos("[a-zA-Z0-9\$\@\.\-]{7,100}",$_POST['usuario_clave_nueva_1']) && mainModel::verificar_datos("[a-zA-Z0-9\$\@\.\-]{7,100}",$_POST['usuario_clave_nueva_2'])){
						$alerta = [
							"Alerta"=>"simple",
							"Titulo"=>"Ocurrio un error inesperado",
							"Texto"=>"Tu claves no coincide con el formato solicitado.",
							"Tipo"=>"error"
						];
						echo (json_encode($alerta));
						exit();
					}

					$clave = mainModel::encryption($_POST['usuario_clave_nueva_1']);
				}
			}else{
				$clave = $campos['usuario_clave'];
			}

			// Comprobar las credenciales para actualizar datos
			if($tipo_cuenta == "Propia"){
				$check_cuenta = mainModel::ejecutar_consulta_simple("SELECT usuario_id FROM usuario WHERE usuario_usuario = '$admin_usuario' AND usuario_clave = '$admin_clave' AND usuario_id = '$id';");
			}else{
				session_start(['name'=>'SPM']);
				if($_SESSION['privilegio_spm'] != 1){
					$alerta = [
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrio un error inesperado",
						"Texto"=>"No tienes los permisos necesarios para realizar esta operación.",
						"Tipo"=>"error"
					];
					echo (json_encode($alerta));
					exit();
				}

				$check_cuenta = mainModel::ejecutar_consulta_simple("SELECT usuario_id FROM usuario WHERE usuario_usuario = '$admin_usuario' AND usuario_clave = '$admin_clave';");
			}

			if($check_cuenta->rowCount() <= 0){
				$alerta = [
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrio un error inesperado",
					"Texto"=>"Nombre y clave del administrador no validos.",
					"Tipo"=>"error"
				];
				echo (json_encode($alerta));
				exit();
			}

			// Preparando datos para enviarlos al modelo

			$datos_usuario_update = [
				"DNI"=>$dni,
				"Nombre"=>$nombre,
				"Apellido"=>$apellido,
				"Telefono"=>$telefono,
				"Direccion"=>$direccion,
				"Email"=>$email,
				"Usuario"=>$usuario,
				"Clave"=>$clave,
				"Estado"=>$estado,
				"Privilegio"=>$privilegio,
				"ID"=>$id
			];

			if(usuarioModelo::actualizar_usuario_modelo($datos_usuario_update)){

				if($tipo_cuenta=="Propia"){
					session_start(['name'=>'SPM']);
					$_SESSION['nombre_spm']=$nombre;
					$_SESSION['apellido_spm']=$apellido;
					$_SESSION['usuario_spm']=$usuario;
				}

				$alerta = [
					"Alerta"=>"recargar",
					"Titulo"=>"Datos actualizados",
					"Texto"=>"Los datos han sido actualizados con exito.",
					"Tipo"=>"success"
				];
				echo (json_encode($alerta));
				exit();
			}else{
				$alerta = [
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrio un error inesperado",
					"Texto"=>"No se han podido actualizar los datos.",
					"Tipo"=>"error"
				];
				echo (json_encode($alerta));
				exit();
			}
	
		}
	}/* Fin del controlador */
}
?>