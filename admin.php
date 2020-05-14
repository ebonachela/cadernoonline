<?php

session_start();

require_once('db.php');

$db = new dbClass();
$link = $db->conectar();

if (!isset($_SESSION['login'])){
    header('Location: index.php?erro=5');
}

$login = $_SESSION['login'];
$email = $_SESSION['email'];
$cargo = $_SESSION['cargo'];

if ($cargo !== 'admin') {
    unset($_SESSION['login']);
    unset($_SESSION['email']);
    unset($_SESSION['cargo']);
    header('Location: index.php?erro=7');
}

// Carregar users
$query = " select usuario, email, cargo from usuarios where status = 1 ";
$resultado = mysqli_query($link, $query);

$query2 = "select count(*) as qtd_usuarios from usuarios where status = 1 ";
$resultado2 = mysqli_query($link, $query2);
$dados = mysqli_fetch_array($resultado2, MYSQLI_ASSOC);
$qtd_usuarios = $dados['qtd_usuarios'];
// ---

// Carregar users bloqueados
$query3 = " select usuario, email, cargo, motivo, ban_time from usuarios where status = 0 ";
$resultado3 = mysqli_query($link, $query3);
// ---

// Carregar provas pendentes
$query4 = " select * from provas_pendente ";
$resultado4 = mysqli_query($link, $query4);
// ---

// Carregar vestibulares
$query5 = " select * from vestibulares ";
$resultado5 = mysqli_query($link, $query5);
$resultado6 = mysqli_query($link, $query5);
// ---

// Carregar pendentes
$query7 = " SELECT p.*, pa.nome FROM pendentes as p INNER JOIN provas_pendente as pa ON p.id_c=pa.id ";
$resultado7 = mysqli_query($link, $query7);
// --

?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    

    <title>Treine para os Vestibulres - Admin</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
