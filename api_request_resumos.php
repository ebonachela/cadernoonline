<?php

require_once('db.php');

$db = new dbClass();
$link = $db->conectar();

$login = $db->escape($_GET['login']);
$senha = md5($db->escape($_GET['senha']));

$pagina = isset($_GET['pagina']) ? $db->escape($_GET['pagina']) : 1;
$limite = 20;

$pagina = ($pagina - 1) * $limite;

$query = " select usuario, email, cargo, status, motivo, ban_time from usuarios where usuario = '$login' and senha = '$senha' ";
if ($resultado = mysqli_query($link, $query)){
    $dados = mysqli_fetch_array($resultado);

    if (isset($dados['usuario'])){
        $sql = " select id, materia, materia_tipo, data from revisao where usuario = '$login' order by data desc limit $pagina, $limite ";
        if ($resultado2 = mysqli_query($link, $sql)){

            while ($dados2 = mysqli_fetch_array($resultado2, MYSQLI_ASSOC)) {                        
                echo $dados2['materia'], ' - ', $dados2['materia_tipo'], '.', $dados2['id'] ,';';
            }

        }
    }

} else {
    echo 'Erro ao tentar realizar login, tente novamente mais tarde';
    die();
}
