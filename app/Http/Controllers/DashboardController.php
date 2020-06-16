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

            $data = $this->data->where('id', '>', $getID_data + 1)->get();
            $month_ = $this->data->where('id', '=', $getID_data)->value('date_time');
            // $month_ = \DateTime::createFromFormat("Y-m", $month_)->format('m-Y');
            $month = [];
            for ($i = 1; $i < $haf_count + 12; $i++) {
                $month__ = Carbon::parse($month_)->addMonths($i + 1)->format('m-Y');
                $month[] = [
                    'date_time' => $month__,
                ];
            }
            $data_predict = $this->datapredict->where('id', '>', $getID_data_predict)->get();
        }

        //cpi dự đoán trong 6 tháng tới
        $month_end_ = $this->data->orderBy('id', 'desc')->select('date_time')->get();
        $month_end = [];
        if (file_exists('C:/xampp/htdocs/DSS-CPI-Predict/public/py/Results/predict_of_year.text')) {

            $read = file('C:/xampp/htdocs/DSS-CPI-Predict/public/py/Results/predict_of_year.text');
            $k = 1;
            foreach ($read as $line) {
                $month_end[] = [
                    'date_time' => Carbon::parse($month_end_[0]->date_time)->addMonths($k)->format('m-Y'),
                    'value' => $line,
                ];
                $k = $k + 1;
            }
        }


        return view('dashboard', compact('month_end', 'month', 'data', 'data_predict'));
    }
    public function getTrend()
    {
        return view('trend');
    }
    public function getSuggest()
    {
        return view('suggest');
    }
}
