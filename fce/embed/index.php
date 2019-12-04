<!DOCTYPE HTML>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, user-scalable=no">
        <title>FCE | Enquete</title>

 
        <!-- TWITTER BOOTSTRAP CSS -->
        <link href="../../bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
        <!--<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">-->
        <link href="../../bootstrap/datepicker/css/datepicker.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="/resources/demos/style.css">
 
 		<script type="text/javascript" src="../../jquery-ui-1.9.0.custom/js/jquery-1.8.2.js"></script>
 		<script src="../../bootstrap/datepicker/js/bootstrap-datepicker.js"></script>
        <!--<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>-->
        <!-- TWITTER BOOTSTRAP JS -->
        <script src="../../highcharts-6.0.4/code/highcharts.js"></script>
		<script src="../../highcharts-6.0.4/code/modules/exporting.js"></script>
        <script src="../../bootstrap/js/bootstrap.min.js"></script>
        <!--<script src="../bootstrap/ckeditor/ckeditor.js"></script>-->
        <script src="../../Chart.js/Chart.min.js"></script>
        
        
        <style>
        .grupo-respostas, .contatos { max-width: 500px;  }
        .grupo-respostas .span12:first-child { margin-left: 11px; }

        .box {
            position: relative;
            float: left;
            min-width: 100px;
        }

        .box .texto {
            padding-left: 7px;
        }

        .inline-block {
            display: inline-block;
        }

        .padding-direita-3 {
            padding-right: 3%;
        }

        #nascimento {
            /*background-color: #fff;*/
        }

        #nascimento:hover {
            /*cursor: default;*/
        }
        </style>
 
    </head>
    <body>
    <?php
	ini_set('display_errors',1);
	//include("../../connection_alpha_homologacao.php");
	require_once("../../classes/enqueteModel.php");
	
	if(!isset($_GET['ativoInativo'])) {
		$_GET['ativoInativo'] = 'ativas'; 
	}
	
	?>
        <!-- CLASSE QUE DEFINE O CONTAINER COMO FLUIDO (100%) -->
        <div class="container-fluid">
            <!-- CLASSE PARA DEFINIR UMA LINHA -->
            <div class="row-fluid">
                <?php
            	$enquete = new Enquete();
            	
            	if(isset($_GET['d']) && !empty($_GET['d'])) {
            		
            		$retorno = $enquete->listaEnqueteAtiva(trim($_GET['d']));
            		//echo '<pre>';
            		//var_dump($retorno);
            		//echo '</pre>';

            		$pergunta = '';
            		$respostas = '';
            		$contatos = '';
            		$idenquete = '';

            		echo '<div class="span12 agradecimento" style="display: none;"><h2 style="color: #aaa;">Obrigado pela participação!</h2></div>';

                    echo '<div class="row-fluid enquete">';

                    $conta = 0;

            		for ($i=0; $i < count($retorno); $i++) {
            			//$idenquete = '<div class="row-fluid"><input type="hidden" name="idenquete" id="idenquete" value="'.$retorno[$i]['id_enquete'].'" /></div>';
            			$idenquete = '<input type="hidden" name="idenquete" id="idenquete" value="'.$retorno[$i]['id_enquete'].'" />';
            			$pergunta = '<div class="row-fluid"><h3>'.$retorno[$i]['pergunta'].'</h3></div>';
            			
            			$respostas .= '<div class="span12 respostas">';
            			$respostas .= '<label class="radio">';
            			$respostas .= '<input type="radio" name="optionsRadios" id="resposta-'.$retorno[$i]['id'].'" value="'.$retorno[$i]['id'].'" semcheck>';
            			$respostas .= $retorno[$i]['resposta'];
            			$respostas .= '</label>';
            			$respostas .= '</div>';

                        $conta++;
            		}

            		//echo $pergunta.$respostas;
                    echo $pergunta;
                    echo $idenquete;
                    echo '<div class="row-fluid grupo-respostas">';

                    if($conta == 1) {
                        $respostas = str_replace("semcheck", "checked", $respostas);
                    }

                    echo $respostas;
                    echo '<div class="span12" style="margin-top:25px;"><input type="button" name="enviar" id="enviar" value="Enviar" class="btn btn-default btn-large span6" style="padding-top:7px; padding-bottom:7px;" /></div>';
                    echo '</div>';

            		echo '</div>'; // Fecha enquete

            		echo '<div class="row-fluid contatos" style="display:none;">';
            		
            		echo '<div class="row-fluid"><h3>Deixe seus contatos!</h3></div>';
            		
            		echo '<div class="span12">Nome</div>';
            		echo '<div class="span12"><input type="text" name="nome" id="nome" class="span8" /></div>';

            		echo '<div class="span12">E-mail</div>';
            		echo '<div class="span12"><input type="email" name="email" id="email" class="span8" /></div>';

            		echo '<div class="span12">Telefone</div>';
            		echo '<div class="span2"><input type="text" name="ddd" id="ddd" class="span12" /></div>';
            		echo '<div class="span6"><input type="text" name="telefone" id="telefone" class="span12" /></div>';

            		$camposExtra = $enquete->listaDemaisCampos();
            		//var_dump($camposExtra);

            		if(!empty($camposExtra)) {

            			for ($i=0; $i < count($camposExtra); $i++) {

	            			$sexo = '';
                            if($camposExtra[$i]['campo'] == 'sexo') {
                                //$sexo = 'feitoooo';
                                $sexo .= '<div class="span12" style="text-transform:capitalize;">'.$camposExtra[$i]['campo'].'</div>';
                                
                                $sexo .= '<div class="span12">';
                                
                                $sexo .= '<div class="box padding-direita-3">';
                                $sexo .= '<div class="inline-block"><input type="radio" name="'.$camposExtra[$i]['campo'].'" id="'.$camposExtra[$i]['campo'].'-f" style="margin-top: 0;" value="F" /></div>';
                                $sexo .= '<div class="inline-block texto">Feminino</div>';
                                $sexo .= '</div>';

                                $sexo .= '<div class="box padding-direita-3">';
                                $sexo .= '<div class="inline-block"><input type="radio" name="'.$camposExtra[$i]['campo'].'" id="'.$camposExtra[$i]['campo'].'-m" style="margin-top: 0;" value="M" /></div>';
                                $sexo .= '<div class="inline-block texto">Masculino</div>';
                                $sexo .= '</div>';                                

                                $sexo .= '</div>';

                                echo $sexo;

                            //} else if($camposExtra[$i]['campo'] == 'nascimento') {
                            	/*
                                echo '<div class="span12" style="text-transform:capitalize;">'.$camposExtra[$i]['campo'].'</div>';
                                echo '<div class="span12"><input type="text" name="'.$camposExtra[$i]['campo'].'" id="'.$camposExtra[$i]['campo'].'" class="span8" /></div>';
                                */

                            } else {

                                echo '<div class="span12" style="text-transform:capitalize;">'.$camposExtra[$i]['campo'].'</div>';
    	            			echo '<div class="span12"><input type="text" name="'.$camposExtra[$i]['campo'].'" id="'.$camposExtra[$i]['campo'].'" class="span8" /></div>';

                            }

	            		}

            		}

                    echo '<div class="span12" style="margin-top:25px;"><input type="button" name="salvar" id="salvar" value="Salvar" class="btn btn-default btn-large span8" style="padding-top:7px; padding-bottom:7px;" /></div>';

            		echo '</div>'; // Fecha contatos
            	}

            	?>

            </div>

		</div>
        
    </body>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.0/jquery.mask.js"></script>
    <!--<script type="text/javascript" src="https://github.com/uxsolutions/bootstrap-datepicker/blob/master/js/locales/bootstrap-datepicker.pt-BR.js"></script>-->
    <script src="../js/scripts.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            
            $('[name=outros]').css({'margin-bottom':'0'});

            $('#enviar').click(function() {
                $('.enquete').hide();
                $('.contatos').show(300);
            });

            //$('#nascimento').mask('00/00/0000');

            /*
            (function($){
                $.fn.datepicker.dates['pt-BR'] = {
                    days: ["Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado"],
                    daysShort: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sáb"],
                    daysMin: ["Do", "Se", "Te", "Qu", "Qu", "Se", "Sa"],
                    months: ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"],
                    monthsShort: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"],
                    today: "Hoje",
                    monthsTitle: "Meses",
                    clear: "Limpar",
                    format: "dd/mm/yyyy"
                };
            }(jQuery));*/


            $('#nascimento').datepicker({
                autoclose: true,
                format: 'dd/mm/yyyy'
            });

            $('#ddd').mask('00');
            $('#telefone').mask('00000.0000', {reverse: true});
            $('#cpf').mask('000.000.000-00', {reverse: true});


            function SalvaResposta() {
            	$.ajax({
            		url: "ajax_embed.php",
            		type: "POST",
            		data: {
            			'idenquete': $('#idenquete').val(),
            			'resposta': $('[name=optionsRadios]:checked').val(),
            			'outros': $('[name=outros]').val(),
            			'nome': $('#nome').val(),
            			'email': $('#email').val(),
            			'ddd': $('#ddd').val(),
            			'telefone': $('#telefone').val(),
            			'cpf': $('#cpf').val(),
            			'nascimento': $('#nascimento').val(),
            			'sexo': $('[name=sexo]:checked').val(),
            			'acao': 'salvaresposta'
            		},
            		//contentType: false,
            		//cache: false,
            		//processData:false,
            		beforeSend : function() {

            		},
            		success: function(data) {
            			console.log(data);
                        agradecimento();
            		},
            		error: function(e, exception) {
            			console.log("SERVER ERROR (AtualizaLamina) - "+e.responseText+" - "+e.status);
            			console.log(exception);
                        agradecimento();
            		}

            	});
            }

            function agradecimento() {
                $('.contatos').hide(100);
                $('.agradecimento').show(300);
            }

            $('#salvar').click(function() {
            	SalvaResposta();
            });

        });
    </script>
</html>