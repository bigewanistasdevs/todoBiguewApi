<?php

require dirname(__DIR__).'../vendor/autoload.php';
use Ivmelo\SUAP\SUAP;

if(isset($_POST['matricula'])){

    try {
        $suap = new SUAP();
        $matricula =  $_POST['matricula'];
        $senha = $_POST['senha'];

        if($suap->autenticar($matricula, $senha)){
            
            if(strlen($matricula) == 14){
                    
                $meusDados = $suap->getMeusDados();
                
                //PEGA FOTO
                $f = $meusDados['url_foto_75x100'];
                $ft = 'https://suap.ifrn.edu.br'.$f;

                //PEGA ANO, PERIODO E TOTAL DE DISCIPLINAS
                $periodo = $suap->getMeusPeriodosLetivos();
                $count_periodo = count($periodo)-1;
                $ano = $periodo[$count_periodo]['ano_letivo'];
                $turmasVirtuais = $suap->getTurmasVirtuais($ano -1, 1);
                $n_disciplinas = count($turmasVirtuais);
                $boletins = $suap->getMeuBoletim($ano -1, 1);

                //PEGA FALTAS DAS DISCIOLINAS
                $faltas = array();
                for ($i = 1; $i <= $n_disciplinas; $i++) {
                    $falta = $boletins[$i-1]['numero_faltas'];
                    $faltas += [$boletins[$i-1]['codigo_diario'] => $falta];
                };

                //PEGA NOMES DAS DISCIPLINAS
                $disciplinas = array();
                for ($i = 1; $i <= $n_disciplinas; $i++) {
                    $dici = $boletins[$i-1]['disciplina'];
                    $disciplinas += [$boletins[$i-1]['codigo_diario'] => $dici];
                };

                //PEGA SITUAÇÕES DAS DISCIPLINAS
                $situacoesDisciplinas = array();
                for ($i = 1; $i <= $n_disciplinas; $i++) {
                    $situ = $boletins[$i-1]['situacao'];
                    $situacoesDisciplinas += [$boletins[$i-1]['codigo_diario'] => $situ];
                };

                //PEGA CARGA HORARIA DAS DISCIPLINAS
                $cargasHorarias = array();
                for ($i = 1; $i <= $n_disciplinas; $i++) {
                    $carga = $boletins[$i-1]['carga_horaria'];
                    $cargasHorarias += [$boletins[$i-1]['codigo_diario'] => $carga];
                };

                //PEGA CARGA HORARIA CUMPRIDA DAS DISCIPLINAS
                $cargasHorariasC = array();
                for ($i = 1; $i <= $n_disciplinas; $i++) {
                    $cargaC = $boletins[$i-1]['carga_horaria_cumprida'];
                    $cargasHorariasC += [$boletins[$i-1]['codigo_diario'] => $carga];
                };

                //PEGA PROFESSORES COM SEUS RESPECTIVOS NOMES E FOTOS
                $professores = array();
                $professores_nomes = array();
                $professores_fotos = array();
                $disciplinasTurmas = array();
                for ($i = 1; $i <= $n_disciplinas; $i++) {
                    $sigla = $turmasVirtuais[$i-1]['sigla'];
                    $nome = $turmasVirtuais[$i-1]['descricao'];
                    $disciplina = $sigla." - ".$nome;
                    for ($j = 1; $j <= $n_disciplinas; $j++) {
                        if($disciplina==$disciplinas[$boletins[$j-1]['codigo_diario']]){
                            $prof = $suap->getTurmaVirtual($turmasVirtuais[$i-1]['id']);
                            $proff = $prof['professores'][0]['matricula'];
                            $professores += [$boletins[$j-1]['codigo_diario'] => $proff];

                            $prof_n = $suap->getTurmaVirtual($turmasVirtuais[$i-1]['id']);
                            $profn = $prof_n['professores'][0]['nome'];
                            $professores_nomes += [$boletins[$j-1]['codigo_diario'] => $profn];

                            $prof_ft = $suap->getTurmaVirtual($turmasVirtuais[$i-1]['id']);
                            $proft = 'https://suap.ifrn.edu.br'.$prof_ft['professores'][0]['foto'];
                            $professores_fotos += [$boletins[$j-1]['codigo_diario'] => $proft];
                        };
                    };
                };

                $_SESSION['senha'] = $senha;
                $_SESSION['matricula'] = $matricula;
                $_SESSION['nome'] = $meusDados["nome_usual"];
                $_SESSION['email_aluno'] = $meusDados["email"];
                $_SESSION['vinculo'] = $meusDados["vinculo"]["situacao"];
                $_SESSION['foto_url'] = $ft;
                $_SESSION['disciplinas'] = $boletins;
                $_SESSION['disciplinas_nomes'] = $disciplinas;
                $_SESSION['disciplinas_cargas_horarias'] = $cargasHorarias;
                $_SESSION['disciplinas_cargas_horarias_cumpridas'] = $cargasHorariasC;
                $_SESSION['disciplinas_faltas'] = $faltas;
                $_SESSION['disciplinas_situacoes'] = $situacoesDisciplinas;
                $_SESSION['professores'] = $professores;
                $_SESSION['professores_nomes'] = $professores_nomes;
                $_SESSION['professores_fotos'] = $professores_fotos;
                $_SESSION['numero_disciplinas'] = $n_disciplinas;

            }elseif(strlen($matricula) == 7){
                
                //metodo back-door
                $_SESSION['matricula'] = $matricula;
                $_SESSION['PROF'] == true;

            };
            echo "Logado, olá ".$_SESSION['nome'];
        };
        //AQUI MANDA UM ELSE PARA OS PROFESSORES;;
    } catch (Exception $e) {
        echo "Não logado";
    };
};

?>

<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
    <form action="?" method="POST">
        Matrícula: <input type="text" name="matricula">
        Senha: <input type="password" name="senha">
        <input type="submit" value="vai">
    </form>
</body>
</html>