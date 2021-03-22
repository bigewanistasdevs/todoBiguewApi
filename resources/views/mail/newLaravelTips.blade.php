<!-- editar esse componente depois q romerito revisar como puxar os dados do prof -->
@component('mail::message')

<h1>{{$user->assunto}}</h1>

<!-- AINDA PRECISA DE UMA FORMA DE PUXAR O CONTEÚDO DA MSG, P N SER PRÉ ESCRITA -->
<p>Caro professor(a) {{$user->nome_prof}}, {{$user->corpo}}.</p>
<br>
<p>ateciosamente, {{$user->aluno}} </p>

@endcomponent