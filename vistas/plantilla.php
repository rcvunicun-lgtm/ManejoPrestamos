
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <!-- Estilos CSS -->
    <?php include "./vistas/inc/Link.php"; ?>
    <title><?php echo(COMPANY) ?></title>

</head>

<body>
	<?php 

		$peticionAjax = false;

		// require_once "D:/Instalados/Xampp/htdocs/Prestamos/controladores/vistasControlador.php"; 
		// require_once $_SERVER['DOCUMENT_ROOT'] . '/Prestamos/controladores/vistasControlador.php';
		// require_once './controladores/vistasControlador.php';

		$IV = new vistasControlador();	
		
		$vistas = $IV->obtener_vistas_controlador();
				
		if($vistas == "login" || $vistas == "404" ){
		
			// require_once "D:/Instalados/Xampp/htdocs/Prestamos/vistas/contenidos/".$vistas."-view.php"; 
			// require_once $_SERVER['DOCUMENT_ROOT'] . '/Prestamos/vistas/contenidos/'.$vistas.'-view.php';
			// require_once "../Prestamos/vistas/contenidos/".$vistas."-view.php";
			require_once "./vistas/contenidos/".$vistas."-view.php";

		}else{
	
		session_start(['name'=>'SPM']);

		$pagina = explode("/",$_GET['views']);

		require_once "./controladores/loginControlador.php";

		$login_controller = new loginControlador();

		if(!isset($_SESSION['token_spm']) || !isset($_SESSION['usuario_spm']) || !isset($_SESSION['privilegio_spm']) || !isset($_SESSION['id_spm'])){
			echo($login_controller->forzar_cierre_sesion_controlador()); //Retorna la localizaciòn del login
			exit();
		}
		
	?>
	<!-- Main container -->
	<main class="full-box main-container">
		
		<!-- Nav lateral -->
        <?php  include "./vistas/inc/NavLateral.php";?>

		<!-- Page content -->
		<section class="full-box page-content">

            <!-- Nav Bar -->
            <?php include "./vistas/inc/NavBar.php" ?>

			<!-- Contenido de la pagina seleccionada -->
			<?php include $vistas ?>
			
		</section>
	</main>
	<?php

		// Incluye el script para cerrar la sesion
		include SERVERPATH."vistas/inc/LogOut.php";
		}
		
	?>

	 <!-- Scripts -->
     <?php include "./vistas/inc/Script.php" ?>

</body>

</html>