<?php

require_once('db.php');

$db = new dbClass();
$link = $db->conectar();

$id = $_GET['id'];

$query = " select texto from revisao where id = '$id' ";
$resultado = mysqli_query($link, $query);

if ($dados = mysqli_fetch_array($resultado)){
    echo $dados['texto'];
} else {
    echo 0;
}

?>