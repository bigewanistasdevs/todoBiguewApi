<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lista;

class ListaController extends Controller
{

    // rota principal like?? n entendi praq mas ta ai
    public function index()
    {
        $listas = Lista::all();
        return response()->json($listas);

        // return response()->json('ok');

        // $listas = Lista::all();
        // return response()->json($listas);
    }

    // em teoria cria novas listas
    public function store(Request $request)
    {
        Lista::create($request->all());
    }

    // retorna os objetos criados
    public function show($id)
    {
        return Lista::findOrFail($id);
    }

    // update é update, muda blablabla
    public function update(Request $request, $id)
    {
        $lista = Lista::findOrFail($id);
        $lista->update($request->all());
    }

    public function destroy($id)
    {
        $lista = Lista::findOrFail($id);
        $lista->delete();
    }
}
