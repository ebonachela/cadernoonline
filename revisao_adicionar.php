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

$id = isset($_GET['id']) ? $db->escape($_GET['id']) : '';

if (isset($id)) {
    
    // Carregar dados do resumo
    $query = " select * from revisao where id = $id and usuario = '$login' ";
    $resultado = mysqli_query($link, $query);

    if ($resultado){
        $dados = mysqli_fetch_array($resultado, MYSQLI_ASSOC);
    }
    // ---
}

?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    
    
    <title>Treine para os Vestibulres - Adicionar questões</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
    <script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <link rel="stylesheet" href="style2.css">

    <script type="text/x-mathjax-config">
        MathJax.Hub.Config({
            tex2jax: {inlineMath: [['$','$'], ['\\(','\\)']]},
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
                <li class="nav-item"><a class="nav-link nav-color" href="home.php">Home</a></li>
                <li class="nav-item"><a class="nav-link nav-color" href="sair.php">Sair</a></li>
            </ul>
        </div>
    </nav>
    
    <div class="container">

        <div class="modal fade imagem-box" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Escolha uma imagem</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input id="enviar" type="file" class="imgur" accept="image/*" data-max-size="5000"/>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary imagem-enviar">Enviar</button>
                    </div>
                </div>
            </div>
        </div>
        
        <br>
        <h2>Resumo</h2>
        <form id="imgur">
            <input id="enviar" type="file" class="imgur" accept="image/*" data-max-size="5000"/>
        </form>
        <textarea id="editor"><?= isset($dados) ? $dados['raw'] : 'Digite aqui o resumo' ?></textarea>
        
        <select class="custom-select" id="materia">
            <option value="0" selected>Disciplina</option>
            <option value="Português">Português</option>
            <option value="Matemática">Matemática</option>
            <option value="Física">Física</option>
            <option value="Química">Química</option>
            <option value="Biologia">Biologia</option>
            <option value="Geografia">Geografia</option>
            <option value="História">História</option>
            <option value="Literatura">Literatura</option>
            <option value="Inglês">Inglês</option>
            <option value="Espanhol">Espanhol</option>
        </select>
        
        <input type="text" class="form-control materia_tipo" placeholder="Parte da disciplina (Exemplo: Funções)" value='<?= isset($dados) ? $dados['materia_tipo'] : '' ?>'>
        
        <button id="btn-enviar" type="button" class="btn btn-success enviar" data-user=<?= $login ?> data-tipo='<?= $id ?>' data-materia='<?= isset($dados) ? $dados['materia'] : '' ?>'>Enviar</button>
        <a href="revisao_lista.php" class="btn btn-primary enviar">Lista</a>
        
    </div>
    
    <script>
        $("#imgur").hide();
        
        function salvarImagem(editor){
            $(".imagem-box").modal();
            
            $('.imagem-enviar').click(function() {
                $(".imagem-box").modal('hide');
                var $files = $('input[type=file]').get(0).files;
                
                if ($files.length) {
                    
                    if ($files[0].size > $('input[type=file]').data("max-size") * 1024) {
                        alert("Arquivo é muito grande, selecione um menor");
                        return false;
                    }
                    
                    var apiUrl = 'https://api.imgur.com/3/image';
                    var apiKey = 'c04033bd8f71280';
                    
                    var settings = {
                        async: false,
                        crossDomain: true,
                        processData: false,
                        contentType: false,
                        type: 'POST',
                        url: apiUrl,
                        headers: {
                            Authorization: 'Client-ID ' + apiKey,
                            Accept: 'application/json'
                        },
                        mimeType: 'multipart/form-data'
                    };
                    
                    var formData = new FormData();
                    formData.append("image", $files[0]);
                    settings.data = formData;
                    
                    $.ajax(settings).done(function(response) {
                        var r = JSON.parse(response).data;
                        var texto = editor.value();
                        var link = `![imagem](${r.link})`
                        editor.value(texto + link);
                        $("#enviar").val('');
                        $("#imgur").hide();
                    });
                    
                }
            });
        }
        
        
        // Corpo do resumo
        var simplemde = new SimpleMDE({ 
            element: document.getElementById("editor"),
            spellChecker: false,
            toolbar: [
            "bold", "italic", "heading", "quote", "unordered-list",
            "ordered-list", "link", {
                name: "imagem_imgur",
                action: salvarImagem,
                className: "fa fa-picture-o",
                title: "Enviar imagem",
            }, "table", "preview", "guide"
            ],
        });

        var button = document.querySelector(".fa-eye");
        button.addEventListener("click", () => {
            MathJax.Hub.Queue(["Typeset",MathJax.Hub, $(".editor-preview").html()]);
        });

        var liberado = false;

        // Enviar
        $('#btn-enviar').click(() => {
            var raw = simplemde.value();

            $(".fa-eye").click();
            var resumo = $(".editor-preview").html();
            var resumo_value = $(".editor-preview").val();

            if (resumo_value == "Digite aqui o resumo" || $('#materia').val() == 0 || $('.materia_tipo').val().length == 0) {
                alert("Preencha todos os campos antes de enviar");
                return;
            }

            var url_str = window.location.href;
            var url = new URL(url_str);
            var id = url.searchParams.get("id");
            var target = $('#btn-enviar').data('user');
            var modo = false;

            if ($('#btn-enviar').data('tipo')) {
                modo = 'editar';
                var id = parseInt($('#btn-enviar').data('tipo'));
            }

            $.ajax({
                url: 'novo_resumo.php',
                method: 'post',
                data: {
                    target,
                    resumo,
                    disciplina: $('#materia').val(),
                    tipo: $('.materia_tipo').val(),
                    modo,
                    id,
                    raw
                },
                success: function(d){
                    if (d == 1) {
                        alert('Resumo adicionado com sucesso');
                        liberado = true;
                        location.reload();
                    } else if (d == 0) {
                        alert('Erro ao tentar adicionar resumo');
                    }
                },
            });
        });

        var materia = $('#btn-enviar').data('materia');

        if (materia){
            $('#materia').val(materia);
            $('#btn-enviar').text('Atualizar');
        }

        window.onbeforeunload = function() {
            if (simplemde.value() != "Digite aqui o resumo" && !liberado) {
                return "Você tem certeza que desejar sair da página?";
            }
        }

    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
</body> 
</html>