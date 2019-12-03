<?php

$mensagem = "";

if($_SERVER['REQUEST_METHOD'] === 'POST') {
	
	if($_POST['login'] == 'empresa' && $_POST['senha'] == '123456'){

        session_start();

        $_SESSION["usuario"] = $_POST['login'];

        $_SESSION["logado"] = true;

        header("Location: fce/");

	} else {

        $mensagem = "<div class='alert alert-error'>Usuário ou senha inválidos.</div>";

    }
	
}

?>

<!DOCTYPE HTML>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, user-scalable=no">
        <title>::: Enquetes :::</title>

 
        <!-- TWITTER BOOTSTRAP CSS -->
        <link href="bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
 
 		<script type="text/javascript" src="jquery-ui-1.9.0.custom/js/jquery-1.8.2.js"></script>
        <!-- TWITTER BOOTSTRAP JS -->
        <script src="bootstrap/js/bootstrap.min.js"></script>
        
        <style>
         .form-titulo {
             text-align: center;
             margin-top: 30px;
         }
         
         .form-login {
            width: 250px;
            position: absolute;
            left: 50%;
            margin-left: -145px;
            top: 150px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
         }
        </style>
 
    </head>
    <body>

        <!-- CLASSE QUE DEFINE O CONTAINER COMO FLUIDO (100%) -->
        <div class="container-fluid">

            <div class="row-fluid form-titulo">
            
                <h1>FCE</h1>
                <h4>Ferramenta de Construção de Enquetes</h4>
            
            </div>
            
            <div class="row-fluid form-login">

                <?=$mensagem; ?>

                <form action="index.php" method="post">
                
                    <div class="control-group">
                
                        <label class="control-label" for="login">Login</label>
                
                        <div class="controls">
                
                            <input type="text" name="login" placeholder="Login">
                
                        </div>
                
                    </div>
                
                    <div class="control-group">
                
                        <label class="control-label" for="senha">Senha</label>
                
                        <div class="controls">
                
                            <input type="password" name="senha" placeholder="Senha"> 
                
                        </div>
                
                    </div>
                
                    <div class="control-group">
                
                        <div class="controls">
                
                            <button type="submit" class="btn">Entrar</button>
                
                        </div>
                
                    </div>
                
                </form>

            </div>
            
		</div>
        
    </body>

</html>