<?php

namespace App\Imports;
use Log;
use App\Models\DependenciasSS;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\ToCollection;
use DB;
class ImportacionesMedicosImport implements ToCollection,WithHeadingRow
{
    public $rows;
   
    public function collection(Collection $collection)
    {
        $this->rows = $collection->filter(function($row) {
            return !empty(array_filter($row->toArray(), function($value) {
                return !is_null($value) && trim($value) !== '';
            }));
        })->values();
    }


    /*public function model(array $row)
    {
        $dependencia_existe =  DependenciasSS::where('nombre',$row['nombre_dependencia'])->first();
        $importacion_medicos = MedicosImportaciones::where('_id',$this->importacion)->first();
        $lista_medicos_importaron = [];
        $lista_medicos_no_importaron = [];
        $registro_se_puede_insertar = false;
        if($dependencia_existe){
            Log::info("Inserta registro");
            Log::info($dependencia_existe->_id);
            Log::info("en la importacion");
            Log::info($this->importacion);
            $registro_se_puede_insertar = true;
        }
        
        if($registro_se_puede_insertar){
            $lista_medicos_importaron [] = ['medico'=>$row['nombre'],'cuenta'=>$row['no_cuenta']];
        }else{
            $lista_medicos_no_importaron [] = ['medico'=>$row['nombre'],'cuenta'=>$row['no_cuenta']];
        }

    }*/

    public function chunkSize(): int
    {
        return 300;
    }
}
