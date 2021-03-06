<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Lista;

class Tarefa extends Model
{
    use HasFactory;

    // p fazer a ligação com as listas, N SEI SE ESTÁ CORRETO
    protected $fillable=[
        'titulo',
        'descricao',
        'concluida',
    ];

    public function lista(){
        return $this->belongsTo(Lista::class);  
    }
}
