<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule_doctor_service;
use Illuminate\Support\Facades\Validator;
use DataTables;
use fidpro\builder\Create;

class Schedule_doctor_serviceController extends Controller
{
    public $model   = "Schedule_doctor_service";
    public $folder  = "schedule_doctor_service";
    public $route   = "schedule_doctor_service";

    public $param = [
        'created_at'   =>  '',
        'created_by'   =>  '',
        'day'   =>  'required',
        'kuota_jkn'   =>  '',
        'kuota_non_jkn'   =>  '',
        'par_id'   =>  'required',
        'time_end'   =>  'required',
        'time_start'   =>  'required'
    ];

    function __construct()
    {
        /*
        $this->middleware('permission:18-read', ['only' => ['index']]);
        $this->middleware('permission:18-create', ['only' => ['create','store']]);
        $this->middleware('permission:18-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:18-delete', ['only' => ['destroy']]);
        */
    }

    public function index()
    {
        return $this->themes($this->folder . '.index', null, $this);
    }

    public function get_dataTable(Request $request)
    {
        $data = Schedule_doctor_service::select([
            'id',
            'created_at',
            'created_by',
            'day',
            'kuota_jkn',
            'kuota_non_jkn',
            'par_id',
            'time_end',
            'time_start'
        ]);

        $datatables = DataTables::of($data)->addIndexColumn()->addColumn('action', function ($data) {
            $button = "";
            //if(auth()->user()->can('18-edit')){
            $button = Create::action("<i class=\"fas fa-edit\"></i>", [
                "class"     => "btn btn-primary btn-xs",
                "onclick"   => "set_edit(this)",
                "data-url"  => route($this->route . ".edit", $data->id),
                "ajax-url"  => route($this->route . '.update', $data->id),
                "data-target"  => "page_schedule_doctor_service"
            ]);
            //}

            //if(auth()->user()->can('18-edit')){
            $button .= Create::action("<i class=\"fas fa-trash\"></i>", [
                "class"     => "btn btn-danger btn-xs",
                "onclick"   => "delete_row(this,function(){
                        location.reload();
                    })",
                "x-token"   => csrf_token(),
                "data-url"  => route($this->route . ".destroy", $data->id),
            ]);
            //}
            return $button;
        })
        ->addColumn('doctor_name', function ($data) {
            $doctorName = $data->dokter->employee_ft.$data->dokter->employee_name.$data->dokter->employee_bt;
            return $doctorName;
        })
        ->addColumn('nama_hari', function ($data) {
            return getNamaHari($data->day);
        })->rawColumns(['action']);
        return $datatables->make(true);
    }

    public function create()
    {
        $defaultValue =  array_fill_keys(array_keys($this->param), null);
        $defaultValue["id"] = null;
        $schedule_doctor_service = (object)$defaultValue;
        return view($this->folder . '.form', compact('schedule_doctor_service'));
    }

    public function store(Request $request)
    {
        $valid = $this->form_validasi($request->all());
        if ($valid['code'] != 200) {
            return response()->json([
                'success' => false,
                'message' => $valid['message']
            ]);
        }
        try {
            Schedule_doctor_service::create($valid['data']);
            $resp = [
                'success' => true,
                'message' => 'Data Berhasil Disimpan!',
                'redirect'  => view($this->folder . ".data")->render()
            ];
        } catch (\Exception $e) {
            $resp = [
                'success' => false,
                'message' => 'Data Gagal Disimpan! <br>' . $e->getMessage()
            ];
        }
        return response()->json($resp);
    }

    private function form_validasi($data)
    {
        $validator = Validator::make($data, $this->param);
        //check if validation fails
        if ($validator->fails()) {
            return [
                "code"      => "201",
                "message"   => implode("<br>", $validator->errors()->all())
            ];
        }
        //filter
        $filter = array_keys($this->param);
        $input = array_filter(
            $data,
            fn ($key) => in_array($key, $filter),
            ARRAY_FILTER_USE_KEY
        );
        return [
            "code"      => "200",
            "data"      => $input
        ];
    }

    public function edit(Schedule_doctor_service $schedule_doctor_service)
    {
        return view($this->folder . '.form', compact('schedule_doctor_service'));
    }
    public function update(Request $request, Schedule_doctor_service $schedule_doctor_service)
    {
        $valid = $this->form_validasi($request->all());
        if ($valid['code'] != 200) {
            return response()->json([
                'success' => false,
                'message' => $this->form_validasi($request->all())['message']
            ]);
        }
        try {
            $data = Schedule_doctor_service::findOrFail($schedule_doctor_service->id);
            $data->update($valid['data']);
            $resp = [
                'success' => true,
                'message' => 'Data Berhasil Diupdate!',
                'redirect'  => view($this->folder . ".data")->render()
            ];
        } catch (\Exception $e) {
            $resp = [
                'success' => false,
                'message' => 'Data Gagal Diupdate! <br>' . $e->getMessage()
            ];
        }
        return response()->json($resp);
    }

    public function destroy($id)
    {
        $data = Schedule_doctor_service::findOrFail($id);
        $data->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Dihapus!'
        ]);
    }
}
