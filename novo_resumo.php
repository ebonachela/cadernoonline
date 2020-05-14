<?php

session_start();

if (!isset($_SESSION['login'])){
    header('Location: index.php?erro=5');
}

require_once('db.php');

$login = $_SESSION['login'];

$db = new dbClass();
$link = $db->conectar();

$target = $_POST['target'];
$resumo = $db->escape($_POST['resumo']);
$disciplina = $db->escape($_POST['disciplina']);
$tipo = $db->escape($_POST['tipo']);
$raw = $db->escape($_POST['raw']);

if ($_POST['modo'] == 'editar'){
    $id = $_POST['id'];

    $sql = " UPDATE revisao SET texto = '$resumo', materia = '$disciplina', materia_tipo = '$tipo', raw = '$raw' WHERE id = '$id' ";
    $resultado = mysqli_query($link, $sql);
    if ($resultado) {
        echo 1;
    } else {
        echo 0;
    }

    die();
}

$sql = " insert into revisao (usuario, texto, materia, materia_tipo, raw) values ('$target', '$resumo', '$disciplina', '$tipo', '$raw') ";
$resultado = mysqli_query($link, $sql);
if ($resultado) {
    echo 1;
} else {
    echo 0;
}