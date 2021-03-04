<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tarefa;

class TarefaController extends Controller
{

    // rota principal like?? n entendi praq mas ta ai
    public function index()
    {
        return Tarefa::all();
    }

    // em teoria cria novas Tarefas
    public function store(Request $request)
    {
        Tarefa::create($request->all());
    }

    // retorna os objetos criados
    public function show($id)
    {
        return Tarefa::findOrFail($id);
    }

    // update é update, muda blablabla
    public function update(Request $request, $id)
    {
        $tarefa = Tarefa::findOrFail($id);
        $tarefa->update($request->all());
    }

    public function destroy($id)
    {
        $tarefa = Tarefa::findOrFail($id);
        $tarefa->delete();
    }
}
