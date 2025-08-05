<?php

namespace App\Models;
use MongoDB\Laravel\Eloquent\Model;
class CatVisualAgudezas extends Model
{
    protected $connection = "mongodb";
    protected $table="CatAgudezaVisual";
}
