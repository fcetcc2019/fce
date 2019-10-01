<?php
include("../connection_aries.php");
include("../connection.php");

if(isset($_GET['id']) && !empty($_GET['id'])) {
	$sql = "SELECT     e.titulo, p.pergunta, r.resposta, e.unidade,
			rc.data, rco.valor, rc.nome, rc.ddd, rc.telefone, rc.dddcelular, rc.celular, rc.email
		FROM         CAD_enquete_respostacliente rc
		inner join CAD_enquete_resposta r on r.id = rc.id_resposta
		inner join CAD_enquete_pergunta p on p.id = r.id_pergunta
		inner join CAD_enquete e on e.id = p.id_enquete
		left join CAD_enquete_contatocliente ecc on ecc.id_resposta = r.id and ecc.id_respostacliente = rc.id
		left join CAD_enquete_respostacliente_outros rco on rco.id_resposta = r.id and rco.id_respostacliente = rc.id
		WHERE e.id = ".$_GET['id']."
		order by
			rc.data desc";
			
	$query = mssql_query($sql, $db);
	$resposta_cliente = "";
	$resposta = "";
	$tabela_listagem = '';
	$th_resposta = "<th>RESPOSTA</th>";
	$th_respostacliente = "<th>RESPOSTA CLIENTE</th>";
	$tabela_header = '';
	$mostra_agrupado = true;
	$total = 0;
	
	while($res = mssql_fetch_assoc($query)) {
		if($res['valor'] == 'undefined') {
			$resposta_cliente = '';
			$th_respostacliente = '';
		} else {
			$resposta_cliente = '<td>'.utf8_encode($res['valor']).'</td>';
		}
		
		if(substr(trim($res['resposta']), 0, 6) == "Outros") {
			$resposta = '<td>Outros</td>';
		} elseif(substr(trim($res['resposta']), 0, 18) == '<input type="text"') {
			$resposta = '';
			$th_resposta = '';
			$mostra_agrupado = false;
		} else {
			$resposta = '<td>'.utf8_encode($res['resposta']).'</td>';
		}
		
		$tabela_listagem .= '<tr><td>'.utf8_encode($res['titulo']).'</td><td>'.utf8_encode($res['pergunta']).'</td>'.$resposta.'<td>'.utf8_encode($res['data']).'</td>'.$resposta_cliente.'<td>'.utf8_encode($res['nome']).'</td><td>'.utf8_encode($res['ddd']).'</td><td>'.utf8_encode($res['telefone']).'</td><td>'.utf8_encode($res['dddcelular']).'</td><td>'.utf8_encode($res['celular']).'</td><td>'.utf8_encode($res['email']).'</td></tr>';
		
		$total++;
	}
	
	$tabela_header = '<tr><th>T&Iacute;TULO</th><th>PERGUNTA</th>'.$th_resposta.'<th>DATA</th>'.$th_respostacliente.'<th>NOME</th><th>DDD</th><th>TELEFONE</th><th>DDD CELULAR</th><th>CELULAR</th><th>E-MAIL</th></tr>';
	
	$tabela_listagem = '<table class="table table-striped table-bordered">'.$tabela_header.$tabela_listagem.'</table>';
	
	if($mostra_agrupado) {
		$sql = "SELECT   r.resposta, COUNT(*) AS total
		FROM         CAD_enquete_respostacliente rc
		inner join CAD_enquete_resposta r on r.id = rc.id_resposta
		inner join CAD_enquete_pergunta p on p.id = r.id_pergunta
		inner join CAD_enquete e on e.id = p.id_enquete
		left join CAD_enquete_respostacliente_outros rco on rco.id_resposta = r.id and rco.id_respostacliente = rc.id
		WHERE e.id = ".$_GET['id']."
		GROUP BY r.resposta";
		
		$query2 = mssql_query($sql, $db);
		$tabela_listagem2 = '';
		
		while($res2 = mssql_fetch_assoc($query2)) {
			if(substr(trim($res2['resposta']), 0, 6) == "Outros") {
				$resposta = '<td>Outros</td>';
			} else {
				$resposta = '<td>'.utf8_encode($res2['resposta']).'</td>';
			}
			
			$tabela_listagem2 .= '<tr>'.$resposta.'<td>'.utf8_encode($res2['total']).'</td></tr>'; 
		}
		
		$tabela_listagem2 = '<table class="table table-striped table-bordered"><tr><th>RESPOSTA</th><th>TOTAL</th></tr>'.$tabela_listagem2.'</table>';
	}
}
?>
<!DOCTYPE HTML>
<html lang="en-US">
    <head>
    	<script type="text/javascript" src="../jquery-ui-1.9.0.custom/js/jquery-2.0.3.min.js"></script>
		<script type="text/javascript" src="script.js"></script>
		<link href="estilo.css" rel="stylesheet" type="text/css" />
        
        <meta charset="iso-8859-1">
        <title>Relat&oacute;rio de enquete</title>

 
        <!-- TWITTER BOOTSTRAP CSS -->
        <link href="css/bootstrap.css" rel="stylesheet" type="text/css" />
 
        <!-- TWITTER BOOTSTRAP JS -->
        <script src="js/bootstrap.min.js"></script>
 
    </head>
    <body>
    	<div class="container-fluid">
            <!-- CLASSE PARA DEFINIR UMA LINHA -->
            <div class="row-fluid">
            	<div class="span12">
                	<p>
                	<ul class="nav nav-pills">
                        <li class="active">
                            <a href="index.php">Voltar</a>
                        </li>
                    </ul>
                    </p>
                </div>
            </div>
            <div class="row-fluid">
            	<div class="span4 offset4">
                	 <?php echo $tabela_listagem2; ?>
                </div>
            </div>
            <div class="row-fluid">
                <!-- COLUNA OCUPANDO 12 ESPAÇOS NO GRID -->
                <div class="row-fluid">
                    <div class="span12">
                        <h3>Total de linhas: <?php echo $total; ?></h3>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span12">
                        <?php echo $tabela_listagem; ?>
                    </div>
                </div>
            </div>
            <div class="row-fluid">
            	<div class="span12">
                	<p>
                	<ul class="nav nav-pills">
                        <li class="active">
                            <a href="index.php">Voltar</a>
                        </li>
                    </ul>
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>