<?php

session_start();

if (!isset($_SESSION['login'])){
    header('Location: index.php?erro=5');
}

require_once('db.php');

$login = $_SESSION['login'];

$db = new dbClass();
$link = $db->conectar();

$id = $_POST['id'];

// adicionar questão
$query = " select texto from revisao where id = '$id' ";
$resultado = mysqli_query($link, $query);

if ($dados = mysqli_fetch_array($resultado)){
    echo $dados['texto'];
} else {
    echo 0;
}