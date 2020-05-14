var simulado = [];

$(document).ready(function() {
    $('#start').click(function(){
        if ($('#inst').selectpicker('val').length == 0 || $('#ano-inst').selectpicker('val').length == 0 ) {
            alert('Preencha as opções antes de começar');
            return;
        }
        
        montarProva($('#inst').selectpicker('val'), $('#ano-inst').selectpicker('val'));
        //console.log(simulado);
    });
});

function misturar(arra1) {
    let ctr = arra1.length;
    let temp;
    let index;

    while (ctr > 0) {
        index = Math.floor(Math.random() * ctr);
        ctr--;
        temp = arra1[ctr];
        arra1[ctr] = arra1[index];
        arra1[index] = temp;
    }

    return arra1;
}

function montarProva(prova, anos) {
    simulado = [];

    prova.forEach(function(item) {
        anos.forEach(function(ano){

            $.ajax({
                url: 'admin_acoes.php',
                method: 'post',
                data: {
                    action: 'qtdquestoes',
                    target: 'none',
                    item,
                    ano
                },
                success: function(d){
                    let k = parseInt(d);
                    let temp = [];
                    for (i = 1; i <= k; i++){
                        //simulado.push([item, [ano, i]]);
                        temp.push({'prova': item, 'ano': ano, 'count': i});
                    }

                    misturar(temp);
                    saveSimulado(temp);
                },
            });

        });
    });


}

function saveSimulado(data) {
    simulado.push(data);

    console.log(simulado);
}

function carregarQuestoes(sim) {

    let id = sim;
    console.log(id);

    $.ajax({
        url: 'admin_acoes.php',
        method: 'post',
        data: {
            action: 'carregarquestoes',
            target: 'none',
            sim: id
        },
        success: function(d){
            alert(d);
        },
    });

}