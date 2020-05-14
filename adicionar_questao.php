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

$id = $db->escape($_GET['id']);

// Carregar dados da prova
$query = " select * from provas_pendente where id = '$id' ";
$resultado = mysqli_query($link, $query);

if ($resultado = mysqli_query($link, $query)){
    $dados = mysqli_fetch_array($resultado);

    $nome = $dados['nome'];
    $qtd_questoes = $dados['qtd_questoes'];
    $feita = $dados['feita'];
    if ($feita == $qtd_questoes && $feita != '') {
        header('Location: adicionar_questoes.php');
    } else if ($feita == '') {
        header('Location: adicionar_questoes.php');
    } else {
        $feita++;
    }
} else {
    header("Location: adicionar_questoes.php");
    die();
}
// ---

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
    <link rel="stylesheet" href="style.css">
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
                
        <div align="center">
            <h3 class="status">Prova: <?= isset($nome) ? $nome : '' ?></h3>
            <h3 class="status">Questão: <span id="feita"><?= isset($feita) ? $feita : '' ?></span></h3>
        </div>
        <br>
        <h2>Corpo da pergunta</h2>
        <form id="imgur">
            <input id="enviar" type="file" class="imgur" accept="image/*" data-max-size="5000"/>
        </form>
        <textarea id="editor">Digite aqui a pergunta</textarea>
        <h3>Alternativa A</h3>
        <textarea id="res_A">Digite aqui a alternativa A</textarea>
        <h3>Alternativa B</h3>
        <textarea id="res_B">Digite aqui a alternativa B</textarea>
        <h3>Alternativa C</h3>
        <textarea id="res_C">Digite aqui a alternativa C</textarea>
        <h3>Alternativa D</h3>
        <textarea id="res_D">Digite aqui a alternativa D</textarea>
        <h3>Alternativa E</h3>
        <textarea id="res_E">Digite aqui a alternativa E</textarea>
        
        <select class="custom-select" id="materia">
            <option value="0" selected>Disciplina</option>
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
        
        <input type="text" class="form-control materia_tipo" placeholder="Parte da disciplina (Exemplo: Funções)">

        <select class="custom-select resposta" id="resposta">
            <option value="0" selected>Resposta</option>
            <option value="A">A</option>
            <option value="B">B</option>
            <option value="C">C</option>
            <option value="D">D</option>
            <option value="E">E</option>
        </select>
        
        <button type="button" class="btn btn-success enviar">Enviar</button>
        
    </div>
    
    <script>
        $("#imgur").hide();
        
        function salvarImagem(editor){
            //$("#imgur").show();
            $(".imagem-box").modal();
            //$(".fa-eye").click();
            //alert($(".editor-preview").html());
            //$('input[type=file]').on("change", function() {
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
        
        
        // Corpo da pergunta
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
        
        // Respostas
        var res_A = new SimpleMDE({ 
            element: document.getElementById("res_A"),
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
        
        var res_B = new SimpleMDE({ 
            element: document.getElementById("res_B"),
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
        
        var res_C = new SimpleMDE({ 
            element: document.getElementById("res_C"),
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
        
        var res_D = new SimpleMDE({ 
            element: document.getElementById("res_D"),
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
        
        var res_E = new SimpleMDE({ 
            element: document.getElementById("res_E"),
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

        // Enviar
        $('.enviar').click(() => {
            $(".fa-eye").click();
            var all = [];
            var _all = $(".editor-preview").map(function() {
                return all.push(this.innerHTML);
            }).get();

            if (all[0].length == 0 || all[1].length == 0 || all[2].length == 0 || all[3].length == 0 || all[4].length == 0 || all[5].length == 0 || $('#materia').val().length == 0 || $('.materia_tipo').val().length == 0 || $('#resposta').val() == 0) {
                alert('Preencha todos os campos antes de enviar');
                return;
            }

            var url_str = window.location.href;
            var url = new URL(url_str);
            var id = url.searchParams.get("id");

            $.ajax({
                url: 'nova_questao.php',
                method: 'post',
                data: {
                    body_pergunta: all[0],
                    body_resa: all[1],
                    body_resb: all[2],
                    body_resc: all[3],
                    body_resd: all[4],
                    body_rese: all[5],
                    disciplina: $('#materia').val(),
                    tipo: $('.materia_tipo').val(),
                    resposta: $('#resposta').val(),
                    id,
                    feita: $('#feita').html()
                },
                success: function(d){
                    if (d == 1) {
                        window.scrollTo(0, 0);
                        location.reload();
                    } else if (d == 0) {
                        alert('Erro ao tentar adicionar questão');
                    }
                },
            });
        });

    </script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
</body> 
</html>