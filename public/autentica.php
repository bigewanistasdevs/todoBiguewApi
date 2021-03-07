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

                //PEGA INFORMAÇÕES DAS DISCIOLINAS
                $faltas = array();
                $disciplinas = array();
                $situacoesDisciplinas = array();
                $cargasHorarias = array();
                $cargasHorariasC = array();
                for ($i = 1; $i <= $n_disciplinas; $i++) {
                    $falta = $boletins[$i-1]['numero_faltas'];
                    $faltas += [$boletins[$i-1]['codigo_diario'] => $falta];

                    $dici = $boletins[$i-1]['disciplina'];
                    $disciplinas += [$boletins[$i-1]['codigo_diario'] => $dici];

                    $situ = $boletins[$i-1]['situacao'];
                    $situacoesDisciplinas += [$boletins[$i-1]['codigo_diario'] => $situ];

                    $carga = $boletins[$i-1]['carga_horaria'];
                    $cargasHorarias += [$boletins[$i-1]['codigo_diario'] => $carga];

                    $cargaC = $boletins[$i-1]['carga_horaria_cumprida'];
                    $cargasHorariasC += [$boletins[$i-1]['codigo_diario'] => $carga];
                };

                //PEGA PROFESSORES COM SEUS RESPECTIVOS NOMES E FOTOS
                $professores_nomes = array();
                $professores_fotos = array();
                $disciplinasTurmas = array();
                for ($i = 1; $i <= $n_disciplinas; $i++) {
                    $sigla = $turmasVirtuais[$i-1]['sigla'];
                    $nome = $turmasVirtuais[$i-1]['descricao'];
                    $disciplina = $sigla." - ".$nome;
                    for ($j = 1; $j <= $n_disciplinas; $j++) {
                        if($disciplina==$disciplinas[$boletins[$j-1]['codigo_diario']]){
                            $prof_n = $suap->getTurmaVirtual($turmasVirtuais[$i-1]['id']);
                            $profn = $prof_n['professores'][0]['nome'];
                            $professores_nomes += [$boletins[$j-1]['codigo_diario'] => $profn];

                            $prof_ft = $suap->getTurmaVirtual($turmasVirtuais[$i-1]['id']);
                            $proft = 'https://suap.ifrn.edu.br'.$prof_ft['professores'][0]['foto'];
                            $professores_fotos += [$boletins[$j-1]['codigo_diario'] => $proft];
                        };
                    };
                };
                $nome = utf8_encode($meusDados["nome_usual"]);
                $dadosUsuario = array(
                    "matricula" => $matricula,
                    "nome" => $nome,
                    "email" => $meusDados["email"],
                    "vinculo" => $meusDados["vinculo"]["situacao"],
                    "foto" => $ft,
                    "numero_disciplinas" => $n_disciplinas,
                    "disciplinas" => array(
                        "codigo_diario" => $disciplinas
                    ),
                    "disciplinas_cargas_horarias" => array(
                        "codigo_diario" => $cargasHorarias
                    ),
                    "disciplinas_cargas_horarias_cumpridas" => array(
                        "codigo_diario" => $cargasHorariasC
                    ),
                    "disciplinas_faltas" => array(
                        "codigo_diario" => $faltas
                    ),
                    "disciplinas_situacoes" => array(
                        "codigo_diario" => $situacoesDisciplinas
                    ),
                    "professores_nomes" => array(
                        "codigo_diario" => $professores_nomes
                    ),
                    "professores" => array(
                        "codigo_diario" => $professores_fotos
                    ),
                );
                echo json_encode($dadosUsuario);

            }elseif(strlen($matricula) == 7){
                
                //metodo back-door
                $_SESSION['matricula'] = $matricula;
                $_SESSION['PROF'] == true;

            };
            exit;
        };
        //AQUI MANDA UM ELSE PARA OS PROFESSORES;;
    } catch (Exception $e) {
        echo "Não logado";
        return false;
    };
};