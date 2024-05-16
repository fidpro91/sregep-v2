<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient_surety;
use Illuminate\Support\Facades\Validator;
use DataTables;
use fidpro\builder\Create;

class Patient_suretyController extends Controller
{
    public $model   = "Patient_surety";
    public $folder  = "patient_surety";
    public $route   = "patient_surety";

    public $param = [
        'class_id'   =>  'required',
        'kode_ppk'   =>  '',
        'ppk_rujukan'   =>  '',
        'px_id'   =>  'required',
        'pxsurety_no'   =>  'required',
        'pxsurety_status'   =>  'required',
        'surety_id'   =>  'required'
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
        $data = Patient_surety::select([
            'id',
            'class_id',
            'kode_ppk',
            'ppk_rujukan',
            'px_id',
            'pxsurety_no',
            'pxsurety_status',
            'surety_id'
        ]);

        $datatables = DataTables::of($data)->addIndexColumn()->addColumn('action', function ($data) {
            $button = "";
            //if(auth()->user()->can('18-edit')){
            $button = Create::action("<i class=\"fas fa-edit\"></i>", [
                "class"     => "btn btn-primary btn-xs",
                "onclick"   => "set_edit(this)",
                "data-url"  => route($this->route . ".edit", $data->id),
                "ajax-url"  => route($this->route . '.update', $data->id),
                "data-target"  => "page_patient_surety"
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
        })->rawColumns(['action']);
        return $datatables->make(true);
    }

    public function create()
    {
        $defaultValue =  array_fill_keys(array_keys($this->param), null);
        $defaultValue["id"] = null;
        $patient_surety = (object)$defaultValue;
        return view($this->folder . '.form', compact('patient_surety'));
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
            Patient_surety::create($valid['data']);
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

    public function edit(Patient_surety $patient_surety)
    {
        return view($this->folder . '.form', compact('patient_surety'));
    }
    public function update(Request $request, Patient_surety $patient_surety)
    {
        $valid = $this->form_validasi($request->all());
        if ($valid['code'] != 200) {
            return response()->json([
                'success' => false,
                'message' => $this->form_validasi($request->all())['message']
            ]);
        }
        try {
            $data = Patient_surety::findOrFail($patient_surety->id);
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

    public function get_patient_surety($px_id)
    {
        $data = Patient_surety::from("patient_surety as ps")
                ->join("ms_surety as ms","ps.surety_id","=","ms.surety_id")
                ->select(["ms.surety_id","surety_name","pxsurety_no"])
                ->where("px_id",$px_id)->get()->toArray();
        $default[] = [
            "surety_id"     => 45,
            "surety_name"   => "UMUM/TUNAI",
            "pxsurety_no"   => "1"
        ];
        $data = array_merge($default,$data);
        return response()->json($data,200);
    }

    public function destroy($id)
    {
        $data = Patient_surety::findOrFail($id);
        $data->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Dihapus!'
        ]);
    }
}
