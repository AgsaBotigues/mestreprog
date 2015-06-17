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
	$tarea = $_POST['tarea'];
	if( !(isset($apartado)) and !(isset($pregunta)) and !(isset($tarea)) ){
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
	/* Ini Calendarios */
	window.prettyPrint && prettyPrint();
	$("#2_diainici").datepicker({format:"dd-mm-yyyy",weekStart:1});
	if( $("#inici").val() != "" )
	{
		$("#2_diainici").datepicker("setValue", $("#inici").val());
	}
	$("#2_diafinal").datepicker({format:"dd-mm-yyyy",weekStart:1});
	if( $("#final").val() != "" )
	{
		$("#2_diafinal").datepicker("setValue", $("#final").val());
	}
	$("#2_diaacabat").datepicker({format:"dd-mm-yyyy",weekStart:1});
	if( $("#acabat").val() != "" )
	{
		$("#2_diaacabat").datepicker("setValue", $("#acabat").val());
	}
	$("#2_diapremes").datepicker({format:"dd-mm-yyyy",weekStart:1});
	if( $("#premes").val() != "" )
	{
		$("#2_diapremes").datepicker("setValue", $("#premes").val());
	}
	/* Fin Calendarios */
	$("#2_fitxers").select(function(){
		if(document.selection){ //IE
        	selectedText = document.selection.createRange().text;
        } else{
        	selectedText = window.getSelection().toString();
        }		
    	//selectedText = document.getSelection();
		//alert("Texto seleccionado: "+selectedText);
    	//$("#resultado").html(selectedText);
    });
	$("#irlistado").click(function(e){
		e.preventDefault();
		$("#tareas").attr("method","post");
		$("#tareas").attr("action","preguntas.php");
		$("#tareas").submit();
	});
	$("#btnGuardar").click(function(e){
		e.preventDefault();
		//* Ocultar emergente -Guardar-
		$("#myguardar").modal("hide");
		//* 2. Ocultar botones
		$("#irlistado").hide();
		//* 3. Mandar formulario para guardar en Universe
		$("#irguardar").html("<span class='glyphicon glyphicon-refresh'></span>&nbsp;<b>Guardando...</b>");
		$("#tareas").attr("method","post");
		$("#tareas").attr("action","procesar.php");
		$("#tareas").submit();
	});
	/* *********** */	
	/*
	if(!window.Fitxer){
		Fitxer = {};
	}	
	Fitxer.Selector = {};
	//* Seleccionar texto 
	Fitxer.Selector.getSelected = function(){
		var texto = '';
		if(window.getSelection){
			texto = window.getSelection();
		}else if(document.getSelection){
			texto = document.getSelection();
		}else if(document.selection){
			texto = document.selection.createRange().text;
		}
		return texto;
	}
	//* DesSeleccionar texto 
	Fitxer.Selector.getDesSelected = function(){
		if (document.selection)
			document.selection.empty();
		else if (window.getSelection)
			window.getSelection().removeAllRanges();
		textoSeleccionado = "";
	}
	//* Buscar contenido del Fichero Seleccionado y después Quitar seleccion 
	Fitxer.Selector.mouseup = function(){
		var textoSeleccionado = Fitxer.Selector.getSelected();
		if( textoSeleccionado != "" ){
			//* Comprobar por ajax que existe el fichero 
			$.ajax({
  				url: "busca.php",
  				data: "fitxer="+textoSeleccionado,
  				type: "post",
  				success: function(json) {
					 if( json == "tancar" ){
					 }else{ 
						if( json == "" || json == undefined ){
							alert("El fichero ["+textoSeleccionado+"] no tiene desglose creado.");
						}else{
							$("#contenido").html(json);
							$("#mydesglossament").modal("show");
							json = "tancar";
						}
					 }					
  				},
  				error:function (xhr, ajaxOptions, thrownError) {
    				alert("Error en el proceso de busqueda. Vuelva a intentarlo más tarde.");
  				}
			});
		}
	}
	$(document).bind("mouseup", Fitxer.Selector.mouseup);
	$("#btnTancarDesgl").click(function(e){
		e.preventDefault();
		//* Ocultar emergente -Guardar-
		$("#mydesglossament").modal("hide");
		//* Quitar Seleccion
		Fitxer.Selector.getDesSelected();
	});
	*/
});
$( window ).load(function() {
	/* Si el codigo es 8, ocultar boton guardar */
	if( $("#codigo").val() == 8 ){
		$("#irguardar").hide();
		$("#ompliresp").hide();
	}
	/* Si son tareas acabats no permeter modificar dades */
	if( $("#codigo").val() == 4 ){
		$("#irguardar").hide();
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
	        		<button class="btn btn-primary btn-lg" id="irlistado"><span class="glyphicon glyphicon-list"></span>&nbsp;Llistat Tasques</button>
	        	</li>
	        	<li>&nbsp;</li>
        		<li id="modal4">
        			<button class="btn btn-success btn-lg" id="irguardar" data-toggle="modal" data-target="#myguardar"><span class="glyphicon glyphicon-floppy-disk"></span>&nbsp;Guardar</button>
        		</li>
      		</ul>
         </div>
	</div>
</div>
<form name="tareas" id="tareas">
<input type="hidden" name="codigo" id="codigo" value="<?=$codigo?>">
<input type="hidden" name="apartado" id="apartado" value="<?=$apartado?>">
<input type="hidden" name="tarea" id="tarea" value="<?=$tarea?>">
<div class="container theme-showcase">
	<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-info">
				<div class="panel-heading" id="titulo"><b>N&uacute;mero de Peticio: <?=$tarea?></b></div>
				<div class="panel-body">
<?php
				$tar = $conexio->consultar("MESTREPR",$tarea);
				if( $tar <> "" ){
					$array_opcions = explode(chr(254),$tar);
					
					$programador1 = "";
					$programador2 = "";
					$programes = "";
					$compte = "";
					$fitxers = "";
					$procesos = "";
					$menu = "";
					$temps = "";
					$dificultad = "";
					$diainici = "";
					$diafinal = "";
					$diapremes = "";
					$proves = "";
					$obs_proves = "";
					$temps_real = "";
					$diaacabat = "";
					$reduccio = "";
					$obs = "";
					$inventiu = "";
					$repartiment = "";
					$seguiment = "";
					
					for($ii=0;$ii<26;$ii++){ 					
						if( array_key_exists($ii,$array_opcions) ){
							echo $ii." ".$array_opcions[$ii]."<br>";
							$ii==6?$programador1=$array_opcions[6]:"";
							$ii==7?$programador2=$array_opcions[7]:"";
							$ii==8?$programes=$array_opcions[8]:"";
							$ii==9?$compte=$array_opcions[9]:"";
							$ii==10?$fitxers=$array_opcions[10]:"";
							$ii==10?$procesos=$array_opcions[11]:"";
							$ii==11?$menu=$array_opcions[12]:"";
							$ii==13?$temps=$array_opcions[13]:"";
							$ii==14?$dificultad=$array_opcions[14]:"";
							$ii==14?$diainici=$array_opcions[14]:"";
							$ii==15?$diafinal=$array_opcions[15]:"";
							$ii==16?$diapremes=$array_opcions[16]:"";
							$ii==17?$proves=$array_opcions[17]:"";
							$ii==18?$obs_proves=$array_opcions[18]:"";
							$ii==19?$temps_real=$array_opcions[19]:"";
							$ii==20?$diaacabat=$array_opcions[20]:"";
							$ii==21?$reduccio=$array_opcions[21]:"";
							$ii==22?$obs=$array_opcions[22]:"";
							$ii==23?$inventiu=$array_opcions[23]:"";
							$ii==24?$repartiment=$array_opcions[24]:"";
							$ii==25?$seguiment=$array_opcions[25]:"";
						}
					}					
?>
					<div class="input-group">
						<span class="input-group-addon"><b>Qui ho demana</b></span>
						<input type="text" class="form-control" readonly tabindex="-1" value="<?=$array_opcions[0]?>">
					</div><br>
					<div class="input-group">
						<span class="input-group-addon"><b>Dia Peticio</b></span>
						<input type="text" class="form-control" readonly tabindex="-1" value="<?=$array_opcions[1]?>">
					</div><br>
					<div class="input-group">
						<span class="input-group-addon"><b>Que ha de fer el programa</b></span>
						<textarea rows="3" class="form-control" readonly tabindex="-1"><?=$array_opcions[2]?></textarea>
					</div><br>
					<div class="input-group">
						<span class="input-group-addon"><b>Explicacions / Observacions</b></span>
						<textarea rows="3" class="form-control" readonly tabindex="-1"><?=$array_opcions[3]?></textarea>
					</div><br>
					<div class="input-group">
						<span class="input-group-addon"><b>Reduccio despeses</b></span>
						<textarea rows="3" class="form-control" readonly tabindex="-1"><?=$array_opcions[4]?></textarea>
					</div><br>
					<div class="input-group">
						<span class="input-group-addon"><b>Documents adjunts i/o exemples</b></span>
						<textarea rows="3" class="form-control" readonly tabindex="-1"><?=$array_opcions[5]?></textarea>
					</div>
				</div>
			</div>
			<div class="panel panel-primary">
				<div class="panel-heading" id="titulo"><b>Omplir per el programador</b></div>
				<div class="panel-body">
					<div class="input-group">
						<span class="input-group-addon"><b>Programador 1</b></span>
						<select class="form-control" id="2_programador_1" name="2_programador_1" tabindex="1" <?php if($_SESSION["modificar_programador"] == "NO"){ echo "disabled"; }?> >
							<option value=""></option>
<?php
					$usr = $conexio->leer_usuarios("MESTREPR.USR");
					if( $usr <> "" ){
						$partes = explode(chr(254),$usr);
						$partes0 = explode(chr(253),$partes[0]);
						$partes1 = explode(chr(253),$partes[1]);
						$nopc = count($partes1);
						for($ii=0;$ii<$nopc;$ii++){
							if( !empty($programador1) ){ 
								echo '<option value="'.$partes0[$ii].'"'.($partes0[$ii]==$programador1?"selected":"").'>'.$partes1[$ii].'</option>';
							}else{
								echo '<option value="'.$partes0[$ii].'">'.$partes1[$ii].'</option>';
							}
						}
					}					
?>
						</select>
					</div><br>
					<div class="input-group">
						<span class="input-group-addon"><b>Programador 2</b></span>
						<select class="form-control" id="2_programador_2" name="2_programador_2" tabindex="2" <?php if($_SESSION["modificar_programador"] == "NO"){ echo "disabled"; }?> >
							<option value=""></option>
<?php
					$usr = $conexio->leer_usuarios("MESTREPR.USR");
					if( $usr <> "" ){
						$partes = explode(chr(254),$usr);
						$partes0 = explode(chr(253),$partes[0]);
						$partes1 = explode(chr(253),$partes[1]);
						$nopc = count($partes1);						
						for($ii=0;$ii<$nopc;$ii++){ 
							if( !empty($programador1) ){ 
								echo '<option value="'.$partes0[$ii].'"'.($partes0[$ii]==$programador2?"selected":"").'>'.$partes1[$ii].'</option>';
							}else{
								echo '<option value="'.$partes0[$ii].'">'.$partes1[$ii].'</option>';								
							}
						}
					}
?>
						</select>
					</div><br>
					<div class="input-group">
						<span class="input-group-addon"><b>Programes</b></span>
						<input type="text" class="form-control" id="2_programes" name="2_programes" tabindex="3" value="<?=$programes?>">
					</div><br>
					<div class="input-group">
						<span class="input-group-addon"><b>Compte on esta</b></span>
						<input type="text" class="form-control" id="2_compte" name="2_compte" tabindex="4" value="<?=$compte?>">
					</div><br>
					<?php if( !empty($fitxers) and 1==0 ){ ?>
					<div class="input-group">
						<p tabindex="-1"><b>Seleccioneu un Fitxer per Veure el seu desglossament: </b><?=$fitxers?></p>						
					</div><br>
					<?php } ?>
					<div class="input-group">
						<span class="input-group-addon"><b>Fitxers</b></span>
						<input type="text" class="form-control" id="2_fitxers" name="2_fitxers" tabindex="5" value="<?=$fitxers?>">						
					</div><br>
					<div class="input-group">
						<span class="input-group-addon"><b>Procesos</b></span>
						<input type="text" class="form-control" id="2_procesos" name="2_procesos" tabindex="6" value="<?=$procesos?>">
					</div><br>
					<div class="input-group">
						<span class="input-group-addon"><b>Menu</b></span>
						<input type="text" class="form-control" id="2_menu" name="2_menu" tabindex="7" value="<?=$menu?>">
					</div><br>
					<div class="input-group">
						<span class="input-group-addon"><b>Temps previst</b></span>
						<input type="text" class="form-control" id="2_temps" name="2_temps" tabindex="8" value="<?=$temps?>">
					</div><br>
					<div class="input-group">
						<span class="input-group-addon"><b>Dificultat (0 a 5)</b></span>
						<input type="text" class="form-control" id="2_dificultad" name="2_dificultad" tabindex="9" value="<?=$dificultad?>">
					</div><br>
					<div class="input-group">
						<span class="input-group-addon"><b>Dia inici</b></span>
						<input type="hidden" id="inici" name="inici" value="<?= $diainici?>">
						<input type="text" class="form-control" style="cursor:pointer;" data-date="today" name="2_diainici" id="2_diainici" readonly tabindex="-1">
					</div><br>
					<div class="input-group">
						<span class="input-group-addon"><b>Dia final</b></span>
						<input type="hidden" id="final" name="final" value="<?= $diafinal?>">
						<input type="text" class="form-control" style="cursor:pointer;" data-date="today" name="2_diafinal" id="2_diafinal" readonly tabindex="-1">
					</div><br>
					<div class="input-group has-error">
						<span class="input-group-addon"><b>Dia final acordado</b></span>
						<input type="hidden" id="premes" name="premes" value="<?= $diapremes?>">
						<input type="text" class="form-control" style="cursor:pointer;" data-date="today" name="2_diapremes" id="2_diapremes" readonly tabindex="-1">
					</div><br>
					<div class="input-group">
						<span class="input-group-addon"><b>Proves</b></span>
						<input type="text" class="form-control" id="2_proves" name="2_proves" tabindex="12" value="<?=$proves?>">
					</div><br>
					<div class="input-group">
						<span class="input-group-addon"><b>Observacions Proves</b></span>
						<textarea rows="3" class="form-control" id="2_obs_proves" name="2_obs_proves" tabindex="13"><?=$obs_proves?></textarea>
					</div><br>
					<div class="input-group">
						<span class="input-group-addon"><b>Temps real</b></span>
						<input type="text" class="form-control" id="2_temps_real" name="2_temps_real" tabindex="14" value="<?=$temps_real?>">
					</div><br>
					<div class="input-group">
						<span class="input-group-addon"><b>Dia acabat i posat en marxa</b></span>
						<input type="hidden" id="acabat" name="acabat" value="<?= $diaacabat?>">
						<input type="text" class="form-control" style="cursor:pointer;" data-date="today" name="2_diaacabat" id="2_diaacabat" readonly tabindex="-1">
					</div><br>
					<div class="input-group">
						<span class="input-group-addon"><b>Reduccio despeses</b></span>
						<input type="text" class="form-control" id="2_reduccio" name="2_reduccio" tabindex="15" value="<?=$reduccio?>">
					</div><br>
					<div class="input-group">
						<span class="input-group-addon"><b>Observacions</b></span>
						<textarea rows="3" class="form-control" id="2_obs" name="2_obs" tabindex="16"><?=$obs?></textarea>
					</div>
				</div>
			</div>
<?php
					if( $_SESSION["responsable"] == "SI" ){
?>
			<div class="panel panel-danger" id="ompliresp">
				<div class="panel-heading" id="titulo"><b>Omplir per el responsable</b></div>
				<div class="panel-body">
					<div class="input-group" id="txt_1">
						<span class="input-group-addon"><b>Incentiu &euro;</b></span>
						<input type="text" class="form-control" placeholder="(Introduir nomes el import)" name="3_inventiu" id="3_inventiu" tabindex="17" value="<?=$inventiu?>">
					</div><br>
					<div class="input-group" id="txt_1">
						<span class="input-group-addon"><b>Repartiment &#37;</b></span>
						<input type="text" class="form-control" placeholder="(Introduir nomes el import)" name="3_repartiment" id="3_repartiment" tabindex="18" value="<?=$repartiment?>">
					</div><br>
					<div class="input-group" id="txt_1">
						<span class="input-group-addon"><b>Seguiment</b></span>
						<input type="text" class="form-control" placeholder="" name="3_seguiment" id="3_seguiment" tabindex="19" value="<?=$seguiment?>">
					</div>
				</div>
			</div>
<?php
					}
				}
?>
		</div>
	</div>
	<div class="modal fade" id="myguardar">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title text-primary"><b>GUARDAR TAREA</b></h4>
				</div>
				<div class="modal-body"><label>&iquest;Est&aacute; seguro de guardar aquesta tarea&#63;</label></div>
				<div class="modal-footer">
					<button type="button" class="btn btn-success btn-lg" id="btnGuardar">&nbsp;&nbsp;&nbsp;SI&nbsp;&nbsp;&nbsp;</button>
					<button type="button" class="btn btn-danger btn-lg" data-dismiss="modal">&nbsp;&nbsp;&nbsp;NO&nbsp;&nbsp;&nbsp;</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="mydesglossament">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title text-primary"><b>DESGLOSSAMENT FITXER</b></h4>
				</div>
				<div class="modal-body">
					<label id="contenido"></label>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger btn-lg" id="btnTancarDesgl">&nbsp;&nbsp;&nbsp;TANCAR&nbsp;&nbsp;&nbsp;</button>
				</div>
			</div>
		</div>
	</div>
</div>
</form>
</body>
</html>