<?php

session_start();

if (!isset($_SESSION['login'])){
    header('Location: index.php?erro=5');
}

require_once('db.php');

$login = $_SESSION['login'];

$db = new dbClass();
$link = $db->conectar();

$body_pergunta = $_POST['body_pergunta'];
$body_resa = $_POST['body_resa'];
$body_resb = $_POST['body_resb'];
$body_resc = $_POST['body_resc'];
$body_resd = $_POST['body_resd'];
$body_rese = $_POST['body_rese'];
$disciplina = $_POST['disciplina'];
$tipo = $_POST['tipo'];
$resposta = $_POST['resposta'];
$id = $_POST['id'];
$numero = intval($_POST['feita']);

// adicionar questão
$query = " insert into pendentes (usuario, body, resa, resb, resc, resd, rese, disciplina, disciplina_tipo, resposta, id_c, numero) values ('$login', '$body_pergunta', '$body_resa', '$body_resb', '$body_resc', '$body_resd', '$body_rese', '$disciplina', '$tipo', '$resposta', $id, $numero ) ";

if (mysqli_query($link, $query)){
    // Atualizar feitas
    $query2 = " UPDATE provas_pendente SET feita = feita + 1 WHERE id = '$id' ";
    $resultado2 = mysqli_query($link, $query2);
    // ---

    echo 1;
} else {
    echo 0;
}