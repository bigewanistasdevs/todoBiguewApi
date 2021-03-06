<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lista;

class ListaController extends Controller
{

    /* *
     * 
     * pega todas as listas nao concluidas do 
     * usuario da matricula repassada
     * 
     * tipo: GET
     * url: /api/listas/?matricula={matricula}
     * parametros:
     *  - matricula
     * 
     * */
    public function index(Request $request)
    {
        try {
            
            $listas = Lista::all()
                ->where('matricula', $request['matricula'])
                ->where('concluida', '0');
    
            $listaAux = [];

            foreach( $listas as $dados ):
                array_push(
                    $listaAux,
                    [
                        "id" => $dados->id,
                        "nome" => $dados->nome
                    ]
                );
            endforeach;

            return $listaAux;

        } catch (\Exception $e) {
            
            return  response()->json(
                [
                    'error' => 'Falha ao tentar buscar listas.'
                ]
            );

        }
    }

    /* *
     * 
     * Cadastrar nova lista
     * 
     * tipo: POST
     * url: /api/listas/
     * parametros:
     *  - matricula
     *  - nome
     * 
     * */
    public function store(Request $request)
    {
        try {

            $lista = Lista::create($request->all());

            $listaAuxiliar = json_decode( $lista );

            if ( isset( $listaAuxiliar->id ) && !empty( $listaAuxiliar->id ) ) {
                $response = [
                    'success' => 'Lista cadastrada com sucesso.'
                ];
            } else {
                $response = [
                    'error' => 'Falha ao cadastrar lista.'
                ];
            }

            return response()->json($response);
        
        } catch (\Exception $e) {
            
            return  response()->json(
                [
                    'error' => 'Falha ao tentar cadastrar lista.'
                ]
            );

        }
    }

    /* *
     * 
     * Trazer informações da lista a partir
     * do ID informado
     * 
     * tipo: GET
     * url: /api/listas/{lista}
     * parametros:
     *  - id da lista
     * 
     */
    public function show($id)
    {
        try {

            $lista = Lista::where('concluida', '0')->findOrFail($id);
            
            $listaAuxiliar = json_decode( $lista );
    
            if ( isset( $listaAuxiliar->id ) && !empty( $listaAuxiliar->id ) ) {
                $response = $lista;
            } else {
                $response = json_encode(
                    [
                        'error' => 'Lista não encontrada.'
                    ]
                );
            }
    
            return $response;

        } catch (\Exception $e) {
            
            return  response()->json(
                [
                    'error' => 'Falha ao tentar buscar dados da lista.'
                ]
            );

        }
    }

    /* *
     * 
     * Atualiza as informações da lista
     * 
     * tipo: PUT
     * url: /api/listas/{lista}?nome={nome}
     * parametros:
     *  - id da lista
     *  - nome
     * 
     */
    public function update(Request $request, $id)
    {
        try {

            $lista = Lista::where('concluida', '0')->findOrFail($id);
            $lista->update($request->all());

            $listaAuxiliar = json_decode( $lista );

            if ( isset( $listaAuxiliar->id ) && !empty( $listaAuxiliar->id ) ) {
                $response = $lista;
            } else {
                $response = json_encode(
                    [
                        'error' => 'Lista não encontrada.'
                    ]
                );
            }

            return $response;

        } catch (\Exception $e) {
            
            return  response()->json(
                [
                    'error' => 'Falha ao tentar atualizar lista.'
                ]
            );

        }
    }

    /* *
     * 
     * Apaga a lista
     * 
     * tipo: DELETE
     * url: /api/listas/{lista}?concluida={concluida}
     * parametros:
     *  - id da lista
     *  - concluida
     * 
     */
    public function destroy(Request $request, $id)
    {
        try {

            $lista = Lista::where('concluida', '0')->findOrFail($id);
            $lista->update($request->all());
    
            $listaAuxiliar = json_decode( $lista );
    
            if ( isset( $listaAuxiliar->id ) && !empty( $listaAuxiliar->id ) ) {
                $response = $lista;
            } else {
                $response = json_encode(
                    [
                        'error' => 'Lista não encontrada.'
                    ]
                );
            }
    
            return $response;

        } catch (\Exception $e) {
            
            return  response()->json(
                [
                    'error' => 'Falha ao tentar deletar lista.'
                ]
            );

        }
    }


}
