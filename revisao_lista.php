<?php

session_start();

if (!isset($_SESSION['login'])){
    header('Location: index.php?erro=5');
}

$login = $_SESSION['login'];
$email = $_SESSION['email'];
$cargo = $_SESSION['cargo'];

require_once('db.php');

$db = new dbClass();
$link = $db->conectar();

$pagina = isset($_GET['pagina']) ? $db->escape($_GET['pagina']) : 1;
$limite = 20;

if (!is_numeric($pagina)){
    header('Location: revisao_lista.php');
}

$pagina = ($pagina - 1) * $limite;

// Carregar lista de resumos

if (isset($_GET['disciplina']) && $_GET['disciplina'] != '') {
    $disciplina = $db->escape($_GET['disciplina']);

    if (isset($_GET['pesquisa'])) {
        $pesquisa = $db->escape($_GET['pesquisa']);
        $sql = " select id, materia, materia_tipo, data from revisao where usuario = '$login' and materia = '$disciplina' and materia_tipo like '%$pesquisa%' order by data desc limit $pagina, $limite ";
        $sql_count = " select count(*) as qtd from revisao where usuario = '$login' and materia = '$disciplina' and materia_tipo like '%$pesquisa%' ";
    } else {
        $sql = " select id, materia, materia_tipo, data from revisao where usuario = '$login' and materia = '$disciplina' order by data desc limit $pagina, $limite ";
        $sql_count = " select count(*) as qtd from revisao where usuario = '$login' and materia = '$disciplina' ";
    }

} else {

    if (isset($_GET['pesquisa'])) {
        $pesquisa = $db->escape($_GET['pesquisa']);
        $sql = " select id, materia, materia_tipo, data from revisao where usuario = '$login' and materia_tipo like '%$pesquisa%' order by data desc limit $pagina, $limite ";
        $sql_count = " select count(*) as qtd from revisao where usuario = '$login' and materia_tipo like '%$pesquisa%' ";
    } else {
        $sql = " select id, materia, materia_tipo, data from revisao where usuario = '$login' order by data desc limit $pagina, $limite ";
        $sql_count = " select count(*) as qtd from revisao where usuario = '$login' ";
    }

}

$resultado = mysqli_query($link, $sql);
$resultado_count = mysqli_query($link, $sql_count);

if ($resultado_count){
    $dados = mysqli_fetch_array($resultado_count, MYSQLI_ASSOC);
    $total_itens = $dados['qtd'];
}
// ---

?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    

    <title>Treine para os Vestibulres - Resumo</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>

    <script type="text/x-mathjax-config">
        MathJax.Hub.Config({
            tex2jax: {inlineMath: [['$','$'], ['\\(','\\)']]},
            "HTML-CSS": {
                linebreaks: { automatic: true, width: "container" }   
            },
            messageStyle: "none",
            showMathMenu: false
        });
    </script>

    <script src='https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.5/MathJax.js?config=TeX-MML-AM_CHTML' async></script>
