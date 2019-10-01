<!DOCTYPE HTML>
<html lang="en-US">
    <head>
        <meta charset="iso-8859-1">
        <meta name="viewport" content="width=device-width, user-scalable=no">
        <title>::: Enquetes :::</title>

 
        <!-- TWITTER BOOTSTRAP CSS -->
        <link href="../bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
        <link href="../jquery-ui-1.12.1.custom/jquery-ui.min.css" rel="stylesheet" type="text/css" />
        <style>
		  	
		</style>
 
 		<script type="text/javascript" src="../jquery-ui-1.9.0.custom/js/jquery-1.8.2.js"></script>
        <script type="text/javascript" src="../jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
        <!-- TWITTER BOOTSTRAP JS -->
        <script src="../bootstrap/js/bootstrap.min.js"></script>
        <script src="../bootstrap/ckeditor/ckeditor.js"></script>
        <script src="js/scripts.js"></script>
        
        <script>
			$(document).ready(function() {
				
            });
		</script>
 
    </head>
    <body>
    
    <?php
    //ini_set('error_reporting',E_ALL);
    //include("../connection.php");
    require_once('../classes/enqueteModel.php');
    include("../connection_alpha_homologacao.php");
    
    $enquete = new Enquete();

		
    /*
	//$usuario = trim(strtoupper(substr($_SERVER["AUTH_USER"],8,100)));
    $usuario = 'DJUNIOR';
	
	mysql_select_db("Siap", $db_alpha);
	
	$sql20 = "SELECT * FROM stUsuario where IdUsuario = '".$usuario."'";

	//$qsql20 = mysql_query($sql20, $db2);
	$qsql20 = mysql_query($sql20, $db_alpha);
	//echo $db2;
	if($res0 = mysql_fetch_assoc($qsql20)){
		$unidade = trim($res0['IdUO']);
		$email = trim($res0['email']);
		$nomeUsusario = trim($res0['NOME']);
	}
	
	//$unidade = "6";
	$unidade = trim($unidade);
	
	if(is_numeric($unidade)) {
		header("Location:index_unidade.php");
	}
    */

	//mysql_select_db("PortalSenacRS", $db_alpha);
	
	?>
        <!-- CLASSE QUE DEFINE O CONTAINER COMO FLUIDO (100%) -->
        <div class="container-fluid">
            <div class="row">
                <?php
                if(empty($_GET['client']) || !is_numeric($_GET['client'])) {
                    echo "Parâmetros incorretos. [1]";
                } else {
                    //echo "ok";
                    $dadosEnquete = $enquete->listaEnqueteAtiva($_GET['client']);
                    //'<pre>'.var_dump($dadosEnquete).'</pre>';
                    echo '<pre>';
                    var_dump($dadosEnquete);
                    echo '</pre>';
                    $pergunta = '';
                    //$respostas = array();
                    $respostas = '';

                    for($i=0; $i < count($dadosEnquete); $i++) {
                        //echo '<p>'.$dadosEnquete[$i]['resposta'].'</p>';
                        $pergunta = $dadosEnquete[$i]['pergunta'];
                        //$respostas[] = $dadosEnquete[$i]['resposta'];
                        $respostas .= '<div class="linha">';
                        $respostas .= '<input type="radio" name="resposta-'.$dadosEnquete[$i]['id'].'" />';
                        $respostas .= '<label for="'.$dadosEnquete[$i]['id'].'">'.$dadosEnquete[$i]['resposta'].'</label>';
                        $respostas .= '</div>';
                    }

                    echo $respostas;
                }
                ?>
            </div>            
		</div>
    </body>
</html>