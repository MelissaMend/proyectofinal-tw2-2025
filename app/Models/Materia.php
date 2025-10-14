<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
    //use HasFactory;

    public $timestamps = false;
    protected $table = 'materias';

    protected $fillable = ['nombre'];

    public function materiasXUsuarios(){
        return $this->hasMany(MateriasXUsuarios::class, 'materias_id');
    }

    public function users(){
        return $this->belongsToMany(User::class, 'materias_x_usuarios', 'materias_id', 'users_id');
    }

}
