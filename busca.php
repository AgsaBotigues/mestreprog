<?php
session_start();
require("sesion.php");
require("conexion.php");
$conexio = new conexion($_SESSION["servidor"], $_SESSION["compte"], $_SESSION["user"], $_SESSION["password"]);
$error = $conexio->conectar();
if( $error != 0 ){
	$host = $_SERVER['HTTP_HOST'];
	$uri = rtrim(dirname($_SERVER['PHP_SELF']),'/\\');
	$extra = 'index.html?error='.$error;
	header("Location: http://$host$uri/$extra");
}else{
	$fitxer = $_POST['fitxer'];
	if( !(isset($fitxer)) ){
		$host = $_SERVER['HTTP_HOST'];
		$uri = rtrim(dirname($_SERVER['PHP_SELF']),'/\\');
		$extra = 'listado.php';
		header("Location: http://$host$uri/$extra");
	}else{
		$texto = $conexio->consultar("MESTREPR.FITXERS",$fitxer);
		echo $texto;
		return $texto;		
	}	
}	
?>