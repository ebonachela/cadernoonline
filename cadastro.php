<?php

require_once('db.php');

$db = new dbClass();
$link = $db->conectar();

$login = $db->escape($_POST['login']);
$email = $db->escape($_POST['email']);
$senha = $db->escape($_POST['senha']);
$cosenha = $db->escape($_POST['cosenha']);

if ($senha !== $cosenha){
    header('Location: index.php?erro=1');
    die();
}

$login_existe = false;
$email_existe = false;

//verificar user
$query = " select * from usuarios where usuario = '$login' ";
if ($resultado = mysqli_query($link, $query)){
    $dados = mysqli_fetch_array($resultado);

    if (isset($dados['usuario'])){
        $login_existe = true;
    }

} else {
    echo 'Erro ao tentar realizar cadastro, tente novamente mais tarde';
    die();
}

//verificar email
$query = " select * from usuarios where email = '$email' ";
if ($resultado = mysqli_query($link, $query)){
    $dados = mysqli_fetch_array($resultado);

    if (isset($dados['email'])){
        $email_existe = true;
    }

} else {
    echo 'Erro ao tentar realizar cadastro, tente novamente mais tarde';
    die();
}

if ($login_existe){
    header('Location: index.php?erro=2');
    die();
} else if ($email_existe){
    header('Location: index.php?erro=3');
    die();
}

$md5_senha = md5($senha);

//cadastrar
$query = " insert into usuarios (usuario, email, senha) values ('$login', '$email', '$md5_senha') ";

if (mysqli_query($link, $query)){
    header('Location: index.php?erro=4');
} else {
    echo 'Erro ao tentar realizar cadastro, tente novamente mais tarde';
}
