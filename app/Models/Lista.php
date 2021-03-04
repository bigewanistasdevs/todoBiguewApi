<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lista extends Model
{
    // use HasFactory;

    // testando método de modelo com a tarefa como ligação e com fillable
    protected $fillable=[
        'matricula',
        'nome',
        'concluida'
    ];

    public function tarefas(){
        return $this->hasMany(Tarefa::class);
    }

}


// class Lista extends Authenticatable{
    
// }
