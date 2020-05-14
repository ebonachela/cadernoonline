<?php
    $erro = isset($_GET['erro']) ? $_GET['erro'] : 0; 
    $motivo = isset($_GET['motivo']) ? $_GET['motivo'] : 'Conta desativada';
    $tempo_restante = isset($_GET['duracao']) ? $_GET['duracao'] : 0;
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>Treine para os Vestibulres</title>

        <!-- Bootstrap -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css" integrity="sha384-PmY9l28YgO4JwMKbTvgaS7XNZJ30MK9FAZjjzXtlqyZCqBY6X6bXIkM++IkyinN+" crossorigin="anonymous">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>

    <body>

        <div class="container">
            <div class="page-header">
                <h1>Treine para os Vestibulares!</h1>
            </div>

            <?php
                if ($erro == 1){
                    echo '<div class="alert alert-danger">
                    As senhas não batem! Confirme a senha corretamente.
                    </div>';
                } else if ($erro == 2){
                    echo '<div class="alert alert-danger">
                    O usuário já existe, tente usar outro.
                    </div>';
                } else if ($erro == 3){
                    echo '<div class="alert alert-danger">
                    O email já está cadastrado, tente usar outro.
                    </div>';
                } else if ($erro == 4){
                    echo '<div class="alert alert-success">
                    Cadastro realizado com sucesso! Digite seu usuário e senha no campo ao lado para logar.
                    </div>';
                } else if ($erro == 5){
                    echo '<div class="alert alert-danger">
                    Usuário ou senha incorretos.
                    </div>';
                } else if ($erro == 6){
                    echo '<div class="alert alert-success">
                    Você foi desconectado com sucesso.
                    </div>';
                } else if ($erro == 7){
                    echo '<div class="alert alert-danger">
                    Você não tem permissão para acessar essa página.
                    </div>';
                } else if ($erro == 8){
                    echo '<div class="alert alert-danger">
                    Sua conta foi bloqueada. <strong>Motivo: </strong> '.$motivo.' <strong>Tempo restante: </strong> '.$tempo_restante.'
                    </div>';
                }
            ?>

            <div class="row">
    
                <!-- Cadastro -->
                <div class="col-md-8">
                    <form method="POST" action="cadastro.php">
                        <div class="form-group">
                            <label for="login">Usuário</label>
                            <input name="login" type="text" class="form-control" id="login" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input name="email" type="email" class="form-control" id="email" required>
                        </div>

                        <div class="form-group">
                            <label for="senha">Senha</label>
                            <input name="senha" type="password" class="form-control" id="senha" required>
                        </div>

                        <div class="form-group">
                            <label for="cosenha">Confirmação da senha</label>
                            <input name="cosenha" type="password" class="form-control" id="cosenha" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Cadastrar</button>
                    </form>
                </div>

                <!-- Login -->
                <div class="col-md-4">
                    <form method="POST" action="validar_login.php">
                        <h3>Logar</h3>
                        <div class="form-group">
                            <input name="login" type="text" class="form-control" id="login" placeholder="Usuário" required>
                        </div>
    
                        <div class="form-group">
                            <input name="senha" type="password" class="form-control" id="password" placeholder="Senha" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Logar</button>
                    </form>
                </div>
            
            </div>
        </div>

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha384-nvAa0+6Qg9clwYCGGPpDQLVpLNn0fRaROjHqs13t4Ggj3Ez50XnGQqc/r8MhnRDZ" crossorigin="anonymous"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js" integrity="sha384-vhJnz1OVIdLktyixHY4Uk3OHEwdQqPppqYR8+5mjsauETgLOcEynD9oPHhhz18Nw" crossorigin="anonymous"></script>
    </body>
</html>