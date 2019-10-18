<?php
  
class ConexaoAlpha {
	/*
	private $usuario = 'root';
	private $senha = '';
	private $servidor = 'localhost:3308';
	private $banco = '';
	private $db = 'PortalSenacRS';
	private $link = '';
	*/
	private $usuario = '';
	private $senha = '';
	private $servidor = '';
	private $banco = '';
	private $db = '';
	private $link = '';
	private $url = '';
	
	function __construct() {

		
		$this->url = parse_url(getenv("CLEARDB_DATABASE_URL"));

		$this->usuario = $url["user"];
		$this->senha = $url["pass"];
		$this->servidor = $url["host"];
		$this->banco = '';
		$this->db = substr($url["path"], 1);
		$this->link = '';
		
		/*
		$this->usuario = 'root';
		$this->senha = '';
		$this->servidor = 'localhost';
		$this->banco = '';
		$this->db = 'PortalSenacRS';
		$this->link = '';
		
		$this->link = mysql_connect($this->servidor, $this->usuario, $this->senha) or die ("ERRO de conexão! -> ".mysql_error());
		$this->banco = mysql_select_db($this->db, $this->link) or die ("ERRO ao selecionar o banco! -> ".mysql_error());
		*/

		$this->link = mysqli_connect($this->servidor, $this->usuario, $this->senha, $this->db);

		if (!$this->link) {
		    echo "Error: Unable to connect to MySQL." . PHP_EOL;
		    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
		    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
		    exit;
		}
		
	}
	
	function query($consulta) {
		
		if($resultado = mysqli_query($this->link, $consulta)) {
			return $resultado;
		} else {
			return mysqli_connect_error() . PHP_EOL;
		}
		
	}
	
	function __destruct() {
		
		mysqli_close($this->link);
		
	}
	
}
  

?>