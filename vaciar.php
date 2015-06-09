<?php
session_start();
require("sesion.php");
// Unset all of the session variables.
$_SESSION = array();
// Delete the session cookie.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
}
// Finally, destroy the session.
session_unset();
session_destroy();
// Location header with mistakes.
$host = $_SERVER['HTTP_HOST'];
$uri = rtrim(dirname($_SERVER['PHP_SELF']),'/\\');
$destino = 'index.html';
header("Location: http://$host$uri/$destino");
?>