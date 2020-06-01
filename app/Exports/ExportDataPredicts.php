<?php

namespace App\Exports;

use App\Models\DataPredict;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportDataPredicts implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DataTrain::all();
    }
}
