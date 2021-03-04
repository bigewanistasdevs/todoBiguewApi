<?php

// Route::namespace('Api')->group(function(){

    Route::apiResource('listas', 'App\Http\Controllers\api\ListaController');
    Route::apiResource('tarefas', 'App\Http\Controllers\api\TarefaController');
    
// });

// Route::get('listas', 'Api\\ListaController@index');