</head>
<body>
    
    <nav class="navbar navbar-expand-lg navbar-light bg-light site-header">
        <div class="container">
            <h4 class="navbar-brand">Treine para os <span id="vermelho">VESTS!</span></h4>
            <ul class="nav d-flex flex-column flex-md-row justify-content-end">
                <li class="nav-item">
                    <a class="nav-link nav-color" href="home.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-color" href="sair.php">Sair</a>
                </li>
            </ul>
        </div>
    </nav>
    
    <div class="container">
        <div class="modal fade adicionar" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Nova requisição de prova</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">Título</span>
                            </div>
                            <input id="res-nome" type="text" class="form-control" placeholder="ENEM 1º Dia 2018">
                        </div>

                        <select class="custom-select" id="res-vestibular">
                            <option value="0" selected>Vestibular</option>
                            <?php
                                if ($resultado5){
                                    while ($dados = mysqli_fetch_array($resultado5, MYSQLI_ASSOC)) {
                                        echo '<option value="'.$dados['vestibular'].'">'.$dados['vestibular'].'</option>';
                                    };
                                }
                            ?>
                        </select>

                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">Ano</span>
                            </div>
                            <input id="res-ano" type="number" class="form-control" placeholder="2019">
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">Quantidade</span>
                            </div>
                            <input id="res-quantidade" type="number" class="form-control" placeholder="90">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="btn-enviarprova" type="button" class="btn btn-primary adicionar-enviar">Enviar</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade adicionarvest" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Nova requisição de prova</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">Vestibular</span>
                            </div>
                            <input id="res-nomevest" type="text" class="form-control" placeholder="FUVEST">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="btn-adicionarvest2" type="button" class="btn btn-primary adicionar-enviar">Enviar</button>
                    </div>
                </div>
            </div>
        </div>

        <br>

        <div align="center"><h1>Administração</h1></div>
        
        <br>
        
        <div class="row">
            <div class="col-3">
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">Usuários</a>
                    <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false">Usuários bloqueados</a>
                    <a class="nav-link" id="v-pills-provas-tab" data-toggle="pill" href="#v-pills-provas" role="tab" aria-controls="v-pills-provas" aria-selected="false">Provas</a>
                    <a class="nav-link" id="v-pills-vestibulares-tab" data-toggle="pill" href="#v-pills-vestibulares" role="tab" aria-controls="v-pills-vestibulares" aria-selected="false">Vestibulares</a>
                    <a class="nav-link" id="v-pills-pendentes-tab" data-toggle="pill" href="#v-pills-pendentes" role="tab" aria-controls="v-pills-pendentes" aria-selected="false">Pendentes</a>
                </div>
            </div>
            <div class="col-9">
                <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                        <h4>Quantidade: <?= $qtd_usuarios?></h4>

                        <!-- Renderizando cards -->
                        <?php
                            if ($resultado){
                                while ($dados = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
                                    echo '<div class="card w-75" id="user-'.$dados['usuario'].'">';
                                        echo '<div class="card-body">';
                                            echo '<h5 class="card-title">'.$dados['usuario'].'</h5>';
                                            echo '<p class="card-text">';
                                                echo '<strong>Cargo: </strong> '.$dados['cargo'].' ';
                                                echo '<strong>Email: </strong> '.$dados['email'].' ';
                                            echo '</p>';
                                            if ($dados['cargo'] == 'admin') {
                                                echo '<button type="button" class="btn btn-warning removeadmin" data-user='.$dados['usuario'].'>Remover admin</button>';
                                                echo ' ';
                                            } else {
                                                echo '<button type="button" class="btn btn-success giveadmin" data-user='.$dados['usuario'].'>Dar admin</button>';
                                                echo ' ';
                                            }
                                            echo '<button type="button" class="btn btn-danger excluirconta" data-user='.$dados['usuario'].'>Desativar conta</button>';
                                        echo '</div>';
                                    echo '</div>';
                                }
                            }
                        ?>

                    </div>
                    
                    <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                        <?php
                            if ($resultado){
                                while ($dados = mysqli_fetch_array($resultado3, MYSQLI_ASSOC)) {
                                    $ban_time = $dados['ban_time'];
                                    if ($ban_time > time()){
                                        $tempo_restante = $ban_time - time();
                                        $horas = floor($tempo_restante / 3600);
                                        $minutos = floor(($tempo_restante / 60) % 60);
                                        $tempo_restante = ''.$horas.' horas e '.$minutos.' minutos';
                                        //$tempo_restante = gmdate("H:i:s", $tempo_restante);
                                    } else if ($ban_time == 0) {
                                        $tempo_restante = 'Permanente';
                                    } else {
                                        $tempo_restante = 'Expirou';
                                    }

                                    echo '<div class="card w-75">';
                                        echo '<div class="card-body">';
                                            echo '<h5 class="card-title">'.$dados['usuario'].'</h5>';
                                            echo '<p class="card-text">';
                                                echo '<strong>Cargo: </strong> '.$dados['cargo'].' ';
                                                echo '<strong>Email: </strong> '.$dados['email'].'<br>';
                                                echo '<strong>Motivo: </strong> '.$dados['motivo'].'<br>';
                                                echo '<strong>Tempo restante: </strong> '.$tempo_restante.' ';
                                            echo '</p>';
                                            echo '<button type="button" class="btn btn-success desbloquearconta" data-user='.$dados['usuario'].'>Desbloquear conta</button>';
                                        echo '</div>';
                                    echo '</div>';
                                }
                            }
                        ?>
                    </div>

                    <div class="tab-pane fade" id="v-pills-provas" role="tabpanel" aria-labelledby="v-pills-provas-tab">
                        <button id="btn-adicionar" type="button" class="btn btn-success">Adicionar</button>
                        <br><br>
                        <!-- Renderizando provas -->
                        <?php
                            if ($resultado4){
                                while ($dados = mysqli_fetch_array($resultado4, MYSQLI_ASSOC)) {
                                    echo '<div class="card w-75">';
                                        echo '<div class="card-body">';
                                            echo '<h5 class="card-title">'.$dados['nome'].'</h5>';
                                            echo '<p class="card-text">';
                                                echo '<strong>Ano: </strong> '.$dados['ano'].' ';
                                                echo '<strong>Quantidade: </strong> '.$dados['qtd_questoes'].' ';
                                                echo '<strong>Feitas: </strong> '.$dados['feita'].' ';
                                                echo '<strong>Vestibular: </strong> '.$dados['vestibular'].' ';
                                            echo '</p>';
                                            echo '<button type="button" class="btn btn-danger excluirprova" data-id='.$dados['id'].'>Excluir</button>';
                                        echo '</div>';
                                    echo '</div>';
                                }
                            }
                        ?>
                    </div>

                    <div class="tab-pane fade" id="v-pills-vestibulares" role="tabpanel" aria-labelledby="v-pills-vestibulares-tab">
                        <button id="btn-adicionarvest" type="button" class="btn btn-success">Adicionar</button>
                        <br><br>
                        <?php
                            if ($resultado6){
                                while ($dados = mysqli_fetch_array($resultado6, MYSQLI_ASSOC)) {
                                    echo '<div class="card w-75">';
                                        echo '<div class="card-body">';
                                            echo '<h5 class="card-title">'.$dados['vestibular'].'</h5>';
                                            echo '<button id="btn-excluirvest" type="button" class="btn btn-danger btn-excluirvest" data-id='.$dados['id'].'>Excluir</button>';
                                        echo '</div>';
                                    echo '</div>';
                                }
                            }
                        ?>
                    </div>

                    <div class="tab-pane fade" id="v-pills-pendentes" role="tabpanel" aria-labelledby="v-pills-pendentes-tab">
                        <?php
                            if ($resultado7){
                                while ($dados = mysqli_fetch_array($resultado7, MYSQLI_ASSOC)) {
                                    echo '<div class="card w-75" id="card-'.$dados['id'].'">';
                                        echo '<div class="card-body">';
                                            echo '<h5 class="card-title">'.$dados['nome'].'</h5>';
                                            echo '<p class="card-text">';
                                                echo '<strong>Usuário: </strong> '.$dados['usuario'].' ';
                                                echo '<strong>Questão: </strong> '.$dados['numero'].'<br>';
                                                echo '<strong>Disciplina: </strong> '.$dados['disciplina'].' ';
                                                echo '<strong>Disciplina Tipo: </strong> '.$dados['disciplina_tipo'].' ';
                                                echo '<strong>Resposta: </strong> '.$dados['resposta'].' ';
                                            echo '</p>';
                                            echo '<button type="button" class="btn btn-primary options-btn preview" data-aprovar="'.$dados['id'].'">Preview</button>';
                                            echo '<button type="button" class="btn btn-success options-btn aprovar" data-aprovar="'.$dados['id'].'">Aprovar</button>';
                                            echo '<button type="button" class="btn btn-warning options-btn editar" data-aprovar="'.$dados['id'].'">Editar</button>';
                                        echo '</div>';
                                    echo '</div>';
                                }
                            }
                        ?>
                    </div>

                </div>
            </div>
        </div>
    </div>

    
    <script>

        $(document).ready(function () {
            // Verificar se a card já existe para atribuir o evento de click
            function loadCards() {
                if( $('.excluirconta').length ) {
                    $('.excluirconta').click(function() {
                        var target = $(this).data('user');

                        $("#user-"+target+"").append('<div class="card-body"> <form> <div class="input-group mb-3"> <div class="input-group-prepend"> <span class="input-group-text" id="basic-addon1">Motivo</span> </div> <input type="text" class="form-control" id="motivo-'+target+'" placeholder="Conta desativada" required> </div> <div class="input-group mb-3"> <div class="input-group-prepend"> <span class="input-group-text" id="basic-addon1">Duração</span> </div> <input type="number" class="form-control" id="duracao-'+target+'" placeholder="Em minutos (1 dia = 1440, 0 = permanente)" required> </div> <button type="button" class="btn btn-danger btn-bloquear">Bloquear</button> </form> <br> </div>');
                        $('#motivo-'+target+'').focus();
                        $(this).css("opacity","0.7");
                        $(this).off();

                        $('.btn-bloquear').click(() =>{
                            if ($('#motivo-'+target+'').val().length <= 0 || $('#duracao-'+target+'').val().length <= 0) {
                                alert('Preencha as opções de banimento!');
                                return;
                            }
                            
                            $.ajax({
                                url: 'admin_acoes.php',
                                method: 'post',
                                data: {
                                    action: 'desativar',
                                    target,
                                    motivo: $('#motivo-'+target+'').val(),
                                    duracao: $('#duracao-'+target+'').val()
                                },
                                success: function(d){
                                    if (d == 1) {
                                        location.reload();
                                    } else if (d == 0) {
                                        alert('Erro ao tentar desativar a conta');
                                    } else if (d == 2){
                                        alert('Você não tem permissão para realizar essa ação');
                                    }
                                },
                            });
                        });
                        
                    });

                    $('.giveadmin').click(function() {
                        $.ajax({
                            url: 'admin_acoes.php',
                            method: 'post',
                            data: {
                                action: 'giveadmin',
                                target: $(this).data('user')
                            },
                            success: function(d){
                                if (d == 1) {
                                    location.reload();
                                } else if (d == 0) {
                                    alert('Erro ao tentar dar admin');
                                }
                            },
                        });
                    });

                    $('.removeadmin').click(function() {
                        $.ajax({
                            url: 'admin_acoes.php',
                            method: 'post',
                            data: {
                                action: 'removeadmin',
                                target: $(this).data('user')
                            },
                            success: function(d){
                                if (d == 1) {
                                    location.reload();
                                } else if (d == 0) {
                                    alert('Erro ao tentar remover admin');
                                } else if (d == 2){
                                    alert('Você não tem permissão para realizar essa ação');
                                }
                            },
                        });
                    });

                    $('.desbloquearconta').click(function() {
                        $.ajax({
                            url: 'admin_acoes.php',
                            method: 'post',
                            data: {
                                action: 'desbloquearconta',
                                target: $(this).data('user')
                            },
                            success: function(d){
                                if (d == 1) {
                                    location.reload();
                                } else if (d == 0) {
                                    alert('Erro ao tentar remover admin');
                                }
                            },
                        });
                    });

                    $('.excluirprova').click(function(){
                        var id = $(this).data('id');
                
                        $.ajax({
                            url: 'admin_acoes.php',
                            method: 'post',
                            data: {
                                action: 'excluirprova',
                                target: 'none',
                                id
                            },
                            success: function(d){
                                if (d == 1) {
                                    location.reload();
                                } else if (d == 0) {
                                    alert('Erro ao tentar remover prova');
                                }
                            },
                        });
                    });

                    $('#btn-adicionarvest').click(() => {
                        $('.adicionarvest').modal('show');
                        
                        $('#btn-adicionarvest2').click(() => {
                            var vest = $('#res-nomevest').val()

                            if (vest.length == 0){
                                alert('Preencha os campos antes de enviar');
                                return;
                            }

                            $.ajax({
                            url: 'admin_acoes.php',
                            method: 'post',
                            data: {
                                action: 'adicionarvest',
                                target: 'none',
                                vest
                            },
                            success: function(d){
                                if (d == 1) {
                                    location.reload();
                                } else if (d == 0) {
                                    alert('Erro ao tentar remover prova');
                                } else if (d == 2){
                                    alert('Esse vestibular já está registrado');
                                }
                            },
                        });
                        });
                    });

                    $('.btn-excluirvest').click(function() {
                        $.ajax({
                            url: 'admin_acoes.php',
                            method: 'post',
                            data: {
                                action: 'excluirvest',
                                target: 'none',
                                id: $(this).data('id')
                            },
                            success: function(d){
                                if (d == 1) {
                                    location.reload();
                                } else if (d == 0) {
                                    alert('Erro ao tentar remover prova');
                                } 
                            },
                        });
                    });

                    $('.aprovar').click(function() {
                        var aprovar = $(this).data('aprovar');

                        $.ajax({
                            url: 'admin_acoes.php',
                            method: 'post',
                            data: {
                                action: 'aprovar',
                                target: 'none',
                                aprovar
                            },
                            success: function(d){
                                if (d == 1) {
                                    $('#card-'+ aprovar +'').hide();
                                } else if (d == 0) {
                                    alert('Erro ao tentar aprovar prova');
                                } 
                            },
                        });
                    });

                } else {
                    loadCards();
                }
            }
            
            loadCards();

            $('#btn-adicionar').click(() => {
                $('.adicionar').modal('show');

                $('#btn-enviarprova').click(() => {                    
                    var prova = $('#res-nome').val();
                    var ano = $('#res-ano').val();
                    var quantidade = $('#res-quantidade').val();
                    var vestibular = $('#res-vestibular').val();

                    if (prova.length == 0 || ano.length == 0 || quantidade.length == 0 || vestibular.length == 0){
                        alert('Preencha todos os campos antes de enviar');
                        return;
                    }

                    $.ajax({
                        url: 'admin_acoes.php',
                        method: 'post',
                        data: {
                            action: 'novaprova',
                            target: 'none',
                            prova,
                            ano,
                            quantidade,
                            vestibular
                        },
                        success: function(d){
                            if (d == 1) {
                                location.reload();
                            } else if (d == 0) {
                                alert('Erro ao tentar adicionar nova prova');
                                $('.adicionar').modal('hide');
                            }
                        },
                    });
                });
            });
        });
        
    </script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
</body> 
</html>