<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Ivmelo\SUAP\SUAP;
// use App\Services\PayUService\Exception;


class AutenticaController extends Controller
{

    public function index()
    {
        echo 'opa index';
    }

    public function store(Request $request)
    {

        echo 'opa';

        return true;

        if ( ( isset( $request["matricula"] ) && !empty( $request["matricula"] ) ) && ( isset( $request["senha"] ) && !empty( $request["senha"] ) ) ) {

            $matricula = $request['matricula'];
            
            $senha = $request['senha'];

            echo 'matricula - '.$matricula;
            echo '<br>Senha - '.$senha;

            /*
            try {
                
                $suap = new SUAP();
        
                
                //VERIFICA SE MATRÍCULA E SENHA SÃO VÁLIDAS
                if( $data = $suap->autenticar( $matricula, $senha ) ) {
                        
                    //INFORMA QUE É UM ALUNO QUE ESTÁ FAZENDO O LOGIN
                    if ( strlen( $matricula ) == 14 ) {
                            
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
                        for ( $i = 0; $i < $n_disciplinas; $i++ ) {
                            $falta = $boletins[$i]['numero_faltas'];
                            $faltas += [$boletins[$i]['codigo_diario'] => $falta];
            
                            $dici = $boletins[$i]['disciplina'];
                            $disciplinas += [$boletins[$i]['codigo_diario'] => $dici];
            
                            $situ = $boletins[$i]['situacao'];
                            $situacoesDisciplinas += [$boletins[$i]['codigo_diario'] => $situ];
            
                            $carga = $boletins[$i]['carga_horaria'];
                            $cargasHorarias += [$boletins[$i]['codigo_diario'] => $carga];
            
                            $cargaC = $boletins[$i]['carga_horaria_cumprida'];
                            $cargasHorariasC += [$boletins[$i]['codigo_diario'] => $carga];
                        };
            
                        //PEGA INFORMAÇÕES DOS PROFESSORES
                        $professores_nomes = array();
                        $professores_fotos = array();
                        for ($i = 0; $i < $n_disciplinas; $i++) {
                            $sigla = $turmasVirtuais[$i]['sigla'];
                            $nome = $turmasVirtuais[$i]['descricao'];
                            $disciplina = $sigla." - ".$nome;
                            for ($j = 0; $j < $n_disciplinas; $j++) {
                                if($disciplina==$disciplinas[$boletins[$j]['codigo_diario']]){
                                    $prof_n = $suap->getTurmaVirtual($turmasVirtuais[$i]['id']);
                                    $profn = $prof_n['professores'][0]['nome'];
                                    $professores_nomes += [$boletins[$j]['codigo_diario'] => $profn];
            
                                    $prof_ft = $suap->getTurmaVirtual($turmasVirtuais[$i]['id']);
                                    $proft = 'https://suap.ifrn.edu.br'.$prof_ft['professores'][0]['foto'];
                                    $professores_fotos += [$boletins[$j]['codigo_diario'] => $proft];
                                };
                            };
                        };
            
                        //CRIA O ARQUIVO JSON
                        $dadosUsuario = array(
                            "matricula" => $matricula,
                            "nome" => $meusDados["nome_usual"],
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
        
                        return true;
            
                    //CASO UM PROFESSOR REALIZE O LOGIN
                    } else if ( strlen( $matricula ) == 7 ) {
            
                        $dadosUsuario = array(
                            "matricula" => $matricula,
                            "isProfessor" => true,
                        );
                        
                        echo json_encode($dadosUsuario);
                        
                        return true;
        
                    };
        
                }
                
            //CASO OCORRA ALGUM ERRO
            } catch ( \Exception $e ) {
        
                echo json_encode(
                    [
                        "error" => "Falha ao tentar fazer login! Verifique sua matrícula e senha, depois tente novamente."
                    ]
                );
        
                return false;
        
            }
            */
        } else {

            echo json_encode(
                [
                    "error" => "Atenção! Você precisa fornecer a sua matrícula e senha."
                ]
            );
            
            return false;

        }

    }

}
