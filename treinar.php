<?php

session_start();

if (!isset($_SESSION['login'])){
    header('Location: index.php?erro=5');
}

$login = $_SESSION['login'];
$email = $_SESSION['email'];
$cargo = $_SESSION['cargo'];

require_once('db.php');

$db = new dbClass();
$link = $db->conectar();

// Carregar instituições
$sql = " select * from provas_pendente ";
$resultado = mysqli_query($link, $sql);
// ---

?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    

    <title>Treine para os Vestibulres - Home</title>
    <link rel="stylesheet" href="questoes.css">
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/css/bootstrap-select.min.css">
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/js/bootstrap-select.min.js"></script>
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
        <div id="caixa-pesquisa">
            <h1>Treine fazendo questões</h1>

            <div id="opcoes">
                <div class="instituicao">
                    <select id="inst" class="selectpicker" data-live-search="true" multiple title="Instituição" data-none-results-text="Não foi encontrado {0}">
                        <?php
                            if ($resultado){
                                while ($dados = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
                                    echo '<option value="'.$dados['vestibular'].'">'.$dados['vestibular'].'</option>';
                                }
                            }
                        ?>
                    </select>
                </div>

                <div class="instituicao">
                    <select id="ano-inst" class="selectpicker" data-live-search="true" multiple title="Ano" data-none-results-text="Não foi encontrado {0}">
                        <option value="2019">2019</option>
                        <option value="2018">2018</option>
                        <option value="2017">2017</option>
                        <option value="2016">2016</option>
                        <option value="2015">2015</option>
                        <option value="2014">2014</option>
                        <option value="2013">2013</option>
                        <option value="2012">2012</option>
                        <option value="2011">2011</option>
                        <option value="2010">2010</option>
                        <option value="2009">2009</option>
                        <option value="2008">2008</option>
                        <option value="2007">2007</option>
                        <option value="2006">2006</option>
                        <option value="2005">2005</option>
                        <option value="2004">2004</option>
                        <option value="2003">2003</option>
                        <option value="2002">2002</option>
                        <option value="2001">2001</option>
                        <option value="2000">2000</option>
                    </select>
                </div>
                <button id="start" type="button" class="btn btn-primary">Começar</button>
            </div>
        </div>

        <div class="questao">
            <div class="questao-header">
                <div class="header-q disciplina"><strong>FUVEST 2018 - Questão 1</strong> Matemática | Funções do 1º Grau</div>
            </div>
            
            <div class="pergunta-body">
                <p>Em junho de 1995, a seleção de rugby da África do Sul conquistou a Copa do Mundo dessa modalidade esportiva ao vencer a equipe da Nova Zelândia por 15 a 12, na cidade de Johannesburgo. O capitão sul‐africano, François Pienaar, recebeu a taça destinada à seleção campeã das mãos de Nelson Mandela.</p>
            </div>

            <div class="alternativas-body">
                <button type="button" class="btn btn-primary btn-block alternativa">
                    a) é um dos marcos do fim do Apartheid, devido à constituição de uma primeira seleção multirracial representando a África do Sul. 
                </button>
                <button type="button" class="btn btn-primary btn-block alternativa">
                    b) tornou‐se uma das justificativas para o veto à participação da África do Sul em eventos esportivos devido à proibição da presença de atletas brancos.  
                </button>
                <button type="button" class="btn btn-primary btn-block alternativa">
                    c) permitiu a vitória eleitoral de Mandela, apoiado massivamente pelos bôeres insuflados pelo nacionalismo sul‐africano. 
                </button>
                <button type="button" class="btn btn-primary btn-block alternativa">
                    d) desencadeou uma série de conflitos raciais entre negros e brancos devido às rivalidades entre os atletas da seleção sul‐africana. 
                </button>
                <button type="button" class="btn btn-primary btn-block alternativa">
                    e) foi realizado graças a um esforço conjunto de Nelson Mandela e de Frederik de Klerk, agraciados, por isso, com o prêmio Nobel da Paz. 
                </button>
            </div>
        </div>

        <div class="questao">
            <div class="questao-header">
                <div class="header-q disciplina"><strong>FUVEST 2018 - Questão 2</strong> Matemática | Funções do 1º Grau</div>
            </div>
            
            <div class="pergunta-body">
                <p>Em junho de 1995, a seleção de rugby da África do Sul conquistou a Copa do Mundo dessa modalidade esportiva ao vencer a equipe da Nova Zelândia por 15 a 12, na cidade de Johannesburgo. O capitão sul‐africano, François Pienaar, recebeu a taça destinada à seleção campeã das mãos de Nelson Mandela.</p>
            </div>

            <div class="alternativas-body">
                <button type="button" class="btn btn-primary btn-block alternativa">
                    a) <p>é um dos marcos do fim do Apartheid, devido à constituição de uma primeira seleção multirracial representando a África do Sul.</p> 
                </button>
                <button type="button" class="btn btn-primary btn-block alternativa">
                    b) tornou‐se uma das justificativas para o veto à participação da África do Sul em eventos esportivos devido à proibição da presença de atletas brancos.  
                </button>
                <button type="button" class="btn btn-primary btn-block alternativa">
                    c) permitiu a vitória eleitoral de Mandela, apoiado massivamente pelos bôeres insuflados pelo nacionalismo sul‐africano. 
                </button>
                <button type="button" class="btn btn-primary btn-block alternativa">
                    d) desencadeou uma série de conflitos raciais entre negros e brancos devido às rivalidades entre os atletas da seleção sul‐africana. 
                </button>
                <button type="button" class="btn btn-primary btn-block alternativa">
                    e) foi realizado graças a um esforço conjunto de Nelson Mandela e de Frederik de Klerk, agraciados, por isso, com o prêmio Nobel da Paz. 
                </button>
            </div>
        </div>

    </div>

    <script src="treinar.js"></script>
</body> 
</html>