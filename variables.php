<?php
session_start();
session_regenerate_id();
require("sesion.php");
/* Rellenar varaibles de sesion */
$_SESSION["servidor"] = "10.0.0.1"; /* cag4.cag.es */
$_SESSION["compte"] = "EAGSA";
$_SESSION["user"] = $_POST["user"];
$_SESSION["password"] = $_POST["pass"];
$_SESSION["permisos"] = "";
$_SESSION["modificar_programador"] = "NO";
$_SESSION["responsable"] = "NO";
$_SESSION["user.connect"] = "";
$_SESSION["user.dpto"] = "";
$_SESSION["user.seccio"] = "";
/* Redirect a listado.php para elegir el tipo de auditoria */
$host = $_SERVER["HTTP_HOST"];
$uri = rtrim(dirname($_SERVER['PHP_SELF']),'/\\');
$extra = "listado.php";
header("Location: http://$host$uri/$extra");
?>