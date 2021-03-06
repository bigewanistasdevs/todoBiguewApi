<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lista;

class ListaController extends Controller
{

    // mostra todos os coisas
    public function index()
    {
        $listas = Lista::all();
        return response()->json($listas);
    }

    // em teoria cria novas listas
    public function store(Request $request)
    {
        Lista::create($request->all());
    }

    // procura por um objeto especifico
    public function show($id)
    {
        // $lista = Lista::findOrFail($id)->where('id', $id)->first();
        // if(!$lista){
        //     return response(['erro'=>'lista ta com deus.']);
        // }
        // return response()->json($lista);

        $lista = Lista::findOrFail($id);
        return response()->json($lista);
    }

    // update é update, muda blablabla
    public function update(Request $request, $id)
    {
        $lista = Lista::findOrFail($id);
        $lista->update($request->all());
    }

    public function destroy($id)
    {
        // $lista = Lista::findOrFail($id)->where('id', $id)->first();
        // if(!$lista){
        //     return response(['erro'=>'lista ta com deus.']);
        // }
        // $lista->delete();
        // return response()->json(['sucesso'=>'lista apagada.']);

        $lista = Lista::findOrFail($id);
        $lista->delete();
    }
}
