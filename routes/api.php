<?php
    
    Route::group(['middleware' => 'cors'], function () {
        Route::apiResource('listas', 'App\Http\Controllers\api\ListaController');
        Route::apiResource('tarefas', 'App\Http\Controllers\api\TarefaController');
        Route::apiResource('autentica', 'App\Http\Controllers\api\AutenticaController');
    });