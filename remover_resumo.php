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
$query = " DELETE FROM revisao WHERE id = $id and usuario = '$login' ";

if (mysqli_query($link, $query)){
    echo 1;
} else {
    echo 0;
}