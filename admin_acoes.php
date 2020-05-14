<?php

// Verificar se usuário tem permissão
session_start();

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
// ---

require_once('db.php');

$db = new dbClass();
$link = $db->conectar();

$action = $_POST['action'];
$target = $_POST['target'];
$motivo = isset($_POST['motivo']) ? $db->escape($_POST['motivo']) : 'Conta desativada';  
$duracao = isset($_POST['duracao']) ? intval($_POST['duracao']) : 0;
if ($duracao != 0 ){
    $duracao = ($duracao * 60) + time();
} 

if ($action == 'giveadmin') {
    $query = " UPDATE usuarios SET cargo = 'admin' WHERE usuario = '$target' ";
    $resultado = mysqli_query($link, $query);
    if ($resultado) {
        echo 1;
    } else {
        echo 0;
    }
} else if ($action == 'removeadmin') {
    if ($target == 'snipa'){
        echo 2;
        die();
    }
    
    $query = " UPDATE usuarios SET cargo = 'user' WHERE usuario = '$target' ";
    $resultado = mysqli_query($link, $query);
    if ($resultado) {
        echo 1;
    } else {
        echo 0;
    }
} else if ($action == 'desativar') {
    if ($target == 'snipa'){
        echo 2;
        die();
    }

    $query = " UPDATE usuarios SET status = 0, motivo = '$motivo', ban_time = '$duracao' WHERE usuario = '$target' ";
    $resultado = mysqli_query($link, $query);
    if ($resultado) {
        echo 1;
    } else {
        echo 0;
    }   
} else if ($action == 'desbloquearconta') {
    $query = " UPDATE usuarios SET status = 1, motivo = '', ban_time = NULL WHERE usuario = '$target' ";
    $resultado = mysqli_query($link, $query);
    if ($resultado) {
        echo 1;
    } else {
        echo 0;
    }
} else if ($action == 'novaprova') {
    $prova = $_POST['prova'];
    $ano = $_POST['ano'];
    $quantidade = $_POST['quantidade'];
    $vestibular = $_POST['vestibular'];

    $query = " insert into provas_pendente (nome, ano, qtd_questoes, vestibular) values ('$prova', '$ano', '$quantidade', '$vestibular') ";
    $resultado = mysqli_query($link, $query);
    if ($resultado) {
        echo 1;
    } else {
        echo 0;
    }
} else if ($action == 'excluirprova') {
    $id = $_POST['id'];

    $query = " DELETE FROM provas_pendente WHERE id = $id ";
    $resultado = mysqli_query($link, $query);
    if ($resultado) {
        echo 1;
    } else {
        echo 0;
    }
} else if ($action == 'excluirvest') {
    $id = $_POST['id'];

    $query = " DELETE FROM vestibulares WHERE id = $id ";
    $resultado = mysqli_query($link, $query);
    if ($resultado) {
        echo 1;
    } else {
        echo 0;
    }
} else if ($action == 'adicionarvest') {
    $vest = $_POST['vest'];

    // verificar se já existe
    $query = " select * from vestibulares where vestibular = '$vest' ";
    $resultado = mysqli_query($link, $query);
    if ($dados = mysqli_fetch_array($resultado)) {
        echo 2;
        die();
    } 
    // --

    $query = " insert into vestibulares (vestibular) values ('$vest') ";
    $resultado = mysqli_query($link, $query);
    if ($resultado) {
        echo 1;
    } else {
        echo 0;
    }
} else if ($action == 'aprovar') {
    $id = $_POST['aprovar'];

    // resgatar dados da prova
    $query = " SELECT pe.*, po.ano, po.vestibular FROM pendentes as pe INNER JOIN provas_pendente as po ON pe.id_c = po.id WHERE pe.id = '$id' ";
    $resultado = mysqli_query($link, $query);
    if ($dados = mysqli_fetch_array($resultado)) {
        $usuario = $dados['usuario'];
        $body = $dados['body'];
        $resa = $dados['resa'];
        $resb = $dados['resb'];
        $resc = $dados['resc'];
        $resd = $dados['resd'];
        $rese = $dados['rese'];
        $disciplina = $dados['disciplina'];
        $disciplina_tipo = $dados['disciplina_tipo'];
        $resposta = $dados['resposta'];
        $numero = $dados['numero'];
        $ano = $dados['ano'];
        $vestibular = $dados['vestibular'];
    } 
    // --

    $query2 = " insert into questoes (usuario, body, resa, resb, resc, resd, rese, disciplina, disciplina_tipo, resposta, numero, ano, vestibular) values ('$usuario', '$body', '$resa', '$resb', '$resc', '$resd', '$rese', '$disciplina', '$disciplina_tipo', '$resposta', $numero, $ano, '$vestibular') ";
    $resultado2 = mysqli_query($link, $query2);
    
    $query3 = " DELETE FROM pendentes WHERE id = $id ";
    $resultado3 = mysqli_query($link, $query3);
    if ($resultado3) {
        echo 1;
    } else {
        echo 0;
    }
} else if ($action == 'qtdquestoes') {
    $prova = $_POST['item'];
    $ano = $_POST['ano'];

    $sql = "select count(*) as qtd from questoes where vestibular = '$prova' and ano = $ano ";
    $resultado = mysqli_query($link, $sql);
    if ($dados = mysqli_fetch_array($resultado)) {
        echo $dados['qtd'];
    }
} else if ($action == 'carregarquestoes') {
    $simulado = isset($_POST['sim']) ? $_POST['sim'] : [] ;
    //$simulado = json_decode($simulado);

    var_dump($simulado);
    die();

    $sql = "select count(*) as qtd from questoes where vestibular = '$prova' and ano = $ano ";
    $resultado = mysqli_query($link, $sql);
    if ($dados = mysqli_fetch_array($resultado)) {
        echo $dados['qtd'];
    }
} else if ($action == 'adicionarresumo') {
    $resumo = $db->escape($_POST['resumo']);
    $disciplina = $db->escape($_POST['disciplina']);
    $tipo = $db->escape($_POST['tipo']);

    $sql = " insert into revisao (usuario, texto, materia, materia_tipo) values ('$target', '$resumo', '$disciplina', '$tipo') ";
    $resultado = mysqli_query($link, $sql);
    if ($resultado) {
        echo 1;
    } else {
        echo 0;
    }
}
