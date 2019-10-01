<?php
require_once('model.php');
require_once('usuarios.php');

$usuario = new Usuario();
$unidadeUsuario = $usuario->verificaUnidadeUsuario();

if($unidadeUsuario != 'AM') {
	echo '<h1>Você não é da Assessoria de Marketing!</h1>';
} else {
	echo '<h1>Tudo certo, você é da AMKT :)</h1>';
}


$model = new Model();

//$consulta = $conexao->query("SELECT * FROM CUR_cursos WHERE codigocursoproduto = 44929 AND (codigounidade = 83) AND (cursomercado = 1)");
$resultado = $model->listaResultados();

for($i = 0; $i < count($resultado); $i++) {
	echo '<p>'.$resultado[$i]['codigocursoproduto'].' - '.$resultado[$i]['nomecursoproduto'].'</p>';
}

//var_dump($resultado[0]);
  

?>