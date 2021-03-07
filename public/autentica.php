<?php
// //echo dirname(__DIR__).'/vendor/autoload.php';
require dirname(__DIR__).'/vendor/autoload.php';
// require dirname(__DIR__).'/src/Ivmelo/SUAP/SUAP.php';
use GuzzleHttp\Client;
class SUAP
{
    /**
     * O token de acesso do usuário. Tokens tem 24 horas de validade.
     *
     * @var string Token de acesso.
     */
    private $token;

    /**
     * Endpoint do SUAP.
     *
     * @var string Endpoint de acesso ao suap.
     */
    private $endpoint = 'https://suap.ifrn.edu.br/api/v2/';

    /**
     * Um cliente GuzzleHttp para fazer os requests HTTP.
     *
     * @var GuzzleHttp\Client Cliente GuzzleHttp.
     */
    private $client;

    /**
     * Construtor. Pode ser vazio ou receber um token de acesso.
     *
     * @param string $token Token de acesso.
     */
    public function __construct($token = false)
    {
        if ($token) {
            $this->setToken($token);
        }

        // Create and use a guzzle client instance that will time out after 10 seconds
        $this->client = new Client([
            'timeout'         => 10,
            'connect_timeout' => 10,
        ]);
    }

    /**
     * Autentica o usuário e retorna um token de acesso.
     * Pode-se usar a senha ou chave de acesso do aluno.
     *
     * @param string $username  Matrícula do aluno.
     * @param string $password  Senha do aluno ou chave de acesso do responsável.
     * @param bool   $accessKey Define se o login é por chave de acesso.
     * @param bool   $setToken  Define se deve salvar o token para requests subsequentes.
     *
     * @return array $data Array contendo o token de acesso.
     */
    public function autenticar($username, $password, $accessKey = false, $setToken = true)
    {
        // Se estiver acessando com uma chave de acesso...
        if ($accessKey) {
            $url = $this->endpoint.'autenticacao/acesso_responsaveis/';

            $params = [
                'matricula' => $username,
                'chave'     => $password,
            ];
        } else {
            $url = $this->endpoint.'autenticacao/token/';

            $params = [
                'username' => $username,
                'password' => $password,
            ];
        }

        $response = $this->client->request('POST', $url, [
            'form_params' => $params,
        ]);

        $data = false;

        if ($response->getStatusCode() == 200) {
            // Decodifica a resposta JSON para um array.
            $data = json_decode($response->getBody(), true);

            // Seta o token se solicitado. Padrão é true.
            if ($setToken && isset($data['token'])) {
                $this->setToken($data['token']);
            }
        }

        return $data;
    }

    /**
     * Seta o token para acesso a API.
     *
     * @param string $token Token de acesso.
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * Pega os dados pessoais do aluno autenticado.
     *
     * @return array $data Dados pessoais do aluno.
     */
    public function getMeusDados()
    {
        $url = $this->endpoint.'minhas-informacoes/meus-dados/';

        return $this->doGetRequest($url);
    }

    /**
     * Pega os períodos letivos do aluno autenticado.
     *
     * @return array $data Períodos letivos do aluno.
     */
    public function getMeusPeriodosLetivos()
    {
        $url = $this->endpoint.'minhas-informacoes/meus-periodos-letivos/';

        return $this->doGetRequest($url);
    }

    /**
     * Pega o boletim do aluno autenticado.
     *
     * @param int $year Ano letivo.
     * @param int $term Período letivo.
     *
     * @return array $data Boletim do aluno.
     */
    public function getMeuBoletim($year, $term)
    {
        $url = $this->endpoint.'minhas-informacoes/boletim/'.$year.'/'.$term.'/';

        return $this->doGetRequest($url);
    }

    /**
     * Pega a listagem de turmas do aluno para o período solicitado.
     *
     * @param int $year Ano letivo.
     * @param int $term Período letivo.
     *
     * @return array $data Listagem de turmas do aluno.
     */
    public function getTurmasVirtuais($year, $term)
    {
        $url = $this->endpoint.'minhas-informacoes/turmas-virtuais/'.$year.'/'.$term.'/';

        return $this->doGetRequest($url);
    }

