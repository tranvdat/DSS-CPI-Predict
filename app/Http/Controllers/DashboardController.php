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

class DashboardController extends Controller
{
    private $data;
    private $datapredict;
    public function __construct(Data $data, DataPredict $datapredict)
    {
        $this->data = $data;
        $this->datapredict = $datapredict;
    }

    public function getDashboard()
    {
        $data_ = $this->data->all();

        $data = null;
        $data_test = null;
        $data_train = null;
        $data_table_predict = null;
        $data_predict = null;
        if ($data_->first()) {
            $count = count($data_);
            $haf_count = $count / 2;

            $getID_data = $data_[$haf_count]->id;

            $data_predict_ = $this->datapredict->all();

            $getID_data_predict = $data_predict_[$haf_count]->id;

            $data = $this->data->where('id', '>', $getID_data)->get();
            $month_ = $this->data->where('id', '=', $getID_data)->value('date_time');
            // $month_ = \DateTime::createFromFormat("Y-m", $month_)->format('m-Y');
            $month = [];
            for ($i = 1; $i < $haf_count + 6; $i++) {
                $month__ = Carbon::parse($month_)->addMonths($i)->format('m-Y');
                $month[] = [
                    'date_time' => $month__,
                ];
            }
            $data_predict = $this->datapredict->where('id', '>', $getID_data_predict)->get();
            $train = round($count * 0.75);
            $test = $count - $train;

            for ($i = 1; $i < $count; $i++) {
                if ($i < $train)
                    $data_train[] = $data_[$i];
                else
                    $data_test[] = $data_[$i];
            }
        }

        //cpi dự đoán trong 6 tháng tới
        $month_end_ = $this->data->orderBy('id', 'desc')->select('date_time')->get();
        $month_end = [];
        if (file_exists('C:/xampp/htdocs/DSS-CPI-Predict/public/py/Results/predict_six_month.text')) {

            $read = file('C:/xampp/htdocs/DSS-CPI-Predict/public/py/Results/predict_six_month.text');
            $k = 1;
            foreach ($read as $line) {
                $month_end[] = [
                    'date_time' => Carbon::parse($month_end_[0]->date_time)->addMonths($k)->format('m-Y'),
                    'value' => $line,
                ];
                $k = $k + 1;
            }
        }


        return view('dashboard', compact('month_end', 'month', 'data', 'data_predict', 'data_train', 'data_test'));
    }
    public function getData()
    {
        $data = DB::table('data')->get();
        return view('data', compact('data'));
    }
    public function postData(Request $request)
    {
        try {
            if ($request->hasFile('file')) {
                DB::beginTransaction();
                $listCPI = DB::table('data')->get();
                foreach ($listCPI as $key => $value) {
                    $this->data->findOrfail($value->id)->delete();
                }
                $path1 = $request->file('file')->storeAs(
                    'file',
                    'CPI.csv'
                );
                $path = storage_path('app') . '/' . $path1;
                Excel::import(new Imports, $path);
                DB::commit();
                return back();
            } else {
                return back()->with('thatbai', 'Chưa có file');
            }
        } catch (Exception $e) {
            DB::rollBack();
            return back();
        }
    }

    public function getPredict()
    {

        Excel::store(new Exports, 'CPI.csv');
        ini_set('max_execution_time', 300);
        shell_exec('python C:\xampp\htdocs\DSS-CPI-Predict\public\py\CT_cuoi.py');
        $count = DB::table('data')->count();
        $train = round($count * 0.75);

        $data = DB::table('data')->get();


        try {
            DB::beginTransaction();
            $listCPI_predict = DB::table('data_predicts')->get();
            foreach ($listCPI_predict as $key => $value) {
                $this->datapredict->findOrfail($value->id)->delete();
            }
            for ($i = 0; $i < $train - 1; $i++) {
                $data_predict[] = $data[$i];
            }
            foreach ($data_predict as $item) {
                $this->datapredict->create([
                    'cpi' => $item->cpi
                ]);
            }

            $path = "C:/xampp/htdocs/DSS-CPI-Predict/public/py/Results/CPI_predict.csv";
            Excel::import(new ImportDataPredicts, $path);
            DB::commit();
            return response()->json([
                'code' => 200,
                'message' => 'success'
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
        }
    }
}
