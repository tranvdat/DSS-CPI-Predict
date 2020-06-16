<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use App\Imports\Imports;
use App\Exports\Exports;
use Excel;
use App\Models\Data;
use App\Models\DataPredict;
use App\Imports\ImportDataPredicts;
use Carbon\Carbon;

class PredictController extends Controller
{
    private $data;
    private $datapredict;
    public function __construct(Data $data, DataPredict $datapredict)
    {
        $this->data = $data;
        $this->datapredict = $datapredict;
    }

    public function getPredict()
    {
        Excel::store(new Exports, 'CPI.csv');
        ini_set('max_execution_time', 300);
        shell_exec('python C:\xampp\htdocs\DSS-CPI-Predict\public\py\python.py');
        $count = DB::table('data')->count();
        $train = round($count * 0.75);

        $data = DB::table('data')->get();

        try {
            DB::beginTransaction();
            $listCPI_predict = DB::table('data_predicts')->get();
            //xóa hết data cũ
            foreach ($listCPI_predict as $key => $value) {
                $this->datapredict->findOrfail($value->id)->delete();
            }
            $path = "C:/xampp/htdocs/DSS-CPI-Predict/public/py/Results/CPI_predict.csv";
            Excel::import(new ImportDataPredicts, $path);

            $ID_cpi_predict = $this->datapredict->first()->value('id');

            //chèn data cũ đến phần hết phần train còn phần test chèn sau
            for ($i = 0; $i < $train - 1; $i++) {
                $data_predict[] = $data[$i];
            }
            $this->datapredict->findOrfail($ID_cpi_predict)->delete();

            foreach ($data_predict as $item) {
                $ID_cpi_predict = $ID_cpi_predict + 1;
                $this->datapredict->findOrfail($ID_cpi_predict)->update([
                    'cpi' => $item->cpi
                ]);
            }
            $path2 = "C:/xampp/htdocs/DSS-CPI-Predict/public/py/Results/predict_of_year.text";
            Excel::import(new ImportDataPredicts, $path2);
            DB::commit();
            return response()->json([
                'code' => 200,
                'message' => 'success'
            ], 200);
        } catch (Exception $e) {
            dd($e);
            DB::rollBack();
        }
    }
}
