<?php

namespace App\Exports;

use App\Models\Data;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB;
class Exports implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
   	
    public function collection()
    {
    	$CPI = DB::table('data')
    			-> select('date_time','cpi')
    			-> get();
        return $CPI;
    }
}
