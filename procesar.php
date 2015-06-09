<?php 
session_start();
require("sesion.php");
require("conexion.php");
$conexio = new conexion($_SESSION["servidor"], $_SESSION["compte"], $_SESSION["user"], $_SESSION["password"]);
$error = $conexio->conectar();
if( $error != 0 ){
	$host = $_SERVER['HTTP_HOST'];
	$uri = rtrim(dirname($_SERVER['PHP_SELF']),'/\\');
	$extra = 'index.php?error='.$error;
	header("Location: http://$host$uri/$extra");
}else{
	$destino = "listado.php";
	/* Guardar informacion recibida del formulario */
	if( $_POST["codigo"] == 1 ){
		/* Leer el contador del numero de peticio */
		$referencia = $conexio->consultar("CONTROL", "MESTREPR");		
		/* Guardar los datos */
		if( isset($_POST["1_quidemana"]) ){
			$conexio->proceso_guardar("MESTREPR", $referencia, 1, $_POST["1_quidemana"]);
		}
		if( isset($_POST["1_diapeticio"]) ){
			$conexio->proceso_guardar("MESTREPR", $referencia, 2, $_POST["1_diapeticio"]);
		}
		if( isset($_POST["1_descrip"]) ){
			$conexio->proceso_guardar("MESTREPR", $referencia, 3, $_POST["1_descrip"]);
		}
		if( isset($_POST["1_obs"]) ){
			$conexio->proceso_guardar("MESTREPR", $referencia, 4, $_POST["1_obs"]);
		}
		if( isset($_POST["1_reduccio"]) ){
			$conexio->proceso_guardar("MESTREPR", $referencia, 5, $_POST["1_reduccio"]);
		}
		if( isset($_POST["1_adjuntos"]) ){
			$conexio->proceso_guardar("MESTREPR", $referencia, 6, $_POST["1_adjuntos"]);
		}
		/* Aumentar el contador del numero de peticio */
		$referencia += 1;
		$conexio->proceso_guardar("CONTROL", "MESTREPR", 1, $referencia);
		$conexio->desconectar();
		$destino = "vaciar.php";
	}
	if( $_POST["codigo"] == 2 ){
		/* Leer el numero de peticio */
		$referencia = $_POST["tarea"];		
		/* Guardar los datos */
		if( isset($_POST["2_programador_1"]) ){
			$conexio->proceso_guardar("MESTREPR", $referencia, 7, $_POST["2_programador_1"]);
		}
		if( isset($_POST["2_programador_2"]) ){
			$conexio->proceso_guardar("MESTREPR", $referencia, 8, $_POST["2_programador_2"]);
		}
		if( isset($_POST["2_programes"]) ){
			$conexio->proceso_guardar("MESTREPR", $referencia, 9, $_POST["2_programes"]);
		}
		if( isset($_POST["2_compte"]) ){
			$conexio->proceso_guardar("MESTREPR", $referencia, 10, $_POST["2_compte"]);
		}
		if( isset($_POST["2_fitxers"]) ){
			$conexio->proceso_guardar("MESTREPR", $referencia, 11, $_POST["2_fitxers"]);
		}
		if( isset($_POST["2_procesos"]) ){
			$conexio->proceso_guardar("MESTREPR", $referencia, 12, $_POST["2_procesos"]);
		}
		if( isset($_POST["2_menu"]) ){
			$conexio->proceso_guardar("MESTREPR", $referencia, 13, $_POST["2_menu"]);
		}
		if( isset($_POST["2_temps"]) ){
			$conexio->proceso_guardar("MESTREPR", $referencia, 14, $_POST["2_temps"]);
		}
		if( isset($_POST["2_dificultad"]) ){
			$conexio->proceso_guardar("MESTREPR", $referencia, 15, $_POST["2_dificultad"]);
		}
		if( isset($_POST["2_diainici"]) ){
			$conexio->proceso_guardar("MESTREPR", $referencia, 16, $_POST["2_diainici"]);
		}
		if( isset($_POST["2_diafinal"]) ){
			$conexio->proceso_guardar("MESTREPR", $referencia, 17, $_POST["2_diafinal"]);
		}
		if( isset($_POST["2_diapremes"]) ){
			$conexio->proceso_guardar("MESTREPR", $referencia, 27, $_POST["2_diapremes"]);
		}
		if( isset($_POST["2_proves"]) ){
			$conexio->proceso_guardar("MESTREPR", $referencia, 18, $_POST["2_proves"]);
		}
		if( isset($_POST["2_obs_proves"]) ){
			$conexio->proceso_guardar("MESTREPR", $referencia, 19, $_POST["2_obs_proves"]);
		}
		if( isset($_POST["2_temps_real"]) ){
			$conexio->proceso_guardar("MESTREPR", $referencia, 20, $_POST["2_temps_real"]);
		}
		if( isset($_POST["2_diaacabat"]) ){
			$conexio->proceso_guardar("MESTREPR", $referencia, 21, $_POST["2_diaacabat"]);
		}
		if( isset($_POST["2_reduccio"]) ){
			$conexio->proceso_guardar("MESTREPR", $referencia, 22, $_POST["2_reduccio"]);
		}
		if( isset($_POST["2_obs"]) ){
			$conexio->proceso_guardar("MESTREPR", $referencia, 23, $_POST["2_obs"]);
		}
		if( isset($_POST['3_inventiu']) ){
			$conexio->proceso_guardar("MESTREPR", $referencia, 24, $_POST["3_inventiu"]);
		}
		if( isset($_POST['3_repartiment']) ){
			$conexio->proceso_guardar("MESTREPR", $referencia, 25, $_POST["3_repartiment"]);
		}
		if( isset($_POST['3_seguiment']) ){
			$conexio->proceso_guardar("MESTREPR", $referencia, 26, $_POST["3_seguiment"]);
		}
		$conexio->proceso_guardar("MESTREPR", $referencia, 28, " ");
		$conexio->desconectar();
		$destino = "listado.php";
	}	
	/* Redirigir pagina */
	$host = $_SERVER['HTTP_HOST'];
	$uri = rtrim(dirname($_SERVER['PHP_SELF']),'/\\');	
	header("Location: http://$host$uri/$destino");	
}
?>