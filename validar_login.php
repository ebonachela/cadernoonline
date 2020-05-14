<?php

session_start();

require_once('db.php');

$db = new dbClass();
$link = $db->conectar();

$login = $db->escape($_POST['login']);
$senha = md5($db->escape($_POST['senha']));

$query = " select usuario, email, cargo, status, motivo, ban_time from usuarios where usuario = '$login' and senha = '$senha' ";
if ($resultado = mysqli_query($link, $query)){
    $dados = mysqli_fetch_array($resultado);

    if (isset($dados['usuario'])){
        // Verificar se conta está bloqueada
        if ($dados['ban_time'] < time() && $dados['ban_time'] != 0) {
            $user = $dados['usuario'];
            $query2 = " UPDATE usuarios SET status = 1, motivo = '', ban_time = NULL WHERE usuario = '$user' ";
            $resultado2 = mysqli_query($link, $query2);

            if ($resultado2) {
                $_SESSION['login'] = $dados['usuario'];
                $_SESSION['email'] = $dados['email'];
                $_SESSION['cargo'] = $dados['cargo'];

                header('Location: home.php');
                die();    
            } else {
                echo 'Erro na comunicação com o banco de dados, tente novamente mais tarde.';
                die();
            }

        } else if ($dados['status'] == 0) {
            $tempo_restante = $dados['ban_time'] - time();
            $horas = floor($tempo_restante / 3600);
            $minutos = floor(($tempo_restante / 60) % 60);
            $tempo_restante = ''.$horas.' horas e '.$minutos.' minutos';

            if ($dados['ban_time'] == 0){
                $tempo_restante = 'permanente';
            }

            //$tempo_restante = gmdate("H:i:s", $dados['ban_time'] - time());;
            header('Location: index.php?erro=8&motivo='.$dados['motivo'].'&duracao='.$tempo_restante.'');
            die();
        }
        // ---
        
        $_SESSION['login'] = $dados['usuario'];
        $_SESSION['email'] = $dados['email'];
        $_SESSION['cargo'] = $dados['cargo'];

        header('Location: home.php');
    } else {
        header('Location: index.php?erro=5');
    }

} else {
    echo 'Erro ao tentar realizar login, tente novamente mais tarde';
    die();
}
