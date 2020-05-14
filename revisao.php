<?php

session_start();

if (!isset($_SESSION['login'])){
    header('Location: index.php?erro=5');
}

$login = $_SESSION['login'];
$email = $_SESSION['email'];
$cargo = $_SESSION['cargo'];

?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    

    <title>Treine para os Vestibulres - Home</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
</head>
<body>
    
    <nav class="navbar navbar-expand-lg navbar-light bg-light site-header">
        <div class="container">
            <h4 class="navbar-brand">Treine para os <span id="vermelho">VESTS!</span></h4>
            <ul class="nav d-flex flex-column flex-md-row justify-content-end">
                <?php
                    if ($cargo === 'admin'){
                        echo '<li class="nav-item"><a class="nav-link nav-color" href="admin.php">Admin</a></li>';
                    }
                ?>
                <li class="nav-item"><a class="nav-link nav-color" href="adicionar_questoes.php">Adicionar questões</a></li>
                <li class="nav-item"><a class="nav-link nav-color" href="home.php">Home</a></li>
                <li class="nav-item"><a class="nav-link nav-color" href="sair.php">Sair</a></li>
            </ul>
        </div>
    </nav>
    
    <div class="container">
        <div class="row">
            <div class="botoes">
                <h1>Resumo</h1>
                <a href="revisao_adicionar.php" class="btn btn-primary">Adicionar</a>
                <a href="revisao_lista.php" class="btn btn-primary">Lista</a>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
</body> 
</html>