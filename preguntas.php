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
	$codigo = $_POST['codigo'];
	$apartado = $_POST['apartado'];
	$fechahoy = explode("-",date("d-m-y"))[0]."-".explode("-",date("d-m-y"))[1]."-".(2000+explode("-",date("d-m-y"))[2]);
	if( !(isset($apartado)) and !(isset($pregunta)) ){
		$host = $_SERVER['HTTP_HOST'];
		$uri = rtrim(dirname($_SERVER['PHP_SELF']),'/\\');
		$extra = 'listado.php';
		header("Location: http://$host$uri/$extra");
	}
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
$( document ).ready(function(){
	/* Ocultar todas las opcions */ 
	$("#apart_1").hide();
	$("#apart_2").hide();
	/* Hacer visible la que se reciba por parametro */
	if( $("#codigo").val() == 2 || $("#codigo").val() == 3 || $("#codigo").val() == 4 ){
		$("#apart_2").show();
	}else{
		$("#apart_"+$("#codigo").val()).show();
	}
	/* Ini Calendario */
	window.prettyPrint && prettyPrint();
	$("#1_diapeticio").datepicker({format:"dd-mm-yyyy",weekStart:1});
	$("#1_diapeticio").datepicker("setValue", $("#actual").val());
	$('#1_diapeticio').prop('tabIndex',-1);
	/* Fin Calendario */
	$("#irlistado").click(function(e){
		e.preventDefault();
		$(location).attr("href","listado.php");
	});
	$("#btnGuardar").click(function(e){
		e.preventDefault();
		//* Ocultar emergente -Guardar-
		$("#myguardar").modal("hide");
		/* Comprobar formulario */
		if( $("#codigo").val() == "1" ){
			if( $("#1_quidemana").val()=="" || $("#1_diapeticio").val()=="" || $("#1_descrip").val()=="" || $("#1_obs").val()=="" || $("#1_reduccio").val()==""  ){
				$("#1_quidemana").val()=="" ? $("#txt_1").addClass("has-error") : $("#txt_1").removeClass("has-error");
				$("#1_diapeticio").val()=="" ? $("#txt_2").addClass("has-error") : $("#txt_2").removeClass("has-error");
				$("#1_descrip").val()=="" ? $("#txt_3").addClass("has-error") : $("#txt_3").removeClass("has-error");
				$("#1_obs").val()=="" ? $("#txt_4").addClass("has-error") : $("#txt_4").removeClass("has-error");
				$("#1_reduccio").val()=="" ? $("#txt_5").addClass("has-error") : $("#txt_5").removeClass("has-error");
			}else{
				//* 1. Quitar errores	
				$("#txt_1").removeClass("has-error");
				$("#txt_2").removeClass("has-error");
				$("#txt_3").removeClass("has-error");
				$("#txt_4").removeClass("has-error");
				$("#txt_5").removeClass("has-error");		
				//* 2. Ocultar botones
				$("#irresetear").hide();
				$("#irlistado").hide();
				//* 3. Mandar formulario para guardar en Universe
				$("#irguardar").html("<span class='glyphicon glyphicon-refresh'></span>&nbsp;<b>Guardando...</b>");
				$("#preguntas").attr("method","post");
				$("#preguntas").attr("action","procesar.php");
				$("#preguntas").submit();
			}
		}
	});
	$('.list-group > li').click(function(){
		var nom = $(this).attr("id");
     	var key = nom.substr(6,((nom.length)-6));
     	$("#tarea").attr("value",key);
		$("#preguntas").attr("method","post");
		$("#preguntas").attr("action","tareas.php");
		$("#preguntas").submit();
	});
});
$( window ).load(function() {
	$("#titulo").html("<h3 class='panel-title'><b>" + $("#apartado").val() + "</b></h3>");
	/* Si el apartado es de programacio, ocultar el boton de guardar */
	if( $("#codigo").val() == 2 ){
		$("#modal4").hide();
	}
});
</script>
<style rel="stylesheet">body{padding-top: 70px;padding-bottom: 30px;}</style>
</head>
<body>
<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	<div class="container">
		<div class="navbar-header">
			<p class="navbar-text"><?= "Usuari: <b>".$_SESSION["user.connect"]."</b> - Departament: <b>".$_SESSION["user.dpto"]."</b> - Seccio: <b>".$_SESSION["user.seccio"]."</b>" ?></p>
		</div>
    	<div class="navbar-collapse collapse">
      		<ul class="nav navbar-nav navbar-right"> 
	        	<li id="modal0">
	        		<button class="btn btn-primary btn-lg" id="irlistado"><span class="glyphicon glyphicon-home"></span>&nbsp;Menu</button>
	        	</li>
	        	<li>&nbsp;</li>
        		<li id="modal4">
        			<button class="btn btn-success btn-lg" id="irguardar" data-toggle="modal" data-target="#myguardar"><span class="glyphicon glyphicon-floppy-disk"></span>&nbsp;Guardar</button>
        		</li>
      		</ul>
         </div>
	</div>
