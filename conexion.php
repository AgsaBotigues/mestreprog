<?php
class conexion 
{
	private $con;
	private $subr;	
	private $dbHostName;
	private $dbAccountPath;
	private $userName;
	private $password;

	public function __construct ( $dbHost, $dbAccount, $user, $pass )
	{
    	$this->dbHostName = $dbHost;
    	$this->dbAccountPath = $dbAccount;
    	$this->userName = $user;
    	$this->password = $pass;
  	}

  	/* Conectar con el -SUN- */
	public function conectar()
	{
		$this->con = new com("Uniobjects.Unioaifctrl");
		$this->con->HostName = $this->dbHostName;
		$this->con->AccountPath = $this->dbAccountPath;
		$this->con->UserName = $this->userName;
		$this->con->Password = $this->password;
		$this->con->Connect();
		return $this->con->Error();
	}

	/* Devuelve el buffer de una referencia en concreto */
	public function proceso_guardar( $fichero, $referencia, $posicion, $dades )
	{
		$subr = $this->con->Subroutine("GUARDAR.PHP",4);
		$subr->SetArg(0,$fichero);
		$subr->SetArg(1,$referencia);
		$subr->SetArg(2,$posicion);
		$subr->SetArg(3,$dades);
		$subr->Call;
	}

	/* Devuelve el buffer de una referencia en concreto */
	public function consultar( $fichero, $referencia )
	{
		$subr = $this->con->Subroutine("AUBUFFER",5);
		$subr->SetArg(0,$fichero);
		$subr->SetArg(1,$referencia);
		$subr->SetArg(2,"");
		$subr->SetArg(3,"");
		$subr->Call;
		$contenido = $subr->GetArg(4);
		return $contenido;
	}

	/* Devuelve en un 'array' el buffer de varias referencias */
	public function leer( $fichero, $referencia, $pos, $valor )
	{
		$subr = $this->con->Subroutine("AUBUFFER",5);
		$subr->SetArg(0,$fichero);
		$subr->SetArg(1,"");
		$subr->SetArg(2,$pos);
		$subr->SetArg(3,$valor);
		$subr->Call;
		$contenido = $subr->GetArg(4);
		return $contenido;
	}

	/* Devuelve en un 'array' el buffer de varias referencias */
	public function leer_tareas( $fichero, $tipo_tarea )
	{
		$subr = $this->con->Subroutine("LEER.PHP",3);
		$subr->SetArg(0,$fichero);
		$subr->SetArg(1,$_SESSION["user"]."-".$tipo_tarea."-".$_SESSION["modificar_programador"]); //Para que muestre las de cada usuario o todas si es el 'CAP'
		$subr->Call;
		$contenido = $subr->GetArg(2);
		return $contenido;
	}

	/* Devuelve en un 'array' las referencias de un fichero */
	public function leer_usuarios( $fichero )
	{
		$subr = $this->con->Subroutine("LEER.PHP",3);
		$subr->SetArg(0,$fichero);
		$subr->SetArg(1,"");
		$subr->Call;
		$contenido = $subr->GetArg(2);
		return $contenido;
	}

	/* Desconectar la conexion */
	public function desconectar()
	{
		$this->con->Disconnect();
	}
}
?>