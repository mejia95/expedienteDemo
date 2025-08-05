<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class CatMedicamentosConsultorio extends Model
{
    protected $connection = "mongodb";
    protected $table="CatMedicamentosConsultorio";
    protected $guarded = [];
}