</head>
<body>
    
    <nav class="navbar navbar-expand-lg navbar-light bg-light site-header">
        <div class="container">
            <h4 class="navbar-brand">Treine para os <span id="vermelho">VESTS!</span></h4>
            <ul class="nav d-flex flex-column flex-md-row justify-content-end">
                <?php
                    if ($cargo === 'admin'){
                        echo '<li class="nav-item"><a class="nav-link nav-color" href="admin.php">Admin</a></li>';
                    }
                ?>
                <li class="nav-item"><a class="nav-link nav-color" href="revisao_adicionar.php">Adicionar resumo</a></li>
                <li class="nav-item"><a class="nav-link nav-color" href="home.php">Home</a></li>
                <li class="nav-item"><a class="nav-link nav-color" href="sair.php">Sair</a></li>
            </ul>
        </div>
    </nav>
    
    <div class="container">

        <div class="modal fade preview-box" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="preview-title"><span class="titulores">Teste</span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="res-body" class="verresumo"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary res-editar" data-dismiss="modal">Editar</button>
                        <button type="button" class="btn btn-danger res-remover" data-dismiss="modal">Remover</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <h3 style="position: absolute; top: 90px;">Total: <?= isset($total_itens) ? $total_itens : 0 ?></h3>
            <div class="filtro">
                <input type="text" class="form-control pesquisa" placeholder="Nome da matéria">
                <select class="custom-select filtro-item" id="materia">
                    <option value="0">Disciplina</option>
                    <option value="Português">Português</option>
                    <option value="Matemática">Matemática</option>
                    <option value="Física">Física</option>
                    <option value="Química">Química</option>
                    <option value="Biologia">Biologia</option>
                    <option value="Geografia">Geografia</option>
                    <option value="História">História</option>
                    <option value="Inglês">Inglês</option>
                    <option value="Espanhol">Espanhol</option>
                </select>
                <button id="filtrar" class="btn btn-success filtro-item" data-itens="<?= isset($total_itens) ? $total_itens : 0 ?>">Filtrar</button>
            </div>
        </div>
        <div class="row">  
            <?php
                if (mysqli_num_rows($resultado) == 0){
                    echo '<div id="erro-resumo">Não foi encontrado nenhum resumo</div>';
                }

                if ($resultado){
                    while ($dados = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {                        
                        echo '<button type="button" class="btn btn-primary btn-lg btn-block resumo '.$dados['materia'].'" data-id='.$dados['id'].' data-titulo="'.$dados['materia_tipo'].'">';
                            echo '<span class="res-title"> '.$dados['materia'].' - '.$dados['materia_tipo'].' </span>';
                            echo '<span class="res-data"> '.$dados['data'].' </span>';
                        echo '</button>';
                    }
                }
            ?>
        </div>
        <div class="row">
            <div class="paginacao">
                <button type="button" class="btn teste1">&#8592;</button>
                <button type="button" class="btn teste2">&#8594;</button>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            var u = window.location.href;
            var ur = new URL(u);
            var dis = ur.searchParams.get("disciplina");
            var dis_n = ur.searchParams.get("pesquisa");
            if (dis) {
                $('#materia').val(dis);
            } else {
                $('#materia').val(0);
            }

            if (dis_n) {
                $('.pesquisa').val(dis_n);
            }

            $('.teste2').click(function() {
                var id = 1;
                var url_str = window.location.href;
                var url = new URL(url_str);
                var id_s = url.searchParams.get("pagina");
                id += parseInt(id_s);

                let url2 = url;
                let pesquisa = url.searchParams.get("pesquisa");
                let disciplina = url.searchParams.get("disciplina");

                let total = $('#filtrar').data('itens');
                let max_pages = Math.ceil(total / 20);
                
                if (id > max_pages) return;

                if (id) {
                    url2 = "revisao_lista.php?pagina="+id;

                    if (disciplina) {
                        url2 += "&disciplina="+disciplina;
                    }
                    if (pesquisa) {
                        url2 += "&pesquisa="+pesquisa;
                    } 
                    
                    window.location.href = url2;
                } else {
                    id = 2;
                    if (id > max_pages) return;

                    url2 = "revisao_lista.php?pagina=2";

                    if (disciplina) {
                        url2 += "&disciplina="+disciplina;
                    }
                    if (pesquisa) {
                        url2 += "&pesquisa="+pesquisa;
                    }
                    
                    window.location.href = url2;
                }
            });

            $('.teste1').click(function() {
                var url_str = window.location.href;
                var url = new URL(url_str);
                var id = parseInt(url.searchParams.get("pagina"));
                if (id == 1) return;
                id--;

                let url2 = url;
                let pesquisa = url.searchParams.get("pesquisa");
                let disciplina = url.searchParams.get("disciplina");

                if (id) {
                    url2 = "revisao_lista.php?pagina="+id;
                    
                    if (disciplina) {
                        url2 += "&disciplina="+disciplina;
                    }
                    if (pesquisa) {
                        url2 += "&pesquisa="+pesquisa;
                    } 
                    

                    window.location.href = url2;
                }
            });

            var g_id = 'null';

            $('.resumo').click(function() {
                var id = $(this).data('id');
                var titulo = $(this).data('titulo');
                g_id = id;
                $.ajax({
                    url: 'resumo_get.php',
                    method: 'post',
                    data: {
                        id
                    },
                    success: function(d){
                        $('#res-body').html(d);
                        $('.titulores').html(titulo);
                        MathJax.Hub.Queue(["Typeset",MathJax.Hub, $('#res-body').html()]);
                        $('.preview-box').modal();
                        
                        if (d == 0) {
                            alert('Erro ao tentar recuperar resumo');
                        }
                    },
                });
            });

            $('.res-remover').click(function(){
                if (g_id == 'null'){
                    alert('Erro, id é nulo');
                    return;
                }

                if (!confirm("Tem certeza que deseja remover esse resumo?")) {
                    return;
                } 

                $.ajax({
                    url: 'remover_resumo.php',
                    method: 'post',
                    data: {
                        id: g_id
                    },
                    success: function(d){
                        if (d == 1) {
                            alert('Resumo removido com sucesso');
                            location.reload();
                        } else if (d == 0) {
                            alert('Erro ao tentar remover resumo');
                        }
                    },
                });
            });

            $('.res-editar').click(function(){
                if (g_id == 'null'){
                    alert('Erro, id é nulo');
                    return;
                }

                window.location.href = "revisao_adicionar.php?id="+g_id;
            });

            $('#filtrar').click(function(){
                let disciplina = $('#materia').val();
                let pesquisa = $('.pesquisa').val();
                let url = "revisao_lista.php?";

                if (disciplina != 0) {
                    url += "disciplina="+disciplina+"&";
                } 
                
                if (pesquisa.length > 0){
                    url += "pesquisa="+pesquisa;
                }

                window.location.href = url;
            });

        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
</body> 
</html>