<!DOCTYPE HTML>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, user-scalable=no">
        <title>::: Enquetes :::</title>

 
        <!-- TWITTER BOOTSTRAP CSS -->
        <link href="../bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
 
 		<script type="text/javascript" src="../jquery-ui-1.9.0.custom/js/jquery-1.8.2.js"></script>
        <!-- TWITTER BOOTSTRAP JS -->
        <script src="../highcharts-6.0.4/code/highcharts.js"></script>
		<script src="../highcharts-6.0.4/code/modules/exporting.js"></script>
        <script src="../bootstrap/js/bootstrap.min.js"></script>
        <!--<script src="../bootstrap/ckeditor/ckeditor.js"></script>-->
        <script src="../Chart.js/Chart.min.js"></script>
        
        
        <style>
        .filtro {
			background-color: #f1f5fb;
			padding: 1%;
			text-align: center;
		}

		.linha_inteira {
			position: relative;
			float: left;
			width: 100%;
		}

		.bt-insere-enquete {
			padding: 0.5%;
			text-align: center;
		}
		/*.input-radio {
			margin-bottom: 3px;
		}*/

		.div-interna-filtro1 {
			position: relative;
			float: left;
			width: 10%;
		}

		.div-interna-filtro2 {
			position: relative;
			float: left;
			width: 36%;
			margin: 0% 2% 0% 2%;
			text-align: left;
		}

		.div-interna-filtro3 {
			position: relative;
			float: left;
			width: 10%;
		}

		.linha_inteira:hover {
			cursor: pointer;
			color: #039;
			text-decoration: underline;
		}

		.barra-grafico {
			position: relative;
			float: left;
			background-color: #aaa;
			height: 100%;
			width: 10%;
			bottom: -100%;
			margin-left: 2%;
			margin-right: 2%;
		}

		.barra-grafico:hover {
			cursor: pointer;
			box-shadow: 0 0 6px #403f3f;
		}

		.bloco-grafico {
			background-color: #f4f4f4;
			padding: 8px 2%;
			margin: 8px 0;
			cursor: pointer;
			width: 96%;
		}

        </style>
 
    </head>
    <body>
    <?php
	//ini_set('display_errors',1);
	include("../connection_alpha_homologacao.php");
	
	if(!isset($_GET['ativoInativo'])) {
		$_GET['ativoInativo'] = 'ativas'; 
	}
	
	?>
        <!-- CLASSE QUE DEFINE O CONTAINER COMO FLUIDO (100%) -->
        <div class="container-fluid">
            <!-- CLASSE PARA DEFINIR UMA LINHA -->
            <div class="row-fluid">
                <ul class="nav nav-tabs">
                    <li id="enquetes" class="aba">
                        <a href="index.php">Enquetes</a>
                    </li>
                    <li id="relatorios" class="aba active">
                        <a href="graficos.php">Relatórios</a>
                    </li>
                </ul>
            </div>
            
            <div class="row-fluid secoes relatorios">
            	<?php
				$top10 = "SELECT unidade, count(*) as total FROM cad_enquete group by unidade order by total desc LIMIT 0, 10";
				$queryTop10 = mysqli_query($db_alpha, $top10);
				$valores_hichart = '';
				while($resTop10 = mysqli_fetch_assoc($queryTop10)) {
					$valores_hichart .= "['".$resTop10['unidade']."', ".$resTop10['total']."],";
				}
				
				$valores_hichart = rtrim($valores_hichart, ",");
				
				?>
                
                <div class="row-fluid">
                	<div id="container" style="min-width: 310px; height: 400px; width: 100%; margin: 0 auto"></div>
					<script type="text/javascript">
					Highcharts.chart('container', {
						chart: {
							type: 'column'
						},
						title: {
							text: 'Top 10 escolas com mais enquetes publicadas'
						},
						subtitle: {
							text: 'Source: <a href="http://en.wikipedia.org/wiki/List_of_cities_proper_by_population">Wikipedia</a>'
						},
						xAxis: {
							type: 'category',
							labels: {
								rotation: -45,
								style: {
									fontSize: '13px',
									fontFamily: 'Verdana, sans-serif'
								}
							}
						},
						yAxis: {
							min: 0,
							title: {
								text: 'Population (millions)'
							}
						},
						legend: {
							enabled: false
						},
						tooltip: {
							pointFormat: 'Population in 2008: <b>{point.y:.1f} millions</b>'
						},
						series: [{
							name: 'Population',
							data: [
								/*
								['Shanghai', 23.7],
								['Lagos', 16.1],
								['Istanbul', 14.2],
								['Karachi', 14.0],
								['Mumbai', 12.5],
								['Moscow', 12.1],
								['São Paulo', 11.8],
								['Beijing', 11.7],
								['Guangzhou', 11.1],
								['Delhi', 11.1],
								['Shenzhen', 10.5],
								['Seoul', 10.4],
								['Jakarta', 10.0],
								['Kinshasa', 9.3],
								['Tianjin', 9.3],
								['Tokyo', 9.0],
								['Cairo', 8.9],
								['Dhaka', 8.9],
								['Mexico City', 8.9],
								['Lima', 8.9]
								*/
								<?php echo $valores_hichart; ?>
							],
							dataLabels: {
								enabled: true,
								rotation: -90,
								color: '#FFFFFF',
								align: 'right',
								format: '{point.y:.1f}', // one decimal
								y: 10, // 10 pixels down from the top
								style: {
									fontSize: '13px',
									fontFamily: 'Verdana, sans-serif'
								}
							}
						}]
					});
					</script>
                </div>
                
                <?php
				$unidadesComEnquetes = "select distinct u.iduo, u.nome from CAD_enquete e right join INF_unidades u on u.iduo = e.unidade order by u.nome";
				$queryComEnquetes = mysqli_query($db_alpha, $unidadesComEnquetes);
				while($comEnquetes = mysqli_fetch_assoc($queryComEnquetes)) {
					echo '<div class="row-fluid">
							<div class="linha_inteira bloco-grafico" iduo="'.$comEnquetes['iduo'].'">'.$comEnquetes['nome'].'</div>
							<div class="linha_inteira canvas-grafico" iduo="'.$comEnquetes['iduo'].'">
								<canvas id="canvas-'.$comEnquetes['iduo'].'" width="400" height="100" aria-label="Hello ARIA World" role="img"></canvas>
							</div>
						</div>';
				}
				?>
                
                <div class="row-fluid graficos" style="margin-bottom:50px;">
                	<!--<div style="position:relative; background-color:#eee; height:200px; overflow-y:hidden;">
                    	<div class="barra-grafico" style="height:100%; bottom:-67%;"></div>
                        <div class="barra-grafico" style="height:100%; bottom:-33%;"></div>
                        <div class="barra-grafico" style="height:100%; bottom:-57%;"></div>
                        <div class="barra-grafico" style="height:100%; bottom:-8%;"></div>
                        <div class="barra-grafico" style="height:100%; bottom:-95%;"></div>
                        <div class="barra-grafico" style="height:100%; bottom:-22%;"></div>
                        <div class="barra-grafico" style="height:100%; bottom:-11%;"></div>
                    </div>-->
                </div>
                
            </div>
            
		</div>
        
        <div class="container-fluid">
            <!--<div class="modal fade modal-enquete" id="modal-enquete" style="width:850px; margin-left:-425px; display:none;">-->
            <div class="modal fade modal-enquete" id="modal-enquete" style="display:none; width:610px; margin-left:-305px;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title">Enquete</h4>
                        </div>
                        <div class="modal-body">
                            <div id="alert-cadastroproduto" style="display:none">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <span id="msg-cadastraproduto"></span>
                            </div>
                            <div class="row-fluid">
                                <!--<div class="span12 pergunta"></div>
                                <div class="respostas"></div>-->
                                <div class="row-fluid pergunta"></div>
                                <div class="row-fluid respostas"></div>
                                <div class="row-fluid extrato_respostas"></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                            <!--<button type="button" id="bt-publicaEnquete" class="bt-publicaEnquete btn btn-primary span2" id-enquete="" disabled>Publicar <i class="icone-publicar icon-edit"></i></button>
                            <button type="button" id="bt-naoPublicaEnquete" class="bt-naoPublicaEnquete btn btn-danger span2" id-enquete="" disabled>Não publicar <i class="icone-publicar icon-edit"></i></button>-->
                            <button type="button" id="bt-alteraEnquete" class="bt-alteraEnquete btn btn-primary span2" id-enquete="">Alterar enquete <i class="icone-publicar icon-edit"></i></button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
        </div>
        
        <div class="container-fluid">
            <!--<div class="modal fade modal-enquete" id="modal-enquete" style="width:850px; margin-left:-425px; display:none;">-->
            <div class="modal fade modal-enquete-justificativa" id="modal-enquete-justificativa" style="display:none; width:610px; margin-left:-305px;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title">Justificativa</h4>
                        </div>
                        <div class="modal-body">
                            <div id="alert-cadastroproduto" style="display:none">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <span id="msg-cadastraproduto"></span>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">Por que a enquete não deve ser publicada?</div>
                                <div class="span12">
                                	<textarea name="justificativa" id="justificativa" style="width:93%; height:100px;"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                            <button type="button" id="bt-salvaJustificativa" class="bt-salvaJustificativa btn btn-primary span2" id-enquete="">Salvar <i class="icone-publicar icon-edit"></i></button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
        </div>
        
        <div class="container-fluid">
            <!--<div class="modal fade modal-enquete" id="modal-enquete" style="width:850px; margin-left:-425px; display:none;">-->
            <div class="modal fade modal-enquete-altera" id="modal-enquete-altera" style="display:none; width:610px; margin-left:-305px;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title">Alterar enquete</h4>
                        </div>
                        <div class="modal-body">
                            <div id="alert-cadastroproduto" style="display:none">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <span id="msg-cadastraproduto"></span>
                            </div>
                            <div class="row-fluid campos">
                                <!--<div class="span12">Pergunta:</div>
                                <div class="span12">
                                	<textarea name="justificativa" id="justificativa" style="width:93%; height:100px;"></textarea>
                                </div>-->
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                            <button type="button" id="bt-altera-enquete" class="bt-altera-enquete btn btn-primary span2" id-enquete="">Salvar <i class="icone-publicar icon-edit"></i></button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
        </div>
        
    </body>
    <script src="js/scripts.js"></script>
</html>