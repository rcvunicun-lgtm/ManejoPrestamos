<?php
if($peticionAjax){
	
	require_once SERVERPATH."modelos/itemModelo.php";
		
}else{
	 require_once "./modelos/itemModelo.php";

}

class ItemControlador extends ItemModelo {

       /*-------- Controlador agregar item --------- */
    public function agregar_item_controlador(){
        $codigo = mainModel::limpiar_cadena($_POST['item_codigo_reg']);
        $nombre = mainModel::limpiar_cadena($_POST['item_nombre_reg']);
        $stock = mainModel::limpiar_cadena($_POST['item_stock_reg']);
        $estado = mainModel::limpiar_cadena($_POST['item_estado_reg']);
        $detalle = mainModel::limpiar_cadena($_POST['item_detalle_reg']);

        // Verificar que los campos no esten vacios.
        if($codigo == "" || $nombre == "" || $stock == "" || $estado == "" || $detalle == "")
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
		if(mainModel::verificar_datos("[a-zA-Z0-9 \-]{1,45}",$codigo)){
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"El código no coincide con el formato solicitado",
				"Tipo"=>"error"
			];
			echo (json_encode($alerta));
			exit();
		}

        if(mainModel::verificar_datos("[a-zA-záéíóúÁÉÍÓÚñÑ0-9 ]{1,140}",$nombre)){
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"El nombre no coincide con el formato solicitado",
				"Tipo"=>"error"
			];
			echo (json_encode($alerta));
			exit();
		}

        if(mainModel::verificar_datos("[0-9]{1,9}",$stock)){
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"El valor de stock no coincide con el formato solicitado",
				"Tipo"=>"error"
			];
			echo (json_encode($alerta));
			exit();
		}

        if(mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ \( \) \. \, \# \\ \- ]{1,190}",$detalle)){
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"El valor del detalle no coincide con el formato solicitado",
				"Tipo"=>"error"
			];
			echo (json_encode($alerta));
			exit();
		}

        if($estado != "Habilitado" && $estado != 'Deshabilitado'){
            $alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"El valor del estado no coincide con las opciones esperadas.",
				"Tipo"=>"error"
			];
			echo (json_encode($alerta));
			exit();
        }

        /*---------- Comprobar codigo item registradas ----------*/
        $check_codigo = mainModel::ejecutar_consulta_simple("SELECT item_codigo FROM item WHERE item_codigo = '$codigo';");
        if($check_codigo->rowCount() > 0){
            $alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"El codigo que ha ingresado ya existe en el sistema.",
				"Tipo"=>"error"
			];
			echo (json_encode($alerta));
			exit(); 
        }

        $check_nombre = mainModel::ejecutar_consulta_simple("SELECT item_nombre FROM item WHERE item_nombre = '$nombre';");
        if($check_nombre->rowCount() > 0){
            $alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"El nombre que ha ingresado ya existe en el sistema.",
				"Tipo"=>"error"
			];
			echo (json_encode($alerta));
			exit(); 
        }

        $datos_item_reg = [
            "Codigo" => $codigo,
            "Nombre" => $nombre,
            "Stock" => $stock,
            "Estado" => $estado,
            "Detalle" => $detalle
        ];

        $agregar_item = ItemModelo::agregar_item_modelo($datos_item_reg);
        if($agregar_item->rowCount() == 1){
            $alerta = [
				"Alerta"=>"limpiar",
				"Titulo"=>"Item registrado",
				"Texto"=>"Los datos del ítem fuerón registrado con éxito.",
				"Tipo"=>"success"
			];
        }else{
            $alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"No hemos podido registrar el Item. Por favor inténtelo nuevamente.",
				"Tipo"=>"error"
			];
        }
        echo (json_encode($alerta));
        exit(); 
    } // Fin del controlador

	/*---------- controlador paginar items ----------*/
	public function paginador_item_controlador($pagina, $registros, $privilegio, $url, $busqueda){

		$pagina = mainModel::limpiar_cadena($pagina);
		$registros = mainModel::limpiar_cadena($registros);
		$privilegio = mainModel::limpiar_cadena($privilegio);

		$url = mainModel::limpiar_cadena($url);
		$url = SERVERURL.$url."/";

		$busqueda = mainModel::limpiar_cadena($busqueda);
		$tabla = "";

		$pagina = (isset($pagina) && $pagina > 0)? (int)$pagina : 1;
		$inicio = ($pagina > 0)? (($pagina * $registros) - $registros):0 ;

		if(isset($busqueda) && $busqueda != ""){
			$consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM item WHERE item_codigo LIKE '%$busqueda%' OR item_nombre LIKE '%$busqueda%' ORDER BY item_codigo ASC LIMIT $inicio, $registros;";
		}else{
			$consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM item ORDER BY item_codigo ASC LIMIT $inicio, $registros;";
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
					<th>CÒDIGO</th>
					<th>NOMBRE</th>
					<th>STOCK</th>
					<th>ESTADO</th>
					<th>DETALLE</th>';
					if($privilegio == 1 || $privilegio == 2){
						$tabla .= '<th>ACTUALIZAR</th>';
					}
					if($privilegio == 1){
						$tabla .= '<th>ELIMINAR</th>';
					}
					$tabla .= '</tr>
			</thead>
			<tbody>';

		if($total >= 1 && $pagina <= $Npaginas){
			$contador = $inicio + 1;
			
			$reg_inicio = $inicio+1;

			foreach($datos as $rows){
				$tabla .= '
				<tr class="text-center" >
					<td>'.$contador.'</td>
					<td>'.$rows['item_codigo'].'</td>
					<td>'.$rows['item_nombre'].'</td>
					<td>'.$rows['item_stock'].'</td>
					<td>'.$rows['item_estado'].'</td>
					<td> <button type="button" class="btn btn-info" data-toggle="popover" data-trigger="hover"
							title="'.$rows['item_nombre'].'" data-content="'.$rows['item_detalle'].'">
							<i class="fas fa-info-circle"></i>
						</button>
					</td>';
					if($privilegio == 1 || $privilegio == 2){
						$tabla .= '<td>
							<a href="'.SERVERURL.'item-update/'.mainModel::encryption($rows['item_id']).'/" class="btn btn-success">
								<i class="fas fa-sync-alt"></i>	
							</a>
						</td>';
					}
					if($privilegio == 1){
						$tabla .= '<td>
						
							<!-- ESTE FORMULARIO SE ESTA CONTROLANDO DESDE alertas.js -->
							
							<form class="FormularioAjax" action="'.SERVERURL.'ajax/itemAjax.php" method="post" data-form="delete" autocomplete="off">
								<input type="hidden" name= "item_id_del" value="'.mainModel::encryption($rows['item_id']).'">
								<button type="submit" class="btn btn-warning">
									<i class="far fa-trash-alt"></i>
								</button>
							</form>
						</td>';

					}

				$tabla .='</tr>';
				$contador ++;
			}

			$reg_final = $contador - 1;
		}else{
			if($total >= 1){
				$tabla .= '<tr class="text-center"><td colspan="8"><a href="'.$url.'" class="btn btn-raise btn-primary btn-sm">Haga click acá para recargar el listado.</a></td></tr>';
			}else{	
				$tabla .= '<tr class="text-center"><td colspan="8">No hay registros en el sistema.</td></tr>';
			}
		}

		$tabla .= '</tbody></table></div>';

	
		if($total >= 1 && $pagina <= $Npaginas){

			$tabla .= '<p class="text-right">Mostrando item '.$reg_inicio.' al...'.$reg_final.' de un total de '.$total.'</p>';

			$tabla .= mainModel::paginador_tablas($pagina, $Npaginas, $url, 2);
		}

		return $tabla;

	}/* Fin del controlador */
	
	/*-------- Controlador eliminar item --------- */
	public function eliminar_item_controlador(){
		 // Recibiendo el id
		 $id = mainModel::decryption($_POST['item_id_del']);
		 $id = mainModel::limpiar_cadena($id);

		 // Comprobar la existencia del iten en la BD
		 $check_item = mainModel::ejecutar_consulta_simple("SELECT item_id FROM item where item_id = '$id';");
		 if($check_item ->rowCount() <= 0){
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"El item que intentas eliminar no existe en el sistema.",
				"Tipo"=>"error"
			];
			echo (json_encode($alerta));
			exit();
		 }

		// Comprobar detalles de prestamo
		$check_prestamo= mainModel::ejecutar_consulta_simple("SELECT item_id FROM detalle where item_id = '$id' LIMIT 1;");
		if($check_prestamo ->rowCount() > 0){
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"No podemos eliminar el item debido a que tiene prestamos asociados, recomendamos deshabilitar el item si ya no sera usado en el sistema.",
				"Tipo"=>"error"
			];
			echo (json_encode($alerta));
			exit();
		}

		// Comprobar los privilegios del administrador
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

		$eliminar_item = ItemModelo::eliminar_item_modelo($id);
		if($eliminar_item->rowCount() == 1){
			$alerta = [
				"Alerta"=>"recargar",
				"Titulo"=>"Item eliminado",
				"Texto"=>"El item ha sido eliminado del sistema con éxito.",
				"Tipo"=>"success"
			];
		}else{
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"No hemos podido eliminar el item del sistema, por favor intentelo nuevamente.",
				"Tipo"=>"error"
			];
		}
		echo (json_encode($alerta));
		exit();
	}/* Fin del controlador */

	/*---------- Controlador datos de item ----------*/
	public function datos_item_controlador($tipo, $id){
		$tipo = mainModel::limpiar_cadena($tipo);
		
		$id = mainModel::decryption($id);
		$id = mainModel::limpiar_cadena($id);

		return ItemModelo::datos_item_modelo($tipo, $id);
	} /* Fin Controlador*/

	/*---------- Controlador actualizar item ----------*/
	public function actualizar_item_controlador(){
		// Recuperar el id
		$id = mainModel::decryption($_POST['item_id_up']);
		$id = mainModel::limpiar_cadena($id);

		//Comprobar la existencia del item en la base de datos
		$check_item = mainModel::ejecutar_consulta_simple("SELECT * FROM item WHERE item_id = '$id';");
		if($check_item->rowCount() <= 0){
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"No hemos podido encontrar el item en el sistema.",
				"Tipo"=>"error"
				];
				echo (json_encode($alerta));
				exit();
		}else{
			$campos = $check_item->fetch();
		}
		$codigo = mainModel::limpiar_cadena($_POST['item_codigo_up']);
		$nombre = mainModel::limpiar_cadena($_POST['item_nombre_up']);
		$stock = mainModel::limpiar_cadena($_POST['item_stock_up']);
		$detalle = mainModel::limpiar_cadena($_POST['item_detalle_up']);
		$estado = mainModel::limpiar_cadena($_POST['item_estado_up']);

		/*---------- Comprobar los campos vacios ----------*/
		if($codigo == "" || $nombre == "" || $stock == "" || $detalle == "" || $estado == "")
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
		if(mainModel::verificar_datos("[a-zA-Z0-9 \-]{1,45}",$codigo)){
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"El código no coincide con el formato solicitado",
				"Tipo"=>"error"
			];
			echo (json_encode($alerta));
			exit();
		}

		if(mainModel::verificar_datos("[a-zA-záéíóúÁÉÍÓÚñÑ0-9 ]{1,140}",$nombre)){
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"El nombre no coincide con el formato solicitado",
				"Tipo"=>"error"
			];
			echo (json_encode($alerta));
			exit();
		}

		if(mainModel::verificar_datos("[0-9]{1,9}",$stock)){
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"El valor de stock no coincide con el formato solicitado",
				"Tipo"=>"error"
			];
			echo (json_encode($alerta));
			exit();
		}

		if(mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ \( \) \. \, \# \\ \- ]{1,190}",$detalle)){
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"El valor del detalle no coincide con el formato solicitado",
				"Tipo"=>"error"
			];
			echo (json_encode($alerta));
			exit();
		}

		if($estado != "Habilitado" && $estado != 'Deshabilitado'){
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"El valor del estado no coincide con las opciones esperadas.",
				"Tipo"=>"error"
			];
			echo (json_encode($alerta));
			exit();
		}

		/*---------- Comprobar codigo item registradas ----------*/
		if($codigo != $campos['item_codigo']){
			$check_codigo = mainModel::ejecutar_consulta_simple("SELECT item_codigo FROM item WHERE item_codigo = '$codigo';");
			if($check_codigo->rowCount() > 0){
				$alerta = [
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrio un error inesperado",
					"Texto"=>"El codigo que ha ingresado ya existe en el sistema.",
					"Tipo"=>"error"
				];
				echo (json_encode($alerta));
				exit(); 
			}
		}
		
		/*---------- Comprobar nombre item registrado ----------*/
		if($nombre != $campos['item_nombre']){
			$check_nombre = mainModel::ejecutar_consulta_simple("SELECT item_nombre FROM item WHERE item_nombre = '$nombre';");
			if($check_nombre->rowCount() > 0){
				$alerta = [
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrio un error inesperado",
					"Texto"=>"El nombre que ha ingresado ya existe en el sistema.",
					"Tipo"=>"error"
				];
				echo (json_encode($alerta));
				exit(); 
			}
		}	
		
		// Comprobar los privilegios del administrador
		session_start(['name'=>'SPM']);
		if($_SESSION['privilegio_spm'] < 1 || $_SESSION['privilegio_spm'] > 2){
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"No tienes los permisos necesarios para realizar esta operación.",
				"Tipo"=>"error"
			];
			echo (json_encode($alerta));
			exit();
		}

		$datos_item_up = [
            "Codigo" => $codigo,
            "Nombre" => $nombre,
            "Stock" => $stock,
            "Estado" => $estado,
            "Detalle" => $detalle,
			"ID" => $id
        ];

        $actualizar_item = ItemModelo::actualizar_item_modelo($datos_item_up);
        if($actualizar_item->rowCount() == 1){
            $alerta = [
				"Alerta"=>"recargar",
				"Titulo"=>"Item registrado",
				"Texto"=>"Los datos del ítem fuerón actualizados con éxito.",
				"Tipo"=>"success"
			];
        }else{
            $alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"No hemos podido actualizar los datos del Item. Por favor inténtelo nuevamente.",
				"Tipo"=>"error"
			];
        }
        echo (json_encode($alerta));
        exit(); 
		
	}/* Fin del controlador */
}
?>