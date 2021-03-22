<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\ListaController;
use App\Http\Controllers\api\TarefaController;
use GuzzleHttp\Psr7\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('envio-email', function(Request $request){
		
    $user = new stdClass();
    // $user->nome_prof = $request["nome_prof"];
    $user->email_prof = $request["email_prof"];
    // $user->aluno = $request["aluno"];
    $user->assunto = $request["assunto"];
    // $user->corpo = $request["corpo"];

    // retorna uma página p ve a msg do email
    return new \App\Mail\newLaravelTips($user);

    // envia o email
    // \Illuminate\Support\Facades\Mail::send(new \App\Mail\newLaravelTips($user));
    
});