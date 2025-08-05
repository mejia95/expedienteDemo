<?php
namespace App\Http\Repositorios;
use MongoDB\BSON\Regex;
use MongoDB\BSON\ObjectId;
use App\Models\DependenciasSS;
use App\Models\CatGenero;
use DB;
class CatalogosRepo{

    private $consultorios_pipeline = [
        [
            '$project'=>['_id'=>1,'nombre'=>1,'direccion'=>1]
        ]
    ];
    private $generos_pipeline = [
        [
            '$project'=>['_id'=>1,'GeneroEtiqueta'=>1,'GeneroValor'=>1]
        ]
    ];

    

    public function getGenerosTodos (){
        $pipeline = $this->generos_pipeline;
        $generos = CatGenero::raw(function($coleccion)use($pipeline){
            return $coleccion->aggregate($pipeline);
        });
        return response()->json($generos);
    }

    public function getConsultoriosTodos(){
        $pipeline = $this->consultorios_pipeline;
        $consultorios = DependenciasSS::raw(function($coleccion)use($pipeline){
            return $coleccion->aggregate($pipeline);
        });
        return response()->json($consultorios);
    }
}