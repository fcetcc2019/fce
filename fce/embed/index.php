<!DOCTYPE HTML>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, user-scalable=no">
        <title>FCE | Enquete</title>

 
        <!-- TWITTER BOOTSTRAP CSS -->
        <link href="../../bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
 
 		<script type="text/javascript" src="../../jquery-ui-1.9.0.custom/js/jquery-1.8.2.js"></script>
        <!-- TWITTER BOOTSTRAP JS -->
        <script src="../../highcharts-6.0.4/code/highcharts.js"></script>
		<script src="../../highcharts-6.0.4/code/modules/exporting.js"></script>
        <script src="../../bootstrap/js/bootstrap.min.js"></script>
        <!--<script src="../bootstrap/ckeditor/ckeditor.js"></script>-->
        <script src="../../Chart.js/Chart.min.js"></script>
        
        
        <style>
        .grupo-respostas, .contatos { max-width: 500px;  }
        .grupo-respostas .span12:first-child { margin-left: 11px; }
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

            		echo '<div class="row-fluid enquete">';

            		for ($i=0; $i < count($retorno); $i++) {
            			$pergunta = '<div class="row-fluid"><h3>'.$retorno[$i]['pergunta'].'</h3></div>';
            			
            			$respostas .= '<div class="span12 respostas">';
            			$respostas .= '<label class="radio">';
            			$respostas .= '<input type="radio" name="optionsRadios" id="'.$retorno[$i]['id'].'" value="option1">';
            			$respostas .= $retorno[$i]['resposta'];
            			$respostas .= '</label>';
            			$respostas .= '</div>';
            		}

            		//echo $pergunta.$respostas;
                    echo $pergunta;
                    echo '<div class="row-fluid grupo-respostas">';
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

	            			echo '<div class="span12" style="text-transform:capitalize;">'.$camposExtra[$i]['campo'].'</div>';
	            			echo '<div class="span12"><input type="text" name="'.$camposExtra[$i]['campo'].'" id="'.$camposExtra[$i]['campo'].'" class="span8" /></div>';

	            		}

            		}

                    echo '<div class="span12" style="margin-top:25px;"><input type="button" name="salvar" id="salvar" value="Salvar" class="btn btn-default btn-large span8" style="padding-top:7px; padding-bottom:7px;" /></div>';

            		echo '</div>'; // Fecha contatos
            	}

            	?>

            </div>

		</div>
        
    </body>
    <script src="../js/scripts.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('[name=outros]').css({'margin-bottom':'0'});

            $('#enviar').click(function() {
                $('.enquete').hide();
                $('.contatos').show(300);
            })
        });
    </script>
</html>