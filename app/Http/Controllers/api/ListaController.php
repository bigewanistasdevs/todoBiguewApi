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
<<<<<<< HEAD
        try {
            
            $listas = Lista::all()
                ->where('matricula', $request['matricula'])
                ->where('concluida', '0');
    
            return $listas;

        } catch (\Exception $e) {
            
            return  response()->json(
                [
                    'error' => 'Falha ao tentar buscar listas.'
                ]
            );

        }
=======
        $nope = 'Ai não campeão';
        return response()->json($nope);
>>>>>>> 5b080d4f85210ab1f1914c91a01e9b32b89f5440
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
<<<<<<< HEAD
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
=======
        ///$lista = Lista::findOrFail($id)->where('id', $id)->first();
        // if(!$lista){
        //     return response(['erro'=>'lista ta com deus.']);
        // }
        // return response()->json($lista);
        $listas = Lista::all()->where('matricula', $matricula);
        // return response()->json($listas);
        $response = [];
        foreach($listas as $info):
            array_push($response, $info);
        endforeach;
        return $response;

>>>>>>> 5b080d4f85210ab1f1914c91a01e9b32b89f5440
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
