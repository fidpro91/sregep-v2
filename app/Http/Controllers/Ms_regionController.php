<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ms_region;
use Illuminate\Support\Facades\Validator;
use DataTables;
use fidpro\builder\Create;

class Ms_regionController extends Controller
{
    public $model   = "Ms_region";
    public $folder  = "ms_region";
    public $route   = "ms_region";

    public $param = [
        'reg_active'   =>  'required',
        'reg_level'   =>  'required',
        'reg_name'   =>  'required',
        'reg_parent'   =>  ''
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
        $data = Ms_region::select([
            'reg_code',
            'reg_active',
            'reg_level',
            'reg_name',
            'reg_parent'
        ]);

        $datatables = DataTables::of($data)->addIndexColumn()->addColumn('action', function ($data) {
            $button = "";
            //if(auth()->user()->can('18-edit')){
            $button = Create::action("<i class=\"fas fa-edit\"></i>", [
                "class"     => "btn btn-primary btn-xs",
                "onclick"   => "set_edit(this)",
                "data-url"  => route($this->route . ".edit", $data->reg_code),
                "ajax-url"  => route($this->route . '.update', $data->reg_code),
                "data-target"  => "page_ms_region"
            ]);
            //}

            //if(auth()->user()->can('18-edit')){
            $button .= Create::action("<i class=\"fas fa-trash\"></i>", [
                "class"     => "btn btn-danger btn-xs",
                "onclick"   => "delete_row(this,function(){
                        location.reload();
                    })",
                "x-token"   => csrf_token(),
                "data-url"  => route($this->route . ".destroy", $data->reg_code),
            ]);
            //}
            return $button;
        })->rawColumns(['action']);
        return $datatables->make(true);
    }

    public function create()
    {
        $defaultValue =  array_fill_keys(array_keys($this->param), null);
        $defaultValue["reg_code"] = null;
        $ms_region = (object)$defaultValue;
        return view($this->folder . '.form', compact('ms_region'));
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
            Ms_region::create($valid['data']);
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

    public function edit(Ms_region $ms_region)
    {
        return view($this->folder . '.form', compact('ms_region'));
    }
    public function update(Request $request, Ms_region $ms_region)
    {
        $valid = $this->form_validasi($request->all());
        if ($valid['code'] != 200) {
            return response()->json([
                'success' => false,
                'message' => $this->form_validasi($request->all())['message']
            ]);
        }
        try {
            $data = Ms_region::findOrFail($ms_region->reg_code);
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
        $data = Ms_region::findOrFail($id);
        $data->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Dihapus!'
        ]);
    }

    public function get_region(Request $request)
    {
        $data = Ms_region::whereRaw("lower(reg_name) like '%".strtolower($request->q)."%'")
                ->selectRaw("reg_code as id,reg_name as text")
                ->get();
        return response()->json($data,200);
    }

    public function get_region_child($id)
    {
        $data = Ms_region::where("reg_parent",$id)
                ->selectRaw("reg_code as id,reg_name as text")
                ->get();
        return response()->json($data,200);
    }
}
