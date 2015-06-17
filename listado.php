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
	$opc = $conexio->consultar("MESTREPR.USR",$_SESSION["user"]);
	$opcions = explode(chr(254),$opc);
	$opcions_llistat = explode(chr(253),$opcions[0]);
	$_SESSION['permisos'] = $opcions[0];
	$num_opcions = count($opcions_llistat);
	$visible_llistat = explode(chr(253),$opcions[1]);
	$usuarios = $conexio->consultar("DEV","USR*".strtoupper($_SESSION["user"]));
	$nomusu = explode(chr(254),$usuarios);
	$_SESSION["user.connect"] = $nomusu[0];
	$_SESSION["user.dpto"] = $nomusu[7];
	$_SESSION["user.seccio"] = $nomusu[8];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta content="text/html; charset=iso-8859-1" http-equiv="Content-Type">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Mestre de programes">
<meta name="author" content="Grupo Alimentario Guissona - AGSA">
<link rel="shortcut icon" href="./ico/favicon.ico">
<title>AGSA ::: Programes</title>
<link type="text/css" href="./css/datepicker.min.css" rel="stylesheet">
<link type="text/css" href="./css/bootstrap.min.css" rel="stylesheet">
<link type="text/css" href="./css/bootstrap-theme.min.css" rel="stylesheet">
<!--[if lt IE 9]><script src="./docs/ie8-responsive-file-warning.js"></script><![endif]-->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
<![endif]-->
<script type="text/javascript" language="javascript" src="./js/jquery-2.1.4.min.js"></script>
<script type="text/javascript" language="javascript" src="./js/bootstrap.min.js"></script>
<script type="text/javascript" language="javascript" src="./js/bootstrap-datepicker.js"></script>
<script type="text/javascript" language="javascript" src="./js/hide.address.bar.js"></script>
<script type="text/javascript" language="javascript">
$( document ).ready(function() {
	$('.list-group > li').click(function(){
		var valorApartado = $(this).attr("apartado");
		if( valorApartado != 0 ){
			$("#codigo").attr("value",valorApartado);
			$("#apartado").attr("value",$(this).text());
			if( $(this).attr("apartado") == 7 ){
				//$("#listado").attr("action","procesar.php");
			}else{
				$("#listado").attr("action","preguntas.php");
			}
			$("#listado").submit();
		}
	});
	$("#btnDesconectar").click(function(e){
		e.preventDefault();
		$("#mydesconectar").modal('hide');
		$("#irfinalizar").html("<span class='glyphicon glyphicon-refresh'></span>&nbsp;<b>Desconectando...</b>");
		$(location).attr("href","vaciar.php");
	});
});
</script>
<style>body{padding-top:70px;padding-bottom:30px;}</style>
</head>
<body>
<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	<div class="container">
		<div class="navbar-header">
			<p class="navbar-text"><?= "Usuari: <b>".$_SESSION["user.connect"]."</b> - Departament: <b>".$_SESSION["user.dpto"]."</b> - Seccio: <b>".$_SESSION["user.seccio"]."</b>" ?></p>
		</div>
    	<div class="navbar-collapse collapse">
      		<ul class="nav navbar-nav navbar-right">
        		<li id="modal4b">
        			<button class="btn btn-danger btn-lg" id="irdesconectar" data-toggle="modal" data-target="#mydesconectar"><span class="glyphicon glyphicon-off"></span>&nbsp;Desconectar</button>
        		</li>
      		</ul>
         </div>		
	</div>	
</div>
<div class="container theme-showcase">
	<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-info">
				<div class="panel-heading">
					<h3 class="panel-title"><b>OPCIONS GENERALS</b></h3>
				</div>
				<form id="listado" name="listado" method="post">
				<input type="hidden" id="codigo" name="codigo">
				<input type="hidden" id="apartado" name="apartado">
				</form>
				<div class="panel-body">
					<ul class="list-group">
<?php 
						$_SESSION["modificar_programador"] = "NO";
						$_SESSION["responsable"] = "NO";
						$nm = 0;
						if( $num_opcions > 0 ){
							for($ii=0;$ii<$num_opcions;$ii++){ 
								$valor = $opcions_llistat[$ii];
								$visible = $visible_llistat[$ii]; 
								if( $valor == "5" and $visible == "SI" ){
									$_SESSION["modificar_programador"] = "SI";
								}
								if( $valor == "6" and $visible == "SI" ){
									$_SESSION["responsable"] = "SI";
								}
								$texto = $conexio->consultar("MESTREPR.ZON",$valor);
								if( $texto <> "" and $visible == "SI" and ($valor == 1 or $valor == 8) ){
									echo '<li class="list-group-item" style="cursor:pointer;" apartado="'.$valor.'" id="apart_'.$valor.'" name="apart_'.$valor.'"><b>'.$texto.'</b></li>';
									$nm += 1;
								}
							} 
							if( $nm == 0 ){
								echo '<li class="list-group-item" apartado="0"><b>No tens acces a ninguna opcio.</b></li>';
							}
						}else{
							echo '<li class="list-group-item" apartado="0"><b>No tens acces a ninguna opcio.</b></li>';
						}
