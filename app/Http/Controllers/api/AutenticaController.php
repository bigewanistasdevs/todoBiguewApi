<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Ivmelo\SUAP\SUAP;

class AutenticaController extends Controller
{

    public function index()
    {
        echo 'index';
    }

    public function store(Request $request)
    {

        if ( ( isset( $request["matricula"] ) && !empty( $request["matricula"] ) ) && ( isset( $request["senha"] ) && !empty( $request["senha"] ) ) ) {

            $matricula = $request['matricula'];
            
            $senha = $request['senha'];

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
                            $cargasHorariasC += [$boletins[$i]['codigo_diario'] => $cargaC];
                        };
            
                        //PEGA INFORMAÇÕES DOS PROFESSORES
                        $professores_nomes = array();
                        $professores_fotos = array();
                        $professores_emails = array();
                        for ($i = 0; $i < $n_disciplinas; $i++) {
                            $sigla = $turmasVirtuais[$i]['sigla'];
                            $nome = $turmasVirtuais[$i]['descricao'];
                            $disciplina = $sigla." - ".$nome;
                            for ($j = 0; $j < $n_disciplinas; $j++) {
                                if($disciplina==$disciplinas[$boletins[$j]['codigo_diario']]){
                                    $prof = $suap->getTurmaVirtual($turmasVirtuais[$i]['id']);

                                    $profn = $prof['professores'][0]['nome'];
                                    $professores_nomes += [$boletins[$j]['codigo_diario'] => $profn];

                                    $proft = 'https://suap.ifrn.edu.br'.$prof['professores'][0]['foto'];
                                    $professores_fotos += [$boletins[$j]['codigo_diario'] => $proft];

                                    $profe = $prof['professores'][0]['email'];
                                    $professores_emails += [$boletins[$j]['codigo_diario'] => $profe];

                                };
                            };
                        };
            
                        //CRIA O ARQUIVO JSON
                        $dadosUsuario = array(
                            "matricula" => $matricula,
                            "nome" => $meusDados["nome_usual"],
                            "email" => $meusDados["email"],
                            "vinculo" => $meusDados["tipo_vinculo"],
                            "situacao" => $meusDados["vinculo"]["situacao"],
                            "campus" => $meusDados["vinculo"]["campus"],
                            "curso" => $meusDados["vinculo"]["curso"],
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
                            "professores_fotos" => array(
                                "codigo_diario" => $professores_fotos
                            ),
                            "professores_emails" => array(
                                "codigo_diario" => $professores_emails
                            ),
                        );
        
                        echo json_encode($dadosUsuario);
                    
                    //CASO UM PROFESSOR REALIZE O LOGIN
                    } else if ( strlen( $matricula ) == 7 ) {
            
                        $dadosUsuario = array(
                            "matricula" => $matricula,
                            "isProfessor" => true,
                        );
                        
                        echo json_encode($dadosUsuario);
                                
                    };
        
                }
                
            //CASO OCORRA ALGUM ERRO
            } catch ( \Exception $e ) {
        
                echo json_encode(
                    [
                        "error" => "Falha ao tentar fazer login! Verifique sua matrícula e senha, depois tente novamente."
                    ]
                );
                
            }
       
        } else {

            echo json_encode(
                [
                    "error" => "Atenção! Você precisa fornecer a sua matrícula e senha."
                ]
            );
            
        }

    }

}
