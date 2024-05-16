<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Antrian_service;
use Illuminate\Support\Facades\Validator;
use DataTables;
use fidpro\builder\Create;

class Antrian_serviceController extends Controller
{
    public $model   = "Antrian_service";
    public $folder  = "antrian_service";
    public $route   = "antrian_service";
    
    public $param = [
'antrian_code'   =>  'required',
'antrian_date'   =>  'required',
'antrian_group_id'   =>  'required',
'antrian_num'   =>  'required',
'antrian_status'   =>  '',
'visit_id'   =>  '',
'visit_id_online'   =>  ''
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
        return $this->themes($this->folder . '.index',null,$this);
    }

    public function get_dataTable(Request $request)
    {
        $data = Antrian_service::select([
'antrian_id',
'antrian_code',
'antrian_date',
'antrian_group_id',
'antrian_num',
'antrian_status',
'visit_id',
'visit_id_online'
]);

        $datatables = DataTables::of($data)->addIndexColumn()->addColumn('action', function ($data) {
            $button= "";
            //if(auth()->user()->can('18-edit')){
                $button = Create::action("<i class=\"fas fa-edit\"></i>",[
                    "class"     => "btn btn-primary btn-xs",
                    "onclick"   => "set_edit(this)",
                    "data-url"  => route($this->route.".edit",$data->antrian_id),
                    "ajax-url"  => route($this->route.'.update',$data->antrian_id),
                    "data-target"  => "page_antrian_service"
                ]);
            //}

            //if(auth()->user()->can('18-edit')){
                $button .= Create::action("<i class=\"fas fa-trash\"></i>",[
                    "class"     => "btn btn-danger btn-xs",
                    "onclick"   => "delete_row(this,function(){
                        location.reload();
                    })",
                    "x-token"   => csrf_token(),
                    "data-url"  => route($this->route.".destroy",$data->antrian_id),
                ]);
            //}
            return $button;
        })->rawColumns(['action']);
        return $datatables->make(true);
    }

    public function create()
    {
        $defaultValue =  array_fill_keys(array_keys($this->param), null);
        $defaultValue["antrian_id"] = null;
        $antrian_service = (object)$defaultValue;
        return view($this->folder . '.form',compact('antrian_service'));
    }

    public function store(Request $request)
    {
        $valid = $this->form_validasi($request->all());
        if($valid['code'] != 200){
            return response()->json([
                'success' => false,
                'message' => $valid['message']
            ]);
        }
        try {
            Antrian_service::create($valid['data']);
            $resp = [
                'success' => true,
                'message' => 'Data Berhasil Disimpan!',
                'redirect'  => view($this->folder.".data")->render()
            ];
        }catch(\Exception $e){
            $resp = [
                'success' => false,
                'message' => 'Data Gagal Disimpan! <br>'.$e->getMessage()
            ];
        }
        return response()->json($resp);
    }

    private function form_validasi($data){
        $validator = Validator::make($data, $this->param);
        //check if validation fails
        if ($validator->fails()) {
            return [
                "code"      => "201",
                "message"   => implode("<br>",$validator->errors()->all())
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

    public function edit(Antrian_service $antrian_service)
    {
        return view($this->folder . '.form', compact('antrian_service'));
    }
    public function update(Request $request, Antrian_service $antrian_service)
    {
        $valid = $this->form_validasi($request->all());
        if($valid['code'] != 200){
            return response()->json([
                'success' => false,
                'message' => $this->form_validasi($request->all())['message']
            ]);
        }
        try {
            $data = Antrian_service::findOrFail($antrian_service->antrian_id);
            $data->update($valid['data']);
            $resp = [
                'success' => true,
                'message' => 'Data Berhasil Diupdate!',
                'redirect'  => view($this->folder.".data")->render()
            ];
        }catch(\Exception $e){
            $resp = [
                'success' => false,
                'message' => 'Data Gagal Diupdate! <br>'.$e->getMessage()
            ];
        }
        return response()->json($resp);
    }

    public function destroy($id)
    {
        $data = Antrian_service::findOrFail($id);
        $data->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Dihapus!'
        ]);
    }
}
