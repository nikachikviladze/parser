<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class DataExport implements FromCollection
{
    private $data;

    public function __construct($data) 
    {
        $this->data = json_decode( json_encode( $data->toArray() ), true );
    }
    public function collection()
    {        
        return collect([ $this->data ]); 
    }
    public function headings(): array
    {
        return [];
    }
}
