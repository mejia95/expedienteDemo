<?php

namespace App\Exports;

use App\Models\Pacientes;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PacientesExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection() 
    {
        return Pacientes::select('nombre', 'primer_apellido', 'segundo_apellido','CURP')->get();
    }

    public function headings(): array
    {
        return [
            'Nombre',
            'Primer Apellido',
            'Segundo Apellido',
            'CURP',
        ];
    }
}