    /**
     * Pega detalhes sobre uma turma especificada.
     *
     * @param int $id Id da turma virtual.
     *
     * @return array $data Detalhes da turma virtual.
     */
    public function getTurmaVirtual($id)
    {
        $url = $this->endpoint.'minhas-informacoes/turma-virtual/'.$id.'/';

        return $this->doGetRequest($url);
    }

    /**
     * Retorna um array com o horário semanal de um aluno.
     *
     * @param int $year Ano letivo.
     * @param int $term Período letivo.
     *
     * @return array $schedule Horário semanal do aluno.
     */
    public function getHorarios($year, $term)
    {
        $classes = $this->getTurmasVirtuais($year, $term);

        $shifts = [];
        $shifts['M'][1]['hora'] = '07:00 - 07:45';
        $shifts['M'][2]['hora'] = '07:45 - 08:30';
        $shifts['M'][3]['hora'] = '08:50 - 09:35';
        $shifts['M'][4]['hora'] = '09:35 - 10:20';
        $shifts['M'][5]['hora'] = '10:30 - 11:15';
        $shifts['M'][6]['hora'] = '11:15 - 12:00';

        $shifts['V'][1]['hora'] = '13:00 - 13:45';
        $shifts['V'][2]['hora'] = '13:45 - 14:30';
        $shifts['V'][3]['hora'] = '14:40 - 15:25';
        $shifts['V'][4]['hora'] = '15:25 - 16:10';
        $shifts['V'][5]['hora'] = '16:30 - 17:15';
        $shifts['V'][6]['hora'] = '17:15 - 18:00';

        $shifts['N'][1]['hora'] = '19:00 - 19:45';
        $shifts['N'][2]['hora'] = '19:45 - 20:30';
        $shifts['N'][3]['hora'] = '20:40 - 21:25';
        $shifts['N'][4]['hora'] = '21:25 - 22:10';

        $schedule = [];
        $schedule[1] = $shifts;
        $schedule[2] = $shifts;
        $schedule[3] = $shifts;
        $schedule[4] = $shifts;
        $schedule[5] = $shifts;
        $schedule[6] = $shifts;
        $schedule[7] = $shifts;

        // Insere os dados da aula (2M12, 3V34) no local apropriado no array.
        foreach ($classes as $class) {
            $horarios = explode(' / ', $class['horarios_de_aula']);

            foreach ($horarios as $horario) {
                // As vezes disciplinas não tem horários. Isso resulta em uma String vazia.
                if ($horario != '') {
                    $day = $horario[0];
                    $shift = $horario[1];

                    $stringSize = strlen($horario);

                    for ($i = 2; $i < $stringSize; $i++) {
                        $slot = $horario[$i];
                        $schedule[$day][$shift][$slot]['aula'] = $class;
                    }
                }
            }
        }

        return $schedule;
    }

    /**
     * Faz um request GET para um endpoint definido.
     *
     * @param string $url Url para fazer o request.
     *
     * @return array $data Dados retornados pela API.
     */
    private function doGetRequest($url)
    {
        $response = $this->client->request('GET', $url, [
            'headers' => [
                'Authorization' => 'JWT '.$this->token,
            ],
        ]);

        $data = false;

        if ($response->getStatusCode() == 200) {
            $data = json_decode($response->getBody(), true);
        }

        return $data;
    }

}
try {
    $suap = new SUAP();
    
    $matricula =  $_POST['matricula'];
    $senha = $_POST['senha'];

    //VERIFICA SE MATRÍCULA E SENHA SÃO VÁLIDAS
    if($suap->autenticar($matricula, $senha)){
        
        //INFORMA QUE É UM ALUNO QUE ESTÁ FAZENDO O LOGIN
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
            for ($i = 0; $i < $n_disciplinas; $i++) {
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
                "nome" => $$meusDados["nome_usual"],
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
            exit;

        //CASO UM PROFESSOR REALIZE O LOGIN
        }elseif(strlen($matricula) == 7){

            $dadosUsuario = array(
                "matricula" => $matricula,
                "isProfessor" => true,
            );
            echo json_encode($dadosUsuario);
            exit;
        };
    };
//CASO OCORRA ALGUM ERRO
} catch (Exception $e) {
    return false;
};