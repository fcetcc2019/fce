<?php
//include("../connection_aries.php");
//include("../connection.php");
include("../connection_alpha_homologacao.php");

$usuario = 'DJUNIOR';
	
//mysql_select_db("Siap", $db_alpha);

//mysql_select_db("PortalSenacRS", $db_alpha);

if(isset($_GET['id']) && !empty($_GET['id'])) {
	$sql = "SELECT     e.titulo, p.pergunta, r.resposta, e.unidade,
			rc.data, rco.valor, rc.nome, rc.ddd, rc.telefone, rc.dddcelular, rc.celular, rc.email
		FROM         CAD_enquete_respostacliente rc
		inner join CAD_enquete_resposta r on r.id = rc.id_resposta
		inner join CAD_enquete_pergunta p on p.id = r.id_pergunta
		inner join CAD_enquete e on e.id = p.id_enquete
		/*left join CAD_enquete_contatocliente ecc on ecc.id_resposta = r.id and ecc.id_respostacliente = rc.id*/
		left join CAD_enquete_respostacliente_outros rco on rco.id_resposta = r.id and rco.id_respostacliente = rc.id
		WHERE e.id = ".$_GET['id']."
		order by
			rc.data desc";
			
	//echo $sql;
	//$query = mysql_query($sql, $db);
	$query = mysqli_query($db_alpha, $sql);
	$resposta_cliente = "";
	$resposta = "";
	$tabela_listagem = '';
	$th_resposta = "<th>RESPOSTA</th>";
	$th_respostacliente = "<th>RESPOSTA CLIENTE</th>";
	$tabela_header = '';
	$mostra_agrupado = true;
	$total = 0;
	$pergunta = '';
	$id_enquete = $_GET['id'];
	
	while($res = mysqli_fetch_assoc($query)) {
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
		
		//$tabela_listagem .= '<tr><td>'.utf8_encode($res['titulo']).'</td><td>'.utf8_encode($res['pergunta']).'</td>'.$resposta.'<td>'.utf8_encode($res['data']).'</td>'.$resposta_cliente.'<td>'.utf8_encode($res['nome']).'</td><td>'.utf8_encode($res['ddd']).'</td><td>'.utf8_encode($res['telefone']).'</td><td>'.utf8_encode($res['dddcelular']).'</td><td>'.utf8_encode($res['celular']).'</td><td>'.utf8_encode($res['email']).'</td></tr>';
		$tabela_listagem .= '<tr><td>'.utf8_encode($res['titulo']).'</td><td>'.utf8_encode($res['pergunta']).'</td>'.$resposta.'<td>'.utf8_encode($res['data']).'</td>'.$resposta_cliente.'<td>'.utf8_encode($res['nome']).'</td><td>'.utf8_encode($res['ddd']).'</td><td>'.utf8_encode($res['telefone']).'</td><td>'.utf8_encode($res['email']).'</td></tr>';
		
		$pergunta = utf8_encode($res['pergunta']);
		
		$total++;
	}
	
	//$tabela_header = '<tr><th>T&Iacute;TULO</th><th>PERGUNTA</th>'.$th_resposta.'<th>DATA</th>'.$th_respostacliente.'<th>NOME</th><th>DDD</th><th>TELEFONE</th><th>DDD CELULAR</th><th>CELULAR</th><th>E-MAIL</th></tr>';
	$tabela_header = '<tr><th>T&Iacute;TULO</th><th>PERGUNTA</th>'.$th_resposta.'<th>DATA</th>'.$th_respostacliente.'<th>NOME</th><th>DDD</th><th>TELEFONE</th><th>E-MAIL</th></tr>';																																																					   

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
		
		//*******  PARA ORDENAR AS RESPOSTAS DA MAIOR PARA A MENOR... VAI FACILITAR PARA DESTACAR A MAIOR FATIA DO GRÁFICO  ********
		$sql .= " ORDER BY total DESC";
		
		//$query2 = mysql_query($sql, $db);
		$query2 = mysqli_query($db_alpha, $sql);
		$tabela_listagem2 = '';
		$valores_hichart = '';
		$volta = 0;
		
		while($res2 = mysqli_fetch_assoc($query2)) {
			if(substr(trim($res2['resposta']), 0, 6) == "Outros") {
				$resposta = '<td>Outros</td>';
			} else {
				$resposta = '<td>'.utf8_encode($res2['resposta']).'</td>';
			}
			
			$tabela_listagem2 .= '<tr>'.$resposta.'<td>'.utf8_encode($res2['total']).'</td></tr>';
			
			//*********  ADICIONA OS VALORES AO GRÁFICO Hicharts  ************
			if($volta == 0) {
				//*******  AQUI O MAIOR VALOR  *******
				$valores_hichart .= "{ name: '".$resposta."', y: ".((utf8_encode($res2['total']) * 100) / $total).", sliced: true, selected: true },";
			} else {
				$valores_hichart .= "{ name: '".$resposta."', y: ".((utf8_encode($res2['total']) * 100) / $total)." },";
			}
			
			$volta++;
			
		}
		
		$valores_hichart = rtrim($valores_hichart, ',');
		
		$tabela_listagem2 = '<table class="table table-striped table-bordered"><tr><th>RESPOSTA</th><th>TOTAL</th></tr>'.$tabela_listagem2.'</table>';
	}
}
?>
<!DOCTYPE HTML>
<html lang="en-US">
    <head>
    	<script type="text/javascript" src="../jquery-ui-1.9.0.custom/js/jquery-2.0.3.min.js"></script>
        <script src="../highcharts-6.0.4/code/highcharts.js"></script>
		<script src="../highcharts-6.0.4/code/modules/exporting.js"></script>
		<script type="text/javascript" src="script.js"></script>
		<link href="estilo.css" rel="stylesheet" type="text/css" />
        
        <meta charset="utf-8">
        <title>Relat&oacute;rio de enquete</title>

 
        <!-- TWITTER BOOTSTRAP CSS -->
        <link href="css/bootstrap.css" rel="stylesheet" type="text/css" />
 
        <!-- TWITTER BOOTSTRAP JS -->
        <script src="js/bootstrap.min.js"></script>

		<script>
		
			$(document).ready(function(){
				$('.dropdown-toggle').dropdown();
			})
		
		</script>
 
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
            	<div id="container" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
                <script type="text/javascript">
				//$(document).ready(function(){
					
					// Radialize the colors
					Highcharts.setOptions({
						colors: Highcharts.map(Highcharts.getOptions().colors, function (color) {
							return {
								radialGradient: {
									cx: 0.5,
									cy: 0.3,
									r: 0.7
								},
								stops: [
									[0, color],
									[1, Highcharts.Color(color).brighten(-0.3).get('rgb')] // darken
								]
							};
						})
					});
					
					// Build the chart
					Highcharts.chart('container', {
						chart: {
							plotBackgroundColor: null,
							plotBorderWidth: null,
							plotShadow: false,
							type: 'pie'
						},
						title: {
							text: '<?php echo $pergunta; ?>'
						},
						tooltip: {
							pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
						},
						plotOptions: {
							pie: {
								allowPointSelect: true,
								cursor: 'pointer',
								dataLabels: {
									enabled: true,
									format: '<b>{point.name}</b>: {point.percentage:.1f} %',
									style: {
										color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
									},
									connectorColor: 'silver'
								}
							}
						},
						series: [{
							name: 'Respondentes',
							data: [<?php echo $valores_hichart; ?>]
						}]
					});
					
					//Highcharts.chart('container').Series.addPoint({ name: 'Quarta', y: 10.00 });
				
				//});
			</script>
            </div>
            
            <div class="row-fluid">
            	<div class="span4 offset4">
                	 <?php echo $tabela_listagem2; ?>
                </div>
            </div>
            
            <div class="row-fluid">
                <!-- COLUNA OCUPANDO 12 ESPAÇOS NO GRID -->
                <div class="row-fluid">
                    <div class="span12" style="margin:20px 0; font-size:2em;">
                        <div class="span4" style="padding:8px 0;">Total de linhas: <?php echo $total; ?></div>
                        <div class="span4">
                        	<div class="btn-group">
								<a class="btn btn-success dropdown-toggle" data-toggle="dropdown" href="#">Exportar<span class="caret"></span></a>
								<ul class="dropdown-menu">
									<li><a href="exportar.php?id=<?= $id_enquete; ?>&formato=csv" target="_blank">CSV</a></li>
									<li><a href="exportar.php?id=<?= $id_enquete; ?>&formato=excel" target="_blank">Excel</a></li>
									<li><a href="exportar.php?id=<?= $id_enquete; ?>&formato=json" target="_blank">JSON</a></li>
								</ul>
							</div>

                        </div>
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