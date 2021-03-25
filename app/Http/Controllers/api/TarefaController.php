<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tarefa;

class TarefaController extends Controller
{

    /* *
     * 
     * pega todas as tarefas nao concluidas da 
     * lista
     * 
     * tipo: GET
     * url: /api/tarefas/?lista={lista}
     * parametros:
     *  - id da lista
     * 
     * */
    public function index(Request $request)
    {
        try {

            $tarefa = Tarefa::all()
                ->where('lista_id', $request['lista'])
                ->where('concluida', '0');
    
            $tarefaAux = [];

            foreach( $tarefa as $dados ):
                array_push(
                    $tarefaAux,
                    [
                        "id" => $dados->id,
                        "titulo" => $dados->titulo
                    ]
                );
            endforeach;

            return $tarefaAux;

        } catch (\Exception $e) {
           
            return  response()->json(
                [
                    'error' => 'Falha ao buscar tarefas.'
                ]
            );

        }
    }
    
    /* *
     * 
     * Cadastrar nova tarefa
     * 
     * tipo: POST
     * url: /api/tarefas/
     * parametros:
     *  - titulo
     *  - descricao
     *  - id da lista 
     * 
     * */
    public function store(Request $request)
    {
        try {

            $tarefa = Tarefa::create($request->all());

            $tarefaAuxiliar = json_decode( $tarefa );

            if ( isset( $tarefaAuxiliar->id ) && !empty( $tarefaAuxiliar->id ) ) {
                $response = [
                    'success' => 'Tarefa cadastrada com sucesso.'
                ];
            } else {
                $response = [
                    'error' => 'Falha ao cadastrar tarefa. 1'
                ];
            }

            return response()->json($response);

        
        } catch (\Exception $e) {

            return response()->json(
                [
                    'error' => 'Falha ao cadastrar tarefa.',
                ]
            );

        }

    }

    /* *
     * 
     * Trazer informações da tarefa a partir
     * do ID dela
     * 
     * tipo: GET
     * url: /api/tarefas/{tarefas}
     * parametros:
     *  - id da tarefas
     * 
     */
    public function show($id)
    {
        try {

            $tarefa = Tarefa::where('concluida', '0')->findOrFail($id);
            
            $tarefaAuxiliar = json_decode( $tarefa );

            if ( isset( $tarefaAuxiliar->id ) && !empty( $tarefaAuxiliar->id ) ) {
                $response = $tarefa;
            } else {
                $response = json_encode(
                    [
                        'error' => 'Tarefa não encontrada.'
                    ]
                );
            }

            return $response;
            
        } catch (\Exception $e) {
           
            return  response()->json(
                [
                    'error' => 'Falha ao buscar dados da tarefa.'
                ]
            );

        }
    }

    /* *
     * 
     * Atualiza as informações da tarefa
     * 
     * tipo: PUT
     * url: /api/tarefas/{tarefa}?titulo={titulo}&descricao={descricao}
     * parametros:
     *  - id da tarefa
     *  - titulo
     *  - descricao
     * 
     */
    public function update(Request $request, $id)
    {
        try {

            $tarefa = Tarefa::where('concluida', '0')->findOrFail($id);
            $tarefa->update($request->all());

            $tarefaAuxiliar = json_decode( $tarefa );

            if ( isset( $tarefaAuxiliar->id ) && !empty( $tarefaAuxiliar->id ) ) {
                $response = $tarefa;
            } else {
                $response = json_encode(
                    [
                        'error' => 'Tarefa não encontrada.'
                    ]
                );
            }

            return $response;

        } catch (\Exception $e) {
           
            return  response()->json(
                [
                    'error' => 'Falha ao tentar atualizar tarefa.'
                ]
            );

        }
    }

    /* *
     * 
     * Apaga a tarefa
     * 
     * tipo: DELETE
     * url: /api/tarefas/{tarefa}?concluida={concluida}
     * parametros:
     *  - id da tarefa
     *  - concluida
     * 
     */
    public function destroy(Request $request, $id)
    {
        
        try {

            $tarefa = Tarefa::where('concluida', '0')->findOrFail($id);
            $tarefa->update($request->all());

            $tarefaAuxiliar = json_decode( $tarefa );

            if ( isset( $tarefaAuxiliar->id ) && !empty( $tarefaAuxiliar->id ) ) {
                $response = $tarefa;
            } else {
                $response = json_encode(
                    [
                        'error' => 'Tarefa não encontrada.'
                    ]
                );
            }

            return $response;

        } catch (\Exception $e) {
            
            return  response()->json(
                [
                    'error' => 'Falha ao tentar deletar tarefa.'
                ]
            );

        }

    }

}