</div>
<form name="preguntas" id="preguntas">
<input type="hidden" name="codigo" id="codigo" value="<?=$codigo?>">
<input type="hidden" name="apartado" id="apartado" value="<?=$apartado?>">
<input type="hidden" name="tarea" id="tarea">
</form>
<div class="container theme-showcase">
	<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-primary">
				<div class="panel-heading" id="titulo">
				</div>
				<div class="panel-body" id="apart_1">
					<div class="input-group" id="txt_1">
						<span class="input-group-addon"><b>Qui ho demana</b></span>
						<input type="text" class="form-control" placeholder="" name="1_quidemana" id="1_quidemana" tabindex="1">
					</div><br>
					<div class="input-group" id="txt_2">
						<span class="input-group-addon"><b>Dia Peticio</b></span>
						<input type="hidden" id="actual" name="actual" value="<?=$fechahoy?>">
						<input type="text" class="form-control" data-date="today" name="1_diapeticio" id="1_diapeticio" readonly tabindex="-1">
					</div><br>
					<div class="input-group" id="txt_3">
						<span class="input-group-addon"><b>Que ha de fer el programa</b></span>
						<textarea class="form-control" rows="4" id="1_descrip" name="1_descrip" tabindex="2"></textarea>
					</div><br>
					<div class="input-group" id="txt_4">
						<span class="input-group-addon"><b>Explicacions / Observacions</b></span>
						<textarea class="form-control" rows="4" id="1_obs" name="1_obs" tabindex="3"></textarea>
					</div><br>
					<div class="input-group" id="txt_5">
						<span class="input-group-addon"><b>Reduccio despeses</b></span>
						<textarea class="form-control" rows="2" id="1_reduccio" name="1_reduccio" tabindex="4"></textarea>
					</div><br>
					<div class="input-group" id="txt_6">
						<span class="input-group-addon"><b>Documents adjunts i/o exemples</b></span>
						<textarea class="form-control" rows="2" id="1_adjuntos" name="1_adjuntos" tabindex="5"></textarea>
					</div>
				</div>
				<div class="panel-body" id="apart_2">
					<ul class="list-group">
<?php
					$tareas = $conexio->leer_tareas("MESTREPR",$codigo);
					$array_opcions = explode(chr(253),$tareas);
					$num_opcions = count($array_opcions);
					if( $num_opcions <= 0 or $array_opcions[0] == "" ){
						 echo '<li class="list-group-item"><b>No hi ha registres.</b></li>';
					}else{	
						for($ii=0;$ii<$num_opcions;$ii++){ 
							$tarea = $conexio->consultar("MESTREPR",$array_opcions[$ii]);
							if( $tarea <> "" ){
								$nref = $array_opcions[$ii];
								$array = explode(chr(254),$tarea);							
								echo '<li class="list-group-item" id="tarea_'.$nref.'" name="tarea_'.$nref.'">';
								echo '- Num. Peticio: <b>'.$array_opcions[$ii].'</b><br>- Realizat per: <b>'.$array[0].'</b><br>- Data: <b>'.$array[1].'</b><br>';
								echo '- Tarea: <b>'.$array[2].'</b>';
								echo '</li>';
							}
						}
					}
?>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="myguardar">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title text-primary"><b>GUARDAR APARTADO</b></h4>
				</div>
				<div class="modal-body"><label>&iquest;Est&aacute; seguro de guardar este apartado&#63;</label></div>
				<div class="modal-footer">
					<button type="button" class="btn btn-success btn-lg" id="btnGuardar">&nbsp;&nbsp;&nbsp;SI&nbsp;&nbsp;&nbsp;</button>
					<button type="button" class="btn btn-danger btn-lg" data-dismiss="modal">&nbsp;&nbsp;&nbsp;NO&nbsp;&nbsp;&nbsp;</button>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>