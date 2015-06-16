<?php
if( !(isset($_SESSION['user']) && $_SESSION['user'] != '') || 
	!(isset($_SESSION['password']) && $_SESSION['password'] != '') ){
		$host = $_SERVER['HTTP_HOST'];
		$uri = rtrim(dirname($_SERVER['PHP_SELF']),'/\\');
		$extra = 'index.html?error='.$error;
		header("Location: http://$host$uri/$extra");
}
?>