?>
					</ul>
				</div>
			</div>
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title"><b>OPCIONS PROGRAMACIO</b></h3>
				</div>
				<form id="listado" name="listado" method="post">
				<input type="hidden" id="codigo" name="codigo">
				<input type="hidden" id="apartado" name="apartado">
				</form>
				<div class="panel-body">
					<ul class="list-group">
<?php 
						$_SESSION["modificar_programador"] = "NO";
						$_SESSION["responsable"] = "NO";
						$nm = 0;
						if( $num_opcions > 0 ){
							for($ii=0;$ii<$num_opcions;$ii++){ 
								$valor = $opcions_llistat[$ii];
								$visible = $visible_llistat[$ii]; 
								if( $valor == "5" and $visible == "SI" ){
									$_SESSION["modificar_programador"] = "SI";
								}
								if( $valor == "6" and $visible == "SI" ){
									$_SESSION["responsable"] = "SI";
								}
								$texto = $conexio->consultar("MESTREPR.ZON",$valor);
								if( $texto <> "" and $visible == "SI" and ($valor == 2 or $valor == 3 or $valor == 4) ){
									echo '<li class="list-group-item" style="cursor:pointer;" apartado="'.$valor.'" id="apart_'.$valor.'" name="apart_'.$valor.'"><b>'.$texto.'</b></li>';
									$nm += 1;
								}
							}
							if( $nm == 0 ){
								echo '<li class="list-group-item" apartado="0"><b>No tens acces a ninguna opcio.</b></li>';
							}
						}else{
							echo '<li class="list-group-item" apartado="0"><b>No tens acces a ninguna opcio.</b></li>';
						}
?>
					</ul>
				</div>
			</div>
			<!--<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title"><b>OPCIONS FITXERS</b></h3>
				</div>
				<form id="listado" name="listado" method="post">
				<input type="hidden" id="codigo" name="codigo">
				<input type="hidden" id="apartado" name="apartado">
				</form>
				<div class="panel-body">
					<ul class="list-group">
< ?php 
						$_SESSION["modificar_programador"] = "NO";
						$_SESSION["responsable"] = "NO";
						$nm = 0;
						if( $num_opcions > 0 ){
							for($ii=0;$ii<$num_opcions;$ii++){ 
								$valor = $opcions_llistat[$ii];
								$visible = $visible_llistat[$ii]; 
								if( $valor == "5" ){
									$_SESSION["modificar_programador"] = "SI";
								}
								if( $valor == "6" ){
									$_SESSION["responsable"] = "SI";
								}
								$texto = $conexio->consultar("MESTREPR.ZON",$valor);
								if( $texto <> "" and $visible == "SI" and $valor == 7 ){
									echo '<li class="list-group-item" style="cursor:pointer;" apartado="'.$valor.'" id="apart_'.$valor.'" name="apart_'.$valor.'"><b>'.$texto.'</b></li>';
									$nm += 1;
								}
							}
							if( $nm == 0 ){
								echo '<li class="list-group-item" apartado="0"><b>No tens acces a ninguna opcio.</b></li>';
							}
						}else{
							echo '<li class="list-group-item" apartado="0"><b>No tens acces a ninguna opcio.</b></li>';
						} 
? >
					</ul>
				</div>
			</div>-->
		</div>
	</div>
	<div class="modal fade" id="mydesconectar">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title text-primary"><b>DESCONECTAR</b></h4>
				</div>
				<div class="modal-body"><label>&iquest;Est&aacute; seguro de desconectar del programa&#63;</label></div>
				<div class="modal-footer">
					<button type="button" class="btn btn-success btn-lg" id="btnDesconectar">&nbsp;&nbsp;&nbsp;SI&nbsp;&nbsp;&nbsp;</button>
					<button type="button" class="btn btn-danger btn-lg" data-dismiss="modal">&nbsp;&nbsp;&nbsp;NO&nbsp;&nbsp;&nbsp;</button>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>