<?php

require dirname(__DIR__).'/../vendor/autoload.php';
use Ivmelo\SUAP\SUAP;

$matricula =  $_POST['matricula'];
$senha = $_POST['senha'];

$suap = new SUAP();

try {
    
    $data = $suap->autenticar($matricula, $senha);
        
    if(strlen($matricula) == 14){
            
        $meusDados = $suap->getMeusDados();
        
        //PEGA FOTO
        $f = $meusDados['url_foto_75x100'];
        $ft = 'https://suap.ifrn.edu.br'.$f;

        $periodo = $suap->getMeusPeriodosLetivos();
        $count_periodo = count($periodo)-1;
        $ano = $periodo[$count_periodo]['ano_letivo'];

        //PEGA EMAIL
        $emails = array();
        for ($i = 1; $i <= $n_disciplinas; $i++) {
            $course = $suap->getTurmaVirtual($turmasVirtuais[$i-1]['id']);
            $coursee = $course['professores'][0]['email'];

            $emails += [$turmasVirtuais[$i-1]['id'] => $coursee];
 
        };

        $professores = array();
        for ($i = 1; $i <= $n_disciplinas; $i++) {
            $prof = $suap->getTurmaVirtual($turmasVirtuais[$i-1]['id']);
            $proff = $prof['professores'][0]['matricula'];

            $professores += [$turmasVirtuais[$i-1]['id'] => $proff];
 
        };

        $professores_nomes = array();
        for ($i = 1; $i <= $n_disciplinas; $i++) {
            $prof_n = $suap->getTurmaVirtual($turmasVirtuais[$i-1]['id']);
            $profn = $prof_n['professores'][0]['nome'];

            $professores += [$turmasVirtuais[$i-1]['id'] => $profn];
 
        };

        $_SESSION['senha'] = $senha;
        $_SESSION['matricula'] = $matricula;
        $_SESSION['nome'] = $meusDados["nome_usual"];
        $_SESSION['email_aluno'] = $meusDados["email"];
        $_SESSION['foto_url'] = $ft;
        $_SESSION['disciplinas'] = $turmasVirtuais;
        $_SESSION['emails'] = $emails;
        $_SESSION['professores'] = $professores;
        $_SESSION['professores_nomes'] = $professores_nomes;
        $_SESSION['professores_fotos'] = $professores_fotos;
        $_SESSION['n_disciplinas'] = $n_disciplinas;
        
        header("Location: requerimentos.php");
    }
    elseif(strlen($matricula) == 7){
        
        //metodo back-door
        $_SESSION['matricula'] = $matricula;
        $_SESSION['PROF'] == true;

        header('Location: deferimentos.php');
    };

    //AQUI MANDA UM ELSE PARA OS PROFESSORES;;
} catch (Exception $e) {
    return $e;
}