<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calificacion extends Model
{
    //use HasFactory;
    public $timestamps = false;

    protected $table = 'calificaciones';

    protected $fillable = [
        'calificacion',
        'materias_x_usuarios_id'
    ];

    public function materiasXUsuarios(){
        return $this->belongsTo(MateriasXUsuario::class, 'materias_x_usuarios_id');
    }

    public function materias(){
        return $this->hasOneThrough(Materia::class, 'id', 'id', 'materias_x_usuarios_id', 'materias_id');
    }

    public function user(){
        return $this->hasOneThrough(User::class, 'id', 'id', 'materias_x_usuarios_id', 'materias_id');
    }
}