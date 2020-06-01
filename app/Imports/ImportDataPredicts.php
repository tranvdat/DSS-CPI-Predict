<?php

namespace App\Imports;

use App\Models\DataPredict;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportDataPredicts implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new DataPredict([
            'cpi' => $row[0],
        ]);
    }
}
