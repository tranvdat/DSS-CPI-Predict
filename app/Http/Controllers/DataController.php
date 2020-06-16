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

class DataController extends Controller
{
    private $data;
    private $datapredict;
    public function __construct(Data $data, DataPredict $datapredict)
    {
        $this->data = $data;
        $this->datapredict = $datapredict;
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
}
